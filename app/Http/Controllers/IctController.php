<?php

namespace App\Http\Controllers;

use App\Ict;
use App\IctDetail;
use App\Lookup_Refs;
use App\Link;
use App\Exports\IctExportPermohonan;
use App\Exports\IctExportVerifikasi;
use App\Exports\IctExportReject;
use App\Exports\IctExportAssignmentRequest;
use App\Exports\IctExportSedangDikerjakan;
use App\Exports\IctExportSudahDikerjakan;
use App\Exports\IctExportSelesai;
use App\Exports\IctExportTabReviewer;
use App\Exports\IctExportPermohonanAtasan;
use App\Exports\IctExportVerifikasiAtasan;
use App\Exports\IctExportRejectAtasan;
use App\Exports\IctExportAtasanAssignmentRequest;
use App\Exports\IctExportAtasanSedangDikerjakan;
use App\Exports\IctExportAtasanSudahDikerjakan;
use App\Exports\IctExportAtasanSelesai;
use App\Exports\IctExportReviewerPermohonan;
use App\Exports\IctExportReviewerAtasanDivisi;
use App\Exports\IctExportReviewerIctManager;
use App\Exports\IctExportRejectReviewer;
use App\Exports\IctExportReviewerAssignmentRequest;
use App\Exports\IctExportReviewerReject;
use App\Exports\IctExportReviewerSedangDikerjakan;
use App\Exports\IctExportReviewerSudahDikerjakan;
use App\Exports\IctExportReviewerSelesai;
use App\Exports\IctExportManagerPermohonan;
use App\Exports\IctExportManagerSudahDikerjakan;
use App\Exports\IctExportManagerSudahSelesai;
use App\Exports\IctExportManagerAssignmentRequest;
use App\Exports\IctExportVerifikasiManager;
use App\Exports\IctExportRejectManager;
use App\Exports\IctExportManagerSedangDikerjakan;
use App\Exports\IctExportPersonnelAssignmentRequest;
use App\Exports\IctExportPersonnelReject;
use App\Exports\IctExportPersonnelSedangDikerjakan;
use App\Exports\IctExportPersonnelSudahDikerjakan;
use App\Exports\IctExportPersonnelSelesai;
use App\Mng_usr_roles;
use App\Mng_roles;
use App\Mng_role_menu;
use DB;
use Excel;
use Carbon\Carbon;
use Session;
use Auth;
use Illuminate\Http\Request;
use App\Jobs\SendNotifApproval;
use App\Jobs\SendNotifPersonnel;
use App\Jobs\SendNotifRequest;
use App\Jobs\SendNotifIctManager;
use App\Jobs\SendNotifWaitingApprovalFromHigherLevel;
use App\Jobs\SendNotifApprovedFromHigherLevel;
use App\Jobs\SendNotifWaitingApprovalFromIctManager;
use App\Jobs\SendNotifApprovedFromIctManager;
use Mail;
use App\Location;
use Illuminate\Support\Facades\Hash;

class IctController extends Controller
{
    function __construct(){
        $this->reviewer = "/ict-request-reviewer";
        $this->personnel = "/ict-request-divisi3";
        $this->manager = "/ict-request-manager";
        $this->approvalrequestor = "/ict-request-divisi1";
        $this->requestor = "/ict-request";
        $date = Carbon::now();
        $this->date = $date;
        $this->newCreation =Carbon::parse($date)->copy()->tz('Asia/Jakarta')->format('Y-m-d H:i:s');
        $this->newUpdate = Carbon::parse($date)->copy()->tz('Asia/Jakarta')->format('Y-m-d H:i:s');
    }
    public function approveByAtasan($code)
    {
        
        $ict = Ict::where('ireq_id',$code)->first();
        $ict->ireq_status = 'A1';
        $ict->ireq_approver1 = Auth::user()->usr_name;
        $ict->ireq_approver1_date = $this->newUpdate;
        $ict->last_updated_by = Auth::user()->usr_name;
        $ict->last_update_date = $this->newUpdate;
        $ict->program_name = "IctController_approveByAtasan";
        $ict->save();

        $dtl = DB::table('ireq_dtl')
        ->WHERE('ireq_id',$code)
        ->update([
            'ireq_status' => 'A1',
            'last_update_date' => $this->newUpdate,
            'last_updated_by' => Auth::user()->usr_name,
            'program_name' => "IctController_approveByAtasan",
        ]);
        $ICT = DB::table('ireq_dtl as id')
            ->LEFTJOIN('ireq_mst as im','id.ireq_id','im.ireq_id')
            ->LEFTJOIN('lookup_refs as lrs',function ($join) {
                $join->on('id.invent_code','lrs.lookup_code')
                    ->WHERERaw('LOWER(lrs.lookup_type) LIKE ? ',[trim(strtolower('kat_peripheral')).'%']);
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
            ->SELECT('loc.loc_email','mu.usr_fullname','mu.usr_email','im.ireq_no','id.ireqd_id','vr.name as ireq_bu','im.ireq_id','dr.div_name', 'mu.usr_name',DB::raw("TO_CHAR(im.ireq_date, 'dd Mon YYYY HH24:MM') as ireq_date"),'im.ireq_requestor',
                    'im.ireq_user','lrs.lookup_desc as invent_code','id.ireq_qty','lrfs.lookup_desc as ireq_type','id.ireq_remark')
            ->WHERE('im.ireq_id',$code)
            ->ORDERBY('id.ireqd_id','ASC')
            ->get();
        $email_address = $ICT[0]->usr_email .= '@emp.id';
        SendNotifApprovedFromHigherLevel::dispatchAfterResponse($email_address,$ICT);
        return response()->json('Success Update');
    }
    public function rejectByAtasan(Request $request, $code)
    {
        
        $ict = Ict::where('ireq_id',$code)->first();
        $ict->ireq_status = 'RA1';
        $ict->ireq_approver1 = Auth::user()->usr_name;
        $ict->ireq_approver1_date = $this->newUpdate;
        $ict->ireq_reason = $request->ket;
        $ict->last_update_date = $this->newUpdate;
        $ict->last_updated_by = Auth::user()->usr_name;
        $ict->program_name = "IctController_rejectByAtasan";
        $ict->save();

        $dtl = DB::table('ireq_dtl')
        ->WHERE('ireq_id',$code)
        ->update([
            'ireq_status' => 'RA1',
            'ireq_reason' => $request->ket,
            'last_update_date' => $this->newUpdate,
            'last_updated_by' => Auth::user()->usr_name,
            'program_name' => "IctController_rejectByAtasan",
        ]);
        
        return response()->json('Success Update');
    }
    function getDataManager()
    {
        $role = Mng_usr_roles::select('rol_id')->WHERE('usr_id',Auth::user()->usr_id)->pluck('rol_id');
        $menu = Mng_role_menu::select('menu_id')->WHEREIn('rol_id',$role)->pluck('menu_id');
        $aksesmenu = DB::table('mng_menus')->SELECT('controller')->WHEREIn('menu_id',$menu)->pluck('controller');
        if($aksesmenu->contains($this->manager)){
            $ict = DB::table('ireq_mst as im')
            ->SELECT('im.ireq_id','im.ireq_no','im.ireq_date','im.ireq_user','im.ireq_requestor',
            'im.ireq_status as status','dr.div_name','lr.lookup_desc as ireq_status')
            ->LEFTJOIN('divisi_refs as dr','im.ireq_divisi_user','dr.div_id')
            ->LEFTJOIN('lookup_refs as lr','im.ireq_status','lr.lookup_code')
            ->WHERE(function($query){
                return $query
                ->WHERE('im.ireq_status','NA2')
                ->Orwhere('im.ireq_status','NA1')
                ->Orwhere('im.ireq_status','P');
            })
            ->WHERERaw('LOWER(lr.lookup_type) LIKE ? ',[trim(strtolower('ict_status')).'%'])
            ->groupBy('lr.lookup_desc','im.ireq_id','im.ireq_no','im.ireq_date','im.ireq_user','im.ireq_status','im.ireq_requestor','dr.div_name','im.creation_date')
            ->ORDERBY('im.ireq_date','DESC')
            ->get(); 

            $ict1 = DB::table('ireq_mst as im')
            ->SELECT('im.ireq_id','im.ireq_no','im.ireq_date','im.ireq_user','im.ireq_requestor', 
            'dr.div_name','lr.lookup_desc as ireq_status','im.ireq_status as status')
            ->LEFTJOIN('divisi_refs as dr','im.ireq_divisi_user','dr.div_id')
            ->LEFTJOIN('lookup_refs as lr','im.ireq_status','lr.lookup_code')
            ->WHERE('im.ireq_status','A2')
            ->orwhere('im.ireq_status','A1')
            ->WHERERaw('LOWER(lr.lookup_type) LIKE ? ',[trim(strtolower('ict_status')).'%'])
            ->groupBy('lr.lookup_desc','im.ireq_id','im.ireq_no','im.ireq_date','im.ireq_user','im.ireq_status','im.ireq_requestor','dr.div_name','im.creation_date')
            ->ORDERBY('im.ireq_date','DESC')
            ->get(); 

            $ict2 = DB::table('ireq_mst as im')
            ->SELECT('im.ireq_id','im.ireq_no','im.ireq_date','im.ireq_user','im.ireq_requestor','dr.div_name',
            'im.ireq_reason','lr.lookup_desc as ireq_status','im.ireq_status as status')
            ->LEFTJOIN('divisi_refs as dr','im.ireq_divisi_user','dr.div_id')
            ->LEFTJOIN('lookup_refs as lr','im.ireq_status','lr.lookup_code')
            ->WHERE('im.ireq_status','RR')
            ->orwhere('im.ireq_status','RA1')
            ->orwhere('im.ireq_status','RA2')
            ->WHERERaw('LOWER(lr.lookup_type) LIKE ? ',[trim(strtolower('ict_status')).'%'])
            ->groupBy('lr.lookup_desc','im.ireq_status','im.ireq_id','im.ireq_no','im.ireq_date','im.ireq_user','im.ireq_requestor','dr.div_name','im.creation_date','im.ireq_reason')
            ->ORDERBY('im.ireq_date','DESC')
            ->get(); 

            $ict3 = DB::table('ireq_mst as im')
                ->SELECT('im.ireq_id','im.ireq_no','im.ireq_date','im.ireq_user','im.ireq_requestor',
                'dr.div_name',DB::raw("COALESCE(im.ireq_assigned_to2,im.ireq_assigned_to1) AS ireq_assigned_to"),
                'lr.lookup_desc as ireq_status','im.ireq_status as status')
                ->LEFTJOIN('divisi_refs as dr','im.ireq_divisi_user','dr.div_id')
                ->LEFTJOIN('lookup_refs as lr','im.ireq_status','lr.lookup_code')
                ->WHERE('im.ireq_status','T')
                ->WHERERaw('LOWER(lr.lookup_type) LIKE ? ',[trim(strtolower('ict_status')).'%'])
                ->groupBy('lr.lookup_desc','im.ireq_status','im.ireq_id','im.ireq_no','im.ireq_date','im.ireq_user','im.ireq_requestor','dr.div_name','im.creation_date',DB::raw("COALESCE(im.ireq_assigned_to2,im.ireq_assigned_to1)"))
                ->ORDERBY('im.ireq_date','DESC')
                ->get();

            $ict4 = DB::table('ireq_dtl as id')
            ->LEFTJOIN('ireq_mst as im','id.ireq_id','im.ireq_id')
            ->LEFTJOIN('divisi_refs as dr','im.ireq_divisi_user','dr.div_id')
            ->LEFTJOIN('lookup_refs as lr',function ($join) {
                $join->on('id.ireq_status','lr.lookup_code')
                      ->WHERERaw('LOWER(lr.lookup_type) LIKE ? ',[trim(strtolower('ict_status')).'%']);
            })
            ->LEFTJOIN('lookup_refs as lrs',function ($join) {
                $join->on('id.ireq_type','lrs.lookup_code')
                      ->WHERERaw('LOWER(lrs.lookup_type) LIKE ? ',[trim(strtolower('req_type')).'%']);
            })
            ->LEFTJOIN('lookup_refs as lrfs',function ($join) {
                $join->on('id.invent_code','lrfs.lookup_code')
                      ->WHERERaw('LOWER(lrfs.lookup_type) LIKE ? ',[trim(strtolower('kat_peripheral')).'%']);
            })
            ->SELECT('id.ireq_attachment','im.ireq_no','id.ireq_id','id.ireq_remark',DB::raw("COALESCE(id.ireq_assigned_to2,id.ireq_assigned_to1) AS ireq_assigned_to"),'id.ireqd_id',
            'lr.lookup_desc as ireq_status','lrs.lookup_desc as ireq_type','lrfs.lookup_desc as kategori','im.ireq_date','im.ireq_requestor','im.ireq_user',
            'dr.div_name','id.ireq_qty','id.ireq_status as status')
            ->WHERE('id.ireq_status','D')
            ->ORDERBY('im.ireq_date','DESC')
            ->ORDERBY('id.ireqd_id','ASC')
            ->get();
            $ict5 = DB::table('ireq_dtl as id')
            ->LEFTJOIN('ireq_mst as im','id.ireq_id','im.ireq_id')
            ->LEFTJOIN('divisi_refs as dr','im.ireq_divisi_user','dr.div_id')
            ->LEFTJOIN('lookup_refs as lr',function ($join) {
                $join->on('id.ireq_status','lr.lookup_code')
                      ->WHERERaw('LOWER(lr.lookup_type) LIKE ? ',[trim(strtolower('ict_status')).'%']);
            })
            ->LEFTJOIN('lookup_refs as lrs',function ($join) {
                $join->on('id.ireq_type','lrs.lookup_code')
                      ->WHERERaw('LOWER(lrs.lookup_type) LIKE ? ',[trim(strtolower('req_type')).'%']);
            })
            ->LEFTJOIN('lookup_refs as lrfs',function ($join) {
                $join->on('id.invent_code','lrfs.lookup_code')
                      ->WHERERaw('LOWER(lrfs.lookup_type) LIKE ? ',[trim(strtolower('kat_peripheral')).'%']);
            })
            ->SELECT('id.ireq_attachment','im.ireq_no','id.ireq_id','id.ireq_remark',DB::raw("COALESCE(id.ireq_assigned_to2,id.ireq_assigned_to1) AS ireq_assigned_to"),'id.ireqd_id',
            'lr.lookup_desc as ireq_status','lrs.lookup_desc as ireq_type','lrfs.lookup_desc as kategori','im.ireq_date','im.ireq_requestor','im.ireq_user',
            'dr.div_name','id.ireq_qty','id.ireq_status as status')
            ->WHERE('id.ireq_status','C')
            ->ORDERBY('im.ireq_date','DESC')
            ->ORDERBY('id.ireqd_id','ASC')
            ->get();
            $ict6 = DB::table('ireq_mst as im')
                ->LEFTJOIN('divisi_refs as dr','im.ireq_divisi_user','dr.div_id')
                ->LEFTJOIN('lookup_refs as lr','im.ireq_status','lr.lookup_code')
                ->SELECT('im.ireq_id','im.ireq_assigned_to2','im.ireq_no','im.ireq_date','im.ireq_user',
                'im.ireq_requestor','dr.div_name',DB::raw("COALESCE(im.ireq_assigned_to2,im.ireq_assigned_to1) 
                AS ireq_assigned_to"),'lr.lookup_desc as ireq_status','im.ireq_status as status')
                ->WHERE(function($query){
                    return $query
                    ->WHERE('im.ireq_status','NT')
                    ->Orwhere('im.ireq_status','RT');
                })
                ->WHERERaw('LOWER(lr.lookup_type) LIKE ? ',[trim(strtolower('ict_status')).'%'])
                ->groupBy('im.ireq_id','im.ireq_status','im.ireq_assigned_to2','im.ireq_no','im.ireq_date','im.ireq_user','im.ireq_requestor','dr.div_name','im.creation_date','lr.lookup_desc',DB::raw("COALESCE(im.ireq_assigned_to2,im.ireq_assigned_to1)"))
                ->ORDERBY('im.ireq_date','DESC')
                ->get();

            return response()->json(['ict'=>$ict,'ict1'=>$ict1,'ict2'=>$ict2,'ict3'=>$ict3,'ict4'=>$ict4,'ict5'=>$ict5,'ict6'=>$ict6],200);
        }else{
            return response(["message"=>"Cannot Access"],403);
        }
    }
    function getDataManagerVerifikasi($code)
    {
        $dtl = DB::table('ireq_dtl as id')
        ->SELECT('lr.lookup_desc as ireq_type','lrs.lookup_desc','id.invent_code')
        ->LEFTJOIN('invent_mst as im','id.invent_code','im.invent_code')
        ->LEFTJOIN('lookup_refs as lr','id.ireq_type','lr.lookup_code')
        ->LEFTJOIN('lookup_refs as lrs',function ($join) {
            $join->on('id.invent_code','lrs.lookup_code')
                  ->WHERERaw('LOWER(lrs.lookup_type) LIKE ? ',[trim(strtolower('kat_peripheral')).'%']);
        })
        ->WHERE('id.ireq_id',$code)
        ->WHERERaw('LOWER(lr.lookup_type) LIKE ? ',[trim(strtolower('req_type')).'%'])
        ->get();
            return response()->json($dtl);
    }
    function approveByManager(Request $request,$code)
    { 
        $ict = Ict::where('ireq_id',$code)->first();
        $ict->ireq_status = 'A2';
        $ict->ireq_approver2 = Auth::user()->usr_name;
        $ict->ireq_approver2_remark = $request->remark;
        $ict->ireq_approver2_date = $this->newUpdate;
        $ict->last_updated_by = Auth::user()->usr_name;
        $ict->last_update_date = $this->newUpdate;
        $ict->program_name = "IctController_approveByManager";
        $ict->save();

        $dtl = DB::table('ireq_dtl')
        ->WHERE('ireq_id',$code)
        ->update([
            'ireq_status' => 'A2',
            'last_update_date' => $this->newUpdate,
            'last_updated_by' => Auth::user()->usr_name,
            'program_name' => "IctController_approveByManager",
        ]);

        $ICT = DB::table('ireq_dtl as id')
            ->LEFTJOIN('ireq_mst as im','id.ireq_id','im.ireq_id')
            ->LEFTJOIN('lookup_refs as lrs',function ($join) {
                $join->on('id.invent_code','lrs.lookup_code')
                    ->WHERERaw('LOWER(lrs.lookup_type) LIKE ? ',[trim(strtolower('kat_peripheral')).'%']);
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
            ->SELECT('loc.loc_email','mu.usr_fullname','mu.usr_email','im.ireq_no','id.ireqd_id','vr.name as ireq_bu','im.ireq_id','dr.div_name', 'mu.usr_name',DB::raw("TO_CHAR(im.ireq_date, 'dd Mon YYYY HH24:MM') as ireq_date"),'im.ireq_requestor',
                    'im.ireq_user','lrs.lookup_desc as invent_code','id.ireq_qty','lrfs.lookup_desc as ireq_type','id.ireq_remark')
            ->WHERE('im.ireq_id',$code)
            ->ORDERBY('id.ireqd_id','ASC')
            ->get();
            $email_address = $ICT[0]->usr_email .= '@emp.id';
            SendNotifApprovedFromIctManager::dispatchAfterResponse($email_address,$ICT);

        return response()->json('Success Update');
    }
    function rejectByManager(Request $request,$code)
    {
        
        $ict = Ict::where('ireq_id',$code)->first();
        $ict->ireq_status = 'RA2';
        $ict->ireq_reason = $request->ket;
        $ict->ireq_approver2 = Auth::user()->usr_name;
        $ict->ireq_approver2_date = $this->newUpdate;
        $ict->last_updated_by = Auth::user()->usr_name;
        $ict->last_update_date = $this->newUpdate;
        $ict->program_name = "IctController_rejectByManager";
        $ict->save();

        $dtl = DB::table('ireq_dtl')
        ->WHERE('ireq_id',$code)
        ->update([
            'ireq_status' => 'RA2',
            'ireq_reason' => $request->ket,
            'last_update_date' => $this->newUpdate,
            'last_updated_by' => Auth::user()->usr_name,
            'program_name' => "IctController_rejectByManager",
        ]);
        
        return response()->json('Success Update');
    }
    function getRemarkReviewer($ireq_id)
    {
        $ict = Ict::select('ireq_verificator_remark')->where('ireq_id',$ireq_id)->first();
        return json_encode($ict);
    }
    function saveRemarkReviewer(Request $request)
    {
        $ict = Ict::where('ireq_id',$request->id)->first();
        $ict->ireq_verificator_remark = $request->remark;
        $ict->save();
        return response()->json('success update remark');
    }
    function getDataReviewer()
    {
        $role = Mng_usr_roles::select('rol_id')->WHERE('usr_id',Auth::user()->usr_id)->pluck('rol_id');
        $menu = Mng_role_menu::select('menu_id')->WHEREIn('rol_id',$role)->pluck('menu_id');
        $aksesmenu = DB::table('mng_menus')->SELECT('controller')->WHEREIn('menu_id',$menu)->pluck('controller');
        if($aksesmenu->contains($this->reviewer) && Auth::user()->usr_loc == 'OJ'){
            return \App::call('App\Http\Controllers\\IctController@getDataReviewerJakarta');
        }else if($aksesmenu->contains($this->reviewer) && Auth::user()->usr_loc == 'OK' OR Auth::user()->usr_loc == 'FK'){
            return \App::call('App\Http\Controllers\\IctController@getDataReviewerKurau');
        }
        else if($aksesmenu->contains($this->reviewer) && Auth::user()->usr_loc == 'OB' OR Auth::user()->usr_loc == 'FB'){
            return \App::call('App\Http\Controllers\\IctController@getDataReviewerBentu');
        }
         else{
            return response(["message"=>"Cannot Access"],403);
         }
    }
    function getDataReviewerJakarta()
    {
        $ict = DB::table('ireq_mst as im')
            ->SELECT('im.ireq_id','im.ireq_verificator_remark','im.ireq_status as status','im.ireq_no','im.ireq_date','im.ireq_user','im.ireq_requestor','dr.div_name', 
                    DB::raw('count(idd.ireq_assigned_to1) as ireq_count_status'), DB::raw('count(idd.ireq_id) as ireq_count_id'),
                    'lr.lookup_desc as ireq_status',DB::raw("COALESCE(im.ireq_assigned_to2,im.ireq_assigned_to1) AS ireq_assigned_to"))
            ->LEFTJOIN('divisi_refs as dr','im.ireq_divisi_user','dr.div_id')
            ->LEFTJOIN('ireq_dtl as idd','im.ireq_id','idd.ireq_id')
            ->LEFTJOIN('lookup_refs as lr','im.ireq_status','lr.lookup_code')
            ->WHERE('im.ireq_status','P')
            ->WHERE('im.ireq_loc','OJ')
            ->WHERERaw('LOWER(lr.lookup_type) LIKE ? ',[trim(strtolower('ict_status')).'%'])
            ->groupBy('im.ireq_id','im.ireq_verificator_remark','im.ireq_status','im.ireq_no','im.ireq_date','im.ireq_user','im.ireq_requestor','dr.div_name','im.creation_date','lr.lookup_desc',DB::raw("COALESCE(im.ireq_assigned_to2,im.ireq_assigned_to1)"))
            ->ORDERBY('im.ireq_date','DESC')
            ->get(); 

            $ict1 = DB::table('ireq_mst as im')
            ->SELECT('im.ireq_id','im.ireq_verificator_remark','im.ireq_status as status','im.ireq_no','im.ireq_date','im.ireq_user','im.ireq_requestor','dr.div_name','lr.lookup_desc as ireq_status','im.ireq_status as status',
                    DB::raw('count(idd.ireq_assigned_to1) as ireq_count_status'), DB::raw('count(idd.ireq_id) as ireq_count_id'))
            ->LEFTJOIN('divisi_refs as dr','im.ireq_divisi_user','dr.div_id')
            ->LEFTJOIN('ireq_dtl as idd','im.ireq_id','idd.ireq_id')
            ->LEFTJOIN('lookup_refs as lr','im.ireq_status','lr.lookup_code')
            ->WHERE('im.ireq_status','NA1')
            ->WHERE('im.ireq_loc','OJ')
            ->WHERERaw('LOWER(lr.lookup_type) LIKE ? ',[trim(strtolower('ict_status')).'%'])
            ->orwhere('im.ireq_status','A1')
            ->groupBy('im.ireq_id','im.ireq_verificator_remark','im.ireq_status','im.ireq_no','im.ireq_date','im.ireq_user','im.ireq_requestor','dr.div_name','im.creation_date','lr.lookup_desc','im.ireq_status')
            ->ORDERBY('im.ireq_date','DESC')
            ->get(); 

            $ict2 = DB::table('ireq_mst as im')
            ->SELECT('im.ireq_id','im.ireq_verificator_remark','im.ireq_status as status','im.ireq_no','im.ireq_date','im.ireq_user','im.ireq_requestor','dr.div_name','lr.lookup_desc as ireq_status','im.ireq_status as status',
                    DB::raw('count(idd.ireq_assigned_to1) as ireq_count_status'), DB::raw('count(idd.ireq_id) as ireq_count_id'))
            ->LEFTJOIN('divisi_refs as dr','im.ireq_divisi_user','dr.div_id')
            ->LEFTJOIN('ireq_dtl as idd','im.ireq_id','idd.ireq_id')
            ->LEFTJOIN('lookup_refs as lr','im.ireq_status','lr.lookup_code')
            ->WHERE('im.ireq_loc','OJ')
            ->WHERE(function ($query){
                return $query
                ->WHERE('im.ireq_status','NA2')
                ->OrWhere('im.ireq_status','A2');
            })
            ->WHERERaw('LOWER(lr.lookup_type) LIKE ? ',[trim(strtolower('ict_status')).'%'])
            ->groupBy('im.ireq_id','im.ireq_verificator_remark','im.ireq_status','im.ireq_no','im.ireq_date','im.ireq_user','im.ireq_requestor','dr.div_name','im.creation_date','lr.lookup_desc','im.ireq_status')
            ->ORDERBY('im.ireq_date','DESC')
            ->get(); 

            $ict3 = DB::table('ireq_mst as im')
            ->SELECT('im.ireq_id','im.ireq_verificator_remark','im.ireq_status as status','im.ireq_no','im.ireq_date','im.ireq_user','im.ireq_requestor','dr.div_name','im.ireq_reason','lr.lookup_desc as ireq_status',
                    DB::raw('count(idd.ireq_assigned_to1) as ireq_count_status'), DB::raw('count(idd.ireq_id) as ireq_count_id'))
            ->LEFTJOIN('divisi_refs as dr','im.ireq_divisi_user','dr.div_id')
            ->LEFTJOIN('ireq_dtl as idd','im.ireq_id','idd.ireq_id')
            ->LEFTJOIN('lookup_refs as lr','im.ireq_status','lr.lookup_code')
            ->WHERERaw('LOWER(lr.lookup_type) LIKE ? ',[trim(strtolower('ict_status')).'%'])
            ->where('im.ireq_loc','OJ')
            ->WHERE(function ($query){
                return $query
                ->WHERE('im.ireq_status','RR')
                ->OrWhere('im.ireq_status','RA1')
                ->OrWhere('im.ireq_status','RA2');
            })
            ->groupBy('im.ireq_id','im.ireq_verificator_remark','im.ireq_no','im.ireq_status','im.ireq_date','im.ireq_user','im.ireq_requestor','dr.div_name','im.creation_date','lr.lookup_desc','im.ireq_reason')
            ->ORDERBY('im.ireq_date','DESC')
            ->get(); 

            $ict4 = DB::table('ireq_mst as im')
            ->SELECT('im.ireq_id','im.ireq_verificator_remark','im.ireq_status as status','im.ireq_no','im.ireq_date','im.ireq_user','im.ireq_requestor','dr.div_name',DB::raw("COALESCE(im.ireq_assigned_to2,im.ireq_assigned_to1) AS ireq_assigned_to"),'lr.lookup_desc as ireq_status')
            ->LEFTJOIN('divisi_refs as dr','im.ireq_divisi_user','dr.div_id')
            ->LEFTJOIN('lookup_refs as lr','im.ireq_status','lr.lookup_code')
            ->WHERE('im.ireq_status','T')
            ->WHERE('im.ireq_loc','OJ')
            ->WHERERaw('LOWER(lr.lookup_type) LIKE ? ',[trim(strtolower('ict_status')).'%'])
            ->groupBy('im.ireq_id','im.ireq_verificator_remark','im.ireq_status','im.ireq_no','im.ireq_date','im.ireq_user','im.ireq_requestor','dr.div_name','im.creation_date','lr.lookup_desc',DB::raw("COALESCE(im.ireq_assigned_to2,im.ireq_assigned_to1)"))
            ->ORDERBY('im.ireq_date','DESC')
            ->get();

            $ict5 = DB::table('ireq_dtl as id')
            ->LEFTJOIN('ireq_mst as im','id.ireq_id','im.ireq_id')
            ->LEFTJOIN('divisi_refs as dr','im.ireq_divisi_user','dr.div_id')
            ->LEFTJOIN('lookup_refs as lr',function ($join) {
                $join->on('id.ireq_status','lr.lookup_code')
                      ->WHERERaw('LOWER(lr.lookup_type) LIKE ? ',[trim(strtolower('ict_status')).'%']);
            })
            ->LEFTJOIN('lookup_refs as lrs',function ($join) {
                $join->on('id.ireq_type','lrs.lookup_code')
                      ->WHERERaw('LOWER(lrs.lookup_type) LIKE ? ',[trim(strtolower('req_type')).'%']);
            })
            ->LEFTJOIN('lookup_refs as lrfs',function ($join) {
                $join->on('id.invent_code','lrfs.lookup_code')
                      ->WHERERaw('LOWER(lrfs.lookup_type) LIKE ? ',[trim(strtolower('kat_peripheral')).'%']);
            })
            ->SELECT('id.ireq_attachment','im.ireq_no','im.ireq_verificator_remark','id.ireq_id','id.ireq_remark',DB::raw("COALESCE(id.ireq_assigned_to2,id.ireq_assigned_to1) AS ireq_assigned_to"),'id.ireqd_id',
            'lr.lookup_desc as ireq_status','lrs.lookup_desc as ireq_type','lrfs.lookup_desc as kategori','im.ireq_date','im.ireq_requestor','im.ireq_user',
            'dr.div_name','id.ireq_qty','id.ireq_status as status')
            ->WHERE('id.ireq_status','D')
            ->WHERE('im.ireq_loc','OJ')
            ->ORDERBY('im.ireq_date','DESC')
            ->ORDERBY('id.ireqd_id','ASC')
            ->get();

            $ict6 = DB::table('ireq_dtl as id')
            ->LEFTJOIN('ireq_mst as im','id.ireq_id','im.ireq_id')
            ->LEFTJOIN('divisi_refs as dr','im.ireq_divisi_user','dr.div_id')
            ->LEFTJOIN('lookup_refs as lr',function ($join) {
                $join->on('id.ireq_status','lr.lookup_code')
                      ->WHERERaw('LOWER(lr.lookup_type) LIKE ? ',[trim(strtolower('ict_status')).'%']);
            })
            ->LEFTJOIN('lookup_refs as lrs',function ($join) {
                $join->on('id.ireq_type','lrs.lookup_code')
                      ->WHERERaw('LOWER(lrs.lookup_type) LIKE ? ',[trim(strtolower('req_type')).'%']);
            })
            ->LEFTJOIN('lookup_refs as lrfs',function ($join) {
                $join->on('id.invent_code','lrfs.lookup_code')
                      ->WHERERaw('LOWER(lrfs.lookup_type) LIKE ? ',[trim(strtolower('kat_peripheral')).'%']);
            })
            ->SELECT('id.ireq_attachment','im.ireq_no','im.ireq_verificator_remark','id.ireq_status as status','id.ireq_id','id.ireq_remark',DB::raw("COALESCE(id.ireq_assigned_to2,id.ireq_assigned_to1) AS ireq_assigned_to"),'id.ireqd_id',
            'lr.lookup_desc as ireq_status','lrs.lookup_desc as ireq_type','lrfs.lookup_desc as kategori','im.ireq_date','im.ireq_requestor','im.ireq_user',
            'dr.div_name','id.ireq_qty')
            ->WHERE('id.ireq_status','C')
            ->WHERE('im.ireq_loc','OJ')
            ->ORDERBY('im.ireq_date','DESC')
            ->ORDERBY('id.ireqd_id','ASC')
            ->get();
            
            $ict7 = DB::table('ireq_mst as im')
            ->LEFTJOIN('divisi_refs as dr','im.ireq_divisi_user','dr.div_id')
            ->LEFTJOIN('lookup_refs as lr','im.ireq_status','lr.lookup_code')
            ->SELECT('im.ireq_id','im.ireq_verificator_remark','im.ireq_status as status','im.ireq_assigned_to2','im.ireq_no','im.ireq_date','im.ireq_user','im.ireq_requestor','dr.div_name',DB::raw("COALESCE(im.ireq_assigned_to2,im.ireq_assigned_to1) AS ireq_assigned_to"),'lr.lookup_desc as ireq_status')
            ->WHERE(function($query){
                return $query
                ->OrWhere('im.ireq_status','NT')
                ->Orwhere('im.ireq_status','RT');
            })
            ->WHERE('im.ireq_loc','OJ')
            ->WHERERaw('LOWER(lr.lookup_type) LIKE ? ',[trim(strtolower('ict_status')).'%'])
            ->groupBy('im.ireq_id','im.ireq_verificator_remark','im.ireq_status','im.ireq_assigned_to2','im.ireq_no','im.ireq_date','im.ireq_user','im.ireq_requestor','dr.div_name','im.creation_date','lr.lookup_desc',DB::raw("COALESCE(im.ireq_assigned_to2,im.ireq_assigned_to1)"))
            ->ORDERBY('im.ireq_date','DESC')
            ->get();

            $ict8 = DB::table('ireq_mst as im')
            ->LEFTJOIN('divisi_refs as dr','im.ireq_divisi_user','dr.div_id')
            ->LEFTJOIN('lookup_refs as lr','im.ireq_status','lr.lookup_code')
            ->SELECT('im.ireq_id','im.ireq_verificator_remark','im.ireq_status as status','im.ireq_assigned_to2','im.ireq_no','im.ireq_date','im.ireq_user','im.ireq_requestor','dr.div_name',DB::raw("COALESCE(im.ireq_assigned_to2,im.ireq_assigned_to1) AS ireq_assigned_to"),'lr.lookup_desc as ireq_status')
            ->whereNotNull('im.ireq_status')
            ->WHERE('im.ireq_loc','OJ')
            ->WHERERaw('LOWER(lr.lookup_type) LIKE ? ',[trim(strtolower('ict_status')).'%'])
            ->groupBy('im.ireq_id','im.ireq_verificator_remark','im.ireq_status','im.ireq_assigned_to2','im.ireq_no','im.ireq_date','im.ireq_user','im.ireq_requestor','dr.div_name','im.creation_date','lr.lookup_desc',DB::raw("COALESCE(im.ireq_assigned_to2,im.ireq_assigned_to1)"))
            ->ORDERBY('im.ireq_date','DESC')
            ->get();

            return json_encode(['lokasi'=>'jakarta','ict'=>$ict,'ict1'=>$ict1,'ict2'=>$ict2,'ict3'=>$ict3,'ict4'=>$ict4,'ict5'=>$ict5,'ict6'=>$ict6,'ict7'=>$ict7,'ict8'=>$ict8],200);

    }
    function getDataReviewerBentu()
    {
        $ict = DB::table('ireq_mst as im')
            ->SELECT('im.ireq_id','im.ireq_status as status','im.ireq_no','im.ireq_date','im.ireq_user','im.ireq_requestor','dr.div_name', 
                    DB::raw('count(idd.ireq_assigned_to1) as ireq_count_status'), DB::raw('count(idd.ireq_id) as ireq_count_id'),
                    'lr.lookup_desc as ireq_status',DB::raw("COALESCE(im.ireq_assigned_to2,im.ireq_assigned_to1) AS ireq_assigned_to"))
            ->LEFTJOIN('divisi_refs as dr','im.ireq_divisi_user','dr.div_id')
            ->LEFTJOIN('ireq_dtl as idd','im.ireq_id','idd.ireq_id')
            ->LEFTJOIN('lookup_refs as lr','im.ireq_status','lr.lookup_code')
            ->WHERE('im.ireq_status','P')
            ->WHERE(function ($query){
                return $query
                ->WHERE('im.ireq_loc','OB')
                ->OrWhere('im.ireq_loc','FB');
            })
            ->WHERERaw('LOWER(lr.lookup_type) LIKE ? ',[trim(strtolower('ict_status')).'%'])
            ->groupBy('im.ireq_id','im.ireq_status','im.ireq_no','im.ireq_date','im.ireq_user','im.ireq_requestor','dr.div_name','im.creation_date','lr.lookup_desc',DB::raw("COALESCE(im.ireq_assigned_to2,im.ireq_assigned_to1)"))
            ->ORDERBY('im.ireq_date','DESC')
            ->get(); 

            $ict1 = DB::table('ireq_mst as im')
            ->SELECT('im.ireq_id','im.ireq_status as status','im.ireq_no','im.ireq_date','im.ireq_user','im.ireq_requestor','dr.div_name','lr.lookup_desc as ireq_status','im.ireq_status as status',
                    DB::raw('count(idd.ireq_assigned_to1) as ireq_count_status'), DB::raw('count(idd.ireq_id) as ireq_count_id'))
            ->LEFTJOIN('divisi_refs as dr','im.ireq_divisi_user','dr.div_id')
            ->LEFTJOIN('ireq_dtl as idd','im.ireq_id','idd.ireq_id')
            ->LEFTJOIN('lookup_refs as lr','im.ireq_status','lr.lookup_code')
            ->WHERE(function ($query){
                return $query
                ->WHERE('im.ireq_status','A1')
                ->orwhere('im.ireq_status','NA1');
            })
            ->WHERE(function ($query){
                return $query
                ->WHERE('im.ireq_loc','OB')
                ->OrWhere('im.ireq_loc','FB');
            })
            ->WHERERaw('LOWER(lr.lookup_type) LIKE ? ',[trim(strtolower('ict_status')).'%'])
            ->groupBy('im.ireq_id','im.ireq_status','im.ireq_no','im.ireq_date','im.ireq_user','im.ireq_requestor','dr.div_name','im.creation_date','lr.lookup_desc','im.ireq_status')
            ->ORDERBY('im.ireq_date','DESC')
            ->get(); 

            $ict2 = DB::table('ireq_mst as im')
            ->SELECT('im.ireq_id','im.ireq_status as status','im.ireq_no','im.ireq_date','im.ireq_user','im.ireq_requestor','dr.div_name','lr.lookup_desc as ireq_status','im.ireq_status as status',
                    DB::raw('count(idd.ireq_assigned_to1) as ireq_count_status'), DB::raw('count(idd.ireq_id) as ireq_count_id'))
            ->LEFTJOIN('divisi_refs as dr','im.ireq_divisi_user','dr.div_id')
            ->LEFTJOIN('ireq_dtl as idd','im.ireq_id','idd.ireq_id')
            ->LEFTJOIN('lookup_refs as lr','im.ireq_status','lr.lookup_code')
            ->WHERE(function ($query){
                return $query
                ->WHERE('im.ireq_status','A2')
                ->orwhere('im.ireq_status','NA2');
            })
            ->WHERE(function ($query){
                return $query
                ->WHERE('im.ireq_loc','OB')
                ->OrWhere('im.ireq_loc','FB');
            })
            ->WHERERaw('LOWER(lr.lookup_type) LIKE ? ',[trim(strtolower('ict_status')).'%'])
            ->groupBy('im.ireq_id','im.ireq_status','im.ireq_no','im.ireq_date','im.ireq_user','im.ireq_requestor','dr.div_name','im.creation_date','lr.lookup_desc','im.ireq_status')
            ->ORDERBY('im.ireq_date','DESC')
            ->get(); 

            $ict3 = DB::table('ireq_mst as im')
            ->SELECT('im.ireq_id','im.ireq_status as status','im.ireq_no','im.ireq_date','im.ireq_user','im.ireq_requestor','dr.div_name','im.ireq_reason','lr.lookup_desc as ireq_status',
                    DB::raw('count(idd.ireq_assigned_to1) as ireq_count_status'), DB::raw('count(idd.ireq_id) as ireq_count_id'))
            ->LEFTJOIN('divisi_refs as dr','im.ireq_divisi_user','dr.div_id')
            ->LEFTJOIN('ireq_dtl as idd','im.ireq_id','idd.ireq_id')
            ->LEFTJOIN('lookup_refs as lr','im.ireq_status','lr.lookup_code')
            ->WHERERaw('LOWER(lr.lookup_type) LIKE ? ',[trim(strtolower('ict_status')).'%'])
            ->WHERE(function ($query){
                return $query
                ->WHERE('im.ireq_status','RR')
                ->OrWhere('im.ireq_status','RA1')
                ->OrWhere('im.ireq_status','RA2');
            })
            ->WHERE(function ($query){
                return $query
                ->WHERE('im.ireq_loc','OB')
                ->OrWhere('im.ireq_loc','FB');
            })
            ->groupBy('im.ireq_id','im.ireq_no','im.ireq_status','im.ireq_date','im.ireq_user','im.ireq_requestor','dr.div_name','im.creation_date','lr.lookup_desc','im.ireq_reason')
            ->ORDERBY('im.ireq_date','DESC')
            ->get(); 

            $ict4 = DB::table('ireq_mst as im')
            ->SELECT('im.ireq_id','im.ireq_status as status','im.ireq_no','im.ireq_date','im.ireq_user','im.ireq_requestor','dr.div_name',DB::raw("COALESCE(im.ireq_assigned_to2,im.ireq_assigned_to1) AS ireq_assigned_to"),'lr.lookup_desc as ireq_status')
            ->LEFTJOIN('divisi_refs as dr','im.ireq_divisi_user','dr.div_id')
            ->LEFTJOIN('lookup_refs as lr','im.ireq_status','lr.lookup_code')
            ->WHERE('im.ireq_status','T')
            ->WHERE(function ($query){
                return $query
                ->WHERE('im.ireq_loc','OB')
                ->OrWhere('im.ireq_loc','FB');
            })
            ->WHERERaw('LOWER(lr.lookup_type) LIKE ? ',[trim(strtolower('ict_status')).'%'])
            ->groupBy('im.ireq_id','im.ireq_status','im.ireq_no','im.ireq_date','im.ireq_user','im.ireq_requestor','dr.div_name','im.creation_date','lr.lookup_desc',DB::raw("COALESCE(im.ireq_assigned_to2,im.ireq_assigned_to1)"))
            ->ORDERBY('im.ireq_date','DESC')
            ->get();

            $ict5 = DB::table('ireq_dtl as id')
            ->LEFTJOIN('ireq_mst as im','id.ireq_id','im.ireq_id')
            ->LEFTJOIN('divisi_refs as dr','im.ireq_divisi_user','dr.div_id')
            ->LEFTJOIN('lookup_refs as lr',function ($join) {
                $join->on('id.ireq_status','lr.lookup_code')
                      ->WHERERaw('LOWER(lr.lookup_type) LIKE ? ',[trim(strtolower('ict_status')).'%']);
            })
            ->LEFTJOIN('lookup_refs as lrs',function ($join) {
                $join->on('id.ireq_type','lrs.lookup_code')
                      ->WHERERaw('LOWER(lrs.lookup_type) LIKE ? ',[trim(strtolower('req_type')).'%']);
            })
            ->LEFTJOIN('lookup_refs as lrfs',function ($join) {
                $join->on('id.invent_code','lrfs.lookup_code')
                      ->WHERERaw('LOWER(lrfs.lookup_type) LIKE ? ',[trim(strtolower('kat_peripheral')).'%']);
            })
            ->SELECT('id.ireq_attachment','im.ireq_no','id.ireq_id','id.ireq_remark',DB::raw("COALESCE(id.ireq_assigned_to2,id.ireq_assigned_to1) AS ireq_assigned_to"),'id.ireqd_id',
            'lr.lookup_desc as ireq_status','lrs.lookup_desc as ireq_type','lrfs.lookup_desc as kategori','im.ireq_date','im.ireq_requestor','im.ireq_user',
            'dr.div_name','id.ireq_qty','id.ireq_status as status')
            ->WHERE('id.ireq_status','D')
            ->WHERE(function ($query){
                return $query
                ->WHERE('im.ireq_loc','OB')
                ->OrWhere('im.ireq_loc','FB');
            })
            ->ORDERBY('im.ireq_date','DESC')
            ->ORDERBY('id.ireqd_id','ASC')
            ->get();

            $ict6 = DB::table('ireq_dtl as id')
            ->LEFTJOIN('ireq_mst as im','id.ireq_id','im.ireq_id')
            ->LEFTJOIN('divisi_refs as dr','im.ireq_divisi_user','dr.div_id')
            ->LEFTJOIN('lookup_refs as lr',function ($join) {
                $join->on('id.ireq_status','lr.lookup_code')
                      ->WHERERaw('LOWER(lr.lookup_type) LIKE ? ',[trim(strtolower('ict_status')).'%']);
            })
            ->LEFTJOIN('lookup_refs as lrs',function ($join) {
                $join->on('id.ireq_type','lrs.lookup_code')
                      ->WHERERaw('LOWER(lrs.lookup_type) LIKE ? ',[trim(strtolower('req_type')).'%']);
            })
            ->LEFTJOIN('lookup_refs as lrfs',function ($join) {
                $join->on('id.invent_code','lrfs.lookup_code')
                      ->WHERERaw('LOWER(lrfs.lookup_type) LIKE ? ',[trim(strtolower('kat_peripheral')).'%']);
            })
            ->SELECT('id.ireq_attachment','im.ireq_no','im.ireq_status as status','id.ireq_id','id.ireq_remark',DB::raw("COALESCE(id.ireq_assigned_to2,id.ireq_assigned_to1) AS ireq_assigned_to"),'id.ireqd_id',
            'lr.lookup_desc as ireq_status','lrs.lookup_desc as ireq_type','lrfs.lookup_desc as kategori','im.ireq_date','im.ireq_requestor','im.ireq_user',
            'dr.div_name','id.ireq_qty')
            ->WHERE('id.ireq_status','C')
            ->WHERE(function ($query){
                return $query
                ->WHERE('im.ireq_loc','OB')
                ->OrWhere('im.ireq_loc','FB');
            })
            ->ORDERBY('im.ireq_date','DESC')
            ->ORDERBY('id.ireqd_id','ASC')
            ->get();
            
            $ict7 = DB::table('ireq_mst as im')
            ->LEFTJOIN('divisi_refs as dr','im.ireq_divisi_user','dr.div_id')
            ->LEFTJOIN('lookup_refs as lr','im.ireq_status','lr.lookup_code')
            ->SELECT('im.ireq_id','im.ireq_status as status','im.ireq_assigned_to2','im.ireq_no','im.ireq_date','im.ireq_user','im.ireq_requestor','dr.div_name',DB::raw("COALESCE(im.ireq_assigned_to2,im.ireq_assigned_to1) AS ireq_assigned_to"),'lr.lookup_desc as ireq_status')
            ->WHERE(function($query){
                return $query
                ->WHERE('im.ireq_status','NT')
                ->Orwhere('im.ireq_status','RT');
            })
            ->WHERE(function ($query){
                return $query
                ->WHERE('im.ireq_loc','OB')
                ->OrWhere('im.ireq_loc','FB');
            })
            ->WHERERaw('LOWER(lr.lookup_type) LIKE ? ',[trim(strtolower('ict_status')).'%'])
            ->groupBy('im.ireq_id','im.ireq_status','im.ireq_assigned_to2','im.ireq_no','im.ireq_date','im.ireq_user','im.ireq_requestor','dr.div_name','im.creation_date','lr.lookup_desc',DB::raw("COALESCE(im.ireq_assigned_to2,im.ireq_assigned_to1)"))
            ->ORDERBY('im.ireq_date','DESC')
            ->get();

            $ict8 = DB::table('ireq_mst as im')
            ->LEFTJOIN('divisi_refs as dr','im.ireq_divisi_user','dr.div_id')
            ->LEFTJOIN('lookup_refs as lr','im.ireq_status','lr.lookup_code')
            ->SELECT('im.ireq_id','im.ireq_status as status','im.ireq_assigned_to2','im.ireq_no','im.ireq_date','im.ireq_user','im.ireq_requestor','dr.div_name',DB::raw("COALESCE(im.ireq_assigned_to2,im.ireq_assigned_to1) AS ireq_assigned_to"),'lr.lookup_desc as ireq_status')
            ->WHERENotNull('im.ireq_status')
            ->WHERE(function ($query){
                return $query
                ->WHERE('im.ireq_loc','OB')
                ->OrWhere('im.ireq_loc','FB');
            })
            ->WHERERaw('LOWER(lr.lookup_type) LIKE ? ',[trim(strtolower('ict_status')).'%'])
            ->groupBy('im.ireq_id','im.ireq_status','im.ireq_assigned_to2','im.ireq_no','im.ireq_date','im.ireq_user','im.ireq_requestor','dr.div_name','im.creation_date','lr.lookup_desc',DB::raw("COALESCE(im.ireq_assigned_to2,im.ireq_assigned_to1)"))
            ->ORDERBY('im.ireq_date','DESC')
            ->get();

            return json_encode(['lokasi'=>'bentu','ict'=>$ict,'ict1'=>$ict1,'ict2'=>$ict2,'ict3'=>$ict3,'ict4'=>$ict4,'ict5'=>$ict5,'ict6'=>$ict6,'ict7'=>$ict7,'ict8'=>$ict8],200);

    }
    function getDataReviewerKurau()
    {
        $ict = DB::table('ireq_mst as im')
            ->SELECT('im.ireq_id','im.ireq_status as status','im.ireq_no','im.ireq_date','im.ireq_user','im.ireq_requestor','dr.div_name', 
                    DB::raw('count(idd.ireq_assigned_to1) as ireq_count_status'), DB::raw('count(idd.ireq_id) as ireq_count_id'),
                    'lr.lookup_desc as ireq_status',DB::raw("COALESCE(im.ireq_assigned_to2,im.ireq_assigned_to1) AS ireq_assigned_to"))
            ->LEFTJOIN('divisi_refs as dr','im.ireq_divisi_user','dr.div_id')
            ->LEFTJOIN('ireq_dtl as idd','im.ireq_id','idd.ireq_id')
            ->LEFTJOIN('lookup_refs as lr','im.ireq_status','lr.lookup_code')
            ->WHERE('im.ireq_status','P')
            ->WHERE(function ($query){
                return $query
                ->WHERE('im.ireq_loc','OK')
                ->OrWhere('im.ireq_loc','FK');
            })
            ->WHERERaw('LOWER(lr.lookup_type) LIKE ? ',[trim(strtolower('ict_status')).'%'])
            ->groupBy('im.ireq_id','im.ireq_status','im.ireq_no','im.ireq_date','im.ireq_user','im.ireq_requestor','dr.div_name','im.creation_date','lr.lookup_desc',DB::raw("COALESCE(im.ireq_assigned_to2,im.ireq_assigned_to1)"))
            ->ORDERBY('im.ireq_date','DESC')
            ->get(); 

            $ict1 = DB::table('ireq_mst as im')
            ->SELECT('im.ireq_id','im.ireq_status as status','im.ireq_no','im.ireq_date','im.ireq_user','im.ireq_requestor','dr.div_name','lr.lookup_desc as ireq_status','im.ireq_status as status',
                    DB::raw('count(idd.ireq_assigned_to1) as ireq_count_status'), DB::raw('count(idd.ireq_id) as ireq_count_id'))
            ->LEFTJOIN('divisi_refs as dr','im.ireq_divisi_user','dr.div_id')
            ->LEFTJOIN('ireq_dtl as idd','im.ireq_id','idd.ireq_id')
            ->LEFTJOIN('lookup_refs as lr','im.ireq_status','lr.lookup_code')
            ->WHERE(function ($query){
                return $query
                ->WHERE('im.ireq_loc','OK')
                ->OrWhere('im.ireq_loc','FK');
            })
            ->WHERE(function ($query){
                return $query
                ->WHERE('im.ireq_status','NA1')
                ->orwhere('im.ireq_status','A1');
            })
            ->WHERERaw('LOWER(lr.lookup_type) LIKE ? ',[trim(strtolower('ict_status')).'%'])
            ->groupBy('im.ireq_id','im.ireq_status','im.ireq_no','im.ireq_date','im.ireq_user','im.ireq_requestor','dr.div_name','im.creation_date','lr.lookup_desc','im.ireq_status')
            ->ORDERBY('im.ireq_date','DESC')
            ->get(); 

            $ict2 = DB::table('ireq_mst as im')
            ->SELECT('im.ireq_id','im.ireq_status as status','im.ireq_no','im.ireq_date','im.ireq_user','im.ireq_requestor','dr.div_name','lr.lookup_desc as ireq_status','im.ireq_status as status',
                    DB::raw('count(idd.ireq_assigned_to1) as ireq_count_status'), DB::raw('count(idd.ireq_id) as ireq_count_id'))
            ->LEFTJOIN('divisi_refs as dr','im.ireq_divisi_user','dr.div_id')
            ->LEFTJOIN('ireq_dtl as idd','im.ireq_id','idd.ireq_id')
            ->LEFTJOIN('lookup_refs as lr','im.ireq_status','lr.lookup_code')
            ->WHERE(function ($query){
                return $query
                ->WHERE('im.ireq_loc','OK')
                ->OrWhere('im.ireq_loc','FK');
            })
            ->WHERE(function ($query){
                return $query
                ->WHERE('im.ireq_status','NA2')
                ->orwhere('im.ireq_status','A2');
            })
            
            ->WHERERaw('LOWER(lr.lookup_type) LIKE ? ',[trim(strtolower('ict_status')).'%'])
            ->groupBy('im.ireq_id','im.ireq_status','im.ireq_no','im.ireq_date','im.ireq_user','im.ireq_requestor','dr.div_name','im.creation_date','lr.lookup_desc','im.ireq_status')
            ->ORDERBY('im.ireq_date','DESC')
            ->get(); 

            $ict3 = DB::table('ireq_mst as im')
            ->SELECT('im.ireq_id','im.ireq_status as status','im.ireq_no','im.ireq_date','im.ireq_user','im.ireq_requestor','dr.div_name','im.ireq_reason','lr.lookup_desc as ireq_status',
                    DB::raw('count(idd.ireq_assigned_to1) as ireq_count_status'), DB::raw('count(idd.ireq_id) as ireq_count_id'))
            ->LEFTJOIN('divisi_refs as dr','im.ireq_divisi_user','dr.div_id')
            ->LEFTJOIN('ireq_dtl as idd','im.ireq_id','idd.ireq_id')
            ->LEFTJOIN('lookup_refs as lr','im.ireq_status','lr.lookup_code')
            ->WHERERaw('LOWER(lr.lookup_type) LIKE ? ',[trim(strtolower('ict_status')).'%'])
            ->WHERE(function ($query){
                return $query
                ->WHERE('im.ireq_loc','OK')
                ->OrWhere('im.ireq_loc','FK');
            })
            ->WHERE(function ($query){
                return $query
                ->WHERE('im.ireq_status','RR')
                ->OrWhere('im.ireq_status','RA1')
                ->OrWhere('im.ireq_status','RA2');
            })
            ->groupBy('im.ireq_id','im.ireq_no','im.ireq_status','im.ireq_date','im.ireq_user','im.ireq_requestor','dr.div_name','im.creation_date','lr.lookup_desc','im.ireq_reason')
            ->ORDERBY('im.ireq_date','DESC')
            ->get(); 

            $ict4 = DB::table('ireq_mst as im')
            ->SELECT('im.ireq_id','im.ireq_status as status','im.ireq_no','im.ireq_date','im.ireq_user','im.ireq_requestor','dr.div_name',DB::raw("COALESCE(im.ireq_assigned_to2,im.ireq_assigned_to1) AS ireq_assigned_to"),'lr.lookup_desc as ireq_status')
            ->LEFTJOIN('divisi_refs as dr','im.ireq_divisi_user','dr.div_id')
            ->LEFTJOIN('lookup_refs as lr','im.ireq_status','lr.lookup_code')
            ->WHERE('im.ireq_status','T')
            ->WHERE(function ($query){
                return $query
                ->WHERE('im.ireq_loc','OK')
                ->OrWhere('im.ireq_loc','FK');
            })
            ->WHERERaw('LOWER(lr.lookup_type) LIKE ? ',[trim(strtolower('ict_status')).'%'])
            ->groupBy('im.ireq_id','im.ireq_status','im.ireq_no','im.ireq_date','im.ireq_user','im.ireq_requestor','dr.div_name','im.creation_date','lr.lookup_desc',DB::raw("COALESCE(im.ireq_assigned_to2,im.ireq_assigned_to1)"))
            ->ORDERBY('im.ireq_date','DESC')
            ->get();

            $ict5 = DB::table('ireq_dtl as id')
            ->LEFTJOIN('ireq_mst as im','id.ireq_id','im.ireq_id')
            ->LEFTJOIN('divisi_refs as dr','im.ireq_divisi_user','dr.div_id')
            ->LEFTJOIN('lookup_refs as lr',function ($join) {
                $join->on('id.ireq_status','lr.lookup_code')
                      ->WHERERaw('LOWER(lr.lookup_type) LIKE ? ',[trim(strtolower('ict_status')).'%']);
            })
            ->LEFTJOIN('lookup_refs as lrs',function ($join) {
                $join->on('id.ireq_type','lrs.lookup_code')
                      ->WHERERaw('LOWER(lrs.lookup_type) LIKE ? ',[trim(strtolower('req_type')).'%']);
            })
            ->LEFTJOIN('lookup_refs as lrfs',function ($join) {
                $join->on('id.invent_code','lrfs.lookup_code')
                      ->WHERERaw('LOWER(lrfs.lookup_type) LIKE ? ',[trim(strtolower('kat_peripheral')).'%']);
            })
            ->SELECT('id.ireq_attachment','im.ireq_no','id.ireq_id','id.ireq_remark',DB::raw("COALESCE(id.ireq_assigned_to2,id.ireq_assigned_to1) AS ireq_assigned_to"),'id.ireqd_id',
            'lr.lookup_desc as ireq_status','lrs.lookup_desc as ireq_type','lrfs.lookup_desc as kategori','im.ireq_date','im.ireq_requestor','im.ireq_user',
            'dr.div_name','id.ireq_qty','id.ireq_status as status')
            ->WHERE('id.ireq_status','D')
            ->WHERE(function ($query){
                return $query
                ->WHERE('im.ireq_loc','OK')
                ->OrWhere('im.ireq_loc','FK');
            })
            ->ORDERBY('im.ireq_date','DESC')
            ->ORDERBY('id.ireqd_id','ASC')
            ->get();

            $ict6 = DB::table('ireq_dtl as id')
            ->LEFTJOIN('ireq_mst as im','id.ireq_id','im.ireq_id')
            ->LEFTJOIN('divisi_refs as dr','im.ireq_divisi_user','dr.div_id')
            ->LEFTJOIN('lookup_refs as lr',function ($join) {
                $join->on('id.ireq_status','lr.lookup_code')
                      ->WHERERaw('LOWER(lr.lookup_type) LIKE ? ',[trim(strtolower('ict_status')).'%']);
            })
            ->LEFTJOIN('lookup_refs as lrs',function ($join) {
                $join->on('id.ireq_type','lrs.lookup_code')
                      ->WHERERaw('LOWER(lrs.lookup_type) LIKE ? ',[trim(strtolower('req_type')).'%']);
            })
            ->LEFTJOIN('lookup_refs as lrfs',function ($join) {
                $join->on('id.invent_code','lrfs.lookup_code')
                      ->WHERERaw('LOWER(lrfs.lookup_type) LIKE ? ',[trim(strtolower('kat_peripheral')).'%']);
            })
            ->SELECT('id.ireq_attachment','im.ireq_no','im.ireq_status as status','id.ireq_id','id.ireq_remark',DB::raw("COALESCE(id.ireq_assigned_to2,id.ireq_assigned_to1) AS ireq_assigned_to"),'id.ireqd_id',
            'lr.lookup_desc as ireq_status','lrs.lookup_desc as ireq_type','lrfs.lookup_desc as kategori','im.ireq_date','im.ireq_requestor','im.ireq_user',
            'dr.div_name','id.ireq_qty')
            ->WHERE('id.ireq_status','C')
            ->WHERE(function ($query){
                return $query
                ->WHERE('im.ireq_loc','OK')
                ->OrWhere('im.ireq_loc','FK');
            })
            ->ORDERBY('im.ireq_date','DESC')
            ->ORDERBY('id.ireqd_id','ASC')
            ->get();
            
            $ict7 = DB::table('ireq_mst as im')
            ->LEFTJOIN('divisi_refs as dr','im.ireq_divisi_user','dr.div_id')
            ->LEFTJOIN('lookup_refs as lr','im.ireq_status','lr.lookup_code')
            ->SELECT('im.ireq_id','im.ireq_status as status','im.ireq_assigned_to2','im.ireq_no','im.ireq_date','im.ireq_user','im.ireq_requestor','dr.div_name',DB::raw("COALESCE(im.ireq_assigned_to2,im.ireq_assigned_to1) AS ireq_assigned_to"),'lr.lookup_desc as ireq_status')
            ->WHERE(function($query){
                return $query
                ->WHERE('im.ireq_status','NT')
                ->Orwhere('im.ireq_status','RT');
            })
            ->WHERE(function ($query){
                return $query
                ->WHERE('im.ireq_loc','OK')
                ->OrWhere('im.ireq_loc','FK');
            })
            ->WHERERaw('LOWER(lr.lookup_type) LIKE ? ',[trim(strtolower('ict_status')).'%'])
            ->groupBy('im.ireq_id','im.ireq_status','im.ireq_assigned_to2','im.ireq_no','im.ireq_date','im.ireq_user','im.ireq_requestor','dr.div_name','im.creation_date','lr.lookup_desc',DB::raw("COALESCE(im.ireq_assigned_to2,im.ireq_assigned_to1)"))
            ->ORDERBY('im.ireq_date','DESC')
            ->get();

            $ict8 = DB::table('ireq_mst as im')
            ->LEFTJOIN('divisi_refs as dr','im.ireq_divisi_user','dr.div_id')
            ->LEFTJOIN('lookup_refs as lr','im.ireq_status','lr.lookup_code')
            ->SELECT('im.ireq_id','im.ireq_status as status','im.ireq_assigned_to2','im.ireq_no','im.ireq_date','im.ireq_user','im.ireq_requestor','dr.div_name',DB::raw("COALESCE(im.ireq_assigned_to2,im.ireq_assigned_to1) AS ireq_assigned_to"),'lr.lookup_desc as ireq_status')
            ->WHERENotNull('im.ireq_status')
            ->WHERE(function ($query){
                return $query
                ->WHERE('im.ireq_loc','OK')
                ->OrWhere('im.ireq_loc','FK');
            })
            ->WHERERaw('LOWER(lr.lookup_type) LIKE ? ',[trim(strtolower('ict_status')).'%'])
            ->groupBy('im.ireq_id','im.ireq_status','im.ireq_assigned_to2','im.ireq_no','im.ireq_date','im.ireq_user','im.ireq_requestor','dr.div_name','im.creation_date','lr.lookup_desc',DB::raw("COALESCE(im.ireq_assigned_to2,im.ireq_assigned_to1)"))
            ->ORDERBY('im.ireq_date','DESC')
            ->get();

            return json_encode(['lokasi'=>'kurau','ict'=>$ict,'ict1'=>$ict1,'ict2'=>$ict2,'ict3'=>$ict3,'ict4'=>$ict4,'ict5'=>$ict5,'ict6'=>$ict6,'ict7'=>$ict7,'ict8'=>$ict8],200);

    }
    function detailRequestReviewer($ireq_id){
        $dtl = DB::table('ireq_dtl as id')
        ->SELECT(DB::raw("COALESCE(id.ireq_assigned_to2,id.ireq_assigned_to1) AS ireq_assigned_to"),
         'id.ireq_id','id.ireq_assigned_to1_reason','id.ireq_assigned_to1','im.ireq_no',
         'id.ireq_assigned_to2','id.ireqd_id','lr.lookup_desc as ireq_type','id.ireq_remark',
         'id.ireq_desc', 'id.ireq_qty','lrs.lookup_desc as kategori','llr.lookup_desc as ireq_status','id.ireq_status as cekStatus')
        ->LEFTJOIN('lookup_refs as lrs',function ($join) {
            $join->on('id.invent_code','lrs.lookup_code')
                  ->WHERERaw('LOWER(lrs.lookup_type) LIKE ? ',[trim(strtolower('kat_peripheral')).'%']);
        })
        ->LEFTJOIN('ireq_mst as im','id.ireq_id','im.ireq_id')
        ->LEFTJOIN('lookup_refs as lr','id.ireq_type','lr.lookup_code')
        ->LEFTJOIN('lookup_refs as llr',function ($join) {
            $join->on('id.ireq_status','llr.lookup_code')
                  ->WHERERaw('LOWER(llr.lookup_type) LIKE ? ',[trim(strtolower('ict_status')).'%']);
        })
        ->WHERE('id.ireq_id',$ireq_id)
        ->WHERERaw('LOWER(lr.lookup_type) LIKE ? ',[trim(strtolower('req_type')).'%'])
        ->ORDERBY('id.ireqd_id','ASC')
        ->get();
            return response()->json($dtl);
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

        $dtl = DB::table('ireq_dtl')
        ->WHERE('ireq_id',$code)
        ->update([
            'ireq_status' => 'RR',
            'ireq_reason' => $request->ket,
            'last_update_date' => $this->newUpdate,
            'last_updated_by' => Auth::user()->usr_name,
            'program_name' => "IctController_rejectReviewer",
        ]);
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

        $dtl = DB::table('ireq_dtl')
        ->WHERE('ireq_id',$ireq_id)
        ->update([
            'ireq_status' => 'NA1',
            'last_update_date' => $this->newUpdate,
            'last_updated_by' => Auth::user()->usr_name,
            'program_name' => "IctController_needApprovalAtasan",
        ]);
        $emailVerifikator = DB::table('divisi_refs as dr')
            ->rightjoin('mng_users as mu','dr.div_verificator','mu.usr_email')
            ->SELECT('mu.usr_email','mu.usr_id')
            ->WHERE('dr.div_id',$divisiPengguna)
            ->first();
        $ict = DB::table('ireq_dtl as id')
            ->LEFTJOIN('ireq_mst as im','id.ireq_id','im.ireq_id')
            ->LEFTJOIN('lookup_refs as lrs',function ($join) {
                $join->on('id.invent_code','lrs.lookup_code')
                    ->WHERERaw('LOWER(lrs.lookup_type) LIKE ? ',[trim(strtolower('kat_peripheral')).'%']);
            })
            ->LEFTJOIN('lookup_refs as lrfs',function ($join) {
                $join->on('id.ireq_type','lrfs.lookup_code')
                     ->WHERERaw('LOWER(lrfs.lookup_type) LIKE ? ',[trim(strtolower('req_type')).'%']);
            })
            ->LEFTJOIN('vcompany_refs as vr',function ($join) {
                $join->on('im.ireq_bu','vr.company_code');
            })
            ->LEFTJOIN('divisi_refs as dr','im.ireq_divisi_user','dr.div_id')
            ->LEFTJOIN('mng_users as mu','dr.div_verificator','mu.usr_email')
            ->LEFTJOIN('location_refs as loc','im.ireq_loc','loc.loc_code')
            ->SELECT('loc.loc_email','mu.usr_fullname','im.ireq_no','id.ireqd_id','vr.name as ireq_bu','im.ireq_id','dr.div_name', 'mu.usr_name',DB::raw("TO_CHAR(im.ireq_date, 'dd Mon YYYY HH24:MM') as ireq_date"),'im.ireq_requestor',
                    'im.ireq_user','lrs.lookup_desc as invent_code','id.ireq_qty','lrfs.lookup_desc as ireq_type','id.ireq_remark')
            ->WHERE('im.ireq_id',$ireq_id)
            ->ORDERBY('id.ireqd_id','ASC')
            ->get();
        $ICT = DB::table('ireq_dtl as id')
            ->LEFTJOIN('ireq_mst as im','id.ireq_id','im.ireq_id')
            ->LEFTJOIN('lookup_refs as lrs',function ($join) {
                $join->on('id.invent_code','lrs.lookup_code')
                    ->WHERERaw('LOWER(lrs.lookup_type) LIKE ? ',[trim(strtolower('kat_peripheral')).'%']);
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
            ->SELECT('loc.loc_email','mu.usr_fullname','mu.usr_email','im.ireq_no','id.ireqd_id','vr.name as ireq_bu','im.ireq_id','dr.div_name', 'mu.usr_name',DB::raw("TO_CHAR(im.ireq_date, 'dd Mon YYYY HH24:MM') as ireq_date"),'im.ireq_requestor',
                    'im.ireq_user','lrs.lookup_desc as invent_code','id.ireq_qty','lrfs.lookup_desc as ireq_type','id.ireq_remark')
            ->WHERE('im.ireq_id',$ireq_id)
            ->ORDERBY('id.ireqd_id','ASC')
            ->get();

        $Date = $this->date->addDays(1);
        $link = Link::create([
            'link_id'=> md5($this->date),
            'link_action'=> 'Ict Request Verifikasi From Email',
            'expired_at'=>Carbon::parse($Date)->copy()->tz('Asia/Jakarta')->format('Y-m-d H:i:s'),
            'usr_id'=>$emailVerifikator->usr_id,
            'ireq_id'=>$ireq_id
        ]);
        
        $LINK = Link::where('ireq_id',$ireq_id)->WHERE('usr_id',$emailVerifikator->usr_id)->first();
        // $send_mail = $emailVerifikator->usr_email .= '@emp.id';
        $email_address = $ICT[0]->usr_email .= '@emp.id';
        $send_mail = 'adhitya.saputro@emp.id';
        SendNotifApproval::dispatchAfterResponse($send_mail,$ict,$LINK);
        SendNotifWaitingApprovalFromHigherLevel::dispatchAfterResponse($email_address,$ICT);
        return response()->json('success send notification');
    }
    function needApprovalManager($ireq_id)
    {
        $ICT = Ict::where('ireq_id',$ireq_id)->first();
        $ICT->ireq_status = 'NA2';
        $ICT->ireq_verificator = Auth::user()->usr_name;
        $ICT->last_update_date = $this->newUpdate;
        $ICT->last_updated_by = Auth::user()->usr_name;
        $ICT->program_name = "IctController_needApprovalManager";
        $ICT->save();

        $dtl = DB::table('ireq_dtl')
        ->WHERE('ireq_id',$ireq_id)
        ->update([
            'ireq_status' => 'NA2',
            'last_update_date' => $this->newUpdate,
            'last_updated_by' => Auth::user()->usr_name,
            'program_name' => "IctController_needApprovalManager",
        ]);
        $emailVerifikator = DB::table('mng_users')
            ->SELECT('usr_id')
            ->WHERE('usr_email','arifin.tahir')
            ->first();
        $ict = DB::table('ireq_dtl as id')
            ->LEFTJOIN('ireq_mst as im','id.ireq_id','im.ireq_id')
            ->LEFTJOIN('lookup_refs as lrs',function ($join) {
                $join->on('id.invent_code','lrs.lookup_code')
                    ->WHERERaw('LOWER(lrs.lookup_type) LIKE ? ',[trim(strtolower('kat_peripheral')).'%']);
            })
            ->LEFTJOIN('lookup_refs as lrfs',function ($join) {
                $join->on('id.ireq_type','lrfs.lookup_code')
                     ->WHERERaw('LOWER(lrfs.lookup_type) LIKE ? ',[trim(strtolower('req_type')).'%']);
            })
            ->LEFTJOIN('vcompany_refs as vr',function ($join) {
                $join->on('im.ireq_bu','vr.company_code');
            })
            ->LEFTJOIN('divisi_refs as dr','im.ireq_divisi_user','dr.div_id')
            ->LEFTJOIN('mng_users as mu','dr.div_verificator','mu.usr_email')
            ->SELECT('im.ireq_no','id.ireqd_id','vr.name as ireq_bu','im.ireq_id','dr.div_name', 'mu.usr_name',DB::raw("TO_CHAR(im.ireq_date, 'dd Mon YYYY HH24:MM') as ireq_date"),'im.ireq_requestor',
                    'im.ireq_user','lrs.lookup_desc as invent_code','id.ireq_qty','lrfs.lookup_desc as ireq_type','id.ireq_remark')
            ->WHERE('im.ireq_id',$ireq_id)
            ->ORDERBY('id.ireqd_id','ASC')
            ->get();
        $ICT = DB::table('ireq_dtl as id')
            ->LEFTJOIN('ireq_mst as im','id.ireq_id','im.ireq_id')
            ->LEFTJOIN('lookup_refs as lrs',function ($join) {
                $join->on('id.invent_code','lrs.lookup_code')
                    ->WHERERaw('LOWER(lrs.lookup_type) LIKE ? ',[trim(strtolower('kat_peripheral')).'%']);
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
            ->SELECT('loc.loc_email','mu.usr_fullname','mu.usr_email','im.ireq_no','id.ireqd_id','vr.name as ireq_bu','im.ireq_id','dr.div_name', 'mu.usr_name',DB::raw("TO_CHAR(im.ireq_date, 'dd Mon YYYY HH24:MM') as ireq_date"),'im.ireq_requestor',
                    'im.ireq_user','lrs.lookup_desc as invent_code','id.ireq_qty','lrfs.lookup_desc as ireq_type','id.ireq_remark')
            ->WHERE('im.ireq_id',$ireq_id)
            ->ORDERBY('id.ireqd_id','ASC')
            ->get();
        $Date = $this->date->addDays(1);
        $link = Link::create([
            'link_id'=> md5($this->date),
            'link_action'=> 'Ict Request Verifikasi From Email ICT Manager',
            'expired_at'=> Carbon::parse($Date)->copy()->tz('Asia/Jakarta')->format('Y-m-d H:i:s'),
            'usr_id'=>$emailVerifikator->usr_id,
            'ireq_id'=>$ireq_id
        ]);
        $email_address = $ICT[0]->usr_email .= '@emp.id';
        $LINK = Link::where('ireq_id',$ireq_id)->where('usr_id',$emailVerifikator->usr_id)->first();
        $send_mail = env('APP_MAIL_ICT_MANAGER');
        SendNotifIctManager::dispatchAfterResponse($send_mail,$ict,$LINK);
        SendNotifWaitingApprovalFromIctManager::dispatchAfterResponse($email_address,$ICT);
        return response()->json('Success send notification');
    }
    function asignPerRequestReviewer(Request $request)
    {
        $ict = Ict::where('ireq_id',$request->id)->first();
        if($ict->ireq_status == 'RT'){
            $ict->ireq_assigned_to2 = $request->name;
            $ict->last_updated_by = Auth::user()->usr_name;
            $ict->last_update_date = $this->newUpdate;
            $ict->program_name = "IctController_asignPerRequestReviewer";
            $ict->save();

            $dtl = DB::table('ireq_dtl')
            ->WHERE('ireq_id',$request->id)
            ->update([
                'ireq_assigned_to2' =>$request->name,
                'last_updated_by' => Auth::user()->usr_name,
                'last_update_date' => $this->newUpdate,
                'program_name' => "IctController_asignPerRequestReviewer"
            ]);
        return response()->json('Success Update');
        }
        else{
            $ict->ireq_assigned_to1 = $request->name;
            $ict->last_updated_by = Auth::user()->usr_name;
            $ict->last_update_date = $this->newUpdate;
            $ict->program_name = "IctController_asignPerRequestReviewer";
            $ict->save();

            $detail = DB::table('ireq_dtl')
            ->WHERE('ireq_id',$request->id)
            ->update([
                'ireq_assigned_to1'=>$request->name,
                'last_update_date'=>$this->newUpdate,
                'last_updated_by'=>Auth::user()->usr_name,
                'program_name'=>"IctController_asignPerRequestReviewer"
            ]);
            
        return response()->json('Success Update');
        }
    }
    function submitAssignPerRequest($ireq_id)
    {
        $ict = Ict::where('ireq_id',$ireq_id)->first();
        $name = [];
        $email = [];
        if($ict->ireq_status == 'RT'){

            $ict->ireq_status = 'T';
            $ict->ireq_verificator = Auth::user()->usr_name;
            $ict->ireq_assigned_date = $this->newUpdate;
            $ict->last_update_date = $this->newUpdate;    
            $ict->last_updated_by = Auth::user()->usr_name;
            $ict->program_name = "IctController_submitAssignPerRequest";
            $ict->save();
            
            $dtl = DB::table('ireq_dtl')
            ->WHERE('ireq_id',$ireq_id)
            ->update([
                'ireq_status' => 'T',
                'ireq_assigned_date' => $this->newUpdate,
                'last_update_date' => $this->newUpdate,
                'last_updated_by' => Auth::user()->usr_name,
                'program_name' => "IctController_submitAssignPerRequest"
            ]);
            $dtll = IctDetail::where('ireq_id',$ireq_id)->get();
            foreach ($dtll as $d) {
                array_push($name, $d->ireq_assigned_to2);
            }
            $usr_email = DB::table('mng_users')->SELECT('usr_email')->WHEREIn('usr_fullname',$name)->pluck('usr_email');
            foreach($usr_email as $s){
                $emailpush = $s .= '@emp.id';
                array_push($email,$emailpush);
            }
            SendNotifPersonnel::dispatchAfterResponse($email,$ict);
        }else{
            $ict->ireq_status = 'NT';
            $ict->ireq_assigned_date = $this->newUpdate;
            $ict->ireq_verificator = Auth::user()->usr_name;
            $ict->last_update_date = $this->newUpdate;    
            $ict->last_updated_by = Auth::user()->usr_name;
            $ict->program_name = "IctController_submitAssignPerRequest";
            $ict->save();
            
            $dtl = DB::table('ireq_dtl')
            ->WHERE('ireq_id',$ireq_id)
            ->update([
                'ireq_status' => 'NT',
                'ireq_assigned_date' => $this->newUpdate,
                'last_update_date' => $this->newUpdate,
                'last_updated_by' => Auth::user()->usr_name,
                'program_name' => "IctController_submitAssignPerRequest"
            ]);
            $dtll = IctDetail::where('ireq_id',$ireq_id)->get();
            foreach ($dtll as $d) {
                array_push($name, $d->ireq_assigned_to1);
            }
            $usr_email = DB::table('mng_users')->SELECT('usr_email')->WHEREIn('usr_fullname',$name)->pluck('usr_email');
            foreach($usr_email as $s){
                $emailpush = $s .= '@emp.id';
                array_push($email,$emailpush);
            }
            SendNotifPersonnel::dispatchAfterResponse($email,$ict);
        }
        return response()->json('success');

    }
    function getIctAdmin()
    {
        $idrole = Mng_usr_roles::select('rol_id')->WHERE('usr_id',Auth::user()->usr_id)->pluck('rol_id');
        $role = Mng_roles::select('rol_name')->whereIn('rol_id',$idrole)->pluck('rol_name');
        if($role->contains('Admin')){
            $ict = DB::table('ireq_mst as im')
            ->LEFTJOIN('ireq_dtl as idm','im.ireq_id','idm.ireq_id')
            ->LEFTJOIN('divisi_refs as dr','im.ireq_divisi_user','dr.div_id')
            ->LEFTJOIN('lookup_refs as lr','im.ireq_status','lr.lookup_code')
            ->SELECT('im.ireq_id','im.ireq_no','im.ireq_date','dr.div_name',
            'im.ireq_user','dr.div_name', 'lr.lookup_desc as ireq_status','im.ireq_status as status','im.ireq_requestor')
            ->WHERERaw('LOWER(lr.lookup_type) LIKE ? ',[trim(strtolower('ict_status')).'%'])
            ->WHERE(function($query){
                return $query
                ->WHERE('im.ireq_status','P')
                ->OrWhere('im.ireq_status','NA1')
                ->OrWhere('im.ireq_status','NA2');
                })
            ->groupBy('im.ireq_id','im.ireq_status','im.ireq_no','im.ireq_date','im.ireq_requestor','im.ireq_user','im.creation_date','dr.div_name','lr.lookup_desc')
            ->ORDERBY('im.ireq_date','DESC')
            ->get();

            $ict1 = DB::table('ireq_mst as im')
            ->LEFTJOIN('divisi_refs as dr','im.ireq_divisi_user','dr.div_id')
            ->LEFTJOIN('lookup_refs as lr','im.ireq_status','lr.lookup_code')
            ->SELECT('im.ireq_id','im.ireq_no','im.ireq_date','im.ireq_user','im.ireq_status as status','dr.div_name','lr.lookup_desc as ireq_status','im.ireq_requestor')
            ->WHERERaw('LOWER(lr.lookup_type) LIKE ? ',[trim(strtolower('ict_status')).'%'])
            ->WHERE(function($query){
                return $query
                ->WHERE('im.ireq_status','A1')
                ->Orwhere('im.ireq_status','A2');
            })
            ->ORDERBY('im.ireq_date','DESC')
            ->get();

            $ict2 = DB::table('ireq_mst as im')
            ->LEFTJOIN('lookup_refs as lr','im.ireq_status','lr.lookup_code')
            ->LEFTJOIN('divisi_refs as dr','im.ireq_divisi_user','dr.div_id')
            ->SELECT('dr.div_name','im.ireq_id','im.ireq_no','im.ireq_date','im.ireq_user','im.ireq_status as status','im.ireq_reason','lr.lookup_desc as ireq_status','im.ireq_requestor')
            ->WHERERAW('LOWER(lr.lookup_type) LIKE ? ',[trim(strtolower('ict_status')).'%'])
            ->WHERE(function($query){
                return $query
                ->WHERE('im.ireq_status','RR')
                ->Orwhere('im.ireq_status','RA1')
                ->Orwhere('im.ireq_status','RA2');
            })
            ->ORDERBY('im.ireq_date','DESC')
            ->get();

            $ict3 = DB::table('ireq_mst as im')
            ->LEFTJOIN('lookup_refs as lr','im.ireq_status','lr.lookup_code')
            ->SELECT('im.ireq_requestor','im.ireq_id','im.ireq_no','im.ireq_date','im.ireq_status as status','im.ireq_user',DB::raw("COALESCE(im.ireq_assigned_to2,im.ireq_assigned_to1) AS ireq_assigned_to"),'lr.lookup_desc as ireq_status')
            ->WHERE('im.ireq_status','T')
            ->WHERERaw('LOWER(lr.lookup_type) LIKE ? ',[trim(strtolower('ict_status')).'%'])
            ->ORDERBY('im.ireq_date','DESC')
            ->get();

            $ict4 =  DB::table('ireq_dtl as id')
            ->LEFTJOIN('ireq_mst as im','id.ireq_id','im.ireq_id')
            ->LEFTJOIN('divisi_refs as dr','im.ireq_divisi_user','dr.div_id')
            ->LEFTJOIN('lookup_refs as lr',function ($join) {
                $join->on('id.ireq_status','lr.lookup_code')
                    ->WHERERaw('LOWER(lr.lookup_type) LIKE ? ',[trim(strtolower('ict_status')).'%']);
            })
            ->LEFTJOIN('lookup_refs as lrs',function ($join) {
                $join->on('id.ireq_type','lrs.lookup_code')
                    ->WHERERaw('LOWER(lrs.lookup_type) LIKE ? ',[trim(strtolower('req_type')).'%']);
            })
            ->LEFTJOIN('lookup_refs as lrfs',function ($join) {
                $join->on('id.invent_code','lrfs.lookup_code')
                    ->WHERERaw('LOWER(lrfs.lookup_type) LIKE ? ',[trim(strtolower('kat_peripheral')).'%']);
            })
            ->SELECT('id.ireq_attachment','im.ireq_no','id.ireq_id','id.ireq_remark',DB::raw("COALESCE(id.ireq_assigned_to2,id.ireq_assigned_to1) AS ireq_assigned_to"),'id.ireqd_id',
            'lr.lookup_desc as ireq_status','lrs.lookup_desc as ireq_type','lrfs.lookup_desc as kategori','im.ireq_date','im.ireq_requestor','im.ireq_user',
            'dr.div_name','id.ireq_qty','id.ireq_status as status')
            ->WHERE('id.ireq_status','D')
            ->ORDERBY('im.ireq_date','DESC')
            ->ORDERBY('id.ireqd_id','ASC')
            ->get();
            $ict5 = DB::table('ireq_dtl as id')
            ->LEFTJOIN('ireq_mst as im','id.ireq_id','im.ireq_id')
            ->LEFTJOIN('divisi_refs as dr','im.ireq_divisi_user','dr.div_id')
            ->LEFTJOIN('lookup_refs as lr',function ($join) {
                $join->on('id.ireq_status','lr.lookup_code')
                    ->WHERERaw('LOWER(lr.lookup_type) LIKE ? ',[trim(strtolower('ict_status')).'%']);
            })
            ->LEFTJOIN('lookup_refs as lrs',function ($join) {
                $join->on('id.ireq_type','lrs.lookup_code')
                    ->WHERERaw('LOWER(lrs.lookup_type) LIKE ? ',[trim(strtolower('req_type')).'%']);
            })
            ->LEFTJOIN('lookup_refs as lrfs',function ($join) {
                $join->on('id.invent_code','lrfs.lookup_code')
                    ->WHERERaw('LOWER(lrfs.lookup_type) LIKE ? ',[trim(strtolower('kat_peripheral')).'%']);
            })
            ->SELECT('id.ireq_attachment','id.ireq_value','im.ireq_no','id.ireq_id','id.ireq_remark',DB::raw("COALESCE(id.ireq_assigned_to2,id.ireq_assigned_to1) AS ireq_assigned_to"),'id.ireqd_id',
            'lr.lookup_desc as ireq_status','lrs.lookup_desc as ireq_type','lrfs.lookup_desc as kategori','im.ireq_date','im.ireq_requestor','im.ireq_user',
            'dr.div_name','id.ireq_qty','id.ireq_status as status')
            ->WHERE('id.ireq_status','C')
            ->ORDERBY('im.ireq_date','DESC')
            ->ORDERBY('id.ireqd_id','ASC')
            ->get();
            $ict6 = DB::table('ireq_mst as id')
            ->LEFTJOIN('lookup_refs as lr','id.ireq_status','lr.lookup_code')
            ->SELECT('id.ireq_id','id.ireq_no','id.ireq_user','id.ireq_date','lr.lookup_desc as ireq_status','id.ireq_status as status','id.ireq_requestor')
            ->WHERERaw('LOWER(lr.lookup_type) LIKE ? ',[trim(strtolower('ict_status')).'%'])
            ->WHERENotNull('id.ireq_status')
            ->ORDERBY('id.ireq_date','DESC')
            ->get();

            return response()->json(['ict'=>$ict,'ict1'=>$ict1,'ict2'=>$ict2,'ict3'=>$ict3,'ict4'=>$ict4,'ict5'=>$ict5,'ict6'=>$ict6],200);
        }
    }
    function getIct()
    {
        $usr_name = Auth::user()->usr_name;
        $role = Mng_usr_roles::select('rol_id')->WHERE('usr_id',Auth::user()->usr_id)->pluck('rol_id');
        $menu = Mng_role_menu::select('menu_id')->WHEREIn('rol_id',$role)->pluck('menu_id');
        $aksesmenu = DB::table('mng_menus')->SELECT('controller')->WHEREIn('menu_id',$menu)->pluck('controller');
        if($aksesmenu->contains($this->requestor)){
            $ict = DB::table('ireq_mst as id')
            ->LEFTJOIN('ireq_dtl as idm','id.ireq_id','idm.ireq_id')
            ->LEFTJOIN('lookup_refs as lr','id.ireq_status','lr.lookup_code')
            ->LEFTJOIN('lookup_refs as llr',function ($join) {
                $join->on('id.ireq_status','llr.lookup_code')
                    ->WHERERaw('LOWER(llr.lookup_type) LIKE ? ',[trim(strtolower('ict_status')).'%']);
            })
            ->LEFTJOIN('divisi_refs as dr','id.ireq_divisi_user','dr.div_id')
            ->SELECT('id.ireq_id','id.ireq_status as status','id.ireq_no','id.ireq_date','id.ireq_user','dr.div_name','id.ireq_requestor',
                    DB::raw('count(DISTINCT(idm.ireq_id)) as count'),'llr.lookup_desc as ireq_status')
            ->WHERE('id.created_by',$usr_name)
            ->WHERE(function($query){
                return $query
                ->WHERENull('id.ireq_status')
                ->orWhere('id.ireq_status','P');
                })
            ->groupBy('llr.lookup_desc','id.ireq_status','id.ireq_id','id.ireq_no','id.ireq_date','id.ireq_user','id.creation_date','dr.div_name','id.ireq_status','id.ireq_requestor')
            ->ORDERBY('id.ireq_date','DESC')
            ->get();

            $ict1 = DB::table('ireq_mst as im')
            ->LEFTJOIN('lookup_refs as lr','im.ireq_status','lr.lookup_code')
            ->LEFTJOIN('divisi_refs as dr','im.ireq_divisi_user','dr.div_id')
            ->SELECT('im.ireq_id','im.ireq_status as status','dr.div_name','im.ireq_no','im.ireq_date','im.ireq_requestor','im.ireq_user','lr.lookup_desc as ireq_status')
            ->WHERE('im.created_by',$usr_name)
            ->WHERERaw('LOWER(lr.lookup_type) LIKE ? ',[trim(strtolower('ict_status')).'%'])
            ->WHERE(function($query){
                return $query
                ->WHERE('im.ireq_status','A1')
                ->orWhere('im.ireq_status','A2');
            })            
            ->ORDERBY('im.ireq_date','DESC')

            ->get();

            $ict2 = DB::table('ireq_mst as im')
            ->LEFTJOIN('lookup_refs as lr','im.ireq_status','lr.lookup_code')
            ->LEFTJOIN('divisi_refs as dr','im.ireq_divisi_user','dr.div_id')
            ->SELECT('im.ireq_requestor','im.ireq_status as status','dr.div_name','im.ireq_id','im.ireq_no','im.ireq_date','im.ireq_user','im.ireq_reason','lr.lookup_desc as ireq_status')
            ->WHERE('im.created_by',$usr_name)
            ->WHERERaw('LOWER(lr.lookup_type) LIKE ? ',[trim(strtolower('ict_status')).'%'])
            ->WHERE(function($query){
                return $query
                ->WHERE('im.ireq_status','RR')
                ->orwhere('im.ireq_status','RA1')
                ->orwhere('im.ireq_status','RA2');
            })            
            ->ORDERBY('im.ireq_date','DESC')
            ->get();

            $ict3 =  DB::table('ireq_mst as im')
            ->SELECT('im.ireq_requestor','im.ireq_status as status','im.ireq_id','im.ireq_no','im.ireq_date','im.ireq_user','im.ireq_requestor','dr.div_name',DB::raw("COALESCE(im.ireq_assigned_to2,im.ireq_assigned_to1) AS ireq_assigned_to"),'lr.lookup_desc as ireq_status')
            ->LEFTJOIN('divisi_refs as dr','im.ireq_divisi_user','dr.div_id')
            ->LEFTJOIN('lookup_refs as lr','im.ireq_status','lr.lookup_code')
            ->WHERE('im.ireq_status','T')
            ->WHERERaw('LOWER(lr.lookup_type) LIKE ? ',[trim(strtolower('ict_status')).'%'])
            ->WHERE('im.created_by',$usr_name)
            ->groupBy('im.ireq_requestor','im.ireq_status','im.ireq_id','im.ireq_no','im.ireq_date','im.ireq_user','im.ireq_requestor','dr.div_name','im.creation_date','lr.lookup_desc',DB::raw("COALESCE(im.ireq_assigned_to2,im.ireq_assigned_to1)"))          
            ->ORDERBY('im.ireq_date','DESC')
            ->get();

            $ict4 = DB::table('ireq_dtl as id')
            ->LEFTJOIN('ireq_mst as im','id.ireq_id','im.ireq_id')
            ->LEFTJOIN('divisi_refs as dr','im.ireq_divisi_user','dr.div_id')
            ->LEFTJOIN('lookup_refs as lr',function ($join) {
                $join->on('id.ireq_status','lr.lookup_code')
                      ->WHERERaw('LOWER(lr.lookup_type) LIKE ? ',[trim(strtolower('ict_status')).'%']);
            })
            ->LEFTJOIN('lookup_refs as lrs',function ($join) {
                $join->on('id.ireq_type','lrs.lookup_code')
                      ->WHERERaw('LOWER(lrs.lookup_type) LIKE ? ',[trim(strtolower('req_type')).'%']);
            })
            ->LEFTJOIN('lookup_refs as lrfs',function ($join) {
                $join->on('id.invent_code','lrfs.lookup_code')
                      ->WHERERaw('LOWER(lrfs.lookup_type) LIKE ? ',[trim(strtolower('kat_peripheral')).'%']);
            })
            ->SELECT('id.ireq_attachment','im.ireq_no','id.ireq_id','id.ireq_remark',DB::raw("COALESCE(id.ireq_assigned_to2,id.ireq_assigned_to1) AS ireq_assigned_to"),'id.ireqd_id',
            'lr.lookup_desc as ireq_status','lrs.lookup_desc as ireq_type','lrfs.lookup_desc as kategori','im.ireq_date','im.ireq_requestor','im.ireq_user',
            'dr.div_name','id.ireq_qty','id.ireq_status as status')
            ->WHERE('id.ireq_status','D')
            ->WHERE('im.created_by',$usr_name)
            ->ORDERBY('im.ireq_date','DESC')
            ->ORDERBY('id.ireqd_id','ASC')
            ->get();

            $ict5 = DB::table('ireq_dtl as id')
            ->LEFTJOIN('ireq_mst as im','id.ireq_id','im.ireq_id')
            ->LEFTJOIN('divisi_refs as dr','im.ireq_divisi_user','dr.div_id')
            ->LEFTJOIN('lookup_refs as lr',function ($join) {
                $join->on('id.ireq_status','lr.lookup_code')
                      ->WHERERaw('LOWER(lr.lookup_type) LIKE ? ',[trim(strtolower('ict_status')).'%']);
            })
            ->LEFTJOIN('lookup_refs as lrs',function ($join) {
                $join->on('id.ireq_type','lrs.lookup_code')
                      ->WHERERaw('LOWER(lrs.lookup_type) LIKE ? ',[trim(strtolower('req_type')).'%']);
            })
            ->LEFTJOIN('lookup_refs as lrfs',function ($join) {
                $join->on('id.invent_code','lrfs.lookup_code')
                      ->WHERERaw('LOWER(lrfs.lookup_type) LIKE ? ',[trim(strtolower('kat_peripheral')).'%']);
            })
            ->SELECT('id.ireq_attachment','id.ireq_value','im.ireq_no','id.ireq_id','id.ireq_remark',DB::raw("COALESCE(id.ireq_assigned_to2,id.ireq_assigned_to1) AS ireq_assigned_to"),'id.ireqd_id',
            'lr.lookup_desc as ireq_status','lrs.lookup_desc as ireq_type','lrfs.lookup_desc as kategori','im.ireq_date','im.ireq_requestor','im.ireq_user',
            'dr.div_name','id.ireq_qty','id.ireq_status as status')
            ->WHERE('id.ireq_status','C')
            ->WHERE('im.created_by',$usr_name)
            ->ORDERBY('im.ireq_date','DESC')
            ->ORDERBY('id.ireqd_id','ASC')
            ->get();
            
            $ict6 = DB::table('ireq_mst as im')
            ->LEFTJOIN('lookup_refs as lr','im.ireq_status','lr.lookup_code')
            ->LEFTJOIN('divisi_refs as dr','im.ireq_divisi_user','dr.div_id')
            ->SELECT('im.ireq_requestor','im.ireq_status as status','dr.div_name','im.ireq_id','im.ireq_no','im.ireq_date','im.ireq_user','lr.lookup_desc as ireq_status')
            ->WHERE('im.created_by',$usr_name)
            ->WHERERaw('LOWER(lr.lookup_type) LIKE ? ',[trim(strtolower('ict_status')).'%'])
            ->WHERE(function($query){
                return $query
                ->WHERE('im.ireq_status','NA1')
                ->orwhere('im.ireq_status','NA2');
            })            
            ->ORDERBY('im.ireq_date','DESC')
            ->get();
            
            $ict7 = DB::table('ireq_mst as im')
            ->LEFTJOIN('ireq_dtl as idm','im.ireq_id','idm.ireq_id')
            ->LEFTJOIN('lookup_refs as lr','idm.ireq_status','lr.lookup_code')
            ->LEFTJOIN('divisi_refs as dr','im.ireq_divisi_user','dr.div_id')
            ->SELECT('im.ireq_requestor','im.ireq_status as status','im.ireq_id','im.ireq_no','im.ireq_date','im.ireq_user','dr.div_name',
                    DB::raw('count(DISTINCT(idm.ireq_id)) as count'), 'lr.lookup_desc as ireq_status')
            ->WHERE('im.created_by',$usr_name)
            ->WHERERaw('LOWER(lr.lookup_type) LIKE ? ',[trim(strtolower('ict_status')).'%'])
            ->WHERE(function($query){
                return $query
                ->WHERE('im.ireq_status','P');
                })
            ->groupBy('im.ireq_requestor','im.ireq_status','im.ireq_id','im.ireq_no','im.ireq_date','im.ireq_user','im.creation_date','dr.div_name','lr.lookup_desc')          
            ->ORDERBY('im.ireq_date','DESC')
            ->get();
            
            $ict8 = DB::table('ireq_mst as im')
            ->SELECT('im.ireq_requestor','im.ireq_id','im.ireq_status as status','im.ireq_no','im.ireq_date','im.ireq_user','im.ireq_requestor','dr.div_name',DB::raw("COALESCE(im.ireq_assigned_to2,im.ireq_assigned_to1) AS ireq_assigned_to"),'lr.lookup_desc as ireq_status')
            ->LEFTJOIN('divisi_refs as dr','im.ireq_divisi_user','dr.div_id')
            ->LEFTJOIN('lookup_refs as lr','im.ireq_status','lr.lookup_code')
            ->WHERE(function($query){
                return $query
                ->WHERE('im.ireq_status','NT')
                ->orWhere('im.ireq_status','RT');
                })
            ->WHERERaw('LOWER(lr.lookup_type) LIKE ? ',[trim(strtolower('ict_status')).'%'])
            ->WHERE('im.created_by',$usr_name)
            ->groupBy('im.ireq_requestor','im.ireq_status','im.ireq_id','im.ireq_no','im.ireq_date','im.ireq_user','im.ireq_requestor','dr.div_name','im.creation_date','lr.lookup_desc',DB::raw("COALESCE(im.ireq_assigned_to2,im.ireq_assigned_to1)"))          
            ->ORDERBY('im.ireq_date','DESC')
            ->get();

            $ict9 =  DB::table('ireq_mst as im')
            ->SELECT('im.ireq_requestor','im.ireq_status as status','im.ireq_id','im.ireq_no','im.ireq_date','im.ireq_user','im.ireq_requestor','dr.div_name',DB::raw("COALESCE(im.ireq_assigned_to2,im.ireq_assigned_to1) AS ireq_assigned_to"),'lr.lookup_desc as ireq_status')
            ->LEFTJOIN('divisi_refs as dr','im.ireq_divisi_user','dr.div_id')
            ->LEFTJOIN('lookup_refs as lr','im.ireq_status','lr.lookup_code')
            ->WHERE(function($query){
                return $query
                ->WHERE('im.ireq_status','T')
                ->orWhere('im.ireq_status','NT');
                })
            ->WHERERaw('LOWER(lr.lookup_type) LIKE ? ',[trim(strtolower('ict_status')).'%'])
            ->WHERE('im.created_by',$usr_name)
            ->groupBy('im.ireq_requestor','im.ireq_status','im.ireq_id','im.ireq_no','im.ireq_date','im.ireq_user','im.ireq_requestor','dr.div_name','im.creation_date','lr.lookup_desc',DB::raw("COALESCE(im.ireq_assigned_to2,im.ireq_assigned_to1)")) 
            ->ORDERBY('im.ireq_date','DESC')
            ->get();

            return response()->json(['ict'=>$ict,'ict1'=>$ict1,'ict2'=>$ict2,'ict3'=>$ict3,'ict4'=>$ict4,'ict5'=>$ict5,'ict6'=>$ict6,'ict7'=>$ict7,'ict8'=>$ict8,'ict9'=>$ict9],200);
            }else{
                return response(["message"=>"Cannot Access"],403);
            }
        }
    function getPermohonan()
    {
        $usr_email = Auth::user()->usr_email;
        $role = Mng_usr_roles::select('rol_id')->WHERE('usr_id',Auth::user()->usr_id)->pluck('rol_id');
        $menu = Mng_role_menu::select('menu_id')->WHEREIn('rol_id',$role)->pluck('menu_id');
        $aksesmenu = DB::table('mng_menus')->SELECT('controller')->WHEREIn('menu_id',$menu)->pluck('controller');
        if($aksesmenu->contains($this->approvalrequestor)){
            $ict = DB::table('ireq_mst as im')
                ->SELECT('im.ireq_id','im.ireq_no','im.ireq_date','im.ireq_status as ireq_statuss','im.ireq_user',
                'im.ireq_requestor','lr.lookup_desc as ireq_status')
                ->LEFTJOIN('divisi_refs as dr','im.ireq_divisi_user','dr.div_id')
                ->LEFTJOIN('lookup_refs as lr','im.ireq_status','lr.lookup_code')
                // ->WHERE('dr.div_verificator',$usr_email)
                ->WHERERaw('LOWER(lr.lookup_type) LIKE ? ',[trim(strtolower('ict_status')).'%'])
                ->WHERE(function($query){
                    return $query
                    ->WHERE('im.ireq_status','NA1')
                    ->orwhere('im.ireq_status','NA2')
                    ->orwhere('im.ireq_status','P');
                })
                ->groupBy('im.ireq_id','im.ireq_no','im.ireq_date','im.ireq_status','im.ireq_user','im.creation_date','im.ireq_requestor','lr.lookup_desc')
                ->ORDERBY('im.ireq_date','DESC')
                ->get();
            
            $ict1 = DB::table('ireq_mst as im')
                ->SELECT('im.ireq_id','im.ireq_no','im.ireq_date','im.ireq_user','im.ireq_status as status',
                'im.ireq_requestor','lr.lookup_desc as ireq_status')
                ->LEFTJOIN('divisi_refs as dr','im.ireq_divisi_user','dr.div_id')
                ->LEFTJOIN('lookup_refs as lr','im.ireq_status','lr.lookup_code')
                // ->WHERE('dr.div_verificator',$usr_email)
                ->WHERERaw('LOWER(lr.lookup_type) LIKE ? ',[trim(strtolower('ict_status')).'%'])
                ->WHERE(function($query){
                    return $query
                    ->WHERE('im.ireq_status','A1')
                    ->orwhere('im.ireq_status','A2');
                    })
                ->ORDERBY('im.ireq_date','DESC')
                ->get();

            $ict2 = DB::table('ireq_mst as im')
            ->SELECT('im.ireq_id','im.ireq_no','im.ireq_date','im.ireq_user','im.ireq_status as status',
            'im.ireq_requestor','im.ireq_reason','lr.lookup_desc as ireq_status')
            ->LEFTJOIN('divisi_refs as dr','im.ireq_divisi_user','dr.div_id')
            ->LEFTJOIN('lookup_refs as lr','im.ireq_status','lr.lookup_code')
            // ->WHERE('dr.div_verificator',$usr_email)
            ->WHERERaw('LOWER(lr.lookup_type) LIKE ? ',[trim(strtolower('ict_status')).'%'])
            ->WHERE(function($query){
                return $query
                ->WHERE('im.ireq_status','RA1')
                ->orwhere('im.ireq_status','RA2')
                ->orwhere('im.ireq_status','RR');
                })
            ->ORDERBY('im.ireq_date','DESC')
            ->get();

            $ict3 = DB::table('ireq_mst as im')
            ->SELECT('im.ireq_id','im.ireq_no','im.ireq_date','im.ireq_user','im.ireq_requestor','im.ireq_status as status','lr.lookup_desc as ireq_status',
            DB::raw("COALESCE(im.ireq_assigned_to2,im.ireq_assigned_to1) AS ireq_assigned_to"))
            ->LEFTJOIN('divisi_refs as dr','im.ireq_divisi_user','dr.div_id')
            ->LEFTJOIN('lookup_refs as lr','im.ireq_status','lr.lookup_code')
            ->WHERE('dr.div_verificator',$usr_email)
            ->WHERERaw('LOWER(lr.lookup_type) LIKE ? ',[trim(strtolower('ict_status')).'%'])
            ->WHERE('im.ireq_status','T')
            ->groupBy('im.ireq_id','im.ireq_no','im.ireq_date','lr.lookup_desc','im.ireq_user','im.ireq_status','im.creation_date','im.ireq_requestor',DB::raw("COALESCE(im.ireq_assigned_to2,im.ireq_assigned_to1)"))
            ->ORDERBY('im.ireq_date','DESC')
            ->get();

            $ict4 = DB::table('ireq_dtl as id')
            ->LEFTJOIN('ireq_mst as im','id.ireq_id','im.ireq_id')
            ->LEFTJOIN('divisi_refs as dr','im.ireq_divisi_user','dr.div_id')
            ->LEFTJOIN('lookup_refs as lr',function ($join) {
                $join->on('id.ireq_status','lr.lookup_code')
                      ->WHERERaw('LOWER(lr.lookup_type) LIKE ? ',[trim(strtolower('ict_status')).'%']);
            })
            ->LEFTJOIN('lookup_refs as lrs',function ($join) {
                $join->on('id.ireq_type','lrs.lookup_code')
                      ->WHERERaw('LOWER(lrs.lookup_type) LIKE ? ',[trim(strtolower('req_type')).'%']);
            })
            ->LEFTJOIN('lookup_refs as lrfs',function ($join) {
                $join->on('id.invent_code','lrfs.lookup_code')
                      ->WHERERaw('LOWER(lrfs.lookup_type) LIKE ? ',[trim(strtolower('kat_peripheral')).'%']);
            })
            ->SELECT('id.ireq_attachment','im.ireq_no','id.ireq_id','id.ireq_remark',DB::raw("COALESCE(id.ireq_assigned_to2,id.ireq_assigned_to1) AS ireq_assigned_to"),'id.ireqd_id',
            'lr.lookup_desc as ireq_status','lrs.lookup_desc as ireq_type','lrfs.lookup_desc as kategori','im.ireq_date','im.ireq_requestor','im.ireq_user',
            'dr.div_name','id.ireq_qty','id.ireq_status as status')
            ->WHERE('id.ireq_status','D')
            ->WHERE('dr.div_verificator',$usr_email)
            ->ORDERBY('im.ireq_date','DESC')
            ->ORDERBY('id.ireqd_id','ASC')
            ->get();

            $ict5 = DB::table('ireq_dtl as id')
            ->LEFTJOIN('ireq_mst as im','id.ireq_id','im.ireq_id')
            ->LEFTJOIN('divisi_refs as dr','im.ireq_divisi_user','dr.div_id')
            ->LEFTJOIN('lookup_refs as lr',function ($join) {
                $join->on('id.ireq_status','lr.lookup_code')
                      ->WHERERaw('LOWER(lr.lookup_type) LIKE ? ',[trim(strtolower('ict_status')).'%']);
            })
            ->LEFTJOIN('lookup_refs as lrs',function ($join) {
                $join->on('id.ireq_type','lrs.lookup_code')
                      ->WHERERaw('LOWER(lrs.lookup_type) LIKE ? ',[trim(strtolower('req_type')).'%']);
            })
            ->LEFTJOIN('lookup_refs as lrfs',function ($join) {
                $join->on('id.invent_code','lrfs.lookup_code')
                      ->WHERERaw('LOWER(lrfs.lookup_type) LIKE ? ',[trim(strtolower('kat_peripheral')).'%']);
            })
            ->SELECT('id.ireq_attachment','im.ireq_no','id.ireq_id','id.ireq_remark',DB::raw("COALESCE(id.ireq_assigned_to2,id.ireq_assigned_to1) AS ireq_assigned_to"),'id.ireqd_id',
            'lr.lookup_desc as ireq_status','lrs.lookup_desc as ireq_type','lrfs.lookup_desc as kategori','im.ireq_date','im.ireq_requestor','im.ireq_user',
            'dr.div_name','id.ireq_qty','id.ireq_status as status')
            ->WHERE('id.ireq_status','C')
            ->WHERE('dr.div_verificator',$usr_email)
            ->ORDERBY('im.ireq_date','DESC')
            ->ORDERBY('id.ireqd_id','ASC')
            ->get();

            $ict6 = DB::table('ireq_mst as id')
                ->SELECT('id.ireq_id','id.ireq_no','id.ireq_user','id.ireq_date','dr.div_name','id.ireq_requestor','id.ireq_status as status','lr.lookup_desc as ireq_status')
                ->LEFTJOIN('divisi_refs as dr','id.ireq_divisi_user','dr.div_id')
                ->LEFTJOIN('lookup_refs as lr','id.ireq_status','lr.lookup_code')
                ->WHERE('dr.div_verificator',$usr_email)
                ->WHERERaw('LOWER(lr.lookup_type) LIKE ? ',[trim(strtolower('ict_status')).'%'])
                ->WHERENotNull('id.ireq_status')
                ->ORDERBY('id.ireq_date','DESC')
                ->get();

            $ict7 = DB::table('ireq_mst as im')
            ->SELECT('im.ireq_status as status','im.ireq_id','im.ireq_no','im.ireq_date','im.ireq_status as ireq_statuss','im.ireq_user',
            'im.ireq_requestor','lr.lookup_desc as ireq_status')
            ->LEFTJOIN('divisi_refs as dr','im.ireq_divisi_user','dr.div_id')
            ->LEFTJOIN('lookup_refs as lr','im.ireq_status','lr.lookup_code')
            ->WHERE('dr.div_verificator',$usr_email)
            ->WHERERaw('LOWER(lr.lookup_type) LIKE ? ',[trim(strtolower('ict_status')).'%'])
            ->WHERE(function($query){
                return $query
                ->WHERE('im.ireq_status','NA1')
                ->orwhere('im.ireq_status','NA2');
            })
            ->groupBy('im.ireq_status','im.ireq_id','im.ireq_no','im.ireq_date','im.ireq_status','im.ireq_user','im.creation_date','im.ireq_requestor','lr.lookup_desc')
            ->ORDERBY('im.ireq_date','DESC')
            ->get();

            $ict8 = DB::table('ireq_mst as im')
            ->SELECT('im.ireq_id','im.ireq_no','im.ireq_date','im.ireq_status as ireq_statuss','im.ireq_user',
            'im.ireq_requestor','lr.lookup_desc as ireq_status')
            ->LEFTJOIN('divisi_refs as dr','im.ireq_divisi_user','dr.div_id')
            ->LEFTJOIN('lookup_refs as lr','im.ireq_status','lr.lookup_code')
            ->WHERE('dr.div_verificator',$usr_email)
            ->WHERERaw('LOWER(lr.lookup_type) LIKE ? ',[trim(strtolower('ict_status')).'%'])
            ->WHERE('im.ireq_status','P')
            ->groupBy('im.ireq_id','im.ireq_no','im.ireq_date','im.ireq_status','im.ireq_user','im.creation_date','im.ireq_requestor','lr.lookup_desc')
            ->ORDERBY('im.ireq_date','DESC')
            ->get();

            $ict9 = DB::table('ireq_mst as im')
            ->SELECT('im.ireq_status as status','im.ireq_id','im.ireq_no','im.ireq_date','im.ireq_user','im.ireq_requestor','lr.lookup_desc as ireq_status',DB::raw("COALESCE(im.ireq_assigned_to2,im.ireq_assigned_to1) AS ireq_assigned_to"))
            ->LEFTJOIN('divisi_refs as dr','im.ireq_divisi_user','dr.div_id')
            ->LEFTJOIN('lookup_refs as lr','im.ireq_status','lr.lookup_code')
            ->WHERE('dr.div_verificator',$usr_email)
            ->WHERERaw('LOWER(lr.lookup_type) LIKE ? ',[trim(strtolower('ict_status')).'%'])
            ->WHERE(function($query){
                return $query
                ->WHERE('im.ireq_status','NT')
                ->orwhere('im.ireq_status','RT');
            })
            ->groupBy('im.ireq_status','im.ireq_id','im.ireq_no','im.ireq_date','lr.lookup_desc','im.ireq_user','im.creation_date','im.ireq_requestor',DB::raw("COALESCE(im.ireq_assigned_to2,im.ireq_assigned_to1)"))
            ->ORDERBY('im.ireq_date','DESC')
            ->get();


            return response()->json(['ict'=>$ict,'ict1'=>$ict1,'ict2'=>$ict2,'ict3'=>$ict3,'ict4'=>$ict4,'ict5'=>$ict5,'ict6'=>$ict6,'ict7'=>$ict7,'ict8'=>$ict8,'ict9'=>$ict9]);
        }else{
            return response(["message"=>"Cannot Access"],403);
        }
    }
    function getPermohonanDivisi()
    {
        $ict1 = $ict = DB::table('ireq_mst as im')
        ->SELECT('im.ireq_id','im.ireq_status as status','im.ireq_no','im.ireq_date','im.ireq_user','im.ireq_requestor','dr.div_name', 
                DB::raw('count(idd.ireq_assigned_to1) as ireq_count_status'), DB::raw('count(idd.ireq_id) as ireq_count_id'),
                'lr.lookup_desc as ireq_status')
        ->LEFTJOIN('divisi_refs as dr','im.ireq_divisi_user','dr.div_id')
        ->LEFTJOIN('ireq_dtl as idd','im.ireq_id','idd.ireq_id')
        ->LEFTJOIN('lookup_refs as lr','im.ireq_status','lr.lookup_code')
        ->WHERE('im.ireq_status','P')
        ->WHERERaw('LOWER(lr.lookup_type) LIKE ? ',[trim(strtolower('ict_status')).'%'])
        ->groupBy('im.ireq_id','im.ireq_status','im.ireq_no','im.ireq_date','im.ireq_user','im.ireq_requestor','dr.div_name','im.creation_date','lr.lookup_desc')
        ->ORDERBY('im.ireq_date','DESC')
        ->get(); 

        $ict2 = DB::table('ireq_mst as im')
        ->SELECT('im.ireq_id','im.ireq_status as status','im.ireq_no','im.ireq_date','im.ireq_user','im.ireq_requestor','dr.div_name','lr.lookup_desc as ireq_status',
                DB::raw('count(idd.ireq_assigned_to1) as ireq_count_status'), DB::raw('count(idd.ireq_id) as ireq_count_id'))
        ->LEFTJOIN('divisi_refs as dr','im.ireq_divisi_user','dr.div_id')
        ->LEFTJOIN('ireq_dtl as idd','im.ireq_id','idd.ireq_id')
        ->LEFTJOIN('lookup_refs as lr','im.ireq_status','lr.lookup_code')
        ->WHERERaw('LOWER(lr.lookup_type) LIKE ? ',[trim(strtolower('ict_status')).'%'])
        ->WHERE('im.ireq_status','NA1')
        ->orwhere('im.ireq_status','A1')
        ->groupBy('im.ireq_id','im.ireq_status','im.ireq_no','im.ireq_date','im.ireq_user','im.ireq_requestor','dr.div_name','im.creation_date','lr.lookup_desc')
        ->ORDERBY('im.ireq_date','DESC')
        ->get(); 

        $ict3 = DB::table('ireq_mst as im')
        ->SELECT('im.ireq_id','im.ireq_status as status','im.ireq_no','im.ireq_date','im.ireq_user','im.ireq_requestor','dr.div_name','lr.lookup_desc as ireq_status',
                DB::raw('count(idd.ireq_assigned_to1) as ireq_count_status'), DB::raw('count(idd.ireq_id) as ireq_count_id'))
        ->LEFTJOIN('divisi_refs as dr','im.ireq_divisi_user','dr.div_id')
        ->LEFTJOIN('ireq_dtl as idd','im.ireq_id','idd.ireq_id')
        ->LEFTJOIN('lookup_refs as lr','im.ireq_status','lr.lookup_code')
        ->WHERERaw('LOWER(lr.lookup_type) LIKE ? ',[trim(strtolower('ict_status')).'%'])
        ->WHERE('im.ireq_status','NA2')
        ->orwhere('im.ireq_status','A2')
        ->groupBy('im.ireq_id','im.ireq_status','im.ireq_no','im.ireq_date','im.ireq_user','im.ireq_requestor','dr.div_name','im.creation_date','lr.lookup_desc')
        ->ORDERBY('im.ireq_date','DESC')
        ->get(); 

        $ict4 = DB::table('ireq_mst as im')
        ->SELECT('im.ireq_id','im.ireq_status as status','im.ireq_no','im.ireq_date','im.ireq_user','dr.div_name','im.ireq_reason','lr.lookup_desc as ireq_status',
        DB::raw('count(idd.ireq_assigned_to1) as ireq_count_status'), DB::raw('count(idd.ireq_id) as ireq_count_id'))
        ->LEFTJOIN('divisi_refs as dr','im.ireq_divisi_user','dr.div_id')
        ->LEFTJOIN('ireq_dtl as idd','im.ireq_id','idd.ireq_id')
        ->LEFTJOIN('lookup_refs as lr','im.ireq_status','lr.lookup_code')
        ->WHERERaw('LOWER(lr.lookup_type) LIKE ? ',[trim(strtolower('ict_status')).'%'])
        ->WHERE(function ($query){
            return $query
            ->WHERE('im.ireq_status','RR')
            ->OrWhere('im.ireq_status','RA1')
            ->OrWhere('im.ireq_status','RA2');
        })
        ->groupBy('im.ireq_id','im.ireq_status','im.ireq_no','im.ireq_date','im.ireq_user','im.ireq_reason','dr.div_name','im.creation_date','lr.lookup_desc')
        ->ORDERBY('im.ireq_date','DESC')
        ->get(); 

        
        $ict5 = DB::table('ireq_mst as im')
        ->SELECT('im.ireq_id','im.ireq_status as status','im.ireq_no','im.ireq_date','im.ireq_user','im.ireq_requestor','dr.div_name',DB::raw("COALESCE(im.ireq_assigned_to2,im.ireq_assigned_to1) AS ireq_assigned_to"),
                'lr.lookup_desc as ireq_status')
        ->LEFTJOIN('divisi_refs as dr','im.ireq_divisi_user','dr.div_id')
        ->LEFTJOIN('lookup_refs as lr','im.ireq_status','lr.lookup_code')
        ->WHERE('im.ireq_status','T')
        ->WHERERaw('LOWER(lr.lookup_type) LIKE ? ',[trim(strtolower('ict_status')).'%'])
        ->groupBy('im.ireq_id','im.ireq_status','im.ireq_no','im.ireq_date','im.ireq_user','im.ireq_requestor','dr.div_name','im.creation_date','lr.lookup_desc',DB::raw("COALESCE(im.ireq_assigned_to2,im.ireq_assigned_to1)"))
        ->ORDERBY('im.ireq_date','DESC')
        ->get();

        $ict6 = DB::Table('v_ireq_mst_sudah_dikerjakan')->get();

        $ict7 = DB::table('ireq_dtl as idd')
        ->SELECT('idm.ireq_id', 'idm.ireq_no','idd.ireq_status as status', 'idm.ireq_date', 'idm.ireq_requestor', 'lrfs.lookup_desc as ireq_type',
        'idd.ireq_remark','idd.ireq_qty', 'idm.ireq_user', DB::raw("COALESCE(idm.ireq_assigned_to2,idm.ireq_assigned_to1) AS ireq_assigned_to"), 'idd.ireqd_id','lrs.lookup_desc as invent_code','dr.div_name', 'lr.lookup_desc as ireq_status')
        ->LEFTJOIN('ireq_mst as idm','idd.ireq_id','idm.ireq_id')
        ->LEFTJOIN('divisi_refs as dr','idm.ireq_divisi_user','dr.div_id')
        ->LEFTJOIN('lookup_refs as lrs',function ($join) {
            $join->on('idd.invent_code','lrs.lookup_code')
                  ->WHERERaw('LOWER(lrs.lookup_type) LIKE ? ',[trim(strtolower('kat_peripheral')).'%']);
        })
        ->LEFTJOIN('lookup_refs as lr',function ($join) {
            $join->on('idd.ireq_status','lr.lookup_code')
                  ->WHERERaw('LOWER(lr.lookup_type) LIKE ? ',[trim(strtolower('ict_status')).'%']);
        })
        ->LEFTJOIN('lookup_refs as lrfs',function ($join) {
            $join->on('idd.ireq_type','lrfs.lookup_code')
                  ->WHERERaw('LOWER(lrfs.lookup_type) LIKE ? ',[trim(strtolower('req_type')).'%']);
        })
        ->WHERE('idd.ireq_status','C')
        ->ORDERBY('idm.ireq_date','DESC')
        ->ORDERBY('id.ireqd_id','ASC')
        ->get();

        $ict8 = DB::table('ireq_mst as id')
        ->SELECT('id.ireq_id','id.ireq_status as status','id.ireq_no','id.ireq_date','id.ireq_user','id.ireq_requestor','dr.div_name','id.ireq_assigned_to1','lr.lookup_desc as ireq_status')
        ->LEFTJOIN('divisi_refs as dr','id.ireq_divisi_user','dr.div_id')
        ->LEFTJOIN('lookup_refs as lr','id.ireq_status','lr.lookup_code')
        ->WHERERaw('LOWER(lr.lookup_type) LIKE ? ',[trim(strtolower('ict_status')).'%'])
        ->WHERENotNull('id.ireq_status')
        ->ORDERBY('id.ireq_date','DESC')
        ->get();

        $ict9 = DB::table('ireq_mst as im')
            ->LEFTJOIN('divisi_refs as dr','im.ireq_divisi_user','dr.div_id')
            ->LEFTJOIN('lookup_refs as lr','im.ireq_status','lr.lookup_code')
            ->SELECT('im.ireq_id','im.ireq_status as status','im.ireq_assigned_to2','im.ireq_no','im.ireq_date','im.ireq_user','im.ireq_requestor','dr.div_name',DB::raw("COALESCE(im.ireq_assigned_to2,im.ireq_assigned_to1) AS ireq_assigned_to"),'lr.lookup_desc as ireq_status')
            ->WHERE(function($query){
                return $query
                ->WHERE('im.ireq_status','NT')
                ->Orwhere('im.ireq_status','RT');
            })
            ->WHERERaw('LOWER(lr.lookup_type) LIKE ? ',[trim(strtolower('ict_status')).'%'])
            ->groupBy('im.ireq_id','im.ireq_status','im.ireq_assigned_to2','im.ireq_no','im.ireq_date','im.ireq_user','im.ireq_requestor','dr.div_name','im.creation_date','lr.lookup_desc',DB::raw("COALESCE(im.ireq_assigned_to2,im.ireq_assigned_to1)"))
            ->ORDERBY('im.ireq_date','DESC')
            ->get();

        return response()->json(['ict1'=>$ict1,'ict2'=>$ict2,'ict3'=>$ict3,'ict4'=>$ict4,'ict5'=>$ict5,'ict6'=>$ict6,'ict7'=>$ict7,'ict8'=>$ict8,'ict9'=>$ict9]);
    }
    function getSedangDikerjakan()
    {
        $usr_fullname = Auth::user()->usr_fullname;
        $role = Mng_usr_roles::select('rol_id')->WHERE('usr_id',Auth::user()->usr_id)->pluck('rol_id');
        $menu = Mng_role_menu::select('menu_id')->WHEREIn('rol_id',$role)->pluck('menu_id');
        $aksesmenu = DB::table('mng_menus')->SELECT('controller')->WHEREIn('menu_id',$menu)->pluck('controller');
        if($aksesmenu->contains($this->personnel)){
         $ict = DB::table('ireq_dtl as id')
            ->SELECT('id.ireq_attachment','imm.ireq_no','id.ireq_assigned_remark','id.ireq_status as status','id.ireq_id','id.ireq_desc','id.ireq_qty','id.ireq_remark','id.ireqd_id','dr.div_name',
                DB::raw("COALESCE(id.ireq_assigned_to2,id.ireq_assigned_to1) AS ireq_assigned_to"),
                'imm.ireq_user', 'llr.lookup_desc as ireq_status', 'imm.ireq_requestor',
                'vr.name as ireq_bu','lr.lookup_desc as ireq_type','imm.ireq_date',
                'lrs.lookup_desc as name')
            ->LEFTJOIN('lookup_refs as lrs',function ($join) {
                $join->on('id.invent_code','lrs.lookup_code')
                ->WHERERaw('LOWER(lrs.lookup_type) LIKE ? ',[trim(strtolower('kat_peripheral')).'%']);
            })
            ->LEFTJOIN('lookup_refs as lr','id.ireq_type','lr.lookup_code')
            ->LEFTJOIN('ireq_mst as imm','id.ireq_id','imm.ireq_id')        
            ->LEFTJOIN('lookup_refs as llr','id.ireq_status','llr.lookup_code')
            ->LEFTJOIN('vcompany_refs as vr','imm.ireq_bu','vr.company_code')
            ->LEFTJOIN('divisi_refs as dr','imm.ireq_divisi_user','dr.div_id')
            ->WHERE('id.ireq_status','T')
            ->WHERE(DB::raw("COALESCE(id.ireq_assigned_to2,id.ireq_assigned_to1)"),$usr_fullname)
            ->WHERERaw('LOWER(lr.lookup_type) LIKE ? ',[trim(strtolower('req_type')).'%'])
            ->WHERERaw('LOWER(llr.lookup_type) LIKE ? ',[trim(strtolower('ict_status')).'%'])
            ->ORDERBY('imm.ireq_date','DESC')
            ->ORDERBY('id.ireqd_id','ASC')
         ->get();

         $ict1 = DB::table('ireq_dtl as id')
            ->SELECT('id.ireq_attachment','imm.ireq_no','id.ireq_assigned_remark','id.ireq_desc','id.ireq_qty','id.ireq_remark','id.ireqd_id','dr.div_name',DB::raw("COALESCE(id.ireq_assigned_to2,id.ireq_assigned_to1) AS ireq_assigned_to"),
                'imm.ireq_user', 'llr.lookup_desc as ireq_status', 'imm.ireq_requestor',
                'vr.name as ireq_bu','lr.lookup_desc as ireq_type','imm.ireq_date',
                'lrs.lookup_desc as invent_code','id.ireq_status as status')
            ->LEFTJOIN('lookup_refs as lrs',function ($join) {
                $join->on('id.invent_code','lrs.lookup_code')
                ->WHERERaw('LOWER(lrs.lookup_type) LIKE ? ',[trim(strtolower('kat_peripheral')).'%']);
            })
            ->LEFTJOIN('lookup_refs as lr','id.ireq_type','lr.lookup_code')
            ->LEFTJOIN('ireq_mst as imm','id.ireq_id','imm.ireq_id')        
            ->LEFTJOIN('lookup_refs as llr','id.ireq_status','llr.lookup_code')
            ->LEFTJOIN('vcompany_refs as vr','imm.ireq_bu','vr.company_code')
            ->LEFTJOIN('divisi_refs as dr','imm.ireq_divisi_user','dr.div_id')
            ->WHERE('id.ireq_status','D')
            ->WHERE(DB::raw("COALESCE(id.ireq_assigned_to2,id.ireq_assigned_to1)"),$usr_fullname)
            ->WHERERaw('LOWER(lr.lookup_type) LIKE ? ',[trim(strtolower('req_type')).'%'])
            ->WHERERaw('LOWER(llr.lookup_type) LIKE ? ',[trim(strtolower('ict_status')).'%'])
            ->ORDERBY('imm.ireq_date','DESC')
            ->ORDERBY('id.ireqd_id','ASC')
         ->get();

         $ict2 = DB::table('ireq_dtl as id')
            ->SELECT('id.ireq_attachment','imm.ireq_no','imm.ireq_id','id.ireq_assigned_remark','id.ireq_desc','id.ireq_qty','id.ireq_remark','id.ireqd_id','dr.div_name',
                'imm.ireq_user', DB::raw("COALESCE(id.ireq_assigned_to2,id.ireq_assigned_to1) AS ireq_assigned_to"),'llr.lookup_desc as ireq_status', 'imm.ireq_requestor',
                'vr.name as ireq_bu','lr.lookup_desc as ireq_type','imm.ireq_date','id.ireq_status as status', 'lrs.lookup_desc as invent_code')
            ->LEFTJOIN('lookup_refs as lrs',function ($join) {
                $join->on('id.invent_code','lrs.lookup_code')
                ->WHERERaw('LOWER(lrs.lookup_type) LIKE ? ',[trim(strtolower('kat_peripheral')).'%']);
            })
            ->LEFTJOIN('lookup_refs as lr','id.ireq_type','lr.lookup_code')
            ->LEFTJOIN('ireq_mst as imm','id.ireq_id','imm.ireq_id')        
            ->LEFTJOIN('lookup_refs as llr','id.ireq_status','llr.lookup_code')
            ->LEFTJOIN('vcompany_refs as vr','imm.ireq_bu','vr.company_code')
            ->LEFTJOIN('divisi_refs as dr','imm.ireq_divisi_user','dr.div_id')
            ->WHERE('id.ireq_status','C')
            ->WHERE(DB::raw("COALESCE(id.ireq_assigned_to2,id.ireq_assigned_to1)"),$usr_fullname)
            ->WHERERaw('LOWER(lr.lookup_type) LIKE ? ',[trim(strtolower('req_type')).'%'])
            ->WHERERaw('LOWER(llr.lookup_type) LIKE ? ',[trim(strtolower('ict_status')).'%'])
            ->ORDERBY('imm.ireq_date','DESC')
            ->ORDERBY('id.ireqd_id','ASC')
         ->get();

         $ict3 = DB::table('ireq_mst as im')
            ->LEFTJOIN('divisi_refs as dr','im.ireq_divisi_user','dr.div_id')
            ->LEFTJOIN('ireq_dtl as id','im.ireq_id','id.ireq_id')
            ->SELECT('im.ireq_date','im.ireq_no','im.ireq_requestor','im.ireq_user','dr.div_name','im.ireq_status as status','im.ireq_id')
            ->WHERE('id.ireq_status','NT')
            ->WHERE('id.ireq_assigned_to1',$usr_fullname)
            ->groupBy('im.ireq_date','im.ireq_no','im.ireq_requestor','im.ireq_user','dr.div_name','im.ireq_status','im.ireq_id')
            ->ORDERBY('im.ireq_date','DESC')
        ->get();

        $ict4 = DB::table('ireq_dtl as id')
            ->SELECT('id.ireq_attachment','imm.ireq_no','id.ireq_assigned_to1_reason','imm.ireq_id','id.ireq_assigned_remark','id.ireq_desc','id.ireq_qty','id.ireq_remark','id.ireqd_id','dr.div_name',
                'imm.ireq_user', DB::raw("COALESCE(id.ireq_assigned_to2,id.ireq_assigned_to1) AS ireq_assigned_to"),'llr.lookup_desc as ireq_status', 'imm.ireq_requestor',
                'vr.name as ireq_bu','lr.lookup_desc as ireq_type','imm.ireq_date','id.ireq_status as status', 'lrs.lookup_desc as invent_code')
            ->LEFTJOIN('lookup_refs as lrs',function ($join) {
                $join->on('id.invent_code','lrs.lookup_code')
                ->WHERERaw('LOWER(lrs.lookup_type) LIKE ? ',[trim(strtolower('kat_peripheral')).'%']);
            })
            ->LEFTJOIN('lookup_refs as lr','id.ireq_type','lr.lookup_code')
            ->LEFTJOIN('ireq_mst as imm','id.ireq_id','imm.ireq_id')        
            ->LEFTJOIN('lookup_refs as llr','id.ireq_status','llr.lookup_code')
            ->LEFTJOIN('vcompany_refs as vr','imm.ireq_bu','vr.company_code')
            ->LEFTJOIN('divisi_refs as dr','imm.ireq_divisi_user','dr.div_id')
            ->WHERE('id.ireq_status','RT')
            ->WHERE('id.ireq_assigned_to1',$usr_fullname)
            ->WHERERaw('LOWER(lr.lookup_type) LIKE ? ',[trim(strtolower('req_type')).'%'])
            ->WHERERaw('LOWER(llr.lookup_type) LIKE ? ',[trim(strtolower('ict_status')).'%'])
            ->ORDERBY('imm.ireq_date','DESC')
            ->ORDERBY('id.ireqd_id','ASC')
         ->get();
            return response()->json(['ict'=>$ict,'ict1'=>$ict1,'ict2'=>$ict2,'ict3'=>$ict3,'ict4'=>$ict4]);
        }else{
            return response(["message"=>"Cannot Access"],403);
        }
    }
    function ictDivisi4()
    {
        $ict = DB::table('ireq_mst as im')
        ->SELECT('im.ireq_id','im.ireq_no','im.ireq_date','im.ireq_user','im.ireq_requestor',
        'im.ireq_status as status','dr.div_name','lr.lookup_desc as ireq_status')
        ->LEFTJOIN('divisi_refs as dr','im.ireq_divisi_user','dr.div_id')
        ->LEFTJOIN('lookup_refs as lr','im.ireq_status','lr.lookup_code')
        ->WHERE(function($query){
            return $query
            ->WHERE('im.ireq_status','NA2')
            ->Orwhere('im.ireq_status','NA1');
        })
        ->WHERERaw('LOWER(lr.lookup_type) LIKE ? ',[trim(strtolower('ict_status')).'%'])
        ->groupBy('lr.lookup_desc','im.ireq_id','im.ireq_no','im.ireq_date','im.ireq_user','im.ireq_status','im.ireq_requestor','dr.div_name','im.creation_date')
        ->ORDERBY('im.ireq_date','DESC')
        ->get(); 

        $ict1 = DB::table('ireq_mst as im')
            ->SELECT('im.ireq_id','im.ireq_no','im.ireq_date','im.ireq_user','im.ireq_requestor', 
            'dr.div_name','lr.lookup_desc as ireq_status','im.ireq_status as status')
            ->LEFTJOIN('divisi_refs as dr','im.ireq_divisi_user','dr.div_id')
            ->LEFTJOIN('lookup_refs as lr','im.ireq_status','lr.lookup_code')
            ->WHERE('im.ireq_status','A2')
            ->orwhere('im.ireq_status','A1')
            ->WHERERaw('LOWER(lr.lookup_type) LIKE ? ',[trim(strtolower('ict_status')).'%'])
            ->groupBy('lr.lookup_desc','im.ireq_id','im.ireq_no','im.ireq_date','im.ireq_user','im.ireq_status','im.ireq_requestor','dr.div_name','im.creation_date')
            ->ORDERBY('im.ireq_date','DESC')
            ->get();

        $ict2 = DB::table('ireq_mst as im')
            ->SELECT('im.ireq_id','im.ireq_no','im.ireq_date','im.ireq_user','im.ireq_requestor','dr.div_name',
            'im.ireq_reason','lr.lookup_desc as ireq_status','im.ireq_status as status')
            ->LEFTJOIN('divisi_refs as dr','im.ireq_divisi_user','dr.div_id')
            ->LEFTJOIN('lookup_refs as lr','im.ireq_status','lr.lookup_code')
            ->WHERE('im.ireq_status','RR')
            ->orwhere('im.ireq_status','RA1')
            ->orwhere('im.ireq_status','RA2')
            ->WHERERaw('LOWER(lr.lookup_type) LIKE ? ',[trim(strtolower('ict_status')).'%'])
            ->groupBy('lr.lookup_desc','im.ireq_status','im.ireq_id','im.ireq_no','im.ireq_date','im.ireq_user','im.ireq_requestor','dr.div_name','im.creation_date','im.ireq_reason')
            ->ORDERBY('im.ireq_date','DESC')
            ->get();  

        $ict3 = DB::table('ireq_mst as im')
            ->SELECT('im.ireq_id','im.ireq_no','im.ireq_date','im.ireq_user','im.ireq_requestor',
            'dr.div_name',DB::raw("COALESCE(im.ireq_assigned_to2,im.ireq_assigned_to1) AS ireq_assigned_to"),
            'lr.lookup_desc as ireq_status','im.ireq_status as status')
            ->LEFTJOIN('divisi_refs as dr','im.ireq_divisi_user','dr.div_id')
            ->LEFTJOIN('lookup_refs as lr','im.ireq_status','lr.lookup_code')
            ->WHERE('im.ireq_status','T')
            ->WHERERaw('LOWER(lr.lookup_type) LIKE ? ',[trim(strtolower('ict_status')).'%'])
            ->groupBy('lr.lookup_desc','im.ireq_status','im.ireq_id','im.ireq_no','im.ireq_date','im.ireq_user','im.ireq_requestor','dr.div_name','im.creation_date',DB::raw("COALESCE(im.ireq_assigned_to2,im.ireq_assigned_to1)"))
            ->ORDERBY('im.ireq_date','DESC')
            ->get();

        $ict4 = DB::table('ireq_dtl as id')
            ->LEFTJOIN('ireq_mst as im','id.ireq_id','im.ireq_id')
            ->LEFTJOIN('divisi_refs as dr','im.ireq_divisi_user','dr.div_id')
            ->LEFTJOIN('lookup_refs as lr',function ($join) {
                $join->on('id.ireq_status','lr.lookup_code')
                    ->WHERERaw('LOWER(lr.lookup_type) LIKE ? ',[trim(strtolower('ict_status')).'%']);
            })
            ->LEFTJOIN('lookup_refs as lrs',function ($join) {
                $join->on('id.ireq_type','lrs.lookup_code')
                    ->WHERERaw('LOWER(lrs.lookup_type) LIKE ? ',[trim(strtolower('req_type')).'%']);
            })
            ->LEFTJOIN('lookup_refs as lrfs',function ($join) {
                $join->on('id.invent_code','lrfs.lookup_code')
                    ->WHERERaw('LOWER(lrfs.lookup_type) LIKE ? ',[trim(strtolower('kat_peripheral')).'%']);
            })
            ->SELECT('id.ireq_attachment','im.ireq_no','id.ireq_id','id.ireq_remark',DB::raw("COALESCE(id.ireq_assigned_to2,id.ireq_assigned_to1) AS ireq_assigned_to"),'id.ireqd_id',
            'lr.lookup_desc as ireq_status','lrs.lookup_desc as ireq_type','lrfs.lookup_desc as kategori','im.ireq_date','im.ireq_requestor','im.ireq_user',
            'dr.div_name','id.ireq_qty','id.ireq_status as status')
            ->WHERE('id.ireq_status','D')
            ->ORDERBY('im.ireq_date','DESC')
            ->get();

        $ict5 = DB::table('ireq_dtl as id')
            ->LEFTJOIN('ireq_mst as im','id.ireq_id','im.ireq_id')
            ->LEFTJOIN('divisi_refs as dr','im.ireq_divisi_user','dr.div_id')
            ->LEFTJOIN('lookup_refs as lr',function ($join) {
                $join->on('id.ireq_status','lr.lookup_code')
                    ->WHERERaw('LOWER(lr.lookup_type) LIKE ? ',[trim(strtolower('ict_status')).'%']);
            })
            ->LEFTJOIN('lookup_refs as lrs',function ($join) {
                $join->on('id.ireq_type','lrs.lookup_code')
                    ->WHERERaw('LOWER(lrs.lookup_type) LIKE ? ',[trim(strtolower('req_type')).'%']);
            })
            ->LEFTJOIN('lookup_refs as lrfs',function ($join) {
                $join->on('id.invent_code','lrfs.lookup_code')
                    ->WHERERaw('LOWER(lrfs.lookup_type) LIKE ? ',[trim(strtolower('kat_peripheral')).'%']);
            })
            ->SELECT('id.ireq_attachment','im.ireq_no','id.ireq_status as status','id.ireq_id','id.ireq_remark',DB::raw("COALESCE(id.ireq_assigned_to2,id.ireq_assigned_to1) AS ireq_assigned_to"),'id.ireqd_id',
            'lr.lookup_desc as ireq_status','lrs.lookup_desc as ireq_type','lrfs.lookup_desc as kategori','im.ireq_date','im.ireq_requestor','im.ireq_user',
            'dr.div_name','id.ireq_qty')
            ->WHERE('id.ireq_status','C')
            ->ORDERBY('im.ireq_date','DESC')
            ->get();

        $ict6 = DB::table('ireq_mst as id')
            ->SELECT('id.ireq_attachment','id.ireq_id','id.ireq_no','id.ireq_status as status','id.ireq_user','id.ireq_date','dr.div_name','id.ireq_requestor','lr.lookup_desc as ireq_status')
            ->LEFTJOIN('divisi_refs as dr','id.ireq_divisi_user','dr.div_id')
            ->LEFTJOIN('lookup_refs as lr','id.ireq_status','lr.lookup_code')
            ->WHERERaw('LOWER(lr.lookup_type) LIKE ? ',[trim(strtolower('ict_status')).'%'])
            ->WHERENotNull('id.ireq_status')
            ->ORDERBY('id.ireq_date','DESC')
            ->get();

        $ict7 = DB::table('ireq_mst as im')
            ->SELECT('im.ireq_id','im.ireq_no','im.ireq_date','im.ireq_user','im.ireq_requestor',
            'im.ireq_status as status','dr.div_name','lr.lookup_desc as ireq_status')
            ->LEFTJOIN('divisi_refs as dr','im.ireq_divisi_user','dr.div_id')
            ->LEFTJOIN('lookup_refs as lr','im.ireq_status','lr.lookup_code')
            ->WHERE('im.ireq_status','P')
            ->WHERERaw('LOWER(lr.lookup_type) LIKE ? ',[trim(strtolower('ict_status')).'%'])
            ->groupBy('lr.lookup_desc','im.ireq_id','im.ireq_no','im.ireq_date','im.ireq_user','im.ireq_status','im.ireq_requestor','dr.div_name','im.creation_date')
            ->ORDERBY('im.ireq_date','DESC')
            ->get(); 

        $ict8 =  DB::table('ireq_mst as im')
            ->LEFTJOIN('divisi_refs as dr','im.ireq_divisi_user','dr.div_id')
            ->LEFTJOIN('lookup_refs as lr','im.ireq_status','lr.lookup_code')
            ->SELECT('im.ireq_id','im.ireq_assigned_to2','im.ireq_no','im.ireq_date','im.ireq_user',
            'im.ireq_requestor','dr.div_name',DB::raw("COALESCE(im.ireq_assigned_to2,im.ireq_assigned_to1) 
            AS ireq_assigned_to"),'lr.lookup_desc as ireq_status','im.ireq_status as status')
            ->WHERE(function($query){
                return $query
                ->WHERE('im.ireq_status','NT')
                ->Orwhere('im.ireq_status','RT');
            })
            ->WHERERaw('LOWER(lr.lookup_type) LIKE ? ',[trim(strtolower('ict_status')).'%'])
            ->groupBy('im.ireq_id','im.ireq_status','im.ireq_assigned_to2','im.ireq_no','im.ireq_date','im.ireq_user','im.ireq_requestor','dr.div_name','im.creation_date','lr.lookup_desc',DB::raw("COALESCE(im.ireq_assigned_to2,im.ireq_assigned_to1)"))
            ->ORDERBY('im.ireq_date','DESC')
            ->get();

        return response()->json(['ict'=>$ict,'ict1'=>$ict1,'ict2'=>$ict2,'ict3'=>$ict3,'ict4'=>$ict4,'ict5'=>$ict5,'ict6'=>$ict6,'ict7'=>$ict7,'ict8'=>$ict8],200);
    }
    function totalRequest($usr_name)
    {
        $ict = DB::table('ireq_mst as id')
            ->LEFTJOIN('lookup_refs as lr','id.ireq_status','lr.lookup_code')   
            ->SELECT('id.ireq_attachment','id.ireq_id','id.ireq_status as status','id.ireq_no','id.ireq_user','id.ireq_date',
                'lr.lookup_desc as ireq_status','id.ireq_requestor')
            ->WHERERaw('LOWER(lr.lookup_type) LIKE ? ',[trim(strtolower('ict_status')).'%'])
            ->WHERE('id.created_by',$usr_name)
            ->WHERENotNull('id.ireq_status')
            ->ORDERBY('id.ireq_date','DESC')
            ->get();
        return response()->json($ict);
    }
    function save(Request $request)
    {
        $newDate = Carbon::createFromFormat('D M d Y H:i:s e+',$request->tgl)->copy()->tz('Asia/Jakarta')->format('Y-m-d H:i:s');
        $ict = Ict::Create([
            'ireq_date' => $newDate,
            'ireq_type' => $request->tipereq,
            'ireq_requestor'=> Auth::user()->usr_name,
            'ireq_divisi_requestor'=> Auth::user()->div_id,
            'ireq_user' => $request->user_name,
            'ireq_divisi_user'=>$request->user_divisi,
            'ireq_bu' => $request->bisnis,
            // 'ireq_remark'=> $request->ket,
            'ireq_loc'=>Auth::user()->usr_loc,
            'ireq_prio_level'=>$request->priolev,
            'creation_date' => $this->newCreation,
            'created_by' => Auth::user()->usr_name,
            'program_name'=>"Ict_Save",
        ]);
        return response()->json($ict);
    }
    Public function edit($code)
    {
        $ict = DB::table('ireq_mst as im')
            ->SELECT('im.ireq_id','im.ireq_requestor','im.ireq_no','im.ireq_prio_level','im.ireq_type','im.ireq_date','im.ireq_bu','im.ireq_divisi_user','im.ireq_user')
            ->LEFTJOIN('lookup_refs as lr',function ($join) {
                $join->on('im.ireq_type','lr.lookup_code')
                      ->WHERERaw('LOWER(lr.lookup_type) LIKE ? ',[trim(strtolower('req_type')).'%']);
                })
            ->WHERE('im.ireq_id',$code)
            ->first();
        $ref = Lookup_Refs::Select('lookup_code as code','lookup_desc as name')
            ->WHERE('lookup_status','T')
            ->WHERERaw('LOWER(lookup_type) LIKE ? ',[trim(strtolower('req_prio')).'%'])
            ->ORDERBY('lookup_desc','ASC')
            ->get();
    
        $bisnis = DB::table('v_company_refs')->get();
    
        $divisi = DB::table('divisi_refs')
            ->SELECT('div_id as code',DB::raw("(div_code ||'-'|| div_name) as name"))
            ->ORDERBY('div_name','ASC')
            ->get();
    
        $priority = Lookup_Refs::Select('lookup_code as code','lookup_desc as name')
            ->WHERE('lookup_status','T')
            ->WHERERaw('LOWER(lookup_type) LIKE ? ',[trim(strtolower('req_prio')).'%'])
            ->ORDERBY('lookup_desc','ASC')
            ->get();
    
            return response()->json(['ref'=>$ref,'bisnis'=>$bisnis,'divisi'=>$divisi,'prio'=>$priority,'ict'=>$ict],200);
    }
    Public function update(Request $request, $code)
    {

        $newDate = Carbon::parse($request->ireq_date)->copy()->tz('Asia/Jakarta')->format('Y-m-d H:i:s');
        $ict = Ict::where('ireq_id',$code)->first();
        $ict->ireq_date = $newDate;
        $ict->ireq_prio_level = $request->ireq_prio_level;
        // $ict->ireq_remark = $request->ireq_remark;
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
        $dtl= DB::table('ireq_dtl')->WHERE('ireq_id',$ireq_id)->delete();
          $ict->delete();
          return response()->json('Successfully deleted');
    }
    Public function getNoreq()
    {
        $ict = Ict::select('ireq_no as name','ireq_id as code')
                ->ORDERBY('ireq_no','ASC')
                ->WHERENotNull('ireq_status')
                ->get();
            return response()->json($ict);
    }
    Public function getNameBu($noreq,$dtl)
    {
        $ict = DB::table('ireq_mst as im')
        ->SELECT('im.ireq_requestor as req','im.ireq_no','im.ireq_id','vr.name as bu','id.ireqd_id','im.ireq_user',
                DB::raw("TO_CHAR(im.ireq_date, 'dd Mon YYYY') as ireq_date"))
        ->LEFTJOIN('vcompany_refs as vr','im.ireq_bu','vr.company_code')
        ->LEFTJOIN('ireq_dtl as id','im.ireq_id','id.ireq_id')
        ->WHERE('im.ireq_id',$noreq)
        ->WHERE('id.ireqd_id',$dtl)
        ->first();
            return response()->json($ict);
    }
    Public function getDetail($noreq)
    {
        $ict = IctDetail::select('ireqd_id as code')->WHERE('ireq_id',$noreq)->get();
            return response()->json($ict);
    }
    Public function cetak_pdf_atasan_permohonan()
    {
        $ict =  DB::table('ireq_mst as im')
        ->SELECT('dr.div_name','im.ireq_id','im.ireq_no',DB::raw("TO_CHAR(im.ireq_date,' dd Mon YYYY') as ireq_date"),'im.ireq_status as ireq_statuss','im.ireq_user',
        'im.ireq_requestor','lr.lookup_desc as ireq_status','vr.name as ireq_bu')
        ->LEFTJOIN('vcompany_refs as vr','im.ireq_bu','vr.company_code')
        ->LEFTJOIN('divisi_refs as dr','im.ireq_divisi_user','dr.div_id')
        ->LEFTJOIN('lookup_refs as lr','im.ireq_status','lr.lookup_code')
        ->WHERE('dr.div_verificator',Auth::user()->usr_email)
        ->WHERERaw('LOWER(lr.lookup_type) LIKE ? ',[trim(strtolower('ict_status')).'%'])
        ->WHERE(function($query){
            return $query
            ->WHERE('im.ireq_status','NA1')
            ->orwhere('im.ireq_status','NA2')
            ->orwhere('im.ireq_status','P');
        })
        ->groupBy('dr.div_name','vr.name','im.ireq_id','im.ireq_no','im.ireq_date','im.ireq_status','im.ireq_user','im.creation_date','im.ireq_requestor','lr.lookup_desc')
        ->ORDERBY('im.ireq_date','DESC')
        ->get();
        return view('pdf/Laporan_IctRequest_Permohonan', compact('ict'));
    }
    public function cetak_excel_atasan_permohonan()
    {
        $usr_email = Auth::user()->usrl_email;
        $newCreation = Carbon::parse($this->date)->copy()->tz('Asia/Jakarta')->format('d M Y');
        return Excel::download(new IctExportPermohonanAtasan($usr_email),'ICT REQUEST STATUS REPORT LIST ON '.$newCreation.'.xlsx');
    }
    Public function cetak_pdf_atasan_verifikasi()
    {
        $ict =  DB::table('ireq_mst as im')
        ->SELECT('dr.div_name','im.ireq_id','im.ireq_no',DB::raw("TO_CHAR(im.ireq_date,' dd Mon YYYY') as ireq_date"),'im.ireq_status as ireq_statuss','im.ireq_user',
        'im.ireq_requestor','lr.lookup_desc as ireq_status','vr.name as ireq_bu')
        ->LEFTJOIN('vcompany_refs as vr','im.ireq_bu','vr.company_code')
        ->LEFTJOIN('divisi_refs as dr','im.ireq_divisi_user','dr.div_id')
        ->LEFTJOIN('lookup_refs as lr','im.ireq_status','lr.lookup_code')
        ->WHERE('dr.div_verificator',Auth::user()->usr_email)
        ->WHERERaw('LOWER(lr.lookup_type) LIKE ? ',[trim(strtolower('ict_status')).'%'])
        ->WHERE(function($query){
            return $query
            ->WHERE('im.ireq_status','A1')
            ->orwhere('im.ireq_status','A2');
        })
        ->groupBy('dr.div_name','vr.name','im.ireq_id','im.ireq_no','im.ireq_date','im.ireq_status','im.ireq_user','im.creation_date','im.ireq_requestor','lr.lookup_desc')
        ->ORDERBY('im.ireq_date','DESC')
        ->get();
        return view('pdf/Laporan_IctRequest_Permohonan', compact('ict'));
    }
    public function cetak_excel_atasan_verifikasi()
    {
        $usr_email = Auth::user()->usr_email;
        $newCreation = Carbon::parse($this->date)->copy()->tz('Asia/Jakarta')->format('d M Y');
        return Excel::download(new IctExportVerifikasiAtasan($usr_email),'ICT REQUEST STATUS REPORT LIST ON '.$newCreation.'.xlsx');
    }
    Public function cetak_pdf_atasan_reject()
    {
        $ict =  DB::table('ireq_mst as im')
        ->SELECT('im.ireq_reason','dr.div_name','im.ireq_id','im.ireq_no',DB::raw("TO_CHAR(im.ireq_date,' dd Mon YYYY') as ireq_date"),'im.ireq_status as ireq_statuss','im.ireq_user',
        'im.ireq_requestor','lr.lookup_desc as ireq_status','vr.name as ireq_bu')
        ->LEFTJOIN('vcompany_refs as vr','im.ireq_bu','vr.company_code')
        ->LEFTJOIN('divisi_refs as dr','im.ireq_divisi_user','dr.div_id')
        ->LEFTJOIN('lookup_refs as lr','im.ireq_status','lr.lookup_code')
        ->WHERE('dr.div_verificator',Auth::user()->usr_email)
        ->WHERERaw('LOWER(lr.lookup_type) LIKE ? ',[trim(strtolower('ict_status')).'%'])
        ->WHERE(function($query){
            return $query
            ->WHERE('im.ireq_status','RA1')
            ->orwhere('im.ireq_status','RA2')
            ->orwhere('im.ireq_status','RR');
        })
        ->groupBy('im.ireq_reason','dr.div_name','vr.name','im.ireq_id','im.ireq_no','im.ireq_date','im.ireq_status','im.ireq_user','im.creation_date','im.ireq_requestor','lr.lookup_desc')
        ->ORDERBY('im.ireq_date','DESC')
        ->get();
        return view('pdf/Laporan_IctRequest_Reject', compact('ict'));
    }
    public function cetak_excel_atasan_reject()
    {
        $usr_email = Auth::user()->usr_email;
        $newCreation = Carbon::parse($this->date)->copy()->tz('Asia/Jakarta')->format('d M Y');
        return Excel::download(new IctExportRejectAtasan($usr_email),'ICT REQUEST STATUS REPORT LIST ON '.$newCreation.'.xlsx');
    }
    Public function cetak_pdf_atasan_assignment_request()
    {
        $ict =  DB::table('ireq_mst as im')
        ->SELECT('dr.div_name','im.ireq_id','im.ireq_no',DB::raw("TO_CHAR(im.ireq_date,' dd Mon YYYY') as ireq_date"),'im.ireq_status as ireq_statuss','im.ireq_user',
        'im.ireq_requestor','lr.lookup_desc as ireq_status','vr.name as ireq_bu',DB::raw("COALESCE(im.ireq_assigned_to2,im.ireq_assigned_to1) AS ireq_assigned_to"))
        ->LEFTJOIN('vcompany_refs as vr','im.ireq_bu','vr.company_code')
        ->LEFTJOIN('divisi_refs as dr','im.ireq_divisi_user','dr.div_id')
        ->LEFTJOIN('lookup_refs as lr','im.ireq_status','lr.lookup_code')
        ->WHERE('dr.div_verificator',Auth::user()->usr_email)
        ->WHERERaw('LOWER(lr.lookup_type) LIKE ? ',[trim(strtolower('ict_status')).'%'])
        ->WHERE(function($query){
            return $query
            ->WHERE('im.ireq_status','NT')
            ->orwhere('im.ireq_status','RT');
        })
        ->groupBy(DB::raw("COALESCE(im.ireq_assigned_to2,im.ireq_assigned_to1)"),'dr.div_name','vr.name','im.ireq_id','im.ireq_no','im.ireq_date','im.ireq_status','im.ireq_user','im.creation_date','im.ireq_requestor','lr.lookup_desc')
        ->ORDERBY('im.ireq_date','DESC')
        ->get();
        return view('pdf/Laporan_IctRequest_Sedang_Dikerjakan', compact('ict'));
    }
    public function cetak_excel_atasan_assignment_request()
    {
        $usr_email = Auth::user()->usr_email;
        $newCreation = Carbon::parse($this->date)->copy()->tz('Asia/Jakarta')->format('d M Y');
        return Excel::download(new IctExportAtasanAssignmentRequest($usr_email),'ICT REQUEST STATUS REPORT LIST ON '.$newCreation.'.xlsx');
    }
    Public function cetak_pdf_atasan_sedang_dikerjakan()
    {
        $ict =  DB::table('ireq_mst as im')
        ->SELECT('dr.div_name','im.ireq_id','im.ireq_no',DB::raw("TO_CHAR(im.ireq_date,' dd Mon YYYY') as ireq_date"),'im.ireq_status as ireq_statuss','im.ireq_user',
        'im.ireq_requestor','lr.lookup_desc as ireq_status','vr.name as ireq_bu',DB::raw("COALESCE(im.ireq_assigned_to2,im.ireq_assigned_to1) AS ireq_assigned_to"))
        ->LEFTJOIN('vcompany_refs as vr','im.ireq_bu','vr.company_code')
        ->LEFTJOIN('divisi_refs as dr','im.ireq_divisi_user','dr.div_id')
        ->LEFTJOIN('lookup_refs as lr','im.ireq_status','lr.lookup_code')
        ->WHERE('dr.div_verificator',Auth::user()->usr_email)
        ->WHERERaw('LOWER(lr.lookup_type) LIKE ? ',[trim(strtolower('ict_status')).'%'])
        ->WHERE('im.ireq_status','T')
        ->groupBy(DB::raw("COALESCE(im.ireq_assigned_to2,im.ireq_assigned_to1)"),'dr.div_name','vr.name','im.ireq_id','im.ireq_no','im.ireq_date','im.ireq_status','im.ireq_user','im.creation_date','im.ireq_requestor','lr.lookup_desc')
        ->ORDERBY('im.ireq_date','DESC')
        ->get();
        return view('pdf/Laporan_IctRequest_Sedang_Dikerjakan', compact('ict'));
    }
    public function cetak_excel_atasan_sedang_dikerjakan()
    {
        $usr_email = Auth::user()->usr_email;
        $newCreation = Carbon::parse($this->date)->copy()->tz('Asia/Jakarta')->format('d M Y');
        return Excel::download(new IctExportAtasanSedangDikerjakan($usr_email),'ICT REQUEST STATUS REPORT LIST ON '.$newCreation.'.xlsx');
    }
    Public function cetak_pdf_atasan_sudah_dikerjakan()
    {
        $ict =  DB::table('ireq_dtl as id')
        ->LEFTJOIN('ireq_mst as im','id.ireq_id','im.ireq_id')
        ->LEFTJOIN('vcompany_refs as vr','im.ireq_bu','vr.company_code')
        ->LEFTJOIN('divisi_refs as dr','im.ireq_divisi_user','dr.div_id')
        ->LEFTJOIN('lookup_refs as lr',function ($join) {
            $join->on('id.ireq_status','lr.lookup_code')
                  ->WHERERaw('LOWER(lr.lookup_type) LIKE ? ',[trim(strtolower('ict_status')).'%']);
        })
        ->LEFTJOIN('lookup_refs as lrs',function ($join) {
            $join->on('id.ireq_type','lrs.lookup_code')
                  ->WHERERaw('LOWER(lrs.lookup_type) LIKE ? ',[trim(strtolower('req_type')).'%']);
        })
        ->LEFTJOIN('lookup_refs as lrfs',function ($join) {
            $join->on('id.invent_code','lrfs.lookup_code')
                  ->WHERERaw('LOWER(lrfs.lookup_type) LIKE ? ',[trim(strtolower('kat_peripheral')).'%']);
        })
        ->SELECT('vr.name as ireq_bu','im.ireq_no','id.ireq_id','id.ireq_remark',DB::raw("COALESCE(id.ireq_assigned_to2,id.ireq_assigned_to1) AS ireq_assigned_to"),'id.ireqd_id',
        'lr.lookup_desc as ireq_status','lrs.lookup_desc as ireq_type','lrfs.lookup_desc as kategori',DB::raw("TO_CHAR(im.ireq_date,' dd Mon YYYY') as ireq_date"),'im.ireq_requestor','im.ireq_user',
        'dr.div_name','id.ireq_qty','id.ireq_status as status')
        ->WHERE('id.ireq_status','D')
        ->WHERE('dr.div_verificator', Auth::user()->usr_email)
        ->ORDERBY('im.ireq_date','DESC')
        ->ORDERBY('id.ireqd_id','ASC')
        ->get();
        return view('pdf/Laporan_IctRequest_Sudah_Dikerjakan', compact('ict'));
    }
    public function cetak_excel_atasan_sudah_dikerjakan()
    {
        $usr_email = Auth::user()->usr_email;
        $newCreation = Carbon::parse($this->date)->copy()->tz('Asia/Jakarta')->format('d M Y');
        return Excel::download(new IctExportAtasanSudahDikerjakan($usr_email),'ICT REQUEST STATUS REPORT LIST ON '.$newCreation.'.xlsx');
    }
    Public function cetak_pdf_atasan_selesai()
    {
        $ict =  DB::table('ireq_dtl as id')
        ->LEFTJOIN('ireq_mst as im','id.ireq_id','im.ireq_id')
        ->LEFTJOIN('vcompany_refs as vr','im.ireq_bu','vr.company_code')
        ->LEFTJOIN('divisi_refs as dr','im.ireq_divisi_user','dr.div_id')
        ->LEFTJOIN('lookup_refs as lr',function ($join) {
            $join->on('id.ireq_status','lr.lookup_code')
                  ->WHERERaw('LOWER(lr.lookup_type) LIKE ? ',[trim(strtolower('ict_status')).'%']);
        })
        ->LEFTJOIN('lookup_refs as lrs',function ($join) {
            $join->on('id.ireq_type','lrs.lookup_code')
                  ->WHERERaw('LOWER(lrs.lookup_type) LIKE ? ',[trim(strtolower('req_type')).'%']);
        })
        ->LEFTJOIN('lookup_refs as lrfs',function ($join) {
            $join->on('id.invent_code','lrfs.lookup_code')
                  ->WHERERaw('LOWER(lrfs.lookup_type) LIKE ? ',[trim(strtolower('kat_peripheral')).'%']);
        })
        ->SELECT('vr.name as ireq_bu','im.ireq_no','id.ireq_id','id.ireq_remark',DB::raw("COALESCE(id.ireq_assigned_to2,id.ireq_assigned_to1) AS ireq_assigned_to"),'id.ireqd_id',
        'lr.lookup_desc as ireq_status','lrs.lookup_desc as ireq_type','lrfs.lookup_desc as kategori',DB::raw("TO_CHAR(im.ireq_date,' dd Mon YYYY') as ireq_date"),'im.ireq_requestor','im.ireq_user',
        'dr.div_name','id.ireq_qty','id.ireq_status as status')
        ->WHERE('id.ireq_status','C')
        ->WHERE('dr.div_verificator', Auth::user()->usr_email)
        ->ORDERBY('im.ireq_date','DESC')
        ->ORDERBY('id.ireqd_id','ASC')
        ->get();
        return view('pdf/Laporan_IctRequest_Selesai', compact('ict'));
    }
    public function cetak_excel_atasan_selesai()
    {
        $usr_email = Auth::user()->usr_email;
        $newCreation = Carbon::parse($this->date)->copy()->tz('Asia/Jakarta')->format('d M Y');
        return Excel::download(new IctExportAtasanSelesai($usr_email),'ICT REQUEST STATUS REPORT LIST ON '.$newCreation.'.xlsx');
    }
    Public function cetak_pdf_reviewer_permohonan()
    {
        if(Auth::user()->usr_loc == 'OJ'){
            $ict =  DB::table('ireq_mst as im')
            ->SELECT('im.ireq_no','im.ireq_requestor','vr.name as ireq_bu','lr.lookup_desc as ireq_type','im.ireq_user','dr.div_name',
                    DB::raw("TO_CHAR(im.ireq_date,' dd Mon YYYY') as ireq_date"),'llr.lookup_desc as ireq_status')
            ->LEFTJOIN('vcompany_refs as vr','im.ireq_bu','vr.company_code')
            ->LEFTJOIN('lookup_refs as lr',function ($join) {
                $join->on('im.ireq_type','lr.lookup_code')
                    ->WHERERaw('LOWER(lr.lookup_type) LIKE ? ',[trim(strtolower('req_type')).'%']);
            })
            ->LEFTJOIN('divisi_refs as dr','im.ireq_divisi_user','dr.div_id')
            ->LEFTJOIN('lookup_refs as llr','im.ireq_status','llr.lookup_code')
            ->WHERE('im.ireq_status','P')
            ->WHERE('im.ireq_loc','OJ')
            ->WHERERaw('LOWER(llr.lookup_type) LIKE ? ',[trim(strtolower('ict_status')).'%'])
            ->ORDERBY('im.ireq_date','DESC')
            ->get();
            return view('pdf/Laporan_IctRequest_Permohonan', compact('ict'));
        }else if(Auth::user()->usr_loc == 'OB' OR Auth::user()->usr_loc == 'FB' ) {
            $ict =  DB::table('ireq_mst as im')
            ->SELECT('im.ireq_no','im.ireq_requestor','vr.name as ireq_bu','lr.lookup_desc as ireq_type','im.ireq_user','dr.div_name',
                    DB::raw("TO_CHAR(im.ireq_date,' dd Mon YYYY') as ireq_date"),'llr.lookup_desc as ireq_status')
            ->LEFTJOIN('vcompany_refs as vr','im.ireq_bu','vr.company_code')
            ->LEFTJOIN('lookup_refs as lr',function ($join) {
                $join->on('im.ireq_type','lr.lookup_code')
                    ->WHERERaw('LOWER(lr.lookup_type) LIKE ? ',[trim(strtolower('req_type')).'%']);
            })
            ->LEFTJOIN('divisi_refs as dr','im.ireq_divisi_user','dr.div_id')
            ->LEFTJOIN('lookup_refs as llr','im.ireq_status','llr.lookup_code')
            ->WHERE('im.ireq_status','P')
            ->WHERE(function ($query){
                return $query
                ->WHERE('im.ireq_loc','OB')
                ->OrWhere('im.ireq_loc','FB');
            })
            ->WHERERaw('LOWER(llr.lookup_type) LIKE ? ',[trim(strtolower('ict_status')).'%'])
            ->ORDERBY('im.ireq_date','DESC')
            ->get();
            return view('pdf/Laporan_IctRequest_Permohonan', compact('ict'));
        }
        else if (Auth::user()->usr_loc == 'OK' OR Auth::user()->usr_loc == 'FK'){
            $ict =  DB::table('ireq_mst as im')
            ->SELECT('im.ireq_no','im.ireq_requestor','vr.name as ireq_bu','lr.lookup_desc as ireq_type','im.ireq_user','dr.div_name',
                    DB::raw("TO_CHAR(im.ireq_date,' dd Mon YYYY') as ireq_date"),'llr.lookup_desc as ireq_status')
            ->LEFTJOIN('vcompany_refs as vr','im.ireq_bu','vr.company_code')
            ->LEFTJOIN('lookup_refs as lr',function ($join) {
                $join->on('im.ireq_type','lr.lookup_code')
                    ->WHERERaw('LOWER(lr.lookup_type) LIKE ? ',[trim(strtolower('req_type')).'%']);
            })
            ->LEFTJOIN('divisi_refs as dr','im.ireq_divisi_user','dr.div_id')
            ->LEFTJOIN('lookup_refs as llr','im.ireq_status','llr.lookup_code')
            ->WHERE('im.ireq_status','P')
            ->WHERE(function ($query){
                return $query
                ->WHERE('im.ireq_loc','OK')
                ->OrWhere('im.ireq_loc','FK');
            })
            ->WHERERaw('LOWER(llr.lookup_type) LIKE ? ',[trim(strtolower('ict_status')).'%'])
            ->ORDERBY('im.ireq_date','DESC')
            ->get();
            return view('pdf/Laporan_IctRequest_Permohonan', compact('ict'));
        }
    }
    public function cetak_excel_reviewer_permohonan()
    {
        $newCreation = Carbon::parse($this->date)->copy()->tz('Asia/Jakarta')->format('d M Y');
        return Excel::download(new IctExportReviewerPermohonan,'ICT REQUEST STATUS REPORT LIST ON '.$newCreation.'.xlsx');
    }
    Public function cetak_pdf_reviewer_atasan_divisi()
    {
        if(Auth::user()->usr_loc == 'OJ'){
            $ict =  DB::table('ireq_mst as im')
            ->SELECT('im.ireq_no','im.ireq_requestor','vr.name as ireq_bu','lr.lookup_desc as ireq_type','im.ireq_user','dr.div_name',
                    DB::raw("TO_CHAR(im.ireq_date,' dd Mon YYYY') as ireq_date"),'llr.lookup_desc as ireq_status')
            ->LEFTJOIN('vcompany_refs as vr','im.ireq_bu','vr.company_code')
            ->LEFTJOIN('lookup_refs as lr',function ($join) {
                $join->on('im.ireq_type','lr.lookup_code')
                    ->WHERERaw('LOWER(lr.lookup_type) LIKE ? ',[trim(strtolower('req_type')).'%']);
            })
            ->LEFTJOIN('divisi_refs as dr','im.ireq_divisi_user','dr.div_id')
            ->LEFTJOIN('lookup_refs as llr','im.ireq_status','llr.lookup_code')
            ->WHERE(function($query){
                return $query
                ->WHERE('im.ireq_status','NA1')
                ->orwhere('im.ireq_status','A1');
            })
            ->WHERE('im.ireq_loc','OJ')
            ->WHERERaw('LOWER(llr.lookup_type) LIKE ? ',[trim(strtolower('ict_status')).'%'])
            ->ORDERBY('im.ireq_date','DESC')
            ->get();
            return view('pdf/Laporan_IctRequest_Permohonan', compact('ict'));
        }else if (Auth::user()->usr_loc == 'OB' OR Auth::user()->usr_loc == 'FB'){
                $ict =  DB::table('ireq_mst as im')
                ->SELECT('im.ireq_no','im.ireq_requestor','vr.name as ireq_bu','lr.lookup_desc as ireq_type','im.ireq_user','dr.div_name',
                        DB::raw("TO_CHAR(im.ireq_date,' dd Mon YYYY') as ireq_date"),'llr.lookup_desc as ireq_status')
                ->LEFTJOIN('vcompany_refs as vr','im.ireq_bu','vr.company_code')
                ->LEFTJOIN('lookup_refs as lr',function ($join) {
                    $join->on('im.ireq_type','lr.lookup_code')
                        ->WHERERaw('LOWER(lr.lookup_type) LIKE ? ',[trim(strtolower('req_type')).'%']);
                })
                ->LEFTJOIN('divisi_refs as dr','im.ireq_divisi_user','dr.div_id')
                ->LEFTJOIN('lookup_refs as llr','im.ireq_status','llr.lookup_code')
                ->WHERE(function($query){
                    return $query
                    ->WHERE('im.ireq_status','NA1')
                    ->orwhere('im.ireq_status','A1');
                })
                ->WHERE(function ($query){
                    return $query
                    ->WHERE('im.ireq_loc','OB')
                    ->OrWhere('im.ireq_loc','FB');
                })
                ->WHERERaw('LOWER(llr.lookup_type) LIKE ? ',[trim(strtolower('ict_status')).'%'])
                ->ORDERBY('im.ireq_date','DESC')
                ->get();
                return view('pdf/Laporan_IctRequest_Permohonan', compact('ict'));
        }else if(Auth::user()->usr_loc == 'OK' OR Auth::user()->usr_loc == 'FK'){
            $ict =  DB::table('ireq_mst as im')
            ->SELECT('im.ireq_no','im.ireq_requestor','vr.name as ireq_bu','lr.lookup_desc as ireq_type','im.ireq_user','dr.div_name',
                    DB::raw("TO_CHAR(im.ireq_date,' dd Mon YYYY') as ireq_date"),'llr.lookup_desc as ireq_status')
            ->LEFTJOIN('vcompany_refs as vr','im.ireq_bu','vr.company_code')
            ->LEFTJOIN('lookup_refs as lr',function ($join) {
                $join->on('im.ireq_type','lr.lookup_code')
                    ->WHERERaw('LOWER(lr.lookup_type) LIKE ? ',[trim(strtolower('req_type')).'%']);
            })
            ->LEFTJOIN('divisi_refs as dr','im.ireq_divisi_user','dr.div_id')
            ->LEFTJOIN('lookup_refs as llr','im.ireq_status','llr.lookup_code')
            ->WHERE(function($query){
                return $query
                ->WHERE('im.ireq_status','NA1')
                ->orwhere('im.ireq_status','A1');
            })
            ->WHERE(function ($query){
                return $query
                ->WHERE('im.ireq_loc','OK')
                ->OrWhere('im.ireq_loc','FK');
            })
            ->WHERERaw('LOWER(llr.lookup_type) LIKE ? ',[trim(strtolower('ict_status')).'%'])
            ->ORDERBY('im.ireq_date','DESC')
            ->get();
            return view('pdf/Laporan_IctRequest_Permohonan', compact('ict'));
            
        }
    }
    public function cetak_excel_reviewer_atasan_divisi()
    {
        $newCreation = Carbon::parse($this->date)->copy()->tz('Asia/Jakarta')->format('d M Y');
        return Excel::download(new IctExportReviewerAtasanDivisi,'ICT REQUEST STATUS REPORT LIST ON '.$newCreation.'.xlsx');
    }
    Public function cetak_pdf_reviewer_ict_manager()
    {
        if(Auth::user()->usr_loc == 'OJ'){
            $ict =  DB::table('ireq_mst as im')
            ->SELECT('im.ireq_no','im.ireq_requestor','vr.name as ireq_bu','lr.lookup_desc as ireq_type','im.ireq_user','dr.div_name',
                    DB::raw("TO_CHAR(im.ireq_date,' dd Mon YYYY') as ireq_date"),'llr.lookup_desc as ireq_status')
            ->LEFTJOIN('vcompany_refs as vr','im.ireq_bu','vr.company_code')
            ->LEFTJOIN('lookup_refs as lr',function ($join) {
                $join->on('im.ireq_type','lr.lookup_code')
                    ->WHERERaw('LOWER(lr.lookup_type) LIKE ? ',[trim(strtolower('req_type')).'%']);
            })
            ->LEFTJOIN('divisi_refs as dr','im.ireq_divisi_user','dr.div_id')
            ->LEFTJOIN('lookup_refs as llr','im.ireq_status','llr.lookup_code')
            ->WHERE(function($query){
                return $query
                ->WHERE('im.ireq_status','NA2')
                ->orwhere('im.ireq_status','A2');
            })
            ->WHERE('im.ireq_loc','OJ')
            ->WHERERaw('LOWER(llr.lookup_type) LIKE ? ',[trim(strtolower('ict_status')).'%'])
            ->ORDERBY('im.ireq_date','DESC')
            ->get();
            return view('pdf/Laporan_IctRequest_Permohonan', compact('ict'));
        }else if (Auth::user()->usr_loc == 'OB' OR Auth::user()->usr_loc == 'FB'){
                $ict =  DB::table('ireq_mst as im')
                ->SELECT('im.ireq_no','im.ireq_requestor','vr.name as ireq_bu','lr.lookup_desc as ireq_type','im.ireq_user','dr.div_name',
                        DB::raw("TO_CHAR(im.ireq_date,' dd Mon YYYY') as ireq_date"),'llr.lookup_desc as ireq_status')
                ->LEFTJOIN('vcompany_refs as vr','im.ireq_bu','vr.company_code')
                ->LEFTJOIN('lookup_refs as lr',function ($join) {
                    $join->on('im.ireq_type','lr.lookup_code')
                        ->WHERERaw('LOWER(lr.lookup_type) LIKE ? ',[trim(strtolower('req_type')).'%']);
                })
                ->LEFTJOIN('divisi_refs as dr','im.ireq_divisi_user','dr.div_id')
                ->LEFTJOIN('lookup_refs as llr','im.ireq_status','llr.lookup_code')
                ->WHERE(function($query){
                    return $query
                    ->WHERE('im.ireq_status','NA2')
                    ->orwhere('im.ireq_status','A2');
                })
                ->WHERE(function ($query){
                    return $query
                    ->WHERE('im.ireq_loc','OB')
                    ->OrWhere('im.ireq_loc','FB');
                })
                ->WHERERaw('LOWER(llr.lookup_type) LIKE ? ',[trim(strtolower('ict_status')).'%'])
                ->ORDERBY('im.ireq_date','DESC')
                ->get();
                return view('pdf/Laporan_IctRequest_Permohonan', compact('ict'));
        }else if(Auth::user()->usr_loc == 'OK' OR Auth::user()->usr_loc == 'FK'){
            $ict =  DB::table('ireq_mst as im')
            ->SELECT('im.ireq_no','im.ireq_requestor','vr.name as ireq_bu','lr.lookup_desc as ireq_type','im.ireq_user','dr.div_name',
                    DB::raw("TO_CHAR(im.ireq_date,' dd Mon YYYY') as ireq_date"),'llr.lookup_desc as ireq_status')
            ->LEFTJOIN('vcompany_refs as vr','im.ireq_bu','vr.company_code')
            ->LEFTJOIN('lookup_refs as lr',function ($join) {
                $join->on('im.ireq_type','lr.lookup_code')
                    ->WHERERaw('LOWER(lr.lookup_type) LIKE ? ',[trim(strtolower('req_type')).'%']);
            })
            ->LEFTJOIN('divisi_refs as dr','im.ireq_divisi_user','dr.div_id')
            ->LEFTJOIN('lookup_refs as llr','im.ireq_status','llr.lookup_code')
            ->WHERE(function($query){
                return $query
                ->WHERE('im.ireq_status','NA2')
                ->orwhere('im.ireq_status','A2');
            })
            ->WHERE(function ($query){
                return $query
                ->WHERE('im.ireq_loc','OK')
                ->OrWhere('im.ireq_loc','FK');
            })
            ->WHERERaw('LOWER(llr.lookup_type) LIKE ? ',[trim(strtolower('ict_status')).'%'])
            ->ORDERBY('im.ireq_date','DESC')
            ->get();
            return view('pdf/Laporan_IctRequest_Permohonan', compact('ict'));
        }
    }
    public function cetak_excel_reviewer_ict_manager()
    {
        $newCreation = Carbon::parse($this->date)->copy()->tz('Asia/Jakarta')->format('d M Y');
        return Excel::download(new IctExportReviewerIctManager,'ICT REQUEST STATUS REPORT LIST ON '.$newCreation.'.xlsx');
    }
    Public function cetak_pdf_reviewer_reject()
    {
        if(Auth::user()->usr_loc == 'OJ'){
            $ict =  DB::table('ireq_mst as im')
            ->SELECT('im.ireq_no','im.ireq_reason','im.ireq_requestor','vr.name as ireq_bu','lr.lookup_desc as ireq_type','im.ireq_user','dr.div_name',
                    DB::raw("TO_CHAR(im.ireq_date,' dd Mon YYYY') as ireq_date"),'llr.lookup_desc as ireq_status')
            ->LEFTJOIN('vcompany_refs as vr','im.ireq_bu','vr.company_code')
            ->LEFTJOIN('lookup_refs as lr',function ($join) {
                $join->on('im.ireq_type','lr.lookup_code')
                    ->WHERERaw('LOWER(lr.lookup_type) LIKE ? ',[trim(strtolower('req_type')).'%']);
            })
            ->LEFTJOIN('divisi_refs as dr','im.ireq_divisi_user','dr.div_id')
            ->LEFTJOIN('lookup_refs as llr','im.ireq_status','llr.lookup_code')
            ->WHERE(function($query){
                return $query
                ->WHERE('im.ireq_status','RA2')
                ->orwhere('im.ireq_status','RA1')
                ->orwhere('im.ireq_status','RR');
            })
            ->WHERE('im.ireq_loc','OJ')
            ->WHERERaw('LOWER(llr.lookup_type) LIKE ? ',[trim(strtolower('ict_status')).'%'])
            ->ORDERBY('im.ireq_date','DESC')
            ->get();
            return view('pdf/Laporan_IctRequest_Reject', compact('ict'));
        }else if (Auth::user()->usr_loc == 'OB' OR Auth::user()->usr_loc == 'FB'){
                $ict =  DB::table('ireq_mst as im')
                ->SELECT('im.ireq_no','im.ireq_reason','im.ireq_requestor','vr.name as ireq_bu','lr.lookup_desc as ireq_type','im.ireq_user','dr.div_name',
                        DB::raw("TO_CHAR(im.ireq_date,' dd Mon YYYY') as ireq_date"),'llr.lookup_desc as ireq_status')
                ->LEFTJOIN('vcompany_refs as vr','im.ireq_bu','vr.company_code')
                ->LEFTJOIN('lookup_refs as lr',function ($join) {
                    $join->on('im.ireq_type','lr.lookup_code')
                        ->WHERERaw('LOWER(lr.lookup_type) LIKE ? ',[trim(strtolower('req_type')).'%']);
                })
                ->LEFTJOIN('divisi_refs as dr','im.ireq_divisi_user','dr.div_id')
                ->LEFTJOIN('lookup_refs as llr','im.ireq_status','llr.lookup_code')
                ->WHERE(function($query){
                    return $query
                    ->WHERE('im.ireq_status','RA2')
                    ->orwhere('im.ireq_status','RA1')
                    ->orwhere('im.ireq_status','RR');
                })
                ->WHERE(function ($query){
                    return $query
                    ->WHERE('im.ireq_loc','OB')
                    ->OrWhere('im.ireq_loc','FB');
                })
                ->WHERERaw('LOWER(llr.lookup_type) LIKE ? ',[trim(strtolower('ict_status')).'%'])
                ->ORDERBY('im.ireq_date','DESC')
                ->get();
                return view('pdf/Laporan_IctRequest_Reject', compact('ict'));
        }else if(Auth::user()->usr_loc == 'OK' OR Auth::user()->usr_loc == 'FK'){
            $ict =  DB::table('ireq_mst as im')
            ->SELECT('im.ireq_no','im.ireq_reason','im.ireq_requestor','vr.name as ireq_bu','lr.lookup_desc as ireq_type','im.ireq_user','dr.div_name',
                    DB::raw("TO_CHAR(im.ireq_date,' dd Mon YYYY') as ireq_date"),'llr.lookup_desc as ireq_status')
            ->LEFTJOIN('vcompany_refs as vr','im.ireq_bu','vr.company_code')
            ->LEFTJOIN('lookup_refs as lr',function ($join) {
                $join->on('im.ireq_type','lr.lookup_code')
                    ->WHERERaw('LOWER(lr.lookup_type) LIKE ? ',[trim(strtolower('req_type')).'%']);
            })
            ->LEFTJOIN('divisi_refs as dr','im.ireq_divisi_user','dr.div_id')
            ->LEFTJOIN('lookup_refs as llr','im.ireq_status','llr.lookup_code')
            ->WHERE(function($query){
                return $query
                ->WHERE('im.ireq_status','RA2')
                ->orwhere('im.ireq_status','RA1')
                ->orwhere('im.ireq_status','RR');
            })
            ->WHERE(function ($query){
                return $query
                ->WHERE('im.ireq_loc','OK')
                ->OrWhere('im.ireq_loc','FK');
            })
            ->WHERERaw('LOWER(llr.lookup_type) LIKE ? ',[trim(strtolower('ict_status')).'%'])
            ->ORDERBY('im.ireq_date','DESC')
            ->get();
            return view('pdf/Laporan_IctRequest_Reject', compact('ict'));
        }
    }
    public function cetak_excel_reviewer_reject()
    {
        $newCreation = Carbon::parse($this->date)->copy()->tz('Asia/Jakarta')->format('d M Y');
        return Excel::download(new IctExportRejectReviewer,'ICT REQUEST STATUS REPORT LIST ON '.$newCreation.'.xlsx');
    }
    Public function cetak_pdf_reviewer_assignment_request()
    {
        if(Auth::user()->usr_loc == 'OJ'){
            $ict =  DB::table('ireq_mst as im')
            ->SELECT('im.ireq_no','im.ireq_requestor','vr.name as ireq_bu','lr.lookup_desc as ireq_type','im.ireq_user','dr.div_name',
                    DB::raw("TO_CHAR(im.ireq_date,' dd Mon YYYY') as ireq_date"),'llr.lookup_desc as ireq_status',DB::raw("COALESCE(im.ireq_assigned_to2,im.ireq_assigned_to1) AS ireq_assigned_to"))
            ->LEFTJOIN('vcompany_refs as vr','im.ireq_bu','vr.company_code')
            ->LEFTJOIN('lookup_refs as lr',function ($join) {
                $join->on('im.ireq_type','lr.lookup_code')
                    ->WHERERaw('LOWER(lr.lookup_type) LIKE ? ',[trim(strtolower('req_type')).'%']);
            })
            ->LEFTJOIN('divisi_refs as dr','im.ireq_divisi_user','dr.div_id')
            ->LEFTJOIN('lookup_refs as llr','im.ireq_status','llr.lookup_code')
            ->WHERE(function($query){
                return $query
                ->WHERE('im.ireq_status','NT')
                ->orwhere('im.ireq_status','RT');
            })
            ->WHERE('im.ireq_loc','OJ')
            ->WHERERaw('LOWER(llr.lookup_type) LIKE ? ',[trim(strtolower('ict_status')).'%'])
            ->ORDERBY('im.ireq_date','DESC')
            ->get();
            return view('pdf/Laporan_IctRequest_Sedang_Dikerjakan', compact('ict'));
        }else if (Auth::user()->usr_loc == 'OB' OR Auth::user()->usr_loc == 'FB'){
                $ict =  DB::table('ireq_mst as im')
                ->SELECT('im.ireq_no','im.ireq_requestor','vr.name as ireq_bu','lr.lookup_desc as ireq_type','im.ireq_user','dr.div_name',
                        DB::raw("TO_CHAR(im.ireq_date,' dd Mon YYYY') as ireq_date"),'llr.lookup_desc as ireq_status',DB::raw("COALESCE(im.ireq_assigned_to2,im.ireq_assigned_to1) AS ireq_assigned_to"))
                ->LEFTJOIN('vcompany_refs as vr','im.ireq_bu','vr.company_code')
                ->LEFTJOIN('lookup_refs as lr',function ($join) {
                    $join->on('im.ireq_type','lr.lookup_code')
                        ->WHERERaw('LOWER(lr.lookup_type) LIKE ? ',[trim(strtolower('req_type')).'%']);
                })
                ->LEFTJOIN('divisi_refs as dr','im.ireq_divisi_user','dr.div_id')
                ->LEFTJOIN('lookup_refs as llr','im.ireq_status','llr.lookup_code')
                ->WHERE(function($query){
                    return $query
                    ->WHERE('im.ireq_status','RT')
                    ->orwhere('im.ireq_status','NT');
                })
                ->WHERE(function ($query){
                    return $query
                    ->WHERE('im.ireq_loc','OB')
                    ->OrWhere('im.ireq_loc','FB');
                })
                ->WHERERaw('LOWER(llr.lookup_type) LIKE ? ',[trim(strtolower('ict_status')).'%'])
                ->ORDERBY('im.ireq_date','DESC')
                ->get();
                return view('pdf/Laporan_IctRequest_Sedang_Dikerjakan', compact('ict'));
        }else if(Auth::user()->usr_loc == 'OK' OR Auth::user()->usr_loc == 'FK'){
            $ict =  DB::table('ireq_mst as im')
            ->SELECT('im.ireq_no','im.ireq_requestor','vr.name as ireq_bu','lr.lookup_desc as ireq_type','im.ireq_user','dr.div_name',
                    DB::raw("TO_CHAR(im.ireq_date,' dd Mon YYYY') as ireq_date"),'llr.lookup_desc as ireq_status',DB::raw("COALESCE(im.ireq_assigned_to2,im.ireq_assigned_to1) AS ireq_assigned_to"))
            ->LEFTJOIN('vcompany_refs as vr','im.ireq_bu','vr.company_code')
            ->LEFTJOIN('lookup_refs as lr',function ($join) {
                $join->on('im.ireq_type','lr.lookup_code')
                    ->WHERERaw('LOWER(lr.lookup_type) LIKE ? ',[trim(strtolower('req_type')).'%']);
            })
            ->LEFTJOIN('divisi_refs as dr','im.ireq_divisi_user','dr.div_id')
            ->LEFTJOIN('lookup_refs as llr','im.ireq_status','llr.lookup_code')
            ->WHERE(function($query){
                return $query
                ->WHERE('im.ireq_status','RT')
                ->orwhere('im.ireq_status','NT');
            })
            ->WHERE(function ($query){
                return $query
                ->WHERE('im.ireq_loc','OK')
                ->OrWhere('im.ireq_loc','FK');
            })
            ->WHERERaw('LOWER(llr.lookup_type) LIKE ? ',[trim(strtolower('ict_status')).'%'])
            ->ORDERBY('im.ireq_date','DESC')
            ->get();
            return view('pdf/Laporan_IctRequest_Sedang_Dikerjakan', compact('ict'));
        }
    }
    public function cetak_excel_reviewer_assignment_request()
    {
        $newCreation = Carbon::parse($this->date)->copy()->tz('Asia/Jakarta')->format('d M Y');
        return Excel::download(new IctExportReviewerAssignmentRequest,'ICT REQUEST STATUS REPORT LIST ON '.$newCreation.'.xlsx');
    }
    Public function cetak_pdf_reviewer_sedang_dikerjakan()
    {
        if(Auth::user()->usr_loc == 'OJ'){
            $ict =  DB::table('ireq_mst as im')
            ->SELECT('im.ireq_no','im.ireq_requestor','vr.name as ireq_bu','lr.lookup_desc as ireq_type','im.ireq_user','dr.div_name',
                    DB::raw("TO_CHAR(im.ireq_date,' dd Mon YYYY') as ireq_date"),'llr.lookup_desc as ireq_status',DB::raw("COALESCE(im.ireq_assigned_to2,im.ireq_assigned_to1) AS ireq_assigned_to"))
            ->LEFTJOIN('vcompany_refs as vr','im.ireq_bu','vr.company_code')
            ->LEFTJOIN('lookup_refs as lr',function ($join) {
                $join->on('im.ireq_type','lr.lookup_code')
                    ->WHERERaw('LOWER(lr.lookup_type) LIKE ? ',[trim(strtolower('req_type')).'%']);
            })
            ->LEFTJOIN('divisi_refs as dr','im.ireq_divisi_user','dr.div_id')
            ->LEFTJOIN('lookup_refs as llr','im.ireq_status','llr.lookup_code')
            ->WHERE('im.ireq_status','T')
            ->WHERE('im.ireq_loc','OJ')
            ->WHERERaw('LOWER(llr.lookup_type) LIKE ? ',[trim(strtolower('ict_status')).'%'])
            ->ORDERBY('im.ireq_date','DESC')
            ->get();
            return view('pdf/Laporan_IctRequest_Sedang_Dikerjakan', compact('ict'));
        }else if (Auth::user()->usr_loc == 'OB' OR Auth::user()->usr_loc == 'FB'){
                $ict =  DB::table('ireq_mst as im')
                ->SELECT('im.ireq_no','im.ireq_requestor','vr.name as ireq_bu','lr.lookup_desc as ireq_type','im.ireq_user','dr.div_name',
                        DB::raw("TO_CHAR(im.ireq_date,' dd Mon YYYY') as ireq_date"),'llr.lookup_desc as ireq_status',DB::raw("COALESCE(im.ireq_assigned_to2,im.ireq_assigned_to1) AS ireq_assigned_to"))
                ->LEFTJOIN('vcompany_refs as vr','im.ireq_bu','vr.company_code')
                ->LEFTJOIN('lookup_refs as lr',function ($join) {
                    $join->on('im.ireq_type','lr.lookup_code')
                        ->WHERERaw('LOWER(lr.lookup_type) LIKE ? ',[trim(strtolower('req_type')).'%']);
                })
                ->LEFTJOIN('divisi_refs as dr','im.ireq_divisi_user','dr.div_id')
                ->LEFTJOIN('lookup_refs as llr','im.ireq_status','llr.lookup_code')
                ->WHERE('im.ireq_status','T')
                ->WHERE(function ($query){
                    return $query
                    ->WHERE('im.ireq_loc','OB')
                    ->OrWhere('im.ireq_loc','FB');
                })
                ->WHERERaw('LOWER(llr.lookup_type) LIKE ? ',[trim(strtolower('ict_status')).'%'])
                ->ORDERBY('im.ireq_date','DESC')
                ->get();
                return view('pdf/Laporan_IctRequest_Sedang_Dikerjakan', compact('ict'));
        }else if(Auth::user()->usr_loc == 'OK' OR Auth::user()->usr_loc == 'FK'){
            $ict =  DB::table('ireq_mst as im')
            ->SELECT('im.ireq_no','im.ireq_requestor','vr.name as ireq_bu','lr.lookup_desc as ireq_type','im.ireq_user','dr.div_name',
                    DB::raw("TO_CHAR(im.ireq_date,' dd Mon YYYY') as ireq_date"),'llr.lookup_desc as ireq_status',DB::raw("COALESCE(im.ireq_assigned_to2,im.ireq_assigned_to1) AS ireq_assigned_to"))
            ->LEFTJOIN('vcompany_refs as vr','im.ireq_bu','vr.company_code')
            ->LEFTJOIN('lookup_refs as lr',function ($join) {
                $join->on('im.ireq_type','lr.lookup_code')
                    ->WHERERaw('LOWER(lr.lookup_type) LIKE ? ',[trim(strtolower('req_type')).'%']);
            })
            ->LEFTJOIN('divisi_refs as dr','im.ireq_divisi_user','dr.div_id')
            ->LEFTJOIN('lookup_refs as llr','im.ireq_status','llr.lookup_code')
            ->WHERE('im.ireq_status','T')
            ->WHERE(function ($query){
                return $query
                ->WHERE('im.ireq_loc','OK')
                ->OrWhere('im.ireq_loc','FK');
            })
            ->WHERERaw('LOWER(llr.lookup_type) LIKE ? ',[trim(strtolower('ict_status')).'%'])
            ->ORDERBY('im.ireq_date','DESC')
            ->get();
            return view('pdf/Laporan_IctRequest_Sedang_Dikerjakan', compact('ict'));
        }
    }
    public function cetak_excel_reviewer_sedang_dikerjakan()
    {
        $newCreation = Carbon::parse($this->date)->copy()->tz('Asia/Jakarta')->format('d M Y');
        return Excel::download(new IctExportReviewerSedangDikerjakan,'ICT REQUEST STATUS REPORT LIST ON '.$newCreation.'.xlsx');
    }
    Public function cetak_pdf_reviewer_sudah_dikerjakan()
    {
        if(Auth::user()->usr_loc == 'OJ'){
            $ict =  DB::table('ireq_dtl as id')
            ->SELECT('imm.ireq_no','id.ireq_desc','id.ireq_qty','id.ireq_remark','id.ireqd_id','dr.div_name',
                'imm.ireq_user','llr.lookup_desc as ireq_status', 'imm.ireq_requestor',
                'vr.name as ireq_bu','lr.lookup_desc as ireq_type',DB::raw("TO_CHAR(imm.ireq_date,' dd Mon YYYY') as ireq_date"),
                'lrs.lookup_desc as kategori',DB::raw("COALESCE(id.ireq_assigned_to2,id.ireq_assigned_to1) AS ireq_assigned_to"))
            ->LEFTJOIN('lookup_refs as lrs',function ($join) {
                $join->on('id.invent_code','lrs.lookup_code')
                    ->WHERERaw('LOWER(lrs.lookup_type) LIKE ? ',[trim(strtolower('kat_peripheral')).'%']);
            })
            ->LEFTJOIN('lookup_refs as lr','id.ireq_type','lr.lookup_code')
            ->join('ireq_mst as imm','id.ireq_id','imm.ireq_id')        
            ->LEFTJOIN('lookup_refs as llr','imm.ireq_status','llr.lookup_code')
            ->LEFTJOIN('vcompany_refs as vr','imm.ireq_bu','vr.company_code')
            ->LEFTJOIN('divisi_refs as dr','imm.ireq_divisi_user','dr.div_id')
            ->WHERE('id.ireq_status','D')
            ->WHERE('imm.ireq_loc','OJ')
            ->WHERERaw('LOWER(lr.lookup_type) LIKE ? ',[trim(strtolower('req_type')).'%'])
            ->WHERERaw('LOWER(llr.lookup_type) LIKE ? ',[trim(strtolower('ict_status')).'%'])
            ->ORDERBY('imm.ireq_date','DESC')
            ->ORDERBY('id.ireqd_id','ASC')
            ->get();
            return view('pdf/Laporan_IctRequest_Sudah_Dikerjakan', compact('ict'));
        }else if (Auth::user()->usr_loc == 'OB' OR Auth::user()->usr_loc == 'FB'){
            $ict =  DB::table('ireq_dtl as id')
            ->SELECT('imm.ireq_no','id.ireq_desc','id.ireq_qty','id.ireq_remark','id.ireqd_id','dr.div_name',
                'imm.ireq_user', 'llr.lookup_desc as ireq_status', 'imm.ireq_requestor',
                'vr.name as ireq_bu','lr.lookup_desc as ireq_type',DB::raw("TO_CHAR(imm.ireq_date,' dd Mon YYYY') as ireq_date"),
                'lrs.lookup_desc as kategori',DB::raw("COALESCE(id.ireq_assigned_to2,id.ireq_assigned_to1) AS ireq_assigned_to"))
            ->LEFTJOIN('lookup_refs as lrs',function ($join) {
                $join->on('id.invent_code','lrs.lookup_code')
                    ->WHERERaw('LOWER(lrs.lookup_type) LIKE ? ',[trim(strtolower('kat_peripheral')).'%']);
            })
            ->LEFTJOIN('lookup_refs as lr','id.ireq_type','lr.lookup_code')
            ->LEFTJOIN('ireq_mst as imm','id.ireq_id','imm.ireq_id')        
            ->LEFTJOIN('lookup_refs as llr','imm.ireq_status','llr.lookup_code')
            ->LEFTJOIN('vcompany_refs as vr','imm.ireq_bu','vr.company_code')
            ->LEFTJOIN('divisi_refs as dr','imm.ireq_divisi_user','dr.div_id')
            ->WHERE('id.ireq_status','D')
            ->WHERE(function ($query){
                return $query
                ->WHERE('imm.ireq_loc','OB')
                ->OrWhere('imm.ireq_loc','FB');
            })
            ->WHERERaw('LOWER(lr.lookup_type) LIKE ? ',[trim(strtolower('req_type')).'%'])
            ->WHERERaw('LOWER(llr.lookup_type) LIKE ? ',[trim(strtolower('ict_status')).'%'])
            ->ORDERBY('imm.ireq_date','DESC')
            ->ORDERBY('id.ireqd_id','ASC')
            ->get();
            return view('pdf/Laporan_IctRequest_Sudah_Dikerjakan', compact('ict'));
        }else if(Auth::user()->usr_loc == 'OK' OR Auth::user()->usr_loc == 'FK'){
            $ict =  DB::table('ireq_dtl as id')
            ->SELECT('imm.ireq_no','id.ireq_desc','id.ireq_qty','id.ireq_remark','id.ireqd_id','dr.div_name',
                'imm.ireq_user','llr.lookup_desc as ireq_status', 'imm.ireq_requestor',
                'vr.name as ireq_bu','lr.lookup_desc as ireq_type',DB::raw("TO_CHAR(imm.ireq_date,' dd Mon YYYY') as ireq_date"),
                'lrs.lookup_desc as kategori',DB::raw("COALESCE(id.ireq_assigned_to2,id.ireq_assigned_to1) AS ireq_assigned_to"))
            ->LEFTJOIN('lookup_refs as lrs',function ($join) {
                $join->on('id.invent_code','lrs.lookup_code')
                    ->WHERERaw('LOWER(lrs.lookup_type) LIKE ? ',[trim(strtolower('kat_peripheral')).'%']);
            })
            ->LEFTJOIN('lookup_refs as lr','id.ireq_type','lr.lookup_code')
            ->join('ireq_mst as imm','id.ireq_id','imm.ireq_id')        
            ->LEFTJOIN('lookup_refs as llr','imm.ireq_status','llr.lookup_code')
            ->LEFTJOIN('vcompany_refs as vr','imm.ireq_bu','vr.company_code')
            ->LEFTJOIN('divisi_refs as dr','imm.ireq_divisi_user','dr.div_id')
            ->WHERE('id.ireq_status','D')
            ->WHERE(function ($query){
                return $query
                ->WHERE('imm.ireq_loc','OK')
                ->OrWhere('imm.ireq_loc','FK');
            })
            ->WHERERaw('LOWER(lr.lookup_type) LIKE ? ',[trim(strtolower('req_type')).'%'])
            ->WHERERaw('LOWER(llr.lookup_type) LIKE ? ',[trim(strtolower('ict_status')).'%'])
            ->ORDERBY('imm.ireq_date','DESC')
            ->ORDERBY('id.ireqd_id','ASC')
            ->get();
            return view('pdf/Laporan_IctRequest_Sudah_Dikerjakan', compact('ict'));
        }
    }
    public function cetak_excel_reviewer_sudah_dikerjakan()
    {
        $newCreation = Carbon::parse($this->date)->copy()->tz('Asia/Jakarta')->format('d M Y');
        return Excel::download(new IctExportReviewerSudahDikerjakan,'ICT REQUEST STATUS REPORT LIST ON '.$newCreation.'.xlsx');
    }
    Public function cetak_pdf_reviewer_selesai()
    {
        if(Auth::user()->usr_loc == 'OJ'){
            $ict =  DB::table('ireq_dtl as id')
            ->SELECT('imm.ireq_no','id.ireq_desc','id.ireq_qty','id.ireq_remark','id.ireqd_id','dr.div_name',
                'imm.ireq_user','llr.lookup_desc as ireq_status', 'imm.ireq_requestor',
                'vr.name as ireq_bu','lr.lookup_desc as ireq_type',DB::raw("TO_CHAR(imm.ireq_date,' dd Mon YYYY') as ireq_date"),
                'lrs.lookup_desc as kategori',DB::raw("COALESCE(id.ireq_assigned_to2,id.ireq_assigned_to1) AS ireq_assigned_to"))
            ->LEFTJOIN('lookup_refs as lrs',function ($join) {
                $join->on('id.invent_code','lrs.lookup_code')
                    ->WHERERaw('LOWER(lrs.lookup_type) LIKE ? ',[trim(strtolower('kat_peripheral')).'%']);
            })
            ->LEFTJOIN('lookup_refs as lr','id.ireq_type','lr.lookup_code')
            ->join('ireq_mst as imm','id.ireq_id','imm.ireq_id')        
            ->LEFTJOIN('lookup_refs as llr','imm.ireq_status','llr.lookup_code')
            ->LEFTJOIN('vcompany_refs as vr','imm.ireq_bu','vr.company_code')
            ->LEFTJOIN('divisi_refs as dr','imm.ireq_divisi_user','dr.div_id')
            ->WHERE('id.ireq_status','C')
            ->WHERE('imm.ireq_loc','OJ')
            ->WHERERaw('LOWER(lr.lookup_type) LIKE ? ',[trim(strtolower('req_type')).'%'])
            ->WHERERaw('LOWER(llr.lookup_type) LIKE ? ',[trim(strtolower('ict_status')).'%'])
            ->ORDERBY('imm.ireq_date','DESC')
            ->ORDERBY('id.ireqd_id','ASC')
            ->get();
            return view('pdf/Laporan_IctRequest_Selesai', compact('ict'));
        }else if (Auth::user()->usr_loc == 'OB' OR Auth::user()->usr_loc == 'FB'){
            $ict =  DB::table('ireq_dtl as id')
            ->SELECT('imm.ireq_no','id.ireq_desc','id.ireq_qty','id.ireq_remark','id.ireqd_id','dr.div_name',
                'imm.ireq_user', 'llr.lookup_desc as ireq_status', 'imm.ireq_requestor',
                'vr.name as ireq_bu','lr.lookup_desc as ireq_type',DB::raw("TO_CHAR(imm.ireq_date,' dd Mon YYYY') as ireq_date"),
                'lrs.lookup_desc as kategori',DB::raw("COALESCE(id.ireq_assigned_to2,id.ireq_assigned_to1) AS ireq_assigned_to"))
            ->LEFTJOIN('lookup_refs as lrs',function ($join) {
                $join->on('id.invent_code','lrs.lookup_code')
                    ->WHERERaw('LOWER(lrs.lookup_type) LIKE ? ',[trim(strtolower('kat_peripheral')).'%']);
            })
            ->LEFTJOIN('lookup_refs as lr','id.ireq_type','lr.lookup_code')
            ->LEFTJOIN('ireq_mst as imm','id.ireq_id','imm.ireq_id')        
            ->LEFTJOIN('lookup_refs as llr','imm.ireq_status','llr.lookup_code')
            ->LEFTJOIN('vcompany_refs as vr','imm.ireq_bu','vr.company_code')
            ->LEFTJOIN('divisi_refs as dr','imm.ireq_divisi_user','dr.div_id')
            ->WHERE('id.ireq_status','C')
            ->WHERE(function ($query){
                return $query
                ->WHERE('imm.ireq_loc','OB')
                ->OrWhere('imm.ireq_loc','FB');
            })
            ->WHERERaw('LOWER(lr.lookup_type) LIKE ? ',[trim(strtolower('req_type')).'%'])
            ->WHERERaw('LOWER(llr.lookup_type) LIKE ? ',[trim(strtolower('ict_status')).'%'])
            ->ORDERBY('imm.ireq_date','DESC')
            ->ORDERBY('id.ireqd_id','ASC')
            ->get();
            return view('pdf/Laporan_IctRequest_Selesai', compact('ict'));
        }else if(Auth::user()->usr_loc == 'OK' OR Auth::user()->usr_loc == 'FK'){
            $ict =  DB::table('ireq_dtl as id')
            ->SELECT('imm.ireq_no','id.ireq_desc','id.ireq_qty','id.ireq_remark','id.ireqd_id','dr.div_name',
                'imm.ireq_user','llr.lookup_desc as ireq_status', 'imm.ireq_requestor',
                'vr.name as ireq_bu','lr.lookup_desc as ireq_type',DB::raw("TO_CHAR(imm.ireq_date,' dd Mon YYYY') as ireq_date"),
                'lrs.lookup_desc as kategori',DB::raw("COALESCE(id.ireq_assigned_to2,id.ireq_assigned_to1) AS ireq_assigned_to"))
            ->LEFTJOIN('lookup_refs as lrs',function ($join) {
                $join->on('id.invent_code','lrs.lookup_code')
                    ->WHERERaw('LOWER(lrs.lookup_type) LIKE ? ',[trim(strtolower('kat_peripheral')).'%']);
            })
            ->LEFTJOIN('lookup_refs as lr','id.ireq_type','lr.lookup_code')
            ->join('ireq_mst as imm','id.ireq_id','imm.ireq_id')        
            ->LEFTJOIN('lookup_refs as llr','imm.ireq_status','llr.lookup_code')
            ->LEFTJOIN('vcompany_refs as vr','imm.ireq_bu','vr.company_code')
            ->LEFTJOIN('divisi_refs as dr','imm.ireq_divisi_user','dr.div_id')
            ->WHERE('id.ireq_status','C')
            ->WHERE(function ($query){
                return $query
                ->WHERE('imm.ireq_loc','OK')
                ->OrWhere('imm.ireq_loc','FK');
            })
            ->WHERERaw('LOWER(lr.lookup_type) LIKE ? ',[trim(strtolower('req_type')).'%'])
            ->WHERERaw('LOWER(llr.lookup_type) LIKE ? ',[trim(strtolower('ict_status')).'%'])
            ->ORDERBY('imm.ireq_date','DESC')
            ->ORDERBY('id.ireqd_id','ASC')
            ->get();
            return view('pdf/Laporan_IctRequest_Selesai', compact('ict'));
        }
    }
    public function cetak_excel_reviewer_selesai()
    {
        $newCreation = Carbon::parse($this->date)->copy()->tz('Asia/Jakarta')->format('d M Y');
        return Excel::download(new IctExportReviewerSelesai,'ICT REQUEST STATUS REPORT LIST ON '.$newCreation.'.xlsx');
    }
    Public function cetak_pdf_manager_permohonan()
    {
        $ict =  DB::table('ireq_mst as im')
        ->SELECT('im.ireq_no','im.ireq_requestor','vr.name as ireq_bu','im.ireq_user','dr.div_name',
                DB::raw("TO_CHAR(im.ireq_date,' dd Mon YYYY') as ireq_date"),'llr.lookup_desc as ireq_status')
        ->LEFTJOIN('vcompany_refs as vr','im.ireq_bu','vr.company_code')
        ->LEFTJOIN('divisi_refs as dr','im.ireq_divisi_user','dr.div_id')
        ->LEFTJOIN('lookup_refs as llr','im.ireq_status','llr.lookup_code')
        ->WHERE(function($query){
            return $query
            ->WHERE('im.ireq_status','NA1')
            ->Orwhere('im.ireq_status','NA2')
            ->Orwhere('im.ireq_status','P');

        })
        ->WHERERaw('LOWER(llr.lookup_type) LIKE ? ',[trim(strtolower('ict_status')).'%'])
        ->ORDERBY('im.ireq_date','DESC')
        ->get();
        return view('pdf/Laporan_IctRequest_Permohonan', compact('ict'));
    }
    public function cetak_excel_manager_permohonan()
    {
        $newCreation = Carbon::parse($this->date)->copy()->tz('Asia/Jakarta')->format('d M Y');
        return Excel::download(new IctExportManagerPermohonan,'ICT REQUEST STATUS REPORT LIST ON '.$newCreation.'.xlsx');
    }
    Public function cetak_pdf_manager_verifikasi()
    {
        $ict =  DB::table('ireq_mst as im')
        ->SELECT('im.ireq_no','im.ireq_requestor','vr.name as ireq_bu','im.ireq_user','dr.div_name',
                DB::raw("TO_CHAR(im.ireq_date,' dd Mon YYYY') as ireq_date"),'llr.lookup_desc as ireq_status')
        ->LEFTJOIN('vcompany_refs as vr','im.ireq_bu','vr.company_code')
        ->LEFTJOIN('lookup_refs as llr','im.ireq_status','llr.lookup_code')
        ->LEFTJOIN('divisi_refs as dr','im.ireq_divisi_user','dr.div_id')
        ->WHERE(function($query){
            return $query
            ->WHERE('im.ireq_status','A1')
            ->orwhere('im.ireq_status','A2');
        })
        ->WHERERaw('LOWER(llr.lookup_type) LIKE ? ',[trim(strtolower('ict_status')).'%'])
        ->ORDERBY('im.ireq_date','DESC')
        ->get();
        return view('pdf/Laporan_IctRequest_Permohonan', compact('ict'));
    }
    public function cetak_excel_manager_verifikasi()
    {
        $newCreation = Carbon::parse($this->date)->copy()->tz('Asia/Jakarta')->format('d M Y');
        return Excel::download(new IctExportVerifikasiManager(),'ICT REQUEST STATUS REPORT LIST ON '.$newCreation.'.xlsx');
    }
    Public function cetak_pdf_manager_reject()
    {
        $ict =  DB::table('ireq_mst as im')
        ->SELECT('im.ireq_no','im.ireq_requestor','vr.name as ireq_bu','im.ireq_reason','lr.lookup_desc as ireq_type','im.ireq_user','dr.div_name',
                DB::raw("TO_CHAR(im.ireq_date,' dd Mon YYYY') as ireq_date"),'llr.lookup_desc as ireq_status')
        ->LEFTJOIN('vcompany_refs as vr','im.ireq_bu','vr.company_code')
        ->LEFTJOIN('lookup_refs as lr',function ($join) {
            $join->on('im.ireq_type','lr.lookup_code')
                  ->WHERERaw('LOWER(lr.lookup_type) LIKE ? ',[trim(strtolower('req_type')).'%']);
        })
        ->LEFTJOIN('lookup_refs as llr','im.ireq_status','llr.lookup_code')
        ->LEFTJOIN('divisi_refs as dr','im.ireq_divisi_user','dr.div_id')
        ->WHERE(function($query){
            return $query
            ->WHERE('im.ireq_status','RR')
            ->orwhere('im.ireq_status','RA1')
            ->orwhere('im.ireq_status','RA2');
        })
        ->WHERERaw('LOWER(llr.lookup_type) LIKE ? ',[trim(strtolower('ict_status')).'%'])
        ->ORDERBY('im.ireq_date','DESC')
        ->get();
        return view('pdf/Laporan_IctRequest_Reject', compact('ict'));
    }
    public function cetak_excel_manager_reject()
    {
        $newCreation = Carbon::parse($this->date)->copy()->tz('Asia/Jakarta')->format('d M Y');
        return Excel::download(new IctExportRejectManager,'ICT REQUEST STATUS REPORT LIST ON '.$newCreation.'.xlsx');
    }
    function cetak_pdf_manager_assignment_request()
    {
        $ict =  DB::table('ireq_mst as im')
        ->SELECT('im.ireq_no','im.ireq_requestor','vr.name as ireq_bu','im.ireq_reason','im.ireq_user','dr.div_name',DB::raw("COALESCE(im.ireq_assigned_to2,im.ireq_assigned_to1) AS ireq_assigned_to"),
                DB::raw("TO_CHAR(im.ireq_date,' dd Mon YYYY') as ireq_date"),'llr.lookup_desc as ireq_status')
        ->LEFTJOIN('vcompany_refs as vr','im.ireq_bu','vr.company_code')
        ->LEFTJOIN('lookup_refs as llr','im.ireq_status','llr.lookup_code')
        ->LEFTJOIN('divisi_refs as dr','im.ireq_divisi_user','dr.div_id')
        ->WHERE(function($query){
            return $query
            ->WHERE('im.ireq_status','NT')
            ->Orwhere('im.ireq_status','RT');
        })
        ->WHERERaw('LOWER(llr.lookup_type) LIKE ? ',[trim(strtolower('ict_status')).'%'])
        ->ORDERBY('im.ireq_date','DESC')
        ->get();
        return view('pdf/Laporan_IctRequest_Sedang_Dikerjakan', compact('ict'));
    }
    function cetak_excel_manager_assignment_request()
    {
        $newCreation = Carbon::parse($this->date)->copy()->tz('Asia/Jakarta')->format('d M Y');
        return Excel::download(new IctExportManagerAssignmentRequest,'ICT REQUEST STATUS REPORT LIST ON '.$newCreation.'.xlsx');
    }
    Public function cetak_pdf_manager_sedang_dikerjakan()
    {
        $ict =  DB::table('ireq_mst as im')
        ->SELECT('im.ireq_no','im.ireq_requestor','vr.name as ireq_bu','im.ireq_reason','im.ireq_user','dr.div_name',DB::raw("COALESCE(im.ireq_assigned_to2,im.ireq_assigned_to1) AS ireq_assigned_to"),
                DB::raw("TO_CHAR(im.ireq_date,' dd Mon YYYY') as ireq_date"),'llr.lookup_desc as ireq_status')
        ->LEFTJOIN('vcompany_refs as vr','im.ireq_bu','vr.company_code')
        ->LEFTJOIN('lookup_refs as llr','im.ireq_status','llr.lookup_code')
        ->LEFTJOIN('divisi_refs as dr','im.ireq_divisi_user','dr.div_id')
        ->WHERE('im.ireq_status','T')
        ->WHERERaw('LOWER(llr.lookup_type) LIKE ? ',[trim(strtolower('ict_status')).'%'])
        ->ORDERBY('im.ireq_date','DESC')
        ->get();
        return view('pdf/Laporan_IctRequest_Sedang_Dikerjakan', compact('ict'));
    }
    public function cetak_excel_manager_sedang_dikerjakan()
    {
        $newCreation = Carbon::parse($this->date)->copy()->tz('Asia/Jakarta')->format('d M Y');
        return Excel::download(new IctExportManagerSedangDikerjakan,'ICT REQUEST STATUS REPORT LIST ON '.$newCreation.'.xlsx');
    }
    Public function cetak_pdf_manager_sudah_dikerjakan()
    {
        $ict =  DB::table('ireq_dtl as id')
        ->LEFTJOIN('ireq_mst as im','id.ireq_id','im.ireq_id')
        ->LEFTJOIN('vcompany_refs as vr','im.ireq_bu','vr.company_code')
        ->LEFTJOIN('divisi_refs as dr','im.ireq_divisi_user','dr.div_id')
        ->LEFTJOIN('lookup_refs as lr',function ($join) {
            $join->on('id.ireq_status','lr.lookup_code')
                  ->WHERERaw('LOWER(lr.lookup_type) LIKE ? ',[trim(strtolower('ict_status')).'%']);
        })
        ->LEFTJOIN('lookup_refs as lrs',function ($join) {
            $join->on('id.ireq_type','lrs.lookup_code')
                  ->WHERERaw('LOWER(lrs.lookup_type) LIKE ? ',[trim(strtolower('req_type')).'%']);
        })
        ->LEFTJOIN('lookup_refs as lrfs',function ($join) {
            $join->on('id.invent_code','lrfs.lookup_code')
                  ->WHERERaw('LOWER(lrfs.lookup_type) LIKE ? ',[trim(strtolower('kat_peripheral')).'%']);
        })
        ->SELECT('vr.name as ireq_bu','im.ireq_no','id.ireq_id','id.ireq_remark',DB::raw("COALESCE(id.ireq_assigned_to2,id.ireq_assigned_to1) AS ireq_assigned_to"),'id.ireqd_id',
        'lr.lookup_desc as ireq_status','lrs.lookup_desc as ireq_type','lrfs.lookup_desc as kategori',DB::raw("TO_CHAR(im.ireq_date,' dd Mon YYYY') as ireq_date"),'im.ireq_requestor','im.ireq_user',
        'dr.div_name','id.ireq_qty','id.ireq_status as status')
        ->WHERE('id.ireq_status','D')
        ->ORDERBY('im.ireq_date','DESC')
        ->ORDERBY('id.ireqd_id','ASC')
        ->get();
        return view('pdf/Laporan_IctRequest_Sudah_Dikerjakan', compact('ict'));
    }
    public function cetak_excel_manager_sudah_dikerjakan()
    {
        $newCreation = Carbon::parse($this->date)->copy()->tz('Asia/Jakarta')->format('d M Y');
        return Excel::download(new IctExportManagerSudahDikerjakan,'ICT REQUEST STATUS REPORT LIST ON '.$newCreation.'.xlsx');
    }
    Public function cetak_pdf_manager_selesai()
    {
        $ict =  DB::table('ireq_dtl as id')
        ->LEFTJOIN('ireq_mst as im','id.ireq_id','im.ireq_id')
        ->LEFTJOIN('divisi_refs as dr','im.ireq_divisi_user','dr.div_id')
        ->LEFTJOIN('lookup_refs as lr',function ($join) {
            $join->on('id.ireq_status','lr.lookup_code')
                  ->WHERERaw('LOWER(lr.lookup_type) LIKE ? ',[trim(strtolower('ict_status')).'%']);
        })
        ->LEFTJOIN('lookup_refs as lrs',function ($join) {
            $join->on('id.ireq_type','lrs.lookup_code')
                  ->WHERERaw('LOWER(lrs.lookup_type) LIKE ? ',[trim(strtolower('req_type')).'%']);
        })
        ->LEFTJOIN('lookup_refs as lrfs',function ($join) {
            $join->on('id.invent_code','lrfs.lookup_code')
                  ->WHERERaw('LOWER(lrfs.lookup_type) LIKE ? ',[trim(strtolower('kat_peripheral')).'%']);
        })
        ->SELECT('im.ireq_no','id.ireq_id','id.ireq_remark',DB::raw("COALESCE(id.ireq_assigned_to2,id.ireq_assigned_to1) AS ireq_assigned_to"),'id.ireqd_id',
        'lr.lookup_desc as ireq_status','lrs.lookup_desc as ireq_type','lrfs.lookup_desc as kategori',DB::raw("TO_CHAR(im.ireq_date,' dd Mon YYYY') as ireq_date"),'im.ireq_requestor','im.ireq_user',
        'dr.div_name','id.ireq_qty','id.ireq_status as status')
        ->WHERE('id.ireq_status','C')
        ->ORDERBY('im.ireq_date','DESC')
        ->ORDERBY('id.ireqd_id','ASC')
        ->get();
        return view('pdf/Laporan_IctRequest_Sudah_Dikerjakan', compact('ict'));
    }
    public function cetak_excel_manager_selesai()
    {
        $newCreation = Carbon::parse($this->date)->copy()->tz('Asia/Jakarta')->format('d M Y');
        return Excel::download(new IctExportManagerSudahSelesai,'ICT REQUEST STATUS REPORT LIST ON '.$newCreation.'.xlsx');
    }
    function cetak_pdf_personnel_assignment_request()
    {
        $ict =  DB::table('ireq_mst as im')
        ->SELECT('im.ireq_no','im.ireq_requestor','vr.name as ireq_bu','im.ireq_reason','im.ireq_user','dr.div_name','im.ireq_assigned_to1 AS ireq_assigned_to',
                DB::raw("TO_CHAR(im.ireq_date,' dd Mon YYYY') as ireq_date"),'llr.lookup_desc as ireq_status')
        ->LEFTJOIN('vcompany_refs as vr','im.ireq_bu','vr.company_code')
        ->LEFTJOIN('ireq_dtl as id','im.ireq_id','id.ireq_id')
        ->LEFTJOIN('lookup_refs as llr','im.ireq_status','llr.lookup_code')
        ->LEFTJOIN('divisi_refs as dr','im.ireq_divisi_user','dr.div_id')
        ->WHERE('id.ireq_status','NT')
        ->WHERERaw('LOWER(llr.lookup_type) LIKE ? ',[trim(strtolower('ict_status')).'%'])
        ->WHERE('id.ireq_assigned_to1',Auth::user()->usr_fullname)
        ->ORDERBY('im.ireq_date','DESC')
        ->get();
        return view('pdf/Laporan_IctRequest_Sedang_Dikerjakan', compact('ict'));
    }
    function cetak_excel_personnel_assignment_request()
    {
        $usr_fullname = Auth::user()->usr_fullname;
        $newCreation = Carbon::parse($this->date)->copy()->tz('Asia/Jakarta')->format('d M Y');
        return Excel::download(new IctExportPersonnelAssignmentRequest($usr_fullname),'ICT REQUEST STATUS REPORT LIST ON '.$newCreation.'.xlsx');
    }
    function cetak_pdf_personnel_reject()
    {
        $ict =  DB::table('ireq_dtl as id')
        ->SELECT('imm.ireq_no','id.ireq_assigned_to1_reason','imm.ireq_id','id.ireq_assigned_remark','id.ireq_desc','id.ireq_qty','id.ireq_remark','id.ireqd_id','dr.div_name',
            'imm.ireq_user', DB::raw("COALESCE(id.ireq_assigned_to2,id.ireq_assigned_to1) AS ireq_assigned_to"),'llr.lookup_desc as ireq_status', 'imm.ireq_requestor',
            'vr.name as ireq_bu','lr.lookup_desc as ireq_type','imm.ireq_date','id.ireq_status as status', 'lrs.lookup_desc as kategori')
        ->LEFTJOIN('lookup_refs as lrs',function ($join) {
            $join->on('id.invent_code','lrs.lookup_code')
            ->WHERERaw('LOWER(lrs.lookup_type) LIKE ? ',[trim(strtolower('kat_peripheral')).'%']);
        })
        ->LEFTJOIN('lookup_refs as lr','id.ireq_type','lr.lookup_code')
        ->LEFTJOIN('ireq_mst as imm','id.ireq_id','imm.ireq_id')        
        ->LEFTJOIN('lookup_refs as llr','id.ireq_status','llr.lookup_code')
        ->LEFTJOIN('vcompany_refs as vr','imm.ireq_bu','vr.company_code')
        ->LEFTJOIN('divisi_refs as dr','imm.ireq_divisi_user','dr.div_id')
        ->WHERE('id.ireq_status','RT')
        ->WHERE('id.ireq_assigned_to1',Auth::user()->usr_fullname)
        ->WHERERaw('LOWER(lr.lookup_type) LIKE ? ',[trim(strtolower('req_type')).'%'])
        ->WHERERaw('LOWER(llr.lookup_type) LIKE ? ',[trim(strtolower('ict_status')).'%'])
        ->ORDERBY('imm.ireq_date','DESC')
        ->ORDERBY('id.ireqd_id','ASC')
     ->get();
        return view('pdf/Laporan_IctDetailReject', compact('ict'));
    }
    function cetak_excel_personnel_reject()
    {
        $usr_fullname = Auth::user()->usr_fullname;
        $newCreation = Carbon::parse($this->date)->copy()->tz('Asia/Jakarta')->format('d M Y');
        return Excel::download(new IctExportPersonnelReject($usr_fullname),'ICT REQUEST STATUS REPORT LIST ON '.$newCreation.'.xlsx');
    }
    function cetak_pdf_personnel_sedang_dikerjakan()
    {
        $ict =  DB::table('ireq_dtl as id')
        ->LEFTJOIN('ireq_mst as im','id.ireq_id','im.ireq_id')
        ->LEFTJOIN('vcompany_refs as vr','im.ireq_bu','vr.company_code')
        ->LEFTJOIN('divisi_refs as dr','im.ireq_divisi_user','dr.div_id')
        ->LEFTJOIN('lookup_refs as lr',function ($join) {
            $join->on('id.ireq_status','lr.lookup_code')
                  ->WHERERaw('LOWER(lr.lookup_type) LIKE ? ',[trim(strtolower('ict_status')).'%']);
        })
        ->LEFTJOIN('lookup_refs as lrs',function ($join) {
            $join->on('id.ireq_type','lrs.lookup_code')
                  ->WHERERaw('LOWER(lrs.lookup_type) LIKE ? ',[trim(strtolower('req_type')).'%']);
        })
        ->LEFTJOIN('lookup_refs as lrfs',function ($join) {
            $join->on('id.invent_code','lrfs.lookup_code')
                  ->WHERERaw('LOWER(lrfs.lookup_type) LIKE ? ',[trim(strtolower('kat_peripheral')).'%']);
        })
        ->SELECT('vr.name as ireq_bu','im.ireq_no','id.ireq_id','id.ireq_remark',DB::raw("COALESCE(id.ireq_assigned_to2,id.ireq_assigned_to1) AS ireq_assigned_to"),'id.ireqd_id',
        'lr.lookup_desc as ireq_status','lrs.lookup_desc as ireq_type','lrfs.lookup_desc as kategori',DB::raw("TO_CHAR(im.ireq_date,' dd Mon YYYY') as ireq_date"),'im.ireq_requestor','im.ireq_user',
        'dr.div_name','id.ireq_qty','id.ireq_status as status')
        ->WHERE('id.ireq_status','T')
        ->WHERE(DB::raw("COALESCE(id.ireq_assigned_to2,id.ireq_assigned_to1)"),Auth::user()->usr_fullname)
        ->ORDERBY('im.ireq_date','DESC')
        ->ORDERBY('id.ireqd_id','ASC')
        ->get();
        return view('pdf/Laporan_IctRequest_Sudah_Dikerjakan', compact('ict'));
    }
    public function cetak_excel_personnel_sedang_dikerjakan()
    {
        $usr_fullname = Auth::user()->usr_fullname;
        $newCreation = Carbon::parse($this->date)->copy()->tz('Asia/Jakarta')->format('d M Y');
        return Excel::download(new IctExportPersonnelSedangDikerjakan($usr_fullname),'ICT REQUEST STATUS REPORT LIST ON '.$newCreation.'.xlsx');
    }
    Public function cetak_pdf_personnel_sudah_dikerjakan()
    {
        $ict =  DB::table('ireq_dtl as id')
        ->LEFTJOIN('ireq_mst as im','id.ireq_id','im.ireq_id')
        ->LEFTJOIN('vcompany_refs as vr','im.ireq_bu','vr.company_code')
        ->LEFTJOIN('divisi_refs as dr','im.ireq_divisi_user','dr.div_id')
        ->LEFTJOIN('lookup_refs as lr',function ($join) {
            $join->on('id.ireq_status','lr.lookup_code')
                  ->WHERERaw('LOWER(lr.lookup_type) LIKE ? ',[trim(strtolower('ict_status')).'%']);
        })
        ->LEFTJOIN('lookup_refs as lrs',function ($join) {
            $join->on('id.ireq_type','lrs.lookup_code')
                  ->WHERERaw('LOWER(lrs.lookup_type) LIKE ? ',[trim(strtolower('req_type')).'%']);
        })
        ->LEFTJOIN('lookup_refs as lrfs',function ($join) {
            $join->on('id.invent_code','lrfs.lookup_code')
                  ->WHERERaw('LOWER(lrfs.lookup_type) LIKE ? ',[trim(strtolower('kat_peripheral')).'%']);
        })
        ->SELECT('vr.name as ireq_bu','im.ireq_no','id.ireq_id','id.ireq_remark',DB::raw("COALESCE(id.ireq_assigned_to2,id.ireq_assigned_to1) AS ireq_assigned_to"),'id.ireqd_id',
        'lr.lookup_desc as ireq_status','lrs.lookup_desc as ireq_type','lrfs.lookup_desc as kategori',DB::raw("TO_CHAR(im.ireq_date,' dd Mon YYYY') as ireq_date"),'im.ireq_requestor','im.ireq_user',
        'dr.div_name','id.ireq_qty','id.ireq_status as status')
        ->WHERE('id.ireq_status','D')
        ->WHERE(DB::raw("COALESCE(id.ireq_assigned_to2,id.ireq_assigned_to1)"),Auth::user()->usr_fullname)
        ->ORDERBY('im.ireq_date','DESC')
        ->ORDERBY('id.ireqd_id','ASC')
        ->get();
        return view('pdf/Laporan_IctRequest_Sudah_Dikerjakan', compact('ict'));
    }
    public function cetak_excel_personnel_sudah_dikerjakan()
    {
        $usr_fullname = AUth::user()->usr_fullname;
        $newCreation = Carbon::parse($this->date)->copy()->tz('Asia/Jakarta')->format('d M Y');
        return Excel::download(new IctExportPersonnelSudahDikerjakan($usr_fullname),'ICT REQUEST STATUS REPORT LIST ON '.$newCreation.'.xlsx');
    }
    Public function cetak_pdf_personnel_selesai()
    {
        $ict =  DB::table('ireq_dtl as id')
        ->LEFTJOIN('ireq_mst as im','id.ireq_id','im.ireq_id')
        ->LEFTJOIN('vcompany_refs as vr','im.ireq_bu','vr.company_code')
        ->LEFTJOIN('divisi_refs as dr','im.ireq_divisi_user','dr.div_id')
        ->LEFTJOIN('lookup_refs as lr',function ($join) {
            $join->on('id.ireq_status','lr.lookup_code')
                  ->WHERERaw('LOWER(lr.lookup_type) LIKE ? ',[trim(strtolower('ict_status')).'%']);
        })
        ->LEFTJOIN('lookup_refs as lrs',function ($join) {
            $join->on('id.ireq_type','lrs.lookup_code')
                  ->WHERERaw('LOWER(lrs.lookup_type) LIKE ? ',[trim(strtolower('req_type')).'%']);
        })
        ->LEFTJOIN('lookup_refs as lrfs',function ($join) {
            $join->on('id.invent_code','lrfs.lookup_code')
                  ->WHERERaw('LOWER(lrfs.lookup_type) LIKE ? ',[trim(strtolower('kat_peripheral')).'%']);
        })
        ->SELECT('vr.name as ireq_bu','im.ireq_no','id.ireq_id','id.ireq_remark',DB::raw("COALESCE(id.ireq_assigned_to2,id.ireq_assigned_to1) AS ireq_assigned_to"),'id.ireqd_id',
        'lr.lookup_desc as ireq_status','lrs.lookup_desc as ireq_type','lrfs.lookup_desc as kategori',DB::raw("TO_CHAR(im.ireq_date,' dd Mon YYYY') as ireq_date"),'im.ireq_requestor','im.ireq_user',
        'dr.div_name','id.ireq_qty','id.ireq_status as status')
        ->WHERE('id.ireq_status','C')
        ->WHERE(DB::raw("COALESCE(id.ireq_assigned_to2,id.ireq_assigned_to1)"),Auth::user()->usr_fullname)
        ->ORDERBY('im.ireq_date','DESC')
        ->ORDERBY('id.ireqd_id','ASC')
        ->get();
        return view('pdf/Laporan_IctRequest_Selesai', compact('ict'));
    }
    public function cetak_excel_personnel_selesai()
    {
        $usr_fullname = Auth::user()->usr_fullname;
        $newCreation = Carbon::parse($this->date)->copy()->tz('Asia/Jakarta')->format('d M Y');
        return Excel::download(new IctExportPersonnelSelesai($usr_fullname),'ICT REQUEST STATUS REPORT LIST ON '.$newCreation.'.xlsx');
    }
    Public function cetak_pdf_permohonan()
    {
            $ict =  DB::table('ireq_mst as id')
            ->LEFTJOIN('vcompany_refs as vr','id.ireq_bu','vr.company_code')
            ->LEFTJOIN('ireq_dtl as idm','id.ireq_id','idm.ireq_id')
            ->LEFTJOIN('lookup_refs as lr','id.ireq_status','lr.lookup_code')
            ->LEFTJOIN('lookup_refs as llr',function ($join) {
                $join->on('id.ireq_status','llr.lookup_code')
                    ->WHERERaw('LOWER(llr.lookup_type) LIKE ? ',[trim(strtolower('ict_status')).'%']);
            })
            ->LEFTJOIN('divisi_refs as dr','id.ireq_divisi_user','dr.div_id')
            ->SELECT('vr.name as ireq_bu','id.ireq_id','id.ireq_no',
             DB::raw("TO_CHAR(id.ireq_date,' dd Mon YYYY') as ireq_date"),'id.ireq_user','dr.div_name',
            'id.ireq_requestor',DB::raw('count(DISTINCT(idm.ireq_id)) as count'),'llr.lookup_desc as ireq_status')
            ->WHERE('id.created_by',Auth::user()->usr_name)
            ->WHERE('id.ireq_status','P')
            ->groupBy('vr.name','llr.lookup_desc','id.ireq_id','id.ireq_no','id.ireq_user',
            'id.creation_date','dr.div_name','id.ireq_requestor','id.ireq_date')
            ->ORDERBY('id.ireq_date','DESC')
            ->get();
            return view('pdf/Laporan_IctRequest_Permohonan', compact('ict'));
    }
    public function cetak_excel_permohonan()
    {
        $usr_name = Auth::user()->usr_name;
        $newCreation = Carbon::parse($this->date)->copy()->tz('Asia/Jakarta')->format('d M Y');
        return Excel::download(new IctExportPermohonan($usr_name),'ICT REQUEST STATUS REPORT LIST ON '.$newCreation.'.xlsx');
    }
    Public function cetak_pdf_tab_reviewer()
    {
        $ict =  DB::table('ireq_mst as im')
        ->LEFTJOIN('vcompany_refs as vr','im.ireq_bu','vr.company_code')
        ->LEFTJOIN('lookup_refs as lr','im.ireq_status','lr.lookup_code')
        ->LEFTJOIN('divisi_refs as dr','im.ireq_divisi_user','dr.div_id')
        ->SELECT('vr.name as ireq_bu','im.ireq_requestor','im.ireq_status as status','dr.div_name','im.ireq_id','im.ireq_no',DB::raw("TO_CHAR(im.ireq_date,' dd Mon YYYY') as ireq_date"),'im.ireq_user','lr.lookup_desc as ireq_status')
        ->WHERE('im.created_by',Auth::user()->usr_name)
        ->WHERERaw('LOWER(lr.lookup_type) LIKE ? ',[trim(strtolower('ict_status')).'%'])
        ->WHERE(function($query){
            return $query
            ->WHERE('im.ireq_status','NA1')
            ->orwhere('im.ireq_status','NA2');
        })            
        ->ORDERBY('im.ireq_date','DESC')
        ->get();
        return view('pdf/Laporan_IctRequest_Tab_Reviewer', compact('ict'));
    }
    public function cetak_excel_tab_reviewer()
    {
        $usr_name = Auth::user()->usr_name;
        $newCreation = Carbon::parse($this->date)->copy()->tz('Asia/Jakarta')->format('d M Y');
        return Excel::download(new IctExportTabReviewer($usr_name),'ICT REQUEST STATUS REPORT LIST ON (Reviewer) '.$newCreation.'.xlsx');
    }
    Public function cetak_pdf_verifikasi()
    {
        $ict =  DB::table('ireq_mst as im')
        ->LEFTJOIN('vcompany_refs as vr','im.ireq_bu','vr.company_code')
        ->LEFTJOIN('lookup_refs as lr','im.ireq_status','lr.lookup_code')
        ->LEFTJOIN('divisi_refs as dr','im.ireq_divisi_user','dr.div_id')
        ->SELECT('vr.name as ireq_bu','im.ireq_requestor','im.ireq_status as status','dr.div_name','im.ireq_id','im.ireq_no',DB::raw("TO_CHAR(im.ireq_date,' dd Mon YYYY') as ireq_date"),'im.ireq_user','lr.lookup_desc as ireq_status')
        ->WHERE('im.created_by',Auth::user()->usr_name)
        ->WHERERaw('LOWER(lr.lookup_type) LIKE ? ',[trim(strtolower('ict_status')).'%'])
        ->WHERE(function($query){
            return $query
            ->WHERE('im.ireq_status','A1')
            ->orwhere('im.ireq_status','A2');
        })            
        ->ORDERBY('im.ireq_date','DESC')
        ->get();
        return view('pdf/Laporan_IctRequest_Verifikasi', compact('ict'));
    }
    public function cetak_excel_verifikasi()
    {
        $usr_name = Auth::user()->usr_name;
        $newCreation = Carbon::parse($this->date)->copy()->tz('Asia/Jakarta')->format('d M Y');
        return Excel::download(new IctExportVerifikasi($usr_name),'ICT REQUEST STATUS REPORT LIST ON (Terverifikasi) '.$newCreation.'.xlsx');
    }
    Public function cetak_pdf_reject()
    {
        $ict =  DB::table('ireq_mst as im')
        ->LEFTJOIN('vcompany_refs as vr','im.ireq_bu','vr.company_code')
        ->LEFTJOIN('lookup_refs as lr','im.ireq_status','lr.lookup_code')
        ->LEFTJOIN('divisi_refs as dr','im.ireq_divisi_user','dr.div_id')
        ->SELECT('im.ireq_reason','vr.name as ireq_bu','im.ireq_requestor','im.ireq_status as status','dr.div_name','im.ireq_id','im.ireq_no',DB::raw("TO_CHAR(im.ireq_date,' dd Mon YYYY') as ireq_date"),'im.ireq_user','lr.lookup_desc as ireq_status')
        ->WHERE('im.created_by',Auth::user()->usr_name)
        ->WHERERaw('LOWER(lr.lookup_type) LIKE ? ',[trim(strtolower('ict_status')).'%'])
        ->WHERE(function($query){
            return $query
            ->WHERE('im.ireq_status','RA1')
            ->orwhere('im.ireq_status','RA2')
            ->orwhere('im.ireq_status','RR');
        })            
        ->ORDERBY('im.ireq_date','DESC')
        ->get();
        return view('pdf/Laporan_IctRequest_Reject', compact('ict'));
    }
    public function cetak_excel_reject()
    {
        $usr_name = Auth::user()->usr_name;
        $newCreation = Carbon::parse($this->date)->copy()->tz('Asia/Jakarta')->format('d M Y');
        return Excel::download(new IctExportReject($usr_name),'ICT REQUEST STATUS REPORT LIST ON (Direject) '.$newCreation.'.xlsx');
    }
    function cetak_pdf_assignment_request(){
        $ict = DB::table('ireq_mst as im')
            ->SELECT('vr.name as ireq_bu','im.ireq_requestor','im.ireq_id','im.ireq_status as status','im.ireq_no',DB::raw("TO_CHAR(im.ireq_date,' dd Mon YYYY') as ireq_date"),'im.ireq_user','im.ireq_requestor','dr.div_name',DB::raw("COALESCE(im.ireq_assigned_to2,im.ireq_assigned_to1) AS ireq_assigned_to"),'lr.lookup_desc as ireq_status')
            ->LEFTJOIN('divisi_refs as dr','im.ireq_divisi_user','dr.div_id')
            ->LEFTJOIN('vcompany_refs as vr','im.ireq_bu','vr.company_code')
            ->LEFTJOIN('lookup_refs as lr','im.ireq_status','lr.lookup_code')
            ->WHERE(function($query){
                return $query
                ->WHERE('im.ireq_status','NT')
                ->orWhere('im.ireq_status','RT');
                })
            ->WHERERaw('LOWER(lr.lookup_type) LIKE ? ',[trim(strtolower('ict_status')).'%'])
            ->WHERE('im.created_by',Auth::user()->usr_name)
            ->groupBy('vr.name','im.ireq_requestor','im.ireq_status','im.ireq_id','im.ireq_no','im.ireq_date','im.ireq_user','im.ireq_requestor','dr.div_name','im.creation_date','lr.lookup_desc',DB::raw("COALESCE(im.ireq_assigned_to2,im.ireq_assigned_to1)"))          
            ->ORDERBY('im.ireq_date','DESC')
            ->get();
            return view('pdf/Laporan_IctRequest_Sedang_Dikerjakan', compact('ict'));
    }
    function cetak_excel_assignment_request(){
        $usr_name = Auth::user()->usr_name;
        $newCreation = Carbon::parse($this->date)->copy()->tz('Asia/Jakarta')->format('d M Y');
        return Excel::download(new IctExportAssignmentRequest($usr_name),'ICT REQUEST STATUS REPORT LIST ON (Direject) '.$newCreation.'.xlsx');
    }
    function cetak_pdf_sedang_dikerjakan()
    {
        $ict =  DB::table('ireq_mst as im')
        ->SELECT('vr.name as ireq_bu','im.ireq_requestor','im.ireq_id','im.ireq_status as status','im.ireq_no',DB::raw("TO_CHAR(im.ireq_date,' dd Mon YYYY') as ireq_date"),'im.ireq_user','im.ireq_requestor','dr.div_name',DB::raw("COALESCE(im.ireq_assigned_to2,im.ireq_assigned_to1) AS ireq_assigned_to"),'lr.lookup_desc as ireq_status')
        ->LEFTJOIN('divisi_refs as dr','im.ireq_divisi_user','dr.div_id')
        ->LEFTJOIN('vcompany_refs as vr','im.ireq_bu','vr.company_code')
        ->LEFTJOIN('lookup_refs as lr','im.ireq_status','lr.lookup_code')
        ->WHERE('im.ireq_status','T')
        ->WHERERaw('LOWER(lr.lookup_type) LIKE ? ',[trim(strtolower('ict_status')).'%'])
        ->WHERE('im.created_by',Auth::user()->usr_name)
        ->groupBy('vr.name','im.ireq_requestor','im.ireq_status','im.ireq_id','im.ireq_no','im.ireq_date','im.ireq_user','im.ireq_requestor','dr.div_name','im.creation_date','lr.lookup_desc',DB::raw("COALESCE(im.ireq_assigned_to2,im.ireq_assigned_to1)"))          
        ->ORDERBY('im.ireq_date','DESC')
        ->get();
        return view('pdf/Laporan_IctRequest_Sedang_Dikerjakan', compact('ict'));
    }
    function cetak_excel_sedang_dikerjakan()
    {
        $usr_name = Auth::user()->usr_name;
        $newCreation = Carbon::parse($this->date)->copy()->tz('Asia/Jakarta')->format('d M Y');
        return Excel::download(new IctExportSedangDikerjakan($usr_name),'ICT REQUEST STATUS REPORT LIST ON (Sedang Dikerjakan) '.$newCreation.'.xlsx');
    }
    Public function cetak_pdf_sudah_dikerjakan()
    {
        $ict =  DB::table('ireq_dtl as id')
        ->LEFTJOIN('ireq_mst as im','id.ireq_id','im.ireq_id')
        ->LEFTJOIN('divisi_refs as dr','im.ireq_divisi_user','dr.div_id')
        ->LEFTJOIN('lookup_refs as lr',function ($join) {
            $join->on('id.ireq_status','lr.lookup_code')
                  ->WHERERaw('LOWER(lr.lookup_type) LIKE ? ',[trim(strtolower('ict_status')).'%']);
        })
        ->LEFTJOIN('lookup_refs as lrs',function ($join) {
            $join->on('id.ireq_type','lrs.lookup_code')
                  ->WHERERaw('LOWER(lrs.lookup_type) LIKE ? ',[trim(strtolower('req_type')).'%']);
        })
        ->LEFTJOIN('lookup_refs as lrfs',function ($join) {
            $join->on('id.invent_code','lrfs.lookup_code')
                  ->WHERERaw('LOWER(lrfs.lookup_type) LIKE ? ',[trim(strtolower('kat_peripheral')).'%']);
        })
        ->SELECT('im.ireq_no','id.ireq_id','id.ireq_remark',DB::raw("COALESCE(id.ireq_assigned_to2,id.ireq_assigned_to1) AS ireq_assigned_to"),'id.ireqd_id',
        'lr.lookup_desc as ireq_status','lrs.lookup_desc as ireq_type','lrfs.lookup_desc as kategori',DB::raw("TO_CHAR(im.ireq_date,' dd Mon YYYY') as ireq_date"),'im.ireq_requestor','im.ireq_user',
        'dr.div_name','id.ireq_qty','id.ireq_status as status')
        ->WHERE('id.ireq_status','D')
        ->WHERE('im.created_by',Auth::user()->usr_name)
        ->ORDERBY('im.ireq_date','DESC')
        ->ORDERBY('id.ireqd_id','ASC')
        ->get();
        return view('pdf/Laporan_IctRequest_Sudah_Dikerjakan', compact('ict'));
    }
    public function cetak_excel_sudah_dikerjakan()
    {
        $usr_name = Auth::user()->usr_name;
        $newCreation = Carbon::parse($this->date)->copy()->tz('Asia/Jakarta')->format('d M Y');
        return Excel::download(new IctExportSudahDikerjakan($usr_name),'ICT REQUEST STATUS REPORT LIST ON (Sudah Dikerjakan) '.$newCreation.'.xlsx');
    }
    Public function cetak_pdf_selesai()
    {
        $ict =  DB::table('ireq_dtl as id')
        ->LEFTJOIN('ireq_mst as im','id.ireq_id','im.ireq_id')
        ->LEFTJOIN('divisi_refs as dr','im.ireq_divisi_user','dr.div_id')
        ->LEFTJOIN('lookup_refs as lr',function ($join) {
            $join->on('id.ireq_status','lr.lookup_code')
                  ->WHERERaw('LOWER(lr.lookup_type) LIKE ? ',[trim(strtolower('ict_status')).'%']);
        })
        ->LEFTJOIN('lookup_refs as lrs',function ($join) {
            $join->on('id.ireq_type','lrs.lookup_code')
                  ->WHERERaw('LOWER(lrs.lookup_type) LIKE ? ',[trim(strtolower('req_type')).'%']);
        })
        ->LEFTJOIN('lookup_refs as lrfs',function ($join) {
            $join->on('id.invent_code','lrfs.lookup_code')
                  ->WHERERaw('LOWER(lrfs.lookup_type) LIKE ? ',[trim(strtolower('kat_peripheral')).'%']);
        })
        ->SELECT('im.ireq_no','id.ireq_id','id.ireq_remark',DB::raw("COALESCE(id.ireq_assigned_to2,id.ireq_assigned_to1) AS ireq_assigned_to"),'id.ireqd_id',
        'lr.lookup_desc as ireq_status','lrs.lookup_desc as ireq_type','lrfs.lookup_desc as kategori',DB::raw("TO_CHAR(im.ireq_date,' dd Mon YYYY') as ireq_date"),'im.ireq_requestor','im.ireq_user',
        'dr.div_name','id.ireq_qty','id.ireq_status as status')
        ->WHERE('id.ireq_status','C')
        ->WHERE('im.created_by',Auth::user()->usr_name)
        ->ORDERBY('im.ireq_date','DESC')
        ->ORDERBY('id.ireqd_id','ASC')
        ->get();
        return view('pdf/Laporan_IctRequest_Selesai', compact('ict'));
    }
    public function cetak_excel_selesai()
    {
        $usr_name = Auth::user()->usr_name;
        $newCreation = Carbon::parse($this->date)->copy()->tz('Asia/Jakarta')->format('d M Y');
        return Excel::download(new IctExportSelesai($usr_name),'ICT REQUEST STATUS REPORT LIST ON '.$newCreation.'.xlsx');
    }
    public function updateAssign(Request $request)
    {
        $ict = Ict::where('ireq_id',$request->id)->first();
        $ict->ireq_status = 'T';
        $ict->ireq_assigned_to1 = $request->name;
        $ict->last_updated_by = Auth::user()->usr_name;
        $ict->last_update_date = $this->newUpdate;
        $ict->program_name = "IctController_updateAssign";
        $ict->save();

        $dtl = IctDetail::where('ireq_id',$request->id)->get();
        foreach ($dtl as $d){
            $d->ireq_status = 'T';
            $d->ireq_assigned_to1 = $request->name;
            $d->last_update_date = $this->newUpdate;
            $d->last_updated_by = Auth::user()->usr_name;
            $d->program_name = "IctController_updateAssign";
            $d->save();
        }
        return response()->json('Success Update');
    }
    public function updateStatusSubmit($ireq_id)
    {
        $ICT = Ict::where('ireq_id',$ireq_id)->first();
            $ICT->ireq_status = 'P';
            $ICT->ireq_date = $this->newUpdate;
            $ICT->last_update_date = $this->newUpdate;
            $ICT->last_updated_by = Auth::user()->usr_name;
            $ICT->program_name = "IctController_updateStatusSubmit";
            $ICT->save();

        $dtl = DB::table('ireq_dtl')
        ->WHERE('ireq_id',$ireq_id)
        ->update([
            'ireq_status' => 'P',
            'last_update_date' => $this->newUpdate,
            'last_updated_by' => Auth::user()->usr_name,
            'program_name' => "IctController_updateStatusSubmit",
        ]);
        $link = Link::create([
            'link_id'=> md5($this->date),
            'link_action'=> env('APP_VERIF_REVIEWER').''.$ireq_id,
            'ireq_id'=>$ireq_id
        ]);
        $LINK = Link::where('ireq_id',$ireq_id)->first();
        // $send_mail = Location::select('loc_email')->WHERE('loc_code',Auth::user()->usr_loc)->pluck('loc_email');
        $send_mail ='adhitya.saputro@emp.id';
        SendNotifRequest::dispatchAfterResponse($send_mail,$ICT,$LINK);
        return response()->json('Success Update Status');
    }
    public function cekVerif($code)
    {
        $link = Link::where('link_id',$code)->first();
        return json_encode($link);
    }
    function updateStatusPenugasan($ireq_id)
    {
        $ict = Ict::where('ireq_id',$ireq_id)->first();
        $ict->ireq_status = 'T';
        $ict->ireq_verificator = Auth::user()->usr_name;
        $ict->last_update_date = $this->newUpdate;    
        $ict->last_updated_by = Auth::user()->usr_name;
        $ict->program_name = "IctController_updateStatusPenugasan";
        $ict->save();
        
        $dtl = DB::table('ireq_dtl')
        ->WHERE('ireq_id',$ireq_id)
        ->update([
            'ireq_status' => 'T',
            'last_update_date' => $this->newUpdate,
            'last_updated_by' => Auth::user()->usr_name,
            'program_name' => "IctController_updateStatusPenugasan",
        ]);

        return response()->json('Success Update');
    }
    function updateStatusClosing($ireq_id)
    {
        
        $ict = Ict::where('ireq_id',$ireq_id)->first();
        $ict->ireq_approver2 = Auth::user()->usr_name;
        $ict->ireq_status = 'C';
        $ict->last_update_date = $this->newUpdate;
        $ict->last_updated_by = Auth::user()->usr_name;
        $ict->ireq_date_closing = $this->newUpdate;
        $ict->program_name = "IctController_updateStatusClosing";
        $ict->save();

        $dtl = DB::table('ireq_dtl')
        ->WHERE('ireq_id',$ireq_id)
        ->update([
            'ireq_status' => 'C',
            'last_update_date' => $this->newUpdate,
            'last_updated_by' => Auth::user()->usr_name,
            'program_name' => "IctController_updateStatusClosing",
        ]);
        
        return response()->json('Success Update');
    }
    function getDataIct(){
        $ict=DB::table('ireq_dtl as id')
        ->LEFTJOIN('ireq_mst as im','id.ireq_id','im.ireq_id')
        ->LEFTJOIN('divisi_refs as dr','im.ireq_divisi_user','dr.div_id')
        ->LEFTJOIN('lookup_refs as lr',function ($join) {
            $join->on('id.ireq_status','lr.lookup_code')
                  ->WHERERaw('LOWER(lr.lookup_type) LIKE ? ',[trim(strtolower('ict_status')).'%']);
        })
        ->LEFTJOIN('lookup_refs as lrs',function ($join) {
            $join->on('id.ireq_type','lrs.lookup_code')
                  ->WHERERaw('LOWER(lrs.lookup_type) LIKE ? ',[trim(strtolower('req_type')).'%']);
        })
        ->LEFTJOIN('lookup_refs as lrfs',function ($join) {
            $join->on('id.invent_code','lrfs.lookup_code')
                  ->WHERERaw('LOWER(lrfs.lookup_type) LIKE ? ',[trim(strtolower('kat_peripheral')).'%']);
        })
        ->SELECT('im.ireq_no','id.ireq_remark',DB::raw("COALESCE(id.ireq_assigned_to2,id.ireq_assigned_to1) AS ireq_assigned_to"),'id.ireqd_id',
        'lr.lookup_desc as ireq_status','lrs.lookup_desc as ireq_type','lrfs.lookup_desc as kategori','im.ireq_date','im.ireq_requestor','im.ireq_user',
        'dr.div_name','id.ireq_qty')
        ->whereNotNull('im.ireq_status')
        ->ORDERBY('im.ireq_date','DESC')
        ->get();
        
        return response()->json($ict);
    }
    function getDataIctByStatus($statuss){
        $ict = DB::table('ireq_mst as im')
        ->LEFTJOIN('lookup_refs as lr',function ($join) {
            $join->on('im.ireq_status','lr.lookup_code')
                  ->WHERERaw('LOWER(lr.lookup_type) LIKE ? ',[trim(strtolower('ict_status')).'%']);
        })
        ->LEFTJOIN('divisi_refs as dr','im.ireq_divisi_user','dr.div_id')
        ->SELECT('im.ireq_no','dr.div_name','im.ireq_date','im.ireq_user','im.ireq_requestor','lr.lookup_desc as ireq_status')
        ->WHERE('im.ireq_status',$statuss)
        ->ORDERBY('im.ireq_date','DESC')
        ->get();
        return response()->json($ict);
    }
    function detailNoRequest($code){
        $ict = DB::table('ireq_mst as im')
        ->LEFTJOIN('divisi_refs as dr','im.ireq_divisi_user','dr.div_id')
        ->LEFTJOIN('vcompany_refs as vr','im.ireq_bu','vr.company_code')
        ->LEFTJOIN('mng_users as mu','im.ireq_approver1','mu.usr_name')
        ->LEFTJOIN('mng_users as muu','im.ireq_approver2','muu.usr_name')
        ->SELECT('im.ireq_no','im.ireq_date','vr.name as ireq_bu','im.ireq_approver1_date as date_approver1',
        'im.ireq_approver2_date as date_approver2','dr.div_name','im.ireq_user','im.ireq_requestor','mu.usr_fullname as ireq_approval1','muu.usr_fullname as ireq_approval2')
        ->WHERE('im.ireq_id',$code)
        ->get();
        return json_encode($ict);
    }
}