<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
Use App\Models\Ict;
use App\Models\IctDetail;
Use Illuminate\Support\Facades\Auth;
use App\Jobs\SendNotifRejectByReviewer;
use App\Jobs\SendNotifRejectByIctManager;
use App\Jobs\SendNotifApprovedFromIctManager;

class IctServices
{
    public function getDataRequestWithFilter($code){
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
        $data->LEFTJOIN('mng_user_domain mus','im.ireq_user','mus.usr_domain');
        $data->LEFTJOIN('divisi_refs as dr','im.ireq_divisi_user','dr.div_id');
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
            'ms.usr_fullname as ireq_requestor',
            'ms.usr_fullname',
            'ms.usr_email as mail_requestor',
            'mus.usr_fullname as ireq_user',
            'mus.usr_division as division_user',
            'im.ireq_no',
            'im.ireq_spv',
            'im.ireq_reason',
            'im.ireq_verificator_remark',
            'ireq_dtl.ireq_status as status',
            'ireq_dtl.ireq_id',
            'ireq_dtl.ireq_remark',
            'ireq_dtl.ireqd_id',
            'lr.lookup_desc as ireq_status',
            'lrs.lookup_desc as ireq_type',
            DB::raw("(crs.catalog_name ||' - '|| cr.catalog_name) as name"),
            'im.ireq_date',
            'mus.usr_division',
            'ireq_dtl.ireq_qty');
        if(!empty($code)){
            $data->WHERE('im.ireq_id',$code);
        }
        $data->ORDERBY('im.ireq_date','DESC');
        $data->ORDERBY('ireq_dtl.ireqd_id','ASC');
        return $data->get();
    }
    function detailNoRequest($code){
        $data = DB::table('ireq_mst as im')
        ->leftJoin('lookup_refs as lr',function ($join) {
            $join->on('im.ireq_status','lr.lookup_code')
                ->whereRaw('LOWER(lr.lookup_type) LIKE ? ',[trim(strtolower('ict_status')).'%']);
        })
        ->leftJoin('location_refs as lrs',function ($join) {
            $join->on('im.ireq_loc','lrs.loc_code');
        })
        ->leftJoin('ireq_dtl as id',function ($join) {
            $join->on('im.ireq_id','id.ireq_id');
        })
        ->select(
            DB::raw("TO_CHAR(im.ireq_date, 'yyyy-MM-dd HH24:MI:SS') AS ireq_date "),
            'im.ireq_date as request_date',
            'im.ireq_requestor',
            'im.ireq_no as noreq',
            'im.ireq_type',
            'im.ireq_status as cekStatus',
            'im.ireq_spv',
            'im.ireq_id',
            'lr.lookup_desc as ireq_status','lrs.loc_desc')
        ->where('im.ireq_id',$code)
        ->first();
         return $data;
    }
    
    
}