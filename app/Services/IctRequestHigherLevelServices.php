<?php

namespace App\Services;

use App\Jobs\SendNotifApprovedFromHigherLevel;
use App\Jobs\SendNotifRejectByHigherLevel;
use App\Models\Ict;
use App\Models\IctDetail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class IctRequestHigherLevelServices
{
    public function getDataWithFilter($status1, $status2, $status3, $status4)
    {
        $data = Ict::query();
        $data->SELECT(
            'ireq_mst.ireq_id',
            'ireq_mst.ireq_verificator_remark',
            'mus.usr_division',
            'mu.usr_email',
            'ms.usr_fullname as ireq_requestor',
            'mus.usr_fullname as ireq_user',
            'ireq_mst.ireq_status as status',
            'ireq_mst.ireq_no',
            'ireq_mst.ireq_reason',
            'ireq_mst.ireq_date',
            'lr.lookup_desc as ireq_status',
            DB::raw("COALESCE(vi.official_name,vii.official_name) AS ireq_assigned_to"),
            'ireq_mst.ireq_approver2_remark');
        $data->LEFTJOIN('mng_users mu', 'ireq_mst.ireq_requestor', 'mu.usr_id');
        $data->LEFTJOIN('supervisor_refs sr', 'ireq_mst.ireq_spv', 'sr.spv_id');
        $data->LEFTJOIN('vpekerja_ict vi', function ($join) {
            $join->on('ireq_mst.ireq_assigned_to1', 'vi.usr_id')
                ->whereNotNull('ireq_mst.ireq_assigned_to1');
        });
        $data->LEFTJOIN('vpekerja_ict vii', function ($join) {
            $join->on('ireq_mst.ireq_assigned_to2', 'vii.usr_id')
                ->whereNull('ireq_mst.ireq_assigned_to1');
        });
        $data->LEFTJOIN('ireq_dtl idd', 'ireq_mst.ireq_id', 'idd.ireq_id');
        $data->LEFTJOIN('lookup_refs lr', 'ireq_mst.ireq_status', 'lr.lookup_code');
        $data->LEFTJOIN('mng_users ms', 'ireq_mst.ireq_requestor', 'ms.usr_id');
        $data->LEFTJOIN('mng_user_domain mus', 'ireq_mst.ireq_user', 'mus.usr_domain');
        $data->WHERE(function ($query) use ($status1, $status2, $status3, $status4) {
            if (!empty($status1)) {
                $query->WHERE('ireq_mst.ireq_status', $status1);
            }
            if (!empty($status2)) {
                $query->Orwhere('ireq_mst.ireq_status', $status2);
            }
            if (!empty($status3)) {
                $query->Orwhere('ireq_mst.ireq_status', $status3);
            }
            if (!empty($status4)) {
                $query->whereNotNull('ireq_mst.ireq_status');
            }
        });
        $data->where('sr.spv_name', Auth::user()->usr_id);
        $data->WHERERaw('LOWER(lr.lookup_type) LIKE ? ', [trim(strtolower('ict_status')) . '%']);
        $data->GroupBy(
            'ireq_mst.ireq_id',
            'ireq_mst.ireq_verificator_remark',
            'mus.usr_division',
            'mu.usr_email',
            'ms.usr_fullname',
            'mus.usr_fullname',
            'ireq_mst.ireq_status',
            'ireq_mst.ireq_no',
            'ireq_mst.ireq_reason',
            'ireq_mst.ireq_date',
            'lr.lookup_desc',
            DB::raw("COALESCE(vi.official_name,vii.official_name)"),
            'ireq_mst.ireq_approver2_remark');
        $data->ORDERBY('ireq_mst.ireq_date', 'DESC');
        return $data->get();
    }
    public function getDetailWithFilter($status, $code = null)
    {
        $data = IctDetail::Query();
        $data->LEFTJOIN('ireq_mst im', 'ireq_dtl.ireq_id', 'im.ireq_id');
        $data->LEFTJOIN('supervisor_refs sr', 'im.ireq_spv', 'sr.spv_id');
        $data->LEFTJOIN('vpekerja_ict vi', function ($join) {
            $join->on('ireq_dtl.ireq_assigned_to1', 'vi.usr_id')
                ->whereNotNull('ireq_dtl.ireq_assigned_to1');
        });
        $data->LEFTJOIN('vpekerja_ict vii', function ($join) {
            $join->on('ireq_dtl.ireq_assigned_to2', 'vii.usr_id')
                ->whereNull('ireq_dtl.ireq_assigned_to1');
        });
        $data->LEFTJOIN('mng_users ms', 'im.ireq_requestor', 'ms.usr_id');
        $data->LEFTJOIN('mng_user_domain mus', 'im.ireq_user', 'mus.usr_domain');
        $data->LEFTJOIN('lookup_refs lr', function ($join) {
            $join->on('ireq_dtl.ireq_status', 'lr.lookup_code')
                ->WHERERaw('LOWER(lr.lookup_type) LIKE ? ', [trim(strtolower('ict_status')) . '%']);
        });
        $data->LEFTJOIN('lookup_refs lrs', function ($join) {
            $join->on('ireq_dtl.ireq_type', 'lrs.lookup_code')
                ->WHERERaw('LOWER(lrs.lookup_type) LIKE ? ', [trim(strtolower('req_type')) . '%']);
        });
        $data->LEFTJOIN('catalog_refs cr', function ($join) {
            $join->on('ireq_dtl.invent_code', 'cr.catalog_id');
        });
        $data->LEFTJOIN('catalog_refs crs', function ($join) {
            $join->on('cr.parent_id', 'crs.catalog_id');
        });
        $data->SELECT(
            'ireq_dtl.ireq_attachment',
            DB::raw("COALESCE(vi.official_name,vii.official_name) AS ireq_assigned_to"),
            'ms.usr_fullname as ireq_requestor',
            'ms.usr_fullname',
            'mus.usr_fullname as ireq_user',
            'mus.usr_division as division_user',
            'ms.usr_email as user_mail',
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
        if (!empty($status)) {
            $data->WHERE('ireq_dtl.ireq_status', $status);
        }
        if (!empty($code)) {
            $data->WHERE('im.ireq_id', $code);
        }
        $data->WHERE('sr.spv_name', Auth::user()->usr_id);
        $data->ORDERBY('im.ireq_date', 'DESC');
        $data->ORDERBY('ireq_dtl.ireqd_id', 'ASC');
        return $data->get();
    }
    public function ApprovedByAtasan($code)
    {
        $ict = Ict::where('ireq_id', $code)->first();
        $ict->ireq_status = 'A1';
        $ict->ireq_approver1 = Auth::user()->usr_id;
        $ict->ireq_approver1_date = now();
        $ict->last_updated_by = Auth::user()->usr_id;
        $ict->last_update_date = now();
        $ict->program_name = "IctController_approveByAtasan";
        $ict->save();

        DB::table('ireq_dtl')
            ->WHERE('ireq_id', $code)
            ->update([
                'ireq_status' => 'A1',
                'last_update_date' => now(),
                'last_updated_by' => Auth::user()->usr_id,
                'program_name' => "IctController_approveByAtasan",
            ]);

        $data = $this->getDetailWithFilter(null, $code);
        $user_mail = $data[0]->user_mail;
        SendNotifApprovedFromHigherLevel::dispatchAfterResponse($user_mail, $data);
        return $ict;
    }
    public function RejectedByAtasan($request, $code)
    {
        $ict = Ict::where('ireq_id', $code)->first();
        $ict->ireq_status = 'RA1';
        $ict->ireq_approver1 = Auth::user()->usr_id;
        $ict->ireq_approver1_date = now();
        $ict->ireq_reason = $request->ket;
        $ict->last_update_date = now();
        $ict->last_updated_by = Auth::user()->usr_id;
        $ict->program_name = "IctController_rejectByAtasan";
        $ict->save();

        DB::table('ireq_dtl')
            ->WHERE('ireq_id', $code)
            ->update([
                'ireq_status' => 'A2',
                'ireq_reason' => $request->ket,
                'last_update_date' => now(),
                'last_updated_by' => Auth::user()->usr_id,
                'program_name' => "IctController_rejectByManager",
            ]);

        $data = $this->getDetailWithFilter(null, $code);
        $user_mail = $data[0]->user_mail;
        SendNotifRejectByHigherLevel::dispatchAfterResponse($user_mail, $data);
        return $ict;
    }

}
