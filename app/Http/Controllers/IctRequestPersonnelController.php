<?php

namespace App\Http\Controllers;

use App\Exports\IctExportPersonnelAssignmentRequest;
use App\Exports\IctExportPersonnelReject;
use App\Exports\IctExportPersonnelSedangDikerjakan;
use App\Exports\IctExportPersonnelSelesai;
use App\Exports\IctExportPersonnelSudahDikerjakan;
use App\Helpers\ResponseFormatter;
use App\Jobs\SendNotifDoneUser;
use App\Models\Mng_user;
use App\Services\IctDetailServices;
use App\Services\IctRequestPersonnelServices;
use App\Services\IctServices;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;
use Maatwebsite\Excel\Facades\Excel;

class IctRequestPersonnelController extends Controller
{
    protected $to;
    protected $personnelService;
    protected $IctDetailService;
    protected $userMenu;
    public function __construct(IctRequestPersonnelServices $services, IctDetailServices $servicess){
        $this->personnelService = $services;
        $this->IctDetailService = $servicess;
        $this->middleware(['auth:sanctum', function ($request, $next) {
            $this->to = "/ict-request-personnel";
            $this->userMenu = Mng_User::menu();
            if ($this->userMenu->contains($this->to)) {
                return $next($request);
            } else {
                return response(["message" => "Cannot Access"], 403);
            }
        }]);
    }
    function getDataPersonnel(){
        $data['ict'] = $this->personnelService->getDetailWithFilter('T');
        $data['ict1'] = $this->personnelService->getDetailWithFilter('D');
        $data['ict2'] = $this->personnelService->getDetailWithFilter('C');
        $data['ict3'] = $this->personnelService->getDataWithFilter('NT');
        $data['ict4'] = $this->personnelService->getDetailWithFilter('RT');
        return ResponseFormatter::success($data, 'Successfully Get Data');
    }
    function saveRemark(Request $request, $code){
        $dtl = DB::table('ireq_dtl')
            ->where('ireqd_id', $code)
            ->where('ireq_id', $request->ireq_id)
            ->update([
                'ireq_assigned_remark' => $request->ireq_assigned_remark,
                'last_update_date' => now(),
                'last_updated_by' => Auth::user()->usr_id,
            ]);
        return ResponseFormatter::success($dtl, 'Successfully Added Remark');
    }
    function rejectedByPersonnel(Request $request, $ireq_id){
        $this->personnelService->rejectedByPersonnel($request, $ireq_id);
        return ResponseFormatter::success('Successfully rejected by personnel');

    }
    function acceptedByPersonnel($ireq_id){
        $this->personnelService->AcceptByPersonnel($ireq_id);
        $this->IctDetailService->cekStatusPenugasan($ireq_id);
        return ResponseFormatter::success('Successfully accepted by personnel');
    }
    function updateNote(Request $request, $code){

        $save = DB::table('ireq_dtl')
            ->where('ireqd_id', $code)
            ->where('ireq_id', $request->ireq_id)
            ->update([
                'ireq_note_personnel' => $request->ireq_reason,
                'last_update_date' => now(),
                'last_updated_by' => Auth::user()->usr_id,
            ]);

        return ResponseFormatter::success($save, 'Successfully Submitted Note');
    }
    function updateStatusDone(Request $request, $code){
        DB::table('ireq_dtl')
            ->where('ireqd_id', $request->ireqd_id)
            ->where('ireq_id', $code)
            ->update([
                'ireq_status' => $request->status,
                'last_update_date' => now(),
                'ireq_date_done' => now(),
                'last_updated_by' => Auth::user()->usr_id,
                'program_name' => "IctDetailController_updateStatusDone",
            ]);
        DB::getPdo()->exec("begin SP_DONE_IREQ_MST('$code'); end;");
        $datadone = $this->IctDetailService->getDataWithFilter($code, 'D');
        if (env('APP_ENV') != 'local') {
            $mail_requestor = $datadone[0]->mail_requestor;
        } else {
            $mail_requestor = 'adhitya.saputro@emp.id';
        }
        SendNotifDoneUser::dispatchAfterResponse($mail_requestor, $datadone);
        return ResponseFormatter::success('Successfully Updated Status');
    }
    function getDetailDone($code){
        $ictService = new IctServices();
        $data['detail'] = $this->IctDetailService->getDataDetailRequest($code, null, null, null);
        $data['request'] = $ictService->detailNoRequest($code);
        return ResponseFormatter::success($data, 'Successfully get data');
    }

    function cetak_pdf($filter, $viewName, $methodService){
        $ict = $this->personnelService->$methodService($filter);
        $data['htmlContent'] = View::make($viewName, compact('ict'))->render();
        return ResponseFormatter::success($data, 'Successfully Print Report');
    }

    function printPdfByFilter(Request $request){
        switch($request->report_type){
            case 'assignment_request':
                return $this->cetak_pdf('NT', 'pdf.Laporan_IctRequest_Sedang_Dikerjakan', 'getDataWithFilter');
            case 'reject':
                return $this->cetak_pdf('RT', 'pdf.Laporan_IctDetailReject', 'getDetailWithFilter');
            case 'sedang_dikerjakan':
                return $this->cetak_pdf('T', 'pdf.Laporan_IctRequest_Sudah_Dikerjakan', 'getDetailWithFilter');
            case 'sudah_dikerjakan':
                return $this->cetak_pdf('D', 'pdf.Laporan_IctRequest_Sudah_Dikerjakan', 'getDetailWithFilter');
            case 'selesai':
                return $this->cetak_pdf('C', 'pdf.Laporan_IctRequest_Selesai', 'getDetailWithFilter');
            default:
                return ResponseFormatter::error(null, 'Invalid report type');
        }
    }

    function cetak_excel_personnel_assignment_request(){
        $usr_fullname = Auth::user()->usr_id;
        $newCreation = Carbon::parse(Carbon::now())->copy()->tz('Asia/Jakarta')->format('d M Y');
        return Excel::download(new IctExportPersonnelAssignmentRequest($usr_fullname), 'ICT REQUEST STATUS REPORT LIST ON ' . $newCreation . '.xlsx');
    }
    function cetak_excel_personnel_reject(){
        $usr_fullname = Auth::user()->usr_id;
        $newCreation = Carbon::parse(Carbon::now())->copy()->tz('Asia/Jakarta')->format('d M Y');
        return Excel::download(new IctExportPersonnelReject($usr_fullname), 'ICT REQUEST STATUS REPORT LIST ON ' . $newCreation . '.xlsx');
    }
    function cetak_excel_personnel_sedang_dikerjakan(){
        $usr_fullname = Auth::user()->usr_id;
        $newCreation = Carbon::parse(Carbon::now())->copy()->tz('Asia/Jakarta')->format('d M Y');
        return Excel::download(new IctExportPersonnelSedangDikerjakan($usr_fullname), 'ICT REQUEST STATUS REPORT LIST ON ' . $newCreation . '.xlsx');
    }
    function cetak_excel_personnel_sudah_dikerjakan(){
        $usr_fullname = Auth::user()->usr_id;
        $newCreation = Carbon::parse(Carbon::now())->copy()->tz('Asia/Jakarta')->format('d M Y');
        return Excel::download(new IctExportPersonnelSudahDikerjakan($usr_fullname), 'ICT REQUEST STATUS REPORT LIST ON ' . $newCreation . '.xlsx');
    }
    function cetak_excel_personnel_selesai(){
        $usr_fullname = Auth::user()->usr_id;
        $newCreation = Carbon::parse(Carbon::now())->copy()->tz('Asia/Jakarta')->format('d M Y');
        return Excel::download(new IctExportPersonnelSelesai($usr_fullname), 'ICT REQUEST STATUS REPORT LIST ON ' . $newCreation . '.xlsx');
    }
}
