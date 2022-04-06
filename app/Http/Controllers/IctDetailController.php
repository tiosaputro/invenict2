<?php

namespace App\Http\Controllers;

use App\IctDetail;
use App\Ict;
use App\Exports\IctDetailExport;
use App\Exports\IctDetailExportReject;
use App\Exports\IctDetailTabReviewerExport;
use App\Exports\IctDetailTabVerifikasiExport;
use App\Exports\IctDetailTabSudahDikerjakanExport;
use Illuminate\Support\Facades\DB;
use Excel;
use Carbon\Carbon;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class IctDetailController extends Controller
{
     function __construct(){
        $date = Carbon::now();
        $this->date= Carbon::now();
        $this->newCreation =Carbon::parse($date)->copy()->tz('Asia/Jakarta')->format('Y-m-d H:i:s');
        $this->newUpdate = Carbon::parse($date)->copy()->tz('Asia/Jakarta')->format('Y-m-d H:i:s');
    }
    Public function index($code)
    {
        $dtl = DB::table('ireq_dtl as id')
        ->select('id.invent_code','id.ireq_assigned_to','id.ireqd_id','lr.lookup_desc as ireq_type','im.invent_desc','id.ireq_remark','id.ireq_desc', 'id.ireq_qty',
        DB::raw("(im.invent_code ||'-'|| im.invent_desc) as name"),'llr.lookup_desc as ireq_status')
        ->leftjoin('invent_mst as im','id.invent_code','im.invent_code')
        ->leftjoin('lookup_refs as lr','id.ireq_type','lr.lookup_code')
        ->leftJoin('lookup_refs as llr',function ($join) {
            $join->on('id.ireq_status','llr.lookup_code')
                  ->whereRaw('LOWER(llr.lookup_type) LIKE ? ',[trim(strtolower('ict_status')).'%']);
        })
        ->where('id.ireq_id',$code)
        ->whereRaw('LOWER(lr.lookup_type) LIKE ? ',[trim(strtolower('req_type')).'%'])
        ->orderBy('id.ireqd_id','ASC')
        ->get();

            return json_encode($dtl);
    }
    Public function detailPenugasan($code)
    {
        $dtl = DB::table('ireq_dtl as id')
        ->select('id.ireq_qty','id.ireq_remark','id.ireq_assigned_to','id.ireqd_id',
            'lr.lookup_desc as ireq_type','llr.lookup_desc as ireq_status','id.ireq_desc',
            DB::raw("(im.invent_code ||'-'|| im.invent_desc) as name"))
        ->leftjoin('invent_mst as im','id.invent_code','im.invent_code')
        ->leftjoin('lookup_refs as lr','id.ireq_type','lr.lookup_code')
        ->leftjoin('lookup_refs as llr','id.ireq_status','llr.lookup_code')
        ->where('id.ireq_id',$code)
        ->where('id.ireq_status','T')
        ->whereRaw('LOWER(lr.lookup_type) LIKE ? ',[trim(strtolower('req_type')).'%'])
        ->whereRaw('LOWER(llr.lookup_type) LIKE ? ',[trim(strtolower('ict_status')).'%'])
        ->orderBy('id.ireqd_id','ASC')
        ->get();
            return json_encode($dtl);
    }
    Public function getDetailDone($code,$usr_fullname)
    {
        $dtl = DB::table('ireq_dtl as id')
        ->select('id.invent_code','id.ireq_assigned_to','id.ireqd_id','lr.lookup_desc as ireq_type','im.invent_desc',
        DB::raw("CASE WHEN id.ireq_status = 'A' Then 'Approved' WHEN id.ireq_status = 'T' Then 'Penugasan' WHEN id.ireq_status = 'R' Then 'Reject' WHEN id.ireq_status = 'D' Then 'Done' WHEN id.ireq_status = 'C' Then 'Close' WHEN id.ireq_status = 'P' Then 'Permohonan' end as ireq_status "))
        ->leftjoin('invent_mst as im','id.invent_code','im.invent_code')
        ->leftjoin('lookup_refs as lr','id.ireq_type','lr.lookup_code')
        ->where('id.ireq_id',$code)
        ->where('id.ireq_assigned_to',$usr_fullname)
        ->whereRaw('LOWER(lr.lookup_type) LIKE ? ',[trim(strtolower('req_type')).'%'])
        ->get();
            return json_encode($dtl);
    }
    Public function getNo_req($code)
    {
        $dtl = DB::table('ireq_mst as im')
        ->select('im.ireq_no as noreq','im.ireq_type','im.ireq_status as cekStatus',
                DB::raw("CASE WHEN im.ireq_status = 'NA1' Then 'Menunggu Diapprove Atasan' WHEN im.ireq_status = 'NA2' Then 'Menunggu Diapprove Manager' WHEN im.ireq_status = 'A1' Then 'Approved By Atasan' WHEN im.ireq_status = 'A2' Then 'Approved By ICT Manager'
         WHEN im.ireq_status = 'T' Then 'Penugasan' WHEN im.ireq_status = 'RR' Then 'Reject By Reviewer' WHEN im.ireq_status = 'RA1' Then 'Reject By Atasan' WHEN im.ireq_status = 'RA2' Then 'Reject By ICT Manager' WHEN im.ireq_status = 'D' Then 'Done' WHEN im.ireq_status = 'C' Then 'Close' WHEN im.ireq_status = 'P' Then 'Permohonan' end as ireq_status "))
         ->where('im.ireq_id',$code)
         ->first();
         return response()->json($dtl);
      
    }
    Public function save(Request $request,$code)
    {
        if($request->tipereq == 'P'){
            $message = [
                'invent_code.required'=>'Nama Peripheral Belum Diisi',
                'qty.required'=>'Qty Belum diisi',
                'qty.numeric'=>'Qty Belum diisi',
                'ket.required'=>'Keterangan Belum Diisi'
            ];
                $request->validate([
                    'invent_code'=>'required',
                    'qty'=>'required|numeric',
                    'ket' => 'required'
                ],$message);

            $dtl = IctDetail::create([
                'ireq_id' => $code,
                'ireq_type' => $request->tipereq,
                'invent_code'=>$request->invent_code,
                'ireq_desc'=> $request->desk,
                'ireq_qty'=> $request->qty,
                'ireq_remark'=>$request->ket,
                'creation_date'=>$this->newCreation,
                'created_by' => Auth::user()->usr_name,
                'program_name'=>"IctDetail_Save"
            ]);
            return json_encode([
                'success' => true,
                'message' => 'Created Successfully '
            ]);
        } else{
            $message = [
                'ket.required'=>'Keterangan Belum Diisi'
            ];
                $request->validate([
                    'ket' => 'required'
                ],$message);

            $dtl = IctDetail::create([
                'ireq_id' => $code,
                'ireq_type' => $request->tipereq,
                'ireq_desc'=> $request->desk,
                'ireq_remark'=>$request->ket,
                'creation_date'=>$this->newCreation,
                'created_by' => Auth::user()->usr_name,
                'program_name'=>"IctDetail_Save"
            ]);
            return json_encode([
                'success' => true,
                'message' => 'Created Successfully '
            ]);
        }
    }
    Public function edit($ireq)
    {
        $ict = DB::table('ireq_dtl as id')
        ->select('id.ireqd_id','id.ireq_type','id.invent_code','id.ireq_desc','id.ireq_qty','id.ireq_remark','im.invent_photo as photo','imm.ireq_no')
        ->leftjoin('invent_mst as im','id.invent_code','im.invent_code')
        ->leftjoin('ireq_mst as imm','id.ireq_id','imm.ireq_id')
        ->leftjoin('lookup_refs as lr','id.ireq_type','lr.lookup_code')
        ->where('id.ireqd_id',$ireq)
        ->whereRaw('LOWER(lr.lookup_type) LIKE ? ',[trim(strtolower('req_type')).'%'])
        ->first();
            return json_encode($ict);
    }
    Public function update(Request $request,$ireq)
    {
        if($request->ireq_type == 'P'){
        $message = [
            'ireq_type.required'=>'Tipe Request Belum Diisi',
            'invent_code.required'=>'Nama Peripheral Belum Diisi',
            'ireq_qty.required'=>'Qty Belum diisi',
            'ireq_remark.required'=>'Keterangan Belum Diisi'
        ];
            $request->validate([
                'ireq_type' => 'required',
                'invent_code'=>'required',
                'ireq_qty'=>'required',
                'ireq_remark' => 'required'
            ],$message);
        
        $dtl = IctDetail::find($ireq);

        $dtl->ireq_type = $request->ireq_type;
        $dtl->invent_code = $request->invent_code;
        $dtl->ireq_desc = $request->ireq_desc;
        $dtl->ireq_qty = $request->ireq_qty;
        $dtl->ireq_remark = $request->ireq_remark;
        $dtl->last_update_date = $this->newUpdate;
        $dtl->last_updated_by = Auth::user()->usr_name;
        $dtl->program_name = "IctDetail_Update";
        $dtl->save();
        $msg = [
            'success' => true,
            'message' => 'Updated Successfully'
        ];
        return json_encode($msg);

    }else{
        $message = [
            'ireq_type.required'=>'Tipe Request Belum Diisi',
            'ireq_remark.required'=>'Keterangan Belum Diisi'
        ];
            $request->validate([
                'ireq_type' => 'required',
                'ireq_remark' => 'required'
            ],$message);
        
        $dtl = IctDetail::find($ireq);

        $dtl->ireq_type = $request->ireq_type;
        $dtl->ireq_desc = $request->ireq_desc;
        $dtl->ireq_remark = $request->ireq_remark;
        $dtl->last_update_date = $this->newUpdate;
        $dtl->last_updated_by = Auth::user()->usr_name;
        $dtl->program_name = "IctDetail_Update";
        $dtl->save();
        $msg = [
            'success' => true,
            'message' => 'Updated Successfully'
        ];
    }
    }
    Public function delete($ireqd_id)
    {
        $ict = IctDetail::find($ireqd_id);
         $ict->delete();
          return json_encode('Deleted Successfully');
    }
    public function cetak_pdf($code)
    {
        $detail = DB::table('ireq_dtl as id')
        ->select('id.*','im.invent_desc','imm.ireq_requestor','imm.ireq_no','llr.lookup_desc as ireq_type',
                'vr.name as ireq_bu',DB::raw("TO_CHAR(imm.ireq_date,' dd Mon YYYY') as ireq_date"),
                'lr.lookup_desc as ireq_status', 'lr.lookup_desc as ireqq_status',DB::raw("(im.invent_code ||'-'|| im.invent_desc) as name"))
        ->leftjoin('invent_mst as im','id.invent_code','im.invent_code')
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
    public function cetak_excel($code)
    {
        $newCreation = Carbon::parse($this->date)->copy()->tz('Asia/Jakarta')->format('d M Y');
        return Excel::download(new IctDetailExport($code),'Laporan ICT request '.$newCreation.'.xlsx');
    }
    public function cetak_pdf_tab_reviewer($code)
    {
        $detail = DB::table('ireq_dtl as id')
        ->select('id.*','im.invent_desc','imm.ireq_requestor','imm.ireq_no','llr.lookup_desc as ireq_type',
                'vr.name as ireq_bu',DB::raw("TO_CHAR(imm.ireq_date,' dd Mon YYYY') as ireq_date"),
                'lr.lookup_desc as ireq_status', 'lr.lookup_desc as ireqq_status',DB::raw("(im.invent_code ||'-'|| im.invent_desc) as name"))
        ->leftjoin('invent_mst as im','id.invent_code','im.invent_code')
        ->leftjoin('ireq_mst as imm','id.ireq_id','imm.ireq_id')
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
        ->select('id.*','im.invent_desc','imm.ireq_requestor','imm.ireq_no','llr.lookup_desc as ireq_type',
                'vr.name as ireq_bu',DB::raw("TO_CHAR(imm.ireq_date,' dd Mon YYYY') as ireq_date"),
                'lr.lookup_desc as ireq_status', 'lr.lookup_desc as ireqq_status',DB::raw("(im.invent_code ||'-'|| im.invent_desc) as name"))
        ->leftjoin('invent_mst as im','id.invent_code','im.invent_code')
        ->leftjoin('ireq_mst as imm','id.ireq_id','imm.ireq_id')
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
        ->select('id.*','im.invent_desc','imm.ireq_requestor','imm.ireq_no','llr.lookup_desc as ireq_type',
                'vr.name as ireq_bu',DB::raw("TO_CHAR(imm.ireq_date,' dd Mon YYYY') as ireq_date"),
                'lr.lookup_desc as ireq_status', 'lr.lookup_desc as ireqq_status',DB::raw("(im.invent_code ||'-'|| im.invent_desc) as name"))
        ->leftjoin('invent_mst as im','id.invent_code','im.invent_code')
        ->leftjoin('ireq_mst as imm','id.ireq_id','imm.ireq_id')
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
    public function cetak_pdf_sedang_dikerjakan($code)
    {
        $detail = DB::table('ireq_dtl as id')
        ->select('id.*','im.invent_desc','imm.ireq_requestor','imm.ireq_no','llr.lookup_desc as ireq_type',
                'vr.name as ireq_bu',DB::raw("TO_CHAR(imm.ireq_date,' dd Mon YYYY') as ireq_date"),
                'lr.lookup_desc as ireq_status', 'lr.lookup_desc as ireqq_status',DB::raw("(im.invent_code ||'-'|| im.invent_desc) as name"))
        ->leftjoin('invent_mst as im','id.invent_code','im.invent_code')
        ->leftjoin('ireq_mst as imm','id.ireq_id','imm.ireq_id')
        ->leftjoin('lookup_refs as llr','id.ireq_type','llr.lookup_code')
        ->leftjoin('lookup_refs as lr','id.ireq_status','lr.lookup_code')
        ->leftjoin('vcompany_refs as vr','imm.ireq_bu','vr.company_code')
        ->where('id.ireq_id',$code)
        ->whereRaw('LOWER(llr.lookup_type) LIKE ? ',[trim(strtolower('req_type')).'%'])
        ->whereRaw('LOWER(lr.lookup_type) LIKE ? ',[trim(strtolower('ict_status')).'%'])
        ->orderBy('id.ireqd_id','ASC')
        ->get();
        return view('pdf/Laporan_IctDetailSedangDikerjakan', compact('detail'));
    }
    public function cetak_excel_sedang_dikerjakan($code)
    {
        $newCreation = Carbon::parse($this->date)->copy()->tz('Asia/Jakarta')->format('d M Y');
        return Excel::download(new IctDetailTabSudahDikerjakanExport($code),'Laporan Ict Request '.$newCreation.'.xlsx');
    }
    public function getDetailVerif($code)
    {
        $dtl = DB::table('ireq_dtl as id')
        ->select('id.*','lr.lookup_desc as ireq_type','im.invent_desc')
        ->leftjoin('invent_mst as im','id.invent_code','im.invent_code')
        ->leftjoin('lookup_refs as lr','id.ireq_type','lr.lookup_code')
        ->where('id.ireq_id',$code)
        ->whereRaw('LOWER(lr.lookup_type) LIKE ? ',[trim(strtolower('req_type')).'%'])
        ->get();
            return json_encode($dtl);
    }
    public function getDetail($ireqd_id){
        $dtl = DB::table('ireq_dtl as id')
        ->select('id.ireq_assigned_to','im.ireq_no','id.ireqd_id','id.ireq_status as status', DB::raw("(iim.invent_code ||'-'|| iim.invent_desc) as name"))
        ->leftjoin('ireq_mst as im','id.ireq_id','im.ireq_id')
        ->leftjoin('invent_mst as iim','id.invent_code','iim.invent_code')
        ->where('id.ireqd_id',$ireqd_id)
        ->first();
            return json_encode($dtl);
    }
    public function updateAssign(Request $request,$code)
    {
        
        $dtl = IctDetail::find($request->ireqd_id);
        $dtl->ireq_assigned_to = $request->ireq_assigned_to;
        $dtl->last_update_date = $this->newUpdate;
        $dtl->last_updated_by = Auth::user()->usr_name;
        $dtl->save();
        return json_encode('Updated Successfully');
    }
    public function updateStatusDone(Request $request,$code){
        
        $dtl = IctDetail::find($request->ireqd_id);
        $dtl->ireq_status = $request->status;
        $dtl->last_update_date = $this->newUpdate;
        $dtl->last_updated_by = Auth::user()->usr_name;
        $dtl->save();
        $result = DB::connection('oracle')->getPdo()->exec("begin SP_DONE_IREQ_MST($code); end;");
        return json_encode($result);
    }
    public function updateStatusClosingDetail($ireqd_id,$ireq_id){
        
        $dtl = IctDetail::find($ireqd_id);
        $dtl->ireq_status = 'C';
        $dtl->last_update_date = $this->newUpdate;
        $dtl->last_updated_by = Auth::user()->usr_name;
        $dtl->program_name = "IctDetail_updateStatusClosingDetail";
        $dtl->save();
        $result = DB::connection('oracle')->getPdo()->exec("begin SP_CLOSING_IREQ_MST($ireq_id); end;");
        return json_encode('Updated Successfully');
    }
    function submitRating(Request $request){
        if($request->rating <= '2'){
            $dtl = IctDetail::where('ireqd_id',$request->id)->first();
            $dtl->ireq_value = $request->rating;
            $dtl->ireq_note = $request->ket;
            $dtl->save();
            return response()->json('JIka rating <= 2');
        }
        else{
            $dtl = IctDetail::where('ireqd_id',$request->id)->first();
            $dtl->ireq_value = $request->rating;
            $dtl->save();
            return response()->json('JIka rating > 2');
        }
    }
}
