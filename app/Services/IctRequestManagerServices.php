<?php

namespace App\Services;


use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\IctDetail;
use App\Models\Ict;
use Carbon\Carbon;
use App\Jobs\SendNotifApprovedFromIctManager;
Use App\Jobs\SendNotifRejectByIctManager;

class IctRequestManagerServices
{
    public function getDataRequestWithFilter($status1, $status2, $status3, $status4){
        $data = Ict::query();
        $data->SELECT(
                DB::raw("(usr.usr_fullname ||' - '|| sr.spv_job_title) as spv"),
                'ireq_mst.ireq_id',
                'ireq_mst.ireq_verificator_remark',
                'mus.usr_division',
                'mu.usr_email',
                'ms.usr_fullname as ireq_requestor',
                'mus.usr_fullname as ireq_user',
                'ireq_mst.ireq_status as status',
                'ireq_mst.ireq_no',
                'ireq_mst.ireq_date',
                'ireq_mst.ireq_reason',
                'lr.lookup_desc as ireq_status',
                'ireq_mst.ireq_status as status',
                DB::raw('count(ireq_mst.ireq_verificator_remark) as count_remark'),
                DB::raw('count(idd.ireq_assigned_to1) as ireq_count_status'), 
                DB::raw('count(idd.ireq_id) as ireq_count_id'),
                DB::raw("COALESCE(vi.official_name,vii.official_name) AS ireq_assigned_to"),
                DB::raw('count(ireq_mst.ireq_approver2_remark) as count_remark_approver2'),'ireq_mst.ireq_approver2_remark');
        $data->LEFTJOIN('divisi_refs dr','ireq_mst.ireq_divisi_user','dr.div_id');
        $data->LEFTJOIN('mng_users mu','ireq_mst.created_by','mu.usr_id');
        $data->LEFTJOIN('supervisor_refs sr','ireq_mst.ireq_spv','sr.spv_id');
        $data->LEFTJOIN('mng_users usr','sr.spv_name','usr.usr_id');
        $data->LEFTJOIN('vpekerja_ict vi', function($join) {
            $join->on('ireq_mst.ireq_assigned_to1','vi.usr_id')
                  ->whereNotNull('ireq_mst.ireq_assigned_to1');
        });
        $data->LEFTJOIN('vpekerja_ict vii', function($join) {
            $join->on('ireq_mst.ireq_assigned_to2', 'vii.usr_id')
                  ->whereNull('ireq_mst.ireq_assigned_to1');
        });
        $data->LEFTJOIN('user_profile up','ireq_mst.ireq_user','up.user_id');
        $data->LEFTJOIN('ireq_dtl idd','ireq_mst.ireq_id','idd.ireq_id');
        $data->LEFTJOIN('lookup_refs lr','ireq_mst.ireq_status','lr.lookup_code');
        $data->LEFTJOIN('mng_users ms','ireq_mst.ireq_requestor','ms.usr_id');
        $data->LEFTJOIN('mng_users mus','ireq_mst.ireq_user','mus.usr_id');
        $data->WHERE(function($query) use($status1, $status2, $status3, $status4){
            if(!empty($status1)){
                $query->WHERE('ireq_mst.ireq_status',$status1);
            }
            if(!empty($status2)){
                $query->Orwhere('ireq_mst.ireq_status',$status2);
            }
            if(!empty($status3)){
                $query->Orwhere('ireq_mst.ireq_status',$status3);
            }
            if(!empty($status4)){
                $query->whereNotNull('ireq_mst.ireq_status');
            }
        });
        $data->WHERERaw('LOWER(lr.lookup_type) LIKE ? ',[trim(strtolower('ict_status')).'%']);
        $data->GroupBy(
            DB::raw("(usr.usr_fullname ||' - '|| sr.spv_job_title)"),
            'ms.usr_fullname',
            'ireq_mst.ireq_reason',
            'mus.usr_fullname',
            'mus.usr_division',
            'ireq_mst.ireq_id',
            'ireq_mst.ireq_verificator_remark',
            DB::raw("COALESCE(vi.official_name,vii.official_name)"),
            'ireq_mst.ireq_status','ireq_mst.ireq_no','ireq_mst.ireq_date',
            'mu.usr_email',
            'lr.lookup_desc',
            'ireq_mst.ireq_status',
            'ireq_mst.ireq_approver2_remark');
        $data->ORDERBY('ireq_mst.ireq_date','DESC');
        return $data->get();
    }
    public function getDataDetailWithFilter($status, $code = NULL){
        $data = IctDetail::Query();
        $data->LEFTJOIN('ireq_mst as im','ireq_dtl.ireq_id','im.ireq_id');
        $data->LEFTJOIN('vpekerja_ict vi', function($join) {
            $join->on('ireq_dtl.ireq_assigned_to1','vi.usr_id')
                  ->whereNotNull('ireq_dtl.ireq_assigned_to1');
        });
        $data->LEFTJOIN('vpekerja_ict vii', function($join) {
            $join->on('ireq_dtl.ireq_assigned_to2', 'vii.usr_id')
                  ->whereNull('ireq_dtl.ireq_assigned_to1');
        });
        $data->LEFTJOIN('mng_users ms','im.ireq_requestor','ms.usr_id');
        $data->LEFTJOIN('mng_users mus','im.ireq_user','mus.usr_id');
        $data->LEFTJOIN('divisi_refs as dr','im.ireq_divisi_user','dr.div_id');
        $data->LEFTJOIN('user_profile up','im.ireq_user','up.user_id');
        $data->LEFTJOIN('lookup_refs as lr',function ($join) {
            $join->on('ireq_dtl.ireq_status','lr.lookup_code')
                ->WHERERaw('LOWER(lr.lookup_type) LIKE ? ',[trim(strtolower('ict_status')).'%']);
        });
        $data->LEFTJOIN('lookup_refs as lrs',function ($join) {
            $join->on('ireq_dtl.ireq_type','lrs.lookup_code')
                ->WHERERaw('LOWER(lrs.lookup_type) LIKE ? ',[trim(strtolower('req_type')).'%']);
        });
        $data->LEFTJOIN('catalog_refs as cr',function ($join) {
            $join->on('ireq_dtl.invent_code','cr.catalog_id');
        });
        $data->LEFTJOIN('catalog_refs as crs',function ($join) {
            $join->on('cr.parent_id','crs.catalog_id');
        });
        $data->SELECT(
            'ireq_dtl.ireq_attachment',
            DB::raw("COALESCE(vi.official_name,vii.official_name) AS ireq_assigned_to"),
            'ms.usr_email as mail_requestor',
            'ms.usr_fullname as ireq_requestor',
            'ms.usr_fullname',
            'mus.usr_fullname as ireq_user',
            'mus.usr_division as division_user',
            'im.ireq_reason',
            'im.ireq_no',
            'im.ireq_verificator_remark',
            'ireq_dtl.ireq_status as status',
            'ireq_dtl.ireq_id',
            'ireq_dtl.ireq_remark',
            'ireq_dtl.ireqd_id',
            'lr.lookup_desc as ireq_status',
            'lrs.lookup_desc as ireq_type',
            DB::raw("(crs.catalog_name ||' - '|| cr.catalog_name) as kategori"),
            'im.ireq_date',
            'mus.usr_division',
            'ireq_dtl.ireq_qty');
        if(!empty($status)){
            $data->WHERE('ireq_dtl.ireq_status',$status);
        }
        if(!empty($code)){
            $data->WHERE('im.ireq_id',$code);
        }
        $data->ORDERBY('im.ireq_date','DESC');
        $data->ORDERBY('ireq_dtl.ireqd_id','ASC');
        return $data->get();
    }
    public function RejectedByIctManager($request, $code){
        $ict = Ict::where('ireq_id',$code)->first();
        $ict->ireq_status = 'RA2';
        $ict->ireq_approver1 = Auth::user()->usr_id;
        $ict->ireq_approver1_date = now();
        $ict->ireq_reason = $request->ket;
        $ict->last_update_date = now();
        $ict->last_updated_by = Auth::user()->usr_id;
        $ict->program_name = "IctController_rejectByManager";
        $ict->save();

        DB::table('ireq_dtl')
        ->WHERE('ireq_id',$code)
        ->update([
            'ireq_status' => 'A2',
            'last_update_date' => now(),
            'last_updated_by' => Auth::user()->usr_id,
            'program_name' => "IctController_approveByManager",
        ]);

        $data = $this->getDataDetailWithFilter(NULL, $code);

        $user_mail = $data[0]->mail_requestor;
        SendNotifRejectByIctManager::dispatchAfterResponse($user_mail,$data);
        
        return $ict;
    }
    public function ApprovedByIctManager($request,$code){
        $ict = Ict::where('ireq_id',$code)->first();
        $ict->ireq_status = 'A2';
        $ict->ireq_approver2_remark = $request->remark;
        $ict->ireq_approver2 = Auth::user()->usr_id;
        $ict->ireq_approver2_date = now();
        $ict->last_updated_by = Auth::user()->usr_id;
        $ict->last_update_date = now();
        $ict->program_name = "IctController_approveByManager";
        $ict->save();

        DB::table('ireq_dtl')
        ->WHERE('ireq_id',$code)
        ->update([
            'ireq_status' => 'A2',
            'last_update_date' => now(),
            'last_updated_by' => Auth::user()->usr_id,
            'program_name' => "IctController_approveByManager",
        ]);

        $data = $this->getDataDetailWithFilter(NULL, $code);
        $user_mail = $data[0]->mail_requestor;
        SendNotifApprovedFromIctManager::dispatchAfterResponse($user_mail,$data);

        return $ict;
    }
}