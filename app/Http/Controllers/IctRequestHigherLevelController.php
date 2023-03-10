<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use App\Model\Ict;
use App\Model\IctDetail;
use App\Helpers\ResponseFormatter;
use App\Mng_User;
use App\Exports\IctExportPermohonanAtasan;
use App\Exports\IctExportVerifikasiAtasan;
use App\Exports\IctExportRejectAtasan;
use App\Exports\IctExportAtasanAssignmentRequest;
use App\Exports\IctExportAtasanSedangDikerjakan;
use App\Exports\IctExportAtasanSudahDikerjakan;
use App\Exports\IctExportAtasanSelesai;
class IctRequestHigherLevelController extends Controller
{
    protected $to;
    protected $userMenu;
    public function __construct(){
        $this->middleware('auth:sanctum');
        $this->to = "/ict-request-higher-level";
        $this->middleware(function ($request, $next) {
          $this->userMenu = Mng_User::menu();
            if($this->userMenu->contains($this->to)){    
                return $next($request);
            } else {
                return response(["message"=>"Cannot Access"],403);
            }
        });
    }
    function approveByAtasan($code)
    {
        $save = Ict::ApprovedByAtasan($code);
        IctDetail::ApprovedByAtasan($code);
        return ResponseFormatter::success($save,'Successfully approved Request');
    }
    function rejectByAtasan(Request $request, $code)
    {
        $save = Ict::RejectedByAtasan($request,$code);
        IctDetail::RejectedByAtasan($request,$code);

        return ResponseFormatter::success($save,'Successfully rejected Request');
    }
    function getPermohonan()
    {
        $usr_email = Auth::user()->usr_email;
            $ict = DB::table('ireq_mst as im')
                ->SELECT('im.ireq_id','im.ireq_no','im.ireq_date','im.ireq_status as ireq_statuss','im.ireq_user',
                'im.ireq_requestor','lr.lookup_desc as ireq_status')
                ->LEFTJOIN('divisi_refs as dr','im.ireq_divisi_user','dr.div_id')
                ->LEFTJOIN('lookup_refs as lr','im.ireq_status','lr.lookup_code')
                ->WHERE('dr.div_verificator',$usr_email)
                ->WHERERaw('LOWER(lr.lookup_type) LIKE ? ',[trim(strtolower('ict_status')).'%'])
                ->WHERE(function($query){
                    return $query
                    ->WHERE('im.ireq_status','NA1')
                    ->orwhere('im.ireq_status','NA2')
                    ->orwhere('im.ireq_status','P');
                })
                ->groupBy('im.ireq_id','im.ireq_no','im.ireq_date','im.ireq_status','im.ireq_user','im.creation_date','im.ireq_requestor','lr.lookup_desc')
                ->ORDERBY('im.ireq_date','DESC')
                ->get();
            
            $ict1 = DB::table('ireq_mst as im')
                ->SELECT('im.ireq_id','im.ireq_no','im.ireq_date','im.ireq_user','im.ireq_status as status',
                'im.ireq_requestor','lr.lookup_desc as ireq_status')
                ->LEFTJOIN('divisi_refs as dr','im.ireq_divisi_user','dr.div_id')
                ->LEFTJOIN('lookup_refs as lr','im.ireq_status','lr.lookup_code')
                ->WHERE('dr.div_verificator',$usr_email)
                ->WHERERaw('LOWER(lr.lookup_type) LIKE ? ',[trim(strtolower('ict_status')).'%'])
                ->WHERE(function($query){
                    return $query
                    ->WHERE('im.ireq_status','A1')
                    ->orwhere('im.ireq_status','A2');
                    })
                ->ORDERBY('im.ireq_date','DESC')
                ->get();

            $ict2 = DB::table('ireq_mst as im')
            ->SELECT('im.ireq_id','im.ireq_no','im.ireq_date','im.ireq_user','im.ireq_status as status',
            'im.ireq_requestor','im.ireq_reason','lr.lookup_desc as ireq_status')
            ->LEFTJOIN('divisi_refs as dr','im.ireq_divisi_user','dr.div_id')
            ->LEFTJOIN('lookup_refs as lr','im.ireq_status','lr.lookup_code')
            ->WHERE('dr.div_verificator',$usr_email)
            ->WHERERaw('LOWER(lr.lookup_type) LIKE ? ',[trim(strtolower('ict_status')).'%'])
            ->WHERE(function($query){
                return $query
                ->WHERE('im.ireq_status','RA1')
                ->orwhere('im.ireq_status','RA2')
                ->orwhere('im.ireq_status','RR');
                })
            ->ORDERBY('im.ireq_date','DESC')
            ->get();

            $ict3 = DB::table('ireq_mst as im')
            ->SELECT('im.ireq_id','im.ireq_no','im.ireq_date','im.ireq_user','im.ireq_requestor','im.ireq_status as status','lr.lookup_desc as ireq_status',
            DB::raw("COALESCE(im.ireq_assigned_to2,im.ireq_assigned_to1) AS ireq_assigned_to"))
            ->LEFTJOIN('divisi_refs as dr','im.ireq_divisi_user','dr.div_id')
            ->LEFTJOIN('lookup_refs as lr','im.ireq_status','lr.lookup_code')
            ->WHERE('dr.div_verificator',$usr_email)
            ->WHERERaw('LOWER(lr.lookup_type) LIKE ? ',[trim(strtolower('ict_status')).'%'])
            ->WHERE('im.ireq_status','T')
            ->groupBy('im.ireq_id','im.ireq_no','im.ireq_date','lr.lookup_desc','im.ireq_user','im.ireq_status','im.creation_date','im.ireq_requestor',DB::raw("COALESCE(im.ireq_assigned_to2,im.ireq_assigned_to1)"))
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
            ->leftJoin('catalog_refs as cr',function ($join) {
                $join->on('id.invent_code','cr.catalog_id');
            })
            ->leftJoin('catalog_refs as crs',function ($join) {
                $join->on('cr.parent_id','crs.catalog_id');
            })
            ->SELECT('id.ireq_attachment','im.ireq_no','id.ireq_id','id.ireq_remark',DB::raw("COALESCE(id.ireq_assigned_to2,id.ireq_assigned_to1) AS ireq_assigned_to"),'id.ireqd_id',
            'lr.lookup_desc as ireq_status','lrs.lookup_desc as ireq_type',DB::raw("(crs.catalog_name ||' - '|| cr.catalog_name) as kategori"),'im.ireq_date','im.ireq_requestor','im.ireq_user',
            'dr.div_name','id.ireq_qty','id.ireq_status as status')
            ->WHERE('id.ireq_status','D')
            ->WHERE('dr.div_verificator',$usr_email)
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
            ->leftJoin('catalog_refs as cr',function ($join) {
                $join->on('id.invent_code','cr.catalog_id');
            })
            ->leftJoin('catalog_refs as crs',function ($join) {
                $join->on('cr.parent_id','crs.catalog_id');
            })
            ->SELECT('id.ireq_attachment','im.ireq_no','id.ireq_id','id.ireq_remark',DB::raw("COALESCE(id.ireq_assigned_to2,id.ireq_assigned_to1) AS ireq_assigned_to"),'id.ireqd_id',
            'lr.lookup_desc as ireq_status','lrs.lookup_desc as ireq_type',DB::raw("(crs.catalog_name ||' - '|| cr.catalog_name) as kategori"),'im.ireq_date','im.ireq_requestor','im.ireq_user',
            'dr.div_name','id.ireq_qty','id.ireq_status as status')
            ->WHERE('id.ireq_status','C')
            ->WHERE('dr.div_verificator',$usr_email)
            ->ORDERBY('im.ireq_date','DESC')
            ->ORDERBY('id.ireqd_id','ASC')
            ->get();

            $ict6 = DB::table('ireq_mst as id')
                ->SELECT('id.ireq_id','id.ireq_no','id.ireq_user','id.ireq_date','dr.div_name','id.ireq_requestor','id.ireq_status as status','lr.lookup_desc as ireq_status')
                ->LEFTJOIN('divisi_refs as dr','id.ireq_divisi_user','dr.div_id')
                ->LEFTJOIN('lookup_refs as lr','id.ireq_status','lr.lookup_code')
                ->WHERE('dr.div_verificator',$usr_email)
                ->WHERERaw('LOWER(lr.lookup_type) LIKE ? ',[trim(strtolower('ict_status')).'%'])
                ->WHERENotNull('id.ireq_status')
                ->ORDERBY('id.ireq_date','DESC')
                ->get();

            $ict7 = DB::table('ireq_mst as im')
            ->SELECT('im.ireq_status as status','im.ireq_id','im.ireq_no','im.ireq_date','im.ireq_status as ireq_statuss','im.ireq_user',
            'im.ireq_requestor','lr.lookup_desc as ireq_status')
            ->LEFTJOIN('divisi_refs as dr','im.ireq_divisi_user','dr.div_id')
            ->LEFTJOIN('lookup_refs as lr','im.ireq_status','lr.lookup_code')
            ->WHERE('dr.div_verificator',$usr_email)
            ->WHERERaw('LOWER(lr.lookup_type) LIKE ? ',[trim(strtolower('ict_status')).'%'])
            ->WHERE(function($query){
                return $query
                ->WHERE('im.ireq_status','NA1')
                ->orwhere('im.ireq_status','NA2');
            })
            ->groupBy('im.ireq_status','im.ireq_id','im.ireq_no','im.ireq_date','im.ireq_status','im.ireq_user','im.creation_date','im.ireq_requestor','lr.lookup_desc')
            ->ORDERBY('im.ireq_date','DESC')
            ->get();

            $ict8 = DB::table('ireq_mst as im')
            ->SELECT('im.ireq_id','im.ireq_no','im.ireq_date','im.ireq_status as ireq_statuss','im.ireq_user',
            'im.ireq_requestor','lr.lookup_desc as ireq_status')
            ->LEFTJOIN('divisi_refs as dr','im.ireq_divisi_user','dr.div_id')
            ->LEFTJOIN('lookup_refs as lr','im.ireq_status','lr.lookup_code')
            ->WHERE('dr.div_verificator',$usr_email)
            ->WHERERaw('LOWER(lr.lookup_type) LIKE ? ',[trim(strtolower('ict_status')).'%'])
            ->WHERE('im.ireq_status','P')
            ->groupBy('im.ireq_id','im.ireq_no','im.ireq_date','im.ireq_status','im.ireq_user','im.creation_date','im.ireq_requestor','lr.lookup_desc')
            ->ORDERBY('im.ireq_date','DESC')
            ->get();

            $ict9 = DB::table('ireq_mst as im')
            ->SELECT('im.ireq_status as status','im.ireq_id','im.ireq_no','im.ireq_date','im.ireq_user','im.ireq_requestor','lr.lookup_desc as ireq_status',DB::raw("COALESCE(im.ireq_assigned_to2,im.ireq_assigned_to1) AS ireq_assigned_to"))
            ->LEFTJOIN('divisi_refs as dr','im.ireq_divisi_user','dr.div_id')
            ->LEFTJOIN('lookup_refs as lr','im.ireq_status','lr.lookup_code')
            ->WHERE('dr.div_verificator',$usr_email)
            ->WHERERaw('LOWER(lr.lookup_type) LIKE ? ',[trim(strtolower('ict_status')).'%'])
            ->WHERE(function($query){
                return $query
                ->WHERE('im.ireq_status','NT')
                ->orwhere('im.ireq_status','RT');
            })
            ->groupBy('im.ireq_status','im.ireq_id','im.ireq_no','im.ireq_date','lr.lookup_desc','im.ireq_user','im.creation_date','im.ireq_requestor',DB::raw("COALESCE(im.ireq_assigned_to2,im.ireq_assigned_to1)"))
            ->ORDERBY('im.ireq_date','DESC')
            ->get();


            return response()->json(['ict'=>$ict,'ict1'=>$ict1,'ict2'=>$ict2,'ict3'=>$ict3,'ict4'=>$ict4,'ict5'=>$ict5,'ict6'=>$ict6,'ict7'=>$ict7,'ict8'=>$ict8,'ict9'=>$ict9]);
    }
    function detailRequest($code){
        $data = IctDetail::getDataDetailRequest($code);
        return ResponseFormatter::success($data,'Successfully Get Data Detail Request'); 
    }
    function getDetailVerif($code)
    {
        $data = IctDetail::detailVerification($code);
        return response()->json($data);
    }
    function cetak_pdf_atasan_permohonan()
    {
        $ict =  DB::table('ireq_mst as im')
        ->SELECT('dr.div_name','im.ireq_id','im.ireq_no',DB::raw("TO_CHAR(im.ireq_date,' dd Mon YYYY') as ireq_date"),'im.ireq_status as ireq_statuss','im.ireq_user',
        'im.ireq_requestor','lr.lookup_desc as ireq_status','vr.name as ireq_bu')
        ->LEFTJOIN('vcompany_refs as vr','im.ireq_bu','vr.company_code')
        ->LEFTJOIN('divisi_refs as dr','im.ireq_divisi_user','dr.div_id')
        ->LEFTJOIN('lookup_refs as lr','im.ireq_status','lr.lookup_code')
        ->WHERE('dr.div_verificator',Auth::user()->usr_email)
        ->WHERERaw('LOWER(lr.lookup_type) LIKE ? ',[trim(strtolower('ict_status')).'%'])
        ->WHERE(function($query){
            return $query
            ->WHERE('im.ireq_status','NA1')
            ->orwhere('im.ireq_status','NA2')
            ->orwhere('im.ireq_status','P');
        })
        ->groupBy('dr.div_name','vr.name','im.ireq_id','im.ireq_no','im.ireq_date','im.ireq_status','im.ireq_user','im.creation_date','im.ireq_requestor','lr.lookup_desc')
        ->ORDERBY('im.ireq_date','DESC')
        ->get();
        return view('pdf/Laporan_IctRequest_Permohonan', compact('ict'));
    }
    function cetak_excel_atasan_permohonan()
    {
        $usr_email = Auth::user()->usrl_email;
        $newCreation = Carbon::parse(Carbon::now())->copy()->tz('Asia/Jakarta')->format('d M Y');
        return Excel::download(new IctExportPermohonanAtasan($usr_email),'ICT REQUEST STATUS REPORT LIST ON '.$newCreation.'.xlsx');
    }
    function cetak_pdf_atasan_verifikasi()
    {
        $ict =  DB::table('ireq_mst as im')
        ->SELECT('dr.div_name','im.ireq_id','im.ireq_no',DB::raw("TO_CHAR(im.ireq_date,' dd Mon YYYY') as ireq_date"),'im.ireq_status as ireq_statuss','im.ireq_user',
        'im.ireq_requestor','lr.lookup_desc as ireq_status','vr.name as ireq_bu')
        ->LEFTJOIN('vcompany_refs as vr','im.ireq_bu','vr.company_code')
        ->LEFTJOIN('divisi_refs as dr','im.ireq_divisi_user','dr.div_id')
        ->LEFTJOIN('lookup_refs as lr','im.ireq_status','lr.lookup_code')
        ->WHERE('dr.div_verificator',Auth::user()->usr_email)
        ->WHERERaw('LOWER(lr.lookup_type) LIKE ? ',[trim(strtolower('ict_status')).'%'])
        ->WHERE(function($query){
            return $query
            ->WHERE('im.ireq_status','A1')
            ->orwhere('im.ireq_status','A2');
        })
        ->groupBy('dr.div_name','vr.name','im.ireq_id','im.ireq_no','im.ireq_date','im.ireq_status','im.ireq_user','im.creation_date','im.ireq_requestor','lr.lookup_desc')
        ->ORDERBY('im.ireq_date','DESC')
        ->get();
        return view('pdf/Laporan_IctRequest_Permohonan', compact('ict'));
    }
    function cetak_excel_atasan_verifikasi()
    {
        $usr_email = Auth::user()->usr_email;
        $newCreation = Carbon::parse(Carbon::now())->copy()->tz('Asia/Jakarta')->format('d M Y');
        return Excel::download(new IctExportVerifikasiAtasan($usr_email),'ICT REQUEST STATUS REPORT LIST ON '.$newCreation.'.xlsx');
    }
    function cetak_pdf_atasan_reject()
    {
        $ict =  DB::table('ireq_mst as im')
        ->SELECT('im.ireq_reason','dr.div_name','im.ireq_id','im.ireq_no',DB::raw("TO_CHAR(im.ireq_date,' dd Mon YYYY') as ireq_date"),'im.ireq_status as ireq_statuss','im.ireq_user',
        'im.ireq_requestor','lr.lookup_desc as ireq_status','vr.name as ireq_bu')
        ->LEFTJOIN('vcompany_refs as vr','im.ireq_bu','vr.company_code')
        ->LEFTJOIN('divisi_refs as dr','im.ireq_divisi_user','dr.div_id')
        ->LEFTJOIN('lookup_refs as lr','im.ireq_status','lr.lookup_code')
        ->WHERE('dr.div_verificator',Auth::user()->usr_email)
        ->WHERERaw('LOWER(lr.lookup_type) LIKE ? ',[trim(strtolower('ict_status')).'%'])
        ->WHERE(function($query){
            return $query
            ->WHERE('im.ireq_status','RA1')
            ->orwhere('im.ireq_status','RA2')
            ->orwhere('im.ireq_status','RR');
        })
        ->groupBy('im.ireq_reason','dr.div_name','vr.name','im.ireq_id','im.ireq_no','im.ireq_date','im.ireq_status','im.ireq_user','im.creation_date','im.ireq_requestor','lr.lookup_desc')
        ->ORDERBY('im.ireq_date','DESC')
        ->get();
        return view('pdf/Laporan_IctRequest_Reject', compact('ict'));
    }
    function cetak_excel_atasan_reject()
    {
        $usr_email = Auth::user()->usr_email;
        $newCreation = Carbon::parse(Carbon::now())->copy()->tz('Asia/Jakarta')->format('d M Y');
        return Excel::download(new IctExportRejectAtasan($usr_email),'ICT REQUEST STATUS REPORT LIST ON '.$newCreation.'.xlsx');
    }
    function cetak_pdf_atasan_assignment_request()
    {
        $ict =  DB::table('ireq_mst as im')
        ->SELECT('dr.div_name','im.ireq_id','im.ireq_no',DB::raw("TO_CHAR(im.ireq_date,' dd Mon YYYY') as ireq_date"),'im.ireq_status as ireq_statuss','im.ireq_user',
        'im.ireq_requestor','lr.lookup_desc as ireq_status','vr.name as ireq_bu',DB::raw("COALESCE(im.ireq_assigned_to2,im.ireq_assigned_to1) AS ireq_assigned_to"))
        ->LEFTJOIN('vcompany_refs as vr','im.ireq_bu','vr.company_code')
        ->LEFTJOIN('divisi_refs as dr','im.ireq_divisi_user','dr.div_id')
        ->LEFTJOIN('lookup_refs as lr','im.ireq_status','lr.lookup_code')
        ->WHERE('dr.div_verificator',Auth::user()->usr_email)
        ->WHERERaw('LOWER(lr.lookup_type) LIKE ? ',[trim(strtolower('ict_status')).'%'])
        ->WHERE(function($query){
            return $query
            ->WHERE('im.ireq_status','NT')
            ->orwhere('im.ireq_status','RT');
        })
        ->groupBy(DB::raw("COALESCE(im.ireq_assigned_to2,im.ireq_assigned_to1)"),'dr.div_name','vr.name','im.ireq_id','im.ireq_no','im.ireq_date','im.ireq_status','im.ireq_user','im.creation_date','im.ireq_requestor','lr.lookup_desc')
        ->ORDERBY('im.ireq_date','DESC')
        ->get();
        return view('pdf/Laporan_IctRequest_Sedang_Dikerjakan', compact('ict'));
    }
    function cetak_excel_atasan_assignment_request()
    {
        $usr_email = Auth::user()->usr_email;
        $newCreation = Carbon::parse(Carbon::now())->copy()->tz('Asia/Jakarta')->format('d M Y');
        return Excel::download(new IctExportAtasanAssignmentRequest($usr_email),'ICT REQUEST STATUS REPORT LIST ON '.$newCreation.'.xlsx');
    }
    function cetak_pdf_atasan_sedang_dikerjakan()
    {
        $ict =  DB::table('ireq_mst as im')
        ->SELECT('dr.div_name','im.ireq_id','im.ireq_no',DB::raw("TO_CHAR(im.ireq_date,' dd Mon YYYY') as ireq_date"),'im.ireq_status as ireq_statuss','im.ireq_user',
        'im.ireq_requestor','lr.lookup_desc as ireq_status','vr.name as ireq_bu',DB::raw("COALESCE(im.ireq_assigned_to2,im.ireq_assigned_to1) AS ireq_assigned_to"))
        ->LEFTJOIN('vcompany_refs as vr','im.ireq_bu','vr.company_code')
        ->LEFTJOIN('divisi_refs as dr','im.ireq_divisi_user','dr.div_id')
        ->LEFTJOIN('lookup_refs as lr','im.ireq_status','lr.lookup_code')
        ->WHERE('dr.div_verificator',Auth::user()->usr_email)
        ->WHERERaw('LOWER(lr.lookup_type) LIKE ? ',[trim(strtolower('ict_status')).'%'])
        ->WHERE('im.ireq_status','T')
        ->groupBy(DB::raw("COALESCE(im.ireq_assigned_to2,im.ireq_assigned_to1)"),'dr.div_name','vr.name','im.ireq_id','im.ireq_no','im.ireq_date','im.ireq_status','im.ireq_user','im.creation_date','im.ireq_requestor','lr.lookup_desc')
        ->ORDERBY('im.ireq_date','DESC')
        ->get();
        return view('pdf/Laporan_IctRequest_Sedang_Dikerjakan', compact('ict'));
    }
    function cetak_excel_atasan_sedang_dikerjakan()
    {
        $usr_email = Auth::user()->usr_email;
        $newCreation = Carbon::parse(Carbon::now())->copy()->tz('Asia/Jakarta')->format('d M Y');
        return Excel::download(new IctExportAtasanSedangDikerjakan($usr_email),'ICT REQUEST STATUS REPORT LIST ON '.$newCreation.'.xlsx');
    }
    function cetak_pdf_atasan_sudah_dikerjakan()
    {
        $ict =  DB::table('ireq_dtl as id')
        ->LEFTJOIN('ireq_mst as im','id.ireq_id','im.ireq_id')
        ->LEFTJOIN('vcompany_refs as vr','im.ireq_bu','vr.company_code')
        ->LEFTJOIN('divisi_refs as dr','im.ireq_divisi_user','dr.div_id')
        ->LEFTJOIN('lookup_refs as lr',function ($join) {
            $join->on('id.ireq_status','lr.lookup_code')
                  ->WHERERaw('LOWER(lr.lookup_type) LIKE ? ',[trim(strtolower('ict_status')).'%']);
        })
        ->LEFTJOIN('catalog_refs as cr',function ($join) {
            $join->on('id.invent_code','cr.catalog_id');
        })
        ->LEFTJOIN('catalog_refs as crs',function ($join) {
            $join->on('cr.parent_id','crs.catalog_id');
        })
        ->SELECT('vr.name as ireq_bu','im.ireq_no','id.ireq_id','id.ireq_remark',DB::raw("COALESCE(id.ireq_assigned_to2,id.ireq_assigned_to1) AS ireq_assigned_to"),'id.ireqd_id',
        'lr.lookup_desc as ireq_status','lrs.lookup_desc as ireq_type',DB::raw("(crs.catalog_name ||' - '|| cr.catalog_name) as kategori"),DB::raw("TO_CHAR(im.ireq_date,' dd Mon YYYY') as ireq_date"),'im.ireq_requestor','im.ireq_user',
        'dr.div_name','id.ireq_qty','id.ireq_status as status')
        ->WHERE('id.ireq_status','D')
        ->WHERE('dr.div_verificator', Auth::user()->usr_email)
        ->ORDERBY('im.ireq_date','DESC')
        ->ORDERBY('id.ireqd_id','ASC')
        ->get();
        return view('pdf/Laporan_IctRequest_Sudah_Dikerjakan', compact('ict'));
    }
    function cetak_excel_atasan_sudah_dikerjakan()
    {
        $usr_email = Auth::user()->usr_email;
        $newCreation = Carbon::parse(Carbon::now())->copy()->tz('Asia/Jakarta')->format('d M Y');
        return Excel::download(new IctExportAtasanSudahDikerjakan($usr_email),'ICT REQUEST STATUS REPORT LIST ON '.$newCreation.'.xlsx');
    }
    function cetak_pdf_atasan_selesai()
    {
        $ict =  DB::table('ireq_dtl as id')
        ->LEFTJOIN('ireq_mst as im','id.ireq_id','im.ireq_id')
        ->LEFTJOIN('vcompany_refs as vr','im.ireq_bu','vr.company_code')
        ->LEFTJOIN('divisi_refs as dr','im.ireq_divisi_user','dr.div_id')
        ->LEFTJOIN('lookup_refs as lr',function ($join) {
            $join->on('id.ireq_status','lr.lookup_code')
                  ->WHERERaw('LOWER(lr.lookup_type) LIKE ? ',[trim(strtolower('ict_status')).'%']);
        })
        ->LEFTJOIN('catalog_refs as cr',function ($join) {
            $join->on('id.invent_code','cr.catalog_id');
        })
        ->LEFTJOIN('catalog_refs as crs',function ($join) {
            $join->on('cr.parent_id','crs.catalog_id');
        })
        ->SELECT('vr.name as ireq_bu','im.ireq_no','id.ireq_id','id.ireq_remark',DB::raw("COALESCE(id.ireq_assigned_to2,id.ireq_assigned_to1) AS ireq_assigned_to"),'id.ireqd_id',
        'lr.lookup_desc as ireq_status','lrs.lookup_desc as ireq_type',DB::raw("(crs.catalog_name ||' - '|| cr.catalog_name) as kategori"),DB::raw("TO_CHAR(im.ireq_date,' dd Mon YYYY') as ireq_date"),'im.ireq_requestor','im.ireq_user',
        'dr.div_name','id.ireq_qty','id.ireq_status as status')
        ->WHERE('id.ireq_status','C')
        ->WHERE('dr.div_verificator', Auth::user()->usr_email)
        ->ORDERBY('im.ireq_date','DESC')
        ->ORDERBY('id.ireqd_id','ASC')
        ->get();
        return view('pdf/Laporan_IctRequest_Selesai', compact('ict'));
    }
    function cetak_excel_atasan_selesai()
    {
        $usr_email = Auth::user()->usr_email;
        $newCreation = Carbon::parse(Carbon::now())->copy()->tz('Asia/Jakarta')->format('d M Y');
        return Excel::download(new IctExportAtasanSelesai($usr_email),'ICT REQUEST STATUS REPORT LIST ON '.$newCreation.'.xlsx');
    }
}
