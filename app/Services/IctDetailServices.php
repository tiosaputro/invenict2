<?php

namespace App\Services;

use App\Jobs\SendNotifInProgress;
use App\Models\IctDetail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class IctDetailServices
{
    public static function getDataWithFilter($code, $status)
    {
        $data = IctDetail::query();
        $data->LEFTJOIN('ireq_mst as im', 'ireq_dtl.ireq_id', 'im.ireq_id');
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
        $data->LEFTJOIN('lookup_refs as lr', function ($join) {
            $join->on('ireq_dtl.ireq_status', 'lr.lookup_code')
                ->WHERERaw('LOWER(lr.lookup_type) LIKE ? ', [trim(strtolower('ict_status')) . '%']);
        });
        $data->LEFTJOIN('lookup_refs as lrs', function ($join) {
            $join->on('ireq_dtl.ireq_type', 'lrs.lookup_code')
                ->WHERERaw('LOWER(lrs.lookup_type) LIKE ? ', [trim(strtolower('req_type')) . '%']);
        });
        $data->LEFTJOIN('catalog_refs as cr', function ($join) {
            $join->on('ireq_dtl.invent_code', 'cr.catalog_id');
        });
        $data->LEFTJOIN('catalog_refs as crs', function ($join) {
            $join->on('cr.parent_id', 'crs.catalog_id');
        });
        $data->SELECT(
            DB::raw("COALESCE(vi.official_name,vii.official_name) AS ireq_assigned_to"),
            'ms.usr_fullname as ireq_requestor',
            'ms.usr_fullname',
            'ms.usr_email as mail_requestor',
            'mus.usr_fullname as ireq_user',
            'mus.usr_division',
            'im.ireq_no',
            'im.ireq_verificator_remark',
            'ireq_dtl.ireq_status as status',
            'ireq_dtl.ireq_id',
            'ireq_dtl.ireq_remark',
            'ireq_dtl.ireqd_id',
            'lr.lookup_desc as ireq_status',
            'lrs.lookup_desc as ireq_type',
            DB::raw("(crs.catalog_name ||' - '|| cr.catalog_name) as name"),
            'im.ireq_date',
            'ireq_dtl.ireq_qty');
        if (!empty($code)) {
            $data->WHERE('im.ireq_id', $code);
        }
        if (!empty($status)) {
            $data->WHERE('ireq_dtl.ireq_status', $status);
        }
        $data->ORDERBY('ireq_dtl.ireqd_id', 'ASC');
        return $data->get();
    }
    public static function getDataDetailRequest($code)
    {
        $data = DB::table('ireq_dtl as id')
            ->select(
                DB::raw("COALESCE(vi2.official_name,vi1.official_name) AS ireq_assigned_to"),
                'id.ireq_attachment',
                'id.ireq_id',
                'id.ireq_assigned_to1_reason',
                'id.invent_code',
                'vi1.official_name as ireq_assigned_to1',
                'id.ireq_status as status',
                'vi2.official_name as ireq_assigned_to2',
                'id.ireqd_id',
                'lr.lookup_desc as ireq_type',
                'id.ireq_remark',
                'id.ireq_desc',
                'id.ireq_qty',
                DB::raw('COUNT(id.ireq_assigned_to2) as ireq_count_personnel2'),
                DB::raw('COUNT(id.ireq_assigned_to1_reason) as ireq_count_reason'),
                DB::raw('COUNT(id.ireq_assigned_to1) as ireq_count_personnel1'),
                DB::raw("(crs.catalog_name ||' - '|| cr.catalog_name) as name"),
                'llr.lookup_desc as ireq_status',
                'id.ireq_status as cekStatus')
            ->leftJoin('vpekerja_ict as vi1', 'id.ireq_assigned_to1', 'vi1.usr_id')
            ->leftJoin('vpekerja_ict as vi2', function ($join) {
                $join->on('id.ireq_assigned_to2', 'vi2.usr_id')
                    ->whereNotNull('id.ireq_assigned_to2');
            })
            ->leftJoin('catalog_refs as cr', function ($join) {
                $join->on('id.invent_code', 'cr.catalog_id');
            })
            ->leftJoin('catalog_refs as crs', function ($join) {
                $join->on('cr.parent_id', 'crs.catalog_id');
            })
            ->leftJoin('lookup_refs as lr', function ($join) {
                $join->on('id.ireq_type', 'lr.lookup_code')
                    ->whereRaw('LOWER(lr.lookup_type) LIKE ? ', [trim(strtolower('req_type')) . '%']);
            })
            ->leftJoin('lookup_refs as llr', function ($join) {
                $join->on('id.ireq_status', 'llr.lookup_code')
                    ->whereRaw('LOWER(llr.lookup_type) LIKE ? ', [trim(strtolower('ict_status')) . '%']);
            })
            ->where('id.ireq_id', $code)
            ->groupBy(
                DB::raw("COALESCE(vi2.official_name,vi1.official_name)"),
                'id.ireq_attachment',
                'id.ireq_id',
                'id.ireq_assigned_to1_reason',
                'id.invent_code',
                'vi1.official_name',
                'id.ireq_status',
                'vi2.official_name',
                'id.ireqd_id',
                'lr.lookup_desc',
                'id.ireq_remark',
                'id.ireq_desc',
                'id.ireq_qty',
                DB::raw("(crs.catalog_name ||' - '|| cr.catalog_name)"),
                'llr.lookup_desc')
            ->orderBy('id.ireqd_id', 'ASC')
            ->get();
        return $data;
    }
    public static function detailDone($code)
    {
        $data = DB::table('ireq_dtl as id')
            ->select('id.ireq_assigned_to1', 'id.ireq_attachment', 'id.ireqd_id', 'lr.lookup_desc as ireq_type',
                DB::raw("(crs.catalog_name ||' - '|| cr.catalog_name) as name"), 'id.ireq_remark', 'id.ireq_qty', 'id.ireq_desc')
            ->leftjoin('ireq_mst as imm', 'id.ireq_id', 'imm.ireq_id')
            ->LEFTJOIN('catalog_refs as cr', function ($join) {
                $join->on('id.invent_code', 'cr.catalog_id');
            })
            ->LEFTJOIN('catalog_refs as crs', function ($join) {
                $join->on('cr.parent_id', 'crs.catalog_id');
            })
            ->leftjoin('lookup_refs as lr', 'id.ireq_type', 'lr.lookup_code')
            ->where('imm.ireq_id', $code)
            ->where('id.ireq_assigned_to1', Auth::user()->usr_id)
            ->whereRaw('LOWER(lr.lookup_type) LIKE ? ', [trim(strtolower('req_type')) . '%'])
            ->orderBy('id.ireqd_id', 'ASC')
            ->get();
        return $data;
    }
    public static function cekStatusPenugasan($code)
    {
        $ict = DB::table('ireq_dtl as id')
            ->LEFTJOIN('ireq_mst as im', 'id.ireq_id', 'im.ireq_id')
            ->LEFTJOIN('catalog_refs as cr', function ($join) {
                $join->on('id.invent_code', 'cr.catalog_id');
            })
            ->LEFTJOIN('catalog_refs as crs', function ($join) {
                $join->on('cr.parent_id', 'crs.catalog_id');
            })
            ->LEFTJOIN('lookup_refs as lrfs', function ($join) {
                $join->on('id.ireq_type', 'lrfs.lookup_code')
                    ->WHERERaw('LOWER(lrfs.lookup_type) LIKE ? ', [trim(strtolower('req_type')) . '%']);
            })
            ->LEFTJOIN('vpekerja_ict vi', function ($join) {
                $join->on('id.ireq_assigned_to1', 'vi.usr_id')
                    ->whereNotNull('id.ireq_assigned_to1');
            })
            ->LEFTJOIN('vpekerja_ict vii', function ($join) {
                $join->on('id.ireq_assigned_to2', 'vii.usr_id')
                    ->whereNull('id.ireq_assigned_to1');
            })
            ->LEFTJOIN('mng_users mu', 'im.ireq_requestor', 'mu.usr_id')
            ->LEFTJOIN('location_refs loc', 'im.ireq_loc', 'loc.loc_code')
            ->LEFTJOIN('supervisor_refs sr', 'im.ireq_spv', 'sr.spv_id')
            ->LEFTJOIN('mng_users mus', 'sr.spv_name', 'mus.usr_id')
            ->LEFTJOIN('mng_user_domain usr', 'im.ireq_user', 'usr.usr_domain')
            ->SELECT(
                'mus.usr_email as spv_mail',
                'loc.loc_email',
                'mu.usr_fullname',
                'sr.spv_name',
                'mu.usr_email as requestor_mail',
                'im.ireq_no',
                'id.ireqd_id',
                'im.ireq_id',
                'mu.usr_division as division_user',
                'im.ireq_date',
                DB::raw("COALESCE(vi.official_name,vii.official_name) AS ireq_assigned_to"),
                'mu.usr_fullname as ireq_requestor',
                'usr.usr_fullname as ireq_user',
                DB::raw("(crs.catalog_name ||' - '|| cr.catalog_name) as kategori"),
                'id.ireq_qty',
                'lrfs.lookup_desc as ireq_type',
                'id.ireq_remark')
            ->WHERE('im.ireq_id', $code)
            ->ORDERBY('id.ireqd_id', 'ASC')
            ->get();
        if (env('APP_ENV') != 'local') {
            $mail_user = $ict[0]->requestor_mail;
        } else {
            $mail_user = 'adhitya.saputro@emp.id';
        }
        SendNotifInProgress::dispatchAfterResponse($mail_user, $ict);
        $message = 'Success';
        return $message;

    }
    public static function FindDetailRequest($ireqd_id, $ireq_id)
    {
        $data = DB::table('ireq_dtl as id')
            ->select(
                'id.ireq_attachment',
                'id.invent_code',
                'im.ireq_spv',
                'id.ireq_type',
                'id.ireq_assigned_to1',
                'id.ireq_assigned_to2',
                'id.ireq_assigned_remark',
                'im.ireq_no',
                'id.ireq_id',
                'id.ireq_note_personnel as ireq_reason',
                'id.ireqd_id',
                'id.ireq_status as status',
                DB::raw("(crs.catalog_name ||' - '|| cr.catalog_name) as name"),
                'lr.lookup_desc as request_type',
                'id.ireq_qty',
                'id.ireq_remark',
                'id.ireq_assigned_to1_reason')
            ->leftjoin('ireq_mst as im', 'id.ireq_id', 'im.ireq_id')
            ->LEFTJOIN('catalog_refs as cr', function ($join) {
                $join->on('id.invent_code', 'cr.catalog_id');
            })
            ->LEFTJOIN('catalog_refs as crs', function ($join) {
                $join->on('cr.parent_id', 'crs.catalog_id');
            })
            ->leftJoin('lookup_refs as lr', function ($join) {
                $join->on('id.ireq_type', 'lr.lookup_code')
                    ->whereRaw('LOWER(lr.lookup_type) LIKE ? ', [trim(strtolower('req_type')) . '%']);
            })
            ->where('id.ireqd_id', $ireqd_id)
            ->where('id.ireq_id', $ireq_id)
            ->first();
        return $data;
    }
    public static function detailVerification($code)
    {
        $data = DB::table('ireq_dtl as id')
            ->select(
                'id.*',
                'lr.lookup_desc as ireq_type',
                DB::raw("(crs.catalog_name ||' - '|| cr.catalog_name) as invent_desc"))
            ->leftjoin('lookup_refs as lr', 'id.ireq_type', 'lr.lookup_code')
            ->LEFTJOIN('catalog_refs as cr', function ($join) {
                $join->on('id.invent_code', 'cr.catalog_id');
            })
            ->LEFTJOIN('catalog_refs as crs', function ($join) {
                $join->on('cr.parent_id', 'crs.catalog_id');
            })
            ->where('id.ireq_id', $code)
            ->whereRaw('LOWER(lr.lookup_type) LIKE ? ', [trim(strtolower('req_type')) . '%'])
            ->orderby('id.ireqd_id', 'ASC')
            ->get();
        return $data;
    }

    public function dataReport($code)
    {
        $data = DB::table('ireq_dtl as id')
            ->select(
                'imm.ireq_status as cekstatus',
                'id.ireq_type',
                'imm.ireq_id',
                'imm.ireq_no',
                'id.ireq_desc',
                'mu.usr_division as division_user',
                'mngr.usr_fullname as manager_ict_name',
                'mngr.usr_jabatan as manager_job_title',
                'id.ireq_qty',
                'mu.usr_fullname as ireq_requestor',
                'mus.usr_fullname as ireq_spv',
                'sr.spv_job_title',
                'id.ireq_remark',
                'lllr.lookup_desc as prio_level',
                'imm.ireq_no',
                'loc_refs.loc_desc as ireq_loc',
                'imm.ireq_verificator_remark',
                'imm.ireq_approver2_remark',
                'llr.lookup_desc as ireq_type',
                'imm.ireq_status as status',
                'imm.ireq_date',
                'imm.ireq_approver1_date',
                'usr.usr_fullname as ireq_verificator',
                DB::raw("COALESCE(vi.official_name,vii.official_name) AS ireq_assigned_to"),
                'lr.lookup_desc as ireqq_status',
                'imm.ireq_approver2_date',
                DB::raw("(crs.catalog_name ||' - '|| cr.catalog_name) as name"),
                'lr.lookup_desc as ireq_status',
                'id.ireq_assigned_date',
                'id.ireq_date_closing',
                'id.ireq_assigned_remark as assigned_remark_detail',
                'imm.ireq_assigned_remark as assigned_remark_request',
                'id.ireqd_id',
                'id.ireq_date_closing')
            ->LEFTJOIN('catalog_refs as cr', function ($join) {
                $join->on('id.invent_code', 'cr.catalog_id');
            })
            ->LEFTJOIN('catalog_refs as crs', function ($join) {
                $join->on('cr.parent_id', 'crs.catalog_id');
            })
            ->LEFTJOIN('vpekerja_ict vi', function ($join) {
                $join->on('id.ireq_assigned_to1', 'vi.usr_id')
                    ->whereNotNull('id.ireq_assigned_to1');
            })
            ->LEFTJOIN('vpekerja_ict vii', function ($join) {
                $join->on('id.ireq_assigned_to2', 'vii.usr_id')
                    ->whereNull('id.ireq_assigned_to1');
            })
            ->leftjoin('ireq_mst as imm', 'id.ireq_id', 'imm.ireq_id')
            ->leftjoin('supervisor_refs sr', 'imm.ireq_spv', 'sr.spv_id')
            ->leftjoin('mng_users mus', 'sr.spv_name', 'mus.usr_id')
            ->leftjoin('mng_users usr', 'imm.ireq_verificator', 'usr.usr_id')
            ->leftjoin('mng_users mngr', 'imm.ireq_approver2', 'mngr.usr_id')
            ->leftjoin('location_refs as loc_refs', 'imm.ireq_loc', 'loc_refs.loc_code')
            ->leftjoin('mng_user_domain mu', 'imm.ireq_user', 'mu.usr_domain')
            ->leftjoin('lookup_refs as lllr', 'imm.ireq_prio_level', 'lllr.lookup_code')
            ->leftjoin('lookup_refs as llr', 'id.ireq_type', 'llr.lookup_code')
            ->leftjoin('lookup_refs as lr', 'id.ireq_status', 'lr.lookup_code')
            ->where('id.ireq_id', $code)
            ->whereRaw('LOWER(lllr.lookup_type) LIKE ? ', [trim(strtolower('req_prio')) . '%'])
            ->whereRaw('LOWER(llr.lookup_type) LIKE ? ', [trim(strtolower('req_type')) . '%'])
            ->whereRaw('LOWER(lr.lookup_type) LIKE ? ', [trim(strtolower('ict_status')) . '%'])
            ->orderBy('id.ireqd_id', 'ASC')
            ->get();
        return $data;
    }

}
