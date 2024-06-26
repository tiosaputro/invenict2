<?php

namespace App\Services;

use App\Models\Ict;
use App\Models\IctDetail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class IctRequestPersonnelServices
{
    public function getDataWithFilter($status)
    {
        $data = Ict::Query();
        $data->LEFTJOIN('divisi_refs as dr', 'ireq_mst.ireq_divisi_user', 'dr.div_id');
        $data->LEFTJOIN('mng_users ms', 'ireq_mst.ireq_requestor', 'ms.usr_id');
        $data->LEFTJOIN('mng_user_domain mus', 'ireq_mst.ireq_user', 'mus.usr_domain');
        $data->RIGHTJOIN('ireq_dtl as id', 'ireq_mst.ireq_id', 'id.ireq_id');
        $data->SELECT(
            'ireq_mst.ireq_date',
            DB::RAW("COUNT(ireq_mst.ireq_verificator_remark) as countRemarkReviewer"),
            'mus.usr_division',
            'mus.usr_fullname as ireq_user',
            'ireq_mst.ireq_verificator_remark',
            'ireq_mst.ireq_no',
            'ms.usr_fullname as ireq_requestor',
            'ireq_mst.ireq_status as status',
            'ireq_mst.ireq_id');
        $data->WHERE('ireq_mst.ireq_status', $status);
        $data->WHERE('id.ireq_assigned_to1', Auth::user()->usr_id);
        $data->WHERE('id.ireq_status', $status);
        $data->groupBy(
            'ireq_mst.ireq_date',
            'mus.usr_division',
            'mus.usr_fullname',
            'ireq_mst.ireq_verificator_remark',
            'ireq_mst.ireq_no',
            'ireq_mst.ireq_requestor',
            'ireq_mst.ireq_user',
            'ireq_mst.ireq_status',
            'ms.usr_fullname',
            'ireq_mst.ireq_id'
        );
        $data->ORDERBY('ireq_mst.ireq_date', 'DESC');
        return $data->get();
    }
    public function getDetailWithFilter($status)
    {
        $data = IctDetail::Query();
        $data->SELECT(
            DB::raw('COUNT(ireq_dtl.ireq_assigned_to1_reason) as countReason'),
            DB::raw('COUNT(ireq_dtl.ireq_assigned_remark) as countRemarkAssigned'),
            DB::raw('COUNT(ireq_dtl.ireq_note_personnel) as countNoteAssigned'),
            DB::raw('COUNT(imm.ireq_verificator_remark) as countRemarkReviewer'),
            'imm.ireq_verificator_remark',
            'ireq_dtl.ireq_attachment',
            'ireq_dtl.ireq_assigned_to1_reason',
            'imm.ireq_no',
            'ireq_dtl.ireq_assigned_remark',
            'ireq_dtl.ireq_status as status',
            'ireq_dtl.ireq_id',
            'ireq_dtl.ireq_desc',
            'ireq_dtl.ireq_note_personnel',
            'ireq_dtl.ireq_qty',
            'ireq_dtl.ireq_remark',
            'ireq_dtl.ireqd_id',
            DB::raw("COALESCE(vi.official_name,vii.official_name) AS ireq_assigned_to"),
            'llr.lookup_desc as ireq_status',
            'lr.lookup_desc as ireq_type',
            'imm.ireq_date',
            'ms.usr_fullname as ireq_requestor',
            'mus.usr_fullname as ireq_user',
            'mus.usr_division',
            DB::raw("(crs.catalog_name ||' - '|| cr.catalog_name) as name")
        );
        $data->leftJoin('catalog_refs cr', function ($join) {
            $join->on('ireq_dtl.invent_code', 'cr.catalog_id');
        });
        $data->leftJoin('catalog_refs crs', function ($join) {
            $join->on('cr.parent_id', 'crs.catalog_id');
        });
        $data->LEFTJOIN('lookup_refs lr', 'ireq_dtl.ireq_type', 'lr.lookup_code');
        $data->LEFTJOIN('ireq_mst imm', 'ireq_dtl.ireq_id', 'imm.ireq_id');
        $data->LEFTJOIN('vpekerja_ict vi', function ($join) {
            $join->on('imm.ireq_assigned_to1', 'vi.usr_id')
                ->whereNotNull('imm.ireq_assigned_to1');
        });
        $data->LEFTJOIN('vpekerja_ict vii', function ($join) {
            $join->on('imm.ireq_assigned_to2', 'vii.usr_id')
                ->whereNull('imm.ireq_assigned_to1');
        });
        $data->LEFTJOIN('lookup_refs llr', 'ireq_dtl.ireq_status', 'llr.lookup_code');
        $data->LEFTJOIN('mng_users ms', 'imm.ireq_requestor', 'ms.usr_id');
        $data->LEFTJOIN('mng_user_domain mus', 'imm.ireq_user', 'mus.usr_domain');
        if (!empty($status)) {
            $data->WHERE('ireq_dtl.ireq_status', $status);
        }
        $data->WHERE(DB::raw("COALESCE(ireq_dtl.ireq_assigned_to2,ireq_dtl.ireq_assigned_to1)"), Auth::user()->usr_id);
        $data->WHERERaw('LOWER(lr.lookup_type) LIKE ? ', [trim(strtolower('req_type')) . '%']);
        $data->WHERERaw('LOWER(llr.lookup_type) LIKE ? ', [trim(strtolower('ict_status')) . '%']);
        $data->GROUPBY(
            'ireq_dtl.ireq_attachment',
            'imm.ireq_verificator_remark',
            'imm.ireq_no',
            'ireq_dtl.ireq_assigned_to1_reason',
            'ireq_dtl.ireq_note_personnel',
            'ireq_dtl.ireq_assigned_remark',
            'ireq_dtl.ireq_status',
            'ireq_dtl.ireq_id',
            'ireq_dtl.ireq_desc',
            'ireq_dtl.ireq_qty',
            'ireq_dtl.ireq_remark',
            'ireq_dtl.ireqd_id',
            DB::raw("COALESCE(vi.official_name,vii.official_name)"),
            'mus.usr_division',
            'imm.ireq_user',
            'llr.lookup_desc',
            'imm.ireq_requestor',
            'lr.lookup_desc',
            'imm.ireq_date',
            'ms.usr_fullname',
            'mus.usr_fullname',
            DB::raw("(crs.catalog_name ||' - '|| cr.catalog_name)"));
        $data->ORDERBY('imm.ireq_date', 'DESC');
        $data->ORDERBY('ireq_dtl.ireqd_id', 'ASC');
        return $data->get();
    }
    public function rejectedByPersonnel($request)
    {
        $dtl = DB::table('ireq_dtl')
            ->where('ireq_id', $request->ireq_id)
            ->where('ireq_assigned_to1', Auth::user()->usr_id)
            ->update([
                'ireq_status' => 'RT',
                'ireq_assigned_to1_reason' => $request->ireq_reason,
                'last_update_date' => now(),
                'last_updated_by' => Auth::user()->usr_id,
                'program_name' => "IctDetailController_rbp",
            ]);
        DB::getPdo()->exec("begin SP_REJECT_PENUGASAN_IREQ_MST('$request->ireq_id'); end;");
        return $dtl;
    }
    public function AcceptByPersonnel($ireq_id)
    {
        $dtl = DB::table('ireq_dtl')
            ->where('ireq_id', $ireq_id)
            ->where('ireq_assigned_to1', Auth::user()->usr_id)
            ->update([
                'ireq_status' => 'T',
                'last_update_date' => now(),
                'last_updated_by' => Auth::user()->usr_id,
                'program_name' => "IctDetailController_abp",
            ]);
        DB::getPdo()->exec("begin SP_PENUGASAN_IREQ_MST('$ireq_id'); end;");
        return $dtl;
    }

}
