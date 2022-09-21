<?php

namespace App\Http\Controllers;

use App\IctDetail;
use App\Ict;
use Illuminate\Support\Facades\Storage;
use App\Link;
use App\Exports\IctDetailExport;
use App\Exports\IctDetailExportReject;
use App\Exports\IctDetailTabReviewerExport;
use App\Exports\IctDetailTabVerifikasiExport;
use App\Exports\IctDetailTabSudahDikerjakanExport;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Jobs\SendNotifPersonnel;
use App\Jobs\SendNotifInProgress;
use App\Jobs\SendNotifDone;

class IctDetailController extends Controller
{
    protected $date;
    protected $newCreation;
    protected $newUpdate;
     public function __construct(){
        $date = Carbon::now();
        $this->date= Carbon::now();
        $this->newCreation =Carbon::parse($date)->copy()->tz('Asia/Jakarta')->format('Y-m-d H:i:s');
        $this->newUpdate = Carbon::parse($date)->copy()->tz('Asia/Jakarta')->format('Y-m-d H:i:s');
    }
    Public function index($code)
    {
        $dtl = DB::table('ireq_dtl as id')
        ->select(DB::raw("COALESCE(id.ireq_assigned_to2,id.ireq_assigned_to1) AS ireq_assigned_to"),'id.ireq_attachment',
         'id.ireq_id','id.ireq_assigned_to1_reason','id.invent_code','id.ireq_assigned_to1','id.ireq_status as status',
         'id.ireq_assigned_to2','id.ireqd_id','lr.lookup_desc as ireq_type','id.ireq_remark',
         'id.ireq_desc', 'id.ireq_qty',DB::raw('COUNT(id.ireq_assigned_to2) as ireq_count_personnel2'),DB::raw('COUNT(id.ireq_assigned_to1_reason) as ireq_count_reason'),DB::raw('COUNT(id.ireq_assigned_to1) as ireq_count_status'),DB::raw("(crs.catalog_name ||' - '|| cr.catalog_name) as name"),'llr.lookup_desc as ireq_status','id.ireq_status as cekStatus')
         ->leftJoin('catalog_refs as cr',function ($join){
            $join->on('id.invent_code','cr.catalog_id');
        })
        ->leftJoin('catalog_refs as crs',function ($join) {
            $join->on('cr.parent_id','crs.catalog_id');
        })
        ->leftJoin('lookup_refs as lr',function ($join) {
            $join->on('id.ireq_type','lr.lookup_code')
                  ->whereRaw('LOWER(lr.lookup_type) LIKE ? ',[trim(strtolower('req_type')).'%']);
        })
        ->leftJoin('lookup_refs as llr',function ($join) {
            $join->on('id.ireq_status','llr.lookup_code')
                  ->whereRaw('LOWER(llr.lookup_type) LIKE ? ',[trim(strtolower('ict_status')).'%']);
        })
        ->where('id.ireq_id',$code)
        ->groupBy(DB::raw("COALESCE(id.ireq_assigned_to2,id.ireq_assigned_to1)"),'id.ireq_attachment',
        'id.ireq_id','id.ireq_assigned_to1_reason','id.invent_code','id.ireq_assigned_to1','id.ireq_status',
        'id.ireq_assigned_to2','id.ireqd_id','lr.lookup_desc','id.ireq_remark',
        'id.ireq_desc', 'id.ireq_qty',DB::raw("(crs.catalog_name ||' - '|| cr.catalog_name)"),'llr.lookup_desc')
        ->orderBy('id.ireqd_id','ASC')
        ->get();
            return response()->json($dtl);
    }
    Public function detailPenugasan($code)
    {
        $dtl = DB::table('ireq_dtl as id')
        ->select('id.ireq_attachment','id.ireq_qty','id.ireq_status as status','id.ireq_remark','id.ireqd_id','id.ireq_note_personnel',
            'lr.lookup_desc as ireq_type','llr.lookup_desc as ireq_status','id.ireq_desc',DB::raw("(crs.catalog_name ||' - '|| cr.catalog_name) as name"),
            DB::raw("COALESCE(id.ireq_assigned_to2,id.ireq_assigned_to1) AS ireq_assigned_to"))
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
        ->leftJoin('lookup_refs as llr',function ($join) {
            $join->on('id.ireq_status','llr.lookup_code')
                  ->whereRaw('LOWER(llr.lookup_type) LIKE ? ',[trim(strtolower('ict_status')).'%']);
        })
        ->where('id.ireq_id',$code)
        ->where(function($query){
            return $query
            ->where('id.ireq_status','NT')
            ->Orwhere('id.ireq_status','RT')
            ->Orwhere('id.ireq_status','T');
        })
        ->orderBy('id.ireqd_id','ASC')
        ->get();
            return response()->json($dtl);
    }
    Public function getDetailDone($code,$usr_fullname)
    {
        $dtl = DB::table('ireq_dtl as id')
        ->select('id.ireq_assigned_to1','id.ireqd_id','lr.lookup_desc as ireq_type', 
                DB::raw("(crs.catalog_name ||' - '|| cr.catalog_name) as name"),'id.ireq_remark','id.ireq_qty','id.ireq_desc')
        ->leftjoin('ireq_mst as imm','id.ireq_id','imm.ireq_id')
        ->LEFTJOIN('catalog_refs as cr',function ($join) {
            $join->on('id.invent_code','cr.catalog_id');
        })
        ->LEFTJOIN('catalog_refs as crs',function ($join) {
            $join->on('cr.parent_id','crs.catalog_id');
        })
        ->leftjoin('lookup_refs as lr','id.ireq_type','lr.lookup_code')
        ->where('imm.ireq_id',$code)
        ->where('id.ireq_assigned_to1',$usr_fullname)
        ->whereRaw('LOWER(lr.lookup_type) LIKE ? ',[trim(strtolower('req_type')).'%'])
        ->orderBy('id.ireqd_id','ASC')
        ->get();
            return response()->json($dtl);
    }
    function abp($ireq_id){ //accept by personnel
        $usr_fullname = Auth::User()->usr_fullname;
        $dtl = DB::table('ireq_dtl')
        ->where('ireq_id',$ireq_id)
        ->where('ireq_assigned_to1',$usr_fullname)
        ->update([
            'ireq_status' => 'T',
            'last_update_date' => $this->newUpdate,
            'last_updated_by' => Auth::user()->usr_name,
            'program_name' => "IctDetailController_abp",
        ]);

        $result = DB::connection('oracle')->getPdo()->exec("begin SP_PENUGASAN_IREQ_MST($ireq_id); end;");
        $cek = $this->cekStatusPenugasan($ireq_id);
        return json_encode($cek);
    }
    function cekStatusPenugasan($ireq_id){
        $ict = DB::table('ireq_dtl as id')
        ->LEFTJOIN('ireq_mst as im','id.ireq_id','im.ireq_id')
        ->LEFTJOIN('catalog_refs as cr',function ($join) {
            $join->on('id.invent_code','cr.catalog_id');
        })
        ->LEFTJOIN('catalog_refs as crs',function ($join) {
            $join->on('cr.parent_id','crs.catalog_id');
        })
        ->LEFTJOIN('lookup_refs as lrfs',function ($join) {
            $join->on('id.ireq_type','lrfs.lookup_code')
                 ->WHERERaw('LOWER(lrfs.lookup_type) LIKE ? ',[trim(strtolower('req_type')).'%']);
        })
        ->LEFTJOIN('vcompany_refs as vr',function ($join) {
            $join->on('im.ireq_bu','vr.company_code');
        })
        ->LEFTJOIN('divisi_refs as dr','im.ireq_divisi_user','dr.div_id')
        ->LEFTJOIN('mng_users as mu','im.ireq_requestor','mu.usr_name')
        ->LEFTJOIN('location_refs as loc','im.ireq_loc','loc.loc_code')
        ->SELECT('loc.loc_email','mu.usr_email','mu.usr_fullname','im.ireq_no','id.ireqd_id','vr.name as ireq_bu','im.ireq_id','dr.div_name', 'mu.usr_name',DB::raw("TO_CHAR(im.ireq_date, 'dd Mon YYYY HH24:MM') as ireq_date"),'im.ireq_requestor',
                'im.ireq_status','im.ireq_user',DB::raw("(crs.catalog_name ||' - '|| cr.catalog_name) as invent_code"),'id.ireq_qty','lrfs.lookup_desc as ireq_type','id.ireq_remark',DB::raw("COALESCE(id.ireq_assigned_to2,id.ireq_assigned_to1) AS ireq_assigned_to"))
        ->WHERE('im.ireq_id',$ireq_id)
        ->WHERE('id.ireq_status','T')
        ->ORDERBY('id.ireqd_id','ASC')
        ->get();

        // $mail = $ict[0]->usr_email .= '@emp.id';
        $mail = 'adhitya.saputro@emp.id';
        SendNotifInProgress::dispatchAfterResponse($mail,$ict);
        $message = 'Success';
        return $message;

    }
    function rbp(Request $request,$ireq_id){ //reject by personnel
        $usr_fullname = Auth::User()->usr_fullname;
        $dtl = DB::table('ireq_dtl')
        ->where('ireq_id',$ireq_id)
        ->where('ireq_assigned_to1',$usr_fullname)
        ->update([
            'ireq_status' => 'RT',
            'ireq_assigned_to1_reason' => $request->ireq_reason,
            'last_update_date' => $this->newUpdate,
            'last_updated_by' => Auth::user()->usr_name,
            'program_name' => "IctDetailController_rbp"
        ]);
        $result = DB::connection('oracle')->getPdo()->exec("begin SP_REJECT_PENUGASAN_IREQ_MST($ireq_id); end;");
        return response()->json('Updated Successfully');
        
    }
    function getNo_req($code)
    {
        $dtl = DB::table('ireq_mst as im')
        ->leftJoin('lookup_refs as lr',function ($join) {
            $join->on('im.ireq_status','lr.lookup_code')
                  ->whereRaw('LOWER(lr.lookup_type) LIKE ? ',[trim(strtolower('ict_status')).'%']);
        })
        ->leftJoin('ireq_dtl as id',function ($join) {
            $join->on('im.ireq_id','id.ireq_id');
        })
        ->select(DB::raw("TO_CHAR(im.ireq_date, 'dd/MM/yyyy') AS ireq_date "),'im.ireq_requestor','im.ireq_no as noreq','im.ireq_type','im.ireq_status as cekStatus',
                'lr.lookup_desc as ireq_status')
         ->where('im.ireq_id',$code)
         ->first();
         return response()->json($dtl);
      
    }
    Public function save(Request $request,$code)
    {
        if($request->file){
            $file= $request->file;
            // $extension = explode('/', explode(':', substr($file, 0, strpos($file, ';')))[1])[1];
            // $replace = substr($file, 0, strpos($file, ',')+1); 
            // $fotoo = str_replace($replace, '', $file);
            // $foto= str_replace(' ', '+', $fotoo); 
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
                'creation_date'=>$this->newCreation,
                'created_by' => Auth::user()->usr_name,
                'program_name'=>"IctDetail_Save"
            ]);
            return response()->json([
                'success' => true,
                'message' => 'Created Successfully '
            ]);
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
                'creation_date'=>$this->newCreation,
                'created_by' => Auth::user()->usr_name,
                'program_name'=>"IctDetail_Save"
            ]);
            return response()->json([
                'success' => true,
                'message' => 'Created Successfully '
            ]);
        }
    }
    Public function edit($ireq,$code)
    {
        $ict = DB::table('ireq_dtl as id')
        ->select('id.ireqd_id','id.ireq_attachment','id.ireq_type','id.invent_code','cr.catalog_name as invent_desc','id.ireq_desc','id.ireq_qty','id.ireq_remark','imm.ireq_no')
        ->leftjoin('catalog_refs as cr','id.invent_code','cr.catalog_id')
        ->leftjoin('ireq_mst as imm','id.ireq_id','imm.ireq_id')
        ->leftjoin('lookup_refs as lr','id.ireq_type','lr.lookup_code')
        ->where('id.ireqd_id',$ireq)
        ->where('id.ireq_id',$code)
        ->whereRaw('LOWER(lr.lookup_type) LIKE ? ',[trim(strtolower('req_type')).'%'])
        ->first();
            return response()->json($ict);
    }
    function update(Request $request,$ireq,$code)
    {
        $cek = DB::table('ireq_dtl')->where('ireq_id',$code)->where('ireqd_id',$ireq)->first();
        if($request->image){
             if($cek->ireq_attachment){
                 unlink(Storage_path('app/public/attachment_request/'.$cek->ireq_attachment));
                }
            // $file= $request->image;
            // $nama_file = time().'.'.$request->image->getClientOriginalExtension();
            // $request->image->move(public_path('attachment_request'), $nama_file);
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
            
            $dtl = DB::table('ireq_dtl')
            ->where('ireqd_id',$ireq)
            ->where('ireq_id',$code)
            ->update([
                'ireq_type'=> $request->ireq_type,
                'invent_code'=>$request->invent_code,
                'ireq_qty'=>$request->ireq_qty,
                'ireq_attachment'=>$nama_file,
                'ireq_remark'=> $request->ireq_remark,
                'last_update_date'=> $this->newUpdate,
                'last_updated_by'=>Auth::user()->usr_name,
                'program_name'=>"IctDetail_Update"
            ]);

            $msg = [
                'success' => true,
                'message' => 'Updated Successfully'
            ];
            return response()->json($msg);
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
            
            $dtl = DB::table('ireq_dtl')
            ->where('ireqd_id',$ireq)
            ->where('ireq_id',$code)
            ->update([
                'ireq_type'=> $request->ireq_type,
                'invent_code'=>$request->invent_code,
                'ireq_remark'=> $request->ireq_remark,
                'ireq_attachment'=>$nama_file,
                'last_update_date'=> $this->newUpdate,
                'last_updated_by'=>Auth::user()->usr_name,
                'program_name'=>"IctDetail_Update"
            ]);

            $msg = [
                'success' => true,
                'message' => 'Updated Successfully'
            ];
            return response()->json($msg);
        }
    }
    Public function delete($ireqd_id,$code)
    {
        $cek = DB::table('ireq_dtl')->where('ireq_id',$code)->where('ireqd_id',$ireqd_id)->first();
        if($cek->ireq_attachment){
            unlink(Storage_path('app/public/attachment_request/'.$cek->ireq_attachment));
        }
        $ict = DB::table('ireq_dtl as id')
        ->where('id.ireqd_id',$ireqd_id)
        ->where('id.ireq_id',$code)
        ->delete();
          return response()->json('Deleted Successfully');
    }
    public function cetak_pdf($code)
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
    public function cetak_pdff($code)
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
    public function cetak_excel($code)
    {
        $newCreation = Carbon::parse($this->date)->copy()->tz('Asia/Jakarta')->format('d M Y');
        return Excel::download(new IctDetailExport($code),'Laporan ICT request '.$newCreation.'.xlsx');
    }
    public function cetak_pdf_tab_reviewer($code)
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
    public function cetak_excel_tab_reviewer($code)
    {
        $newCreation = Carbon::parse($this->date)->copy()->tz('Asia/Jakarta')->format('d M Y');
        return Excel::download(new IctDetailTabReviewerExport($code),'Laporan Ict request (Detail Reviewer) '.$newCreation.'.xlsx');
    }
    public function cetak_pdf_tab_verifikasi($code)
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
    public function cetak_excel_tab_verifikasi($code)
    {
        $newCreation = Carbon::parse($this->date)->copy()->tz('Asia/Jakarta')->format('d M Y');
        return Excel::download(new IctDetailTabVerifikasiExport($code),'Laporan Ict request (Detail Terverifikasi) '.$newCreation.'.xlsx');
    }
    public function cetak_pdf_reject($code)
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
    public function cetak_excel_reject($code)
    {
        $newCreation = Carbon::parse($this->date)->copy()->tz('Asia/Jakarta')->format('d M Y');
        return Excel::download(new IctDetailExportReject($code),'Laporan ICT Request '.$newCreation.'.xlsx');
    }
    public function printout_ictrequest($code)
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
    public function cetak_excel_sedang_dikerjakan($code)
    {
        $newCreation = Carbon::parse($this->date)->copy()->tz('Asia/Jakarta')->format('d M Y');
        return Excel::download(new IctDetailTabSudahDikerjakanExport($code),'Laporan Ict Request '.$newCreation.'.xlsx');
    }
    public function getDetailVerif($code)
    {
        $dtl = DB::table('ireq_dtl as id')
        ->select('id.*','lr.lookup_desc as ireq_type',DB::raw("(crs.catalog_name ||' - '|| cr.catalog_name) as invent_desc"))
        ->leftjoin('lookup_refs as lr','id.ireq_type','lr.lookup_code')
        ->LEFTJOIN('catalog_refs as cr',function ($join) {
            $join->on('id.invent_code','cr.catalog_id');
        })
        ->LEFTJOIN('catalog_refs as crs',function ($join) {
            $join->on('cr.parent_id','crs.catalog_id');
        })
        ->where('id.ireq_id',$code)
        ->whereRaw('LOWER(lr.lookup_type) LIKE ? ',[trim(strtolower('req_type')).'%'])
        ->orderby('id.ireqd_id','ASC')
        ->get();
            return response()->json($dtl);
    }
    public function getDetail($ireqd_id,$ireq_id){
        $dtl = DB::table('ireq_dtl as id')
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
            return json_encode($dtl);
    }
    public function updateAssign(Request $request,$code)
    {
        $dtl = DB::table('ireq_dtl')
        ->where('ireqd_id',$request->ireqd_id)
        ->where('ireq_id',$code)
        ->update([
            'ireq_assigned_to1'=>$request->ireq_assigned_to,
            'last_update_date' =>$this->newUpdate,
            'last_updated_by'=>Auth::user()->usr_name
        ]);
        return response()->json('Updated Successfully');
    }
    public function updateAssignFromReject(Request $request,$code)
    {
        $dtl = DB::table('ireq_dtl')
        ->where('ireqd_id',$code)
        ->where('ireq_id',$request->ireq_id)
        ->update([
            'ireq_assigned_to1_reason'=>$request->ireq_assigned_to1_reason,
            'ireq_assigned_to2' => $request->ireq_assigned_to1,
            'last_update_date' => $this->newUpdate,
            'last_updated_by' => Auth::user()->usr_name
        ]);
        return response()->json('Updated Successfully');
    }
    public function updateStatusDone(Request $request,$code){
        
        $dtl = DB::table('ireq_dtl')
        ->where('ireqd_id',$request->ireqd_id)
        ->where('ireq_id',$code)
        ->update([
            'ireq_status' => $request->status,
            'last_update_date' => $this->newUpdate,
            'ireq_date_done' => $this->newUpdate,
            'last_updated_by' => Auth::user()->usr_name
        ]);
        
        $result = DB::connection('oracle')->getPdo()->exec("begin SP_DONE_IREQ_MST($code); end;");
        $ict = DB::table('ireq_dtl as id')
        ->LEFTJOIN('ireq_mst as im','id.ireq_id','im.ireq_id')
        ->LEFTJOIN('catalog_refs as cr',function ($join) {
            $join->on('id.invent_code','cr.catalog_id');
        })
        ->LEFTJOIN('catalog_refs as crs',function ($join) {
            $join->on('cr.parent_id','crs.catalog_id');
        })
        ->LEFTJOIN('lookup_refs as lrfs',function ($join) {
            $join->on('id.ireq_type','lrfs.lookup_code')
                 ->WHERERaw('LOWER(lrfs.lookup_type) LIKE ? ',[trim(strtolower('req_type')).'%']);
        })
        ->LEFTJOIN('vcompany_refs as vr',function ($join) {
            $join->on('im.ireq_bu','vr.company_code');
        })
        ->LEFTJOIN('divisi_refs as dr','im.ireq_divisi_user','dr.div_id')
        ->LEFTJOIN('mng_users as mu','im.ireq_requestor','mu.usr_name')
        ->LEFTJOIN('location_refs as loc','im.ireq_loc','loc.loc_code')
        ->SELECT('loc.loc_email','mu.usr_email','mu.usr_fullname','im.ireq_no','id.ireqd_id','vr.name as ireq_bu','im.ireq_id','dr.div_name', 'mu.usr_name',DB::raw("TO_CHAR(im.ireq_date, 'dd Mon YYYY HH24:MM') as ireq_date"),'im.ireq_requestor',
                'im.ireq_status','im.ireq_user',DB::raw("(crs.catalog_name ||' - '|| cr.catalog_name) as invent_code"),'id.ireq_qty','lrfs.lookup_desc as ireq_type','id.ireq_remark',DB::raw("COALESCE(id.ireq_assigned_to2,id.ireq_assigned_to1) AS ireq_assigned_to"))
        ->WHERE('im.ireq_id',$code)
        ->WHERE('id.ireq_status','D')
        ->ORDERBY('id.ireqd_id','ASC')
        ->get();

        // $mail = $ict[0]->usr_email .= '@emp.id';
        $mail = 'adhitya.saputro@emp.id';
        SendNotifDone::dispatchAfterResponse($mail,$ict);
        return response()->json('Updated Successfully');
    }
    public function updateNote(Request $request,$code){
        
        $dtl = DB::table('ireq_dtl')
        ->where('ireqd_id',$code)
        ->where('ireq_id',$request->ireq_id)
        ->update([
            'ireq_note_personnel' => $request->ireq_reason,
            'last_update_date' => $this->newUpdate,
            'last_updated_by' => Auth::user()->usr_name
        ]);

        return response()->json(['message'=>'Updated Successfully'],200);
    }
    public function updateStatusClosingDetail($ireqd_id,$ireq_id){
        
        $dtl = DB::table('ireq_dtl')
        ->where('ireqd_id',$ireqd_id)
        ->where('ireq_id',$ireq_id)
        ->update([
            'ireq_status' => 'C',
            'ireq_date_closing'=> $this->newUpdate,
            'last_update_date' => $this->newUpdate,
            'last_updated_by' => Auth::user()->usr_name,
            'program_name' => "IctDetail_updateStatusClosingDetail"
        ]);
        $result = DB::connection('oracle')->getPdo()->exec("begin SP_CLOSING_IREQ_MST($ireq_id); end;");
        return response()->json('Updated Successfully');
    }
    public function appd($ireqd_id,$code){ 
        $dtl = DB::table('ireq_dtl')
        ->where('ireqd_id',$ireqd_id)
        ->where('ireq_id',$code)
        ->update([
            'ireq_status' => 'T',
            'last_update_date' => $this->newUpdate,
            'ireq_assigned_date' => $this->newUpdate,
            'last_updated_by' => Auth::user()->usr_name,
            'program_name' => "IctDetail_appd"
        ]);
        $personel = IctDetail::select('ireq_assigned_to2')->where('ireq_id',$code)->where('ireqd_id',$ireqd_id)->pluck('ireq_assigned_to2');
        $ict = Ict::where('ireq_id',$code)->first();
        $mail = DB::table('mng_users')->SELECT('usr_email')->WHERE('usr_fullname',$personel)->first();
        $result = DB::connection('oracle')->getPdo()->exec("begin SP_PENUGASAN_IREQ_MST($code); end;");
        $email = $mail->usr_email .= '@emp.id';
        SendNotifPersonnel::dispatchAfterResponse($email,$ict);
        $cek = $this->cekStatusPenugasan($code);
        return json_encode($cek);
    }
    function submitRating(Request $request){
        if($request->rating <= '2'){
            $dtl = DB::table('ireq_dtl')
            ->where('ireqd_id',$request->id)
            ->where('ireq_id',$request->ireq_id)
            ->update([
                'ireq_value' => $request->rating,
                'ireq_note' => $request->ket
            ]);
            
            return response()->json('Updated Successfully');
        }
        else{
            $dtl = DB::table('ireq_dtl')
            ->where('ireqd_id',$request->id)
            ->where('ireq_id',$request->ireq_id)
            ->update([
                'ireq_value' => $request->rating
            ]);
            
            return response()->json('Updated Successfully');
        }
    }
    function saveRemark(Request $request,$code){
        $dtl = DB::table('ireq_dtl')
        ->where('ireqd_id',$code)
        ->where('ireq_id',$request->ireq_id)
        ->update([
            'ireq_assigned_remark' => $request->ireq_assigned_remark,
            'last_update_date' => $this->newUpdate,
            'last_updated_by' => Auth::user()->usr_name
        ]);

        return response()->json(['message'=>'Updated Successfully'],200);
    }
}
