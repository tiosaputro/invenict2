<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Mng_User;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\IctExportPersonnelAssignmentRequest;
use App\Exports\IctExportPersonnelReject;
use App\Exports\IctExportPersonnelSedangDikerjakan;
use App\Exports\IctExportPersonnelSudahDikerjakan;
use App\Exports\IctExportPersonnelSelesai;

class IctRequestPersonnelController extends Controller
{
    protected $to;
    protected $userMenu;
    public function __construct(){
        $this->middleware('auth:sanctum');
        $this->to = "/ict-request-personnel";
        $this->middleware(function ($request, $next) {
          $this->userMenu = Mng_User::menu();
            if($this->userMenu->contains($this->to)){    
                return $next($request);
            } else {
                return response(["message"=>"Cannot Access"],403);
            }
        });
    }
    
    function getDataPersonnel()
    {
        $usr_fullname = Auth::user()->usr_fullname;
         $ict = DB::table('ireq_dtl as id')
            ->SELECT(DB::raw('COUNT(imm.ireq_verificator_remark) as countRemarkReviewerInProgress'),'imm.ireq_verificator_remark','id.ireq_attachment','imm.ireq_no','id.ireq_assigned_remark','id.ireq_status as status','id.ireq_id','id.ireq_desc','id.ireq_qty','id.ireq_remark','id.ireqd_id','dr.div_name',
                DB::raw("COALESCE(id.ireq_assigned_to2,id.ireq_assigned_to1) AS ireq_assigned_to"),
                'imm.ireq_user', 'llr.lookup_desc as ireq_status', 'imm.ireq_requestor',
                'vr.name as ireq_bu','lr.lookup_desc as ireq_type','imm.ireq_date',
                DB::raw("(crs.catalog_name ||' - '|| cr.catalog_name) as name"))
                ->leftJoin('catalog_refs as cr',function ($join) {
                    $join->on('id.invent_code','cr.catalog_id');
                })
                ->leftJoin('catalog_refs as crs',function ($join) {
                    $join->on('cr.parent_id','crs.catalog_id');
                })
            ->LEFTJOIN('lookup_refs as lr','id.ireq_type','lr.lookup_code')
            ->LEFTJOIN('ireq_mst as imm','id.ireq_id','imm.ireq_id')        
            ->LEFTJOIN('lookup_refs as llr','id.ireq_status','llr.lookup_code')
            ->LEFTJOIN('vcompany_refs as vr','imm.ireq_bu','vr.company_code')
            ->LEFTJOIN('divisi_refs as dr','imm.ireq_divisi_user','dr.div_id')
            ->WHERE('id.ireq_status','T')
            ->WHERE(DB::raw("COALESCE(id.ireq_assigned_to2,id.ireq_assigned_to1)"),$usr_fullname)
            ->WHERERaw('LOWER(lr.lookup_type) LIKE ? ',[trim(strtolower('req_type')).'%'])
            ->WHERERaw('LOWER(llr.lookup_type) LIKE ? ',[trim(strtolower('ict_status')).'%'])
            ->GROUPBY('id.ireq_attachment','imm.ireq_verificator_remark','imm.ireq_no','id.ireq_assigned_remark','id.ireq_status','id.ireq_id','id.ireq_desc','id.ireq_qty','id.ireq_remark','id.ireqd_id','dr.div_name',
                DB::raw("COALESCE(id.ireq_assigned_to2,id.ireq_assigned_to1)"),
                'imm.ireq_user', 'llr.lookup_desc', 'imm.ireq_requestor',
                'vr.name','lr.lookup_desc','imm.ireq_date',
                DB::raw("(crs.catalog_name ||' - '|| cr.catalog_name)"))
            ->ORDERBY('imm.ireq_date','DESC')
            ->ORDERBY('id.ireqd_id','ASC')
         ->get();

         $ict1 = DB::table('ireq_dtl as id')
            ->SELECT(DB::Raw('COUNT(imm.ireq_verificator_remark) as countRemarkReviewerDone'),'imm.ireq_verificator_remark','id.ireq_attachment','imm.ireq_no','id.ireq_id','id.ireq_assigned_remark','id.ireq_desc','id.ireq_qty','id.ireq_remark','id.ireqd_id','dr.div_name',DB::raw("COALESCE(id.ireq_assigned_to2,id.ireq_assigned_to1) AS ireq_assigned_to"),
                'imm.ireq_user', 'llr.lookup_desc as ireq_status', 'imm.ireq_requestor',
                'vr.name as ireq_bu','lr.lookup_desc as ireq_type','imm.ireq_date',
                DB::raw("(crs.catalog_name ||' - '|| cr.catalog_name) as invent_code"),'id.ireq_status as status')
            ->leftJoin('catalog_refs as cr',function ($join) {
                    $join->on('id.invent_code','cr.catalog_id');
            })
            ->leftJoin('catalog_refs as crs',function ($join) {
                    $join->on('cr.parent_id','crs.catalog_id');
            })
            ->LEFTJOIN('lookup_refs as lr','id.ireq_type','lr.lookup_code')
            ->LEFTJOIN('ireq_mst as imm','id.ireq_id','imm.ireq_id')        
            ->LEFTJOIN('lookup_refs as llr','id.ireq_status','llr.lookup_code')
            ->LEFTJOIN('vcompany_refs as vr','imm.ireq_bu','vr.company_code')
            ->LEFTJOIN('divisi_refs as dr','imm.ireq_divisi_user','dr.div_id')
            ->WHERE('id.ireq_status','D')
            ->WHERE(DB::raw("COALESCE(id.ireq_assigned_to2,id.ireq_assigned_to1)"),$usr_fullname)
            ->WHERERaw('LOWER(lr.lookup_type) LIKE ? ',[trim(strtolower('req_type')).'%'])
            ->WHERERaw('LOWER(llr.lookup_type) LIKE ? ',[trim(strtolower('ict_status')).'%'])
            ->GROUPBY('id.ireq_attachment','imm.ireq_verificator_remark','imm.ireq_no','id.ireq_id','id.ireq_assigned_remark','id.ireq_desc','id.ireq_qty','id.ireq_remark','id.ireqd_id','dr.div_name',DB::raw("COALESCE(id.ireq_assigned_to2,id.ireq_assigned_to1)"),
            'imm.ireq_user', 'llr.lookup_desc', 'imm.ireq_requestor',
            'vr.name','lr.lookup_desc','imm.ireq_date',
            DB::raw("(crs.catalog_name ||' - '|| cr.catalog_name)"),'id.ireq_status')
            ->ORDERBY('imm.ireq_date','DESC')
            ->ORDERBY('id.ireqd_id','ASC')
         ->get();

         $ict2 = DB::table('ireq_dtl as id')
            ->SELECT(DB::RAW('COUNT(imm.ireq_verificator_remark) as countRemarkReviewerClose'),'imm.ireq_verificator_remark','id.ireq_attachment','imm.ireq_no','imm.ireq_id','id.ireq_assigned_remark','id.ireq_desc','id.ireq_qty','id.ireq_remark','id.ireqd_id','dr.div_name',
                'imm.ireq_user', DB::raw("COALESCE(id.ireq_assigned_to2,id.ireq_assigned_to1) AS ireq_assigned_to"),'llr.lookup_desc as ireq_status', 'imm.ireq_requestor',
                'vr.name as ireq_bu','id.ireq_id','lr.lookup_desc as ireq_type','imm.ireq_date','id.ireq_status as status', DB::raw("(crs.catalog_name ||' - '|| cr.catalog_name) as invent_code"))
            ->leftJoin('catalog_refs as cr',function ($join) {
                $join->on('id.invent_code','cr.catalog_id');
            })
            ->leftJoin('catalog_refs as crs',function ($join) {
                $join->on('cr.parent_id','crs.catalog_id');
            })
            ->LEFTJOIN('lookup_refs as lr','id.ireq_type','lr.lookup_code')
            ->LEFTJOIN('ireq_mst as imm','id.ireq_id','imm.ireq_id')        
            ->LEFTJOIN('lookup_refs as llr','id.ireq_status','llr.lookup_code')
            ->LEFTJOIN('vcompany_refs as vr','imm.ireq_bu','vr.company_code')
            ->LEFTJOIN('divisi_refs as dr','imm.ireq_divisi_user','dr.div_id')
            ->WHERE('id.ireq_status','C')
            ->WHERE(DB::raw("COALESCE(id.ireq_assigned_to2,id.ireq_assigned_to1)"),$usr_fullname)
            ->WHERERaw('LOWER(lr.lookup_type) LIKE ? ',[trim(strtolower('req_type')).'%'])
            ->WHERERaw('LOWER(llr.lookup_type) LIKE ? ',[trim(strtolower('ict_status')).'%'])
            ->GROUPBy('id.ireq_attachment','imm.ireq_no','imm.ireq_id','id.ireq_assigned_remark','id.ireq_desc','id.ireq_qty','id.ireq_remark','id.ireqd_id','dr.div_name',
                'imm.ireq_user','imm.ireq_verificator_remark', DB::raw("COALESCE(id.ireq_assigned_to2,id.ireq_assigned_to1)"),'llr.lookup_desc', 'imm.ireq_requestor',
                'vr.name','id.ireq_id','lr.lookup_desc','imm.ireq_date','id.ireq_status', DB::raw("(crs.catalog_name ||' - '|| cr.catalog_name)"))
            ->ORDERBY('imm.ireq_date','DESC')
            ->ORDERBY('id.ireqd_id','ASC')
         ->get();

         $ict3 = DB::table('ireq_mst as im')
            ->LEFTJOIN('divisi_refs as dr','im.ireq_divisi_user','dr.div_id')
            ->RIGHTJOIN('ireq_dtl as id','im.ireq_id','id.ireq_id')
            ->SELECT('im.ireq_date',DB::RAW("COUNT(im.ireq_verificator_remark) as countremarkreviewerpenugasan"),'im.ireq_verificator_remark','im.ireq_no','im.ireq_requestor',
                'im.ireq_user','dr.div_name','im.ireq_status as status','im.ireq_id')
            ->WHERE('im.ireq_status','NT')
            ->WHERE('id.ireq_assigned_to1',$usr_fullname)
            ->groupBy('im.ireq_date','im.ireq_verificator_remark','im.ireq_no','im.ireq_requestor','im.ireq_user','dr.div_name','im.ireq_status','im.ireq_id')
            ->ORDERBY('im.ireq_date','DESC')
        ->get();

        $ict4 = DB::table('ireq_dtl as id')
            ->SELECT('id.ireq_attachment','imm.ireq_no','id.ireq_assigned_to1_reason','imm.ireq_id','id.ireq_assigned_remark','id.ireq_desc','id.ireq_qty','id.ireq_remark','id.ireqd_id','dr.div_name',
                'imm.ireq_user', DB::raw("COALESCE(id.ireq_assigned_to2,id.ireq_assigned_to1) AS ireq_assigned_to"),'llr.lookup_desc as ireq_status', 'imm.ireq_requestor',
                'vr.name as ireq_bu','lr.lookup_desc as ireq_type','imm.ireq_date','id.ireq_status as status', DB::raw("(crs.catalog_name ||' - '|| cr.catalog_name) as invent_code"))
            ->LEFTJOIN('catalog_refs as cr',function ($join) {
                $join->on('id.invent_code','cr.catalog_id');
            })
            ->LEFTJOIN('catalog_refs as crs',function ($join) {
                $join->on('cr.parent_id','crs.catalog_id');
            })
            ->LEFTJOIN('lookup_refs as lr','id.ireq_type','lr.lookup_code')
            ->LEFTJOIN('ireq_mst as imm','id.ireq_id','imm.ireq_id')        
            ->LEFTJOIN('lookup_refs as llr','id.ireq_status','llr.lookup_code')
            ->LEFTJOIN('vcompany_refs as vr','imm.ireq_bu','vr.company_code')
            ->LEFTJOIN('divisi_refs as dr','imm.ireq_divisi_user','dr.div_id')
            ->WHERE('id.ireq_status','RT')
            ->WHERE('id.ireq_assigned_to1',$usr_fullname)
            ->WHERERaw('LOWER(lr.lookup_type) LIKE ? ',[trim(strtolower('req_type')).'%'])
            ->WHERERaw('LOWER(llr.lookup_type) LIKE ? ',[trim(strtolower('ict_status')).'%'])
            ->ORDERBY('imm.ireq_date','DESC')
            ->ORDERBY('id.ireqd_id','ASC')
         ->get();
            return response()->json(['ict'=>$ict,'ict1'=>$ict1,'ict2'=>$ict2,'ict3'=>$ict3,'ict4'=>$ict4]);
    }
    function cetak_pdf_personnel_assignment_request()
    {
        $ict =  DB::table('ireq_mst as im')
        ->SELECT('im.ireq_no','im.ireq_requestor','vr.name as ireq_bu','im.ireq_reason','im.ireq_user','dr.div_name','im.ireq_assigned_to1 AS ireq_assigned_to',
                DB::raw("TO_CHAR(im.ireq_date,' dd Mon YYYY') as ireq_date"),'llr.lookup_desc as ireq_status')
        ->LEFTJOIN('vcompany_refs as vr','im.ireq_bu','vr.company_code')
        ->LEFTJOIN('ireq_dtl as id','im.ireq_id','id.ireq_id')
        ->LEFTJOIN('lookup_refs as llr','im.ireq_status','llr.lookup_code')
        ->LEFTJOIN('divisi_refs as dr','im.ireq_divisi_user','dr.div_id')
        ->WHERE('id.ireq_status','NT')
        ->WHERERaw('LOWER(llr.lookup_type) LIKE ? ',[trim(strtolower('ict_status')).'%'])
        ->WHERE('id.ireq_assigned_to1',Auth::user()->usr_fullname)
        ->ORDERBY('im.ireq_date','DESC')
        ->get();
        return view('pdf/Laporan_IctRequest_Sedang_Dikerjakan', compact('ict'));
    }
    function cetak_excel_personnel_assignment_request()
    {
        $usr_fullname = Auth::user()->usr_fullname;
        $newCreation = Carbon::parse(Carbon::now())->copy()->tz('Asia/Jakarta')->format('d M Y');
        return Excel::download(new IctExportPersonnelAssignmentRequest($usr_fullname),'ICT REQUEST STATUS REPORT LIST ON '.$newCreation.'.xlsx');
    }
    function cetak_pdf_personnel_reject()
    {
        $ict =  DB::table('ireq_dtl as id')
        ->SELECT('imm.ireq_no','id.ireq_assigned_to1_reason','imm.ireq_id','id.ireq_assigned_remark','id.ireq_desc','id.ireq_qty','id.ireq_remark','id.ireqd_id','dr.div_name',
            'imm.ireq_user', DB::raw("COALESCE(id.ireq_assigned_to2,id.ireq_assigned_to1) AS ireq_assigned_to"),'llr.lookup_desc as ireq_status', 'imm.ireq_requestor',
            'vr.name as ireq_bu','lr.lookup_desc as ireq_type','imm.ireq_date','id.ireq_status as status', DB::raw("(crs.catalog_name ||' - '|| cr.catalog_name) as kategori"))
        ->LEFTJOIN('lookup_refs as lr','id.ireq_type','lr.lookup_code')
        ->LEFTJOIN('catalog_refs as cr',function ($join) {
            $join->on('id.invent_code','cr.catalog_id');
        })
        ->LEFTJOIN('catalog_refs as crs',function ($join) {
            $join->on('cr.parent_id','crs.catalog_id');
        })
        ->LEFTJOIN('ireq_mst as imm','id.ireq_id','imm.ireq_id')        
        ->LEFTJOIN('lookup_refs as llr','id.ireq_status','llr.lookup_code')
        ->LEFTJOIN('vcompany_refs as vr','imm.ireq_bu','vr.company_code')
        ->LEFTJOIN('divisi_refs as dr','imm.ireq_divisi_user','dr.div_id')
        ->WHERE('id.ireq_status','RT')
        ->WHERE('id.ireq_assigned_to1',Auth::user()->usr_fullname)
        ->WHERERaw('LOWER(lr.lookup_type) LIKE ? ',[trim(strtolower('req_type')).'%'])
        ->WHERERaw('LOWER(llr.lookup_type) LIKE ? ',[trim(strtolower('ict_status')).'%'])
        ->ORDERBY('imm.ireq_date','DESC')
        ->ORDERBY('id.ireqd_id','ASC')
     ->get();
        return view('pdf/Laporan_IctDetailReject', compact('ict'));
    }
    function cetak_excel_personnel_reject()
    {
        $usr_fullname = Auth::user()->usr_fullname;
        $newCreation = Carbon::parse(Carbon::now())->copy()->tz('Asia/Jakarta')->format('d M Y');
        return Excel::download(new IctExportPersonnelReject($usr_fullname),'ICT REQUEST STATUS REPORT LIST ON '.$newCreation.'.xlsx');
    }
    function cetak_pdf_personnel_sedang_dikerjakan()
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
        ->LEFTJOIN('catalog_refs as cr',function ($join) {
            $join->on('id.invent_code','cr.catalog_id');
        })
        ->LEFTJOIN('catalog_refs as crs',function ($join) {
            $join->on('cr.parent_id','crs.catalog_id');
        })
        ->SELECT('vr.name as ireq_bu','im.ireq_no','id.ireq_id','id.ireq_remark',DB::raw("COALESCE(id.ireq_assigned_to2,id.ireq_assigned_to1) AS ireq_assigned_to"),'id.ireqd_id',
        'lr.lookup_desc as ireq_status','lrs.lookup_desc as ireq_type',DB::raw("(crs.catalog_name ||' - '|| cr.catalog_name) as kategori"),DB::raw("TO_CHAR(im.ireq_date,' dd Mon YYYY') as ireq_date"),'im.ireq_requestor','im.ireq_user',
        'dr.div_name','id.ireq_qty','id.ireq_status as status')
        ->WHERE('id.ireq_status','T')
        ->WHERE(DB::raw("COALESCE(id.ireq_assigned_to2,id.ireq_assigned_to1)"),Auth::user()->usr_fullname)
        ->ORDERBY('im.ireq_date','DESC')
        ->ORDERBY('id.ireqd_id','ASC')
        ->get();
        return view('pdf/Laporan_IctRequest_Sudah_Dikerjakan', compact('ict'));
    }
    public function cetak_excel_personnel_sedang_dikerjakan()
    {
        $usr_fullname = Auth::user()->usr_fullname;
        $newCreation = Carbon::parse(Carbon::now())->copy()->tz('Asia/Jakarta')->format('d M Y');
        return Excel::download(new IctExportPersonnelSedangDikerjakan($usr_fullname),'ICT REQUEST STATUS REPORT LIST ON '.$newCreation.'.xlsx');
    }
    Public function cetak_pdf_personnel_sudah_dikerjakan()
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
        ->WHERE(DB::raw("COALESCE(id.ireq_assigned_to2,id.ireq_assigned_to1)"),Auth::user()->usr_fullname)
        ->ORDERBY('im.ireq_date','DESC')
        ->ORDERBY('id.ireqd_id','ASC')
        ->get();
        return view('pdf/Laporan_IctRequest_Sudah_Dikerjakan', compact('ict'));
    }
    public function cetak_excel_personnel_sudah_dikerjakan()
    {
        $usr_fullname = AUth::user()->usr_fullname;
        $newCreation = Carbon::parse(Carbon::now())->copy()->tz('Asia/Jakarta')->format('d M Y');
        return Excel::download(new IctExportPersonnelSudahDikerjakan($usr_fullname),'ICT REQUEST STATUS REPORT LIST ON '.$newCreation.'.xlsx');
    }
    Public function cetak_pdf_personnel_selesai()
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
        ->WHERE(DB::raw("COALESCE(id.ireq_assigned_to2,id.ireq_assigned_to1)"),Auth::user()->usr_fullname)
        ->ORDERBY('im.ireq_date','DESC')
        ->ORDERBY('id.ireqd_id','ASC')
        ->get();
        return view('pdf/Laporan_IctRequest_Selesai', compact('ict'));
    }
    public function cetak_excel_personnel_selesai()
    {
        $usr_fullname = Auth::user()->usr_fullname;
        $newCreation = Carbon::parse(Carbon::now())->copy()->tz('Asia/Jakarta')->format('d M Y');
        return Excel::download(new IctExportPersonnelSelesai($usr_fullname),'ICT REQUEST STATUS REPORT LIST ON '.$newCreation.'.xlsx');
    }
}
