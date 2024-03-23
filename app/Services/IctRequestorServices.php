<?php

namespace App\Services;


use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\IctDetail;
use App\Models\Ict;
use Carbon\Carbon;

class IctRequestorServices
{
    public function getDataWithFilter($status1, $status2, $status3, $status4, $status5){

        $usr_id = Auth::user()->usr_id;

        $data = Ict::Query();
        $data->LEFTJOIN('ireq_dtl as idm','ireq_mst.ireq_id','idm.ireq_id');
        $data->LEFTJOIN('mng_users as mu','ireq_mst.ireq_requestor','mu.usr_id');
        $data->LEFTJOIN('mng_users as muu','ireq_mst.ireq_user','muu.usr_id');
        $data->LEFTJOIN('user_profile up','ireq_mst.ireq_user','up.user_id');
        $data->LEFTJOIN('supervisor_refs sr','ireq_mst.ireq_spv','sr.spv_id');
        $data->LEFTJOIN('mng_users usr','sr.spv_name','usr.usr_id');
        $data->LEFTJOIN('lookup_refs as lr',function ($join) {
            $join->on('ireq_mst.ireq_status','lr.lookup_code')
                ->WHERERaw('LOWER(lr.lookup_type) LIKE ? ',[trim(strtolower('ict_status')).'%']);
        });
        $data->LEFTJOIN('vpekerja_ict vi', function($join) {
            $join->on('ireq_mst.ireq_assigned_to1','vi.usr_id')
                  ->whereNotNull('ireq_mst.ireq_assigned_to1');
        });
        $data->LEFTJOIN('vpekerja_ict vii', function($join) {
            $join->on('ireq_mst.ireq_assigned_to2', 'vii.usr_id')
                  ->whereNull('ireq_mst.ireq_assigned_to1');
        });
        $data->SELECT(
            DB::raw("(usr.usr_fullname ||' - '|| sr.spv_job_title) as spv"),
            DB::RAW("COUNT(ireq_mst.ireq_verificator_remark) as countremark_reviewer"),
            'mu.usr_division',
            'ireq_mst.ireq_verificator_remark',
            'ireq_mst.ireq_id',
            'ireq_mst.ireq_reason',
            'ireq_mst.ireq_status as status',
            'ireq_mst.ireq_no',
            'ireq_mst.ireq_date',
            'muu.usr_fullname as ireq_user',
            DB::raw("COALESCE(vi.official_name,vii.official_name) AS ireq_assigned_to"),
            'mu.usr_fullname as ireq_requestor',
            DB::raw('count(DISTINCT(idm.ireq_id)) as count'),
            'lr.lookup_desc as ireq_status');
        $data->WHERE('ireq_mst.ireq_requestor',$usr_id);
        $data->WHERE(function($query) use($status4,$status1, $status2, $status3, $status5){
            if(isset($status1)){
                $query->where('ireq_mst.ireq_status',$status1);
            }
            if(isset($status4)){
                $query->ORwhereNull('ireq_mst.ireq_status');
            }
            if(isset($status2)){
                $query->Orwhere('ireq_mst.ireq_status',$status2);
            }
            if(isset($status3)){
                $query->Orwhere('ireq_mst.ireq_status',$status3);
            }
            if(isset($status5)){
                $query->whereNotNull('ireq_mst.ireq_status');
            }
        });
        $data->groupBy(
            DB::raw("(usr.usr_fullname ||' - '|| sr.spv_job_title)"),
            DB::raw("COALESCE(vi.official_name,vii.official_name)"),
            'ireq_mst.ireq_reason',
            'lr.lookup_desc',
            'mu.usr_division',
            'ireq_mst.ireq_verificator_remark',
            'muu.usr_fullname',
            'ireq_mst.ireq_id',
            'ireq_mst.ireq_no',
            'ireq_mst.ireq_date',
            'ireq_mst.ireq_user',
            'ireq_mst.creation_date',
            'ireq_mst.ireq_status',
            'mu.usr_fullname',
            'muu.usr_fullname');
        $data->ORDERBY('ireq_mst.ireq_date','DESC');
        return $data->get();
    }
    public function getDetailIct($status, $createdBy, $code = NULL){
        $data = IctDetail::query();
        $data->LEFTJOIN('ireq_mst as im','ireq_dtl.ireq_id','im.ireq_id');
        $data->LEFTJOIN('user_profile up','im.ireq_user','up.user_id');
        $data->LEFTJOIN('mng_users as mu','im.ireq_requestor','mu.usr_id');
        $data->LEFTJOIN('mng_users as muu','im.ireq_user','muu.usr_id');
        $data->LEFTJOIN('vpekerja_ict vi', function($join) {
            $join->on('ireq_dtl.ireq_assigned_to1','vi.usr_id')
                  ->whereNotNull('ireq_dtl.ireq_assigned_to1');
        });
        $data->LEFTJOIN('vpekerja_ict vii', function($join) {
            $join->on('ireq_dtl.ireq_assigned_to2', 'vii.usr_id')
                  ->whereNull('ireq_dtl.ireq_assigned_to1');
        });
        $data->LEFTJOIN('lookup_refs as lr',function ($join) {
            $join->on('ireq_dtl.ireq_status','lr.lookup_code')->WHERERaw('LOWER(lr.lookup_type) LIKE ? ',[trim(strtolower('ict_status')).'%']);
        });
        $data->LEFTJOIN('lookup_refs as lrs',function ($join) {
            $join->on('ireq_dtl.ireq_type','lrs.lookup_code')->WHERERaw('LOWER(lrs.lookup_type) LIKE ? ',[trim(strtolower('req_type')).'%']);
        });
        $data->leftJoin('catalog_refs as cr',function ($join) {
            $join->on('ireq_dtl.invent_code','cr.catalog_id');
        });
        $data->leftJoin('catalog_refs as crs',function ($join) {
            $join->on('cr.parent_id','crs.catalog_id');
        });
        $data->SELECT(
            'muu.usr_fullname as ireq_user',
            'mu.usr_fullname as ireq_requestor',
            'mu.usr_fullname',
            'ireq_dtl.ireq_attachment',
            'im.ireq_no',
            'ireq_dtl.ireq_id',
            'ireq_dtl.ireq_remark',
            DB::raw("COALESCE(vi.official_name,vii.official_name) AS ireq_assigned_to"),
            'ireq_dtl.ireqd_id',
            'lr.lookup_desc as ireq_status',
            'lrs.lookup_desc as ireq_type',
            DB::raw("(crs.catalog_name ||' - '|| cr.catalog_name) as kategori"),
            'muu.usr_division',
            'im.ireq_date',
            'ireq_dtl.ireq_qty',
            'ireq_dtl.ireq_status as status');
        if(isset($status)){
            $data->WHERE('ireq_dtl.ireq_status',$status);
        }
        if(isset($createdBy)){
            $data->WHERE('im.ireq_requestor',$createdBy);
        };
        if(isset($code)){
            $data->WHERE('im.ireq_id',$code);
        };
        $data->ORDERBY('im.ireq_date','DESC');
        $data->ORDERBY('ireq_dtl.ireqd_id','ASC');
        return $data->get();
    }
    public static function saveRequest($request){
        $ict = Ict::Create([
            'ireq_id' => generate_id(),
            'ireq_date' => now(),
            'ireq_requestor'=> Auth::user()->usr_id,
            'ireq_user' => $request->usr_name,
            'ireq_spv' => $request->ireq_spv,
            'ireq_loc'=>Auth::user()->usr_loc,
            'ireq_prio_level'=>$request->priolev,
            'creation_date' => now(),
            'created_by' => Auth::user()->usr_id,
            'program_name'=>"Ict_Save",
        ]);
        return $ict;
    }
    public static function listNoRequest(){
        $data = Ict::select('ireq_no as name','ireq_id as code')
        ->ORDERBY('ireq_no','DESC')
        ->WHERENotNull('ireq_status')
        ->get();
         return $data;
    }
    public static function updateRequest($request,$code){
        $ict = Ict::where('ireq_id',$code)->first();
        $ict->ireq_prio_level = $request->ireq_prio_level;
        $ict->ireq_type = $request->ireq_type;
        $ict->ireq_user = $request->ireq_user;
        $ict->ireq_spv = $request->ireq_spv;
        $ict->last_update_date = now();
        $ict->last_updated_by = Auth::user()->usr_id;
        $ict->program_name = "Ict_Update";
        $ict->save();
        return $ict;
    }
    public static function deleteRequest($ireq_id){
        Ict::find($ireq_id)->delete();
        IctDetail::where('ireq_id',$ireq_id)->delete();
    }
}