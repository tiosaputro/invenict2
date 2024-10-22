<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\Ict;
use App\Models\IctDetail;
use App\Helpers\ResponseFormatter;
use App\Models\Mng_user;
use Maatwebsite\Excel\Facades\Excel;
use App\Jobs\SendNotifPersonnel;
use App\Services\IctRequestReviewerServices;
use App\Services\IctServices;
use App\Services\IctDetailServices;
use App\Exports\IctExportReviewerPermohonan;
use App\Exports\IctExportReviewerAtasanDivisi;
use App\Exports\IctExportReviewerIctManager;
use App\Exports\IctExportRejectReviewer;
use App\Exports\IctExportReviewerAssignmentRequest;
use App\Exports\IctExportReviewerSedangDikerjakan;
use App\Exports\IctExportReviewerSudahDikerjakan;
use App\Exports\IctExportReviewerSelesai;
use App\Services\PekerjaServices;
use App\Models\Lookup_Refs;
use App\Services\UserDomainServices;
use Illuminate\Support\Facades\View;
use App\Helpers\ldap_connection;

class IctRequestReviewerController extends Controller
{
    protected $to;
    protected $userMenu;
    protected $reviewerServices;
    protected $IctServices;
    protected $IctDetailServices;
    protected $Pekerjaservices;
    public function __construct(IctRequestReviewerServices $services, IctServices $service, IctDetailServices $servicess, PekerjaServices $pekerja ){
        $this->reviewerServices = $services;
        $this->IctServices = $service;
        $this->IctDetailServices = $servicess;
        $this->Pekerjaservices = $pekerja;
        $this->middleware(['auth:sanctum', function ($request, $next) {
            $this->to = "/ict-request-reviewer";
            $this->userMenu = Mng_User::menu();
            if ($this->userMenu->contains($this->to)) {
                return $next($request);
            } else {
                return response(["message" => "Cannot Access"], 403);
            }
        }]);
    }
    function index($code){
        $data['detail'] = $this->IctDetailServices->getDataDetailRequest($code,NULL,NULL,NULL);
        $data['request'] = $this->IctServices->detailNoRequest($code);
        $data['pekerja'] = $this->Pekerjaservices->getPekerja();
        $data['request_type'] = Lookup_Refs::Type();
        return ResponseFormatter::success($data,'Successfully Get Data Detail Request'); 
    }
    function editSpv($code){
        $data['request'] = $this->IctServices->detailNoRequest($code);
        $data['listSupervisor'] = app(UserDomainServices::class)->getAllData();
        return ResponseFormatter::success($data,'Successfully Get Data'); 
    }
    function saveSpv(Request $request){
        $CheckisUser = Mng_user::where('usr_domain', $request->ireq_spv)->first();

        if (!$CheckisUser) {
            $ldap = new ldap_connection();
            $check = $ldap->findUser($request->ireq_spv);

            if (!$check) {
                return ResponseFormatter::error(null, 'User not found');
            }

            $CheckisUser = Mng_user::createUser($check);
        }

        if (!$request->ireq_spv_acting) {
            $this->reviewerServices->CheckIsHigherLevel($CheckisUser->usr_id);
        }

        $request->ireq_spv = $CheckisUser->usr_id;
        $data = $this->reviewerServices->SaveSpv($request);

        return ResponseFormatter::success($data, 'Successfully Get Data');
    }
    function sendMailtoRequestor(Request $request){
        $this->reviewerServices->sendMailToRequestor($request);
        return ResponseFormatter::success('Successfully Sent Email to Requestor');
    }
    function editDataDetail($ireq,$code){
        $data['request'] = $this->IctDetailServices->FindDetailRequest($ireq,$code);
        return ResponseFormatter::success($data,'Successfully get data');
    }
    function detailPenugasan($code){
        $data['detail'] = $this->IctDetailServices->getDataDetailRequest($code, 'NT','RT','T');
        $data['request'] = $this->IctServices->detailNoRequest($code);
        return ResponseFormatter::success($data,'Successfully Get Data');
    }
    function assignedPersonnelDetail($ireqd_id,$code){
        DB::table('ireq_dtl')
        ->where('ireqd_id',$ireqd_id)
        ->where('ireq_id',$code)
        ->update([
            'ireq_status' => 'T',
            'last_update_date' => now(),
            'ireq_assigned_date' => now(),
            'last_updated_by' => Auth::user()->usr_id,
            'program_name' => "IctDetail_appd"
        ]);
        $personel = IctDetail::select('ireq_assigned_to2')->where('ireq_id',$code)->where('ireqd_id',$ireqd_id)->pluck('ireq_assigned_to2');
        $dataPersonnel = Ict::where('ireq_id',$code)->first();
        $mail = Mng_user::SELECT('usr_email')->WHERE('usr_id',$personel)->pluck('usr_email');
        DB::getPdo()->exec("begin SP_PENUGASAN_IREQ_MST('$code'); end;");
        
        SendNotifPersonnel::dispatchAfterResponse($mail,$dataPersonnel);
        $this->IctDetailServices->cekStatusPenugasan($code);
        return ResponseFormatter::success('Successfully');
    }
    function getRemarkReviewer($ireq_id){
        $ict = Ict::select('ireq_verificator_remark')->where('ireq_id',$ireq_id)->first();
        return json_encode($ict);
    }
    function saveRemarkReviewer(Request $request){
        $ict = Ict::findOrFail($request->id);
        $ict->ireq_verificator_remark = $request->remark;
        $ict->save();
        return ResponseFormatter::success($ict,'Successfully save remark');
    }
    function getDataReviewer(){
        $data['ict'] = $this->reviewerServices->getDataWithFilter('P',NULL,NULL,NULL);
        $data['ict1'] = $this->reviewerServices->getDataWithFilter('NA1','A1',NULL,NULL); 
        $data['ict2'] = $this->reviewerServices->getDataWithFilter('NA2','A2',NULL,NULL); 
        $data['ict3'] = $this->reviewerServices->getDataWithFilter('RR','RA1','RA2',NULL); 
        $data['ict4'] = $this->reviewerServices->getDataWithFilter('T',NULL,NULL,NULL);
        $data['ict7'] = $this->reviewerServices->getDataWithFilter('NT','RT',NULL,NULL);
        $data['ict8'] = $this->reviewerServices->getDataWithFilter(NULL,NULL,NULL,'status');
        $data['ict5'] = $this->reviewerServices->getDetailWithFilter('D');
        $data['ict6'] = $this->reviewerServices->getDetailWithFilter('C');

        return json_encode($data,200);
    }
    function detailRequestReviewer($ireq_id){
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
    function rejectReviewer(Request $request, $code){
        $this->reviewerServices->RejectedByReviewer($request,$code);
        return ResponseFormatter::success('Successfully rejected request');
    }
    function needApprovalAtasan($ireq_id){
        $this->reviewerServices->NeedApprovalByHigherLevel($ireq_id);
        return ResponseFormatter::success('Successfully send mail');
    }
    function needApprovalManager($ireq_id){
        $this->reviewerServices->needApprovalByIctManager($ireq_id);
        return ResponseFormatter::success('Successfully send mail');
    }
    function asignPerRequestReviewer(Request $request){
        $this->reviewerServices->assignPerRequest($request);
        return ResponseFormatter::success('Successfully assign request');
    }
    function submitAssignPerRequest($ireq_id){
        $this->reviewerServices->submitAssignPerRequets($ireq_id); 
        return ResponseFormatter::success('Successfully Submit');

    }
    function updateStatusClosingDetail($ireqd_id,$ireq_id){
        $save = DB::table('ireq_dtl')
        ->where('ireqd_id',$ireqd_id)
        ->where('ireq_id',$ireq_id)
        ->update([
            'ireq_status' => 'C',
            'ireq_date_closing'=> now(),
            'last_update_date' => now(),
            'last_updated_by' => Auth::user()->usr_id,
            'program_name' => "IctDetail_updateStatusClosingDetail"
        ]);
        DB::getPdo()->exec("begin SP_CLOSING_IREQ_MST('$ireq_id'); end;");
        return ResponseFormatter::success($save,'Successfully Closing');
    }
    function getDataIct(){
        $ict = DB::table('ireq_dtl as id')
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
    function updateAssignPerRequest(Request $request){
        $ict = Ict::where('ireq_id',$request->id)->first();
        $ict->ireq_status = 'T';
        $ict->ireq_assigned_to1 = $request->name;
        $ict->last_updated_by = Auth::user()->usr_id;
        $ict->last_update_date = now();
        $ict->program_name = "IctController_updateAssign";
        $ict->save();

        $dtl = IctDetail::where('ireq_id',$request->id)->get();
        foreach ($dtl as $d){
            $d->ireq_status = 'T';
            $d->ireq_assigned_to1 = $request->name;
            $d->last_update_date = now();
            $d->last_updated_by = Auth::user()->usr_id;
            $d->program_name = "IctController_updateAssign";
            $d->save();
        }
        return ResponseFormatter::success($ict,'Successfully Assigned');
    }
    function updateStatusPenugasan($ireq_id){
        $ict = Ict::where('ireq_id',$ireq_id)->first();
        $ict->ireq_status = 'T';
        $ict->ireq_verificator = Auth::user()->usr_id;
        $ict->last_update_date = now();    
        $ict->last_updated_by = Auth::user()->usr_id;
        $ict->program_name = "IctController_updateStatusPenugasan";
        $ict->save();
        
        DB::table('ireq_dtl')
        ->WHERE('ireq_id',$ireq_id)
        ->update([
            'ireq_status' => 'T',
            'last_update_date' => now(),
            'last_updated_by' => Auth::user()->usr_id,
            'program_name' => "IctController_updateStatusPenugasan",
        ]);

        return ResponseFormatter::success($ict,'Successfully Updated');
    }
    function updateStatusClosing($ireq_id){
        $ict = Ict::where('ireq_id',$ireq_id)->first();
        $ict->ireq_approver2 = Auth::user()->usr_id;
        $ict->ireq_status = 'C';
        $ict->last_update_date = now();
        $ict->last_updated_by = Auth::user()->usr_id;
        $ict->ireq_date_closing = now();
        $ict->program_name = "IctController_updateStatusClosing";
        $ict->save();

        DB::table('ireq_dtl')
        ->WHERE('ireq_id',$ireq_id)
        ->update([
            'ireq_status' => 'C',
            'last_update_date' => now(),
            'last_updated_by' => Auth::user()->usr_id,
            'program_name' => "IctController_updateStatusClosing",
        ]);
        
        return ResponseFormatter::success($ict,'Successfully Closing ');
    }
    function updateAssignFromReject(Request $request,$code){
        $save = DB::table('ireq_dtl')
        ->where('ireqd_id',$code)
        ->where('ireq_id',$request->ireq_id)
        ->update([
            'ireq_assigned_to1_reason'=>$request->ireq_assigned_to1_reason,
            'ireq_assigned_to2' => $request->ireq_assigned_to2,
            'last_update_date' => now(),
            'last_updated_by' => Auth::user()->usr_id
        ]);
        return ResponseFormatter::success($save,'Successfully Assigned');
    }
    function updateAssignPerDetail(Request $request,$code){
        $save = DB::table('ireq_dtl')
        ->where('ireqd_id',$request->ireqd_id)
        ->where('ireq_id',$code)
        ->update([
            'ireq_assigned_to1'=>$request->ireq_assigned_to1,
            'last_update_date' =>now(),
            'last_updated_by'=>Auth::user()->usr_id
        ]);
        return ResponseFormatter::success($save,'Successfully Assigned');
    }
    function cetak_pdf($filter1, $filter2 = null, $filter3 = null, $filter4 = null, $viewName, $methodService) {
        $ict = $this->reviewerServices->$methodService($filter1, $filter2, $filter3, $filter4);
        $data['htmlContent'] = View::make($viewName, compact('ict'))->render();
        return ResponseFormatter::success($data, 'Successfully Print Report');
    }
    
    function printPdfByFilter(Request $request) {
        // Mapping of report types to parameters
        $reportParams = [
            'permohonan' => ['P', null, null, null, 'pdf.Laporan_IctRequest_Permohonan', 'getDataWithFilter'],
            'atasan' => ['NA1', 'A1', null, null, 'pdf.Laporan_IctRequest_Permohonan', 'getDataWithFilter'],
            'manager' => ['NA2', 'A2', null, null, 'pdf.Laporan_IctRequest_Permohonan', 'getDataWithFilter'],
            'verifikasi' => ['A1', 'A2', null, null, 'pdf.Laporan_IctRequest_Verifikasi', 'getDataWithFilter'],
            'selesai' => ['C', null, null, null, 'pdf.Laporan_IctRequest_Selesai', 'getDetailWithFilter'],
            'sudah_dikerjakan' => ['D', null, null, null, 'pdf.Laporan_IctRequest_Sudah_Dikerjakan', 'getDetailWithFilter'],
            'sedang_dikerjakan' => ['T', null, null, null, 'pdf.Laporan_IctRequest_Sedang_Dikerjakan', 'getDataWithFilter'],
            'assignment_request' => ['NT', 'RT', null, null, 'pdf.Laporan_IctRequest_Sedang_Dikerjakan', 'getDataWithFilter'],
            'reject' => ['RA1', 'RA2', 'RR', null, 'pdf.Laporan_IctRequest_Reject', 'getDataWithFilter'],
        ];
    
        // Check if the report type exists in the mapping
        if (array_key_exists($request->report_type, $reportParams)) {
            return $this->cetak_pdf(...$reportParams[$request->report_type]);
        }
    
        return ResponseFormatter::error(null, 'Invalid report type');
    }
    function printOutExcel(Request $request) {
        $type = $request->report_type;
        $exportClassMap = [
            'permohonan' => IctExportReviewerPermohonan::class,
            'atasan_divisi' => IctExportReviewerAtasanDivisi::class,
            'ict_manager' => IctExportReviewerIctManager::class,
            'reject' => IctExportRejectReviewer::class,
            'assignment_request' => IctExportReviewerAssignmentRequest::class,
            'sedang_dikerjakan' => IctExportReviewerSedangDikerjakan::class,
            'sudah_dikerjakan' => IctExportReviewerSudahDikerjakan::class,
            'selesai' => IctExportReviewerSelesai::class,
        ];
    
        $exportClass = $exportClassMap[$type];
        $newCreation = now()->timezone('Asia/Jakarta')->format('d M Y');
        $prefix = 'ICT REQUEST STATUS REPORT LIST';
    
        return Excel::download(new $exportClass, "{$prefix} ON {$newCreation}.xlsx");
    }
}
