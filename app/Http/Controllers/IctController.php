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

    function getDataManager()
    {
        $ict = DB::table('ireq_mst as im')
        ->select('im.ireq_id','im.ireq_no','im.ireq_date','im.ireq_user','im.ireq_requestor','dr.div_name')
        ->leftjoin('divisi_refs as dr','im.ireq_divisi_user','dr.div_id')
        ->where('im.ireq_status','NA2')
        ->groupBy('im.ireq_id','im.ireq_no','im.ireq_date','im.ireq_user','im.ireq_requestor','dr.div_name','im.creation_date','im.ireq_status')
        ->orderBy('im.creation_date','ASC')
        ->get(); 

        $ict1 = DB::table('ireq_mst as im')
        ->select('im.ireq_id','im.ireq_no','im.ireq_date','im.ireq_user','im.ireq_requestor','dr.div_name')
        ->leftjoin('divisi_refs as dr','im.ireq_divisi_user','dr.div_id')
        ->where('im.ireq_status','A2')
        ->groupBy('im.ireq_id','im.ireq_no','im.ireq_date','im.ireq_user','im.ireq_requestor','dr.div_name','im.creation_date','im.ireq_status')
        ->orderBy('im.creation_date','ASC')
        ->get(); 

        $ict2 = DB::table('ireq_mst as im')
        ->select('im.ireq_id','im.ireq_no','im.ireq_date','im.ireq_user','im.ireq_requestor','dr.div_name','im.ireq_reason',
          DB::raw("CASE WHEN im.ireq_status = 'RA2' Then 'Reject By ICT Manager' end as ireq_status "))
        ->leftjoin('divisi_refs as dr','im.ireq_divisi_user','dr.div_id')
        ->where('im.ireq_status','RA2')
        ->groupBy('im.ireq_id','im.ireq_no','im.ireq_date','im.ireq_user','im.ireq_requestor','dr.div_name','im.creation_date','im.ireq_status','im.ireq_reason')
        ->orderBy('im.creation_date','ASC')
        ->get(); 

        $ict3 = DB::table('ireq_mst as im')
        ->select('im.ireq_id','im.ireq_no','im.ireq_date','im.ireq_user','im.ireq_requestor','dr.div_name','im.ireq_assigned_to',
                DB::raw("CASE WHEN im.ireq_status = 'T' Then 'Penugasan' end as ireq_status"))
        ->leftjoin('divisi_refs as dr','im.ireq_divisi_user','dr.div_id')
        ->where('im.ireq_status','T')
        ->groupBy('im.ireq_id','im.ireq_no','im.ireq_date','im.ireq_user','im.ireq_requestor','dr.div_name','im.creation_date','im.ireq_status','im.ireq_assigned_to')
        ->orderBy('im.creation_date','ASC')
        ->get();

        $ict4 = DB::Table('v_ireq_mst_sudah_dikerjakan')->get();
        $ict5 = DB::Table('v_ireq_mst_selesai')->get();

        return json_encode(['ict'=>$ict,'ict1'=>$ict1,'ict2'=>$ict2,'ict3'=>$ict3,'ict4'=>$ict4,'ict5'=>$ict5],200);
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
            return json_encode($dtl);
    }
    function approveByManager($code)
    {
        
        $ict = Ict::where('ireq_id',$code)->first();
        $ict->ireq_status = 'A2';
        $ict->ireq_approver1 = Auth::user()->usr_name;
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
        return json_encode('Success Update');
    }
    function rejectByManager(Request $request,$code)
    {
        
        $ict = Ict::where('ireq_id',$code)->first();
        $ict->ireq_status = 'RA2';
        $ict->ireq_reason = $request->ket;
        $ict->ireq_approver1 = Auth::user()->usr_name;
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
        return json_encode('Success Update');
    }
    function getDataReviewer()
    {
        $ict = DB::table('ireq_mst as im')
        ->select('im.ireq_id','im.ireq_no','im.ireq_date','im.ireq_user','im.ireq_requestor','dr.div_name', 
                DB::raw('count(idd.ireq_assigned_to) as ireq_count_status'), DB::raw('count(idd.ireq_id) as ireq_count_id'),
                DB::raw("CASE WHEN im.ireq_status = 'P' Then 'Permohonan' end as ireq_status "))
        ->leftjoin('divisi_refs as dr','im.ireq_divisi_user','dr.div_id')
        ->leftjoin('ireq_dtl as idd','im.ireq_id','idd.ireq_id')
        ->where('im.ireq_status','P')
        ->groupBy('im.ireq_id','im.ireq_no','im.ireq_date','im.ireq_user','im.ireq_requestor','dr.div_name','im.creation_date','im.ireq_status')
        ->orderBy('im.creation_date','ASC')
        ->get(); 

        $ict1 = DB::table('ireq_mst as im')
        ->select('im.ireq_id','im.ireq_no','im.ireq_date','im.ireq_user','im.ireq_requestor','dr.div_name',
                DB::raw("CASE WHEN im.ireq_status = 'NA1' Then 'Belum Diapprove Atasan' WHEN im.ireq_status = 'A1' Then 'Sudah Diapprove Atasan' end as ireq_status "),
                DB::raw('count(idd.ireq_assigned_to) as ireq_count_status'), DB::raw('count(idd.ireq_id) as ireq_count_id'))
        ->leftjoin('divisi_refs as dr','im.ireq_divisi_user','dr.div_id')
        ->leftjoin('ireq_dtl as idd','im.ireq_id','idd.ireq_id')
        ->where('im.ireq_status','NA1')
        ->orwhere('im.ireq_status','A1')
        ->groupBy('im.ireq_id','im.ireq_no','im.ireq_date','im.ireq_user','im.ireq_requestor','dr.div_name','im.creation_date','im.ireq_status')
        ->orderBy('im.creation_date','ASC')
        ->get(); 

        $ict2 = DB::table('ireq_mst as im')
        ->select('im.ireq_id','im.ireq_no','im.ireq_date','im.ireq_user','im.ireq_requestor','dr.div_name',
                DB::raw("CASE WHEN im.ireq_status = 'NA2' Then 'Belum Diapprove ICT Manager' WHEN im.ireq_status = 'A2' Then 'Sudah Diapprove ICT Manager' end as ireq_status "),
                DB::raw('count(idd.ireq_assigned_to) as ireq_count_status'), DB::raw('count(idd.ireq_id) as ireq_count_id'))
        ->leftjoin('divisi_refs as dr','im.ireq_divisi_user','dr.div_id')
        ->leftjoin('ireq_dtl as idd','im.ireq_id','idd.ireq_id')
        ->where('im.ireq_status','NA2')
        ->orwhere('im.ireq_status','A2')
        ->groupBy('im.ireq_id','im.ireq_no','im.ireq_date','im.ireq_user','im.ireq_requestor','dr.div_name','im.creation_date','im.ireq_status')
        ->orderBy('im.creation_date','ASC')
        ->get(); 

        $ict3 = DB::table('ireq_mst as im')
        ->select('im.ireq_id','im.ireq_no','im.ireq_date','im.ireq_user','im.ireq_requestor','dr.div_name','im.ireq_reason',
                DB::raw("CASE WHEN im.ireq_status = 'RR' Then 'Reject By Reviewer' WHEN im.ireq_status = 'RA1' Then 'Reject By Atasan' WHEN im.ireq_status = 'RA2' Then 'Reject By ICT Manager' end as ireq_status "),
                DB::raw('count(idd.ireq_assigned_to) as ireq_count_status'), DB::raw('count(idd.ireq_id) as ireq_count_id'))
        ->leftjoin('divisi_refs as dr','im.ireq_divisi_user','dr.div_id')
        ->leftjoin('ireq_dtl as idd','im.ireq_id','idd.ireq_id')
        ->where(function ($query){
            return $query
            ->where('im.ireq_status','RR')
            ->OrWhere('im.ireq_status','RA1')
            ->OrWhere('im.ireq_status','RA2');
        })
        ->groupBy('im.ireq_id','im.ireq_no','im.ireq_date','im.ireq_user','im.ireq_requestor','dr.div_name','im.creation_date','im.ireq_status','im.ireq_reason')
        ->orderBy('im.creation_date','ASC')
        ->get(); 

        $ict4 = DB::table('ireq_mst as im')
        ->select('im.ireq_id','im.ireq_no','im.ireq_date','im.ireq_user','im.ireq_requestor','dr.div_name','im.ireq_assigned_to',
                DB::raw("CASE WHEN im.ireq_status = 'T' Then 'Penugasan' end as ireq_status"))
        ->leftjoin('divisi_refs as dr','im.ireq_divisi_user','dr.div_id')
        ->where('im.ireq_status','T')
        ->groupBy('im.ireq_id','im.ireq_no','im.ireq_date','im.ireq_user','im.ireq_requestor','dr.div_name','im.creation_date','im.ireq_status','im.ireq_assigned_to')
        ->orderBy('im.creation_date','ASC')
        ->get();

        $ict5 = DB::Table('v_ireq_mst_sudah_dikerjakan')->get();

        $ict6 = DB::Table('v_ireq_mst_selesai')->get();

        return json_encode(['ict'=>$ict,'ict1'=>$ict1,'ict2'=>$ict2,'ict3'=>$ict3,'ict4'=>$ict4,'ict5'=>$ict5,'ict6'=>$ict6],200);
    }
    function rejectReviewer(Request $request, $code)
    {
        
        $ict = Ict::where('ireq_id',$code)->first();
        $ict->ireq_status = 'RR';
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
        return json_encode('Success Update Status');
    }
    function needApprovalAtasan($ireq_id)
    {
        
        $ICT = Ict::where('ireq_id',$ireq_id)->first();
        $divisiPengguna = $ICT->ireq_divisi_user;
        $ICT->ireq_status = 'NA1';
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
        ->join('ireq_dtl as id','im.ireq_id','id.ireq_id')
        ->join('divisi_refs as dr','im.ireq_divisi_user','dr.div_id')
        ->rightjoin('mng_users as mu','dr.div_verificator','mu.usr_name')
        ->join('invent_mst as imm','id.invent_code','imm.invent_code')
        ->select('im.ireq_no','im.ireq_id', 'id.invent_code','mu.usr_name',DB::raw("TO_CHAR(im.ireq_date, 'dd Mon YYYY') as ireq_date"),
                 'im.ireq_user','imm.invent_desc','id.ireq_qty')
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
        return json_encode('Success Update Status');
    }
    function needApprovalManager($ireq_id)
    {
        
        $ict = Ict::where('ireq_id',$ireq_id)->first();
        $ict->ireq_status = 'NA2';
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
        return json_encode('Success Update Status');
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
        return json_encode('Success Update');
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
        return json_encode('Success Update');
    }
    function getIctAdmin()
    {
        $ict = DB::table('ireq_mst as im')
        ->leftjoin('ireq_dtl as idm','im.ireq_id','idm.ireq_id')
        ->leftjoin('divisi_refs as dr','im.ireq_divisi_user','dr.div_id')
        ->select('im.ireq_id','im.ireq_no','im.ireq_date','im.ireq_status','im.ireq_user','dr.div_name')
        ->where(function($query){
            return $query
            ->Where('im.ireq_status','P');
            })
        ->groupBy('im.ireq_id','im.ireq_no','im.ireq_date','im.ireq_status','im.ireq_user','im.creation_date','dr.div_name')
        ->orderBy('im.creation_date','ASC')
        ->get();

        $ict1 = DB::table('ireq_mst')
        ->select('ireq_id','ireq_no','ireq_date','ireq_user')
        ->where('ireq_status','A')
        ->orderBy('creation_date','ASC')
        ->get();

        $ict2 = DB::table('ireq_mst')
        ->select('ireq_id','ireq_no','ireq_date','ireq_user','ireq_reason')
        ->where('ireq_status','R')
        ->orderBy('creation_date','ASC')
        ->get();

        $ict3 = DB::table('ireq_mst')
        ->select('ireq_id','ireq_no','ireq_date','ireq_user')
        ->where('ireq_status','T')
        ->orderBy('creation_date','ASC')
        ->get();

        $ict4 = DB::table('ireq_mst')
        ->select('ireq_id','ireq_no','ireq_date','ireq_user')
        ->where('ireq_status','D')
        ->orderBy('creation_date','ASC')
        ->get();

        $ict5 = DB::table('ireq_mst')
        ->select('ireq_id','ireq_no','ireq_date','ireq_user')
        ->where('ireq_status','C')
        ->orderBy('creation_date','ASC')
        ->get();
        
        $ict6 = DB::table('ireq_mst')
        ->select('ireq_id','ireq_no','ireq_user','ireq_date',
            DB::raw("CASE WHEN ireq_status = 'P' Then 'Permohonan' WHEN ireq_status = 'NA1' Then 
            'Menunggu Diapprove Atasan' When ireq_status = 'NA2' Then 'Menunggu Diapprove Manager' 
            WHEN ireq_status = 'RR' Then 'Reject By Reviewer' WHEN ireq_status = 'RA1' Then 
            'Reject By Atasan' WHEN ireq_status = 'RA2' Then 'Reject By Manager' WHEN 
            ireq_status = 'A1' Then 'Sudah Diapprove Atasan' WHEN ireq_status = 'A2' 
            Then 'Sudah Diapprove Manager' WHEN ireq_status = 'T' Then 'Penugasan' WHEN 
            ireq_status = 'D' Then 'Done' WHEN ireq_status = 'C' Then 'Close' end as ireq_status "))
        ->whereNotNull('ireq_status')
        ->orderBy('ireq_status','ASC')
        ->get();

        return json_encode(['ict'=>$ict,'ict1'=>$ict1,'ict2'=>$ict2,'ict3'=>$ict3,'ict4'=>$ict4,'ict5'=>$ict5,'ict6'=>$ict6],200);

    }
    Public function getIct($usr_name)
    {
        $ict = DB::table('ireq_mst as im')
        ->leftjoin('ireq_dtl as idm','im.ireq_id','idm.ireq_id')
        ->leftjoin('divisi_refs as dr','im.ireq_divisi_user','dr.div_id')
        ->select('im.ireq_id','im.ireq_no','im.ireq_date','im.ireq_status','im.ireq_user','dr.div_name',
                  DB::raw('count(DISTINCT(idm.ireq_id)) as count'), DB::raw("CASE WHEN im.ireq_status = 'P' Then 'Permohonan' WHEN im.ireq_status = 'NA1' Then 'Belum Diapprove Atasan' WHEN im.ireq_status ='NA2' Then 'Belum Diapprove ICT Manager' END as ireq_status "))
        ->where('im.created_by',$usr_name)
        ->where(function($query){
            return $query
            ->whereNull('im.ireq_status')
            ->orWhere('im.ireq_status','P');
            })
        ->groupBy('im.ireq_id','im.ireq_no','im.ireq_date','im.ireq_status','im.ireq_user','im.creation_date','dr.div_name')
        ->orderBy('im.creation_date','ASC')
        ->get();

        $ict1 = DB::table('ireq_mst')
        ->select('ireq_id','ireq_no','ireq_date','ireq_user',
          DB::raw("CASE WHEN ireq_status = 'A1' Then 'Sudah Diapprove Atasan' 
          WHEN ireq_status = 'A2' Then 'Sudah Diapprove ICT Manager' End as ireq_status"))
        ->where('created_by',$usr_name)
        ->where(function($query){
            return $query
            ->where('ireq_status','A1')
            ->orWhere('ireq_status','A2');
        })
        ->orderBy('creation_date','ASC')
        ->get();

        $ict2 = DB::table('ireq_mst')
        ->select('ireq_id','ireq_no','ireq_date','ireq_user','ireq_reason',
          DB::raw("CASE WHEN ireq_status ='RR' Then 'Reject By Reviewer' WHEN 
          ireq_status = 'RA1' Then 'Reject By Atasan' WHEN ireq_status = 'RA2' Then 'Reject By ICT Manager'
           END as ireq_status"))
        ->where('created_by',$usr_name)
        ->where(function($query){
            return $query
            ->where('ireq_status','RR')
            ->orwhere('ireq_status','RA1')
            ->orwhere('ireq_status','RA2');
        })
        ->orderBy('creation_date','ASC')
        ->get();

        $ict3 =  DB::table('ireq_mst as im')
        ->select('im.ireq_id','im.ireq_no','im.ireq_date','im.ireq_user','im.ireq_requestor','dr.div_name','im.ireq_assigned_to',
                DB::raw("CASE WHEN im.ireq_status = 'T' Then 'Penugasan' end as ireq_status"))
        ->leftjoin('divisi_refs as dr','im.ireq_divisi_user','dr.div_id')
        ->where('im.ireq_status','T')
        ->where('im.created_by',$usr_name)
        ->groupBy('im.ireq_id','im.ireq_no','im.ireq_date','im.ireq_user','im.ireq_requestor','dr.div_name','im.creation_date','im.ireq_status','im.ireq_assigned_to')
        ->orderBy('im.creation_date','ASC')
        ->get();

        // $ict4 = DB::table('ireq_mst')
        // ->select('ireq_id','ireq_no','ireq_date','ireq_user')
        // ->where('created_by',$usr_name)
        // ->where('ireq_status','D')
        // ->orderBy('creation_date','ASC')
        // ->get();

        // $ict5 = DB::table('ireq_mst')
        // ->select('ireq_id','ireq_no','ireq_date','ireq_user')
        // ->where('created_by',$usr_name)
        // ->where('ireq_status','C')
        // ->orderBy('creation_date','ASC')
        // ->get();

        $ict4 = DB::table('ireq_mst as im')
        ->leftjoin('ireq_dtl as idd','im.ireq_id','idd.ireq_id')
        ->leftjoin('divisi_refs as dr','im.ireq_divisi_user','dr.div_id')
        ->leftjoin('invent_mst as imm','idd.invent_code','imm.invent_code')
        ->select('dr.div_name','im.ireq_id','im.ireq_no','im.ireq_date','im.ireq_requestor',
                 'im.ireq_user','idd.ireq_assigned_to','idd.ireqd_id',
                 DB::raw("(imm.invent_code ||'-'|| imm.invent_desc) as invent_code"),DB::raw("CASE WHEN idd.ireq_status = 'D' Then 'Done' end as ireq_status "))
        ->where('idd.ireq_status','D')
        ->where('im.created_by',$usr_name)
        ->orderBy('idd.ireqd_id','ASC')
        ->get();

        $ict5 = DB::table('ireq_mst as im')
        ->leftjoin('ireq_dtl as idd','im.ireq_id','idd.ireq_id')
        ->leftjoin('divisi_refs as dr','im.ireq_divisi_user','dr.div_id')
        ->leftjoin('invent_mst as imm','idd.invent_code','imm.invent_code')
        ->select('dr.div_name','im.ireq_id','im.ireq_no','im.ireq_date','im.ireq_requestor',
                 'im.ireq_user','idd.ireq_assigned_to','idd.ireqd_id',
                 DB::raw("(imm.invent_code ||'-'|| imm.invent_desc) as invent_code"),DB::raw("CASE WHEN idd.ireq_status = 'C' Then 'Closing' end as ireq_status "))
        ->where('idd.ireq_status','C')
        ->where('im.created_by',$usr_name)
        ->orderBy('idd.ireqd_id','ASC')
        ->get();
        
        $ict6 = DB::table('ireq_mst')
        ->select('ireq_id','ireq_no','ireq_date','ireq_user',
          DB::raw("CASE WHEN ireq_status ='NA1' Then 'Menunggu Diapprove Atasan' WHEN 
          ireq_status = 'NA2' Then 'Menunggu Diapprove ICT Manager' END as ireq_status"))
        ->where('created_by',$usr_name)
        ->where('ireq_status','NA1')
        ->orwhere('ireq_status','NA2')
        ->orderBy('creation_date','ASC')
        ->get();
        
        $ict7 = DB::table('ireq_mst as im')
        ->leftjoin('ireq_dtl as idm','im.ireq_id','idm.ireq_id')
        ->leftjoin('divisi_refs as dr','im.ireq_divisi_user','dr.div_id')
        ->select('im.ireq_id','im.ireq_no','im.ireq_date','im.ireq_status','im.ireq_user','dr.div_name',
                  DB::raw('count(DISTINCT(idm.ireq_id)) as count'), DB::raw("CASE WHEN im.ireq_status = 'P' Then 'Permohonan' WHEN im.ireq_status = 'NA1' Then 'Belum Diapprove Atasan' WHEN im.ireq_status ='NA2' Then 'Belum Diapprove ICT Manager' END as ireq_status "))
        ->where('im.created_by',$usr_name)
        ->where(function($query){
            return $query
            ->where('im.ireq_status','NA1')
            ->orWhere('im.ireq_status','NA2')
            ->orWhere('im.ireq_status','P');
            })
        ->groupBy('im.ireq_id','im.ireq_no','im.ireq_date','im.ireq_status','im.ireq_user','im.creation_date','dr.div_name')
        ->orderBy('im.creation_date','ASC')
        ->get();

        return json_encode(['ict'=>$ict,'ict1'=>$ict1,'ict2'=>$ict2,'ict3'=>$ict3,'ict4'=>$ict4,'ict5'=>$ict5,'ict6'=>$ict6,'ict7'=>$ict7],200);
    }
    Public function getPermohonan($usr_name)
    {
        $ict = DB::table('ireq_mst as im')
        ->select('im.ireq_id','im.ireq_no','im.ireq_date','im.ireq_status','im.ireq_user','im.ireq_requestor')
        ->leftjoin('divisi_refs as dr','im.ireq_divisi_user','dr.div_id')
        ->where('dr.div_verificator',$usr_name)
        ->where('im.ireq_status','NA1')
        ->groupBy('im.ireq_id','im.ireq_no','im.ireq_date','im.ireq_status','im.ireq_user','im.creation_date','im.ireq_requestor')
        ->orderBy('im.creation_date','ASC')
        ->get();
        
        $ict1 = DB::table('ireq_mst as im')
        ->select('im.ireq_id','im.ireq_no','im.ireq_date','im.ireq_status','im.ireq_user','im.ireq_requestor')
        ->leftjoin('divisi_refs as dr','im.ireq_divisi_user','dr.div_id')
        ->where('dr.div_verificator',$usr_name)
        ->where(function($query){
            return $query
            ->Where('im.ireq_status','A1');
            })
        ->groupBy('im.ireq_id','im.ireq_no','im.ireq_date','im.ireq_status','im.ireq_user','im.creation_date','im.ireq_requestor')
        ->orderBy('im.creation_date','ASC')
        ->get();

        $ict2 = DB::table('ireq_mst as im')
        ->select('im.ireq_id','im.ireq_no','im.ireq_date','im.ireq_status','im.ireq_user','im.ireq_requestor','im.ireq_reason')
        ->leftjoin('divisi_refs as dr','im.ireq_divisi_user','dr.div_id')
        ->where('dr.div_verificator',$usr_name)
        ->where(function($query){
            return $query
            ->where('im.ireq_status','RA1');
            })
        ->groupBy('im.ireq_id','im.ireq_no','im.ireq_date','im.ireq_status','im.ireq_user','im.creation_date','im.ireq_requestor','im.ireq_reason')
        ->orderBy('im.creation_date','ASC')
        ->get();

        $ict3 = DB::table('ireq_mst as im')
        ->select('im.ireq_id','im.ireq_no','im.ireq_date','im.ireq_status','im.ireq_user','im.ireq_requestor','im.ireq_assigned_to')
        ->leftjoin('divisi_refs as dr','im.ireq_divisi_user','dr.div_id')
        ->where('dr.div_verificator',$usr_name)
        ->where('im.ireq_status','T')
        ->groupBy('im.ireq_id','im.ireq_no','im.ireq_date','im.ireq_status','im.ireq_user','im.creation_date','im.ireq_requestor','im.ireq_assigned_to')
        ->orderBy('im.creation_date','ASC')
        ->get();
        
        // $ict4 = DB::table('ireq_mst as im')
        // ->select('im.ireq_id','im.ireq_no','im.ireq_date','im.ireq_status','im.ireq_user','im.ireq_requestor')
        // ->leftjoin('divisi_refs as dr','im.ireq_divisi_user','dr.div_id')
        // ->where('dr.div_verificator',$usr_name)
        // ->where('im.ireq_status','D')
        // ->groupBy('im.ireq_id','im.ireq_no','im.ireq_date','im.ireq_status','im.ireq_user','im.creation_date','im.ireq_requestor')
        // ->orderBy('im.creation_date','ASC')
        // ->get();

        // $ict5 = DB::table('ireq_mst as im')
        // ->select('im.ireq_id','im.ireq_no','im.ireq_date','im.ireq_status','im.ireq_user','im.ireq_requestor')
        // ->leftjoin('divisi_refs as dr','im.ireq_divisi_user','dr.div_id')
        // ->where('dr.div_verificator',$usr_name)
        // ->where('im.ireq_status','C')
        // ->groupBy('im.ireq_id','im.ireq_no','im.ireq_date','im.ireq_status','im.ireq_user','im.creation_date','im.ireq_requestor')
        // ->orderBy('im.creation_date','ASC')
        // ->get();

        $ict4 = DB::table('ireq_mst as im')
        ->leftjoin('ireq_dtl as idd','im.ireq_id','idd.ireq_id')
        ->leftjoin('divisi_refs as dr','im.ireq_divisi_user','dr.div_id')
        ->leftjoin('invent_mst as imm','idd.invent_code','imm.invent_code')
        ->select('dr.div_name','im.ireq_id','im.ireq_no','im.ireq_date','im.ireq_requestor',
                 'im.ireq_user','idd.ireq_assigned_to','idd.ireqd_id',
                 DB::raw("(imm.invent_code ||'-'|| imm.invent_desc) as invent_code"),DB::raw("CASE WHEN idd.ireq_status = 'D' Then 'Done' end as ireq_status "))
        ->where('idd.ireq_status','D')
        ->where('dr.div_verificator',$usr_name)
        ->orderBy('idd.ireqd_id','ASC')
        ->get();

        $ict5 = DB::table('ireq_mst as im')
        ->leftjoin('ireq_dtl as idd','im.ireq_id','idd.ireq_id')
        ->leftjoin('divisi_refs as dr','im.ireq_divisi_user','dr.div_id')
        ->leftjoin('invent_mst as imm','idd.invent_code','imm.invent_code')
        ->select('dr.div_name','im.ireq_id','im.ireq_no','im.ireq_date','im.ireq_requestor',
                 'im.ireq_user','idd.ireq_assigned_to','idd.ireqd_id',
                 DB::raw("(imm.invent_code ||'-'|| imm.invent_desc) as invent_code"),DB::raw("CASE WHEN idd.ireq_status = 'C' Then 'Closing' end as ireq_status "))
        ->where('idd.ireq_status','C')
        ->where('dr.div_verificator',$usr_name)
        ->orderBy('idd.ireqd_id','ASC')
        ->get();

        return json_encode(['ict'=>$ict,'ict1'=>$ict1,'ict2'=>$ict2,'ict3'=>$ict3,'ict4'=>$ict4,'ict5'=>$ict5]);
    }
    Public function getPermohonanDivisi()
    {
        $ict1 = $ict = DB::table('ireq_mst as im')
        ->select('im.ireq_id','im.ireq_no','im.ireq_date','im.ireq_user','im.ireq_requestor','dr.div_name', 
                DB::raw('count(idd.ireq_assigned_to) as ireq_count_status'), DB::raw('count(idd.ireq_id) as ireq_count_id'),
                DB::raw("CASE WHEN im.ireq_status = 'P' Then 'Permohonan' end as ireq_status "))
        ->leftjoin('divisi_refs as dr','im.ireq_divisi_user','dr.div_id')
        ->leftjoin('ireq_dtl as idd','im.ireq_id','idd.ireq_id')
        ->where('im.ireq_status','P')
        ->groupBy('im.ireq_id','im.ireq_no','im.ireq_date','im.ireq_user','im.ireq_requestor','dr.div_name','im.creation_date','im.ireq_status')
        ->orderBy('im.creation_date','ASC')
        ->get(); 

        $ict2 = DB::table('ireq_mst as im')
        ->select('im.ireq_id','im.ireq_no','im.ireq_date','im.ireq_user','im.ireq_requestor','dr.div_name',
                DB::raw("CASE WHEN im.ireq_status = 'NA1' Then 'Belum Diapprove Atasan' WHEN im.ireq_status = 'A1' Then 'Sudah Diapprove Atasan' end as ireq_status "),
                DB::raw('count(idd.ireq_assigned_to) as ireq_count_status'), DB::raw('count(idd.ireq_id) as ireq_count_id'))
        ->leftjoin('divisi_refs as dr','im.ireq_divisi_user','dr.div_id')
        ->leftjoin('ireq_dtl as idd','im.ireq_id','idd.ireq_id')
        ->where('im.ireq_status','NA1')
        ->orwhere('im.ireq_status','A1')
        ->groupBy('im.ireq_id','im.ireq_no','im.ireq_date','im.ireq_user','im.ireq_requestor','dr.div_name','im.creation_date','im.ireq_status')
        ->orderBy('im.creation_date','ASC')
        ->get(); 

        $ict3 = DB::table('ireq_mst as im')
        ->select('im.ireq_id','im.ireq_no','im.ireq_date','im.ireq_user','im.ireq_requestor','dr.div_name',
                DB::raw("CASE WHEN im.ireq_status = 'NA2' Then 'Belum Diapprove ICT Manager' WHEN im.ireq_status = 'A2' Then 'Sudah Diapprove ICT Manager' end as ireq_status "),
                DB::raw('count(idd.ireq_assigned_to) as ireq_count_status'), DB::raw('count(idd.ireq_id) as ireq_count_id'))
        ->leftjoin('divisi_refs as dr','im.ireq_divisi_user','dr.div_id')
        ->leftjoin('ireq_dtl as idd','im.ireq_id','idd.ireq_id')
        ->where('im.ireq_status','NA2')
        ->orwhere('im.ireq_status','A2')
        ->groupBy('im.ireq_id','im.ireq_no','im.ireq_date','im.ireq_user','im.ireq_requestor','dr.div_name','im.creation_date','im.ireq_status')
        ->orderBy('im.creation_date','ASC')
        ->get(); 

        $ict4 = DB::table('ireq_mst as im')
        ->select('im.ireq_id','im.ireq_no','im.ireq_date','im.ireq_user','dr.div_name','im.ireq_reason',
                DB::raw("CASE WHEN im.ireq_status = 'RR' Then 'Reject By Reviewer' WHEN im.ireq_status = 'RA1' Then 'Reject By Atasan' WHEN im.ireq_status = 'RA2' Then 'Reject By ICT Manager' end as ireq_status "),
                DB::raw('count(idd.ireq_assigned_to) as ireq_count_status'), DB::raw('count(idd.ireq_id) as ireq_count_id'))
        ->leftjoin('divisi_refs as dr','im.ireq_divisi_user','dr.div_id')
        ->leftjoin('ireq_dtl as idd','im.ireq_id','idd.ireq_id')
        ->where(function ($query){
            return $query
            ->where('im.ireq_status','RR')
            ->OrWhere('im.ireq_status','RA1')
            ->OrWhere('im.ireq_status','RA2');
        })
        ->groupBy('im.ireq_id','im.ireq_no','im.ireq_date','im.ireq_user','im.ireq_reason','dr.div_name','im.creation_date','im.ireq_status')
        ->orderBy('im.creation_date','ASC')
        ->get(); 

        
        $ict5 = DB::table('ireq_mst as im')
        ->select('im.ireq_id','im.ireq_no','im.ireq_date','im.ireq_user','im.ireq_requestor','dr.div_name','im.ireq_assigned_to',
                DB::raw("CASE WHEN im.ireq_status = 'T' Then 'Penugasan' end as ireq_status"))
        ->leftjoin('divisi_refs as dr','im.ireq_divisi_user','dr.div_id')
        ->where('im.ireq_status','T')
        ->groupBy('im.ireq_id','im.ireq_no','im.ireq_date','im.ireq_user','im.ireq_requestor','dr.div_name','im.creation_date','im.ireq_status','im.ireq_assigned_to')
        ->orderBy('im.creation_date','ASC')
        ->get();

        $ict6 = DB::Table('v_ireq_mst_sudah_dikerjakan')->get();

        $ict7 = DB::Table('v_ireq_mst_selesai')->get();
        return json_encode(['ict1'=>$ict1,'ict2'=>$ict2,'ict3'=>$ict3,'ict4'=>$ict4,'ict5'=>$ict5,'ict6'=>$ict6,'ict7'=>$ict7]);
    }
    Public function getSedangDikerjakan($usr_fullname)
    {
        $ict = DB::table('ireq_mst as im')
        ->select('im.ireq_id','im.ireq_no','im.ireq_date','im.ireq_requestor','im.ireq_user','im.ireq_status','im.ireq_assigned_to')
        ->leftjoin('ireq_dtl as idm','im.ireq_id','idm.ireq_id')
        ->where('im.ireq_status','T')
        ->where('idm.ireq_assigned_to',$usr_fullname)
        ->groupBy('im.ireq_id','im.ireq_no','im.ireq_date','im.ireq_requestor','im.ireq_user','im.ireq_status','im.ireq_assigned_to')
        ->get();

        $ict1 = DB::Table('v_ireq_mst_sudah_dikerjakan')->get();
        $ict2 = DB::Table('v_ireq_mst_selesai')->get();
        return json_encode(['ict'=>$ict,'ict1'=>$ict1,'ict2'=>$ict2]);
    }
    public function ictDivisi4()
    {

        $ict = DB::table('ireq_mst as im')
        ->select('im.ireq_id','im.ireq_no','im.ireq_date','im.ireq_user','im.ireq_requestor','dr.div_name')
        ->leftjoin('divisi_refs as dr','im.ireq_divisi_user','dr.div_id')
        ->where('im.ireq_status','NA2')
        ->groupBy('im.ireq_id','im.ireq_no','im.ireq_date','im.ireq_user','im.ireq_requestor','dr.div_name','im.creation_date','im.ireq_status')
        ->orderBy('im.creation_date','ASC')
        ->get(); 

        $ict1 = DB::table('ireq_mst as im')
        ->select('im.ireq_id','im.ireq_no','im.ireq_date','im.ireq_user','im.ireq_requestor','dr.div_name')
        ->leftjoin('divisi_refs as dr','im.ireq_divisi_user','dr.div_id')
        ->where('im.ireq_status','A2')
        ->groupBy('im.ireq_id','im.ireq_no','im.ireq_date','im.ireq_user','im.ireq_requestor','dr.div_name','im.creation_date','im.ireq_status')
        ->orderBy('im.creation_date','ASC')
        ->get(); 

        $ict2 = DB::table('ireq_mst as im')
        ->select('im.ireq_id','im.ireq_no','im.ireq_date','im.ireq_user','im.ireq_requestor','dr.div_name','im.ireq_reason',
          DB::raw("CASE WHEN im.ireq_status = 'RA2' Then 'Reject By ICT Manager' end as ireq_status "))
        ->leftjoin('divisi_refs as dr','im.ireq_divisi_user','dr.div_id')
        ->where('im.ireq_status','RA2')
        ->groupBy('im.ireq_id','im.ireq_no','im.ireq_date','im.ireq_user','im.ireq_requestor','dr.div_name','im.creation_date','im.ireq_status','im.ireq_reason')
        ->orderBy('im.creation_date','ASC')
        ->get(); 

        $ict3 = DB::table('ireq_mst as im')
        ->select('im.ireq_id','im.ireq_no','im.ireq_date','im.ireq_user','im.ireq_requestor','dr.div_name','im.ireq_assigned_to',
                DB::raw("CASE WHEN im.ireq_status = 'T' Then 'Penugasan' end as ireq_status"))
        ->leftjoin('divisi_refs as dr','im.ireq_divisi_user','dr.div_id')
        ->where('im.ireq_status','T')
        ->groupBy('im.ireq_id','im.ireq_no','im.ireq_date','im.ireq_user','im.ireq_requestor','dr.div_name','im.creation_date','im.ireq_status','im.ireq_assigned_to')
        ->orderBy('im.creation_date','ASC')
        ->get();

        $ict4 = DB::Table('v_ireq_mst_sudah_dikerjakan')->get();
        $ict5 = DB::Table('v_ireq_mst_selesai')->get();

        return json_encode(['ict'=>$ict,'ict1'=>$ict1,'ict2'=>$ict2,'ict3'=>$ict3,'ict4'=>$ict4,'ict5'=>$ict5],200);
    }
    function totalRequest($usr_name)
    {
        $ict = DB::table('ireq_mst as id')
            ->select('id.ireq_id','id.ireq_no','id.ireq_user','id.ireq_date',
                DB::raw("CASE WHEN id.ireq_status = 'A1' Then 'Approved By Atasan' WHEN  id.ireq_status = 'NA1' Then 'Menunggu Diapprove Atasan' WHEN id.ireq_status = 'NA2' Then 'Menunggu Diapprove ICT Manager' WHEN
                id.ireq_status = 'A2' Then 'Approved By ICT Manager' WHEN id.ireq_status = 'T' Then 'Penugasan'
                WHEN id.ireq_status = 'RR' Then 'Reject By Reviewer' WHEN id.ireq_status = 'RA1' Then 'Reject By Atasan'
                WHEN id.ireq_status = 'RA2' Then 'Reject By Manager' WHEN id.ireq_status = 'D' Then 'Done' 
                WHEN id.ireq_status = 'C' Then 'Close' WHEN id.ireq_status = 'P' Then 'Permohonan' 
                end as ireq_status "))
            ->where('id.created_by',$usr_name)
            ->whereNotNull('id.ireq_status')
            ->orderBy(DB::raw("CASE WHEN id.ireq_status = 'P' Then 1 WHEN id.ireq_status = 'NA1' Then
             2 WHEN id.ireq_status = 'NA2' Then 3 WHEN
              id.ireq_status = 'A1' Then 4 WHEN id.ireq_status = 'A2' Then 5
              WHEN id.ireq_status = 'RR' Then 6 WHEN id.ireq_status = 'RA1' Then 7
              WHEN id.ireq_status = 'RA2' THEN 8 WHEN id.ireq_status = 'T' Then 9 
              WHEN id.ireq_status = 'D' Then 10 WHEN id.ireq_status = 'C' Then 11 end "))
            ->get();
        return json_encode($ict);
    }
    Public function save(Request $request)
    {
        $message = [
            'tgl.required'=>'Tgl. Request Wajib Diisi',
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
        return json_encode($ict,200);
    }
    Public function edit($code)
    {
        $ict = Ict::find($code);
            return json_encode($ict);
    }
    Public function update(Request $request, $code)
    {
        $message = [
            'ireq_date.required'=>'Tgl. Request Wajib Diisi',
            'ireq_type.required'=>'Tipe Request Wajib diisi',
            'ireq_bu.required'=>'Bisnis Unit Wajib Diisi',
            'ireq_user.required'=>'Pengguna Wajib diisi',
            'ireq_divisi_user.required'=>'Divisi Pengguna Wajib Diisi'
        ];
        $request->validate([
                'ireq_date' => 'required',
                'ireq_type'=>'required',
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
        return json_encode($msg);
    }
    Public function delete($ireq_id)
    {
        $ict = Ict::find($ireq_id);
        $dtl= DB::table('ireq_dtl')->where('ireq_id',$ireq_id)->delete();
          $ict->delete();
          return json_encode('Successfully deleted');
    }
    Public function getNoreq()
    {
        $ict = Ict::select('ireq_no as name','ireq_id as code')
                ->orderBy('ireq_no','ASC')
                ->get();
            return json_encode($ict);
    }
    Public function getNameBu($noreq)
    {
        $ict = DB::table('ireq_mst as im')
        ->select('im.ireq_requestor as req','im.ireq_no','im.ireq_id','vr.name as bu',
                DB::raw("TO_CHAR(im.ireq_date, 'dd Mon YYYY') as ireq_date"))
        ->join('vcompany_refs as vr','im.ireq_bu','vr.company_code')
        ->where('im.ireq_id',$noreq)
        ->first();
            return json_encode($ict);
    }
    Public function cetak_pdf_permohonan()
    {
        $ict =  DB::table('ireq_mst as im')
        ->select('im.ireq_no','im.ireq_requestor','vr.name as ireq_bu','lr.lookup_desc as ireq_type','im.ireq_user','dr.div_name',
                DB::raw("TO_CHAR(im.ireq_date,' dd Mon YYYY') as ireq_date"))
        ->leftjoin('vcompany_refs as vr','im.ireq_bu','vr.company_code')
        ->leftjoin('lookup_refs as lr','im.ireq_type','lr.lookup_code')
        ->leftjoin('divisi_refs as dr','im.ireq_divisi_user','dr.div_id')
        ->where('im.ireq_status','P')
        ->whereRaw('LOWER(lr.lookup_type) LIKE ? ',[trim(strtolower('req_type')).'%'])
        ->orderBy('im.creation_date','ASC')
        ->get();
        return view('pdf/Laporan_IctRequest_Permohonan', compact('ict'));
    }
    public function cetak_excel_permohonan()
    {
        $newCreation = Carbon::parse($this->date)->copy()->tz('Asia/Jakarta')->format('d M Y');
        return Excel::download(new IctExportPermohonan,'Laporan Ict Status Permohonan '.$newCreation.'.xlsx');
    }
    Public function cetak_pdf_verifikasi()
    {
        $ict =  DB::table('ireq_mst as im')
        ->select('im.ireq_no','im.ireq_requestor','vr.name as ireq_bu','lr.lookup_desc as ireq_type',
                DB::raw("TO_CHAR(im.ireq_date,' dd Mon YYYY') as ireq_date"))
        ->join('vcompany_refs as vr','im.ireq_bu','vr.company_code')
        ->join('lookup_refs as lr','im.ireq_type','lr.lookup_code')
        ->where('im.ireq_status','A')
        ->whereRaw('LOWER(lr.lookup_type) LIKE ? ',[trim(strtolower('req_type')).'%'])
        ->orderBy('im.creation_date','ASC')
        ->get();
        return view('pdf/Laporan_IctRequest_Verifikasi', compact('ict'));
    }
    public function cetak_excel_verifikasi()
    {
        return Excel::download(new IctExportVerifikasi,'Laporan_Ict_Verifikasi.xlsx');
    }
    Public function cetak_pdf_reject()
    {
        $ict =  DB::table('ireq_mst as im')
        ->select('im.ireq_no','im.ireq_requestor','im.ireq_reason','vr.name as ireq_bu','lr.lookup_desc as ireq_type',
                DB::raw("TO_CHAR(im.ireq_date,' dd Mon YYYY') as ireq_date"))
        ->join('vcompany_refs as vr','im.ireq_bu','vr.company_code')
        ->join('lookup_refs as lr','im.ireq_type','lr.lookup_code')
        ->where('im.ireq_status','R')
        ->whereRaw('LOWER(lr.lookup_type) LIKE ? ',[trim(strtolower('req_type')).'%'])
        ->orderBy('im.creation_date','ASC')
        ->get();
        return view('pdf/Laporan_IctRequest_Reject', compact('ict'));
    }
    public function cetak_excel_reject()
    {
        return Excel::download(new IctExportReject,'Laporan_Ict_Reject.xlsx');
    }
    Public function cetak_pdf_sedang_dikerjakan()
    {
        $ict =  DB::table('ireq_mst as im')
        ->select('im.ireq_no','im.ireq_requestor','vr.name as ireq_bu','lr.lookup_desc as ireq_type',
                DB::raw("TO_CHAR(im.ireq_date,' dd Mon YYYY') as ireq_date"))
        ->join('vcompany_refs as vr','im.ireq_bu','vr.company_code')
        ->join('lookup_refs as lr','im.ireq_type','lr.lookup_code')
        ->where('im.ireq_status','T')
        ->whereRaw('LOWER(lr.lookup_type) LIKE ? ',[trim(strtolower('req_type')).'%'])
        ->orderBy('im.creation_date','ASC')
        ->get();
        return view('pdf/Laporan_IctRequest_Sedang_Dikerjakan', compact('ict'));
    }
    public function cetak_excel_sudah_dikerjakan()
    {
        return Excel::download(new IctExportSudahDikerjakan,'Laporan_Ict_Sudah_Dikerjakan.xlsx');
    }
    Public function cetak_pdf_sudah_dikerjakan()
    {
        $ict =  DB::table('ireq_mst as im')
        ->select('im.ireq_no','im.ireq_requestor','vr.name as ireq_bu','lr.lookup_desc as ireq_type',
                DB::raw("TO_CHAR(im.ireq_date,' dd Mon YYYY') as ireq_date"))
        ->join('vcompany_refs as vr','im.ireq_bu','vr.company_code')
        ->join('lookup_refs as lr','im.ireq_type','lr.lookup_code')
        ->where('im.ireq_status','D')
        ->whereRaw('LOWER(lr.lookup_type) LIKE ? ',[trim(strtolower('req_type')).'%'])
        ->orderBy('im.creation_date','ASC')
        ->get();
        return view('pdf/Laporan_IctRequest_Sudah_Dikerjakan', compact('ict'));
    }
    Public function cetak_pdf_selesai()
    {
        $ict =  DB::table('ireq_mst as im')
        ->select('im.ireq_no','im.ireq_requestor','vr.name as ireq_bu','lr.lookup_desc as ireq_type',
                DB::raw("TO_CHAR(im.ireq_date,' dd Mon YYYY') as ireq_date"))
        ->join('vcompany_refs as vr','im.ireq_bu','vr.company_code')
        ->join('lookup_refs as lr','im.ireq_type','lr.lookup_code')
        ->where('im.ireq_status','C')
        ->whereRaw('LOWER(lr.lookup_type) LIKE ? ',[trim(strtolower('req_type')).'%'])
        ->orderBy('im.creation_date','ASC')
        ->get();
        return view('pdf/Laporan_IctRequest_Selesai', compact('ict'));
    }
    public function cetak_excel_selesai()
    {
        return Excel::download(new IctExportSelesai,'Laporan_Ict_Sudah_Selesai.xlsx');
    }
    public function cetak_excel_sedang_dikerjakan()
    {
        return Excel::download(new IctExportSedangDikerjakan,'Laporan_Ict_Sedang_Dikerjakan.xlsx');
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
        return json_encode('Success Update');
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
        return json_encode('Success Update');
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
        return json_encode('Success Update');
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
        return json_encode('Success Update Status');
    }
    public function cekVerif($code)
    {
        $link = Link::where('link_id',$code)->first();
        return json_encode($link);
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
        return json_encode('Success Update');
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
        return json_encode('Success Update');
    }
}
