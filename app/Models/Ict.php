<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Ict extends Model
{
    protected $fillable= [
        'ireq_id',
        'ireq_spv',
        'ireq_no',
        'ireq_date',
        'ireq_type',
        'ireq_requestor',
        'ireq_bu',
        'ireq_approver1',
        'ireq_approver1_date',
        'ireq_verificator',
        'ireq_approver2',
        'ireq_approver2_date',
        'ireq_approver2_remark',
        'ireq_status',
        'ireq_reason',
        'ireq_assigned_to1',
        'ireq_assigned_date',
        'ireq_assigned_to2',
        'ireq_assigned_to1_reason',
        'ireq_user',
        'ireq_divisi_requestor',
        'ireq_divisi_user',
        'ireq_verificator_remark',
        'ireq_prio_level',
        'creation_date',
        'created_by',
        'last_update_date',
        'last_updated_by',
        'program_name',
        'ireq_date_closing',
        'ireq_loc'
    ];
    protected $table ='ireq_mst';
    protected $primaryKey ='ireq_id';
    public $timestamps = false;
    public $incrementing = false;

    public static function detailNoRequest($code){
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
