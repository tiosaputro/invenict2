<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
Use App\Jobs\SendNotifInProgress;
use App\Models\IctDetail;

class IctDetailServices
{
    public static function getDataWithFilter($code, $status){
        $data = IctDetail::query();
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
        if(!empty($code)){
            $data->WHERE('im.ireq_id', $code);
        }
        if(!empty($status)){
            $data->WHERE('ireq_dtl.ireq_status',$status);
        }
        $data->ORDERBY('ireq_dtl.ireqd_id','ASC');
        return $data->get();
    }
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
            ->where('id.ireq_assigned_to1',Auth::user()->usr_id)
            ->whereRaw('LOWER(lr.lookup_type) LIKE ? ',[trim(strtolower('req_type')).'%'])
            ->orderBy('id.ireqd_id','ASC')
            ->get();
        return $data;
    }
    public static function cekStatusPenugasan($code){
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
            ->LEFTJOIN('vpekerja_ict vi', function($join) {
                $join->on('im.ireq_assigned_to1','vi.usr_id')
                      ->whereNotNull('im.ireq_assigned_to1');
            })
            ->LEFTJOIN('vpekerja_ict vii', function($join) {
                $join->on('im.ireq_assigned_to2', 'vii.usr_id')
                      ->whereNull('im.ireq_assigned_to1');
            })
            ->LEFTJOIN('mng_users mu','im.ireq_requestor','mu.usr_id')
            ->LEFTJOIN('location_refs loc','im.ireq_loc','loc.loc_code')
            ->LEFTJOIN('supervisor_refs sr','im.ireq_spv','sr.spv_id')
            ->LEFTJOIN('mng_users mus','sr.spv_name','mus.usr_id')
            ->LEFTJOIN('mng_users usr','im.ireq_user','usr.usr_id')
            ->SELECT(
                'mus.usr_email as spv_mail',
                'loc.loc_email',
                'mu.usr_fullname',
                'sr.spv_name',
                'mu.usr_email as user_mail',
                'im.ireq_no',
                'id.ireqd_id',
                'vr.name as ireq_bu',
                'im.ireq_id',
                'mu.usr_division as div_name', 
                DB::raw("TO_CHAR(im.ireq_date, 'dd Mon YYYY HH24:MM') as ireq_date"),
                DB::raw("COALESCE(vi.official_name,vii.official_name) AS ireq_assigned_to"),
                'mu.usr_fullname as ireq_requestor',
                'usr.usr_fullname as ireq_user',
                DB::raw("(crs.catalog_name ||' - '|| cr.catalog_name) as invent_code"),
                'id.ireq_qty',
                'lrfs.lookup_desc as ireq_type',
                'id.ireq_remark')
            ->WHERE('im.ireq_id',$code)
            ->ORDERBY('id.ireqd_id','ASC')
            ->get();
        if(env('APP_ENV') != 'local'){
            $mail_user = $ict[0]->usr_email;
        } else {
            $mail_user = 'adhitya.saputro@emp.id';
        }
        SendNotifInProgress::dispatchAfterResponse($mail_user, $ict);
        $message = 'Success';
        return $message;

    }
    public static function ApprovedByAtasan($code){
        $dtl = DB::table('ireq_dtl')
        ->WHERE('ireq_id',$code)
        ->update([
            'ireq_status' => 'A1',
            'last_update_date' => now(),
            'last_updated_by' => Auth::user()->usr_id,
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
            'last_update_date' => now(),
            'last_updated_by' => Auth::user()->usr_id,
            'program_name' => "IctController_rejectByManager",
        ]);
        return $dtl;
    }
    public static function detailVerification($code){
        $data = DB::table('ireq_dtl as id')
        ->select(
            'id.*',
            'lr.lookup_desc as ireq_type',
            DB::raw("(crs.catalog_name ||' - '|| cr.catalog_name) as invent_desc"))
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
            'last_update_date' => now(),
            'last_updated_by' => Auth::user()->usr_id,
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
            'last_update_date' => now(),
            'last_updated_by' => Auth::user()->usr_id,
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
            'last_update_date' => now(),
            'last_updated_by' => Auth::user()->usr_id,
            'program_name' => "IctController_rejectReviewer",
        ]);
        
        return $dtl;
    }

    public static function needApprovalByHigherLevel($ireq_id){
        DB::table('ireq_dtl')
        ->WHERE('ireq_id',$ireq_id)
        ->update([
            'ireq_status' => 'NA1',
            'last_update_date' => now(),
            'last_updated_by' => Auth::user()->usr_id,
            'program_name' => "IctController_needApprovalAtasan",
        ]);
    }

    public static function needApprovalByIctManager($ireq_id){
        $dtl = DB::table('ireq_dtl')
        ->WHERE('ireq_id',$ireq_id)
        ->update([
            'ireq_status' => 'NA2',
            'last_update_date' => now(),
            'last_updated_by' => Auth::user()->usr_id,
            'program_name' => "IctController_needApprovalManager",
        ]);
        return $dtl;
    }

    public static function AcceptByPersonnel($ireq_id){
        $dtl = DB::table('ireq_dtl')
        ->where('ireq_id',$ireq_id)
        ->where('ireq_assigned_to1',Auth::user()->usr_id)
        ->update([
            'ireq_status' => 'T',
            'last_update_date' => now(),
            'last_updated_by' => Auth::user()->usr_id,
            'program_name' => "IctDetailController_abp",
        ]);
        return $dtl;
    }

    public static function rejectedByPersonnel($request, $ireq_id){
        $dtl = DB::table('ireq_dtl')
        ->where('ireq_id',$ireq_id)
        ->where('ireq_assigned_to1',Auth::user()->usr_id)
        ->update([
            'ireq_status' => 'RT',
            'ireq_assigned_to1_reason' => $request->ireq_reason,
            'last_update_date' => now(),
            'last_updated_by' => Auth::user()->usr_id,
            'program_name' => "IctDetailController_rbp"
        ]);
        return $dtl;
    }
}