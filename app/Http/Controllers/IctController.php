<?php

namespace App\Http\Controllers;

use App\Ict;
use App\IctDetail;
use App\Link;
use App\Exports\IctExportPermohonan;
use App\Exports\IctExportVerifikasi;
use App\Exports\IctExportReject;
use App\Exports\IctExportSedangDikerjakan;
use App\Exports\IctExportSudahDikerjakan;
use App\Exports\IctExportSelesai;
use App\Exports\IctExportTabReviewer;
use App\Exports\IctExportPermohonanAtasan;
use App\Exports\IctExportVerifikasiAtasan;
use App\Exports\IctExportRejectAtasan;
use App\Exports\IctExportAtasanSedangDikerjakan;
use App\Exports\IctExportAtasanSudahDikerjakan;
use App\Exports\IctExportAtasanSelesai;
use App\Exports\IctExportReviewerPermohonan;
use App\Exports\IctExportReviewerAtasanDivisi;
use App\Exports\IctExportReviewerIctManager;
use App\Exports\IctExportRejectReviewer;
use App\Exports\IctExportReviewerSedangDikerjakan;
use App\Exports\IctExportReviewerSudahDikerjakan;
use App\Exports\IctExportReviewerSelesai;
use App\Exports\IctExportManagerPermohonan;
use App\Exports\IctExportVerifikasiManager;
use App\Exports\IctExportRejectManager;
use App\Exports\IctExportManagerSedangDikerjakan;
use App\Exports\IctExportPersonnelSedangDikerjakan;
use App\Exports\IctExportPersonnelSudahDikerjakan;
use App\Exports\IctExportPersonnelSelesai;
use DB;
use Excel;
use Carbon\Carbon;
use Auth;
use Illuminate\Http\Request;
use App\Jobs\SendEmailJob;
use Mail;
use App\Mail\IctRequestApproval;
use Illuminate\Support\Facades\Hash;

class IctController extends Controller
{
    function __construct(){
        $date = Carbon::now();
        $this->date = Carbon::now();
        $this->newCreation =Carbon::parse($date)->copy()->tz('Asia/Jakarta')->format('Y-m-d H:i:s');
        $this->newUpdate = Carbon::parse($date)->copy()->tz('Asia/Jakarta')->format('Y-m-d H:i:s');
    }
    function testing(){
        
    }
    function getDataManager()
    {
        $ict = DB::table('ireq_mst as im')
        ->select('im.ireq_id','im.ireq_no','im.ireq_date','im.ireq_user','im.ireq_requestor','im.ireq_status','dr.div_name','lr.lookup_desc as ireq_statuss')
        ->leftjoin('divisi_refs as dr','im.ireq_divisi_user','dr.div_id')
        ->leftjoin('lookup_refs as lr','im.ireq_status','lr.lookup_code')
        ->where(function($query){
            return $query
            ->where('im.ireq_status','NA2')
            ->Orwhere('im.ireq_status','NA1')
            ->Orwhere('im.ireq_status','P');
        })
        ->whereRaw('LOWER(lr.lookup_type) LIKE ? ',[trim(strtolower('ict_status')).'%'])
        ->groupBy('lr.lookup_desc','im.ireq_id','im.ireq_no','im.ireq_date','im.ireq_user','im.ireq_status','im.ireq_requestor','dr.div_name','im.creation_date')
        ->orderBy('im.creation_date','ASC')
        ->get(); 

        $ict1 = DB::table('ireq_mst as im')
        ->select('im.ireq_id','im.ireq_no','im.ireq_date','im.ireq_user','im.ireq_requestor', 'dr.div_name','lr.lookup_desc as ireq_status')
        ->leftjoin('divisi_refs as dr','im.ireq_divisi_user','dr.div_id')
        ->leftjoin('lookup_refs as lr','im.ireq_status','lr.lookup_code')
        ->where('im.ireq_status','A2')
        ->orwhere('im.ireq_status','A1')
        ->whereRaw('LOWER(lr.lookup_type) LIKE ? ',[trim(strtolower('ict_status')).'%'])
        ->groupBy('lr.lookup_desc','im.ireq_id','im.ireq_no','im.ireq_date','im.ireq_user','im.ireq_status','im.ireq_requestor','dr.div_name','im.creation_date')
        ->orderBy('im.creation_date','ASC')
        ->get(); 

        $ict2 = DB::table('ireq_mst as im')
        ->select('im.ireq_id','im.ireq_no','im.ireq_date','im.ireq_user','im.ireq_requestor','dr.div_name','im.ireq_reason','lr.lookup_desc as ireq_status')
        ->leftjoin('divisi_refs as dr','im.ireq_divisi_user','dr.div_id')
        ->leftjoin('lookup_refs as lr','im.ireq_status','lr.lookup_code')
        ->where('im.ireq_status','RR')
        ->orwhere('im.ireq_status','RA1')
        ->orwhere('im.ireq_status','RA2')
        ->whereRaw('LOWER(lr.lookup_type) LIKE ? ',[trim(strtolower('ict_status')).'%'])
        ->groupBy('lr.lookup_desc','im.ireq_id','im.ireq_no','im.ireq_date','im.ireq_user','im.ireq_requestor','dr.div_name','im.creation_date','im.ireq_reason')
        ->orderBy('im.creation_date','ASC')
        ->get(); 

        $ict3 = DB::table('ireq_mst as im')
        ->select('im.ireq_id','im.ireq_no','im.ireq_date','im.ireq_user','im.ireq_requestor','dr.div_name','im.ireq_assigned_to','lr.lookup_desc as ireq_status')
        ->leftjoin('divisi_refs as dr','im.ireq_divisi_user','dr.div_id')
        ->leftjoin('lookup_refs as lr','im.ireq_status','lr.lookup_code')
        ->where('im.ireq_status','T')
        ->whereRaw('LOWER(lr.lookup_type) LIKE ? ',[trim(strtolower('ict_status')).'%'])
        ->groupBy('lr.lookup_desc','im.ireq_id','im.ireq_no','im.ireq_date','im.ireq_user','im.ireq_requestor','dr.div_name','im.creation_date','im.ireq_assigned_to')
        ->orderBy('im.creation_date','ASC')
        ->get();

        $ict4 = DB::Table('v_ireq_mst_sudah_dikerjakan')->get();
        $ict5 = DB::Table('v_ireq_mst_selesai')->get();

        return response()->json(['ict'=>$ict,'ict1'=>$ict1,'ict2'=>$ict2,'ict3'=>$ict3,'ict4'=>$ict4,'ict5'=>$ict5],200);
    }
    function getDataManagerVerifikasi($code)
    {
        $dtl = DB::table('ireq_dtl as id')
        ->select('lr.lookup_desc as ireq_type','im.invent_desc','id.invent_code')
        ->leftjoin('invent_mst as im','id.invent_code','im.invent_code')
        ->leftjoin('lookup_refs as lr','id.ireq_type','lr.lookup_code')
        ->where('id.ireq_id',$code)
        ->whereRaw('LOWER(lr.lookup_type) LIKE ? ',[trim(strtolower('req_type')).'%'])
        ->get();
            return response()->json($dtl);
    }
    function approveByManager($code)
    {
        
        $ict = Ict::where('ireq_id',$code)->first();
        $ict->ireq_status = 'A2';
        $ict->ireq_approver2 = Auth::user()->usr_name;
        $ict->last_updated_by = Auth::user()->usr_name;
        $ict->last_update_date = $this->newUpdate;
        $ict->program_name = "IctController_approveByManager";
        $ict->save();

        $dtl = IctDetail::where('ireq_id',$code)->get();
        foreach ($dtl as $d){
            $d->ireq_status = 'A2';
            $d->last_update_date = $this->newUpdate;
            $d->last_updated_by = Auth::user()->usr_name;
            $d->program_name = "IctController_approveByManager";
            $d->save();
        }
        return response()->json('Success Update');
    }
    function rejectByManager(Request $request,$code)
    {
        
        $ict = Ict::where('ireq_id',$code)->first();
        $ict->ireq_status = 'RA2';
        $ict->ireq_reason = $request->ket;
        $ict->ireq_approver2 = Auth::user()->usr_name;
        $ict->last_updated_by = Auth::user()->usr_name;
        $ict->last_update_date = $this->newUpdate;
        $ict->program_name = "IctController_rejectByManager";
        $ict->save();

        $dtl = IctDetail::where('ireq_id',$code)->get();
        foreach ($dtl as $d){
            $d->ireq_status = 'RA2';
            $d->ireq_reason = $request->ket;
            $d->last_update_date = $this->newUpdate;
            $d->last_updated_by = Auth::user()->usr_name;
            $d->program_name = "IctController_rejectByManager";
            $d->save();
        }
        return response()->json('Success Update');
    }
    function getDataReviewer()
    {
        $ict = DB::table('ireq_mst as im')
        ->select('im.ireq_id','im.ireq_no','im.ireq_date','im.ireq_user','im.ireq_requestor','dr.div_name', 
                DB::raw('count(idd.ireq_assigned_to) as ireq_count_status'), DB::raw('count(idd.ireq_id) as ireq_count_id'),
                'lr.lookup_desc as ireq_status')
        ->leftjoin('divisi_refs as dr','im.ireq_divisi_user','dr.div_id')
        ->leftjoin('ireq_dtl as idd','im.ireq_id','idd.ireq_id')
        ->leftjoin('lookup_refs as lr','im.ireq_status','lr.lookup_code')
        ->where('im.ireq_status','P')
        ->whereRaw('LOWER(lr.lookup_type) LIKE ? ',[trim(strtolower('ict_status')).'%'])
        ->groupBy('im.ireq_id','im.ireq_no','im.ireq_date','im.ireq_user','im.ireq_requestor','dr.div_name','im.creation_date','lr.lookup_desc')
        ->orderBy('im.creation_date','ASC')
        ->get(); 

        $ict1 = DB::table('ireq_mst as im')
        ->select('im.ireq_id','im.ireq_no','im.ireq_date','im.ireq_user','im.ireq_requestor','dr.div_name','lr.lookup_desc as ireq_status',
                DB::raw('count(idd.ireq_assigned_to) as ireq_count_status'), DB::raw('count(idd.ireq_id) as ireq_count_id'))
        ->leftjoin('divisi_refs as dr','im.ireq_divisi_user','dr.div_id')
        ->leftjoin('ireq_dtl as idd','im.ireq_id','idd.ireq_id')
        ->leftjoin('lookup_refs as lr','im.ireq_status','lr.lookup_code')
        ->where('im.ireq_status','NA1')
        ->whereRaw('LOWER(lr.lookup_type) LIKE ? ',[trim(strtolower('ict_status')).'%'])
        ->orwhere('im.ireq_status','A1')
        ->groupBy('im.ireq_id','im.ireq_no','im.ireq_date','im.ireq_user','im.ireq_requestor','dr.div_name','im.creation_date','lr.lookup_desc')
        ->orderBy('im.creation_date','ASC')
        ->get(); 

        $ict2 = DB::table('ireq_mst as im')
        ->select('im.ireq_id','im.ireq_no','im.ireq_date','im.ireq_user','im.ireq_requestor','dr.div_name','lr.lookup_desc as ireq_status',
                DB::raw('count(idd.ireq_assigned_to) as ireq_count_status'), DB::raw('count(idd.ireq_id) as ireq_count_id'))
        ->leftjoin('divisi_refs as dr','im.ireq_divisi_user','dr.div_id')
        ->leftjoin('ireq_dtl as idd','im.ireq_id','idd.ireq_id')
        ->leftjoin('lookup_refs as lr','im.ireq_status','lr.lookup_code')
        ->where('im.ireq_status','NA2')
        ->whereRaw('LOWER(lr.lookup_type) LIKE ? ',[trim(strtolower('ict_status')).'%'])
        ->orwhere('im.ireq_status','A2')
        ->groupBy('im.ireq_id','im.ireq_no','im.ireq_date','im.ireq_user','im.ireq_requestor','dr.div_name','im.creation_date','lr.lookup_desc')
        ->orderBy('im.creation_date','ASC')
        ->get(); 

        $ict3 = DB::table('ireq_mst as im')
        ->select('im.ireq_id','im.ireq_no','im.ireq_date','im.ireq_user','im.ireq_requestor','dr.div_name','im.ireq_reason','lr.lookup_desc as ireq_status',
                DB::raw('count(idd.ireq_assigned_to) as ireq_count_status'), DB::raw('count(idd.ireq_id) as ireq_count_id'))
        ->leftjoin('divisi_refs as dr','im.ireq_divisi_user','dr.div_id')
        ->leftjoin('ireq_dtl as idd','im.ireq_id','idd.ireq_id')
        ->leftjoin('lookup_refs as lr','im.ireq_status','lr.lookup_code')
        ->whereRaw('LOWER(lr.lookup_type) LIKE ? ',[trim(strtolower('ict_status')).'%'])
        ->where(function ($query){
            return $query
            ->where('im.ireq_status','RR')
            ->OrWhere('im.ireq_status','RA1')
            ->OrWhere('im.ireq_status','RA2');
        })
        ->groupBy('im.ireq_id','im.ireq_no','im.ireq_date','im.ireq_user','im.ireq_requestor','dr.div_name','im.creation_date','lr.lookup_desc','im.ireq_reason')
        ->orderBy('im.creation_date','ASC')
        ->get(); 

        $ict4 = DB::table('ireq_mst as im')
        ->select('im.ireq_id','im.ireq_no','im.ireq_date','im.ireq_user','im.ireq_requestor','dr.div_name','im.ireq_assigned_to','lr.lookup_desc as ireq_status')
        ->leftjoin('divisi_refs as dr','im.ireq_divisi_user','dr.div_id')
        ->leftjoin('lookup_refs as lr','im.ireq_status','lr.lookup_code')
        ->where('im.ireq_status','T')
        ->whereRaw('LOWER(lr.lookup_type) LIKE ? ',[trim(strtolower('ict_status')).'%'])
        ->groupBy('im.ireq_id','im.ireq_no','im.ireq_date','im.ireq_user','im.ireq_requestor','dr.div_name','im.creation_date','lr.lookup_desc','im.ireq_assigned_to')
        ->orderBy('im.creation_date','ASC')
        ->get();

        $ict5 = DB::Table('v_ireq_mst_sudah_dikerjakan')->get();

        $ict6 = DB::Table('v_ireq_mst_selesai')->get();

        return response()->json(['ict'=>$ict,'ict1'=>$ict1,'ict2'=>$ict2,'ict3'=>$ict3,'ict4'=>$ict4,'ict5'=>$ict5,'ict6'=>$ict6],200);
    }
    function rejectReviewer(Request $request, $code)
    {
        
        $ict = Ict::where('ireq_id',$code)->first();
        $ict->ireq_status = 'RR';
        $ict->ireq_verificator = Auth::user()->usr_name;
        $ict->ireq_reason = $request->ket;
        $ict->last_update_date = $this->newUpdate;
        $ict->last_updated_by = Auth::user()->usr_name;
        $ict->program_name = "IctController_rejectReviewer";
        $ict->save();

        $dtl = IctDetail::where('ireq_id',$code)->get();
        foreach ($dtl as $d){
            $d->ireq_status = 'RR';
            $d->ireq_reason = $request->ket;
            $d->last_update_date = $this->newUpdate;
            $d->last_updated_by = Auth::user()->usr_name;
            $d->program_name = "IctController_rejectReviewer";
            $d->save();
        }
        return response()->json('Success Update Status');
    }
    function needApprovalAtasan($ireq_id)
    {
        
        $ICT = Ict::where('ireq_id',$ireq_id)->first();
        $divisiPengguna = $ICT->ireq_divisi_user;
        $ICT->ireq_status = 'NA1';
        $ICT->ireq_verificator = Auth::user()->usr_name;
        $ICT->last_update_date = $this->newUpdate;
        $ICT->last_updated_by = Auth::user()->usr_name;
        $ICT->program_name = "IctController_needApprovalAtasan";
        $ICT->save();

        $dtl = IctDetail::where('ireq_id',$ireq_id)->get();
        foreach ($dtl as $d){
            $d->ireq_status = 'NA1';
            $d->last_update_date = $this->newUpdate;
            $d->last_updated_by = Auth::user()->usr_name;
            $d->program_name = "IctController_needApprovalAtasan";
            $d->save();
        }
        $emailVerifikator = DB::table('divisi_refs as dr')
                    ->rightjoin('mng_users as mu','dr.div_verificator','mu.usr_name')
                    ->select('mu.usr_email','mu.usr_id')
                    ->where('dr.div_id',$divisiPengguna)
                    ->first();
        $ict = DB::table('ireq_mst as im')
        ->leftjoin('ireq_dtl as id','im.ireq_id','id.ireq_id')
        ->leftjoin('divisi_refs as dr','im.ireq_divisi_user','dr.div_id')
        ->rightjoin('mng_users as mu','dr.div_verificator','mu.usr_name')
        ->join('invent_mst as imm','id.invent_code','imm.invent_code')
        ->select('im.ireq_no','im.ireq_id','dr.div_name', 'mu.usr_name',DB::raw("TO_CHAR(im.ireq_date, 'dd Mon YYYY') as ireq_date"),
                 'im.ireq_user',DB::raw("(imm.invent_code ||'-'|| imm.invent_desc) as invent_code"),'id.ireq_qty')
        ->where('im.ireq_id',$ireq_id)
        ->get();

        $Date = $this->date->addDays(1);
        $expiredDate = Carbon::parse($Date)->copy()->tz('Asia/Jakarta')->format('Y-m-d H:i:s');
        $link = Link::create([
            'link_id'=> md5($this->date),
            'link_action'=> 'http://localhost:8000/ict-request-verifikasi/'.''.$ireq_id,
            'expired_at'=>$expiredDate,
            'usr_id'=>$emailVerifikator->usr_id,
            'ireq_id'=>$ireq_id
        ]);
        $LINK = Link::where('ireq_id',$ireq_id)->first();
        $send_mail = $emailVerifikator->usr_email;
        $emailJob = (new SendEmailJob($send_mail,$ict,$LINK));
        dispatch($emailJob);
        return response()->json('Success Update Status');
    }
    function needApprovalManager($ireq_id)
    {
        $ict = Ict::where('ireq_id',$ireq_id)->first();
        $ict->ireq_status = 'NA2';
        $ict->ireq_verificator = Auth::user()->usr_name;
        $ict->last_update_date = $this->newUpdate;
        $ict->last_updated_by = Auth::user()->usr_name;
        $ict->program_name = "IctController_needApprovalManager";
        $ict->save();

        $dtl = IctDetail::where('ireq_id',$ireq_id)->get();
        foreach ($dtl as $d){
            $d->ireq_status = 'NA2';
            $d->last_update_date = $this->newUpdate;
            $d->last_updated_by = Auth::user()->usr_name;
            $d->program_name = "IctController_needApprovalManager";
            $d->save();
        }
        return response()->json('Success Update Status');
    }
    function asignPerRequestReviewer(Request $request)
    {
        
        $ict = Ict::where('ireq_id',$request->id)->first();
        $ict->ireq_assigned_to = $request->name;
        $ict->last_updated_by = Auth::user()->usr_name;
        $ict->last_update_date = $this->newUpdate;
        $ict->program_name = "IctController_asignPerRequestReviewer";
        $ict->save();

        $dtl = IctDetail::where('ireq_id',$request->id)->get();
        foreach ($dtl as $d){
            $d->ireq_assigned_to = $request->name;
            $d->last_update_date = $this->newUpdate;
            $d->last_updated_by = Auth::user()->usr_name;
            $d->program_name = "IctController_asignPerRequestReviewer";
            $d->save();
        }
        
        return response()->json('Success Update');
    }
    function submitAssignPerRequest($ireq_id)
    {
        $ict = Ict::where('ireq_id',$ireq_id)->first();
        $ict->ireq_status = 'T';
        $ict->ireq_verificator = Auth::user()->usr_name;
        $ict->last_update_date = $this->newUpdate;    
        $ict->last_updated_by = Auth::user()->usr_name;
        $ict->program_name = "IctController_submitAssignPerRequest";
        $ict->save();
        
        $dtl = IctDetail::where('ireq_id',$ireq_id)->get();
        foreach ($dtl as $d){
            $d->ireq_status = 'T';
            $d->last_update_date = $this->newUpdate;
            $d->last_updated_by = Auth::user()->usr_name;
            $d->program_name = "IctController_submitAssignPerRequest";
            $d->save();
        }
        return response()->json('Success Update');
    }
    function getIctAdmin()
    {
        $ict = DB::table('ireq_mst as im')
        ->leftjoin('ireq_dtl as idm','im.ireq_id','idm.ireq_id')
        ->leftjoin('divisi_refs as dr','im.ireq_divisi_user','dr.div_id')
        ->leftjoin('lookup_refs as lr','im.ireq_status','lr.lookup_code')
        ->select('im.ireq_id','im.ireq_no','im.ireq_date','dr.div_name',
        'im.ireq_user','dr.div_name', 'lr.lookup_desc as ireq_status')
        ->whereRaw('LOWER(lr.lookup_type) LIKE ? ',[trim(strtolower('ict_status')).'%'])
        ->where(function($query){
            return $query
            ->Where('im.ireq_status','P')
            ->OrWhere('im.ireq_status','NA1')
            ->OrWhere('im.ireq_status','NA2');
            })
        ->groupBy('im.ireq_id','im.ireq_no','im.ireq_date','im.ireq_status','im.ireq_user','im.creation_date','dr.div_name','lr.lookup_desc')
        ->orderBy('im.creation_date','ASC')
        ->get();

        $ict1 = DB::table('ireq_mst as im')
        ->leftjoin('divisi_refs as dr','im.ireq_divisi_user','dr.div_id')
        ->leftjoin('lookup_refs as lr','im.ireq_status','lr.lookup_code')
        ->select('im.ireq_id','im.ireq_no','im.ireq_date','im.ireq_user','dr.div_name','lr.lookup_desc as ireq_status')
        ->whereRaw('LOWER(lr.lookup_type) LIKE ? ',[trim(strtolower('ict_status')).'%'])
        ->where(function($query){
            return $query
            ->where('im.ireq_status','A1')
            ->Orwhere('im.ireq_status','A2');
        })
        ->orderBy('im.creation_date','ASC')
        ->get();

        $ict2 = DB::table('ireq_mst as im')
        ->leftjoin('lookup_refs as lr','im.ireq_status','lr.lookup_code')
        ->select('im.ireq_id','im.ireq_no','im.ireq_date','im.ireq_user','im.ireq_reason','lr.lookup_desc as ireq_status')
        ->whereRaw('LOWER(lr.lookup_type) LIKE ? ',[trim(strtolower('ict_status')).'%'])
        ->where(function($query){
            return $query
            ->where('im.ireq_status','RR')
            ->Orwhere('im.ireq_status','RA1')
            ->Orwhere('im.ireq_status','RA2');
        })
        ->orderBy('im.creation_date','ASC')
        ->get();

        $ict3 = DB::table('ireq_mst as im')
        ->leftjoin('lookup_refs as lr','im.ireq_status','lr.lookup_code')
        ->select('im.ireq_id','im.ireq_no','im.ireq_date','im.ireq_user','im.ireq_assigned_to','lr.lookup_desc as ireq_status')
        ->where('im.ireq_status','T')
        ->whereRaw('LOWER(lr.lookup_type) LIKE ? ',[trim(strtolower('ict_status')).'%'])
        ->orderBy('im.creation_date','ASC')
        ->get();

        $ict4 =  DB::Table('v_ireq_mst_sudah_dikerjakan')->get();
        $ict5 = DB::Table('v_ireq_mst_selesai')->get();
        $ict6 = DB::table('ireq_mst as id')
        ->leftjoin('lookup_refs as lr','id.ireq_status','lr.lookup_code')
        ->select('id.ireq_id','id.ireq_no','id.ireq_user','id.ireq_date','lr.lookup_desc as ireq_status')
        ->whereRaw('LOWER(lr.lookup_type) LIKE ? ',[trim(strtolower('ict_status')).'%'])
        ->whereNotNull('id.ireq_status')
        ->orderBy(DB::raw("CASE WHEN id.ireq_status = 'P' Then 1 WHEN id.ireq_status = 'NA1' Then
        2 WHEN id.ireq_status = 'NA2' Then 3 WHEN
         id.ireq_status = 'A1' Then 4 WHEN id.ireq_status = 'A2' Then 5
         WHEN id.ireq_status = 'RR' Then 6 WHEN id.ireq_status = 'RA1' Then 7
         WHEN id.ireq_status = 'RA2' THEN 8 WHEN id.ireq_status = 'T' Then 9 
         WHEN id.ireq_status = 'D' Then 10 WHEN id.ireq_status = 'C' Then 11 end "))
        ->get();

        return response()->json(['ict'=>$ict,'ict1'=>$ict1,'ict2'=>$ict2,'ict3'=>$ict3,'ict4'=>$ict4,'ict5'=>$ict5,'ict6'=>$ict6],200);

    }
    Public function getIct($usr_name)
    {
        $ict = DB::table('ireq_mst as id')
        ->leftjoin('ireq_dtl as idm','id.ireq_id','idm.ireq_id')
        ->leftjoin('lookup_refs as lr','id.ireq_status','lr.lookup_code')
        ->leftJoin('lookup_refs as llr',function ($join) {
            $join->on('id.ireq_status','llr.lookup_code')
                  ->whereRaw('LOWER(llr.lookup_type) LIKE ? ',[trim(strtolower('ict_status')).'%']);
        })
        ->leftjoin('divisi_refs as dr','id.ireq_divisi_user','dr.div_id')
        ->select('id.ireq_id','id.ireq_no','id.ireq_date','id.ireq_user','dr.div_name','id.ireq_requestor',
                  DB::raw('count(DISTINCT(idm.ireq_id)) as count'),'llr.lookup_desc as ireq_status')
        ->where('id.created_by',$usr_name)
        ->where(function($query){
            return $query
            ->whereNull('id.ireq_status')
            ->orWhere('id.ireq_status','P');
            })
        ->groupBy('llr.lookup_desc','id.ireq_id','id.ireq_no','id.ireq_date','id.ireq_user','id.creation_date','dr.div_name','id.ireq_status','id.ireq_requestor')
        ->orderBy('id.creation_date','ASC')
        ->get();

        $ict1 = DB::table('ireq_mst as im')
        ->leftjoin('lookup_refs as lr','im.ireq_status','lr.lookup_code')
        ->select('im.ireq_id','im.ireq_no','im.ireq_date','im.ireq_requestor','im.ireq_user','lr.lookup_desc as ireq_status')
        ->where('im.created_by',$usr_name)
        ->whereRaw('LOWER(lr.lookup_type) LIKE ? ',[trim(strtolower('ict_status')).'%'])
        ->where(function($query){
            return $query
            ->where('im.ireq_status','A1')
            ->orWhere('im.ireq_status','A2');
        })
        ->orderBy('im.creation_date','ASC')
        ->get();

        $ict2 = DB::table('ireq_mst as im')
        ->leftjoin('lookup_refs as lr','im.ireq_status','lr.lookup_code')
        ->select('im.ireq_requestor','im.ireq_id','im.ireq_no','im.ireq_date','im.ireq_user','im.ireq_reason','lr.lookup_desc as ireq_status')
        ->where('im.created_by',$usr_name)
        ->whereRaw('LOWER(lr.lookup_type) LIKE ? ',[trim(strtolower('ict_status')).'%'])
        ->where(function($query){
            return $query
            ->where('im.ireq_status','RR')
            ->orwhere('im.ireq_status','RA1')
            ->orwhere('im.ireq_status','RA2');
        })
        ->orderBy('im.creation_date','ASC')
        ->get();

        $ict3 =  DB::table('ireq_mst as im')
        ->select('im.ireq_requestor','im.ireq_id','im.ireq_no','im.ireq_date','im.ireq_user','im.ireq_requestor','dr.div_name','im.ireq_assigned_to','lr.lookup_desc as ireq_status')
        ->leftjoin('divisi_refs as dr','im.ireq_divisi_user','dr.div_id')
        ->leftjoin('lookup_refs as lr','im.ireq_status','lr.lookup_code')
        ->where('im.ireq_status','T')
        ->whereRaw('LOWER(lr.lookup_type) LIKE ? ',[trim(strtolower('ict_status')).'%'])
        ->where('im.created_by',$usr_name)
        ->groupBy('im.ireq_requestor','im.ireq_id','im.ireq_no','im.ireq_date','im.ireq_user','im.ireq_requestor','dr.div_name','im.creation_date','lr.lookup_desc','im.ireq_assigned_to')
        ->orderBy('im.creation_date','ASC')
        ->get();

        $ict4 = DB::table('ireq_mst as im')
        ->leftjoin('ireq_dtl as idd','im.ireq_id','idd.ireq_id')
        ->leftjoin('lookup_refs as lr','idd.ireq_status','lr.lookup_code')
        ->leftjoin('divisi_refs as dr','im.ireq_divisi_user','dr.div_id')
        ->leftjoin('invent_mst as imm','idd.invent_code','imm.invent_code')
        ->select('im.ireq_requestor','dr.div_name','im.ireq_id','im.ireq_no','im.ireq_date','im.ireq_requestor',
                 'im.ireq_user','idd.ireq_assigned_to','idd.ireqd_id',
                 DB::raw("(imm.invent_code ||'-'|| imm.invent_desc) as invent_code"),'lr.lookup_desc as ireq_status')
        ->where('idd.ireq_status','D')
        ->whereRaw('LOWER(lr.lookup_type) LIKE ? ',[trim(strtolower('ict_status')).'%'])
        ->where('im.created_by',$usr_name)
        ->orderBy('idd.ireqd_id','ASC')
        ->get();

        $ict5 = DB::table('ireq_mst as im')
        ->leftjoin('ireq_dtl as idd','im.ireq_id','idd.ireq_id')
        ->leftjoin('lookup_refs as lr','im.ireq_status','lr.lookup_code')
        ->leftjoin('divisi_refs as dr','im.ireq_divisi_user','dr.div_id')
        ->leftjoin('invent_mst as imm','idd.invent_code','imm.invent_code')
        ->select('im.ireq_requestor','dr.div_name','im.ireq_id','im.ireq_no','im.ireq_date','im.ireq_requestor',
                 'im.ireq_user','idd.ireq_assigned_to','idd.ireqd_id','idd.ireq_value',
                 DB::raw("(imm.invent_code ||'-'|| imm.invent_desc) as invent_code"),'lr.lookup_desc as ireq_status')
        ->whereRaw('LOWER(lr.lookup_type) LIKE ? ',[trim(strtolower('ict_status')).'%'])
        ->where('idd.ireq_status','C')
        ->where('im.created_by',$usr_name)
        ->orderBy('idd.ireqd_id','ASC')
        ->get();
        
        $ict6 = DB::table('ireq_mst as im')
        ->leftjoin('lookup_refs as lr','im.ireq_status','lr.lookup_code')
        ->select('im.ireq_requestor','im.ireq_id','im.ireq_no','im.ireq_date','im.ireq_user','lr.lookup_desc as ireq_status')
        ->where('im.created_by',$usr_name)
        ->whereRaw('LOWER(lr.lookup_type) LIKE ? ',[trim(strtolower('ict_status')).'%'])
        ->where(function($query){
            return $query
            ->where('im.ireq_status','NA1')
            ->orwhere('im.ireq_status','NA2');
        })
        ->orderBy('im.creation_date','ASC')
        ->get();
        
        $ict7 = DB::table('ireq_mst as im')
        ->leftjoin('ireq_dtl as idm','im.ireq_id','idm.ireq_id')
        ->leftjoin('lookup_refs as lr','idm.ireq_status','lr.lookup_code')
        ->leftjoin('divisi_refs as dr','im.ireq_divisi_user','dr.div_id')
        ->select('im.ireq_requestor','im.ireq_id','im.ireq_no','im.ireq_date','im.ireq_user','dr.div_name',
                  DB::raw('count(DISTINCT(idm.ireq_id)) as count'), 'lr.lookup_desc as ireq_status')
        ->where('im.created_by',$usr_name)
        ->whereRaw('LOWER(lr.lookup_type) LIKE ? ',[trim(strtolower('ict_status')).'%'])
        ->where(function($query){
            return $query
            ->where('im.ireq_status','P');
            })
        ->groupBy('im.ireq_requestor','im.ireq_id','im.ireq_no','im.ireq_date','im.ireq_user','im.creation_date','dr.div_name','lr.lookup_desc')
        ->orderBy('im.creation_date','ASC')
        ->get();

        return response()->json(['ict'=>$ict,'ict1'=>$ict1,'ict2'=>$ict2,'ict3'=>$ict3,'ict4'=>$ict4,'ict5'=>$ict5,'ict6'=>$ict6,'ict7'=>$ict7],200);
    }
    Public function getPermohonan($usr_name)
    {
        $ict = DB::table('ireq_mst as im')
        ->select('im.ireq_id','im.ireq_no','im.ireq_date','im.ireq_status as ireq_statuss','im.ireq_user',
        'im.ireq_requestor','lr.lookup_desc as ireq_status')
        ->leftjoin('divisi_refs as dr','im.ireq_divisi_user','dr.div_id')
        ->leftjoin('lookup_refs as lr','im.ireq_status','lr.lookup_code')
        ->where('dr.div_verificator',$usr_name)
        ->whereRaw('LOWER(lr.lookup_type) LIKE ? ',[trim(strtolower('ict_status')).'%'])
        ->where(function($query){
            return $query
            ->where('im.ireq_status','NA1')
            ->orwhere('im.ireq_status','NA2')
            ->orwhere('im.ireq_status','P');
        })
        ->groupBy('im.ireq_id','im.ireq_no','im.ireq_date','im.ireq_status','im.ireq_user','im.creation_date','im.ireq_requestor','lr.lookup_desc')
        ->orderBy('im.creation_date','ASC')
        ->get();
        
        $ict1 = DB::table('ireq_mst as im')
        ->select('im.ireq_id','im.ireq_no','im.ireq_date','im.ireq_user',
        'im.ireq_requestor','lr.lookup_desc as ireq_status')
        ->leftjoin('divisi_refs as dr','im.ireq_divisi_user','dr.div_id')
        ->leftjoin('lookup_refs as lr','im.ireq_status','lr.lookup_code')
        ->where('dr.div_verificator',$usr_name)
        ->whereRaw('LOWER(lr.lookup_type) LIKE ? ',[trim(strtolower('ict_status')).'%'])
        ->where(function($query){
            return $query
            ->Where('im.ireq_status','A1')
            ->orwhere('im.ireq_status','A2');
            })
        ->orderBy('im.creation_date','ASC')
        ->get();

        $ict2 = DB::table('ireq_mst as im')
        ->select('im.ireq_id','im.ireq_no','im.ireq_date','im.ireq_user',
        'im.ireq_requestor','im.ireq_reason','lr.lookup_desc as ireq_status')
        ->leftjoin('divisi_refs as dr','im.ireq_divisi_user','dr.div_id')
        ->leftjoin('lookup_refs as lr','im.ireq_status','lr.lookup_code')
        ->where('dr.div_verificator',$usr_name)
        ->whereRaw('LOWER(lr.lookup_type) LIKE ? ',[trim(strtolower('ict_status')).'%'])
        ->where(function($query){
            return $query
            ->where('im.ireq_status','RA1')
            ->orwhere('im.ireq_status','RA2')
            ->orwhere('im.ireq_status','RR');
            })
        ->orderBy('im.creation_date','ASC')
        ->get();

        $ict3 = DB::table('ireq_mst as im')
        ->select('im.ireq_id','im.ireq_no','im.ireq_date','im.ireq_user','im.ireq_requestor','im.ireq_assigned_to','lr.lookup_desc as ireq_status')
        ->leftjoin('divisi_refs as dr','im.ireq_divisi_user','dr.div_id')
        ->leftjoin('lookup_refs as lr','im.ireq_status','lr.lookup_code')
        ->where('dr.div_verificator',$usr_name)
        ->whereRaw('LOWER(lr.lookup_type) LIKE ? ',[trim(strtolower('ict_status')).'%'])
        ->where('im.ireq_status','T')
        ->groupBy('im.ireq_id','im.ireq_no','im.ireq_date','lr.lookup_desc','im.ireq_user','im.creation_date','im.ireq_requestor','im.ireq_assigned_to')
        ->orderBy('im.creation_date','ASC')
        ->get();

        $ict4 = DB::table('ireq_mst as im')
        ->leftjoin('ireq_dtl as idd','im.ireq_id','idd.ireq_id')
        ->leftjoin('lookup_refs as lr','idd.ireq_status','lr.lookup_code')
        ->leftjoin('divisi_refs as dr','im.ireq_divisi_user','dr.div_id')
        ->leftjoin('invent_mst as imm','idd.invent_code','imm.invent_code')
        ->select('dr.div_name','im.ireq_id','im.ireq_no','im.ireq_date','im.ireq_requestor',
                 'im.ireq_user','idd.ireq_assigned_to','idd.ireqd_id',
                 DB::raw("(imm.invent_code ||'-'|| imm.invent_desc) as invent_code"),'lr.lookup_desc as ireq_status')
        ->where('idd.ireq_status','D')
        ->whereRaw('LOWER(lr.lookup_type) LIKE ? ',[trim(strtolower('ict_status')).'%'])
        ->where('dr.div_verificator',$usr_name)
        ->orderBy('idd.ireqd_id','ASC')
        ->get();

        $ict5 = DB::table('ireq_mst as im')
        ->leftjoin('ireq_dtl as idd','im.ireq_id','idd.ireq_id')
        ->leftjoin('lookup_refs as lr','idd.ireq_status','lr.lookup_code')
        ->leftjoin('divisi_refs as dr','im.ireq_divisi_user','dr.div_id')
        ->leftjoin('invent_mst as imm','idd.invent_code','imm.invent_code')
        ->select('dr.div_name','im.ireq_id','im.ireq_no','im.ireq_date','im.ireq_requestor',
                 'im.ireq_user','idd.ireq_assigned_to','idd.ireqd_id',
                 DB::raw("(imm.invent_code ||'-'|| imm.invent_desc) as invent_code"),'lr.lookup_desc as ireq_status')
        ->where('idd.ireq_status','C')
        ->whereRaw('LOWER(lr.lookup_type) LIKE ? ',[trim(strtolower('ict_status')).'%'])
        ->where('dr.div_verificator',$usr_name)
        ->orderBy('idd.ireqd_id','ASC')
        ->get();

        $ict6 = DB::table('ireq_mst as id')
            ->select('id.ireq_id','id.ireq_no','id.ireq_user','id.ireq_date','dr.div_name','id.ireq_requestor','lr.lookup_desc as ireq_status')
            ->leftjoin('divisi_refs as dr','id.ireq_divisi_user','dr.div_id')
            ->leftjoin('lookup_refs as lr','id.ireq_status','lr.lookup_code')
            ->where('dr.div_verificator',$usr_name)
            ->whereRaw('LOWER(lr.lookup_type) LIKE ? ',[trim(strtolower('ict_status')).'%'])
            ->whereNotNull('id.ireq_status')
            ->orderBy(DB::raw("CASE WHEN id.ireq_status = 'P' Then 1 WHEN id.ireq_status = 'NA1' Then
             2 WHEN id.ireq_status = 'NA2' Then 3 WHEN
              id.ireq_status = 'A1' Then 4 WHEN id.ireq_status = 'A2' Then 5
              WHEN id.ireq_status = 'RR' Then 6 WHEN id.ireq_status = 'RA1' Then 7
              WHEN id.ireq_status = 'RA2' THEN 8 WHEN id.ireq_status = 'T' Then 9 
              WHEN id.ireq_status = 'D' Then 10 WHEN id.ireq_status = 'C' Then 11 end "))
            ->get();

        $ict7 = DB::table('ireq_mst as im')
        ->select('im.ireq_id','im.ireq_no','im.ireq_date','im.ireq_status as ireq_statuss','im.ireq_user',
        'im.ireq_requestor','lr.lookup_desc as ireq_status')
        ->leftjoin('divisi_refs as dr','im.ireq_divisi_user','dr.div_id')
        ->leftjoin('lookup_refs as lr','im.ireq_status','lr.lookup_code')
        ->where('dr.div_verificator',$usr_name)
        ->whereRaw('LOWER(lr.lookup_type) LIKE ? ',[trim(strtolower('ict_status')).'%'])
        ->where(function($query){
            return $query
            ->where('im.ireq_status','NA1')
            ->orwhere('im.ireq_status','NA2');
        })
        ->groupBy('im.ireq_id','im.ireq_no','im.ireq_date','im.ireq_status','im.ireq_user','im.creation_date','im.ireq_requestor','lr.lookup_desc')
        ->orderBy('im.creation_date','ASC')
        ->get();

        $ict8 = DB::table('ireq_mst as im')
        ->select('im.ireq_id','im.ireq_no','im.ireq_date','im.ireq_status as ireq_statuss','im.ireq_user',
        'im.ireq_requestor','lr.lookup_desc as ireq_status')
        ->leftjoin('divisi_refs as dr','im.ireq_divisi_user','dr.div_id')
        ->leftjoin('lookup_refs as lr','im.ireq_status','lr.lookup_code')
        ->where('dr.div_verificator',$usr_name)
        ->whereRaw('LOWER(lr.lookup_type) LIKE ? ',[trim(strtolower('ict_status')).'%'])
        ->where('im.ireq_status','P')
        ->groupBy('im.ireq_id','im.ireq_no','im.ireq_date','im.ireq_status','im.ireq_user','im.creation_date','im.ireq_requestor','lr.lookup_desc')
        ->orderBy('im.creation_date','ASC')
        ->get();

        return response()->json(['ict'=>$ict,'ict1'=>$ict1,'ict2'=>$ict2,'ict3'=>$ict3,'ict4'=>$ict4,'ict5'=>$ict5,'ict6'=>$ict6,'ict7'=>$ict7,'ict8'=>$ict8]);
    }
    Public function getPermohonanDivisi()
    {
        $ict1 = $ict = DB::table('ireq_mst as im')
        ->select('im.ireq_id','im.ireq_no','im.ireq_date','im.ireq_user','im.ireq_requestor','dr.div_name', 
                DB::raw('count(idd.ireq_assigned_to) as ireq_count_status'), DB::raw('count(idd.ireq_id) as ireq_count_id'),
                'lr.lookup_desc as ireq_status')
        ->leftjoin('divisi_refs as dr','im.ireq_divisi_user','dr.div_id')
        ->leftjoin('ireq_dtl as idd','im.ireq_id','idd.ireq_id')
        ->leftjoin('lookup_refs as lr','im.ireq_status','lr.lookup_code')
        ->where('im.ireq_status','P')
        ->whereRaw('LOWER(lr.lookup_type) LIKE ? ',[trim(strtolower('ict_status')).'%'])
        ->groupBy('im.ireq_id','im.ireq_no','im.ireq_date','im.ireq_user','im.ireq_requestor','dr.div_name','im.creation_date','lr.lookup_desc')
        ->orderBy('im.creation_date','ASC')
        ->get(); 

        $ict2 = DB::table('ireq_mst as im')
        ->select('im.ireq_id','im.ireq_no','im.ireq_date','im.ireq_user','im.ireq_requestor','dr.div_name','lr.lookup_desc as ireq_status',
                DB::raw('count(idd.ireq_assigned_to) as ireq_count_status'), DB::raw('count(idd.ireq_id) as ireq_count_id'))
        ->leftjoin('divisi_refs as dr','im.ireq_divisi_user','dr.div_id')
        ->leftjoin('ireq_dtl as idd','im.ireq_id','idd.ireq_id')
        ->leftjoin('lookup_refs as lr','im.ireq_status','lr.lookup_code')
        ->whereRaw('LOWER(lr.lookup_type) LIKE ? ',[trim(strtolower('ict_status')).'%'])
        ->where('im.ireq_status','NA1')
        ->orwhere('im.ireq_status','A1')
        ->groupBy('im.ireq_id','im.ireq_no','im.ireq_date','im.ireq_user','im.ireq_requestor','dr.div_name','im.creation_date','lr.lookup_desc')
        ->orderBy('im.creation_date','ASC')
        ->get(); 

        $ict3 = DB::table('ireq_mst as im')
        ->select('im.ireq_id','im.ireq_no','im.ireq_date','im.ireq_user','im.ireq_requestor','dr.div_name','lr.lookup_desc as ireq_status',
                DB::raw('count(idd.ireq_assigned_to) as ireq_count_status'), DB::raw('count(idd.ireq_id) as ireq_count_id'))
        ->leftjoin('divisi_refs as dr','im.ireq_divisi_user','dr.div_id')
        ->leftjoin('ireq_dtl as idd','im.ireq_id','idd.ireq_id')
        ->leftjoin('lookup_refs as lr','im.ireq_status','lr.lookup_code')
        ->whereRaw('LOWER(lr.lookup_type) LIKE ? ',[trim(strtolower('ict_status')).'%'])
        ->where('im.ireq_status','NA2')
        ->orwhere('im.ireq_status','A2')
        ->groupBy('im.ireq_id','im.ireq_no','im.ireq_date','im.ireq_user','im.ireq_requestor','dr.div_name','im.creation_date','lr.lookup_desc')
        ->orderBy('im.creation_date','ASC')
        ->get(); 

        $ict4 = DB::table('ireq_mst as im')
        ->select('im.ireq_id','im.ireq_no','im.ireq_date','im.ireq_user','dr.div_name','im.ireq_reason','lr.lookup_desc as ireq_status',
        DB::raw('count(idd.ireq_assigned_to) as ireq_count_status'), DB::raw('count(idd.ireq_id) as ireq_count_id'))
        ->leftjoin('divisi_refs as dr','im.ireq_divisi_user','dr.div_id')
        ->leftjoin('ireq_dtl as idd','im.ireq_id','idd.ireq_id')
        ->leftjoin('lookup_refs as lr','im.ireq_status','lr.lookup_code')
        ->whereRaw('LOWER(lr.lookup_type) LIKE ? ',[trim(strtolower('ict_status')).'%'])
        ->where(function ($query){
            return $query
            ->where('im.ireq_status','RR')
            ->OrWhere('im.ireq_status','RA1')
            ->OrWhere('im.ireq_status','RA2');
        })
        ->groupBy('im.ireq_id','im.ireq_no','im.ireq_date','im.ireq_user','im.ireq_reason','dr.div_name','im.creation_date','lr.lookup_desc')
        ->orderBy('im.creation_date','ASC')
        ->get(); 

        
        $ict5 = DB::table('ireq_mst as im')
        ->select('im.ireq_id','im.ireq_no','im.ireq_date','im.ireq_user','im.ireq_requestor','dr.div_name','im.ireq_assigned_to',
                'lr.lookup_desc as ireq_status')
        ->leftjoin('divisi_refs as dr','im.ireq_divisi_user','dr.div_id')
        ->leftjoin('lookup_refs as lr','im.ireq_status','lr.lookup_code')
        ->where('im.ireq_status','T')
        ->whereRaw('LOWER(lr.lookup_type) LIKE ? ',[trim(strtolower('ict_status')).'%'])
        ->groupBy('im.ireq_id','im.ireq_no','im.ireq_date','im.ireq_user','im.ireq_requestor','dr.div_name','im.creation_date','lr.lookup_desc','im.ireq_assigned_to')
        ->orderBy('im.creation_date','ASC')
        ->get();

        $ict6 = DB::Table('v_ireq_mst_sudah_dikerjakan')->get();

        $ict7 = DB::Table('v_ireq_mst_selesai')->get();
        $ict8 = DB::table('ireq_mst as id')
        ->select('id.ireq_id','id.ireq_no','id.ireq_date','id.ireq_user','id.ireq_requestor','dr.div_name','id.ireq_assigned_to','lr.lookup_desc as ireq_status')
        ->leftjoin('divisi_refs as dr','id.ireq_divisi_user','dr.div_id')
        ->leftjoin('lookup_refs as lr','id.ireq_status','lr.lookup_code')
        ->whereRaw('LOWER(lr.lookup_type) LIKE ? ',[trim(strtolower('ict_status')).'%'])
        ->whereNotNull('id.ireq_status')
        ->orderBy(DB::raw("CASE WHEN id.ireq_status = 'P' Then 1 WHEN id.ireq_status = 'NA1' Then
             2 WHEN id.ireq_status = 'NA2' Then 3 WHEN
              id.ireq_status = 'A1' Then 4 WHEN id.ireq_status = 'A2' Then 5
              WHEN id.ireq_status = 'RR' Then 6 WHEN id.ireq_status = 'RA1' Then 7
              WHEN id.ireq_status = 'RA2' THEN 8 WHEN id.ireq_status = 'T' Then 9 
              WHEN id.ireq_status = 'D' Then 10 WHEN id.ireq_status = 'C' Then 11 end "))
        ->get();
        return response()->json(['ict1'=>$ict1,'ict2'=>$ict2,'ict3'=>$ict3,'ict4'=>$ict4,'ict5'=>$ict5,'ict6'=>$ict6,'ict7'=>$ict7,'ict8'=>$ict8]);
    }
    Public function getSedangDikerjakan($usr_fullname)
    {
        $ict = DB::table('ireq_dtl as id')
        ->select('imm.ireq_no','id.ireq_id','id.ireq_desc','id.ireq_qty','id.ireq_remark','id.ireqd_id','dr.div_name',
            'imm.ireq_user', 'id.ireq_assigned_to', 'llr.lookup_desc as ireq_status', 'imm.ireq_requestor',
            'vr.name as ireq_bu','lr.lookup_desc as ireq_type',DB::raw("TO_CHAR(imm.ireq_date,' dd Mon YYYY') as ireq_date"),
            DB::raw("(im.invent_code ||'-'|| im.invent_desc) as name"))
        ->leftjoin('invent_mst as im','id.invent_code','im.invent_code')
        ->leftjoin('lookup_refs as lr','id.ireq_type','lr.lookup_code')
        ->leftjoin('ireq_mst as imm','id.ireq_id','imm.ireq_id')        
        ->leftjoin('lookup_refs as llr','imm.ireq_status','llr.lookup_code')
        ->leftjoin('vcompany_refs as vr','imm.ireq_bu','vr.company_code')
        ->leftjoin('divisi_refs as dr','imm.ireq_divisi_user','dr.div_id')
        ->where('id.ireq_status','T')
        ->where('id.ireq_assigned_to',$usr_fullname)
        ->whereRaw('LOWER(lr.lookup_type) LIKE ? ',[trim(strtolower('req_type')).'%'])
        ->whereRaw('LOWER(llr.lookup_type) LIKE ? ',[trim(strtolower('ict_status')).'%'])
        ->orderBy('id.creation_date','ASC')
        ->get();

        $ict1 = DB::table('ireq_dtl as id')
        ->select('imm.ireq_no','id.ireq_desc','id.ireq_qty','id.ireq_remark','id.ireqd_id','dr.div_name',
            'imm.ireq_user', 'id.ireq_assigned_to', 'llr.lookup_desc as ireq_status', 'imm.ireq_requestor',
            'vr.name as ireq_bu','lr.lookup_desc as ireq_type',DB::raw("TO_CHAR(imm.ireq_date,' dd Mon YYYY') as ireq_date"),
            DB::raw("(im.invent_code ||'-'|| im.invent_desc) as name"))
        ->leftjoin('invent_mst as im','id.invent_code','im.invent_code')
        ->leftjoin('lookup_refs as lr','id.ireq_type','lr.lookup_code')
        ->leftjoin('ireq_mst as imm','id.ireq_id','imm.ireq_id')        
        ->leftjoin('lookup_refs as llr','imm.ireq_status','llr.lookup_code')
        ->leftjoin('vcompany_refs as vr','imm.ireq_bu','vr.company_code')
        ->leftjoin('divisi_refs as dr','imm.ireq_divisi_user','dr.div_id')
        ->where('id.ireq_status','D')
        ->where('id.ireq_assigned_to',$usr_fullname)
        ->whereRaw('LOWER(lr.lookup_type) LIKE ? ',[trim(strtolower('req_type')).'%'])
        ->whereRaw('LOWER(llr.lookup_type) LIKE ? ',[trim(strtolower('ict_status')).'%'])
        ->orderBy('id.creation_date','ASC')
        ->get();

        $ict2 = DB::table('ireq_dtl as id')
        ->select('imm.ireq_no','id.ireq_desc','id.ireq_qty','id.ireq_remark','id.ireqd_id','dr.div_name',
            'imm.ireq_user', 'id.ireq_assigned_to', 'llr.lookup_desc as ireq_status', 'imm.ireq_requestor',
            'vr.name as ireq_bu','lr.lookup_desc as ireq_type',DB::raw("TO_CHAR(imm.ireq_date,' dd Mon YYYY') as ireq_date"),
            DB::raw("(im.invent_code ||'-'|| im.invent_desc) as name"))
        ->leftjoin('invent_mst as im','id.invent_code','im.invent_code')
        ->leftjoin('lookup_refs as lr','id.ireq_type','lr.lookup_code')
        ->leftjoin('ireq_mst as imm','id.ireq_id','imm.ireq_id')        
        ->leftjoin('lookup_refs as llr','imm.ireq_status','llr.lookup_code')
        ->leftjoin('vcompany_refs as vr','imm.ireq_bu','vr.company_code')
        ->leftjoin('divisi_refs as dr','imm.ireq_divisi_user','dr.div_id')
        ->where('id.ireq_status','C')
        ->where('id.ireq_assigned_to',$usr_fullname)
        ->whereRaw('LOWER(lr.lookup_type) LIKE ? ',[trim(strtolower('req_type')).'%'])
        ->whereRaw('LOWER(llr.lookup_type) LIKE ? ',[trim(strtolower('ict_status')).'%'])
        ->orderBy('id.creation_date','ASC')
        ->get();
        
        return response()->json(['ict'=>$ict,'ict1'=>$ict1,'ict2'=>$ict2]);
    }
    public function ictDivisi4()
    {
        $ict = DB::table('ireq_mst as im')
        ->select('im.ireq_id','im.ireq_no','im.ireq_date','im.ireq_user','im.ireq_requestor','im.ireq_status','dr.div_name','lr.lookup_desc as ireq_statuss')
        ->leftjoin('divisi_refs as dr','im.ireq_divisi_user','dr.div_id')
        ->leftjoin('lookup_refs as lr','im.ireq_status','lr.lookup_code')
        ->whereRaw('LOWER(lr.lookup_type) LIKE ? ',[trim(strtolower('ict_status')).'%'])
        ->where('im.ireq_status','NA2')
        ->Orwhere('im.ireq_status','NA1')
        ->groupBy('lr.lookup_desc','im.ireq_id','im.ireq_no','im.ireq_date','im.ireq_user','im.ireq_status','im.ireq_requestor','dr.div_name','im.creation_date','im.ireq_status',)
        ->orderBy('im.creation_date','ASC')
        ->get(); 

        $ict1 = DB::table('ireq_mst as im')
        ->select('im.ireq_id','im.ireq_no','im.ireq_date','im.ireq_user','im.ireq_requestor',
        'dr.div_name','lr.lookup_desc as ireq_status')
        ->leftjoin('divisi_refs as dr','im.ireq_divisi_user','dr.div_id')
        ->leftjoin('lookup_refs as lr','im.ireq_status','lr.lookup_code')
        ->whereRaw('LOWER(lr.lookup_type) LIKE ? ',[trim(strtolower('ict_status')).'%'])
        ->where('im.ireq_status','A2')
        ->orwhere('im.ireq_status','A1')
        ->groupBy('lr.lookup_desc','im.ireq_id','im.ireq_no','im.ireq_date','im.ireq_user','im.ireq_requestor','dr.div_name','im.creation_date','im.ireq_status')
        ->orderBy('im.creation_date','ASC')
        ->get(); 

        $ict2 = DB::table('ireq_mst as im')
        ->select('im.ireq_id','im.ireq_no','im.ireq_date','im.ireq_user','im.ireq_requestor','dr.div_name','im.ireq_reason','lr.lookup_desc as ireq_status')
        ->leftjoin('divisi_refs as dr','im.ireq_divisi_user','dr.div_id')
        ->leftjoin('lookup_refs as lr','im.ireq_status','lr.lookup_code')
        ->whereRaw('LOWER(lr.lookup_type) LIKE ? ',[trim(strtolower('ict_status')).'%'])
        ->where('im.ireq_status','RR')
        ->orwhere('im.ireq_status','RA1')
        ->orwhere('im.ireq_status','RA2')
        ->groupBy('lr.lookup_desc','im.ireq_id','im.ireq_no','im.ireq_date','im.ireq_user','im.ireq_requestor','dr.div_name','im.creation_date','im.ireq_status','im.ireq_reason')
        ->orderBy('im.creation_date','ASC')
        ->get(); 

        $ict3 = DB::table('ireq_mst as im')
        ->select('im.ireq_id','im.ireq_no','im.ireq_date','im.ireq_user','im.ireq_requestor','dr.div_name','im.ireq_assigned_to','lr.lookup_desc as ireq_status')
        ->leftjoin('divisi_refs as dr','im.ireq_divisi_user','dr.div_id')
        ->leftjoin('lookup_refs as lr','im.ireq_status','lr.lookup_code')
        ->whereRaw('LOWER(lr.lookup_type) LIKE ? ',[trim(strtolower('ict_status')).'%'])
        ->where('im.ireq_status','T')
        ->groupBy('lr.lookup_desc','im.ireq_id','im.ireq_no','im.ireq_date','im.ireq_user','im.ireq_requestor','dr.div_name','im.creation_date','im.ireq_status','im.ireq_assigned_to')
        ->orderBy('im.creation_date','ASC')
        ->get();

        $ict4 = DB::Table('v_ireq_mst_sudah_dikerjakan')->get();
        $ict5 = DB::Table('v_ireq_mst_selesai')->get();

        $ict6 = DB::table('ireq_mst as id')
        ->select('id.ireq_id','id.ireq_no','id.ireq_user','id.ireq_date','dr.div_name','id.ireq_requestor','lr.lookup_desc as ireq_status')
        ->leftjoin('divisi_refs as dr','id.ireq_divisi_user','dr.div_id')
        ->leftjoin('lookup_refs as lr','id.ireq_status','lr.lookup_code')
        ->whereRaw('LOWER(lr.lookup_type) LIKE ? ',[trim(strtolower('ict_status')).'%'])
        ->whereNotNull('id.ireq_status')
        ->orderBy(DB::raw("CASE WHEN id.ireq_status = 'P' Then 1 WHEN id.ireq_status = 'NA1' Then
         2 WHEN id.ireq_status = 'NA2' Then 3 WHEN
          id.ireq_status = 'A1' Then 4 WHEN id.ireq_status = 'A2' Then 5
          WHEN id.ireq_status = 'RR' Then 6 WHEN id.ireq_status = 'RA1' Then 7
          WHEN id.ireq_status = 'RA2' THEN 8 WHEN id.ireq_status = 'T' Then 9 
          WHEN id.ireq_status = 'D' Then 10 WHEN id.ireq_status = 'C' Then 11 end "))
        ->get();

        $ict7 = DB::table('ireq_mst as im')
        ->select('im.ireq_id','im.ireq_no','im.ireq_date','im.ireq_user','im.ireq_requestor','im.ireq_status','dr.div_name','lr.lookup_desc as ireq_status')
        ->leftjoin('divisi_refs as dr','im.ireq_divisi_user','dr.div_id')
        ->leftjoin('lookup_refs as lr','im.ireq_status','lr.lookup_code')
        ->whereRaw('LOWER(lr.lookup_type) LIKE ? ',[trim(strtolower('ict_status')).'%'])
        ->where('im.ireq_status','P')
        ->groupBy('lr.lookup_desc','im.ireq_id','im.ireq_no','im.ireq_date','im.ireq_user','im.ireq_status','im.ireq_requestor','dr.div_name','im.creation_date','im.ireq_status',)
        ->orderBy('im.creation_date','ASC')
        ->get(); 

        return response()->json(['ict'=>$ict,'ict1'=>$ict1,'ict2'=>$ict2,'ict3'=>$ict3,'ict4'=>$ict4,'ict5'=>$ict5,'ict6'=>$ict6,'ict7'=>$ict7],200);
    }
    function totalRequest($usr_name)
    {
        $ict = DB::table('ireq_mst as id')
            ->leftjoin('lookup_refs as lr','id.ireq_status','lr.lookup_code')   
            ->select('id.ireq_id','id.ireq_no','id.ireq_user','id.ireq_date',
                'lr.lookup_desc as ireq_status')
            ->whereRaw('LOWER(lr.lookup_type) LIKE ? ',[trim(strtolower('ict_status')).'%'])
            ->where('id.created_by',$usr_name)
            ->whereNotNull('id.ireq_status')
            ->orderBy(DB::raw("CASE WHEN id.ireq_status = 'P' Then 1 WHEN id.ireq_status = 'NA1' Then
             2 WHEN id.ireq_status = 'NA2' Then 3 WHEN
              id.ireq_status = 'A1' Then 4 WHEN id.ireq_status = 'A2' Then 5
              WHEN id.ireq_status = 'RR' Then 6 WHEN id.ireq_status = 'RA1' Then 7
              WHEN id.ireq_status = 'RA2' THEN 8 WHEN id.ireq_status = 'T' Then 9 
              WHEN id.ireq_status = 'D' Then 10 WHEN id.ireq_status = 'C' Then 11 end "))
            ->get();
        return response()->json($ict);
    }
    Public function save(Request $request)
    {
        $message = [
            'tgl.required'=>'Tgl. Request Belum Diisi',
        ];
            $request->validate([
                'tgl' => 'required',
            ],$message);
        $newDate = Carbon::createFromFormat('D M d Y H:i:s e+',$request->tgl)->copy()->tz('Asia/Jakarta')->format('Y-m-d');
        $ict = Ict::Create([
            'ireq_date' => $newDate,
            'ireq_type' => $request->tipereq,
            'ireq_requestor'=> Auth::user()->usr_name,
            'ireq_divisi_requestor'=> Auth::user()->div_id,
            'ireq_user' => $request->user_name,
            'ireq_divisi_user'=>$request->user_divisi,
            'ireq_bu' => $request->bisnis,
            'creation_date' => $this->newCreation,
            'created_by' => Auth::user()->usr_name,
            'program_name'=>"Ict_Save",
        ]);
        return response()->json($ict,200);
    }
    Public function edit($code)
    {
        $ict = DB::table('ireq_mst as im')
            ->select('im.ireq_id','im.ireq_no','im.ireq_type','im.ireq_date','im.ireq_bu','im.ireq_divisi_user','im.ireq_user')
            ->leftjoin('lookup_refs as lr','im.ireq_type','lr.lookup_code')
            ->where('im.ireq_id',$code)
            ->first();
            return response()->json($ict);
    }
    Public function update(Request $request, $code)
    {
        $message = [
            'ireq_date.required'=>'Tgl. Request Belum Diisi',
            // 'ireq_type.required'=>'Tipe Request Belum diisi',
            'ireq_bu.required'=>'Bisnis Unit Belum Diisi',
            'ireq_user.required'=>'Pengguna Belum diisi',
            'ireq_divisi_user.required'=>'Divisi Pengguna Belum Diisi'
        ];
        $request->validate([
                'ireq_date' => 'required',
                // 'ireq_type'=>'required',
                'ireq_bu'=>'required',
                'ireq_user' =>'required',
                'ireq_divisi_user'=>'required'
        ],$message);

        $newDate = Carbon::parse($request->ireq_date)->copy()->tz('Asia/Jakarta')->format('Y-m-d');
        $ict = Ict::where('ireq_id',$code)->first();
        $ict->ireq_date = $newDate;
        $ict->ireq_type = $request->ireq_type;
        $ict->ireq_user = $request->ireq_user;
        $ict->ireq_divisi_user = $request->ireq_divisi_user;
        $ict->ireq_bu = $request->ireq_bu;
        $ict->last_update_date = $this->newUpdate;
        $ict->last_updated_by = Auth::user()->usr_name;
        $ict->program_name = "Ict_Update";
        $ict->save();
        $msg = [
            'success' => true,
            'message' => 'Updated Successfully'
        ];
        return response()->json($msg);
    }
    Public function delete($ireq_id)
    {
        $ict = Ict::find($ireq_id);
        $dtl= DB::table('ireq_dtl')->where('ireq_id',$ireq_id)->delete();
          $ict->delete();
          return response()->json('Successfully deleted');
    }
    Public function getNoreq()
    {
        $ict = Ict::select('ireq_no as name','ireq_id as code')
                ->orderBy('ireq_no','ASC')
                ->get();
            return response()->json($ict);
    }
    Public function getNameBu($noreq)
    {
        $ict = DB::table('ireq_mst as im')
        ->select('im.ireq_requestor as req','im.ireq_no','im.ireq_id','vr.name as bu',
                DB::raw("TO_CHAR(im.ireq_date, 'dd Mon YYYY') as ireq_date"))
        ->join('vcompany_refs as vr','im.ireq_bu','vr.company_code')
        ->where('im.ireq_id',$noreq)
        ->first();
            return response()->json($ict);
    }
    Public function cetak_pdf_atasan_permohonan($usr_name)
    {
        $ict =  DB::table('ireq_mst as im')
        ->select('im.ireq_no','im.ireq_requestor','vr.name as ireq_bu','lr.lookup_desc as ireq_type','im.ireq_user','dr.div_name',
                DB::raw("TO_CHAR(im.ireq_date,' dd Mon YYYY') as ireq_date"),'llr.lookup_desc as ireq_status')
        ->leftjoin('vcompany_refs as vr','im.ireq_bu','vr.company_code')
        ->leftJoin('lookup_refs as lr',function ($join) {
            $join->on('im.ireq_type','lr.lookup_code')
                  ->whereRaw('LOWER(lr.lookup_type) LIKE ? ',[trim(strtolower('req_type')).'%']);
        })
        ->leftjoin('lookup_refs as llr','im.ireq_status','llr.lookup_code')
        ->leftjoin('divisi_refs as dr','im.ireq_divisi_user','dr.div_id')
        ->where(function($query){
            return $query
            ->where('im.ireq_status','P')
            ->orwhere('im.ireq_status','NA1')
            ->orwhere('im.ireq_status','NA2');
        })
        ->where('dr.div_verificator',$usr_name)
        ->whereRaw('LOWER(llr.lookup_type) LIKE ? ',[trim(strtolower('ict_status')).'%'])
        ->orderBy('im.creation_date','ASC')
        ->get();
        return view('pdf/Laporan_IctRequest_Permohonan', compact('ict'));
    }
    public function cetak_excel_atasan_permohonan($usr_name)
    {
        $newCreation = Carbon::parse($this->date)->copy()->tz('Asia/Jakarta')->format('d M Y');
        return Excel::download(new IctExportPermohonanAtasan($usr_name),'Laporan ICT Request '.$newCreation.'.xlsx');
    }
    Public function cetak_pdf_atasan_verifikasi($usr_name)
    {
        $ict =  DB::table('ireq_mst as im')
        ->select('im.ireq_no','im.ireq_requestor','vr.name as ireq_bu','lr.lookup_desc as ireq_type','im.ireq_user','dr.div_name',
                DB::raw("TO_CHAR(im.ireq_date,' dd Mon YYYY') as ireq_date"),'llr.lookup_desc as ireq_status')
        ->leftjoin('vcompany_refs as vr','im.ireq_bu','vr.company_code')
        ->leftJoin('lookup_refs as lr',function ($join) {
            $join->on('im.ireq_type','lr.lookup_code')
                  ->whereRaw('LOWER(lr.lookup_type) LIKE ? ',[trim(strtolower('req_type')).'%']);
        })
        ->leftjoin('lookup_refs as llr','im.ireq_status','llr.lookup_code')
        ->leftjoin('divisi_refs as dr','im.ireq_divisi_user','dr.div_id')
        ->where(function($query){
            return $query
            ->where('im.ireq_status','A1')
            ->orwhere('im.ireq_status','A2');
        })
        ->where('dr.div_verificator',$usr_name)
        ->whereRaw('LOWER(llr.lookup_type) LIKE ? ',[trim(strtolower('ict_status')).'%'])
        ->orderBy('im.creation_date','ASC')
        ->get();
        return view('pdf/Laporan_IctRequest_Permohonan', compact('ict'));
    }
    public function cetak_excel_atasan_verifikasi($usr_name)
    {
        $newCreation = Carbon::parse($this->date)->copy()->tz('Asia/Jakarta')->format('d M Y');
        return Excel::download(new IctExportVerifikasiAtasan($usr_name),'Laporan ICT Request '.$newCreation.'.xlsx');
    }
    Public function cetak_pdf_atasan_reject($usr_name)
    {
        $ict =  DB::table('ireq_mst as im')
        ->select('im.ireq_no','im.ireq_requestor','vr.name as ireq_bu','im.ireq_reason','lr.lookup_desc as ireq_type','im.ireq_user','dr.div_name',
                DB::raw("TO_CHAR(im.ireq_date,' dd Mon YYYY') as ireq_date"),'llr.lookup_desc as ireq_status')
        ->leftjoin('vcompany_refs as vr','im.ireq_bu','vr.company_code')
        ->leftJoin('lookup_refs as lr',function ($join) {
            $join->on('im.ireq_type','lr.lookup_code')
                  ->whereRaw('LOWER(lr.lookup_type) LIKE ? ',[trim(strtolower('req_type')).'%']);
        })
        ->leftjoin('lookup_refs as llr','im.ireq_status','llr.lookup_code')
        ->leftjoin('divisi_refs as dr','im.ireq_divisi_user','dr.div_id')
        ->where(function($query){
            return $query
            ->where('im.ireq_status','RR')
            ->orwhere('im.ireq_status','RA1')
            ->orwhere('im.ireq_status','RA2');
        })
        ->where('dr.div_verificator',$usr_name)
        ->whereRaw('LOWER(llr.lookup_type) LIKE ? ',[trim(strtolower('ict_status')).'%'])
        ->orderBy('im.creation_date','ASC')
        ->get();
        return view('pdf/Laporan_IctRequest_Reject', compact('ict'));
    }
    public function cetak_excel_atasan_reject($usr_name)
    {
        $newCreation = Carbon::parse($this->date)->copy()->tz('Asia/Jakarta')->format('d M Y');
        return Excel::download(new IctExportRejectAtasan($usr_name),'Laporan ICT Request '.$newCreation.'.xlsx');
    }
    Public function cetak_pdf_atasan_sedang_dikerjakan($usr_name)
    {
        $ict =  DB::table('ireq_mst as im')
        ->select('im.ireq_no','im.ireq_requestor','vr.name as ireq_bu','im.ireq_reason','lr.lookup_desc as ireq_type','im.ireq_user','dr.div_name','im.ireq_assigned_to',
                DB::raw("TO_CHAR(im.ireq_date,' dd Mon YYYY') as ireq_date"),'llr.lookup_desc as ireq_status')
        ->leftjoin('vcompany_refs as vr','im.ireq_bu','vr.company_code')
        ->leftJoin('lookup_refs as lr',function ($join) {
            $join->on('im.ireq_type','lr.lookup_code')
                  ->whereRaw('LOWER(lr.lookup_type) LIKE ? ',[trim(strtolower('req_type')).'%']);
        })
        ->leftjoin('lookup_refs as llr','im.ireq_status','llr.lookup_code')
        ->leftjoin('divisi_refs as dr','im.ireq_divisi_user','dr.div_id')
        ->where('im.ireq_status','T')
        ->where('dr.div_verificator',$usr_name)
        ->whereRaw('LOWER(llr.lookup_type) LIKE ? ',[trim(strtolower('ict_status')).'%'])
        ->orderBy('im.creation_date','ASC')
        ->get();
        return view('pdf/Laporan_IctRequest_Sedang_Dikerjakan', compact('ict'));
    }
    public function cetak_excel_atasan_sedang_dikerjakan($usr_name)
    {
        $newCreation = Carbon::parse($this->date)->copy()->tz('Asia/Jakarta')->format('d M Y');
        return Excel::download(new IctExportAtasanSedangDikerjakan($usr_name),'Laporan ICT Request '.$newCreation.'.xlsx');
    }
    Public function cetak_pdf_atasan_sudah_dikerjakan($usr_name)
    {
        $ict =  DB::table('ireq_dtl as id')
        ->select('imm.ireq_no','id.ireq_desc','id.ireq_qty','id.ireq_remark','id.ireqd_id','dr.div_name',
            'imm.ireq_user', 'id.ireq_assigned_to', 'llr.lookup_desc as ireq_status', 'imm.ireq_requestor',
            'vr.name as ireq_bu','lr.lookup_desc as ireq_type',DB::raw("TO_CHAR(imm.ireq_date,' dd Mon YYYY') as ireq_date"),
            DB::raw("(im.invent_code ||'-'|| im.invent_desc) as name"))
        ->leftjoin('invent_mst as im','id.invent_code','im.invent_code')
        ->leftjoin('lookup_refs as lr','id.ireq_type','lr.lookup_code')
        ->join('ireq_mst as imm','id.ireq_id','imm.ireq_id')        
        ->leftjoin('lookup_refs as llr','imm.ireq_status','llr.lookup_code')
        ->leftjoin('vcompany_refs as vr','imm.ireq_bu','vr.company_code')
        ->leftjoin('divisi_refs as dr','imm.ireq_divisi_user','dr.div_id')
        ->where('id.ireq_status','D')
        ->where('dr.div_verificator',$usr_name)
        ->whereRaw('LOWER(lr.lookup_type) LIKE ? ',[trim(strtolower('req_type')).'%'])
        ->whereRaw('LOWER(llr.lookup_type) LIKE ? ',[trim(strtolower('ict_status')).'%'])
        ->orderBy('id.creation_date','ASC')
        ->get();
        return view('pdf/Laporan_IctRequest_Sudah_Dikerjakan', compact('ict'));
    }
    public function cetak_excel_atasan_sudah_dikerjakan($usr_name)
    {
        $newCreation = Carbon::parse($this->date)->copy()->tz('Asia/Jakarta')->format('d M Y');
        return Excel::download(new IctExportAtasanSudahDikerjakan($usr_name),'Laporan Ict Request '.$newCreation.'.xlsx');
    }
    Public function cetak_pdf_atasan_selesai($usr_name)
    {
        $ict =  DB::table('ireq_dtl as id')
        ->select('imm.ireq_no','id.ireq_desc','id.ireq_qty','id.ireq_remark','id.ireqd_id','dr.div_name',
        'imm.ireq_user', 'id.ireq_assigned_to', 'llr.lookup_desc as ireq_status', 'imm.ireq_requestor',
        'vr.name as ireq_bu','lr.lookup_desc as ireq_type',DB::raw("TO_CHAR(imm.ireq_date,' dd Mon YYYY') as ireq_date"),
        DB::raw("(im.invent_code ||'-'|| im.invent_desc) as name"))
        ->leftjoin('invent_mst as im','id.invent_code','im.invent_code')
        ->leftjoin('lookup_refs as lr','id.ireq_type','lr.lookup_code')
        ->join('ireq_mst as imm','id.ireq_id','imm.ireq_id')        
        ->leftjoin('lookup_refs as llr','imm.ireq_status','llr.lookup_code')
        ->leftjoin('vcompany_refs as vr','imm.ireq_bu','vr.company_code')
        ->leftjoin('divisi_refs as dr','imm.ireq_divisi_user','dr.div_id')
        ->where('id.ireq_status','C')
        ->where('dr.div_verificator',$usr_name)
        ->whereRaw('LOWER(lr.lookup_type) LIKE ? ',[trim(strtolower('req_type')).'%'])
        ->whereRaw('LOWER(llr.lookup_type) LIKE ? ',[trim(strtolower('ict_status')).'%'])
        ->orderBy('id.creation_date','ASC')
        ->get();
        return view('pdf/Laporan_IctRequest_Selesai', compact('ict'));
    }
    public function cetak_excel_atasan_selesai($usr_name)
    {
        $newCreation = Carbon::parse($this->date)->copy()->tz('Asia/Jakarta')->format('d M Y');
        return Excel::download(new IctExportAtasanSelesai($usr_name),'Laporan Ict Request '.$newCreation.'.xlsx');
    }
    Public function cetak_pdf_reviewer_permohonan()
    {
        $ict =  DB::table('ireq_mst as im')
        ->select('im.ireq_no','im.ireq_requestor','vr.name as ireq_bu','lr.lookup_desc as ireq_type','im.ireq_user','dr.div_name',
                DB::raw("TO_CHAR(im.ireq_date,' dd Mon YYYY') as ireq_date"),'llr.lookup_desc as ireq_status')
        ->leftjoin('vcompany_refs as vr','im.ireq_bu','vr.company_code')
        ->leftJoin('lookup_refs as lr',function ($join) {
            $join->on('im.ireq_type','lr.lookup_code')
                  ->whereRaw('LOWER(lr.lookup_type) LIKE ? ',[trim(strtolower('req_type')).'%']);
        })
        ->leftjoin('divisi_refs as dr','im.ireq_divisi_user','dr.div_id')
        ->leftjoin('lookup_refs as llr','im.ireq_status','llr.lookup_code')
        ->where('im.ireq_status','P')
        ->whereRaw('LOWER(llr.lookup_type) LIKE ? ',[trim(strtolower('ict_status')).'%'])
        ->orderBy('im.creation_date','ASC')
        ->get();
        return view('pdf/Laporan_IctRequest_Permohonan', compact('ict'));
    }
    public function cetak_excel_reviewer_permohonan()
    {
        $newCreation = Carbon::parse($this->date)->copy()->tz('Asia/Jakarta')->format('d M Y');
        return Excel::download(new IctExportReviewerPermohonan,'Laporan ICT Request '.$newCreation.'.xlsx');
    }
    Public function cetak_pdf_reviewer_atasan_divisi()
    {
        $ict =  DB::table('ireq_mst as im')
        ->select('im.ireq_no','im.ireq_requestor','vr.name as ireq_bu','lr.lookup_desc as ireq_type','im.ireq_user','dr.div_name',
                DB::raw("TO_CHAR(im.ireq_date,' dd Mon YYYY') as ireq_date"),'llr.lookup_desc as ireq_status')
        ->leftjoin('vcompany_refs as vr','im.ireq_bu','vr.company_code')
        ->leftJoin('lookup_refs as lr',function ($join) {
            $join->on('im.ireq_type','lr.lookup_code')
                  ->whereRaw('LOWER(lr.lookup_type) LIKE ? ',[trim(strtolower('req_type')).'%']);
        })
        ->leftjoin('divisi_refs as dr','im.ireq_divisi_user','dr.div_id')
        ->leftjoin('lookup_refs as llr','im.ireq_status','llr.lookup_code')
        ->where(function($query){
            return $query
            ->where('im.ireq_status','NA1')
            ->orwhere('im.ireq_status','A1');
        })
        ->whereRaw('LOWER(llr.lookup_type) LIKE ? ',[trim(strtolower('ict_status')).'%'])
        ->orderBy('im.creation_date','ASC')
        ->get();
        return view('pdf/Laporan_IctRequest_Permohonan', compact('ict'));
    }
    public function cetak_excel_reviewer_atasan_divisi()
    {
        $newCreation = Carbon::parse($this->date)->copy()->tz('Asia/Jakarta')->format('d M Y');
        return Excel::download(new IctExportReviewerAtasanDivisi,'Laporan ICT Request '.$newCreation.'.xlsx');
    }
    Public function cetak_pdf_reviewer_ict_manager()
    {
        $ict =  DB::table('ireq_mst as im')
        ->select('im.ireq_no','im.ireq_requestor','vr.name as ireq_bu','lr.lookup_desc as ireq_type','im.ireq_user','dr.div_name',
                DB::raw("TO_CHAR(im.ireq_date,' dd Mon YYYY') as ireq_date"),'llr.lookup_desc as ireq_status')
        ->leftjoin('vcompany_refs as vr','im.ireq_bu','vr.company_code')
        ->leftJoin('lookup_refs as lr',function ($join) {
            $join->on('im.ireq_type','lr.lookup_code')
                  ->whereRaw('LOWER(lr.lookup_type) LIKE ? ',[trim(strtolower('req_type')).'%']);
        })
        ->leftjoin('divisi_refs as dr','im.ireq_divisi_user','dr.div_id')
        ->leftjoin('lookup_refs as llr','im.ireq_status','llr.lookup_code')
        ->where(function($query){
            return $query
            ->where('im.ireq_status','NA2')
            ->orwhere('im.ireq_status','A2');
        })
        ->whereRaw('LOWER(llr.lookup_type) LIKE ? ',[trim(strtolower('ict_status')).'%'])
        ->orderBy('im.creation_date','ASC')
        ->get();
        return view('pdf/Laporan_IctRequest_Permohonan', compact('ict'));
    }
    public function cetak_excel_reviewer_ict_manager()
    {
        $newCreation = Carbon::parse($this->date)->copy()->tz('Asia/Jakarta')->format('d M Y');
        return Excel::download(new IctExportReviewerIctManager,'Laporan ICT Request '.$newCreation.'.xlsx');
    }
    Public function cetak_pdf_reviewer_reject()
    {
        $ict =  DB::table('ireq_mst as im')
        ->select('im.ireq_no','im.ireq_requestor','vr.name as ireq_bu','im.ireq_reason','lr.lookup_desc as ireq_type','im.ireq_user','dr.div_name',
                DB::raw("TO_CHAR(im.ireq_date,' dd Mon YYYY') as ireq_date"),'llr.lookup_desc as ireq_status')
        ->leftjoin('vcompany_refs as vr','im.ireq_bu','vr.company_code')
        ->leftJoin('lookup_refs as lr',function ($join) {
            $join->on('im.ireq_type','lr.lookup_code')
                  ->whereRaw('LOWER(lr.lookup_type) LIKE ? ',[trim(strtolower('req_type')).'%']);
        })
        ->leftjoin('lookup_refs as llr','im.ireq_status','llr.lookup_code')
        ->leftjoin('divisi_refs as dr','im.ireq_divisi_user','dr.div_id')
        ->where(function($query){
            return $query
            ->where('im.ireq_status','RR')
            ->orwhere('im.ireq_status','RA1')
            ->orwhere('im.ireq_status','RA2');
        })
        ->whereRaw('LOWER(llr.lookup_type) LIKE ? ',[trim(strtolower('ict_status')).'%'])
        ->orderBy('im.creation_date','ASC')
        ->get();
        return view('pdf/Laporan_IctRequest_Reject', compact('ict'));
    }
    public function cetak_excel_reviewer_reject()
    {
        $newCreation = Carbon::parse($this->date)->copy()->tz('Asia/Jakarta')->format('d M Y');
        return Excel::download(new IctExportRejectReviewer,'Laporan ICT Request '.$newCreation.'.xlsx');
    }
    Public function cetak_pdf_reviewer_sedang_dikerjakan()
    {
        $ict =  DB::table('ireq_mst as im')
        ->select('im.ireq_no','im.ireq_requestor','vr.name as ireq_bu','im.ireq_reason','lr.lookup_desc as ireq_type','im.ireq_user','dr.div_name','im.ireq_assigned_to',
                DB::raw("TO_CHAR(im.ireq_date,' dd Mon YYYY') as ireq_date"),'llr.lookup_desc as ireq_status')
        ->leftjoin('vcompany_refs as vr','im.ireq_bu','vr.company_code')
        ->leftJoin('lookup_refs as lr',function ($join) {
            $join->on('im.ireq_type','lr.lookup_code')
                  ->whereRaw('LOWER(lr.lookup_type) LIKE ? ',[trim(strtolower('req_type')).'%']);
        })
        ->leftjoin('lookup_refs as llr','im.ireq_status','llr.lookup_code')
        ->leftjoin('divisi_refs as dr','im.ireq_divisi_user','dr.div_id')
        ->where('im.ireq_status','T')
        ->whereRaw('LOWER(llr.lookup_type) LIKE ? ',[trim(strtolower('ict_status')).'%'])
        ->orderBy('im.creation_date','ASC')
        ->get();
        return view('pdf/Laporan_IctRequest_Sedang_Dikerjakan', compact('ict'));
    }
    public function cetak_excel_reviewer_sedang_dikerjakan()
    {
        $newCreation = Carbon::parse($this->date)->copy()->tz('Asia/Jakarta')->format('d M Y');
        return Excel::download(new IctExportReviewerSedangDikerjakan,'Laporan ICT Request '.$newCreation.'.xlsx');
    }
    Public function cetak_pdf_reviewer_sudah_dikerjakan()
    {
        $ict =  DB::table('ireq_dtl as id')
        ->select('imm.ireq_no','id.ireq_desc','id.ireq_qty','id.ireq_remark','id.ireqd_id','dr.div_name',
            'imm.ireq_user', 'id.ireq_assigned_to', 'llr.lookup_desc as ireq_status', 'imm.ireq_requestor',
            'vr.name as ireq_bu','lr.lookup_desc as ireq_type',DB::raw("TO_CHAR(imm.ireq_date,' dd Mon YYYY') as ireq_date"),
            DB::raw("(im.invent_code ||'-'|| im.invent_desc) as name"))
        ->leftjoin('invent_mst as im','id.invent_code','im.invent_code')
        ->leftjoin('lookup_refs as lr','id.ireq_type','lr.lookup_code')
        ->join('ireq_mst as imm','id.ireq_id','imm.ireq_id')        
        ->leftjoin('lookup_refs as llr','imm.ireq_status','llr.lookup_code')
        ->leftjoin('vcompany_refs as vr','imm.ireq_bu','vr.company_code')
        ->leftjoin('divisi_refs as dr','imm.ireq_divisi_user','dr.div_id')
        ->where('id.ireq_status','D')
        ->whereRaw('LOWER(lr.lookup_type) LIKE ? ',[trim(strtolower('req_type')).'%'])
        ->whereRaw('LOWER(llr.lookup_type) LIKE ? ',[trim(strtolower('ict_status')).'%'])
        ->orderBy('id.creation_date','ASC')
        ->get();
        return view('pdf/Laporan_IctRequest_Sudah_Dikerjakan', compact('ict'));
    }
    public function cetak_excel_reviewer_sudah_dikerjakan()
    {
        $newCreation = Carbon::parse($this->date)->copy()->tz('Asia/Jakarta')->format('d M Y');
        return Excel::download(new IctExportReviewerSudahDikerjakan,'Laporan Ict Request '.$newCreation.'.xlsx');
    }
    Public function cetak_pdf_reviewer_selesai()
    {
        $ict =  DB::table('ireq_dtl as id')
        ->select('imm.ireq_no','id.ireq_desc','id.ireq_qty','id.ireq_remark','id.ireqd_id','dr.div_name',
        'imm.ireq_user', 'id.ireq_assigned_to', 'llr.lookup_desc as ireq_status', 'imm.ireq_requestor',
        'vr.name as ireq_bu','lr.lookup_desc as ireq_type',DB::raw("TO_CHAR(imm.ireq_date,' dd Mon YYYY') as ireq_date"),
        DB::raw("(im.invent_code ||'-'|| im.invent_desc) as name"))
        ->leftjoin('invent_mst as im','id.invent_code','im.invent_code')
        ->leftjoin('lookup_refs as lr','id.ireq_type','lr.lookup_code')
        ->join('ireq_mst as imm','id.ireq_id','imm.ireq_id')        
        ->leftjoin('lookup_refs as llr','imm.ireq_status','llr.lookup_code')
        ->leftjoin('vcompany_refs as vr','imm.ireq_bu','vr.company_code')
        ->leftjoin('divisi_refs as dr','imm.ireq_divisi_user','dr.div_id')
        ->where('id.ireq_status','C')
        ->whereRaw('LOWER(lr.lookup_type) LIKE ? ',[trim(strtolower('req_type')).'%'])
        ->whereRaw('LOWER(llr.lookup_type) LIKE ? ',[trim(strtolower('ict_status')).'%'])
        ->orderBy('id.creation_date','ASC')
        ->get();
        return view('pdf/Laporan_IctRequest_Selesai', compact('ict'));
    }
    public function cetak_excel_reviewer_selesai()
    {
        $newCreation = Carbon::parse($this->date)->copy()->tz('Asia/Jakarta')->format('d M Y');
        return Excel::download(new IctExportReviewerSelesai,'Laporan Ict Request '.$newCreation.'.xlsx');
    }
    Public function cetak_pdf_manager_permohonan()
    {
        $ict =  DB::table('ireq_mst as im')
        ->select('im.ireq_no','im.ireq_requestor','vr.name as ireq_bu','lr.lookup_desc as ireq_type','im.ireq_user','dr.div_name',
                DB::raw("TO_CHAR(im.ireq_date,' dd Mon YYYY') as ireq_date"),'llr.lookup_desc as ireq_status')
        ->leftjoin('vcompany_refs as vr','im.ireq_bu','vr.company_code')
        ->leftJoin('lookup_refs as lr',function ($join) {
            $join->on('im.ireq_type','lr.lookup_code')
                  ->whereRaw('LOWER(lr.lookup_type) LIKE ? ',[trim(strtolower('req_type')).'%']);
        })
        ->leftjoin('divisi_refs as dr','im.ireq_divisi_user','dr.div_id')
        ->leftjoin('lookup_refs as llr','im.ireq_status','llr.lookup_code')
        ->where(function($query){
            return $query
            ->where('im.ireq_status','NA1')
            ->Orwhere('im.ireq_status','NA2');
        })
        ->whereRaw('LOWER(llr.lookup_type) LIKE ? ',[trim(strtolower('ict_status')).'%'])
        ->orderBy('im.creation_date','ASC')
        ->get();
        return view('pdf/Laporan_IctRequest_Permohonan', compact('ict'));
    }
    public function cetak_excel_manager_permohonan()
    {
        $newCreation = Carbon::parse($this->date)->copy()->tz('Asia/Jakarta')->format('d M Y');
        return Excel::download(new IctExportManagerPermohonan,'Laporan ICT Request '.$newCreation.'.xlsx');
    }
    Public function cetak_pdf_manager_verifikasi()
    {
        $ict =  DB::table('ireq_mst as im')
        ->select('im.ireq_no','im.ireq_requestor','vr.name as ireq_bu','lr.lookup_desc as ireq_type','im.ireq_user','dr.div_name',
                DB::raw("TO_CHAR(im.ireq_date,' dd Mon YYYY') as ireq_date"),'llr.lookup_desc as ireq_status')
        ->leftjoin('vcompany_refs as vr','im.ireq_bu','vr.company_code')
        ->leftJoin('lookup_refs as lr',function ($join) {
            $join->on('im.ireq_type','lr.lookup_code')
                  ->whereRaw('LOWER(lr.lookup_type) LIKE ? ',[trim(strtolower('req_type')).'%']);
        })
        ->leftjoin('lookup_refs as llr','im.ireq_status','llr.lookup_code')
        ->leftjoin('divisi_refs as dr','im.ireq_divisi_user','dr.div_id')
        ->where(function($query){
            return $query
            ->where('im.ireq_status','A1')
            ->orwhere('im.ireq_status','A2');
        })
        ->whereRaw('LOWER(llr.lookup_type) LIKE ? ',[trim(strtolower('ict_status')).'%'])
        ->orderBy('im.creation_date','ASC')
        ->get();
        return view('pdf/Laporan_IctRequest_Permohonan', compact('ict'));
    }
    public function cetak_excel_manager_verifikasi()
    {
        $newCreation = Carbon::parse($this->date)->copy()->tz('Asia/Jakarta')->format('d M Y');
        return Excel::download(new IctExportVerifikasiManager(),'Laporan ICT Request '.$newCreation.'.xlsx');
    }
    Public function cetak_pdf_manager_reject()
    {
        $ict =  DB::table('ireq_mst as im')
        ->select('im.ireq_no','im.ireq_requestor','vr.name as ireq_bu','im.ireq_reason','lr.lookup_desc as ireq_type','im.ireq_user','dr.div_name',
                DB::raw("TO_CHAR(im.ireq_date,' dd Mon YYYY') as ireq_date"),'llr.lookup_desc as ireq_status')
        ->leftjoin('vcompany_refs as vr','im.ireq_bu','vr.company_code')
        ->leftJoin('lookup_refs as lr',function ($join) {
            $join->on('im.ireq_type','lr.lookup_code')
                  ->whereRaw('LOWER(lr.lookup_type) LIKE ? ',[trim(strtolower('req_type')).'%']);
        })
        ->leftjoin('lookup_refs as llr','im.ireq_status','llr.lookup_code')
        ->leftjoin('divisi_refs as dr','im.ireq_divisi_user','dr.div_id')
        ->where(function($query){
            return $query
            ->where('im.ireq_status','RR')
            ->orwhere('im.ireq_status','RA1')
            ->orwhere('im.ireq_status','RA2');
        })
        ->whereRaw('LOWER(llr.lookup_type) LIKE ? ',[trim(strtolower('ict_status')).'%'])
        ->orderBy('im.creation_date','ASC')
        ->get();
        return view('pdf/Laporan_IctRequest_Reject', compact('ict'));
    }
    public function cetak_excel_manager_reject()
    {
        $newCreation = Carbon::parse($this->date)->copy()->tz('Asia/Jakarta')->format('d M Y');
        return Excel::download(new IctExportRejectManager,'Laporan ICT Request '.$newCreation.'.xlsx');
    }
    Public function cetak_pdf_manager_sedang_dikerjakan()
    {
        $ict =  DB::table('ireq_mst as im')
        ->select('im.ireq_no','im.ireq_requestor','vr.name as ireq_bu','im.ireq_reason','lr.lookup_desc as ireq_type','im.ireq_user','dr.div_name','im.ireq_assigned_to',
                DB::raw("TO_CHAR(im.ireq_date,' dd Mon YYYY') as ireq_date"),'llr.lookup_desc as ireq_status')
        ->leftjoin('vcompany_refs as vr','im.ireq_bu','vr.company_code')
        ->leftJoin('lookup_refs as lr',function ($join) {
            $join->on('im.ireq_type','lr.lookup_code')
                  ->whereRaw('LOWER(lr.lookup_type) LIKE ? ',[trim(strtolower('req_type')).'%']);
        })
        ->leftjoin('lookup_refs as llr','im.ireq_status','llr.lookup_code')
        ->leftjoin('divisi_refs as dr','im.ireq_divisi_user','dr.div_id')
        ->where('im.ireq_status','T')
        ->whereRaw('LOWER(llr.lookup_type) LIKE ? ',[trim(strtolower('ict_status')).'%'])
        ->orderBy('im.creation_date','ASC')
        ->get();
        return view('pdf/Laporan_IctRequest_Sedang_Dikerjakan', compact('ict'));
    }
    public function cetak_excel_manager_sedang_dikerjakan()
    {
        $newCreation = Carbon::parse($this->date)->copy()->tz('Asia/Jakarta')->format('d M Y');
        return Excel::download(new IctExportManagerSedangDikerjakan,'Laporan ICT Request '.$newCreation.'.xlsx');
    }
    Public function cetak_pdf_manager_sudah_dikerjakan()
    {
        $ict =  DB::table('ireq_dtl as id')
        ->select('imm.ireq_no','id.ireq_desc','id.ireq_qty','id.ireq_remark','id.ireqd_id','dr.div_name',
            'imm.ireq_user', 'id.ireq_assigned_to', 'llr.lookup_desc as ireq_status', 'imm.ireq_requestor',
            'vr.name as ireq_bu','lr.lookup_desc as ireq_type',DB::raw("TO_CHAR(imm.ireq_date,' dd Mon YYYY') as ireq_date"),
            DB::raw("(im.invent_code ||'-'|| im.invent_desc) as name"))
        ->leftjoin('invent_mst as im','id.invent_code','im.invent_code')
        ->leftjoin('lookup_refs as lr','id.ireq_type','lr.lookup_code')
        ->join('ireq_mst as imm','id.ireq_id','imm.ireq_id')        
        ->leftjoin('lookup_refs as llr','imm.ireq_status','llr.lookup_code')
        ->leftjoin('vcompany_refs as vr','imm.ireq_bu','vr.company_code')
        ->leftjoin('divisi_refs as dr','imm.ireq_divisi_user','dr.div_id')
        ->where('id.ireq_status','D')
        ->whereRaw('LOWER(lr.lookup_type) LIKE ? ',[trim(strtolower('req_type')).'%'])
        ->whereRaw('LOWER(llr.lookup_type) LIKE ? ',[trim(strtolower('ict_status')).'%'])
        ->orderBy('id.creation_date','ASC')
        ->get();
        return view('pdf/Laporan_IctRequest_Sudah_Dikerjakan', compact('ict'));
    }
    public function cetak_excel_manager_sudah_dikerjakan()
    {
        $newCreation = Carbon::parse($this->date)->copy()->tz('Asia/Jakarta')->format('d M Y');
        return Excel::download(new IctExportReviewerSudahDikerjakan,'Laporan Ict Request '.$newCreation.'.xlsx');
    }
    Public function cetak_pdf_manager_selesai()
    {
        $ict =  DB::table('ireq_dtl as id')
        ->select('imm.ireq_no','id.ireq_desc','id.ireq_qty','id.ireq_remark','id.ireqd_id','dr.div_name',
        'imm.ireq_user', 'id.ireq_assigned_to', 'llr.lookup_desc as ireq_status', 'imm.ireq_requestor',
        'vr.name as ireq_bu','lr.lookup_desc as ireq_type',DB::raw("TO_CHAR(imm.ireq_date,' dd Mon YYYY') as ireq_date"),
        DB::raw("(im.invent_code ||'-'|| im.invent_desc) as name"))
        ->leftjoin('invent_mst as im','id.invent_code','im.invent_code')
        ->leftjoin('lookup_refs as lr','id.ireq_type','lr.lookup_code')
        ->join('ireq_mst as imm','id.ireq_id','imm.ireq_id')        
        ->leftjoin('lookup_refs as llr','imm.ireq_status','llr.lookup_code')
        ->leftjoin('vcompany_refs as vr','imm.ireq_bu','vr.company_code')
        ->leftjoin('divisi_refs as dr','imm.ireq_divisi_user','dr.div_id')
        ->where('id.ireq_status','C')
        ->whereRaw('LOWER(lr.lookup_type) LIKE ? ',[trim(strtolower('req_type')).'%'])
        ->whereRaw('LOWER(llr.lookup_type) LIKE ? ',[trim(strtolower('ict_status')).'%'])
        ->orderBy('id.creation_date','ASC')
        ->get();
        return view('pdf/Laporan_IctRequest_Selesai', compact('ict'));
    }
    public function cetak_excel_manager_selesai()
    {
        $newCreation = Carbon::parse($this->date)->copy()->tz('Asia/Jakarta')->format('d M Y');
        return Excel::download(new IctExportReviewerSelesai,'Laporan Ict Request '.$newCreation.'.xlsx');
    }
    Public function cetak_pdf_personnel_sedang_dikerjakan($usr_fullname)
    {
        $ict =  DB::table('ireq_dtl as id')
        ->select('imm.ireq_no','id.ireq_assigned_to','id.ireq_desc','id.ireq_qty','id.ireq_remark','id.ireqd_id','dr.div_name',
            'imm.ireq_user', 'id.ireq_assigned_to', 'llr.lookup_desc as ireq_status', 'imm.ireq_requestor',
            'vr.name as ireq_bu','lr.lookup_desc as ireq_type',DB::raw("TO_CHAR(imm.ireq_date,' dd Mon YYYY') as ireq_date"),
            DB::raw("(im.invent_code ||'-'|| im.invent_desc) as name"))
        ->leftjoin('invent_mst as im','id.invent_code','im.invent_code')
        ->leftjoin('lookup_refs as lr','id.ireq_type','lr.lookup_code')
        ->join('ireq_mst as imm','id.ireq_id','imm.ireq_id')        
        ->leftjoin('lookup_refs as llr','imm.ireq_status','llr.lookup_code')
        ->leftjoin('vcompany_refs as vr','imm.ireq_bu','vr.company_code')
        ->leftjoin('divisi_refs as dr','imm.ireq_divisi_user','dr.div_id')
        ->where('id.ireq_status','T')
        ->where('id.ireq_assigned_to',$usr_fullname)
        ->whereRaw('LOWER(lr.lookup_type) LIKE ? ',[trim(strtolower('req_type')).'%'])
        ->whereRaw('LOWER(llr.lookup_type) LIKE ? ',[trim(strtolower('ict_status')).'%'])
        ->orderBy('id.creation_date','ASC')
        ->get();
        return view('pdf/Laporan_IctRequest_Sudah_Dikerjakan', compact('ict'));
    }
    public function cetak_excel_personnel_sedang_dikerjakan($usr_fullname)
    {
        $newCreation = Carbon::parse($this->date)->copy()->tz('Asia/Jakarta')->format('d M Y');
        return Excel::download(new IctExportPersonnelSedangDikerjakan($usr_fullname),'Laporan Ict Request '.$newCreation.'.xlsx');
    }
    Public function cetak_pdf_personnel_sudah_dikerjakan($usr_fullname)
    {
        $ict =  DB::table('ireq_dtl as id')
        ->select('imm.ireq_no','id.ireq_assigned_to','id.ireq_desc','id.ireq_qty','id.ireq_remark','id.ireqd_id','dr.div_name',
            'imm.ireq_user', 'id.ireq_assigned_to', 'llr.lookup_desc as ireq_status', 'imm.ireq_requestor',
            'vr.name as ireq_bu','lr.lookup_desc as ireq_type',DB::raw("TO_CHAR(imm.ireq_date,' dd Mon YYYY') as ireq_date"),
            DB::raw("(im.invent_code ||'-'|| im.invent_desc) as name"))
        ->leftjoin('invent_mst as im','id.invent_code','im.invent_code')
        ->leftjoin('lookup_refs as lr','id.ireq_type','lr.lookup_code')
        ->join('ireq_mst as imm','id.ireq_id','imm.ireq_id')        
        ->leftjoin('lookup_refs as llr','imm.ireq_status','llr.lookup_code')
        ->leftjoin('vcompany_refs as vr','imm.ireq_bu','vr.company_code')
        ->leftjoin('divisi_refs as dr','imm.ireq_divisi_user','dr.div_id')
        ->where('id.ireq_status','D')
        ->where('id.ireq_assigned_to',$usr_fullname)
        ->whereRaw('LOWER(lr.lookup_type) LIKE ? ',[trim(strtolower('req_type')).'%'])
        ->whereRaw('LOWER(llr.lookup_type) LIKE ? ',[trim(strtolower('ict_status')).'%'])
        ->orderBy('id.creation_date','ASC')
        ->get();
        return view('pdf/Laporan_IctRequest_Sudah_Dikerjakan', compact('ict'));
    }
    public function cetak_excel_personnel_sudah_dikerjakan($usr_fullname)
    {
        $newCreation = Carbon::parse($this->date)->copy()->tz('Asia/Jakarta')->format('d M Y');
        return Excel::download(new IctExportPersonnelSudahDikerjakan($usr_fullname),'Laporan Ict Request '.$newCreation.'.xlsx');
    }
    Public function cetak_pdf_personnel_selesai($usr_fullname)
    {
        $ict =  DB::table('ireq_dtl as id')
        ->select('imm.ireq_no','id.ireq_assigned_to','id.ireq_desc','id.ireq_qty','id.ireq_remark','id.ireqd_id','dr.div_name',
            'imm.ireq_user', 'id.ireq_assigned_to', 'llr.lookup_desc as ireq_status', 'imm.ireq_requestor',
            'vr.name as ireq_bu','lr.lookup_desc as ireq_type',DB::raw("TO_CHAR(imm.ireq_date,' dd Mon YYYY') as ireq_date"),
            DB::raw("(im.invent_code ||'-'|| im.invent_desc) as name"))
        ->leftjoin('invent_mst as im','id.invent_code','im.invent_code')
        ->leftjoin('lookup_refs as lr','id.ireq_type','lr.lookup_code')
        ->join('ireq_mst as imm','id.ireq_id','imm.ireq_id')        
        ->leftjoin('lookup_refs as llr','imm.ireq_status','llr.lookup_code')
        ->leftjoin('vcompany_refs as vr','imm.ireq_bu','vr.company_code')
        ->leftjoin('divisi_refs as dr','imm.ireq_divisi_user','dr.div_id')
        ->where('id.ireq_status','C')
        ->where('id.ireq_assigned_to',$usr_fullname)
        ->whereRaw('LOWER(lr.lookup_type) LIKE ? ',[trim(strtolower('req_type')).'%'])
        ->whereRaw('LOWER(llr.lookup_type) LIKE ? ',[trim(strtolower('ict_status')).'%'])
        ->orderBy('id.creation_date','ASC')
        ->get();
        return view('pdf/Laporan_IctRequest_Selesai', compact('ict'));
    }
    public function cetak_excel_personnel_selesai($usr_fullname)
    {
        $newCreation = Carbon::parse($this->date)->copy()->tz('Asia/Jakarta')->format('d M Y');
        return Excel::download(new IctExportPersonnelSelesai($usr_fullname),'Laporan Ict Request '.$newCreation.'.xlsx');
    }
    Public function cetak_pdf_permohonan($usr_name)
    {
        $ict =  DB::table('ireq_mst as im')
        ->select('im.ireq_no','im.ireq_requestor','vr.name as ireq_bu','lr.lookup_desc as ireq_type','im.ireq_user','dr.div_name',
                DB::raw("TO_CHAR(im.ireq_date,' dd Mon YYYY') as ireq_date"),'llr.lookup_desc as ireq_status')
        ->leftjoin('vcompany_refs as vr','im.ireq_bu','vr.company_code')
        ->leftJoin('lookup_refs as lr',function ($join) {
                $join->on('im.ireq_type','lr.lookup_code')
                      ->whereRaw('LOWER(lr.lookup_type) LIKE ? ',[trim(strtolower('req_type')).'%']);
        })
        ->leftjoin('divisi_refs as dr','im.ireq_divisi_user','dr.div_id')
        ->leftjoin('lookup_refs as llr','im.ireq_status','llr.lookup_code')
        ->where('im.ireq_status','P')
        ->where('im.ireq_requestor',$usr_name)
        ->whereRaw('LOWER(llr.lookup_type) LIKE ? ',[trim(strtolower('ict_status')).'%'])
        ->orderBy('im.creation_date','ASC')
        ->get();
        return view('pdf/Laporan_IctRequest_Permohonan', compact('ict'));
    }
    public function cetak_excel_permohonan($usr_name)
    {
        $newCreation = Carbon::parse($this->date)->copy()->tz('Asia/Jakarta')->format('d M Y');
        return Excel::download(new IctExportPermohonan($usr_name),'Laporan ICT Request '.$newCreation.'.xlsx');
    }
    Public function cetak_pdf_tab_reviewer($usr_name)
    {
        $ict =  DB::table('ireq_mst as im')
        ->select('im.ireq_no','im.ireq_requestor','vr.name as ireq_bu','lr.lookup_desc as ireq_type',
            'im.ireq_user','dr.div_name','llr.lookup_desc as ireq_status',DB::raw("TO_CHAR(im.ireq_date,' dd Mon YYYY') as ireq_date"))
        ->leftjoin('vcompany_refs as vr','im.ireq_bu','vr.company_code')
        ->leftJoin('lookup_refs as lr',function ($join) {
            $join->on('im.ireq_type','lr.lookup_code')
                  ->whereRaw('LOWER(lr.lookup_type) LIKE ? ',[trim(strtolower('req_type')).'%']);
        })
        ->leftjoin('lookup_refs as llr','im.ireq_status','llr.lookup_code')
        ->leftjoin('divisi_refs as dr','im.ireq_divisi_user','dr.div_id')
        ->where('im.ireq_requestor',$usr_name)
        ->where(function($query){
            return $query
            ->where('im.ireq_status','NA1')
            ->orwhere('im.ireq_status','NA2');
        })
        ->whereRaw('LOWER(llr.lookup_type) LIKE ? ',[trim(strtolower('ict_status')).'%'])
        ->orderBy('im.creation_date','ASC')
        ->get();
        return view('pdf/Laporan_IctRequest_Tab_Reviewer', compact('ict'));
    }
    public function cetak_excel_tab_reviewer($usr_name)
    {
        $newCreation = Carbon::parse($this->date)->copy()->tz('Asia/Jakarta')->format('d M Y');
        return Excel::download(new IctExportTabReviewer($usr_name),'Laporan Ict Request (Reviewer) '.$newCreation.'.xlsx');
    }
    Public function cetak_pdf_verifikasi($usr_name)
    {
        $ict =  DB::table('ireq_mst as im')
        ->select('im.ireq_no','dr.div_name','im.ireq_user','im.ireq_requestor','vr.name as ireq_bu','lr.lookup_desc as ireq_type','llr.lookup_desc as ireq_status',
                DB::raw("TO_CHAR(im.ireq_date,' dd Mon YYYY') as ireq_date"))
        ->leftjoin('vcompany_refs as vr','im.ireq_bu','vr.company_code')
        ->leftjoin('divisi_refs as dr','im.ireq_divisi_user','dr.div_id')
        ->leftJoin('lookup_refs as lr',function ($join) {
            $join->on('im.ireq_type','lr.lookup_code')
                  ->whereRaw('LOWER(lr.lookup_type) LIKE ? ',[trim(strtolower('req_type')).'%']);
        })
        ->leftjoin('lookup_refs as llr','im.ireq_status','llr.lookup_code')
        ->where('im.ireq_requestor',$usr_name)
        ->where(function($query){
            return $query
            ->where('im.ireq_status','A1')
            ->orwhere('im.ireq_status','A2');
        })
        ->whereRaw('LOWER(llr.lookup_type) LIKE ? ',[trim(strtolower('ict_status')).'%'])
        ->orderBy('im.creation_date','ASC')
        ->get();
        return view('pdf/Laporan_IctRequest_Verifikasi', compact('ict'));
    }
    public function cetak_excel_verifikasi($usr_name)
    {
        $newCreation = Carbon::parse($this->date)->copy()->tz('Asia/Jakarta')->format('d M Y');
        return Excel::download(new IctExportVerifikasi($usr_name),'Laporan Ict Request (Terverifikasi) '.$newCreation.'.xlsx');
    }
    Public function cetak_pdf_reject($usr_name)
    {
        $ict =  DB::table('ireq_mst as im')
        ->select('im.ireq_no','im.ireq_requestor','im.ireq_user','im.ireq_reason','vr.name as ireq_bu','lr.lookup_desc as ireq_type',
                DB::raw("TO_CHAR(im.ireq_date,' dd Mon YYYY') as ireq_date"),'llr.lookup_desc as ireq_status','dr.div_name')
        ->leftjoin('vcompany_refs as vr','im.ireq_bu','vr.company_code')
        ->leftJoin('lookup_refs as lr',function ($join) {
            $join->on('im.ireq_type','lr.lookup_code')
                  ->whereRaw('LOWER(lr.lookup_type) LIKE ? ',[trim(strtolower('req_type')).'%']);
        })
        ->leftjoin('lookup_refs as llr','im.ireq_status','llr.lookup_code')
        ->leftjoin('divisi_refs as dr','im.ireq_divisi_user','dr.div_id')
        ->where('im.ireq_requestor',$usr_name)
        ->where(function ($query){
            return $query
            ->where('im.ireq_status','RR')
            ->Orwhere('im.ireq_status','RA1')
            ->Orwhere('im.ireq_status','RA2');
        })
        ->whereRaw('LOWER(llr.lookup_type) LIKE ? ',[trim(strtolower('ict_status')).'%'])
        ->orderBy('im.creation_date','ASC')
        ->get();
        return view('pdf/Laporan_IctRequest_Reject', compact('ict'));
    }
    public function cetak_excel_reject($usr_name)
    {
        $newCreation = Carbon::parse($this->date)->copy()->tz('Asia/Jakarta')->format('d M Y');
        return Excel::download(new IctExportReject($usr_name),'Laporan Ict Request (Direject) '.$newCreation.'.xlsx');
    }
    Public function cetak_pdf_sedang_dikerjakan($usr_name)
    {
        $ict =  DB::table('ireq_mst as im')
        ->select('im.ireq_no','im.ireq_user','dr.div_name','im.ireq_requestor','vr.name as ireq_bu','lr.lookup_desc as ireq_type','im.ireq_assigned_to',
                DB::raw("TO_CHAR(im.ireq_date,' dd Mon YYYY') as ireq_date"),'llr.lookup_desc as ireq_status')
        ->leftjoin('vcompany_refs as vr','im.ireq_bu','vr.company_code')
        ->leftJoin('lookup_refs as lr',function ($join) {
            $join->on('im.ireq_type','lr.lookup_code')
                  ->whereRaw('LOWER(lr.lookup_type) LIKE ? ',[trim(strtolower('req_type')).'%']);
        })
        ->leftjoin('lookup_refs as llr','im.ireq_status','llr.lookup_code')
        ->leftjoin('divisi_refs as dr','im.ireq_divisi_user','dr.div_id')
        ->where('im.ireq_status','T')
        ->where('im.ireq_requestor',$usr_name)
        ->whereRaw('LOWER(llr.lookup_type) LIKE ? ',[trim(strtolower('ict_status')).'%'])
        ->orderBy('im.creation_date','ASC')
        ->get();
        return view('pdf/Laporan_IctRequest_Sedang_Dikerjakan', compact('ict'));
    }
    public function cetak_excel_sedang_dikerjakan($usr_name)
    {
        $newCreation = Carbon::parse($this->date)->copy()->tz('Asia/Jakarta')->format('d M Y');
        return Excel::download(new IctExportSedangDikerjakan($usr_name),'Laporan Ict Request (Sedang Dikerjakan) '.$newCreation.'.xlsx');
    }
    Public function cetak_pdf_sudah_dikerjakan($usr_name)
    {
        $ict =  DB::table('ireq_dtl as id')
        ->select('imm.ireq_no','id.ireq_desc','id.ireq_qty','id.ireq_remark','id.ireqd_id','dr.div_name',
        'imm.ireq_user', 'id.ireq_assigned_to', 'llr.lookup_desc as ireq_status', 'imm.ireq_requestor',
        'vr.name as ireq_bu','lr.lookup_desc as ireq_type',DB::raw("TO_CHAR(imm.ireq_date,' dd Mon YYYY') as ireq_date"),
        DB::raw("(im.invent_code ||'-'|| im.invent_desc) as name"))
        ->leftjoin('invent_mst as im','id.invent_code','im.invent_code')
        ->leftjoin('lookup_refs as lr','id.ireq_type','lr.lookup_code')
        ->join('ireq_mst as imm','id.ireq_id','imm.ireq_id')        
        ->leftjoin('lookup_refs as llr','imm.ireq_status','llr.lookup_code')
        ->leftjoin('vcompany_refs as vr','imm.ireq_bu','vr.company_code')
        ->leftjoin('divisi_refs as dr','imm.ireq_divisi_user','dr.div_id')
        ->where('id.ireq_status','D')
        ->where('imm.ireq_requestor',$usr_name)
        ->whereRaw('LOWER(lr.lookup_type) LIKE ? ',[trim(strtolower('req_type')).'%'])
        ->whereRaw('LOWER(llr.lookup_type) LIKE ? ',[trim(strtolower('ict_status')).'%'])
        ->orderBy('id.creation_date','ASC')
        ->get();
        return view('pdf/Laporan_IctRequest_Sudah_Dikerjakan', compact('ict'));
    }
    public function cetak_excel_sudah_dikerjakan($usr_name)
    {
        $newCreation = Carbon::parse($this->date)->copy()->tz('Asia/Jakarta')->format('d M Y');
        return Excel::download(new IctExportSudahDikerjakan($usr_name),'Laporan Ict Request (Sudah Dikerjakan) '.$newCreation.'.xlsx');
    }
    Public function cetak_pdf_selesai($usr_name)
    {
        $ict =  DB::table('ireq_dtl as id')
        ->select('imm.ireq_no','id.ireq_desc','id.ireq_qty','id.ireq_remark','id.ireqd_id','dr.div_name',
        'imm.ireq_user', 'id.ireq_assigned_to', 'llr.lookup_desc as ireq_status', 'imm.ireq_requestor',
        'vr.name as ireq_bu','lr.lookup_desc as ireq_type',DB::raw("TO_CHAR(imm.ireq_date,' dd Mon YYYY') as ireq_date"),
        DB::raw("(im.invent_code ||'-'|| im.invent_desc) as name"))
        ->leftjoin('invent_mst as im','id.invent_code','im.invent_code')
        ->leftjoin('lookup_refs as lr','id.ireq_type','lr.lookup_code')
        ->join('ireq_mst as imm','id.ireq_id','imm.ireq_id')        
        ->leftjoin('lookup_refs as llr','imm.ireq_status','llr.lookup_code')
        ->leftjoin('vcompany_refs as vr','imm.ireq_bu','vr.company_code')
        ->leftjoin('divisi_refs as dr','imm.ireq_divisi_user','dr.div_id')
        ->where('id.ireq_status','C')
        ->where('imm.ireq_requestor',$usr_name)
        ->whereRaw('LOWER(lr.lookup_type) LIKE ? ',[trim(strtolower('req_type')).'%'])
        ->whereRaw('LOWER(llr.lookup_type) LIKE ? ',[trim(strtolower('ict_status')).'%'])
        ->orderBy('id.creation_date','ASC')
        ->get();
        return view('pdf/Laporan_IctRequest_Selesai', compact('ict'));
    }
    public function cetak_excel_selesai($usr_name)
    {
        $newCreation = Carbon::parse($this->date)->copy()->tz('Asia/Jakarta')->format('d M Y');
        return Excel::download(new IctExportSelesai($usr_name),'Laporan Ict Request '.$newCreation.'.xlsx');
    }
    public function updateAssign(Request $request)
    {
        $ict = Ict::where('ireq_id',$request->id)->first();
        $ict->ireq_status = 'T';
        $ict->ireq_assigned_to = $request->name;
        $ict->last_updated_by = Auth::user()->usr_name;
        $ict->last_update_date = $this->newUpdate;
        $ict->program_name = "IctController_updateAssign";
        $ict->save();

        $dtl = IctDetail::where('ireq_id',$request->id)->get();
        foreach ($dtl as $d){
            $d->ireq_status = 'T';
            $d->ireq_assigned_to = $request->name;
            $d->last_update_date = $this->newUpdate;
            $d->last_updated_by = Auth::user()->usr_name;
            $d->program_name = "IctController_updateAssign";
            $d->save();
        }
        return response()->json('Success Update');
    }
    public function updateStatusPermohonan($code)
    {
        
        $ict = Ict::where('ireq_id',$code)->first();
        $ict->ireq_status = 'A1';
        $ict->ireq_approver1 = Auth::user()->usr_name;
        $ict->last_updated_by = Auth::user()->usr_name;
        $ict->last_update_date = $this->newUpdate;
        $ict->program_name = "IctController_updateStatusPermohonan";
        $ict->save();

        $dtl = IctDetail::where('ireq_id',$code)->get();
        foreach ($dtl as $d){
            $d->ireq_status = 'A1';
            $d->last_update_date = $this->newUpdate;
            $d->last_updated_by = Auth::user()->usr_name;
            $d->program_name = "IctController_updateStatusPermohonan";
            $d->save();
        }
        return response()->json('Success Update');
    }
    public function updateStatusReject(Request $request, $code)
    {
        
        $ict = Ict::where('ireq_id',$code)->first();
        $ict->ireq_status = 'RA1';
        $ict->ireq_reason = $request->ket;
        $ict->last_update_date = $this->newUpdate;
        $ict->last_updated_by = Auth::user()->usr_name;
        $ict->program_name = "IctController_updateStatusReject";
        $ict->save();

        $dtl = IctDetail::where('ireq_id',$code)->get();
        foreach ($dtl as $d){
            $d->ireq_status = 'RA1';
            $d->ireq_reason = $request->ket;
            $d->last_update_date = $this->newUpdate;
            $d->last_updated_by = Auth::user()->usr_name;
            $d->program_name = "IctController_updateStatusReject";
            $d->save();
        }
        return response()->json('Success Update');
    }
    public function updateStatusSubmit($ireq_id)
    {
        
        $ICT = Ict::where('ireq_id',$ireq_id)->first();
        $divisiPengguna = $ICT->ireq_divisi_user;
            $ICT->ireq_status = 'P';
            $ICT->last_update_date = $this->newUpdate;
            $ICT->last_updated_by = Auth::user()->usr_name;
            $ICT->program_name = "IctController_updateStatusSubmit";
            $ICT->save();

        $dtl = IctDetail::where('ireq_id',$ireq_id)->get();
        foreach ($dtl as $d){
            $d->ireq_status = 'P';
            $d->last_update_date = $this->newUpdate;
            $d->last_updated_by = Auth::user()->usr_name;
            $d->program_name = "IctController_updateStatusSubmit";
            $d->save();
        }
        return response()->json('Success Update Status');
    }
    public function cekVerif($code)
    {
        $link = Link::where('link_id',$code)->first();
        return response()->json($link);
    }
    public function updateStatusPenugasan($ireq_id)
    {
        
        $ict = Ict::where('ireq_id',$ireq_id)->first();
        $ict->ireq_status = 'T';
        $ict->ireq_verificator = Auth::user()->usr_name;
        $ict->last_update_date = $this->newUpdate;    
        $ict->last_updated_by = Auth::user()->usr_name;
        $ict->program_name = "IctController_updateStatusPenugasan";
        $ict->save();
        
        $dtl = IctDetail::where('ireq_id',$ireq_id)->get();
        foreach ($dtl as $d){
            $d->ireq_status = 'T';
            $d->last_update_date = $this->newUpdate;
            $d->last_updated_by = Auth::user()->usr_name;
            $d->program_name = "IctController_updateStatusPenugasan";
            $d->save();
        }
        return response()->json('Success Update');
    }
    public function updateStatusClosing($ireq_id)
    {
        
        $ict = Ict::where('ireq_id',$ireq_id)->first();
        $ict->ireq_approver2 = Auth::user()->usr_name;
        $ict->ireq_status = 'C';
        $ict->last_update_date = $this->newUpdate;
        $ict->last_updated_by = Auth::user()->usr_name;
        $ict->program_name = "IctController_updateStatusClosing";
        $ict->save();

        $dtl = IctDetail::where('ireq_id',$ireq_id)->get();
        foreach ($dtl as $d){
            $d->ireq_status = 'C';
            $d->last_update_date = $this->newUpdate;
            $d->last_updated_by = Auth::user()->usr_name;
            $d->program_name = "IctController_updateStatusClosing";
            $d->save();
        }
        return response()->json('Success Update');
    }
}
