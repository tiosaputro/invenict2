<?php

namespace App\Services;

use App\Models\Ict;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
Use App\Jobs\SendNotifInProgress;
use App\Models\IctDetail;

class IctDetailServices
{
    public static function detailDone($code){
        $data = DB::table('ireq_dtl as id')
            ->select('id.ireq_assigned_to1','id.ireq_attachment','id.ireqd_id','lr.lookup_desc as ireq_type', 
                    DB::raw("(crs.catalog_name ||' - '|| cr.catalog_name) as name"),'id.ireq_remark','id.ireq_qty','id.ireq_desc')
            ->leftjoin('ireq_mst as imm','id.ireq_id','imm.ireq_id')
            ->LEFTJOIN('catalog_refs as cr',function ($join) {
                $join->on('id.invent_code','cr.catalog_id');
            })
            ->LEFTJOIN('catalog_refs as crs',function ($join) {
                $join->on('cr.parent_id','crs.catalog_id');
            })
            ->leftjoin('lookup_refs as lr','id.ireq_type','lr.lookup_code')
            ->where('imm.ireq_id',$code)
            ->where('id.ireq_assigned_to1',Auth::user()->usr_fullname)
            ->whereRaw('LOWER(lr.lookup_type) LIKE ? ',[trim(strtolower('req_type')).'%'])
            ->orderBy('id.ireqd_id','ASC')
            ->get();
        return $data;
    }
    public static function cekStatusPenugasan($ireq_id){
        $ict = DB::table('ireq_dtl as id')
            ->LEFTJOIN('ireq_mst as im','id.ireq_id','im.ireq_id')
            ->LEFTJOIN('catalog_refs as cr',function ($join) {
                $join->on('id.invent_code','cr.catalog_id');
            })
            ->LEFTJOIN('catalog_refs as crs',function ($join) {
                $join->on('cr.parent_id','crs.catalog_id');
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
            ->SELECT('loc.loc_email','mu.usr_email','mu.usr_fullname','im.ireq_no','id.ireqd_id','vr.name as ireq_bu','im.ireq_id','dr.div_name', 'mu.usr_name',DB::raw("TO_CHAR(im.ireq_date, 'dd Mon YYYY HH24:MM') as ireq_date"),'im.ireq_requestor',
                    'im.ireq_status','im.ireq_user',DB::raw("(crs.catalog_name ||' - '|| cr.catalog_name) as invent_code"),'id.ireq_qty','lrfs.lookup_desc as ireq_type','id.ireq_remark',DB::raw("COALESCE(id.ireq_assigned_to2,id.ireq_assigned_to1) AS ireq_assigned_to"))
            ->WHERE('im.ireq_id',$ireq_id)
            ->WHERE('id.ireq_status','T')
            ->ORDERBY('id.ireqd_id','ASC')
            ->get();
        if(env('APP_ENV') != 'local'){
            $mail = $ict[0]->usr_email .= '@emp.id';
        } else {
            $mail = 'adhitya.saputro@emp.id';
        }
        SendNotifInProgress::dispatchAfterResponse($mail,$ict);
        $message = 'Success';
        return $message;

    }
    public static function ApprovedByAtasan($code){
        $dtl = DB::table('ireq_dtl')
        ->WHERE('ireq_id',$code)
        ->update([
            'ireq_status' => 'A1',
            'last_update_date' => Carbon::parse(Carbon::now())->copy()->tz('Asia/Jakarta')->format('Y-m-d H:i:s'),
            'last_updated_by' => Auth::user()->usr_name,
            'program_name' => "IctController_approveByAtasan",
        ]);
        return $dtl;
    }
    public static function getDataDetailRequest($code, $status1, $status2, $status3){
            $data = IctDetail::query();
            $data->select(
                DB::raw("COALESCE(vi2.official_name,vi1.official_name) AS ireq_assigned_to"),
                'ireq_dtl.ireq_attachment',
                'ireq_dtl.ireq_id',
                'ireq_dtl.ireq_assigned_to1_reason',
                'ireq_dtl.invent_code',
                'vi1.official_name as ireq_assigned_to1',
                'ireq_dtl.ireq_status as status',
                'vi2.official_name as ireq_assigned_to2',
                'ireq_dtl.ireqd_id',
                'lr.lookup_desc as ireq_type',
                'ireq_dtl.ireq_remark',
                'ireq_dtl.ireq_desc', 
                'ireq_dtl.ireq_qty',
                DB::raw('COUNT(ireq_dtl.ireq_assigned_to2) as ireq_count_personnel2'),
                DB::raw('COUNT(ireq_dtl.ireq_assigned_to1_reason) as ireq_count_reason'),
                DB::raw('COUNT(ireq_dtl.ireq_assigned_to1) as ireq_count_status'),
                DB::raw("(crs.catalog_name ||' - '|| cr.catalog_name) as name"),
                'llr.lookup_desc as ireq_status',
                'ireq_dtl.ireq_status as cekStatus');
            $data->leftJoin('vpekerja_ict as vi1', 'ireq_dtl.ireq_assigned_to1','vi1.usr_id');
            $data->leftJoin('vpekerja_ict as vi2', function($join) {
                $join->on('ireq_dtl.ireq_assigned_to2','vi2.usr_id')
                      ->whereNotNull('ireq_dtl.ireq_assigned_to2');
            });
            $data->leftJoin('catalog_refs as cr',function ($join){
                $join->on('ireq_dtl.invent_code','cr.catalog_id');
            });
            $data->leftJoin('catalog_refs as crs',function ($join) {
                $join->on('cr.parent_id','crs.catalog_id');
            });
            $data->leftJoin('lookup_refs as lr',function ($join) {
                $join->on('ireq_dtl.ireq_type','lr.lookup_code')
                    ->whereRaw('LOWER(lr.lookup_type) LIKE ? ',[trim(strtolower('req_type')).'%']);
            });
            $data->leftJoin('lookup_refs as llr',function ($join) {
                $join->on('ireq_dtl.ireq_status','llr.lookup_code')
                    ->whereRaw('LOWER(llr.lookup_type) LIKE ? ',[trim(strtolower('ict_status')).'%']);
            });
            $data->where('ireq_dtl.ireq_id',$code);
            $data->WHERE(function($query) use($status1, $status2, $status3){
                if(isset($status1)){
                    $query->where('ireq_dtl.ireq_status',$status1);
                }
                if(isset($status2)){
                    $query->Orwhere('ireq_dtl.ireq_status',$status2);
                }
                if(isset($status3)){
                    $query->Orwhere('ireq_dtl.ireq_status',$status3);
                }
            });
            $data->groupBy(
                DB::raw("COALESCE(vi2.official_name,vi1.official_name)"),
                'ireq_dtl.ireq_attachment',
                'ireq_dtl.ireq_id',
                'ireq_dtl.ireq_assigned_to1_reason',
                'ireq_dtl.invent_code',
                'vi1.official_name',
                'ireq_dtl.ireq_status',
                'vi2.official_name',
                'ireq_dtl.ireqd_id',
                'lr.lookup_desc',
                'ireq_dtl.ireq_remark',
                'ireq_dtl.ireq_desc', 
                'ireq_dtl.ireq_qty',
                DB::raw("(crs.catalog_name ||' - '|| cr.catalog_name)"),
                'llr.lookup_desc');
            $data->orderBy('ireq_dtl.ireqd_id','ASC');
            return $data->get();
    }
    public static function FindDetailRequest($ireqd_id,$ireq_id){
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
        ->leftjoin('ireq_mst as im','id.ireq_id','im.ireq_id')
        ->LEFTJOIN('catalog_refs as cr',function ($join) {
            $join->on('id.invent_code','cr.catalog_id');
        })
        ->LEFTJOIN('catalog_refs as crs',function ($join) {
            $join->on('cr.parent_id','crs.catalog_id');
        })
        ->leftJoin('lookup_refs as lr',function ($join) {
            $join->on('id.ireq_type','lr.lookup_code')
                 ->whereRaw('LOWER(lr.lookup_type) LIKE ? ',[trim(strtolower('req_type')).'%']);
        })
        ->where('id.ireqd_id',$ireqd_id)
        ->where('id.ireq_id',$ireq_id)
        ->first();
        return $data;
    }
    public static function RejectedByAtasan($request, $code){
        $dtl = DB::table('ireq_dtl')
        ->WHERE('ireq_id',$code)
        ->update([
            'ireq_status' => 'A2',
            'ireq_reason' => $request->ket,
            'last_update_date' => Carbon::parse(Carbon::now())->copy()->tz('Asia/Jakarta')->format('Y-m-d H:i:s'),
            'last_updated_by' => Auth::user()->usr_name,
            'program_name' => "IctController_rejectByManager",
        ]);
        return $dtl;
    }
    public static function detailVerification($code){
        $data = DB::table('ireq_dtl as id')
        ->select('id.*','lr.lookup_desc as ireq_type',DB::raw("(crs.catalog_name ||' - '|| cr.catalog_name) as invent_desc"))
        ->leftjoin('lookup_refs as lr','id.ireq_type','lr.lookup_code')
        ->LEFTJOIN('catalog_refs as cr',function ($join) {
            $join->on('id.invent_code','cr.catalog_id');
        })
        ->LEFTJOIN('catalog_refs as crs',function ($join) {
            $join->on('cr.parent_id','crs.catalog_id');
        })
        ->where('id.ireq_id',$code)
        ->whereRaw('LOWER(lr.lookup_type) LIKE ? ',[trim(strtolower('req_type')).'%'])
        ->orderby('id.ireqd_id','ASC')
        ->get();
        return $data;
    }
    public static function ApprovedByIctManager($code){
        $dtl = DB::table('ireq_dtl')
        ->WHERE('ireq_id',$code)
        ->update([
            'ireq_status' => 'A2',
            'last_update_date' => Carbon::parse(Carbon::now())->copy()->tz('Asia/Jakarta')->format('Y-m-d H:i:s'),
            'last_updated_by' => Auth::user()->usr_name,
            'program_name' => "IctController_approveByManager",
        ]);
        return $dtl;
    }
     
    public static function RejectedByIctManager($request, $code){
        $dtl = DB::table('ireq_dtl')
        ->WHERE('ireq_id',$code)
        ->update([
            'ireq_status' => 'RA2',
            'ireq_reason' => $request->ket,
            'last_update_date' => Carbon::parse(Carbon::now())->copy()->tz('Asia/Jakarta')->format('Y-m-d H:i:s'),
            'last_updated_by' => Auth::user()->usr_name,
            'program_name' => "IctController_rejectIctManager",
        ]);
        
        return $dtl;
    }

    public static function RejectedByReviewer($request, $code){
        $dtl = DB::table('ireq_dtl')
        ->WHERE('ireq_id',$code)
        ->update([
            'ireq_status' => 'RR',
            'ireq_reason' => $request->ket,
            'last_update_date' => Carbon::parse(Carbon::now())->copy()->tz('Asia/Jakarta')->format('Y-m-d H:i:s'),
            'last_updated_by' => Auth::user()->usr_name,
            'program_name' => "IctController_rejectReviewer",
        ]);
        
        return $dtl;
    }

    public static function needApprovalByHigherLevel($ireq_id){
        DB::table('ireq_dtl')
        ->WHERE('ireq_id',$ireq_id)
        ->update([
            'ireq_status' => 'NA1',
            'last_update_date' => Carbon::parse(Carbon::now())->copy()->tz('Asia/Jakarta')->format('Y-m-d H:i:s'),
            'last_updated_by' => Auth::user()->usr_name,
            'program_name' => "IctController_needApprovalAtasan",
        ]);
    }

    public static function needApprovalByIctManager($ireq_id){
        $dtl = DB::table('ireq_dtl')
        ->WHERE('ireq_id',$ireq_id)
        ->update([
            'ireq_status' => 'NA2',
            'last_update_date' => Carbon::parse(Carbon::now())->copy()->tz('Asia/Jakarta')->format('Y-m-d H:i:s'),
            'last_updated_by' => Auth::user()->usr_name,
            'program_name' => "IctController_needApprovalManager",
        ]);
        return $dtl;
    }

    public static function AcceptByPersonnel($ireq_id){
        $dtl = DB::table('ireq_dtl')
        ->where('ireq_id',$ireq_id)
        ->where('ireq_assigned_to1',Auth::user()->usr_fullname)
        ->update([
            'ireq_status' => 'T',
            'last_update_date' => Carbon::parse(Carbon::now())->copy()->tz('Asia/Jakarta')->format('Y-m-d H:i:s'),
            'last_updated_by' => Auth::user()->usr_name,
            'program_name' => "IctDetailController_abp",
        ]);
        return $dtl;
    }

    public static function rejectedByPersonnel($request, $ireq_id){
        $dtl = DB::table('ireq_dtl')
        ->where('ireq_id',$ireq_id)
        ->where('ireq_assigned_to1',Auth::user()->usr_fullname)
        ->update([
            'ireq_status' => 'RT',
            'ireq_assigned_to1_reason' => $request->ireq_reason,
            'last_update_date' => Carbon::parse(Carbon::now())->copy()->tz('Asia/Jakarta')->format('Y-m-d H:i:s'),
            'last_updated_by' => Auth::user()->usr_name,
            'program_name' => "IctDetailController_rbp"
        ]);
        return $dtl;
    }
}