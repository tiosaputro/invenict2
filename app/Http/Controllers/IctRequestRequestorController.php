<?php

namespace App\Http\Controllers;

use App\Exports\IctExportAssignmentRequest;
use App\Exports\IctExportPermohonan;
use App\Exports\IctExportReject;
use App\Exports\IctExportSedangDikerjakan;
use App\Exports\IctExportSelesai;
use App\Exports\IctExportSudahDikerjakan;
use App\Exports\IctExportTabReviewer;
use App\Exports\IctExportVerifikasi;
use App\Helpers\ResponseFormatter;
use App\Jobs\SendNotifRequest;
use App\Models\Ict;
use App\Models\IctDetail;
use App\Models\Link;
use App\Models\Location;
use App\Models\Lookup_Refs;
use App\Models\Mng_user;
use App\Services\IctRequestorServices;
use App\Services\MngUserDomainServices;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;
use Maatwebsite\Excel\Facades\Excel;

class IctRequestRequestorController extends Controller
{
    protected $to;
    protected $userMenu;
    protected $requestorServices;

    public function __construct(IctRequestorServices $services){
        $this->requestorServices = $services;
        $this->middleware(['auth:sanctum', function ($request, $next) {
            $this->to = "/ict-request";
            $this->userMenu = Mng_User::menu();
            if ($this->userMenu->contains($this->to)) {
                return $next($request);
            } else {
                return response(["message" => "Cannot Access"], 403);
            }
        }]);
    }

    function getIct(){
        $data['ict'] = $this->requestorServices->getDataWithFilter('P', null, null, 'status', null);
        $data['ict1'] = $this->requestorServices->getDataWithFilter('A1', 'A2', null, null, null);
        $data['ict2'] = $this->requestorServices->getDataWithFilter('RR', 'RA1', 'RA2', null, null);
        $data['ict3'] = $this->requestorServices->getDataWithFilter('T', null, null, null, null);
        $data['ict4'] = $this->requestorServices->getDetailIct('D', Auth::user()->usr_id);
        $data['ict5'] = $this->requestorServices->getDetailIct('C', Auth::user()->usr_id);
        $data['ict6'] = $this->requestorServices->getDataWithFilter('NA1', 'NA2', null, null, null);
        $data['ict7'] = $this->requestorServices->getDataWithFilter('P', null, null, null, null);
        $data['ict8'] = $this->requestorServices->getDataWithFilter('NT', 'RT', null, null, null);
        $data['ict9'] = $this->requestorServices->getDataWithFilter('T', 'NT', null, null, null);
        $data['ict10'] = $this->requestorServices->getDataWithFilter(null, null, null, null, 'status');
        return json_encode($data);

    }
    function add(){
        $data['ref'] = Lookup_Refs::Type();
        $data['priority'] = Lookup_Refs::Prioritas();
        $data['requestor'] = Auth::user();
        $data['userList'] = app(MngUserDomainServices::class)->getAllData();
        return json_encode($data);

    }
    function save(Request $request){
        $save = $this->requestorServices->saveRequest($request);
        return ResponseFormatter::success($save, 'Successfully Save Request');
    }
    function edit($code){
        $data['ict'] = Ict::SELECT(
            'ireq_mst.ireq_id',
            'mu.usr_fullname as ireq_requestor',
            'ireq_mst.ireq_no',
            'ireq_mst.ireq_prio_level',
            'ireq_mst.ireq_type',
            'ireq_mst.ireq_date',
            'ireq_mst.ireq_user',
            'ireq_mst.ireq_spv',
            'mu.usr_division as division_requestor',
            'usr.usr_division as division_user',
            'usr.usr_department as department_user',
            'usr.usr_bu as company_user'
        )
            ->LEFTJOIN('lookup_refs as lr', function ($join) {
                $join->on('ireq_mst.ireq_type', 'lr.lookup_code')
                    ->WHERERaw('LOWER(lr.lookup_type) LIKE ? ', [trim(strtolower('req_type')) . '%']);
            })
            ->LEFTJOIN('mng_user_domain usr', 'ireq_mst.ireq_user', 'usr.usr_domain')
            ->LEFTJOIN('mng_users mu', 'ireq_mst.ireq_requestor', 'mu.usr_id')
            ->WHERE('ireq_mst.ireq_id', $code)
            ->first();

        $data['priority'] = Lookup_Refs::Prioritas();
        $data['userlist'] = app(MngUserDomainServices::class)->getAllData();
        return json_encode($data);
    }
    function update(Request $request, $code){
        $save = $this->requestorServices->updateRequest($request, $code);
        return ResponseFormatter::success($save, 'Successfully Updated Request');
    }
    function delete($ireq_id){
        $save = $this->requestorServices->deleteRequest($ireq_id);
        return ResponseFormatter::success($save, 'Successfully Deleted Request');
    }
    function submitRating(Request $request){
        if ($request->rating <= '2') {
            $dtl = DB::table('ireq_dtl')
                ->where('ireqd_id', $request->id)
                ->where('ireq_id', $request->ireq_id)
                ->update([
                    'ireq_value' => $request->rating,
                    'ireq_note' => $request->ket,
                ]);

            return ResponseFormatter::success($dtl, 'Successfully Submitted Rating');
        } else {
            $dtl = DB::table('ireq_dtl')
                ->where('ireqd_id', $request->id)
                ->where('ireq_id', $request->ireq_id)
                ->update([
                    'ireq_value' => $request->rating,
                ]);
            return ResponseFormatter::success($dtl, 'Successfully Submitted Rating');
        }
    }
    function getNoreq(){
        $data = $this->requestorServices->listNoRequest();
        return ResponseFormatter::success($data, 'Successfully get data');
    }
    function getNameBu($noreq, $dtl){
        $ict = DB::table('ireq_mst as im')
            ->SELECT('im.ireq_requestor as req', 'im.ireq_no', 'im.ireq_id', 'vr.name as bu', 'id.ireqd_id', 'im.ireq_user',
                DB::raw("TO_CHAR(im.ireq_date, 'dd Mon YYYY') as ireq_date"))
            ->LEFTJOIN('vcompany_refs as vr', 'im.ireq_bu', 'vr.company_code')
            ->LEFTJOIN('ireq_dtl as id', 'im.ireq_id', 'id.ireq_id')
            ->WHERE('im.ireq_id', $noreq)
            ->WHERE('id.ireqd_id', $dtl)
            ->first();
        return response()->json($ict);
    }
    function detailNoRequest($code){
        $ict = DB::table('ireq_mst as im')
            ->LEFTJOIN('divisi_refs as dr', 'im.ireq_divisi_user', 'dr.div_id')
            ->LEFTJOIN('vcompany_refs as vr', 'im.ireq_bu', 'vr.company_code')
            ->LEFTJOIN('mng_users as mu', 'im.ireq_approver1', 'mu.usr_name')
            ->LEFTJOIN('mng_users as muu', 'im.ireq_approver2', 'muu.usr_name')
            ->SELECT(
                'im.ireq_no',
                'im.ireq_date',
                'vr.name as ireq_bu',
                'im.ireq_approver1_date as date_approver1',
                'im.ireq_approver2_date as date_approver2',
                'dr.div_name', 'im.ireq_user',
                'im.ireq_requestor',
                'mu.usr_fullname as ireq_approval1',
                'muu.usr_fullname as ireq_approval2')
            ->WHERE('im.ireq_id', $code)
            ->get();
        return json_encode($ict);
    }
    function updateStatusSubmit($ireq_id){
        $ICT = Ict::where('ireq_id', $ireq_id)->first();
        $ICT->ireq_status = 'P';
        $ICT->ireq_date = now();
        $ICT->last_update_date = now();
        $ICT->last_updated_by = Auth::user()->usr_id;
        $ICT->program_name = "IctController_updateStatusSubmit";
        $ICT->save();

        DB::table('ireq_dtl')
            ->WHERE('ireq_id', $ireq_id)
            ->update([
                'ireq_status' => 'P',
                'last_update_date' => now(),
                'last_updated_by' => Auth::user()->usr_id,
                'program_name' => "IctController_updateStatusSubmit",
            ]);
        Link::createLinkReviewer($ireq_id);
        $data = $this->requestorServices->getDetailIct(null, null, $ireq_id);
        $LINK = Link::where('ireq_id', $ireq_id)->first();
        if (env('APP_ENV') != 'local') {
            $send_mail = Location::select('loc_email')->WHERE('loc_code', Auth::user()->usr_loc)->pluck('loc_email');
        } else {
            $send_mail = 'adhitya.saputro@emp.id';
        }
        SendNotifRequest::dispatchAfterResponse($send_mail, $data, $LINK);
        return ResponseFormatter::success($ICT, 'Successfully Submitted Request');
    }
    function getDetail($noreq){
        $ict = IctDetail::select('ireqd_id as code')->WHERE('ireq_id', $noreq)->get();
        return response()->json($ict);
    }
    function cetak_pdf($filter1, $filter2 = null, $filter3 = null, $filter4 = null, $filter5 = null, $viewName, $methodService){
        $ict = $this->requestorServices->$methodService($filter1, $filter2, $filter3, $filter4, $filter5);
        $data['htmlContent'] = View::make($viewName, compact('ict'))->render();
        return ResponseFormatter::success($data, 'Successfully Print Report');
    }
    function printPdfByFilter(Request $request){
        switch($request->report_type){
            case 'permohonan':
                return $this->cetak_pdf('P', null, null, null, null, 'pdf.Laporan_IctRequest_Permohonan', 'getDataWithFilter');
            case 'tab_reviewer':
                return $this->cetak_pdf('NA1', 'NA2', null, null, null, 'pdf.Laporan_IctRequest_Tab_Reviewer', 'getDataWithFilter');
            case 'verifikasi':
                return $this->cetak_pdf('A1', 'A2', null, null, null, 'pdf.Laporan_IctRequest_Verifikasi', 'getDataWithFilter');
            case 'selesai':
                return $this->cetak_pdf('C', Auth::user()->usr_id, null, null, null, 'pdf.Laporan_IctRequest_Selesai', 'getDetailIct');
            case 'sudah_dikerjakan':
                return $this->cetak_pdf('D', Auth::user()->usr_id, null, null, null, 'pdf.Laporan_IctRequest_Sudah_Dikerjakan', 'getDetailIct');
            case 'sedang_dikerjakan':
                return $this->cetak_pdf('T', null, null, null, null, 'pdf.Laporan_IctRequest_Sedang_Dikerjakan', 'getDataWithFilter');
            case 'assignment_request':
                return $this->cetak_pdf('NT', 'RT', null, null, null, 'pdf.Laporan_IctRequest_Sedang_Dikerjakan', 'getDataWithFilter');
            case 'reject':
                return $this->cetak_pdf('RA1', 'RA2', 'RR', null, null, 'pdf.Laporan_IctRequest_Reject', 'getDataWithFilter');
            default:
                return ResponseFormatter::error(null, 'Invalid report type');
        }
    }
    function cetak_excel_permohonan(){
        $usr_name = Auth::user()->usr_id;
        $newCreation = Carbon::parse(Carbon::now())->copy()->tz('Asia/Jakarta')->format('d M Y');
        return Excel::download(new IctExportPermohonan($usr_name), 'ICT REQUEST STATUS REPORT LIST ON ' . $newCreation . '.xlsx');
    }
    function cetak_excel_tab_reviewer(){
        $usr_name = Auth::user()->usr_id;
        $newCreation = Carbon::parse(Carbon::now())->copy()->tz('Asia/Jakarta')->format('d M Y');
        return Excel::download(new IctExportTabReviewer(), 'ICT REQUEST STATUS REPORT LIST ON (Reviewer) ' . $newCreation . '.xlsx');
    }
    function cetak_excel_verifikasi(){
        $usr_name = Auth::user()->usr_id;
        $newCreation = Carbon::parse(Carbon::now())->copy()->tz('Asia/Jakarta')->format('d M Y');
        return Excel::download(new IctExportVerifikasi($usr_name), 'ICT REQUEST STATUS REPORT LIST ON (Terverifikasi) ' . $newCreation . '.xlsx');
    }
    function cetak_excel_reject(){
        $usr_name = Auth::user()->usr_id;
        $newCreation = Carbon::parse(Carbon::now())->copy()->tz('Asia/Jakarta')->format('d M Y');
        return Excel::download(new IctExportReject($usr_name), 'ICT REQUEST STATUS REPORT LIST ON (Direject) ' . $newCreation . '.xlsx');
    }
    function cetak_excel_assignment_request(){
        $usr_name = Auth::user()->usr_id;
        $newCreation = Carbon::parse(Carbon::now())->copy()->tz('Asia/Jakarta')->format('d M Y');
        return Excel::download(new IctExportAssignmentRequest($usr_name), 'ICT REQUEST STATUS REPORT LIST ON (Direject) ' . $newCreation . '.xlsx');
    }
    function cetak_excel_sedang_dikerjakan(){
        $usr_name = Auth::user()->usr_id;
        $newCreation = Carbon::parse(Carbon::now())->copy()->tz('Asia/Jakarta')->format('d M Y');
        return Excel::download(new IctExportSedangDikerjakan($usr_name), 'ICT REQUEST STATUS REPORT LIST ON (Sedang Dikerjakan) ' . $newCreation . '.xlsx');
    }
    function cetak_excel_sudah_dikerjakan(){
        $usr_name = Auth::user()->usr_id;
        $newCreation = Carbon::parse(Carbon::now())->copy()->tz('Asia/Jakarta')->format('d M Y');
        return Excel::download(new IctExportSudahDikerjakan($usr_name), 'ICT REQUEST STATUS REPORT LIST ON (Sudah Dikerjakan) ' . $newCreation . '.xlsx');
    }
    function cetak_excel_selesai(){
        $usr_name = Auth::user()->usr_id;
        $newCreation = Carbon::parse(Carbon::now())->copy()->tz('Asia/Jakarta')->format('d M Y');
        return Excel::download(new IctExportSelesai($usr_name), 'ICT REQUEST STATUS REPORT LIST ON ' . $newCreation . '.xlsx');
    }
}
