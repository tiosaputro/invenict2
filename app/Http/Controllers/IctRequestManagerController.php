<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\IctDetail;
use App\Helpers\ResponseFormatter;
use App\Models\Mng_user;
use Maatwebsite\Excel\Facades\Excel;
use App\Services\IctRequestManagerServices;
use App\Services\IctServices;
use App\Exports\IctExportManagerPermohonan;
use App\Exports\IctExportManagerSudahDikerjakan;
use App\Exports\IctExportManagerSudahSelesai;
use App\Exports\IctExportManagerAssignmentRequest;
use App\Exports\IctExportVerifikasiManager;
use App\Exports\IctExportRejectManager;
use App\Exports\IctExportManagerSedangDikerjakan;
use App\Services\IctDetailServices;
use App\Models\Lookup_Refs;
use App\Services\PekerjaServices;
use Illuminate\Support\Facades\View;

class IctRequestManagerController extends Controller
{
    protected $to;
    protected $userMenu;
    protected $managerServices;
    protected $IctServices;
    protected $IctDetailServices;
    function __construct(IctRequestManagerServices $services, IctServices $service, IctDetailServices $dtlService){
        $this->managerServices = $services;
        $this->IctServices = $service;
        $this->IctDetailServices = $dtlService;
        $this->middleware('auth:sanctum');
        $this->to = "/ict-request-manager";
        $this->middleware(function ($request, $next) {
          $this->userMenu = Mng_User::menu();
            if($this->userMenu->contains($this->to)){    
                return $next($request);
            } else {
                return response(["message"=>"Cannot Access"],403);
            }
        });
    }
    
    function getDataManager()
    {
            $data['ict'] = $this->managerServices->getDataRequestWithFilter('NA2', 'NA1', 'P', NULL);
            $data['ict1'] = $this->managerServices->getDataRequestWithFilter('A2', 'A1', NULL, NULL);
            $data['ict2'] = $this->managerServices->getDataRequestWithFilter('RR', 'RA1', 'RA2', NULL);
            $data['ict3'] = $this->managerServices->getDataRequestWithFilter('T', NULL, NULL, NULL);
            $data['ict4'] = $this->managerServices->getDataDetailWithFilter('D');
            $data['ict5'] = $this->managerServices->getDataDetailWithFilter('C');
            $data['ict6'] = $this->managerServices->getDataRequestWithFilter('NT', 'RT', NULL, NULL); 
            $data['ict7'] = $this->managerServices->getDataRequestWithFilter('P', NULL, NULL, NULL); 
            $data['ict8'] = $this->managerServices->getDataRequestWithFilter('NA1', 'NA2', NULL, NULL); 
            $data['ict9'] = $this->managerServices->getDataRequestWithFilter(NULL, NULL, NULL, 'TRUE'); 

            return ResponseFormatter::success($data,'Successfully Get Data Request'); 
    }

    function detailRequest($code){
        $pekerjaService = new PekerjaServices();
        $data['detail'] = $this->IctDetailServices->getDataDetailRequest($code,NULL,NULL,NULL);
        $data['request'] = $this->IctServices->detailNoRequest($code);
        $data['pekerja'] = $pekerjaService->getPekerja();
        $data['request_type'] = Lookup_Refs::Type();
        return ResponseFormatter::success($data,'Successfully Get Data Detail Request'); 
    }

    function getDetailVerif($code)
    {
        $data = IctDetail::detailVerification($code);
        return response()->json($data);
    }

    function getDataManagerVerifikasi($code)
    {
        $dtl = DB::table('ireq_dtl as id')
            ->SELECT('lr.lookup_desc as ireq_type',DB::raw("(crs.catalog_name ||' - '|| cr.catalog_name) as lookup_desc"),'id.invent_code')
            ->LEFTJOIN('invent_mst as im','id.invent_code','im.invent_code')
            ->LEFTJOIN('lookup_refs as lr','id.ireq_type','lr.lookup_code')
            ->LEFTJOIN('catalog_refs as cr',function ($join) {
                $join->on('id.invent_code','cr.catalog_id');
            })
            ->LEFTJOIN('catalog_refs as crs',function ($join) {
                $join->on('cr.parent_id','crs.catalog_id');
            })
            ->WHERE('id.ireq_id',$code)
            ->WHERERaw('LOWER(lr.lookup_type) LIKE ? ',[trim(strtolower('req_type')).'%'])
            ->get();
            return response()->json($dtl);
    }

    function approveByManager(Request $request,$code)
    { 
        $this->managerServices->approvedByIctManager($request,$code);

        return ResponseFormatter::success('Successfully approved Request');
    }

    function rejectByManager(Request $request,$code)
    {
        $this->managerServices->RejectedByIctManager($request,$code);

        return ResponseFormatter::success('Successfully rejected Request');
    }

    function detailPenugasan($code)
    {
        $data['detail'] = $this->IctDetailServices->getDataDetailRequest($code, 'NT','RT','T');
        $data['request'] = $this->IctServices->detailNoRequest($code);
        return ResponseFormatter::success($data,'Successfully Get Data');
    }
    function cetak_pdf_manager_permohonan()
    {
        $ict =  $this->managerServices->getDataRequestWithFilter('NA2', 'NA1', 'P', NULL);
        $data['htmlContent'] = View::make('pdf.Laporan_IctRequest_Permohonan', compact('ict'))->render();
        return ResponseFormatter::success($data,'Successfully Print Report');
    }

    function cetak_excel_manager_permohonan()
    {
        $newCreation = Carbon::parse(Carbon::now())->copy()->tz('Asia/Jakarta')->format('d M Y');
        return Excel::download(new IctExportManagerPermohonan,'ICT REQUEST STATUS REPORT LIST ON '.$newCreation.'.xlsx');
    }

    function cetak_pdf_manager_verifikasi()
    {
        $ict =  $this->managerServices->getDataRequestWithFilter('A1', 'A2', NULL, NULL);
        $data['htmlContent'] = View::make('pdf.Laporan_IctRequest_Permohonan', compact('ict'))->render();
        return ResponseFormatter::success($data,'Successfully Print Report');
    }
    function cetak_excel_manager_verifikasi()
    {
        $newCreation = Carbon::parse(Carbon::now())->copy()->tz('Asia/Jakarta')->format('d M Y');
        return Excel::download(new IctExportVerifikasiManager(),'ICT REQUEST STATUS REPORT LIST ON '.$newCreation.'.xlsx');
    }

    function cetak_pdf_manager_reject()
    {
        $ict =  $this->managerServices->getDataRequestWithFilter('RR', 'RA1', 'RA2', NULL);
        $data['htmlContent'] = View::make('pdf.Laporan_IctRequest_Reject', compact('ict'))->render();
        return ResponseFormatter::success($data,'Successfully Print Report');
    }

    function cetak_excel_manager_reject()
    {
        $newCreation = Carbon::parse(Carbon::now())->copy()->tz('Asia/Jakarta')->format('d M Y');
        return Excel::download(new IctExportRejectManager,'ICT REQUEST STATUS REPORT LIST ON '.$newCreation.'.xlsx');
    }

    function cetak_pdf_manager_assignment_request()
    {
        $ict =  $this->managerServices->getDataRequestWithFilter('NT', 'RT', NULL, NULL);
        $data['htmlContent'] = View::make('pdf.Laporan_IctRequest_Sedang_Dikerjakan', compact('ict'))->render();
        return ResponseFormatter::success($data,'Successfully Print Report');
    }

    function cetak_excel_manager_assignment_request()
    {
        $newCreation = Carbon::parse(Carbon::now())->copy()->tz('Asia/Jakarta')->format('d M Y');
        return Excel::download(new IctExportManagerAssignmentRequest,'ICT REQUEST STATUS REPORT LIST ON '.$newCreation.'.xlsx');
    }

    function cetak_pdf_manager_sedang_dikerjakan()
    {
        $ict =  $this->managerServices->getDataRequestWithFilter('T', NULL, NULL, NULL);
        $data['htmlContent'] = View::make('pdf.Laporan_IctRequest_Sedang_Dikerjakan', compact('ict'))->render();
        return ResponseFormatter::success($data,'Successfully Print Report');
    }

    function cetak_excel_manager_sedang_dikerjakan()
    {
        $newCreation = Carbon::parse(Carbon::now())->copy()->tz('Asia/Jakarta')->format('d M Y');
        return Excel::download(new IctExportManagerSedangDikerjakan,'ICT REQUEST STATUS REPORT LIST ON '.$newCreation.'.xlsx');
    }

    function cetak_pdf_manager_sudah_dikerjakan()
    {
        $ict =  $this->managerServices->getDataDetailWithFilter('D');
        $data['htmlContent'] = View::make('pdf.Laporan_IctRequest_Sudah_Dikerjakan', compact('ict'))->render();
        return ResponseFormatter::success($data,'Successfully Print Report');
    }

    function cetak_excel_manager_sudah_dikerjakan()
    {
        $newCreation = Carbon::parse(Carbon::now())->copy()->tz('Asia/Jakarta')->format('d M Y');
        return Excel::download(new IctExportManagerSudahDikerjakan,'ICT REQUEST STATUS REPORT LIST ON '.$newCreation.'.xlsx');
    }

    function cetak_pdf_manager_selesai()
    {
        $ict =  $this->managerServices->getDataDetailWithFilter('C');
        $data['htmlContent'] = View::make('pdf.Laporan_IctRequest_Sudah_Dikerjakan', compact('ict'))->render();
        return ResponseFormatter::success($data,'Successfully Print Report');
    }
    
    function cetak_excel_manager_selesai()
    {
        $newCreation = Carbon::parse(Carbon::now())->copy()->tz('Asia/Jakarta')->format('d M Y');
        return Excel::download(new IctExportManagerSudahSelesai,'ICT REQUEST STATUS REPORT LIST ON '.$newCreation.'.xlsx');
    }
}
