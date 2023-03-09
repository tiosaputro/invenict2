<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Model\Ict;
use App\Model\IctDetail;
use App\Helpers\ResponseFormatter;
use App\Mng_User;
use Maatwebsite\Excel\Facades\Excel;

use App\Exports\IctExportManagerPermohonan;
use App\Exports\IctExportManagerSudahDikerjakan;
use App\Exports\IctExportManagerSudahSelesai;
use App\Exports\IctExportManagerAssignmentRequest;
use App\Exports\IctExportVerifikasiManager;
use App\Exports\IctExportRejectManager;
use App\Exports\IctExportManagerSedangDikerjakan;

class IctRequestManagerController extends Controller
{
    protected $to;
    protected $userMenu;
    public function __construct(){
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
            $ict = DB::table('ireq_mst as im')
            ->SELECT('im.ireq_verificator_remark','im.ireq_id','im.ireq_no','im.ireq_date','im.ireq_user','im.ireq_requestor',
            'im.ireq_status as status','dr.div_name','lr.lookup_desc as ireq_status',DB::raw('count(im.ireq_verificator_remark) as count_remark'))
            ->LEFTJOIN('divisi_refs as dr','im.ireq_divisi_user','dr.div_id')
            ->LEFTJOIN('lookup_refs as lr','im.ireq_status','lr.lookup_code')
            ->WHERE(function($query){
                return $query
                ->WHERE('im.ireq_status','NA2')
                ->Orwhere('im.ireq_status','NA1')
                ->Orwhere('im.ireq_status','P');
            })
            ->WHERERaw('LOWER(lr.lookup_type) LIKE ? ',[trim(strtolower('ict_status')).'%'])
            ->groupBy('im.ireq_verificator_remark','lr.lookup_desc','im.ireq_id','im.ireq_no','im.ireq_date','im.ireq_user','im.ireq_status','im.ireq_requestor','dr.div_name','im.creation_date')
            ->ORDERBY('im.ireq_date','DESC')
            ->get(); 

            $ict1 = DB::table('ireq_mst as im')
                ->SELECT('im.ireq_verificator_remark',DB::raw('count(im.ireq_verificator_remark) as count_remark'), 'im.ireq_approver2_remark',DB::raw('count(im.ireq_approver2_remark) as count_remark_approver2'),'im.ireq_id','im.ireq_no','im.ireq_date','im.ireq_user','im.ireq_requestor', 
                'dr.div_name','lr.lookup_desc as ireq_status','im.ireq_status as status')
                ->LEFTJOIN('divisi_refs as dr','im.ireq_divisi_user','dr.div_id')
                ->LEFTJOIN('lookup_refs as lr','im.ireq_status','lr.lookup_code')
                ->WHERE('im.ireq_status','A2')
                ->orwhere('im.ireq_status','A1')
                ->WHERERaw('LOWER(lr.lookup_type) LIKE ? ',[trim(strtolower('ict_status')).'%'])
                ->groupBy('im.ireq_approver2_remark','im.ireq_verificator_remark','lr.lookup_desc','im.ireq_id','im.ireq_no','im.ireq_date','im.ireq_user','im.ireq_status','im.ireq_requestor','dr.div_name','im.creation_date')
                ->ORDERBY('im.ireq_date','DESC')
            ->get(); 

            $ict2 = DB::table('ireq_mst as im')
                ->SELECT('im.ireq_id','im.ireq_no','im.ireq_date','im.ireq_user','im.ireq_requestor','dr.div_name',
                'im.ireq_reason','lr.lookup_desc as ireq_status','im.ireq_status as status')
                ->LEFTJOIN('divisi_refs as dr','im.ireq_divisi_user','dr.div_id')
                ->LEFTJOIN('lookup_refs as lr','im.ireq_status','lr.lookup_code')
                ->WHERE('im.ireq_status','RR')
                ->orwhere('im.ireq_status','RA1')
                ->orwhere('im.ireq_status','RA2')
                ->WHERERaw('LOWER(lr.lookup_type) LIKE ? ',[trim(strtolower('ict_status')).'%'])
                ->groupBy('lr.lookup_desc','im.ireq_status','im.ireq_id','im.ireq_no','im.ireq_date','im.ireq_user','im.ireq_requestor','dr.div_name','im.creation_date','im.ireq_reason')
                ->ORDERBY('im.ireq_date','DESC')
            ->get(); 

            $ict3 = DB::table('ireq_mst as im')
                ->SELECT('im.ireq_id','im.ireq_no','im.ireq_date','im.ireq_user','im.ireq_requestor',
                'dr.div_name',DB::raw("COALESCE(im.ireq_assigned_to2,im.ireq_assigned_to1) AS ireq_assigned_to"),
                'lr.lookup_desc as ireq_status','im.ireq_status as status')
                ->LEFTJOIN('divisi_refs as dr','im.ireq_divisi_user','dr.div_id')
                ->LEFTJOIN('lookup_refs as lr','im.ireq_status','lr.lookup_code')
                ->WHERE('im.ireq_status','T')
                ->WHERERaw('LOWER(lr.lookup_type) LIKE ? ',[trim(strtolower('ict_status')).'%'])
                ->groupBy('lr.lookup_desc','im.ireq_status','im.ireq_id','im.ireq_no','im.ireq_date','im.ireq_user','im.ireq_requestor','dr.div_name','im.creation_date',DB::raw("COALESCE(im.ireq_assigned_to2,im.ireq_assigned_to1)"))
                ->ORDERBY('im.ireq_date','DESC')
                ->get();

            $ict4 = DB::table('ireq_dtl as id')
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
                ->SELECT('id.ireq_attachment','im.ireq_no','id.ireq_id','id.ireq_remark',DB::raw("COALESCE(id.ireq_assigned_to2,id.ireq_assigned_to1) AS ireq_assigned_to"),'id.ireqd_id',
                'lr.lookup_desc as ireq_status','lrs.lookup_desc as ireq_type',DB::raw("(crs.catalog_name ||' - '|| cr.catalog_name) as kategori"),'im.ireq_date','im.ireq_requestor','im.ireq_user',
                'dr.div_name','id.ireq_qty','id.ireq_status as status')
                ->WHERE('id.ireq_status','D')
                ->ORDERBY('im.ireq_date','DESC')
                ->ORDERBY('id.ireqd_id','ASC')
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
                ->SELECT('id.ireq_attachment','im.ireq_no','id.ireq_id','id.ireq_remark',DB::raw("COALESCE(id.ireq_assigned_to2,id.ireq_assigned_to1) AS ireq_assigned_to"),'id.ireqd_id',
                'lr.lookup_desc as ireq_status','lrs.lookup_desc as ireq_type',DB::raw("(crs.catalog_name ||' - '|| cr.catalog_name) as kategori"),'im.ireq_date','im.ireq_requestor','im.ireq_user',
                'dr.div_name','id.ireq_qty','id.ireq_status as status')
                ->WHERE('id.ireq_status','C')
                ->ORDERBY('im.ireq_date','DESC')
                ->ORDERBY('id.ireqd_id','ASC')
            ->get();

            $ict6 = DB::table('ireq_mst as im')
                ->LEFTJOIN('divisi_refs as dr','im.ireq_divisi_user','dr.div_id')
                ->LEFTJOIN('lookup_refs as lr','im.ireq_status','lr.lookup_code')
                ->SELECT('im.ireq_id','im.ireq_assigned_to2','im.ireq_no','im.ireq_date','im.ireq_user',
                'im.ireq_requestor','dr.div_name',DB::raw("COALESCE(im.ireq_assigned_to2,im.ireq_assigned_to1) 
                AS ireq_assigned_to"),'lr.lookup_desc as ireq_status','im.ireq_status as status')
                ->WHERE(function($query){
                    return $query
                    ->WHERE('im.ireq_status','NT')
                    ->Orwhere('im.ireq_status','RT');
                })
                ->WHERERaw('LOWER(lr.lookup_type) LIKE ? ',[trim(strtolower('ict_status')).'%'])
                ->groupBy('im.ireq_id','im.ireq_status','im.ireq_assigned_to2','im.ireq_no','im.ireq_date','im.ireq_user','im.ireq_requestor','dr.div_name','im.creation_date','lr.lookup_desc',DB::raw("COALESCE(im.ireq_assigned_to2,im.ireq_assigned_to1)"))
                ->ORDERBY('im.ireq_date','DESC')
            ->get();

            return response()->json(['ict'=>$ict,'ict1'=>$ict1,'ict2'=>$ict2,'ict3'=>$ict3,'ict4'=>$ict4,'ict5'=>$ict5,'ict6'=>$ict6],200);
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
        $save =  Ict::approvedByIctManager($request,$code);
        IctDetail::approvedByIctManager($code);

        return ResponseFormatter::success($save,'Successfully approved Request');
    }
    function rejectByManager(Request $request,$code)
    {
        
        $save = Ict::RejectedByIctManager($request,$code);
        IctDetail::RejectedByIctManager($request,$code);

        return ResponseFormatter::success($save,'Successfully rejected Request');
    }
    function cetak_pdf_manager_permohonan()
    {
        $ict =  DB::table('ireq_mst as im')
        ->SELECT('im.ireq_no','im.ireq_requestor','vr.name as ireq_bu','im.ireq_user','dr.div_name',
                DB::raw("TO_CHAR(im.ireq_date,' dd Mon YYYY') as ireq_date"),'llr.lookup_desc as ireq_status')
        ->LEFTJOIN('vcompany_refs as vr','im.ireq_bu','vr.company_code')
        ->LEFTJOIN('divisi_refs as dr','im.ireq_divisi_user','dr.div_id')
        ->LEFTJOIN('lookup_refs as llr','im.ireq_status','llr.lookup_code')
        ->WHERE(function($query){
            return $query
            ->WHERE('im.ireq_status','NA1')
            ->Orwhere('im.ireq_status','NA2')
            ->Orwhere('im.ireq_status','P');

        })
        ->WHERERaw('LOWER(llr.lookup_type) LIKE ? ',[trim(strtolower('ict_status')).'%'])
        ->ORDERBY('im.ireq_date','DESC')
        ->get();
        return view('pdf/Laporan_IctRequest_Permohonan', compact('ict'));
    }
    function cetak_excel_manager_permohonan()
    {
        $newCreation = Carbon::parse(Carbon::now())->copy()->tz('Asia/Jakarta')->format('d M Y');
        return Excel::download(new IctExportManagerPermohonan,'ICT REQUEST STATUS REPORT LIST ON '.$newCreation.'.xlsx');
    }
    function cetak_pdf_manager_verifikasi()
    {
        $ict =  DB::table('ireq_mst as im')
        ->SELECT('im.ireq_no','im.ireq_requestor','vr.name as ireq_bu','im.ireq_user','dr.div_name',
                DB::raw("TO_CHAR(im.ireq_date,' dd Mon YYYY') as ireq_date"),'llr.lookup_desc as ireq_status')
        ->LEFTJOIN('vcompany_refs as vr','im.ireq_bu','vr.company_code')
        ->LEFTJOIN('lookup_refs as llr','im.ireq_status','llr.lookup_code')
        ->LEFTJOIN('divisi_refs as dr','im.ireq_divisi_user','dr.div_id')
        ->WHERE(function($query){
            return $query
            ->WHERE('im.ireq_status','A1')
            ->orwhere('im.ireq_status','A2');
        })
        ->WHERERaw('LOWER(llr.lookup_type) LIKE ? ',[trim(strtolower('ict_status')).'%'])
        ->ORDERBY('im.ireq_date','DESC')
        ->get();
        return view('pdf/Laporan_IctRequest_Permohonan', compact('ict'));
    }
    function cetak_excel_manager_verifikasi()
    {
        $newCreation = Carbon::parse(Carbon::now())->copy()->tz('Asia/Jakarta')->format('d M Y');
        return Excel::download(new IctExportVerifikasiManager(),'ICT REQUEST STATUS REPORT LIST ON '.$newCreation.'.xlsx');
    }
    function cetak_pdf_manager_reject()
    {
        $ict =  DB::table('ireq_mst as im')
        ->SELECT('im.ireq_no','im.ireq_requestor','vr.name as ireq_bu','im.ireq_reason','lr.lookup_desc as ireq_type','im.ireq_user','dr.div_name',
                DB::raw("TO_CHAR(im.ireq_date,' dd Mon YYYY') as ireq_date"),'llr.lookup_desc as ireq_status')
        ->LEFTJOIN('vcompany_refs as vr','im.ireq_bu','vr.company_code')
        ->LEFTJOIN('lookup_refs as lr',function ($join) {
            $join->on('im.ireq_type','lr.lookup_code')
                  ->WHERERaw('LOWER(lr.lookup_type) LIKE ? ',[trim(strtolower('req_type')).'%']);
        })
        ->LEFTJOIN('lookup_refs as llr','im.ireq_status','llr.lookup_code')
        ->LEFTJOIN('divisi_refs as dr','im.ireq_divisi_user','dr.div_id')
        ->WHERE(function($query){
            return $query
            ->WHERE('im.ireq_status','RR')
            ->orwhere('im.ireq_status','RA1')
            ->orwhere('im.ireq_status','RA2');
        })
        ->WHERERaw('LOWER(llr.lookup_type) LIKE ? ',[trim(strtolower('ict_status')).'%'])
        ->ORDERBY('im.ireq_date','DESC')
        ->get();
        return view('pdf/Laporan_IctRequest_Reject', compact('ict'));
    }
    function cetak_excel_manager_reject()
    {
        $newCreation = Carbon::parse(Carbon::now())->copy()->tz('Asia/Jakarta')->format('d M Y');
        return Excel::download(new IctExportRejectManager,'ICT REQUEST STATUS REPORT LIST ON '.$newCreation.'.xlsx');
    }
    function cetak_pdf_manager_assignment_request()
    {
        $ict =  DB::table('ireq_mst as im')
        ->SELECT('im.ireq_no','im.ireq_requestor','vr.name as ireq_bu','im.ireq_reason','im.ireq_user','dr.div_name',DB::raw("COALESCE(im.ireq_assigned_to2,im.ireq_assigned_to1) AS ireq_assigned_to"),
                DB::raw("TO_CHAR(im.ireq_date,' dd Mon YYYY') as ireq_date"),'llr.lookup_desc as ireq_status')
        ->LEFTJOIN('vcompany_refs as vr','im.ireq_bu','vr.company_code')
        ->LEFTJOIN('lookup_refs as llr','im.ireq_status','llr.lookup_code')
        ->LEFTJOIN('divisi_refs as dr','im.ireq_divisi_user','dr.div_id')
        ->WHERE(function($query){
            return $query
            ->WHERE('im.ireq_status','NT')
            ->Orwhere('im.ireq_status','RT');
        })
        ->WHERERaw('LOWER(llr.lookup_type) LIKE ? ',[trim(strtolower('ict_status')).'%'])
        ->ORDERBY('im.ireq_date','DESC')
        ->get();
        return view('pdf/Laporan_IctRequest_Sedang_Dikerjakan', compact('ict'));
    }
    function cetak_excel_manager_assignment_request()
    {
        $newCreation = Carbon::parse(Carbon::now())->copy()->tz('Asia/Jakarta')->format('d M Y');
        return Excel::download(new IctExportManagerAssignmentRequest,'ICT REQUEST STATUS REPORT LIST ON '.$newCreation.'.xlsx');
    }
    function cetak_pdf_manager_sedang_dikerjakan()
    {
        $ict =  DB::table('ireq_mst as im')
        ->SELECT('im.ireq_no','im.ireq_requestor','vr.name as ireq_bu','im.ireq_reason','im.ireq_user','dr.div_name',DB::raw("COALESCE(im.ireq_assigned_to2,im.ireq_assigned_to1) AS ireq_assigned_to"),
                DB::raw("TO_CHAR(im.ireq_date,' dd Mon YYYY') as ireq_date"),'llr.lookup_desc as ireq_status')
        ->LEFTJOIN('vcompany_refs as vr','im.ireq_bu','vr.company_code')
        ->LEFTJOIN('lookup_refs as llr','im.ireq_status','llr.lookup_code')
        ->LEFTJOIN('divisi_refs as dr','im.ireq_divisi_user','dr.div_id')
        ->WHERE('im.ireq_status','T')
        ->WHERERaw('LOWER(llr.lookup_type) LIKE ? ',[trim(strtolower('ict_status')).'%'])
        ->ORDERBY('im.ireq_date','DESC')
        ->get();
        return view('pdf/Laporan_IctRequest_Sedang_Dikerjakan', compact('ict'));
    }
    function cetak_excel_manager_sedang_dikerjakan()
    {
        $newCreation = Carbon::parse(Carbon::now())->copy()->tz('Asia/Jakarta')->format('d M Y');
        return Excel::download(new IctExportManagerSedangDikerjakan,'ICT REQUEST STATUS REPORT LIST ON '.$newCreation.'.xlsx');
    }
    function cetak_pdf_manager_sudah_dikerjakan()
    {
        $ict =  DB::table('ireq_dtl as id')
        ->LEFTJOIN('ireq_mst as im','id.ireq_id','im.ireq_id')
        ->LEFTJOIN('vcompany_refs as vr','im.ireq_bu','vr.company_code')
        ->LEFTJOIN('divisi_refs as dr','im.ireq_divisi_user','dr.div_id')
        ->LEFTJOIN('lookup_refs as lr',function ($join) {
            $join->on('id.ireq_status','lr.lookup_code')
                  ->WHERERaw('LOWER(lr.lookup_type) LIKE ? ',[trim(strtolower('ict_status')).'%']);
        })
        ->LEFTJOIN('lookup_refs as lrs',function ($join) {
            $join->on('id.ireq_type','lrs.lookup_code')
                  ->WHERERaw('LOWER(lrs.lookup_type) LIKE ? ',[trim(strtolower('req_type')).'%']);
        })
        ->SELECT('vr.name as ireq_bu','im.ireq_no','id.ireq_id','id.ireq_remark',DB::raw("COALESCE(id.ireq_assigned_to2,id.ireq_assigned_to1) AS ireq_assigned_to"),'id.ireqd_id',
        'lr.lookup_desc as ireq_status','lrs.lookup_desc as ireq_type',DB::raw("(crs.catalog_name ||' - '|| cr.catalog_name) as kategori"),DB::raw("TO_CHAR(im.ireq_date,' dd Mon YYYY') as ireq_date"),'im.ireq_requestor','im.ireq_user',
        'dr.div_name','id.ireq_qty','id.ireq_status as status')
        ->WHERE('id.ireq_status','D')
        ->ORDERBY('im.ireq_date','DESC')
        ->ORDERBY('id.ireqd_id','ASC')
        ->get();
        return view('pdf/Laporan_IctRequest_Sudah_Dikerjakan', compact('ict'));
    }
    function cetak_excel_manager_sudah_dikerjakan()
    {
        $newCreation = Carbon::parse(Carbon::now())->copy()->tz('Asia/Jakarta')->format('d M Y');
        return Excel::download(new IctExportManagerSudahDikerjakan,'ICT REQUEST STATUS REPORT LIST ON '.$newCreation.'.xlsx');
    }
    function cetak_pdf_manager_selesai()
    {
        $ict =  DB::table('ireq_dtl as id')
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
        ->SELECT('im.ireq_no','id.ireq_id','id.ireq_remark',DB::raw("COALESCE(id.ireq_assigned_to2,id.ireq_assigned_to1) AS ireq_assigned_to"),'id.ireqd_id',
        'lr.lookup_desc as ireq_status','lrs.lookup_desc as ireq_type',DB::raw("(crs.catalog_name ||' - '|| cr.catalog_name) as kategori"),DB::raw("TO_CHAR(im.ireq_date,' dd Mon YYYY') as ireq_date"),'im.ireq_requestor','im.ireq_user',
        'dr.div_name','id.ireq_qty','id.ireq_status as status')
        ->WHERE('id.ireq_status','C')
        ->ORDERBY('im.ireq_date','DESC')
        ->ORDERBY('id.ireqd_id','ASC')
        ->get();
        return view('pdf/Laporan_IctRequest_Sudah_Dikerjakan', compact('ict'));
    }
    function cetak_excel_manager_selesai()
    {
        $newCreation = Carbon::parse(Carbon::now())->copy()->tz('Asia/Jakarta')->format('d M Y');
        return Excel::download(new IctExportManagerSudahSelesai,'ICT REQUEST STATUS REPORT LIST ON '.$newCreation.'.xlsx');
    }
}
