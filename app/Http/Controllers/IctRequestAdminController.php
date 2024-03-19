<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseFormatter;
use Illuminate\Support\Facades\DB;
use App\Models\Mng_user;
class IctRequestAdminController extends Controller
{
    protected $role;
    protected $userMenu;
    function __construct(){
        $this->middleware('auth:sanctum');
        $this->role = "Admin";
        $this->middleware(function ($request, $next) {
          $this->userMenu = Mng_User::roles();
            if($this->userMenu->contains($this->role)){    
                return $next($request);
            } else {
                return response(["message"=>"Cannot Access"],403);
            }
        });
    }
    function getDataIct()
    {
            $data['ict'] = DB::table('ireq_mst as im')
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
            ->ORDERBY('im.ireq_date','DESC')
            ->get();

            $data['ict1'] = DB::table('ireq_mst as im')
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

            $data['ict2'] = DB::table('ireq_mst as im')
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

            $data['ict3'] = DB::table('ireq_mst as im')
            ->LEFTJOIN('lookup_refs as lr','im.ireq_status','lr.lookup_code')
            ->SELECT('im.ireq_requestor','im.ireq_id','im.ireq_no','im.ireq_date','im.ireq_status as status','im.ireq_user',DB::raw("COALESCE(im.ireq_assigned_to2,im.ireq_assigned_to1) AS ireq_assigned_to"),'lr.lookup_desc as ireq_status')
            ->WHERE('im.ireq_status','T')
            ->WHERERaw('LOWER(lr.lookup_type) LIKE ? ',[trim(strtolower('ict_status')).'%'])
            ->ORDERBY('im.ireq_date','DESC')
            ->get();

            $data['ict4'] =  DB::table('ireq_dtl as id')
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
            ->leftJoin('catalog_refs as cr',function ($join) {
                $join->on('id.invent_code','cr.catalog_id');
            })
            ->leftJoin('catalog_refs as crs',function ($join) {
                $join->on('cr.parent_id','crs.catalog_id');
            })
            ->SELECT('id.ireq_attachment','im.ireq_no','id.ireq_id','id.ireq_remark',DB::raw("COALESCE(id.ireq_assigned_to2,id.ireq_assigned_to1) AS ireq_assigned_to"),'id.ireqd_id',
            'lr.lookup_desc as ireq_status','lrs.lookup_desc as ireq_type',DB::raw("(crs.catalog_name ||' - '|| cr.catalog_name) as kategori"),'im.ireq_date','im.ireq_requestor','im.ireq_user',
            'dr.div_name','id.ireq_qty','id.ireq_status as status')
            ->WHERE('id.ireq_status','D')
            ->ORDERBY('im.ireq_date','DESC')
            ->ORDERBY('id.ireqd_id','ASC')
            ->get();

            $data['ict5'] = DB::table('ireq_dtl as id')
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
            ->leftJoin('catalog_refs as cr',function ($join) {
                $join->on('id.invent_code','cr.catalog_id');
            })
            ->leftJoin('catalog_refs as crs',function ($join) {
                $join->on('cr.parent_id','crs.catalog_id');
            })
            ->SELECT('id.ireq_attachment','id.ireq_value','im.ireq_no','id.ireq_id','id.ireq_remark',DB::raw("COALESCE(id.ireq_assigned_to2,id.ireq_assigned_to1) AS ireq_assigned_to"),'id.ireqd_id',
            'lr.lookup_desc as ireq_status','lrs.lookup_desc as ireq_type',DB::raw("(crs.catalog_name ||' - '|| cr.catalog_name) as kategori"),'im.ireq_date','im.ireq_requestor','im.ireq_user',
            'dr.div_name','id.ireq_qty','id.ireq_status as status')
            ->WHERE('id.ireq_status','C')
            ->ORDERBY('im.ireq_date','DESC')
            ->ORDERBY('id.ireqd_id','ASC')
            ->get();

            $data['ict6'] = DB::table('ireq_mst as id')
            ->LEFTJOIN('lookup_refs as lr','id.ireq_status','lr.lookup_code')
            ->SELECT('id.ireq_id','id.ireq_no','id.ireq_user','id.ireq_date','lr.lookup_desc as ireq_status','id.ireq_status as status','id.ireq_requestor')
            ->WHERERaw('LOWER(lr.lookup_type) LIKE ? ',[trim(strtolower('ict_status')).'%'])
            ->WHERENotNull('id.ireq_status')
            ->ORDERBY('id.ireq_date','DESC')
            ->get();
            return ResponseFormatter::success($data,'Successfully Get Data');
        }
}
