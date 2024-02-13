<?php

namespace App\Http\Controllers;

use App\Models\IctDetail;
use App\Models\Mng_user;
use Illuminate\Support\Facades\Storage;
use App\Models\Link;
use App\Models\Lookup_Refs;
use App\Models\Catalog;
use App\Exports\IctDetailExport;
use App\Exports\IctDetailExportReject;
use App\Exports\IctDetailTabReviewerExport;
use App\Exports\IctDetailTabVerifikasiExport;
use App\Exports\IctDetailTabSudahDikerjakanExport;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Carbon\Carbon;
use App\Helpers\ResponseFormatter;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class IctDetailController extends Controller
{
    protected $userMenu;
    protected $to;
    function __construct(){
        $this->middleware('auth:sanctum')->only('index','getNo_req','save','edit','update','delete','submitRating');
        $this->to = "/ict-request";
        $this->middleware(function ($request, $next) {
          $this->userMenu = Mng_User::menu();
            if($this->userMenu->contains($this->to)){    
                return $next($request);
            } else {
                return response(["message"=>"Cannot Access"],403);
            }
        });
    }
    function index($code)
    {
        $data = IctDetail::getDataDetailRequest($code);
        return response()->json($data);
     }
    function detailPenugasan($code)
    {
        $data = IctDetail::detailRequestForAssignment($code);
        return json_encode($data);
    }
    function getAddDetail() //  for form add request detail
    {
        $ref = Lookup_Refs::Type();

        return response()->json(['ref'=>$ref],200);
    }

    function CatalogRequest($tipereq){
        $catalog = Catalog::select('parent_id','catalog_id','catalog_name',
            DB::raw("CASE WHEN catalog_type = 'N' Then 'false' WHEN catalog_type = 'L' Then 'true' end as catalog_type"))
        ->where('catalog_request_type',$tipereq)
        ->where('catalog_stat','T')
        ->get();
        $tree = $this->parseTreeCatalogRequest($catalog);
        return json_encode($tree);
    }
    function parseTreeCatalogRequest($tree, $root = 0){
        $return = array();
          foreach($tree as $child => $parent) {
            if($parent->parent_id == $root) {
              unset($tree[$child]);
                $return[] = array(
                    'key'=> $parent->catalog_id,
                    'label'     => $parent->catalog_name,
                    'selectable'  => filter_var($parent->catalog_type, FILTER_VALIDATE_BOOLEAN),
                    'children'     => $this->parseTreeCatalogRequest($tree, $parent->catalog_id)
                );
            }
        }
        return empty($return) ? null : $return;    
    }
    function getDetailDone($code)
    {
        $data = IctDetail::detailDone($code);
        return response()->json($data);
    }
    function getNo_req($code)
    {
        $dtl = DB::table('ireq_mst as im')
        ->leftJoin('lookup_refs as lr',function ($join) {
            $join->on('im.ireq_status','lr.lookup_code')
                  ->whereRaw('LOWER(lr.lookup_type) LIKE ? ',[trim(strtolower('ict_status')).'%']);
        })
        ->leftJoin('location_refs as lrs',function ($join) {
            $join->on('im.ireq_loc','lrs.loc_code');
        })
        ->leftJoin('ireq_dtl as id',function ($join) {
            $join->on('im.ireq_id','id.ireq_id');
        })
        ->select(DB::raw("TO_CHAR(im.ireq_date, 'yyyy-MM-dd HH24:MI:SS') AS ireq_date "),'im.ireq_date as request_date','im.ireq_requestor','im.ireq_no as noreq','im.ireq_type','im.ireq_status as cekStatus',
                'lr.lookup_desc as ireq_status','lrs.loc_desc')
         ->where('im.ireq_id',$code)
         ->first();
         return response()->json($dtl);
      
    }
    function save(Request $request,$code)
    {
        if($request->file){
            $nama_file = time().'.'.$request->file->getClientOriginalExtension();
            $request->file->move(public_path('attachment_request'), $nama_file);
        }
        else{
            $nama_file = '';
        }
        if($request->tipereq == 'P'){
            $message = [
                'catalog.required'=>'Catalog not filled',
                'qty.required'=>'Qty not filled',
                'qty.numeric'=>'Qty not filled',
                'ket.required'=>'Remark not filled'
            ];
                $request->validate([
                    'catalog'=>'required',
                    'qty'=>'required|numeric',
                    'ket' => 'required'
                ],$message);

            $dtl = IctDetail::create([
                'ireq_id' => $code,
                'ireq_type' => $request->tipereq,
                'invent_code'=>$request->catalog,
                // 'ireq_desc'=> $request->desk,
                'ireq_qty'=> $request->qty,
                'ireq_remark'=>$request->ket,
                'ireq_attachment'=>$nama_file,
                'creation_date'=>Carbon::parse(Carbon::now())->copy()->tz('Asia/Jakarta')->format('Y-m-d H:i:s'),
                'created_by' => Auth::user()->usr_name,
                'program_name'=>"IctDetail_Save"
            ]);
            return ResponseFormatter::success($dtl,'Successfully Created request');
        } else{
            $message = [
                'ket.required'=>'Remark not filled',
                'catalog.required'=>'Catalog not filled'
            ];
                $request->validate([
                    'ket' => 'required',
                    'catalog'=>'required',
                ],$message);

            $dtl = IctDetail::create([
                'ireq_id' => $code,
                'ireq_type' => $request->tipereq,
                'invent_code'=>$request->catalog,
                // 'ireq_desc'=> $request->desk,
                'ireq_remark'=>$request->ket,
                'ireq_attachment'=>$nama_file,
                'creation_date'=>Carbon::parse(Carbon::now())->copy()->tz('Asia/Jakarta')->format('Y-m-d H:i:s'),
                'created_by' => Auth::user()->usr_name,
                'program_name'=>"IctDetail_Save"
            ]);
            return ResponseFormatter::success($dtl,'Successfully Created detail request');
        }
    }
    function edit($ireq,$code)
    {
       $data = IctDetail::FindDetailRequest($ireq,$code);
        return response()->json($data);
    }
    function update(Request $request,$ireq,$code)
    {
        $cek = DB::table('ireq_dtl')->where('ireq_id',$code)->where('ireqd_id',$ireq)->first();
        if($request->image){
             if($cek->ireq_attachment){
                 unlink(Storage_path('app/public/attachment_request/'.$cek->ireq_attachment));
                }
            $image= $request->image;
            $extension = explode('/', explode(':', substr($image, 0, strpos($image, ';')))[1])[1];
            $replace = substr($image, 0, strpos($image, ',')+1); 
            $fotoo = str_replace($replace, '', $image);
            $foto= str_replace(' ', '+', $fotoo); 
            $nama_file = time().".".$extension;
            Storage::disk('attachment_request')->put($nama_file, base64_decode($foto));
        }
        else{
            $nama_file = $cek->ireq_attachment;
        }
        if($request->ireq_type == 'P'){
            $message = [
                'ireq_type.required'=>'Request type not filled',
                'invent_code.required'=>'Peripheral not filled',
                'ireq_qty.required'=>'Qty not filled',
                'ireq_remark.required'=>'Remark not filled',
                'invent_code.required'=>'Catalog not filled'
            ];
                $request->validate([
                    'ireq_type' => 'required',
                    'invent_code'=>'required',
                    'ireq_qty'=>'required',
                    'ireq_remark' => 'required',
                ],$message);
            
            $saveDtl = DB::table('ireq_dtl')
            ->where('ireqd_id',$ireq)
            ->where('ireq_id',$code)
            ->update([
                'ireq_type'=> $request->ireq_type,
                'invent_code'=>$request->invent_code,
                'ireq_qty'=>$request->ireq_qty,
                'ireq_attachment'=>$nama_file,
                'ireq_remark'=> $request->ireq_remark,
                'last_update_date'=> Carbon::parse(Carbon::now())->copy()->tz('Asia/Jakarta')->format('Y-m-d H:i:s'),
                'last_updated_by'=>Auth::user()->usr_name,
                'program_name'=>"IctDetail_Update"
            ]);

            return ResponseFormatter::success($saveDtl,'Successfully Updated detail request');
        }
        else if($request->ireq_type == 'S'){
            $message = [
                'ireq_type.required'=>'Request type not filled',
                'ireq_remark.required'=>'Remark not filled',
                'invent_code.required'=>'Catalog not filled'
            ];
                $request->validate([
                    'ireq_type' => 'required',
                    'ireq_remark' => 'required',
                    'invent_code'=>'required',
                ],$message);
            
            $saveDtl = DB::table('ireq_dtl')
            ->where('ireqd_id',$ireq)
            ->where('ireq_id',$code)
            ->update([
                'ireq_type'=> $request->ireq_type,
                'invent_code'=>$request->invent_code,
                'ireq_remark'=> $request->ireq_remark,
                'ireq_attachment'=>$nama_file,
                'last_update_date'=> Carbon::parse(Carbon::now())->copy()->tz('Asia/Jakarta')->format('Y-m-d H:i:s'),
                'last_updated_by'=>Auth::user()->usr_name,
                'program_name'=>"IctDetail_Update"
            ]);
            return ResponseFormatter::success($saveDtl,'Successfully Updated detail request');
        }
    }
    function delete($ireqd_id,$code)
    {
        $cek = DB::table('ireq_dtl')->where('ireq_id',$code)->where('ireqd_id',$ireqd_id)->first();
        if($cek->ireq_attachment){
            unlink(Storage_path('app/public/attachment_request/'.$cek->ireq_attachment));
        }
        $deletedDtl = DB::table('ireq_dtl as id')
        ->where('id.ireqd_id',$ireqd_id)
        ->where('id.ireq_id',$code)
        ->delete();
        return ResponseFormatter::success($deletedDtl,'Successfully Deleted detail request');
    }
    function cetak_pdf($code)
    {
        $detail = DB::table('ireq_dtl as id')
        ->select('id.*',DB::raw("(crs.catalog_name ||' - '|| cr.catalog_name) as invent_desc"),'imm.ireq_requestor','imm.ireq_no','llr.lookup_desc as ireq_type',
                'vr.name as ireq_bu',DB::raw("TO_CHAR(imm.ireq_date,' dd Mon YYYY') as ireq_date"),
                'lr.lookup_desc as ireq_status', 'lr.lookup_desc as ireqq_status')
        ->LEFTJOIN('catalog_refs as cr',function ($join) {
            $join->on('id.invent_code','cr.catalog_id');
        })
        ->LEFTJOIN('catalog_refs as crs',function ($join) {
            $join->on('cr.parent_id','crs.catalog_id');
        })
        ->leftjoin('ireq_mst as imm','id.ireq_id','imm.ireq_id')
        ->leftjoin('lookup_refs as llr','id.ireq_type','llr.lookup_code')
        ->leftjoin('lookup_refs as lr','id.ireq_status','lr.lookup_code')
        ->leftjoin('vcompany_refs as vr','imm.ireq_bu','vr.company_code')
        ->where('id.ireq_id',$code)
        ->whereRaw('LOWER(llr.lookup_type) LIKE ? ',[trim(strtolower('req_type')).'%'])
        ->whereRaw('LOWER(lr.lookup_type) LIKE ? ',[trim(strtolower('ict_status')).'%'])
        ->get();
        return view('pdf/Laporan_IctDetailRequest', compact('detail'));
    }
    function cetak_pdff($code)
    {
        $detail = DB::table('ireq_dtl as id')
        ->select('id.*',DB::raw("(crs.catalog_name ||' - '|| cr.catalog_name) as name"),'imm.ireq_requestor','imm.ireq_no','llr.lookup_desc as ireq_type',
                'vr.name as ireq_bu','dr.div_name',DB::raw("TO_CHAR(imm.ireq_date,' dd Mon YYYY') as datee"), DB::raw("TO_CHAR(imm.ireq_date,'HH24:MI') as timee"),
                'lr.lookup_desc as ireq_status', 'lr.lookup_desc as ireqq_status')
        ->LEFTJOIN('catalog_refs as cr',function ($join) {
            $join->on('id.invent_code','cr.catalog_id');
        })
        ->LEFTJOIN('catalog_refs as crs',function ($join) {
            $join->on('cr.parent_id','crs.catalog_id');
        })
        ->leftjoin('ireq_mst as imm','id.ireq_id','imm.ireq_id')
        ->leftjoin('lookup_refs as llr','id.ireq_type','llr.lookup_code')
        ->leftjoin('lookup_refs as lr','id.ireq_status','lr.lookup_code')
        ->leftjoin('vcompany_refs as vr','imm.ireq_bu','vr.company_code')
        ->leftjoin('divisi_refs as dr','imm.ireq_divisi_user','dr.div_id')
        ->where('id.ireq_id',$code)
        ->whereRaw('LOWER(llr.lookup_type) LIKE ? ',[trim(strtolower('req_type')).'%'])
        ->whereRaw('LOWER(lr.lookup_type) LIKE ? ',[trim(strtolower('ict_status')).'%'])
        ->get();
        return view('pdf/tes', compact('detail'));
    }
    function cetak_excel($code)
    {
        $newCreation = Carbon::parse(Carbon::now())->copy()->tz('Asia/Jakarta')->format('d M Y');
        return Excel::download(new IctDetailExport($code),'Laporan ICT request '.$newCreation.'.xlsx');
    }
    function cetak_pdf_tab_reviewer($code)
    {
        $detail = DB::table('ireq_dtl as id')
        ->select('id.*',DB::raw("(crs.catalog_name ||' - '|| cr.catalog_name) as name"),'imm.ireq_requestor','imm.ireq_no','llr.lookup_desc as ireq_type',
                'vr.name as ireq_bu',DB::raw("TO_CHAR(imm.ireq_date,' dd Mon YYYY') as ireq_date"),
                'lr.lookup_desc as ireq_status', 'lr.lookup_desc as ireqq_status')
        ->leftjoin('ireq_mst as imm','id.ireq_id','imm.ireq_id')
        ->LEFTJOIN('catalog_refs as cr',function ($join) {
            $join->on('id.invent_code','cr.catalog_id');
        })
        ->LEFTJOIN('catalog_refs as crs',function ($join) {
            $join->on('cr.parent_id','crs.catalog_id');
        })
        ->leftjoin('lookup_refs as llr','id.ireq_type','llr.lookup_code')
        ->leftjoin('lookup_refs as lr','id.ireq_status','lr.lookup_code')
        ->leftjoin('vcompany_refs as vr','imm.ireq_bu','vr.company_code')
        ->where('id.ireq_id',$code)
        ->whereRaw('LOWER(llr.lookup_type) LIKE ? ',[trim(strtolower('req_type')).'%'])
        ->whereRaw('LOWER(lr.lookup_type) LIKE ? ',[trim(strtolower('ict_status')).'%'])
        ->get();
        return view('pdf/Laporan_IctDetailRequest_Tab_Reviewer', compact('detail'));
    }
    function cetak_excel_tab_reviewer($code)
    {
        $newCreation = Carbon::parse(Carbon::now())->copy()->tz('Asia/Jakarta')->format('d M Y');
        return Excel::download(new IctDetailTabReviewerExport($code),'Laporan Ict request (Detail Reviewer) '.$newCreation.'.xlsx');
    }
    function cetak_pdf_tab_verifikasi($code)
    {
        $detail = DB::table('ireq_dtl as id')
        ->select('id.ireq_type','id.ireq_desc','id.ireq_qty','id.ireq_remark',DB::raw("(crs.catalog_name ||' - '|| cr.catalog_name) as name"),'imm.ireq_requestor','imm.ireq_no','llr.lookup_desc as ireq_type',
                'vr.name as ireq_bu',DB::raw("TO_CHAR(imm.ireq_date,' dd Mon YYYY') as ireq_date"),
                'lr.lookup_desc as ireq_status', 'lr.lookup_desc as ireqq_status')
       ->leftjoin('ireq_mst as imm','id.ireq_id','imm.ireq_id')
       ->LEFTJOIN('catalog_refs as cr',function ($join) {
           $join->on('id.invent_code','cr.catalog_id');
       })
       ->LEFTJOIN('catalog_refs as crs',function ($join) {
           $join->on('cr.parent_id','crs.catalog_id');
       })
        ->leftjoin('lookup_refs as llr','id.ireq_type','llr.lookup_code')
        ->leftjoin('lookup_refs as lr','id.ireq_status','lr.lookup_code')
        ->leftjoin('vcompany_refs as vr','imm.ireq_bu','vr.company_code')
        ->where('id.ireq_id',$code)
        ->whereRaw('LOWER(llr.lookup_type) LIKE ? ',[trim(strtolower('req_type')).'%'])
        ->whereRaw('LOWER(lr.lookup_type) LIKE ? ',[trim(strtolower('ict_status')).'%'])
        ->get();
        return view('pdf/Laporan_IctDetailRequest_Tab_Verifikasi', compact('detail'));
    }
    function cetak_excel_tab_verifikasi($code)
    {
        $newCreation = Carbon::parse(Carbon::now())->copy()->tz('Asia/Jakarta')->format('d M Y');
        return Excel::download(new IctDetailTabVerifikasiExport($code),'Laporan Ict request (Detail Terverifikasi) '.$newCreation.'.xlsx');
    }
    function cetak_pdf_reject($code)
    {
        $detail = DB::table('ireq_dtl as id')
        ->select('id.*','imm.ireq_requestor','imm.ireq_no','llr.lookup_desc as ireq_type',
                'vr.name as ireq_bu',DB::raw("TO_CHAR(imm.ireq_date,' dd Mon YYYY') as ireq_date"),
                'lr.lookup_desc as ireq_status', 'lr.lookup_desc as ireqq_status',DB::raw("(crs.catalog_name ||' - '|| cr.catalog_name) as name"))
        ->leftjoin('ireq_mst as imm','id.ireq_id','imm.ireq_id')
        ->LEFTJOIN('catalog_refs as cr',function ($join) {
            $join->on('id.invent_code','cr.catalog_id');
        })
        ->LEFTJOIN('catalog_refs as crs',function ($join) {
            $join->on('cr.parent_id','crs.catalog_id');
        })
        ->leftjoin('lookup_refs as llr','id.ireq_type','llr.lookup_code')
        ->leftjoin('lookup_refs as lr','id.ireq_status','lr.lookup_code')
        ->leftjoin('vcompany_refs as vr','imm.ireq_bu','vr.company_code')
        ->where('id.ireq_id',$code)
        ->whereRaw('LOWER(llr.lookup_type) LIKE ? ',[trim(strtolower('req_type')).'%'])
        ->whereRaw('LOWER(lr.lookup_type) LIKE ? ',[trim(strtolower('ict_status')).'%'])
        ->get();
        return view('pdf/Laporan_IctDetailReject', compact('detail'));
    }
    function cetak_excel_reject($code)
    {
        $newCreation = Carbon::parse(Carbon::now())->copy()->tz('Asia/Jakarta')->format('d M Y');
        return Excel::download(new IctDetailExportReject($code),'Laporan ICT Request '.$newCreation.'.xlsx');
    }
    function printout_ictrequest($code)
    {
        $detail = DB::table('ireq_dtl as id')
        ->select('imm.ireq_status as cekstatus','id.ireq_type','imm.ireq_id','imm.ireq_no','id.ireq_desc','dr.div_name','id.ireq_qty','mu.usr_fullname',
                'id.ireq_remark','lllr.lookup_desc as prio_level','imm.ireq_requestor','imm.ireq_no','loc_refs.loc_desc as ireq_loc',
                'imm.ireq_verificator_remark','imm.ireq_approver2_remark','llr.lookup_desc as ireq_type', 'vr.name as ireq_bu','imm.ireq_status as status',
                DB::raw("TO_CHAR(imm.ireq_date,' dd Mon YYYY HH24:MI') as date_request"),DB::raw("TO_CHAR(imm.ireq_assigned_date,' dd Mon YYYY') as date_assigned"),
                DB::raw("TO_CHAR(imm.ireq_approver1_date,' dd Mon YYYY HH24:MI') as date_approver1"),'imm.ireq_verificator',
                DB::raw("COALESCE(imm.ireq_assigned_to2,imm.ireq_assigned_to1) AS ireq_assigned_to"),'lr.lookup_desc as ireqq_status',
                DB::raw("TO_CHAR(imm.ireq_approver2_date,' dd Mon YYYY HH24:MI') as date_approver2"),DB::raw("(crs.catalog_name ||' - '|| cr.catalog_name) as name"),
                'lr.lookup_desc as ireq_status',DB::raw("COALESCE(id.ireq_assigned_to2,id.ireq_assigned_to1) AS ireq_assigned_to_detail"),
                DB::raw("TO_CHAR(id.ireq_assigned_date,' dd Mon YYYY') as date_assigned_detail"),'id.ireq_assigned_remark as assigned_remark_detail','imm.ireq_assigned_remark as assigned_remark_request',
                'id.ireqd_id', DB::raw("TO_CHAR(id.ireq_date_closing,' dd Mon YYYY') as date_closing_detail"),DB::raw("TO_CHAR(imm.ireq_date_closing,' dd Mon YYYY') as date_closing_request"))
        ->LEFTJOIN('catalog_refs as cr',function ($join) {
            $join->on('id.invent_code','cr.catalog_id');
        })
        ->LEFTJOIN('catalog_refs as crs',function ($join) {
            $join->on('cr.parent_id','crs.catalog_id');
        })
        ->leftjoin('ireq_mst as imm','id.ireq_id','imm.ireq_id')
        ->leftjoin('location_refs as loc_refs','imm.ireq_loc','loc_refs.loc_code')
        ->leftjoin('divisi_refs as dr','imm.ireq_divisi_user','dr.div_id')
        ->leftjoin('mng_users as mu','dr.div_verificator','mu.usr_email')
        ->leftjoin('lookup_refs as lllr','imm.ireq_prio_level','lllr.lookup_code')
        ->leftjoin('lookup_refs as llr','id.ireq_type','llr.lookup_code')
        ->leftjoin('lookup_refs as lr','id.ireq_status','lr.lookup_code')
        ->leftjoin('vcompany_refs as vr','imm.ireq_bu','vr.company_code')
        ->where('id.ireq_id',$code)
        ->whereRaw('LOWER(lllr.lookup_type) LIKE ? ',[trim(strtolower('req_prio')).'%'])
        ->whereRaw('LOWER(llr.lookup_type) LIKE ? ',[trim(strtolower('req_type')).'%'])
        ->whereRaw('LOWER(lr.lookup_type) LIKE ? ',[trim(strtolower('ict_status')).'%'])
        ->orderBy('id.ireqd_id','ASC')
        ->get();

        $checkLink = Link::where('ireq_id',$code)->whereNull('usr_id')->first();

        if($checkLink){
            $link = $checkLink;
        }else{
            $links = substr(md5(uniqid(rand(1,6))), 0, 6);
            $createLink = Link::create([
                'link_id'=> $links,
                'ireq_id'=>$code
            ]);
            $link = Link::where('ireq_id',$code)->whereNull('usr_id')->first();
        }
            return view('pdf/Report_ICT_PerDetail', compact('detail','link'));
    }
    function cetak_excel_sedang_dikerjakan($code)
    {
        $newCreation = Carbon::parse(Carbon::now())->copy()->tz('Asia/Jakarta')->format('d M Y');
        return Excel::download(new IctDetailTabSudahDikerjakanExport($code),'Laporan Ict Request '.$newCreation.'.xlsx');
    }
    function getDetailVerif($code)
    {
        $data = IctDetail::detailVerification($code);
        return response()->json($data);
    }
    function getDetail($ireqd_id,$ireq_id){
        $data['dtl'] = DB::table('ireq_dtl as id')
        ->select('id.ireq_attachment','id.ireq_assigned_to1','id.ireq_assigned_remark','im.ireq_no','id.ireq_id','id.ireq_note_personnel as ireq_reason','id.ireqd_id','id.ireq_status as status', 
        DB::raw("(crs.catalog_name ||' - '|| cr.catalog_name) as name"),'lr.lookup_desc as ireq_type','id.ireq_qty','id.ireq_remark','id.ireq_assigned_to1_reason')
        ->leftjoin('ireq_mst as im','id.ireq_id','im.ireq_id')
        ->LEFTJOIN('catalog_refs as cr',function ($join) {
            $join->on('id.invent_code','cr.catalog_id');
        })
        ->LEFTJOIN('catalog_refs as crs',function ($join) {
            $join->on('cr.parent_id','crs.catalog_id');
        })
        ->leftJoin('lookup_refs as lr',function ($join) {
            $join->on('id.ireq_type','lr.lookup_code')
                 ->whereRaw('LOWER(lr.lookup_type) LIKE ? ',[trim(strtolower('req_type')).'%']);
        })
        ->where('id.ireqd_id',$ireqd_id)
        ->where('id.ireq_id',$ireq_id)
        ->first();
        $data['status'] = DB::table('VREQ_MST_STATUS')->get();
        return json_encode($data);
    }
}
