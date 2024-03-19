<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;

class IctServices
{
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
        ->select(DB::raw("TO_CHAR(im.ireq_date, 'yyyy-MM-dd HH24:MI:SS') AS ireq_date "),'im.ireq_date as request_date','im.ireq_requestor','im.ireq_no as noreq','im.ireq_type','im.ireq_status as cekStatus',
                'lr.lookup_desc as ireq_status','lrs.loc_desc')
        ->where('im.ireq_id',$code)
        ->first();
         return $data;
    }
}