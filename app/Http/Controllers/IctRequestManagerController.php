<?php

namespace App\Http\Controllers;

use App\Exports\IctExportManagerAssignmentRequest;
use App\Exports\IctExportManagerPermohonan;
use App\Exports\IctExportManagerSedangDikerjakan;
use App\Exports\IctExportManagerSudahDikerjakan;
use App\Exports\IctExportManagerSudahSelesai;
use App\Exports\IctExportRejectManager;
use App\Exports\IctExportVerifikasiManager;
use App\Helpers\ResponseFormatter;
use App\Models\IctDetail;
use App\Models\Lookup_Refs;
use App\Models\Mng_user;
use App\Services\IctDetailServices;
use App\Services\IctRequestManagerServices;
use App\Services\IctServices;
use App\Services\PekerjaServices;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;
use Maatwebsite\Excel\Facades\Excel;

class IctRequestManagerController extends Controller
{
    protected $to;
    protected $userMenu;
    protected $managerServices;
    protected $IctServices;
    protected $IctDetailServices;
    public function __construct(IctRequestManagerServices $services, IctServices $service, IctDetailServices $dtlService){
        $this->managerServices = $services;
        $this->IctServices = $service;
        $this->IctDetailServices = $dtlService;
        $this->middleware(['auth:sanctum', function ($request, $next) {
            $this->to = "/ict-request-manager";
            $this->userMenu = Mng_User::menu();
        
            if ($this->userMenu->contains($this->to)) {
                return $next($request);
            } else {
                return response(["message" => "Cannot Access"], 403);
            }
        }]);
    }

    public function getDataManager(){
        $data['ict'] = $this->managerServices->getDataRequestWithFilter('NA2', 'NA1', 'P', null);
        $data['ict1'] = $this->managerServices->getDataRequestWithFilter('A2', 'A1', null, null);
        $data['ict2'] = $this->managerServices->getDataRequestWithFilter('RR', 'RA1', 'RA2', null);
        $data['ict3'] = $this->managerServices->getDataRequestWithFilter('T', null, null, null);
        $data['ict4'] = $this->managerServices->getDataDetailWithFilter('D');
        $data['ict5'] = $this->managerServices->getDataDetailWithFilter('C');
        $data['ict6'] = $this->managerServices->getDataRequestWithFilter('NT', 'RT', null, null);
        $data['ict7'] = $this->managerServices->getDataRequestWithFilter('P', null, null, null);
        $data['ict8'] = $this->managerServices->getDataRequestWithFilter('NA1', 'NA2', null, null);
        $data['ict9'] = $this->managerServices->getDataRequestWithFilter(null, null, null, 'TRUE');

        return ResponseFormatter::success($data, 'Successfully Get Data Request');
    }

    public function detailRequest($code){
        $pekerjaService = new PekerjaServices();
        $data['detail'] = $this->IctDetailServices->getDataDetailRequest($code, null, null, null);
        $data['request'] = $this->IctServices->detailNoRequest($code);
        $data['pekerja'] = $pekerjaService->getPekerja();
        $data['request_type'] = Lookup_Refs::Type();
        return ResponseFormatter::success($data, 'Successfully Get Data Detail Request');
    }

    public function getDetailVerif($code){
        $data = IctDetail::detailVerification($code);
        return response()->json($data);
    }

    public function getDataManagerVerifikasi($code){
        $dtl = DB::table('ireq_dtl as id')
            ->SELECT('lr.lookup_desc as ireq_type', DB::raw("(crs.catalog_name ||' - '|| cr.catalog_name) as lookup_desc"), 'id.invent_code')
            ->LEFTJOIN('invent_mst as im', 'id.invent_code', 'im.invent_code')
            ->LEFTJOIN('lookup_refs as lr', 'id.ireq_type', 'lr.lookup_code')
            ->LEFTJOIN('catalog_refs as cr', function ($join) {
                $join->on('id.invent_code', 'cr.catalog_id');
            })
            ->LEFTJOIN('catalog_refs as crs', function ($join) {
                $join->on('cr.parent_id', 'crs.catalog_id');
            })
            ->WHERE('id.ireq_id', $code)
            ->WHERERaw('LOWER(lr.lookup_type) LIKE ? ', [trim(strtolower('req_type')) . '%'])
            ->get();
        return response()->json($dtl);
    }

    public function approveByManager(Request $request, $code){
        $this->managerServices->approvedByIctManager($request, $code);

        return ResponseFormatter::success('Successfully approved Request');
    }

    public function rejectByManager(Request $request, $code){
        $this->managerServices->RejectedByIctManager($request, $code);

        return ResponseFormatter::success('Successfully rejected Request');
    }

    public function detailPenugasan($code){
        $data['detail'] = $this->IctDetailServices->getDataDetailRequest($code, 'NT', 'RT', 'T');
        $data['request'] = $this->IctServices->detailNoRequest($code);
        return ResponseFormatter::success($data, 'Successfully Get Data');
    }

    
    public function cetak_pdf($filter1, $filter2 = null, $filter3 = null, $filter4 = null, $viewName, $methodService){
        $ict = $this->managerServices->$methodService($filter1, $filter2, $filter3, $filter4);
        $data['htmlContent'] = View::make($viewName, compact('ict'))->render();
        return ResponseFormatter::success($data, 'Successfully Print Report');
    }

    public function printPdfByFilter(Request $request){
        switch($request->report_type){
            case 'permohonan':
                return $this->cetak_pdf('NA2', 'NA1', 'P', null, 'pdf.Laporan_IctRequest_Permohonan', 'getDataRequestWithFilter');
            case 'verifikasi':
                return $this->cetak_pdf('A1', 'A2', null, null, 'pdf.Laporan_IctRequest_Permohonan', 'getDataRequestWithFilter');
            case 'assignment_request':
                return $this->cetak_pdf('NT', 'RT', null, null, 'pdf.Laporan_IctRequest_Sedang_Dikerjakan', 'getDataRequestWithFilter');
            case 'reject':
                return $this->cetak_pdf('RA1', 'RA2', 'RR', null, 'pdf.Laporan_IctRequest_Reject', 'getDataRequestWithFilter');
            case 'sedang_dikerjakan':
                return $this->cetak_pdf('T', null, null, null, 'pdf.Laporan_IctRequest_Sedang_Dikerjakan', 'getDataRequestWithFilter');
            case 'sudah_dikerjakan':
                return $this->cetak_pdf('D',  null, null, null, 'pdf.Laporan_IctRequest_Sudah_Dikerjakan', 'getDataDetailWithFilter');
            case 'selesai':
                return $this->cetak_pdf('C', null, null, null, 'pdf.Laporan_IctRequest_Sudah_Dikerjakan', 'getDataDetailWithFilter');
            default:
                return ResponseFormatter::error(null, 'Invalid report type');
        }
    }

    public function cetak_excel_manager_permohonan(){
        $newCreation = Carbon::parse(Carbon::now())->copy()->tz('Asia/Jakarta')->format('d M Y');
        return Excel::download(new IctExportManagerPermohonan, 'ICT REQUEST STATUS REPORT LIST ON ' . $newCreation . '.xlsx');
    }

    public function cetak_excel_manager_verifikasi(){
        $newCreation = Carbon::parse(Carbon::now())->copy()->tz('Asia/Jakarta')->format('d M Y');
        return Excel::download(new IctExportVerifikasiManager(), 'ICT REQUEST STATUS REPORT LIST ON ' . $newCreation . '.xlsx');
    }

    public function cetak_excel_manager_reject(){
        $newCreation = Carbon::parse(Carbon::now())->copy()->tz('Asia/Jakarta')->format('d M Y');
        return Excel::download(new IctExportRejectManager, 'ICT REQUEST STATUS REPORT LIST ON ' . $newCreation . '.xlsx');
    }

    public function cetak_excel_manager_assignment_request(){
        $newCreation = Carbon::parse(Carbon::now())->copy()->tz('Asia/Jakarta')->format('d M Y');
        return Excel::download(new IctExportManagerAssignmentRequest, 'ICT REQUEST STATUS REPORT LIST ON ' . $newCreation . '.xlsx');
    }

    public function cetak_excel_manager_sedang_dikerjakan(){
        $newCreation = Carbon::parse(Carbon::now())->copy()->tz('Asia/Jakarta')->format('d M Y');
        return Excel::download(new IctExportManagerSedangDikerjakan, 'ICT REQUEST STATUS REPORT LIST ON ' . $newCreation . '.xlsx');
    }

    public function cetak_excel_manager_sudah_dikerjakan(){
        $newCreation = Carbon::parse(Carbon::now())->copy()->tz('Asia/Jakarta')->format('d M Y');
        return Excel::download(new IctExportManagerSudahDikerjakan, 'ICT REQUEST STATUS REPORT LIST ON ' . $newCreation . '.xlsx');
    }

    public function cetak_excel_manager_selesai(){
        $newCreation = Carbon::parse(Carbon::now())->copy()->tz('Asia/Jakarta')->format('d M Y');
        return Excel::download(new IctExportManagerSudahSelesai, 'ICT REQUEST STATUS REPORT LIST ON ' . $newCreation . '.xlsx');
    }
}
