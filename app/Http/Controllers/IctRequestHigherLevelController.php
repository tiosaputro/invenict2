<?php

namespace App\Http\Controllers;

use App\Exports\IctExportAtasanAssignmentRequest;
use App\Exports\IctExportAtasanSedangDikerjakan;
use App\Exports\IctExportAtasanSelesai;
use App\Exports\IctExportAtasanSudahDikerjakan;
use App\Exports\IctExportPermohonanAtasan;
use App\Exports\IctExportRejectAtasan;
use App\Exports\IctExportVerifikasiAtasan;
use App\Helpers\ResponseFormatter;
use App\Models\Ict;
use App\Models\IctDetail;
use App\Models\Lookup_Refs;
use App\Models\Mng_user;
use App\Services\IctDetailServices;
use App\Services\IctRequestHigherLevelServices;
use App\Services\IctServices;
use App\Services\PekerjaServices;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Maatwebsite\Excel\Facades\Excel;

class IctRequestHigherLevelController extends Controller
{
    protected $to;
    protected $userMenu;
    protected $HigherLevelServices;
    protected $IctDetailServices;
    protected $IctServices;
    public function __construct(IctRequestHigherLevelServices $service, IctDetailServices $dtlservice, IctServices $ictservice){
        $this->HigherLevelServices = $service;
        $this->IctDetailServices = $dtlservice;
        $this->IctServices = $ictservice;
        $this->middleware(['auth:sanctum', function ($request, $next) {
            $this->to = "/ict-request-higher-level";
            $this->userMenu = Mng_User::menu();
        
            if ($this->userMenu->contains($this->to)) {
                return $next($request);
            } else {
                return response(["message" => "Cannot Access"], 403);
            }
        }]);
    }
    function approveByAtasan($code){
        $this->HigherLevelServices->ApprovedByAtasan($code);
        return ResponseFormatter::success('Successfully approved Request');
    }
    function rejectByAtasan(Request $request, $code){
        $this->HigherLevelServices->RejectedByAtasan($request, $code);
        return ResponseFormatter::success('Successfully rejected Request');
    }
    function getPermohonan(){
        $data['ict'] = $this->HigherLevelServices->getDataWithFilter('NA1', 'NA2', 'P', null);
        $data['ict1'] = $this->HigherLevelServices->getDataWithFilter('A1', 'A2', null, null);
        $data['ict2'] = $this->HigherLevelServices->getDataWithFilter('RA1', 'RA2', 'RR', null);
        $data['ict3'] = $this->HigherLevelServices->getDataWithFilter('T', null, null, null);
        $data['ict4'] = $this->HigherLevelServices->getDetailWithFilter('D');
        $data['ict5'] = $this->HigherLevelServices->getDetailWithFilter('C');
        $data['ict6'] = $this->HigherLevelServices->getDataWithFilter(null, null, null, 'true');
        $data['ict7'] = $this->HigherLevelServices->getDataWithFilter('NA1', 'NA2', null, null);
        $data['ict8'] = $this->HigherLevelServices->getDataWithFilter('P', null, null, null);
        $data['ict9'] = $this->HigherLevelServices->getDataWithFilter('NT', 'RT', null, null);

        return ResponseFormatter::success($data, 'Successfully get data');
    }
    function detailRequest($code){
        $pekerjaService = new PekerjaServices();
        $data['detail'] = $this->IctDetailServices->getDataDetailRequest($code, null, null, null);
        $data['request'] = $this->IctServices->detailNoRequest($code);
        $data['pekerja'] = $pekerjaService->getPekerja();
        $data['request_type'] = Lookup_Refs::Type();
        return ResponseFormatter::success($data, 'Successfully Get Data Detail Request');
    }
    function getDetailVerif($code){
        $data['detail'] = IctDetail::detailVerification($code);
        $data['norequest'] = Ict::detailNoRequest($code);
        return ResponseFormatter::success($data, 'Successfully get data');
    }

    function cetak_pdf($filter1, $filter2 = null, $filter3 = null, $filter4 = null, $viewName, $methodService){
        $ict = $this->HigherLevelServices->$methodService($filter1, $filter2, $filter3, $filter4);
        $data['htmlContent'] = View::make($viewName, compact('ict'))->render();
        return ResponseFormatter::success($data, 'Successfully Print Report');
    }

    function printPdfByFilter(Request $request){
        switch($request->report_type){
            case 'permohonan':
                return $this->cetak_pdf('NA2', 'NA1', 'P', null, 'pdf.Laporan_IctRequest_Permohonan', 'getDataWithFilter');
            case 'verifikasi':
                return $this->cetak_pdf('A1', 'A2', null, null, 'pdf.Laporan_IctRequest_Permohonan', 'getDataWithFilter');
            case 'assignment_request':
                return $this->cetak_pdf('NT', 'RT', null, null, 'pdf.Laporan_IctRequest_Sedang_Dikerjakan', 'getDataWithFilter');
            case 'reject':
                return $this->cetak_pdf('RA1', 'RA2', 'RR', null, 'pdf.Laporan_IctRequest_Reject', 'getDataWithFilter');
            case 'sedang_dikerjakan':
                return $this->cetak_pdf('T', null, null, null, 'pdf.Laporan_IctRequest_Sedang_Dikerjakan', 'getDataWithFilter');
            case 'sudah_dikerjakan':
                return $this->cetak_pdf('D',  null, null, null, 'pdf.Laporan_IctRequest_Sudah_Dikerjakan', 'getDetailWithFilter');
            case 'selesai':
                return $this->cetak_pdf('C', null, null, null, 'pdf.Laporan_IctRequest_Sudah_Dikerjakan', 'getDetailWithFilter');
            default:
                return ResponseFormatter::error(null, 'Invalid report type');
        }
    }

    function cetak_excel_atasan_permohonan(){
        $usr_email = Auth::user()->usrl_email;
        $newCreation = Carbon::parse(Carbon::now())->copy()->tz('Asia/Jakarta')->format('d M Y');
        return Excel::download(new IctExportPermohonanAtasan($usr_email), 'ICT REQUEST STATUS REPORT LIST ON ' . $newCreation . '.xlsx');
    }
    function cetak_excel_atasan_verifikasi(){
        $usr_email = Auth::user()->usr_email;
        $newCreation = Carbon::parse(Carbon::now())->copy()->tz('Asia/Jakarta')->format('d M Y');
        return Excel::download(new IctExportVerifikasiAtasan($usr_email), 'ICT REQUEST STATUS REPORT LIST ON ' . $newCreation . '.xlsx');
    }
    function cetak_excel_atasan_reject(){
        $usr_email = Auth::user()->usr_email;
        $newCreation = Carbon::parse(Carbon::now())->copy()->tz('Asia/Jakarta')->format('d M Y');
        return Excel::download(new IctExportRejectAtasan($usr_email), 'ICT REQUEST STATUS REPORT LIST ON ' . $newCreation . '.xlsx');
    }
    function cetak_excel_atasan_assignment_request(){
        $usr_email = Auth::user()->usr_email;
        $newCreation = Carbon::parse(Carbon::now())->copy()->tz('Asia/Jakarta')->format('d M Y');
        return Excel::download(new IctExportAtasanAssignmentRequest($usr_email), 'ICT REQUEST STATUS REPORT LIST ON ' . $newCreation . '.xlsx');
    }
    function cetak_excel_atasan_sedang_dikerjakan(){
        $usr_email = Auth::user()->usr_email;
        $newCreation = Carbon::parse(Carbon::now())->copy()->tz('Asia/Jakarta')->format('d M Y');
        return Excel::download(new IctExportAtasanSedangDikerjakan($usr_email), 'ICT REQUEST STATUS REPORT LIST ON ' . $newCreation . '.xlsx');
    }
    function cetak_pdf_atasan_sudah_dikerjakan(){
        $ict = $this->HigherLevelServices->getDetailWithFilter('D');
        $data['htmlContent'] = View::make('pdf.Laporan_IctRequest_Sudah_Dikerjakan', compact('ict'))->render();
        return ResponseFormatter::success($data, 'Successfully Print Report');
    }
    function cetak_excel_atasan_sudah_dikerjakan(){
        $usr_email = Auth::user()->usr_email;
        $newCreation = Carbon::parse(Carbon::now())->copy()->tz('Asia/Jakarta')->format('d M Y');
        return Excel::download(new IctExportAtasanSudahDikerjakan($usr_email), 'ICT REQUEST STATUS REPORT LIST ON ' . $newCreation . '.xlsx');
    }
    function cetak_pdf_atasan_selesai(){
        $ict = $this->HigherLevelServices->getDetailWithFilter('C');
        $data['htmlContent'] = View::make('pdf.Laporan_IctRequest_Selesai', compact('ict'))->render();
        return ResponseFormatter::success($data, 'Successfully Print Report');
    }
    function cetak_excel_atasan_selesai(){
        $usr_email = Auth::user()->usr_email;
        $newCreation = Carbon::parse(Carbon::now())->copy()->tz('Asia/Jakarta')->format('d M Y');
        return Excel::download(new IctExportAtasanSelesai($usr_email), 'ICT REQUEST STATUS REPORT LIST ON ' . $newCreation . '.xlsx');
    }
}
