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
use App\Services\IctDetailServices;
use App\Services\PekerjaServices;
use App\Services\IctServices;
use App\Services\SupervisorServices;
use Illuminate\Support\Facades\View;


class IctDetailController extends Controller
{
    protected $userMenu;
    protected $to;
    protected $Detailservices;
    protected $Pekerjaservices;
    protected $Ictservices;
    function __construct(IctDetailServices $services, PekerjaServices $service, IctServices $servicess ){
        $this->Detailservices = $services;
        $this->Ictservices = $servicess;
        $this->Pekerjaservices = $service;
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
        $data['detail'] = $this->Detailservices->getDataDetailRequest($code,NULL,NULL,NULL);
        $data['request'] = $this->Ictservices->detailNoRequest($code);
        $data['pekerja'] = $this->Pekerjaservices->getPekerja();
        $data['request_type'] = Lookup_Refs::Type();
        return ResponseFormatter::success($data,'Successfully Get Data');
     }
    function detailPenugasan($code)
    {
        $data['detail'] = $this->Detailservices->getDataDetailRequest($code, 'NT','RT','T');
        $data['request'] = $this->Ictservices->detailNoRequest($code);
        return ResponseFormatter::success($data,'Successfully Get Data');
    }
    function getAddDetail()
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
        $data['detail'] = $this->Detailservices->getDataDetailRequest($code,NULL,NULL,NULL);
        $data['request'] = $this->Ictservices->detailNoRequest($code);
        return ResponseFormatter::success($data,'Successfully Get Data');
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
                'creation_date'=>now(),
                'created_by' => Auth::user()->usr_id,
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
                'creation_date'=>now(),
                'created_by' => Auth::user()->usr_id,
                'program_name'=>"IctDetail_Save"
            ]);
            return ResponseFormatter::success($dtl,'Successfully Created detail request');
        }
    }
    function edit($ireq,$code)
    {
       $data['request'] = $this->Detailservices->FindDetailRequest($ireq,$code);
       return ResponseFormatter::success($data,'Successfully Get Data');
    }
    function update(Request $request,$ireq,$code)
    {
        $cek = DB::table('ireq_dtl')->where('ireq_id', $code)->where('ireqd_id', $ireq)->first();

        if ($request->image) {
            if ($cek->ireq_attachment) {
                unlink(storage_path('app/public/attachment_request/'.$cek->ireq_attachment));
            }
            $nama_file = time() . '.' . explode('/', explode(':', substr($request->image, 0, strpos($request->image, ';')))[1])[1];
            Storage::disk('attachment_request')->put($nama_file, base64_decode(explode(',', $request->image)[1]));
        } else {
            $nama_file = $cek->ireq_attachment;
        }

        $message = [
            'ireq_type.required' => 'Request type not filled',
            'ireq_remark.required' => 'Remark not filled',
            'invent_code.required' => 'Catalog not filled',
        ];

        $rules = [
            'ireq_type' => 'required',
            'invent_code' => 'required',
            'ireq_remark' => 'required',
        ];

        if ($request->ireq_type == 'P') {
            $message['ireq_qty.required'] = 'Qty not filled';
            $rules['ireq_qty'] = 'required';
        }

        $request->validate($rules, $message);

        $saveDtl = DB::table('ireq_dtl')
            ->where('ireqd_id', $ireq)
            ->where('ireq_id', $code)
            ->update([
                'ireq_type' => $request->ireq_type,
                'invent_code' => $request->invent_code,
                'ireq_qty' => $request->ireq_qty,
                'ireq_attachment' => $nama_file,
                'ireq_remark' => $request->ireq_remark,
                'last_update_date' => now()->copy()->tz('Asia/Jakarta'),
                'last_updated_by' => Auth::user()->usr_id,
                'program_name' => 'IctDetail_Update'
            ]);
            

        return ResponseFormatter::success($saveDtl, 'Successfully Updated detail request');

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
        $detail = $this->Detailservices->dataReport($code);

        $checkLink = Link::where('ireq_id',$code)->whereNull('usr_id')->first();

        if($checkLink){
            $link = $checkLink;
        }else{
            $links = substr(md5(uniqid(rand(1,6))), 0, 6);
            $createLink = Link::create([
                'link_id'   => $links,
                'ireq_id'   =>$code,
                'created_at'=> now(),
            ]);
            $link = Link::where('ireq_id',$code)->whereNull('usr_id')->first();
        }
            $data['norequest'] = $detail[0]->ireq_no;
            $data['htmlContent'] = View::make('pdf.Report_ICT_PerDetail', compact('detail', 'link'))->render();
            return json_encode($data);
    }
    function cetak_excel_sedang_dikerjakan($code){
        $newCreation = Carbon::parse(Carbon::now())->copy()->tz('Asia/Jakarta')->format('d M Y');
        return Excel::download(new IctDetailTabSudahDikerjakanExport($code),'Laporan Ict Request '.$newCreation.'.xlsx');
    }
    function getDetailVerif($code){
        $data['detail'] = $this->Detailservices->getDataDetailRequest($code,NULL,NULL,NULL);
        $data['request'] = $this->Ictservices->detailNoRequest($code);
        return json_encode($data);
    }
    function getDetail($ireqd_id,$ireq_id){
        $data['dtl'] = $this->Detailservices->FindDetailRequest($ireqd_id,$ireq_id);
        $data['status'] = DB::table('VREQ_MST_STATUS')->get();
        $data['pekerja'] = $this->Pekerjaservices->getPekerja();
        return ResponseFormatter::success($data,'Successfully Get Data');
    }
}
