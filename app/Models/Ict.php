<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Jobs\SendNotifApprovedFromHigherLevel;
use App\Jobs\SendNotifRejectByHigherLevel;
use App\Jobs\SendNotifApprovedFromIctManager;
use App\Jobs\SendNotifRejectByIctManager;
use App\Jobs\SendNotifToRequestor;
use App\Jobs\SendNotifRejectByReviewer;
use App\Jobs\SendNotifApproval;
use App\Jobs\SendNotifWaitingApprovalFromHigherLevel;
use App\Jobs\SendNotifIctManager;
use App\Jobs\SendNotifWaitingApprovalFromIctManager;
use App\Jobs\SendNotifPersonnel;
use App\Jobs\SendNotifWaitingRecieveByPersonnel;
use Twilio\Rest\Lookups;

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

    public static function ApprovedByAtasan($code){
        $ict = Ict::where('ireq_id',$code)->first();
        $ict->ireq_status = 'A1';
        $ict->ireq_approver1 = Auth::user()->usr_id;
        $ict->ireq_approver1_date = Carbon::parse(Carbon::now())->copy()->tz('Asia/Jakarta')->format('Y-m-d H:i:s');
        $ict->last_updated_by = Auth::user()->usr_id;
        $ict->last_update_date = Carbon::parse(Carbon::now())->copy()->tz('Asia/Jakarta')->format('Y-m-d H:i:s');
        $ict->program_name = "IctController_approveByAtasan";
        $ict->save();

        $data = DB::table('ireq_dtl as id')
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
                'im.ireq_reason',
                'mu.usr_division as div_name', 
                DB::raw("TO_CHAR(im.ireq_date, 'dd Mon YYYY HH24:MM') as ireq_date"),
                'mu.usr_fullname as ireq_requestor',
                'usr.usr_fullname as ireq_user',
                DB::raw("(crs.catalog_name ||' - '|| cr.catalog_name) as invent_code"),
                'id.ireq_qty',
                'lrfs.lookup_desc as ireq_type',
                'id.ireq_remark')
            ->WHERE('im.ireq_id',$code)
            ->ORDERBY('id.ireqd_id','ASC')
            ->get();
        $user_mail = $data[0]->user_mail;
        SendNotifApprovedFromHigherLevel::dispatchAfterResponse($user_mail,$data);
        return $ict;
    }

    public static function RejectedByAtasan($request, $code){
        $ict = Ict::where('ireq_id',$code)->first();
        $ict->ireq_status = 'RA1';
        $ict->ireq_approver1 = Auth::user()->usr_id;
        $ict->ireq_approver1_date = Carbon::parse(Carbon::now())->copy()->tz('Asia/Jakarta')->format('Y-m-d H:i:s');
        $ict->ireq_reason = $request->ket;
        $ict->last_update_date = Carbon::parse(Carbon::now())->copy()->tz('Asia/Jakarta')->format('Y-m-d H:i:s');
        $ict->last_updated_by = Auth::user()->usr_id;
        $ict->program_name = "IctController_rejectByAtasan";
        $ict->save();

        $data = DB::table('ireq_dtl as id')
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
                'im.ireq_reason',
                'mu.usr_division as div_name', 
                DB::raw("TO_CHAR(im.ireq_date, 'dd Mon YYYY HH24:MM') as ireq_date"),
                'mu.usr_fullname as ireq_requestor',
                'usr.usr_fullname as ireq_user',
                DB::raw("(crs.catalog_name ||' - '|| cr.catalog_name) as invent_code"),
                'id.ireq_qty',
                'lrfs.lookup_desc as ireq_type',
                'id.ireq_remark')
            ->WHERE('im.ireq_id',$code)
            ->ORDERBY('id.ireqd_id','ASC')
            ->get();
        $user_mail = $data[0]->user_mail;
        SendNotifRejectByHigherLevel::dispatchAfterResponse($user_mail,$data);
        return $ict;
    }
    
    public static function ApprovedByIctManager($request,$code){
        $ict = Ict::where('ireq_id',$code)->first();
        $ict->ireq_status = 'A2';
        $ict->ireq_approver2_remark = $request->remark;
        $ict->ireq_approver2 = Auth::user()->usr_id;
        $ict->ireq_approver2_date = now();
        $ict->last_updated_by = Auth::user()->usr_id;
        $ict->last_update_date = now();
        $ict->program_name = "IctController_approveByManager";
        $ict->save();

        $data = DB::table('ireq_dtl as id')
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
                'mu.usr_fullname as ireq_requestor',
                'usr.usr_fullname as ireq_user',
                DB::raw("(crs.catalog_name ||' - '|| cr.catalog_name) as invent_code"),
                'id.ireq_qty',
                'lrfs.lookup_desc as ireq_type',
                'id.ireq_remark')
            ->WHERE('im.ireq_id',$code)
            ->ORDERBY('id.ireqd_id','ASC')
            ->get();
            $user_mail = $data[0]->user_mail;
            SendNotifApprovedFromIctManager::dispatchAfterResponse($user_mail,$data);

            return $ict;
    }

    public static function RejectedByIctManager($request, $code){
        $ict = Ict::where('ireq_id',$code)->first();
        $ict->ireq_status = 'RA2';
        $ict->ireq_approver1 = Auth::user()->usr_id;
        $ict->ireq_approver1_date = Carbon::parse(Carbon::now())->copy()->tz('Asia/Jakarta')->format('Y-m-d H:i:s');
        $ict->ireq_reason = $request->ket;
        $ict->last_update_date = Carbon::parse(Carbon::now())->copy()->tz('Asia/Jakarta')->format('Y-m-d H:i:s');
        $ict->last_updated_by = Auth::user()->usr_id;
        $ict->program_name = "IctController_rejectByManager";
        $ict->save();

        $ICT = DB::table('ireq_dtl as id')
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
            ->LEFTJOIN('mng_users as mu','im.ireq_requestor','mu.usr_id')
            ->LEFTJOIN('location_refs as loc','im.ireq_loc','loc.loc_code')
            ->SELECT('im.ireq_reason','loc.loc_email','mu.usr_fullname','mu.usr_email','im.ireq_no','id.ireqd_id','vr.name as ireq_bu','im.ireq_id','dr.div_name', 'mu.usr_name',DB::raw("TO_CHAR(im.ireq_date, 'dd Mon YYYY HH24:MM') as ireq_date"),'im.ireq_requestor',
                    'im.ireq_user',DB::raw("(crs.catalog_name ||' - '|| cr.catalog_name) as invent_code"),'id.ireq_qty','lrfs.lookup_desc as ireq_type','id.ireq_remark')
            ->WHERE('im.ireq_id',$code)
            ->ORDERBY('id.ireqd_id','ASC')
            ->get();

        $user_mail = $ICT[0]->user_mail;
        SendNotifRejectByIctManager::dispatchAfterResponse($user_mail,$ICT);
        
        return $ict;
    }

    public static function sendMailToRequestor($request){
        $Ict = DB::table('ireq_dtl as id')
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
            ->SELECT('im.ireq_no','mu.usr_fullname','mu.usr_email','im.ireq_no','id.ireqd_id','vr.name as ireq_bu','im.ireq_id','dr.div_name', 'mu.usr_name',DB::raw("TO_CHAR(im.ireq_date, 'dd Mon YYYY HH24:MM') as ireq_date"),'im.ireq_requestor',
                    'im.ireq_user',DB::raw("(crs.catalog_name ||' - '|| cr.catalog_name) as invent_code"),'id.ireq_qty','lrfs.lookup_desc as ireq_type','id.ireq_remark')
            ->WHERE('im.ireq_id',$request->ireq_id)
            ->ORDERBY('id.ireqd_id','ASC')
            ->get();
        $to= $request->to;
        $footer = $request->footer;
        $body = $request->body;
        SendNotifToRequestor::dispatchAfterResponse($Ict,$to,$footer,$body);
        
        return $Ict;
    }

    public static function RejectedByReviewer($request, $code){
        $ict = Ict::where('ireq_id',$code)->first();
        $ict->ireq_status = 'RR';
        $ict->ireq_approver1 = Auth::user()->usr_id;
        $ict->ireq_approver1_date = Carbon::parse(Carbon::now())->copy()->tz('Asia/Jakarta')->format('Y-m-d H:i:s');
        $ict->ireq_reason = $request->ket;
        $ict->last_update_date = Carbon::parse(Carbon::now())->copy()->tz('Asia/Jakarta')->format('Y-m-d H:i:s');
        $ict->last_updated_by = Auth::user()->usr_id;
        $ict->program_name = "IctController_rejectByReviewer";
        $ict->save();

        $ICT = DB::table('ireq_dtl as id')
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
            ->SELECT('im.ireq_reason','loc.loc_email','mu.usr_fullname','mu.usr_email','im.ireq_no','id.ireqd_id','vr.name as ireq_bu','im.ireq_id','dr.div_name', 'mu.usr_name',DB::raw("TO_CHAR(im.ireq_date, 'dd Mon YYYY HH24:MM') as ireq_date"),'im.ireq_requestor',
                    'im.ireq_user',DB::raw("(crs.catalog_name ||' - '|| cr.catalog_name) as invent_code"),'id.ireq_qty','lrfs.lookup_desc as ireq_type','id.ireq_remark')
            ->WHERE('im.ireq_id',$code)
            ->ORDERBY('id.ireqd_id','ASC')
            ->get();
        $email_address = $ICT[0]->usr_email .= '@emp.id';
        SendNotifRejectByReviewer::dispatchAfterResponse($email_address,$ICT);
        
        return $ict;
    }
 
    public static function NeedApprovalByHigherLevel($ireq_id){
        $Ict = Ict::find($ireq_id);

        $Ict->ireq_status = 'NA1';
        $Ict->ireq_verificator = Auth::user()->usr_id;
        $Ict->last_update_date = Carbon::parse(Carbon::now())->copy()->tz('Asia/Jakarta')->format('Y-m-d H:i:s');
        $Ict->last_updated_by = Auth::user()->usr_id;
        $Ict->program_name = "IctController_needApprovalAtasan";
        $Ict->save();

        $data = DB::table('ireq_dtl as id') // for higher level
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
                'mu.usr_fullname as ireq_requestor',
                'usr.usr_fullname as ireq_user',
                DB::raw("(crs.catalog_name ||' - '|| cr.catalog_name) as invent_code"),
                'id.ireq_qty',
                'lrfs.lookup_desc as ireq_type',
                'id.ireq_remark')
            ->WHERE('im.ireq_id',$ireq_id)
            ->ORDERBY('id.ireqd_id','ASC')
            ->get();

        Link::createLink($data[0]->spv_name,$ireq_id, 'Ict Request Verifikasi From Email');
        
        $LINK = Link::where('ireq_id',$ireq_id)->WHERE('usr_id',$data[0]->spv_name)->orderBy('created_at', 'desc')->first();

        if(env('APP_ENV') != 'local'){
            $spv_mail = $data[0]->spv_mail;
            $user_mail = $data[0]->user_mail;
        } else {
            $spv_mail = 'adhitya.saputro@emp.id';
            $user_mail ='adhitya.saputro@emp.id';
        }

        SendNotifApproval::dispatchAfterResponse($spv_mail,$data,$LINK);
        SendNotifWaitingApprovalFromHigherLevel::dispatchAfterResponse($user_mail,$data);
        return $Ict;
    }

    public static function needApprovalByIctManager($ireq_id){
        $ICT = Ict::where('ireq_id',$ireq_id)->first();
        $ICT->ireq_status = 'NA2';
        $ICT->ireq_verificator = Auth::user()->usr_id;
        $ICT->last_update_date = Carbon::parse(Carbon::now())->copy()->tz('Asia/Jakarta')->format('Y-m-d H:i:s');
        $ICT->last_updated_by = Auth::user()->usr_id;
        $ICT->program_name = "IctController_needApprovalManager";
        $ICT->save();

        $ictManager = Lookup_Refs::Select('lookup_desc','lookup_code')->where('lookup_type','ict_manager')->first();
        $data = DB::table('ireq_dtl as id') // for higher level
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
        ->LEFTJOIN('mng_users mu','im.ireq_requestor','mu.usr_id')
        ->LEFTJOIN('mng_users usr','im.ireq_user','usr.usr_id')
        ->LEFTJOIN('location_refs loc','im.ireq_loc','loc.loc_code')
        ->SELECT(
            'loc.loc_email',
            'mu.usr_fullname',
            'mu.usr_fullname as ireq_requestor',
            'usr.usr_fullname as ireq_user',
            'mu.usr_email as user_mail',
            'im.ireq_no',
            'id.ireqd_id',
            'vr.name as ireq_bu',
            'im.ireq_id',
            'mu.usr_division as div_name', 
            'mu.usr_name',
            DB::raw("TO_CHAR(im.ireq_date, 'dd Mon YYYY HH24:MM') as ireq_date"),
            DB::raw("(crs.catalog_name ||' - '|| cr.catalog_name) as invent_code"),
            'id.ireq_qty',
            'lrfs.lookup_desc as ireq_type',
            'id.ireq_remark')
        ->WHERE('im.ireq_id',$ireq_id)
        ->ORDERBY('id.ireqd_id','ASC')
        ->get();

        Link::createLink($ictManager->lookup_code,$ireq_id, 'Ict Request Verifikasi From Email ICT Manager');
        $LINK = Link::where('ireq_id',$ireq_id)->where('usr_id',$ictManager->lookup_code)->orderBy('created_at', 'desc')->first();
        if(env('APP_ENV') != 'local'){    
            $mail_manager = $ictManager->lookup_desc;
            $mail_user = $data[0]->user_mail;
         }else {
            $mail_manager = 'adhitya.saputro@emp.id';
            $mail_user ='adhitya.saputro@emp.id';
        }
        SendNotifIctManager::dispatchAfterResponse($mail_manager,$data,$LINK);
        SendNotifWaitingApprovalFromIctManager::dispatchAfterResponse($mail_user,$data);

        return $ICT;
    }

    public static function assignPerRequest($request){
        $ict = Ict::where('ireq_id',$request->id)->first();
        if($ict->ireq_status == 'RT'){
            $ict->ireq_assigned_to2 = $request->name;
            $ict->last_updated_by = Auth::user()->usr_id;
            $ict->last_update_date = Carbon::parse(Carbon::now())->copy()->tz('Asia/Jakarta')->format('Y-m-d H:i:s');
            $ict->program_name = "IctController_asignPerRequestReviewer";
            $ict->save();

            DB::table('ireq_dtl')
            ->WHERE('ireq_id',$request->id)
            ->update([
                'ireq_assigned_to2' =>$request->name,
                'last_updated_by' => Auth::user()->usr_id,
                'last_update_date' =>  Carbon::parse(Carbon::now())->copy()->tz('Asia/Jakarta')->format('Y-m-d H:i:s'),
                'program_name' => "IctController_asignPerRequestReviewer"
            ]);
            return $ict;
        }
        else{
            $ict->ireq_assigned_to1 = $request->name;
            $ict->last_updated_by = Auth::user()->usr_id;
            $ict->last_update_date =  Carbon::parse(Carbon::now())->copy()->tz('Asia/Jakarta')->format('Y-m-d H:i:s');
            $ict->program_name = "IctController_asignPerRequestReviewer";
            $ict->save();

            DB::table('ireq_dtl')
            ->WHERE('ireq_id',$request->id)
            ->update([
                'ireq_assigned_to1'=>$request->name,
                'last_update_date'=> Carbon::parse(Carbon::now())->copy()->tz('Asia/Jakarta')->format('Y-m-d H:i:s'),
                'last_updated_by'=>Auth::user()->usr_id,
                'program_name'=>"IctController_asignPerRequestReviewer"
            ]);
            return $ict;
        }
    }

    public static function submitAssignPerRequets($ireq_id){
        $ict = Ict::where('ireq_id',$ireq_id)->first();
        $dtll = IctDetail::where('ireq_id',$ireq_id)->get();
        $name = [];
        $email = [];
         if($ict->ireq_status == 'RT'){
            foreach ($dtll as $d) {
                array_push($name, $d->ireq_assigned_to2);
            }
            $status = 'T'; 
         }else{
            foreach ($dtll as $d) {
                array_push($name, $d->ireq_assigned_to1);
            }
            $status = 'NT';
         }
            $ict->ireq_status = $status;
            $ict->ireq_verificator = Auth::user()->usr_id;
            $ict->ireq_assigned_date = Carbon::parse(Carbon::now())->copy()->tz('Asia/Jakarta')->format('Y-m-d H:i:s');
            $ict->last_update_date = Carbon::parse(Carbon::now())->copy()->tz('Asia/Jakarta')->format('Y-m-d H:i:s');   
            $ict->last_updated_by = Auth::user()->usr_id;
            $ict->program_name = "IctController_submitAssignPerRequest";
            $ict->save();
            
            DB::table('ireq_dtl')
            ->WHERE('ireq_id',$ireq_id)
            ->update([
                'ireq_status' =>$status,
                'ireq_assigned_date' => Carbon::parse(Carbon::now())->copy()->tz('Asia/Jakarta')->format('Y-m-d H:i:s'),
                'last_update_date' => Carbon::parse(Carbon::now())->copy()->tz('Asia/Jakarta')->format('Y-m-d H:i:s'),
                'last_updated_by' => Auth::user()->usr_id,
                'program_name' => "IctController_submitAssignPerRequest"
            ]);
            $usr_email = DB::table('mng_users')->SELECT('usr_email')->WHEREIn('usr_fullname',$name)->pluck('usr_email');
            foreach($usr_email as $s){
                $emailpush = $s .= '@emp.id';
                array_push($email,$emailpush);
            }
            $Ict = DB::table('ireq_dtl as id')
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
            ->LEFTJOIN('location_refs as lr',function ($join) {
                $join->on('im.ireq_loc','lr.loc_code');
            })
            ->LEFTJOIN('divisi_refs as dr','im.ireq_divisi_user','dr.div_id')
            ->LEFTJOIN('mng_users as mu','im.ireq_requestor','mu.usr_name')
            ->SELECT('lr.loc_email','im.ireq_no','mu.usr_fullname','mu.usr_email','im.ireq_no','id.ireqd_id','vr.name as ireq_bu','im.ireq_id','dr.div_name', 'mu.usr_name',DB::raw("TO_CHAR(im.ireq_date, 'dd Mon YYYY HH24:MM') as ireq_date"),'im.ireq_requestor',
                    'im.ireq_user',DB::raw("COALESCE(id.ireq_assigned_to2,id.ireq_assigned_to1) AS ireq_assigned_to"),DB::raw("(crs.catalog_name ||' - '|| cr.catalog_name) as invent_code"),'id.ireq_qty','lrfs.lookup_desc as ireq_type','id.ireq_remark')
            ->WHERE('im.ireq_id',$ireq_id)
            ->ORDERBY('id.ireqd_id','ASC')
            ->get();
            
            if(env('APP_ENV') != 'local'){    
                $email_address = $Ict[0]->usr_email .= '@emp.id';
                $email = $email;
             }else {
                $email = 'adhitya.saputro@emp.id';
                $email_address ='adhitya.saputro@emp.id';
            }
            SendNotifPersonnel::dispatchAfterResponse($email,$ict);
            SendNotifWaitingRecieveByPersonnel::dispatchAfterResponse($email_address,$Ict);
            
        return $ict;
    }
}
