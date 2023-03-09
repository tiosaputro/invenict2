<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Model\Ict;
use App\Model\IctDetail;
use App\Helpers\ResponseFormatter;
use App\Mng_User;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\IctExportReviewerPermohonan;
use App\Exports\IctExportReviewerAtasanDivisi;
use App\Exports\IctExportReviewerIctManager;
use App\Exports\IctExportRejectReviewer;
use App\Exports\IctExportReviewerAssignmentRequest;
use App\Exports\IctExportReviewerSedangDikerjakan;
use App\Exports\IctExportReviewerSudahDikerjakan;
use App\Exports\IctExportReviewerSelesai;

class IctRequestReviewerController extends Controller
{
    protected $to;
    protected $userMenu;
    public function __construct(){
        $this->middleware('auth:sanctum');
        $this->to = "/ict-request-reviewer";
        $this->middleware(function ($request, $next) {
          $this->userMenu = Mng_User::menu();
            if($this->userMenu->contains($this->to)){    
                return $next($request);
            } else {
                return response(["message"=>"Cannot Access"],403);
            }
        });
    }
    function sendMailtoRequestor(Request $request)
    {
        $save = Ict::sendMailToRequestor($request);
        return ResponseFormatter::success($save,'Successfully Sent Email to Requestor');
    }
    
    function getRemarkReviewer($ireq_id)
    {
        $ict = Ict::select('ireq_verificator_remark')->where('ireq_id',$ireq_id)->first();
        return json_encode($ict);
    }
    function saveRemarkReviewer(Request $request)
    {
        $ict = Ict::where('ireq_id',$request->id)->first();
        $ict->ireq_verificator_remark = $request->remark;
        $ict->save();
        return ResponseFormatter::success($ict,'Successfully save remark');
    }
    function getDataReviewer()
    {
            if(Auth::user()->usr_loc == "OJ"){
                $loc = ["OJ"];
            }
            if(Auth::user()->usr_loc == "OB" OR Auth::user()->usr_loc == "FB"){
                $loc = ["OB","FB"];
            }
            if(Auth::user()->usr_loc == "OK" OR Auth::user()->usr_loc == "FK" ){
                $loc = ["OK","FK"];
            }
            $ict = DB::table('ireq_mst as im')
            ->SELECT('im.ireq_id','im.ireq_verificator_remark','im.ireq_status as status','im.ireq_no','im.ireq_date','im.ireq_user','im.ireq_requestor','dr.div_name', 
                    DB::raw('count(idd.ireq_assigned_to1) as ireq_count_status'), DB::raw('count(idd.ireq_id) as ireq_count_id'),
                    DB::raw('count(im.ireq_verificator_remark) as count_remark'),'mu.usr_email','lr.lookup_desc as ireq_status',DB::raw("COALESCE(im.ireq_assigned_to2,im.ireq_assigned_to1) AS ireq_assigned_to"))
            ->LEFTJOIN('divisi_refs as dr','im.ireq_divisi_user','dr.div_id')
            ->LEFTJOIN('ireq_dtl as idd','im.ireq_id','idd.ireq_id')
            ->LEFTJOIN('lookup_refs as lr','im.ireq_status','lr.lookup_code')
            ->LEFTJOIN('mng_users as mu','im.created_by','mu.usr_name')
            ->WHERE('im.ireq_status','P')
            ->WHEREIn('im.ireq_loc',$loc)
            ->WHERERaw('LOWER(lr.lookup_type) LIKE ? ',[trim(strtolower('ict_status')).'%'])
            ->groupBy('im.ireq_id','im.ireq_verificator_remark','mu.usr_email','im.ireq_status','im.ireq_no','im.ireq_date','im.ireq_user','im.ireq_requestor','dr.div_name','im.creation_date','lr.lookup_desc',DB::raw("COALESCE(im.ireq_assigned_to2,im.ireq_assigned_to1)"))
            ->ORDERBY('im.ireq_date','DESC')
            ->get(); 

            $ict1 = DB::table('ireq_mst as im')
            ->SELECT('im.ireq_id','im.ireq_verificator_remark','im.ireq_status as status','im.ireq_no','im.ireq_date','im.ireq_user','im.ireq_requestor','dr.div_name','lr.lookup_desc as ireq_status','im.ireq_status as status',
            DB::raw('count(im.ireq_verificator_remark) as count_remark'),DB::raw('count(idd.ireq_assigned_to1) as ireq_count_status'), DB::raw('count(idd.ireq_id) as ireq_count_id'),DB::raw("COALESCE(im.ireq_assigned_to2,im.ireq_assigned_to1) AS ireq_assigned_to"))
            ->LEFTJOIN('divisi_refs as dr','im.ireq_divisi_user','dr.div_id')
            ->LEFTJOIN('ireq_dtl as idd','im.ireq_id','idd.ireq_id')
            ->LEFTJOIN('lookup_refs as lr','im.ireq_status','lr.lookup_code')
            ->WHERE(function($query){
                return $query
                ->WHERE('im.ireq_status','NA1')
                ->Orwhere('im.ireq_status','A1');
            })
            ->WHEREIn('im.ireq_loc',$loc)
            ->WHERERaw('LOWER(lr.lookup_type) LIKE ? ',[trim(strtolower('ict_status')).'%'])
            ->groupBy('im.ireq_id','im.ireq_verificator_remark',DB::raw("COALESCE(im.ireq_assigned_to2,im.ireq_assigned_to1)"),'im.ireq_status','im.ireq_no','im.ireq_date','im.ireq_user','im.ireq_requestor','dr.div_name','im.creation_date','lr.lookup_desc','im.ireq_status')
            ->ORDERBY('im.ireq_date','DESC')
            ->get(); 

            $ict2 = DB::table('ireq_mst as im')
            ->SELECT('im.ireq_id','im.ireq_verificator_remark','im.ireq_status as status','im.ireq_no','im.ireq_date','im.ireq_user','im.ireq_requestor','dr.div_name','lr.lookup_desc as ireq_status','im.ireq_status as status',
            DB::raw('count(im.ireq_verificator_remark) as count_remark'),DB::raw('count(im.ireq_approver2_remark) as count_remark_approver2'),'im.ireq_approver2_remark',DB::raw('count(idd.ireq_assigned_to1) as ireq_count_status'), DB::raw("COALESCE(im.ireq_assigned_to2,im.ireq_assigned_to1) AS ireq_assigned_to"),DB::raw('count(idd.ireq_id) as ireq_count_id'))
            ->LEFTJOIN('divisi_refs as dr','im.ireq_divisi_user','dr.div_id')
            ->LEFTJOIN('ireq_dtl as idd','im.ireq_id','idd.ireq_id')
            ->LEFTJOIN('lookup_refs as lr','im.ireq_status','lr.lookup_code')
            ->WHEREIn('im.ireq_loc',$loc)
            ->WHERE(function ($query){
                return $query
                ->WHERE('im.ireq_status','NA2')
                ->OrWhere('im.ireq_status','A2');
            })
            ->WHERERaw('LOWER(lr.lookup_type) LIKE ? ',[trim(strtolower('ict_status')).'%'])
            ->groupBy('im.ireq_approver2_remark','im.ireq_id','im.ireq_verificator_remark',DB::raw("COALESCE(im.ireq_assigned_to2,im.ireq_assigned_to1)"),'im.ireq_status','im.ireq_no','im.ireq_date','im.ireq_user','im.ireq_requestor','dr.div_name','im.creation_date','lr.lookup_desc','im.ireq_status')
            ->ORDERBY('im.ireq_date','DESC')
            ->get(); 

            $ict3 = DB::table('ireq_mst as im')
            ->SELECT('im.ireq_id','im.ireq_verificator_remark','im.ireq_status as status','im.ireq_no','im.ireq_date','im.ireq_user','im.ireq_requestor','dr.div_name','im.ireq_reason','lr.lookup_desc as ireq_status',
                    DB::raw('count(idd.ireq_assigned_to1) as ireq_count_status'), DB::raw('count(idd.ireq_id) as ireq_count_id'))
            ->LEFTJOIN('divisi_refs as dr','im.ireq_divisi_user','dr.div_id')
            ->LEFTJOIN('ireq_dtl as idd','im.ireq_id','idd.ireq_id')
            ->LEFTJOIN('lookup_refs as lr','im.ireq_status','lr.lookup_code')
            ->WHERERaw('LOWER(lr.lookup_type) LIKE ? ',[trim(strtolower('ict_status')).'%'])
            ->whereIn('im.ireq_loc',$loc)
            ->WHERE(function ($query){
                return $query
                ->WHERE('im.ireq_status','RR')
                ->OrWhere('im.ireq_status','RA1')
                ->OrWhere('im.ireq_status','RA2');
            })
            ->groupBy('im.ireq_id','im.ireq_verificator_remark','im.ireq_no','im.ireq_status','im.ireq_date','im.ireq_user','im.ireq_requestor','dr.div_name','im.creation_date','lr.lookup_desc','im.ireq_reason')
            ->ORDERBY('im.ireq_date','DESC')
            ->get(); 

            $ict4 = DB::table('ireq_mst as im')
            ->SELECT('im.ireq_id','im.ireq_verificator_remark','im.ireq_status as status','im.ireq_no','im.ireq_date','im.ireq_user','im.ireq_requestor','dr.div_name',DB::raw("COALESCE(im.ireq_assigned_to2,im.ireq_assigned_to1) AS ireq_assigned_to"),'lr.lookup_desc as ireq_status')
            ->LEFTJOIN('divisi_refs as dr','im.ireq_divisi_user','dr.div_id')
            ->LEFTJOIN('lookup_refs as lr','im.ireq_status','lr.lookup_code')
            ->WHERE('im.ireq_status','T')
            ->WHEREIn('im.ireq_loc',$loc)
            ->WHERERaw('LOWER(lr.lookup_type) LIKE ? ',[trim(strtolower('ict_status')).'%'])
            ->groupBy('im.ireq_id','im.ireq_verificator_remark','im.ireq_status','im.ireq_no','im.ireq_date','im.ireq_user','im.ireq_requestor','dr.div_name','im.creation_date','lr.lookup_desc',DB::raw("COALESCE(im.ireq_assigned_to2,im.ireq_assigned_to1)"))
            ->ORDERBY('im.ireq_date','DESC')
            ->get();

            $ict5 = DB::table('ireq_dtl as id')
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
            ->LEFTJOIN('catalog_refs as cr',function ($join) {
                $join->on('id.invent_code','cr.catalog_id');
            })
            ->LEFTJOIN('catalog_refs as crs',function ($join) {
                $join->on('cr.parent_id','crs.catalog_id');
            })
            ->SELECT('id.ireq_attachment','im.ireq_no','im.ireq_verificator_remark','id.ireq_id','id.ireq_remark',DB::raw("COALESCE(id.ireq_assigned_to2,id.ireq_assigned_to1) AS ireq_assigned_to"),'id.ireqd_id',
            'lr.lookup_desc as ireq_status','lrs.lookup_desc as ireq_type',DB::raw("(crs.catalog_name ||' - '|| cr.catalog_name) as kategori"),'im.ireq_date','im.ireq_requestor','im.ireq_user',
            'dr.div_name','id.ireq_qty','id.ireq_status as status')
            ->WHERE('id.ireq_status','D')
            ->WHEREIn('im.ireq_loc',$loc)
            ->ORDERBY('im.ireq_date','DESC')
            ->ORDERBY('id.ireqd_id','ASC')
            ->get();

            $ict6 = DB::table('ireq_dtl as id')
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
            ->LEFTJOIN('catalog_refs as cr',function ($join) {
                $join->on('id.invent_code','cr.catalog_id');
            })
            ->LEFTJOIN('catalog_refs as crs',function ($join) {
                $join->on('cr.parent_id','crs.catalog_id');
            })
            ->SELECT('id.ireq_attachment','im.ireq_no','im.ireq_verificator_remark','id.ireq_status as status','id.ireq_id','id.ireq_remark',DB::raw("COALESCE(id.ireq_assigned_to2,id.ireq_assigned_to1) AS ireq_assigned_to"),'id.ireqd_id',
            'lr.lookup_desc as ireq_status','lrs.lookup_desc as ireq_type',DB::raw("(crs.catalog_name ||' - '|| cr.catalog_name) as kategori"),'im.ireq_date','im.ireq_requestor','im.ireq_user',
            'dr.div_name','id.ireq_qty')
            ->WHERE('id.ireq_status','C')
            ->WHEREIn('im.ireq_loc',$loc)
            ->ORDERBY('im.ireq_date','DESC')
            ->ORDERBY('id.ireqd_id','ASC')
            ->get();
            
            $ict7 = DB::table('ireq_mst as im')
            ->LEFTJOIN('divisi_refs as dr','im.ireq_divisi_user','dr.div_id')
            ->LEFTJOIN('lookup_refs as lr','im.ireq_status','lr.lookup_code')
            ->SELECT(DB::raw('count(im.ireq_assigned_to1_reason) as count_reason'),'im.ireq_assigned_to1_reason','im.ireq_id',DB::raw('count(im.ireq_verificator_remark) as count_remark'),'im.ireq_verificator_remark','im.ireq_status as status','im.ireq_assigned_to2','im.ireq_no','im.ireq_date','im.ireq_user','im.ireq_requestor','dr.div_name',DB::raw("COALESCE(im.ireq_assigned_to2,im.ireq_assigned_to1) AS ireq_assigned_to"),'lr.lookup_desc as ireq_status')
            ->WHERE(function($query){
                return $query
                ->OrWhere('im.ireq_status','NT')
                ->Orwhere('im.ireq_status','RT');
            })
            ->WHEREIn('im.ireq_loc',$loc)
            ->WHERERaw('LOWER(lr.lookup_type) LIKE ? ',[trim(strtolower('ict_status')).'%'])
            ->groupBy('im.ireq_id','im.ireq_verificator_remark','im.ireq_status','im.ireq_assigned_to1_reason','im.ireq_assigned_to2','im.ireq_no','im.ireq_date','im.ireq_user','im.ireq_requestor','dr.div_name','im.creation_date','lr.lookup_desc',DB::raw("COALESCE(im.ireq_assigned_to2,im.ireq_assigned_to1)"))
            ->ORDERBY('im.ireq_date','DESC')
            ->get();

            $ict8 = DB::table('ireq_mst as im')
            ->LEFTJOIN('divisi_refs as dr','im.ireq_divisi_user','dr.div_id')
            ->LEFTJOIN('lookup_refs as lr','im.ireq_status','lr.lookup_code')
            ->SELECT('im.ireq_id','im.ireq_verificator_remark','im.ireq_status as status','im.ireq_assigned_to2','im.ireq_no','im.ireq_date','im.ireq_user','im.ireq_requestor','dr.div_name',DB::raw("COALESCE(im.ireq_assigned_to2,im.ireq_assigned_to1) AS ireq_assigned_to"),'lr.lookup_desc as ireq_status')
            ->whereNotNull('im.ireq_status')
            ->WHEREIn('im.ireq_loc',$loc)
            ->WHERERaw('LOWER(lr.lookup_type) LIKE ? ',[trim(strtolower('ict_status')).'%'])
            ->groupBy('im.ireq_id','im.ireq_verificator_remark','im.ireq_status','im.ireq_assigned_to2','im.ireq_no','im.ireq_date','im.ireq_user','im.ireq_requestor','dr.div_name','im.creation_date','lr.lookup_desc',DB::raw("COALESCE(im.ireq_assigned_to2,im.ireq_assigned_to1)"))
            ->ORDERBY('im.ireq_date','DESC')
            ->get();

            return json_encode(['lokasi'=>'jakarta','ict'=>$ict,'ict1'=>$ict1,'ict2'=>$ict2,'ict3'=>$ict3,'ict4'=>$ict4,'ict5'=>$ict5,'ict6'=>$ict6,'ict7'=>$ict7,'ict8'=>$ict8],200);
    }
    function detailRequestReviewer($ireq_id)
    {
        $dtl = DB::table('ireq_dtl as id')
            ->SELECT(DB::raw("COALESCE(id.ireq_assigned_to2,id.ireq_assigned_to1) AS ireq_assigned_to"),
            'id.ireq_id','id.ireq_assigned_to1_reason','id.ireq_assigned_to1','im.ireq_no',
            'id.ireq_assigned_to2','id.ireqd_id','lr.lookup_desc as ireq_type','id.ireq_remark',
            'id.ireq_desc', 'id.ireq_qty',DB::raw("(crs.catalog_name ||' - '|| cr.catalog_name) as kategori"),'llr.lookup_desc as ireq_status','id.ireq_status as cekStatus')
            ->LEFTJOIN('ireq_mst as im','id.ireq_id','im.ireq_id')
            ->LEFTJOIN('catalog_refs as cr',function ($join) {
                $join->on('id.invent_code','cr.catalog_id');
            })
            ->LEFTJOIN('catalog_refs as crs',function ($join) {
                $join->on('cr.parent_id','crs.catalog_id');
            })
            ->LEFTJOIN('lookup_refs as lr','id.ireq_type','lr.lookup_code')
            ->LEFTJOIN('lookup_refs as llr',function ($join) {
                $join->on('id.ireq_status','llr.lookup_code')
                    ->WHERERaw('LOWER(llr.lookup_type) LIKE ? ',[trim(strtolower('ict_status')).'%']);
            })
            ->WHERE('id.ireq_id',$ireq_id)
            ->WHERERaw('LOWER(lr.lookup_type) LIKE ? ',[trim(strtolower('req_type')).'%'])
            ->ORDERBY('id.ireqd_id','ASC')
        ->get();
            return response()->json($dtl);
    }
    function rejectReviewer(Request $request, $code)
    {
        $save = Ict::RejectedByReviewer($request,$code);
        IctDetail::RejectedByReviewer($request,$code);
        
        return ResponseFormatter::success($save,'Successfully rejected request');
    }
    function needApprovalAtasan($ireq_id)
    {
        $save = Ict::NeedApprovalByHigherLevel($ireq_id);
        IctDetail::needApprovalByHigherLevel($ireq_id);
        return ResponseFormatter::success($save,'Successfully send mail');
    }
    function needApprovalManager($ireq_id)
    {
        $save = Ict::needApprovalByIctManager($ireq_id);
        IctDetail::needApprovalByIctManager($ireq_id);
        
        return ResponseFormatter::success($save,'Successfully send mail');
    }
    function asignPerRequestReviewer(Request $request)
    {
        $save = Ict::assignPerRequest($request);

        return ResponseFormatter::success($save,'Successfully assign request');
    }
    function submitAssignPerRequest($ireq_id)
    {
        $save = Ict::submitAssignPerRequets($ireq_id); 
        return ResponseFormatter::success($save,'Successfully Submit');

    }
    function getDataIct(){
        $ict=DB::table('ireq_dtl as id')
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
        ->LEFTJOIN('catalog_refs as cr',function ($join) {
            $join->on('id.invent_code','cr.catalog_id');
        })
        ->LEFTJOIN('catalog_refs as crs',function ($join) {
            $join->on('cr.parent_id','crs.catalog_id');
        })
        ->SELECT('im.ireq_no','im.ireq_status as status','id.ireq_remark',DB::raw("COALESCE(id.ireq_assigned_to2,id.ireq_assigned_to1) AS ireq_assigned_to"),'id.ireqd_id',
        'lr.lookup_desc as ireq_status','lrs.lookup_desc as ireq_type',DB::raw("(crs.catalog_name ||' - '|| cr.catalog_name) as kategori"),'im.ireq_date','im.ireq_requestor','im.ireq_user',
        'dr.div_name','id.ireq_qty')
        ->whereNotNull('im.ireq_status')
        ->ORDERBY('im.ireq_date','DESC')
        ->ORDERBY('id.ireqd_id','ASC')
        ->get();
        
        return response()->json($ict);
    }
    function getDataIctByStatus($statuss){
        $ict = DB::table('ireq_mst as im')
        ->LEFTJOIN('lookup_refs as lr',function ($join) {
            $join->on('im.ireq_status','lr.lookup_code')
                  ->WHERERaw('LOWER(lr.lookup_type) LIKE ? ',[trim(strtolower('ict_status')).'%']);
        })
        ->LEFTJOIN('divisi_refs as dr','im.ireq_divisi_user','dr.div_id')
        ->SELECT('im.ireq_no','dr.div_name','im.ireq_date','im.ireq_user','im.ireq_requestor','lr.lookup_desc as ireq_status')
        ->WHERE('im.ireq_status',$statuss)
        ->ORDERBY('im.ireq_date','DESC')
        ->get();
        return response()->json($ict);
    }
    function detailRequestToMail($ireq_id){
        $ict = Ict::find($ireq_id);
        $fromemail = DB::table('location_refs')->select('loc_email')->where('loc_code',$ict->ireq_loc)->first();
        $usr_fullname = Auth::user()->usr_fullname;
        $requestor = DB::table('mng_users')->select('usr_fullname')->where('usr_name',$ict->ireq_requestor)->first();
        return response()->json(['requestor'=>$requestor->usr_fullname,'noreq'=>$ict->ireq_no,'fromemail'=>$fromemail->loc_email,'usr_fullname'=>$usr_fullname],200);
    }
    function updateAssign(Request $request)
    {
        $ict = Ict::where('ireq_id',$request->id)->first();
        $ict->ireq_status = 'T';
        $ict->ireq_assigned_to1 = $request->name;
        $ict->last_updated_by = Auth::user()->usr_name;
        $ict->last_update_date = Carbon::parse(Carbon::now())->copy()->tz('Asia/Jakarta')->format('Y-m-d H:i:s');
        $ict->program_name = "IctController_updateAssign";
        $ict->save();

        $dtl = IctDetail::where('ireq_id',$request->id)->get();
        foreach ($dtl as $d){
            $d->ireq_status = 'T';
            $d->ireq_assigned_to1 = $request->name;
            $d->last_update_date = Carbon::parse(Carbon::now())->copy()->tz('Asia/Jakarta')->format('Y-m-d H:i:s');
            $d->last_updated_by = Auth::user()->usr_name;
            $d->program_name = "IctController_updateAssign";
            $d->save();
        }
        return response()->json('Success Update');
    }
    function updateStatusPenugasan($ireq_id)
    {
        $ict = Ict::where('ireq_id',$ireq_id)->first();
        $ict->ireq_status = 'T';
        $ict->ireq_verificator = Auth::user()->usr_name;
        $ict->last_update_date = Carbon::parse(Carbon::now())->copy()->tz('Asia/Jakarta')->format('Y-m-d H:i:s');    
        $ict->last_updated_by = Auth::user()->usr_name;
        $ict->program_name = "IctController_updateStatusPenugasan";
        $ict->save();
        
        $dtl = DB::table('ireq_dtl')
        ->WHERE('ireq_id',$ireq_id)
        ->update([
            'ireq_status' => 'T',
            'last_update_date' => Carbon::parse(Carbon::now())->copy()->tz('Asia/Jakarta')->format('Y-m-d H:i:s'),
            'last_updated_by' => Auth::user()->usr_name,
            'program_name' => "IctController_updateStatusPenugasan",
        ]);

        return response()->json('Success Update');
    }
    function updateStatusClosing($ireq_id)
    {
        $ict = Ict::where('ireq_id',$ireq_id)->first();
        $ict->ireq_approver2 = Auth::user()->usr_name;
        $ict->ireq_status = 'C';
        $ict->last_update_date = Carbon::parse(Carbon::now())->copy()->tz('Asia/Jakarta')->format('Y-m-d H:i:s');
        $ict->last_updated_by = Auth::user()->usr_name;
        $ict->ireq_date_closing = Carbon::parse(Carbon::now())->copy()->tz('Asia/Jakarta')->format('Y-m-d H:i:s');
        $ict->program_name = "IctController_updateStatusClosing";
        $ict->save();

        $dtl = DB::table('ireq_dtl')
        ->WHERE('ireq_id',$ireq_id)
        ->update([
            'ireq_status' => 'C',
            'last_update_date' => Carbon::parse(Carbon::now())->copy()->tz('Asia/Jakarta')->format('Y-m-d H:i:s'),
            'last_updated_by' => Auth::user()->usr_name,
            'program_name' => "IctController_updateStatusClosing",
        ]);
        
        return response()->json('Success Update');
    }
    function cetak_pdf_reviewer_permohonan()
    {
        if(Auth::user()->usr_loc == 'OJ'){
            $ict =  DB::table('ireq_mst as im')
            ->SELECT('im.ireq_no','im.ireq_requestor','vr.name as ireq_bu','lr.lookup_desc as ireq_type','im.ireq_user','dr.div_name',
                    DB::raw("TO_CHAR(im.ireq_date,' dd Mon YYYY') as ireq_date"),'llr.lookup_desc as ireq_status')
            ->LEFTJOIN('vcompany_refs as vr','im.ireq_bu','vr.company_code')
            ->LEFTJOIN('lookup_refs as lr',function ($join) {
                $join->on('im.ireq_type','lr.lookup_code')
                    ->WHERERaw('LOWER(lr.lookup_type) LIKE ? ',[trim(strtolower('req_type')).'%']);
            })
            ->LEFTJOIN('divisi_refs as dr','im.ireq_divisi_user','dr.div_id')
            ->LEFTJOIN('lookup_refs as llr','im.ireq_status','llr.lookup_code')
            ->WHERE('im.ireq_status','P')
            ->WHERE('im.ireq_loc','OJ')
            ->WHERERaw('LOWER(llr.lookup_type) LIKE ? ',[trim(strtolower('ict_status')).'%'])
            ->ORDERBY('im.ireq_date','DESC')
            ->get();
            return view('pdf/Laporan_IctRequest_Permohonan', compact('ict'));
        }else if(Auth::user()->usr_loc == 'OB' OR Auth::user()->usr_loc == 'FB' ) {
            $ict =  DB::table('ireq_mst as im')
            ->SELECT('im.ireq_no','im.ireq_requestor','vr.name as ireq_bu','lr.lookup_desc as ireq_type','im.ireq_user','dr.div_name',
                    DB::raw("TO_CHAR(im.ireq_date,' dd Mon YYYY') as ireq_date"),'llr.lookup_desc as ireq_status')
            ->LEFTJOIN('vcompany_refs as vr','im.ireq_bu','vr.company_code')
            ->LEFTJOIN('lookup_refs as lr',function ($join) {
                $join->on('im.ireq_type','lr.lookup_code')
                    ->WHERERaw('LOWER(lr.lookup_type) LIKE ? ',[trim(strtolower('req_type')).'%']);
            })
            ->LEFTJOIN('divisi_refs as dr','im.ireq_divisi_user','dr.div_id')
            ->LEFTJOIN('lookup_refs as llr','im.ireq_status','llr.lookup_code')
            ->WHERE('im.ireq_status','P')
            ->WHERE(function ($query){
                return $query
                ->WHERE('im.ireq_loc','OB')
                ->OrWhere('im.ireq_loc','FB');
            })
            ->WHERERaw('LOWER(llr.lookup_type) LIKE ? ',[trim(strtolower('ict_status')).'%'])
            ->ORDERBY('im.ireq_date','DESC')
            ->get();
            return view('pdf/Laporan_IctRequest_Permohonan', compact('ict'));
        }
        else if (Auth::user()->usr_loc == 'OK' OR Auth::user()->usr_loc == 'FK'){
            $ict =  DB::table('ireq_mst as im')
            ->SELECT('im.ireq_no','im.ireq_requestor','vr.name as ireq_bu','lr.lookup_desc as ireq_type','im.ireq_user','dr.div_name',
                    DB::raw("TO_CHAR(im.ireq_date,' dd Mon YYYY') as ireq_date"),'llr.lookup_desc as ireq_status')
            ->LEFTJOIN('vcompany_refs as vr','im.ireq_bu','vr.company_code')
            ->LEFTJOIN('lookup_refs as lr',function ($join) {
                $join->on('im.ireq_type','lr.lookup_code')
                    ->WHERERaw('LOWER(lr.lookup_type) LIKE ? ',[trim(strtolower('req_type')).'%']);
            })
            ->LEFTJOIN('divisi_refs as dr','im.ireq_divisi_user','dr.div_id')
            ->LEFTJOIN('lookup_refs as llr','im.ireq_status','llr.lookup_code')
            ->WHERE('im.ireq_status','P')
            ->WHERE(function ($query){
                return $query
                ->WHERE('im.ireq_loc','OK')
                ->OrWhere('im.ireq_loc','FK');
            })
            ->WHERERaw('LOWER(llr.lookup_type) LIKE ? ',[trim(strtolower('ict_status')).'%'])
            ->ORDERBY('im.ireq_date','DESC')
            ->get();
            return view('pdf/Laporan_IctRequest_Permohonan', compact('ict'));
        }
    }
    function cetak_excel_reviewer_permohonan()
    {
        $newCreation = Carbon::parse(Carbon::now())->copy()->tz('Asia/Jakarta')->format('d M Y');
        return Excel::download(new IctExportReviewerPermohonan,'ICT REQUEST STATUS REPORT LIST ON '.$newCreation.'.xlsx');
    }
    function cetak_pdf_reviewer_atasan_divisi()
    {
        if(Auth::user()->usr_loc == 'OJ'){
            $ict =  DB::table('ireq_mst as im')
            ->SELECT('im.ireq_no','im.ireq_requestor','vr.name as ireq_bu','lr.lookup_desc as ireq_type','im.ireq_user','dr.div_name',
                    DB::raw("TO_CHAR(im.ireq_date,' dd Mon YYYY') as ireq_date"),'llr.lookup_desc as ireq_status')
            ->LEFTJOIN('vcompany_refs as vr','im.ireq_bu','vr.company_code')
            ->LEFTJOIN('lookup_refs as lr',function ($join) {
                $join->on('im.ireq_type','lr.lookup_code')
                    ->WHERERaw('LOWER(lr.lookup_type) LIKE ? ',[trim(strtolower('req_type')).'%']);
            })
            ->LEFTJOIN('divisi_refs as dr','im.ireq_divisi_user','dr.div_id')
            ->LEFTJOIN('lookup_refs as llr','im.ireq_status','llr.lookup_code')
            ->WHERE(function($query){
                return $query
                ->WHERE('im.ireq_status','NA1')
                ->orwhere('im.ireq_status','A1');
            })
            ->WHERE('im.ireq_loc','OJ')
            ->WHERERaw('LOWER(llr.lookup_type) LIKE ? ',[trim(strtolower('ict_status')).'%'])
            ->ORDERBY('im.ireq_date','DESC')
            ->get();
            return view('pdf/Laporan_IctRequest_Permohonan', compact('ict'));
        }else if (Auth::user()->usr_loc == 'OB' OR Auth::user()->usr_loc == 'FB'){
                $ict =  DB::table('ireq_mst as im')
                ->SELECT('im.ireq_no','im.ireq_requestor','vr.name as ireq_bu','lr.lookup_desc as ireq_type','im.ireq_user','dr.div_name',
                        DB::raw("TO_CHAR(im.ireq_date,' dd Mon YYYY') as ireq_date"),'llr.lookup_desc as ireq_status')
                ->LEFTJOIN('vcompany_refs as vr','im.ireq_bu','vr.company_code')
                ->LEFTJOIN('lookup_refs as lr',function ($join) {
                    $join->on('im.ireq_type','lr.lookup_code')
                        ->WHERERaw('LOWER(lr.lookup_type) LIKE ? ',[trim(strtolower('req_type')).'%']);
                })
                ->LEFTJOIN('divisi_refs as dr','im.ireq_divisi_user','dr.div_id')
                ->LEFTJOIN('lookup_refs as llr','im.ireq_status','llr.lookup_code')
                ->WHERE(function($query){
                    return $query
                    ->WHERE('im.ireq_status','NA1')
                    ->orwhere('im.ireq_status','A1');
                })
                ->WHERE(function ($query){
                    return $query
                    ->WHERE('im.ireq_loc','OB')
                    ->OrWhere('im.ireq_loc','FB');
                })
                ->WHERERaw('LOWER(llr.lookup_type) LIKE ? ',[trim(strtolower('ict_status')).'%'])
                ->ORDERBY('im.ireq_date','DESC')
                ->get();
                return view('pdf/Laporan_IctRequest_Permohonan', compact('ict'));
        }else if(Auth::user()->usr_loc == 'OK' OR Auth::user()->usr_loc == 'FK'){
            $ict =  DB::table('ireq_mst as im')
            ->SELECT('im.ireq_no','im.ireq_requestor','vr.name as ireq_bu','lr.lookup_desc as ireq_type','im.ireq_user','dr.div_name',
                    DB::raw("TO_CHAR(im.ireq_date,' dd Mon YYYY') as ireq_date"),'llr.lookup_desc as ireq_status')
            ->LEFTJOIN('vcompany_refs as vr','im.ireq_bu','vr.company_code')
            ->LEFTJOIN('lookup_refs as lr',function ($join) {
                $join->on('im.ireq_type','lr.lookup_code')
                    ->WHERERaw('LOWER(lr.lookup_type) LIKE ? ',[trim(strtolower('req_type')).'%']);
            })
            ->LEFTJOIN('divisi_refs as dr','im.ireq_divisi_user','dr.div_id')
            ->LEFTJOIN('lookup_refs as llr','im.ireq_status','llr.lookup_code')
            ->WHERE(function($query){
                return $query
                ->WHERE('im.ireq_status','NA1')
                ->orwhere('im.ireq_status','A1');
            })
            ->WHERE(function ($query){
                return $query
                ->WHERE('im.ireq_loc','OK')
                ->OrWhere('im.ireq_loc','FK');
            })
            ->WHERERaw('LOWER(llr.lookup_type) LIKE ? ',[trim(strtolower('ict_status')).'%'])
            ->ORDERBY('im.ireq_date','DESC')
            ->get();
            return view('pdf/Laporan_IctRequest_Permohonan', compact('ict'));
            
        }
    }
    function cetak_excel_reviewer_atasan_divisi()
    {
        $newCreation = Carbon::parse(Carbon::now())->copy()->tz('Asia/Jakarta')->format('d M Y');
        return Excel::download(new IctExportReviewerAtasanDivisi,'ICT REQUEST STATUS REPORT LIST ON '.$newCreation.'.xlsx');
    }
    function cetak_pdf_reviewer_ict_manager()
    {
        if(Auth::user()->usr_loc == 'OJ'){
            $ict =  DB::table('ireq_mst as im')
            ->SELECT('im.ireq_no','im.ireq_requestor','vr.name as ireq_bu','lr.lookup_desc as ireq_type','im.ireq_user','dr.div_name',
                    DB::raw("TO_CHAR(im.ireq_date,' dd Mon YYYY') as ireq_date"),'llr.lookup_desc as ireq_status')
            ->LEFTJOIN('vcompany_refs as vr','im.ireq_bu','vr.company_code')
            ->LEFTJOIN('lookup_refs as lr',function ($join) {
                $join->on('im.ireq_type','lr.lookup_code')
                    ->WHERERaw('LOWER(lr.lookup_type) LIKE ? ',[trim(strtolower('req_type')).'%']);
            })
            ->LEFTJOIN('divisi_refs as dr','im.ireq_divisi_user','dr.div_id')
            ->LEFTJOIN('lookup_refs as llr','im.ireq_status','llr.lookup_code')
            ->WHERE(function($query){
                return $query
                ->WHERE('im.ireq_status','NA2')
                ->orwhere('im.ireq_status','A2');
            })
            ->WHERE('im.ireq_loc','OJ')
            ->WHERERaw('LOWER(llr.lookup_type) LIKE ? ',[trim(strtolower('ict_status')).'%'])
            ->ORDERBY('im.ireq_date','DESC')
            ->get();
            return view('pdf/Laporan_IctRequest_Permohonan', compact('ict'));
        }else if (Auth::user()->usr_loc == 'OB' OR Auth::user()->usr_loc == 'FB'){
                $ict =  DB::table('ireq_mst as im')
                ->SELECT('im.ireq_no','im.ireq_requestor','vr.name as ireq_bu','lr.lookup_desc as ireq_type','im.ireq_user','dr.div_name',
                        DB::raw("TO_CHAR(im.ireq_date,' dd Mon YYYY') as ireq_date"),'llr.lookup_desc as ireq_status')
                ->LEFTJOIN('vcompany_refs as vr','im.ireq_bu','vr.company_code')
                ->LEFTJOIN('lookup_refs as lr',function ($join) {
                    $join->on('im.ireq_type','lr.lookup_code')
                        ->WHERERaw('LOWER(lr.lookup_type) LIKE ? ',[trim(strtolower('req_type')).'%']);
                })
                ->LEFTJOIN('divisi_refs as dr','im.ireq_divisi_user','dr.div_id')
                ->LEFTJOIN('lookup_refs as llr','im.ireq_status','llr.lookup_code')
                ->WHERE(function($query){
                    return $query
                    ->WHERE('im.ireq_status','NA2')
                    ->orwhere('im.ireq_status','A2');
                })
                ->WHERE(function ($query){
                    return $query
                    ->WHERE('im.ireq_loc','OB')
                    ->OrWhere('im.ireq_loc','FB');
                })
                ->WHERERaw('LOWER(llr.lookup_type) LIKE ? ',[trim(strtolower('ict_status')).'%'])
                ->ORDERBY('im.ireq_date','DESC')
                ->get();
                return view('pdf/Laporan_IctRequest_Permohonan', compact('ict'));
        }else if(Auth::user()->usr_loc == 'OK' OR Auth::user()->usr_loc == 'FK'){
            $ict =  DB::table('ireq_mst as im')
            ->SELECT('im.ireq_no','im.ireq_requestor','vr.name as ireq_bu','lr.lookup_desc as ireq_type','im.ireq_user','dr.div_name',
                    DB::raw("TO_CHAR(im.ireq_date,' dd Mon YYYY') as ireq_date"),'llr.lookup_desc as ireq_status')
            ->LEFTJOIN('vcompany_refs as vr','im.ireq_bu','vr.company_code')
            ->LEFTJOIN('lookup_refs as lr',function ($join) {
                $join->on('im.ireq_type','lr.lookup_code')
                    ->WHERERaw('LOWER(lr.lookup_type) LIKE ? ',[trim(strtolower('req_type')).'%']);
            })
            ->LEFTJOIN('divisi_refs as dr','im.ireq_divisi_user','dr.div_id')
            ->LEFTJOIN('lookup_refs as llr','im.ireq_status','llr.lookup_code')
            ->WHERE(function($query){
                return $query
                ->WHERE('im.ireq_status','NA2')
                ->orwhere('im.ireq_status','A2');
            })
            ->WHERE(function ($query){
                return $query
                ->WHERE('im.ireq_loc','OK')
                ->OrWhere('im.ireq_loc','FK');
            })
            ->WHERERaw('LOWER(llr.lookup_type) LIKE ? ',[trim(strtolower('ict_status')).'%'])
            ->ORDERBY('im.ireq_date','DESC')
            ->get();
            return view('pdf/Laporan_IctRequest_Permohonan', compact('ict'));
        }
    }
    function cetak_excel_reviewer_ict_manager()
    {
        $newCreation = Carbon::parse(Carbon::now())->copy()->tz('Asia/Jakarta')->format('d M Y');
        return Excel::download(new IctExportReviewerIctManager,'ICT REQUEST STATUS REPORT LIST ON '.$newCreation.'.xlsx');
    }
    function cetak_pdf_reviewer_reject()
    {
        if(Auth::user()->usr_loc == 'OJ'){
            $ict =  DB::table('ireq_mst as im')
            ->SELECT('im.ireq_no','im.ireq_reason','im.ireq_requestor','vr.name as ireq_bu','lr.lookup_desc as ireq_type','im.ireq_user','dr.div_name',
                    DB::raw("TO_CHAR(im.ireq_date,' dd Mon YYYY') as ireq_date"),'llr.lookup_desc as ireq_status')
            ->LEFTJOIN('vcompany_refs as vr','im.ireq_bu','vr.company_code')
            ->LEFTJOIN('lookup_refs as lr',function ($join) {
                $join->on('im.ireq_type','lr.lookup_code')
                    ->WHERERaw('LOWER(lr.lookup_type) LIKE ? ',[trim(strtolower('req_type')).'%']);
            })
            ->LEFTJOIN('divisi_refs as dr','im.ireq_divisi_user','dr.div_id')
            ->LEFTJOIN('lookup_refs as llr','im.ireq_status','llr.lookup_code')
            ->WHERE(function($query){
                return $query
                ->WHERE('im.ireq_status','RA2')
                ->orwhere('im.ireq_status','RA1')
                ->orwhere('im.ireq_status','RR');
            })
            ->WHERE('im.ireq_loc','OJ')
            ->WHERERaw('LOWER(llr.lookup_type) LIKE ? ',[trim(strtolower('ict_status')).'%'])
            ->ORDERBY('im.ireq_date','DESC')
            ->get();
            return view('pdf/Laporan_IctRequest_Reject', compact('ict'));
        }else if (Auth::user()->usr_loc == 'OB' OR Auth::user()->usr_loc == 'FB'){
                $ict =  DB::table('ireq_mst as im')
                ->SELECT('im.ireq_no','im.ireq_reason','im.ireq_requestor','vr.name as ireq_bu','lr.lookup_desc as ireq_type','im.ireq_user','dr.div_name',
                        DB::raw("TO_CHAR(im.ireq_date,' dd Mon YYYY') as ireq_date"),'llr.lookup_desc as ireq_status')
                ->LEFTJOIN('vcompany_refs as vr','im.ireq_bu','vr.company_code')
                ->LEFTJOIN('lookup_refs as lr',function ($join) {
                    $join->on('im.ireq_type','lr.lookup_code')
                        ->WHERERaw('LOWER(lr.lookup_type) LIKE ? ',[trim(strtolower('req_type')).'%']);
                })
                ->LEFTJOIN('divisi_refs as dr','im.ireq_divisi_user','dr.div_id')
                ->LEFTJOIN('lookup_refs as llr','im.ireq_status','llr.lookup_code')
                ->WHERE(function($query){
                    return $query
                    ->WHERE('im.ireq_status','RA2')
                    ->orwhere('im.ireq_status','RA1')
                    ->orwhere('im.ireq_status','RR');
                })
                ->WHERE(function ($query){
                    return $query
                    ->WHERE('im.ireq_loc','OB')
                    ->OrWhere('im.ireq_loc','FB');
                })
                ->WHERERaw('LOWER(llr.lookup_type) LIKE ? ',[trim(strtolower('ict_status')).'%'])
                ->ORDERBY('im.ireq_date','DESC')
                ->get();
                return view('pdf/Laporan_IctRequest_Reject', compact('ict'));
        }else if(Auth::user()->usr_loc == 'OK' OR Auth::user()->usr_loc == 'FK'){
            $ict =  DB::table('ireq_mst as im')
            ->SELECT('im.ireq_no','im.ireq_reason','im.ireq_requestor','vr.name as ireq_bu','lr.lookup_desc as ireq_type','im.ireq_user','dr.div_name',
                    DB::raw("TO_CHAR(im.ireq_date,' dd Mon YYYY') as ireq_date"),'llr.lookup_desc as ireq_status')
            ->LEFTJOIN('vcompany_refs as vr','im.ireq_bu','vr.company_code')
            ->LEFTJOIN('lookup_refs as lr',function ($join) {
                $join->on('im.ireq_type','lr.lookup_code')
                    ->WHERERaw('LOWER(lr.lookup_type) LIKE ? ',[trim(strtolower('req_type')).'%']);
            })
            ->LEFTJOIN('divisi_refs as dr','im.ireq_divisi_user','dr.div_id')
            ->LEFTJOIN('lookup_refs as llr','im.ireq_status','llr.lookup_code')
            ->WHERE(function($query){
                return $query
                ->WHERE('im.ireq_status','RA2')
                ->orwhere('im.ireq_status','RA1')
                ->orwhere('im.ireq_status','RR');
            })
            ->WHERE(function ($query){
                return $query
                ->WHERE('im.ireq_loc','OK')
                ->OrWhere('im.ireq_loc','FK');
            })
            ->WHERERaw('LOWER(llr.lookup_type) LIKE ? ',[trim(strtolower('ict_status')).'%'])
            ->ORDERBY('im.ireq_date','DESC')
            ->get();
            return view('pdf/Laporan_IctRequest_Reject', compact('ict'));
        }
    }
    function cetak_excel_reviewer_reject()
    {
        $newCreation = Carbon::parse(Carbon::now())->copy()->tz('Asia/Jakarta')->format('d M Y');
        return Excel::download(new IctExportRejectReviewer,'ICT REQUEST STATUS REPORT LIST ON '.$newCreation.'.xlsx');
    }
    function cetak_pdf_reviewer_assignment_request()
    {
        if(Auth::user()->usr_loc == 'OJ'){
            $ict =  DB::table('ireq_mst as im')
            ->SELECT('im.ireq_no','im.ireq_requestor','vr.name as ireq_bu','lr.lookup_desc as ireq_type','im.ireq_user','dr.div_name',
                    DB::raw("TO_CHAR(im.ireq_date,' dd Mon YYYY') as ireq_date"),'llr.lookup_desc as ireq_status',DB::raw("COALESCE(im.ireq_assigned_to2,im.ireq_assigned_to1) AS ireq_assigned_to"))
            ->LEFTJOIN('vcompany_refs as vr','im.ireq_bu','vr.company_code')
            ->LEFTJOIN('lookup_refs as lr',function ($join) {
                $join->on('im.ireq_type','lr.lookup_code')
                    ->WHERERaw('LOWER(lr.lookup_type) LIKE ? ',[trim(strtolower('req_type')).'%']);
            })
            ->LEFTJOIN('divisi_refs as dr','im.ireq_divisi_user','dr.div_id')
            ->LEFTJOIN('lookup_refs as llr','im.ireq_status','llr.lookup_code')
            ->WHERE(function($query){
                return $query
                ->WHERE('im.ireq_status','NT')
                ->orwhere('im.ireq_status','RT');
            })
            ->WHERE('im.ireq_loc','OJ')
            ->WHERERaw('LOWER(llr.lookup_type) LIKE ? ',[trim(strtolower('ict_status')).'%'])
            ->ORDERBY('im.ireq_date','DESC')
            ->get();
            return view('pdf/Laporan_IctRequest_Sedang_Dikerjakan', compact('ict'));
        }else if (Auth::user()->usr_loc == 'OB' OR Auth::user()->usr_loc == 'FB'){
                $ict =  DB::table('ireq_mst as im')
                ->SELECT('im.ireq_no','im.ireq_requestor','vr.name as ireq_bu','lr.lookup_desc as ireq_type','im.ireq_user','dr.div_name',
                        DB::raw("TO_CHAR(im.ireq_date,' dd Mon YYYY') as ireq_date"),'llr.lookup_desc as ireq_status',DB::raw("COALESCE(im.ireq_assigned_to2,im.ireq_assigned_to1) AS ireq_assigned_to"))
                ->LEFTJOIN('vcompany_refs as vr','im.ireq_bu','vr.company_code')
                ->LEFTJOIN('lookup_refs as lr',function ($join) {
                    $join->on('im.ireq_type','lr.lookup_code')
                        ->WHERERaw('LOWER(lr.lookup_type) LIKE ? ',[trim(strtolower('req_type')).'%']);
                })
                ->LEFTJOIN('divisi_refs as dr','im.ireq_divisi_user','dr.div_id')
                ->LEFTJOIN('lookup_refs as llr','im.ireq_status','llr.lookup_code')
                ->WHERE(function($query){
                    return $query
                    ->WHERE('im.ireq_status','RT')
                    ->orwhere('im.ireq_status','NT');
                })
                ->WHERE(function ($query){
                    return $query
                    ->WHERE('im.ireq_loc','OB')
                    ->OrWhere('im.ireq_loc','FB');
                })
                ->WHERERaw('LOWER(llr.lookup_type) LIKE ? ',[trim(strtolower('ict_status')).'%'])
                ->ORDERBY('im.ireq_date','DESC')
                ->get();
                return view('pdf/Laporan_IctRequest_Sedang_Dikerjakan', compact('ict'));
        }else if(Auth::user()->usr_loc == 'OK' OR Auth::user()->usr_loc == 'FK'){
            $ict =  DB::table('ireq_mst as im')
            ->SELECT('im.ireq_no','im.ireq_requestor','vr.name as ireq_bu','lr.lookup_desc as ireq_type','im.ireq_user','dr.div_name',
                    DB::raw("TO_CHAR(im.ireq_date,' dd Mon YYYY') as ireq_date"),'llr.lookup_desc as ireq_status',DB::raw("COALESCE(im.ireq_assigned_to2,im.ireq_assigned_to1) AS ireq_assigned_to"))
            ->LEFTJOIN('vcompany_refs as vr','im.ireq_bu','vr.company_code')
            ->LEFTJOIN('lookup_refs as lr',function ($join) {
                $join->on('im.ireq_type','lr.lookup_code')
                    ->WHERERaw('LOWER(lr.lookup_type) LIKE ? ',[trim(strtolower('req_type')).'%']);
            })
            ->LEFTJOIN('divisi_refs as dr','im.ireq_divisi_user','dr.div_id')
            ->LEFTJOIN('lookup_refs as llr','im.ireq_status','llr.lookup_code')
            ->WHERE(function($query){
                return $query
                ->WHERE('im.ireq_status','RT')
                ->orwhere('im.ireq_status','NT');
            })
            ->WHERE(function ($query){
                return $query
                ->WHERE('im.ireq_loc','OK')
                ->OrWhere('im.ireq_loc','FK');
            })
            ->WHERERaw('LOWER(llr.lookup_type) LIKE ? ',[trim(strtolower('ict_status')).'%'])
            ->ORDERBY('im.ireq_date','DESC')
            ->get();
            return view('pdf/Laporan_IctRequest_Sedang_Dikerjakan', compact('ict'));
        }
    }
    function cetak_excel_reviewer_assignment_request()
    {
        $newCreation = Carbon::parse(Carbon::now())->copy()->tz('Asia/Jakarta')->format('d M Y');
        return Excel::download(new IctExportReviewerAssignmentRequest,'ICT REQUEST STATUS REPORT LIST ON '.$newCreation.'.xlsx');
    }
    function cetak_pdf_reviewer_sedang_dikerjakan()
    {
        if(Auth::user()->usr_loc == 'OJ'){
            $ict =  DB::table('ireq_mst as im')
            ->SELECT('im.ireq_no','im.ireq_requestor','vr.name as ireq_bu','lr.lookup_desc as ireq_type','im.ireq_user','dr.div_name',
                    DB::raw("TO_CHAR(im.ireq_date,' dd Mon YYYY') as ireq_date"),'llr.lookup_desc as ireq_status',DB::raw("COALESCE(im.ireq_assigned_to2,im.ireq_assigned_to1) AS ireq_assigned_to"))
            ->LEFTJOIN('vcompany_refs as vr','im.ireq_bu','vr.company_code')
            ->LEFTJOIN('lookup_refs as lr',function ($join) {
                $join->on('im.ireq_type','lr.lookup_code')
                    ->WHERERaw('LOWER(lr.lookup_type) LIKE ? ',[trim(strtolower('req_type')).'%']);
            })
            ->LEFTJOIN('divisi_refs as dr','im.ireq_divisi_user','dr.div_id')
            ->LEFTJOIN('lookup_refs as llr','im.ireq_status','llr.lookup_code')
            ->WHERE('im.ireq_status','T')
            ->WHERE('im.ireq_loc','OJ')
            ->WHERERaw('LOWER(llr.lookup_type) LIKE ? ',[trim(strtolower('ict_status')).'%'])
            ->ORDERBY('im.ireq_date','DESC')
            ->get();
            return view('pdf/Laporan_IctRequest_Sedang_Dikerjakan', compact('ict'));
        }else if (Auth::user()->usr_loc == 'OB' OR Auth::user()->usr_loc == 'FB'){
                $ict =  DB::table('ireq_mst as im')
                ->SELECT('im.ireq_no','im.ireq_requestor','vr.name as ireq_bu','lr.lookup_desc as ireq_type','im.ireq_user','dr.div_name',
                        DB::raw("TO_CHAR(im.ireq_date,' dd Mon YYYY') as ireq_date"),'llr.lookup_desc as ireq_status',DB::raw("COALESCE(im.ireq_assigned_to2,im.ireq_assigned_to1) AS ireq_assigned_to"))
                ->LEFTJOIN('vcompany_refs as vr','im.ireq_bu','vr.company_code')
                ->LEFTJOIN('lookup_refs as lr',function ($join) {
                    $join->on('im.ireq_type','lr.lookup_code')
                        ->WHERERaw('LOWER(lr.lookup_type) LIKE ? ',[trim(strtolower('req_type')).'%']);
                })
                ->LEFTJOIN('divisi_refs as dr','im.ireq_divisi_user','dr.div_id')
                ->LEFTJOIN('lookup_refs as llr','im.ireq_status','llr.lookup_code')
                ->WHERE('im.ireq_status','T')
                ->WHERE(function ($query){
                    return $query
                    ->WHERE('im.ireq_loc','OB')
                    ->OrWhere('im.ireq_loc','FB');
                })
                ->WHERERaw('LOWER(llr.lookup_type) LIKE ? ',[trim(strtolower('ict_status')).'%'])
                ->ORDERBY('im.ireq_date','DESC')
                ->get();
                return view('pdf/Laporan_IctRequest_Sedang_Dikerjakan', compact('ict'));
        }else if(Auth::user()->usr_loc == 'OK' OR Auth::user()->usr_loc == 'FK'){
            $ict =  DB::table('ireq_mst as im')
            ->SELECT('im.ireq_no','im.ireq_requestor','vr.name as ireq_bu','lr.lookup_desc as ireq_type','im.ireq_user','dr.div_name',
                    DB::raw("TO_CHAR(im.ireq_date,' dd Mon YYYY') as ireq_date"),'llr.lookup_desc as ireq_status',DB::raw("COALESCE(im.ireq_assigned_to2,im.ireq_assigned_to1) AS ireq_assigned_to"))
            ->LEFTJOIN('vcompany_refs as vr','im.ireq_bu','vr.company_code')
            ->LEFTJOIN('lookup_refs as lr',function ($join) {
                $join->on('im.ireq_type','lr.lookup_code')
                    ->WHERERaw('LOWER(lr.lookup_type) LIKE ? ',[trim(strtolower('req_type')).'%']);
            })
            ->LEFTJOIN('divisi_refs as dr','im.ireq_divisi_user','dr.div_id')
            ->LEFTJOIN('lookup_refs as llr','im.ireq_status','llr.lookup_code')
            ->WHERE('im.ireq_status','T')
            ->WHERE(function ($query){
                return $query
                ->WHERE('im.ireq_loc','OK')
                ->OrWhere('im.ireq_loc','FK');
            })
            ->WHERERaw('LOWER(llr.lookup_type) LIKE ? ',[trim(strtolower('ict_status')).'%'])
            ->ORDERBY('im.ireq_date','DESC')
            ->get();
            return view('pdf/Laporan_IctRequest_Sedang_Dikerjakan', compact('ict'));
        }
    }
    function cetak_excel_reviewer_sedang_dikerjakan()
    {
        $newCreation = Carbon::parse(Carbon::now())->copy()->tz('Asia/Jakarta')->format('d M Y');
        return Excel::download(new IctExportReviewerSedangDikerjakan,'ICT REQUEST STATUS REPORT LIST ON '.$newCreation.'.xlsx');
    }
    function cetak_pdf_reviewer_sudah_dikerjakan()
    {
        if(Auth::user()->usr_loc == 'OJ'){
            $ict =  DB::table('ireq_dtl as id')
            ->SELECT('imm.ireq_no','id.ireq_desc','id.ireq_qty','id.ireq_remark','id.ireqd_id','dr.div_name',
                'imm.ireq_user','llr.lookup_desc as ireq_status', 'imm.ireq_requestor',
                'vr.name as ireq_bu','lr.lookup_desc as ireq_type',DB::raw("TO_CHAR(imm.ireq_date,' dd Mon YYYY') as ireq_date"),
                DB::raw("(crs.catalog_name ||' - '|| cr.catalog_name) as kategori"),DB::raw("COALESCE(id.ireq_assigned_to2,id.ireq_assigned_to1) AS ireq_assigned_to"))
            ->LEFTJOIN('lookup_refs as lr','id.ireq_type','lr.lookup_code')
            ->LEFTJOIN('catalog_refs as cr',function ($join) {
                $join->on('id.invent_code','cr.catalog_id');
            })
            ->LEFTJOIN('catalog_refs as crs',function ($join) {
                $join->on('cr.parent_id','crs.catalog_id');
            })
            ->join('ireq_mst as imm','id.ireq_id','imm.ireq_id')        
            ->LEFTJOIN('lookup_refs as llr','imm.ireq_status','llr.lookup_code')
            ->LEFTJOIN('vcompany_refs as vr','imm.ireq_bu','vr.company_code')
            ->LEFTJOIN('divisi_refs as dr','imm.ireq_divisi_user','dr.div_id')
            ->WHERE('id.ireq_status','D')
            ->WHERE('imm.ireq_loc','OJ')
            ->WHERERaw('LOWER(lr.lookup_type) LIKE ? ',[trim(strtolower('req_type')).'%'])
            ->WHERERaw('LOWER(llr.lookup_type) LIKE ? ',[trim(strtolower('ict_status')).'%'])
            ->ORDERBY('imm.ireq_date','DESC')
            ->ORDERBY('id.ireqd_id','ASC')
            ->get();
            return view('pdf/Laporan_IctRequest_Sudah_Dikerjakan', compact('ict'));
        }else if (Auth::user()->usr_loc == 'OB' OR Auth::user()->usr_loc == 'FB'){
            $ict =  DB::table('ireq_dtl as id')
            ->SELECT('imm.ireq_no','id.ireq_desc','id.ireq_qty','id.ireq_remark','id.ireqd_id','dr.div_name',
                'imm.ireq_user', 'llr.lookup_desc as ireq_status', 'imm.ireq_requestor',
                'vr.name as ireq_bu','lr.lookup_desc as ireq_type',DB::raw("TO_CHAR(imm.ireq_date,' dd Mon YYYY') as ireq_date"),
                DB::raw("(crs.catalog_name ||' - '|| cr.catalog_name) as kategori"),DB::raw("COALESCE(id.ireq_assigned_to2,id.ireq_assigned_to1) AS ireq_assigned_to"))
            ->LEFTJOIN('catalog_refs as cr',function ($join) {
                $join->on('id.invent_code','cr.catalog_id');
            })
            ->LEFTJOIN('catalog_refs as crs',function ($join) {
                $join->on('cr.parent_id','crs.catalog_id');
            })
            ->LEFTJOIN('lookup_refs as lr','id.ireq_type','lr.lookup_code')
            ->LEFTJOIN('ireq_mst as imm','id.ireq_id','imm.ireq_id')        
            ->LEFTJOIN('lookup_refs as llr','imm.ireq_status','llr.lookup_code')
            ->LEFTJOIN('vcompany_refs as vr','imm.ireq_bu','vr.company_code')
            ->LEFTJOIN('divisi_refs as dr','imm.ireq_divisi_user','dr.div_id')
            ->WHERE('id.ireq_status','D')
            ->WHERE(function ($query){
                return $query
                ->WHERE('imm.ireq_loc','OB')
                ->OrWhere('imm.ireq_loc','FB');
            })
            ->WHERERaw('LOWER(lr.lookup_type) LIKE ? ',[trim(strtolower('req_type')).'%'])
            ->WHERERaw('LOWER(llr.lookup_type) LIKE ? ',[trim(strtolower('ict_status')).'%'])
            ->ORDERBY('imm.ireq_date','DESC')
            ->ORDERBY('id.ireqd_id','ASC')
            ->get();
            return view('pdf/Laporan_IctRequest_Sudah_Dikerjakan', compact('ict'));
        }else if(Auth::user()->usr_loc == 'OK' OR Auth::user()->usr_loc == 'FK'){
            $ict =  DB::table('ireq_dtl as id')
            ->SELECT('imm.ireq_no','id.ireq_desc','id.ireq_qty','id.ireq_remark','id.ireqd_id','dr.div_name',
                'imm.ireq_user','llr.lookup_desc as ireq_status', 'imm.ireq_requestor',
                'vr.name as ireq_bu','lr.lookup_desc as ireq_type',DB::raw("TO_CHAR(imm.ireq_date,' dd Mon YYYY') as ireq_date"),
                DB::raw("(crs.catalog_name ||' - '|| cr.catalog_name) as kategori"),DB::raw("COALESCE(id.ireq_assigned_to2,id.ireq_assigned_to1) AS ireq_assigned_to"))
            ->LEFTJOIN('lookup_refs as lr','id.ireq_type','lr.lookup_code')
            ->LEFTJOIN('catalog_refs as cr',function ($join) {
                $join->on('id.invent_code','cr.catalog_id');
            })
            ->LEFTJOIN('catalog_refs as crs',function ($join) {
                $join->on('cr.parent_id','crs.catalog_id');
            })
            ->JOIN('ireq_mst as imm','id.ireq_id','imm.ireq_id')        
            ->LEFTJOIN('lookup_refs as llr','imm.ireq_status','llr.lookup_code')
            ->LEFTJOIN('vcompany_refs as vr','imm.ireq_bu','vr.company_code')
            ->LEFTJOIN('divisi_refs as dr','imm.ireq_divisi_user','dr.div_id')
            ->WHERE('id.ireq_status','D')
            ->WHERE(function ($query){
                return $query
                ->WHERE('imm.ireq_loc','OK')
                ->OrWhere('imm.ireq_loc','FK');
            })
            ->WHERERaw('LOWER(lr.lookup_type) LIKE ? ',[trim(strtolower('req_type')).'%'])
            ->WHERERaw('LOWER(llr.lookup_type) LIKE ? ',[trim(strtolower('ict_status')).'%'])
            ->ORDERBY('imm.ireq_date','DESC')
            ->ORDERBY('id.ireqd_id','ASC')
            ->get();
            return view('pdf/Laporan_IctRequest_Sudah_Dikerjakan', compact('ict'));
        }
    }
    function cetak_excel_reviewer_sudah_dikerjakan()
    {
        $newCreation = Carbon::parse(Carbon::now())->copy()->tz('Asia/Jakarta')->format('d M Y');
        return Excel::download(new IctExportReviewerSudahDikerjakan,'ICT REQUEST STATUS REPORT LIST ON '.$newCreation.'.xlsx');
    }
    function cetak_pdf_reviewer_selesai()
    {
        if(Auth::user()->usr_loc == 'OJ'){
            $ict =  DB::table('ireq_dtl as id')
            ->SELECT('imm.ireq_no','id.ireq_desc','id.ireq_qty','id.ireq_remark','id.ireqd_id','dr.div_name',
                'imm.ireq_user','llr.lookup_desc as ireq_status', 'imm.ireq_requestor',
                'vr.name as ireq_bu','lr.lookup_desc as ireq_type',DB::raw("TO_CHAR(imm.ireq_date,' dd Mon YYYY') as ireq_date"),
                DB::raw("(crs.catalog_name ||' - '|| cr.catalog_name) as kategori"),DB::raw("COALESCE(id.ireq_assigned_to2,id.ireq_assigned_to1) AS ireq_assigned_to"))
            ->LEFTJOIN('lookup_refs as lr','id.ireq_type','lr.lookup_code')
            ->LEFTJOIN('catalog_refs as cr',function ($join) {
                $join->on('id.invent_code','cr.catalog_id');
            })
            ->LEFTJOIN('catalog_refs as crs',function ($join) {
                $join->on('cr.parent_id','crs.catalog_id');
            })
            ->join('ireq_mst as imm','id.ireq_id','imm.ireq_id')        
            ->LEFTJOIN('lookup_refs as llr','imm.ireq_status','llr.lookup_code')
            ->LEFTJOIN('vcompany_refs as vr','imm.ireq_bu','vr.company_code')
            ->LEFTJOIN('divisi_refs as dr','imm.ireq_divisi_user','dr.div_id')
            ->WHERE('id.ireq_status','C')
            ->WHERE('imm.ireq_loc','OJ')
            ->WHERERaw('LOWER(lr.lookup_type) LIKE ? ',[trim(strtolower('req_type')).'%'])
            ->WHERERaw('LOWER(llr.lookup_type) LIKE ? ',[trim(strtolower('ict_status')).'%'])
            ->ORDERBY('imm.ireq_date','DESC')
            ->ORDERBY('id.ireqd_id','ASC')
            ->get();
            return view('pdf/Laporan_IctRequest_Selesai', compact('ict'));
        }else if (Auth::user()->usr_loc == 'OB' OR Auth::user()->usr_loc == 'FB'){
            $ict =  DB::table('ireq_dtl as id')
            ->SELECT('imm.ireq_no','id.ireq_desc','id.ireq_qty','id.ireq_remark','id.ireqd_id','dr.div_name',
                'imm.ireq_user', 'llr.lookup_desc as ireq_status', 'imm.ireq_requestor',
                'vr.name as ireq_bu','lr.lookup_desc as ireq_type',DB::raw("TO_CHAR(imm.ireq_date,' dd Mon YYYY') as ireq_date"),
                DB::raw("(crs.catalog_name ||' - '|| cr.catalog_name) as kategori"),DB::raw("COALESCE(id.ireq_assigned_to2,id.ireq_assigned_to1) AS ireq_assigned_to"))
            ->LEFTJOIN('lookup_refs as lr','id.ireq_type','lr.lookup_code')
            ->LEFTJOIN('catalog_refs as cr',function ($join) {
                $join->on('id.invent_code','cr.catalog_id');
            })
            ->LEFTJOIN('catalog_refs as crs',function ($join) {
                $join->on('cr.parent_id','crs.catalog_id');
            })
            ->LEFTJOIN('ireq_mst as imm','id.ireq_id','imm.ireq_id')        
            ->LEFTJOIN('lookup_refs as llr','imm.ireq_status','llr.lookup_code')
            ->LEFTJOIN('vcompany_refs as vr','imm.ireq_bu','vr.company_code')
            ->LEFTJOIN('divisi_refs as dr','imm.ireq_divisi_user','dr.div_id')
            ->WHERE('id.ireq_status','C')
            ->WHERE(function ($query){
                return $query
                ->WHERE('imm.ireq_loc','OB')
                ->OrWhere('imm.ireq_loc','FB');
            })
            ->WHERERaw('LOWER(lr.lookup_type) LIKE ? ',[trim(strtolower('req_type')).'%'])
            ->WHERERaw('LOWER(llr.lookup_type) LIKE ? ',[trim(strtolower('ict_status')).'%'])
            ->ORDERBY('imm.ireq_date','DESC')
            ->ORDERBY('id.ireqd_id','ASC')
            ->get();
            return view('pdf/Laporan_IctRequest_Selesai', compact('ict'));
        }else if(Auth::user()->usr_loc == 'OK' OR Auth::user()->usr_loc == 'FK'){
            $ict =  DB::table('ireq_dtl as id')
            ->SELECT('imm.ireq_no','id.ireq_desc','id.ireq_qty','id.ireq_remark','id.ireqd_id','dr.div_name',
                'imm.ireq_user','llr.lookup_desc as ireq_status', 'imm.ireq_requestor',
                'vr.name as ireq_bu','lr.lookup_desc as ireq_type',DB::raw("TO_CHAR(imm.ireq_date,' dd Mon YYYY') as ireq_date"),
                DB::raw("(crs.catalog_name ||' - '|| cr.catalog_name) as kategori"),DB::raw("COALESCE(id.ireq_assigned_to2,id.ireq_assigned_to1) AS ireq_assigned_to"))
            ->LEFTJOIN('lookup_refs as lr','id.ireq_type','lr.lookup_code')
            ->LEFTJOIN('catalog_refs as cr',function ($join) {
                $join->on('id.invent_code','cr.catalog_id');
            })
            ->LEFTJOIN('catalog_refs as crs',function ($join) {
                $join->on('cr.parent_id','crs.catalog_id');
            })
            ->join('ireq_mst as imm','id.ireq_id','imm.ireq_id')        
            ->LEFTJOIN('lookup_refs as llr','imm.ireq_status','llr.lookup_code')
            ->LEFTJOIN('vcompany_refs as vr','imm.ireq_bu','vr.company_code')
            ->LEFTJOIN('divisi_refs as dr','imm.ireq_divisi_user','dr.div_id')
            ->WHERE('id.ireq_status','C')
            ->WHERE(function ($query){
                return $query
                ->WHERE('imm.ireq_loc','OK')
                ->OrWhere('imm.ireq_loc','FK');
            })
            ->WHERERaw('LOWER(lr.lookup_type) LIKE ? ',[trim(strtolower('req_type')).'%'])
            ->WHERERaw('LOWER(llr.lookup_type) LIKE ? ',[trim(strtolower('ict_status')).'%'])
            ->ORDERBY('imm.ireq_date','DESC')
            ->ORDERBY('id.ireqd_id','ASC')
            ->get();
            return view('pdf/Laporan_IctRequest_Selesai', compact('ict'));
        }
    }
    function cetak_excel_reviewer_selesai()
    {
        $newCreation = Carbon::parse(Carbon::now())->copy()->tz('Asia/Jakarta')->format('d M Y');
        return Excel::download(new IctExportReviewerSelesai,'ICT REQUEST STATUS REPORT LIST ON '.$newCreation.'.xlsx');
    }
}
