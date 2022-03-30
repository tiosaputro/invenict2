<?php

namespace App\Http\Controllers;
use DB;
use Illuminate\Http\Request;
use Appstract\Opcache\OpcacheFacade as OPcache;

class DashboardController extends Controller
{
    public function countUser($usr_name)
    {
        $grafik = DB::table('ireq_mst')
        ->select(DB::raw("(SELECT COUNT(ireq_id) FROM ireq_mst WHERE ireq_mst.ireq_status IN ('P','NA1','NA2') AND ireq_mst.created_by = '$usr_name') as belumdiverifikasi"),
                 DB::raw("(SELECT COUNT(ireq_id) FROM ireq_mst WHERE ireq_mst.created_by = '$usr_name' AND ireq_mst.ireq_status IN ('A1','A2')) as sudahdiverifikasi"),
                 DB::raw("(SELECT COUNT(ireq_id) FROM ireq_mst WHERE ireq_mst.ireq_status IN ('RR', 'RA1','RA2') AND ireq_mst.created_by = '$usr_name') as direject"),
                 DB::raw("(SELECT COUNT(ireq_id) FROM ireq_mst WHERE ireq_mst.ireq_status = 'T' AND ireq_mst.created_by = '$usr_name') as sedangdikerjakan"),
                 DB::raw("(SELECT COUNT(ireq_id) FROM ireq_mst WHERE ireq_mst.ireq_status = 'D' AND ireq_mst.created_by = '$usr_name') as sudahdikerjakan"),
                 DB::raw("(SELECT COUNT(ireq_id) FROM ireq_mst WHERE ireq_mst.ireq_status = 'C' AND ireq_mst.created_by = '$usr_name') as sudahselesai"),
                 DB::raw("(SELECT COUNT(ireq_id) FROM ireq_mst WHERE ireq_mst.ireq_status IS NOT NULL AND ireq_mst.created_by = '$usr_name') as countrequest"))
        ->first();
        return response()->json($grafik);
    }
    public function countDivisi1($usr_name)
    {
        $grafik = DB::table('ireq_mst as im')
        ->select(DB::raw("(SELECT COUNT(ireq_id) FROM ireq_mst LEFT JOIN divisi_refs ON ireq_mst.ireq_divisi_user = divisi_refs.div_id WHERE ireq_mst.ireq_status IN ('NA1','NA2') AND divisi_refs.div_verificator = '$usr_name') as belumdiverifikasi"),
                 DB::raw("(SELECT COUNT(ireq_id) FROM ireq_mst LEFT JOIN divisi_refs ON ireq_mst.ireq_divisi_user = divisi_refs.div_id WHERE ireq_mst.ireq_status IN ('A1','A2') AND divisi_refs.div_verificator = '$usr_name') as sudahdiverifikasi"),
                 DB::raw("(SELECT COUNT(ireq_id) FROM ireq_mst LEFT JOIN divisi_refs ON ireq_mst.ireq_divisi_user = divisi_refs.div_id WHERE ireq_mst.ireq_status IN ('RA1','RR','RA2') AND divisi_refs.div_verificator = '$usr_name') as direject"),
                 DB::raw("(SELECT COUNT(ireq_id) FROM ireq_mst LEFT JOIN divisi_refs ON ireq_mst.ireq_divisi_user = divisi_refs.div_id WHERE ireq_mst.ireq_status = 'T' AND divisi_refs.div_verificator = '$usr_name') as sedangdikerjakan"),
                 DB::raw("(SELECT COUNT(ireq_id) FROM ireq_mst LEFT JOIN divisi_refs ON ireq_mst.ireq_divisi_user = divisi_refs.div_id WHERE ireq_mst.ireq_status = 'D' AND divisi_refs.div_verificator = '$usr_name') as sudahdikerjakan"),
                 DB::raw("(SELECT COUNT(ireq_id) FROM ireq_mst LEFT JOIN divisi_refs ON ireq_mst.ireq_divisi_user = divisi_refs.div_id WHERE ireq_mst.ireq_status = 'C' AND divisi_refs.div_verificator = '$usr_name') as sudahselesai"),
                 DB::raw("(SELECT COUNT(ireq_id) FROM ireq_mst LEFT JOIN divisi_refs ON ireq_mst.ireq_divisi_user = divisi_refs.div_id WHERE ireq_mst.ireq_status IS NOT NULL AND divisi_refs.div_verificator = '$usr_name') as total"))
        ->first();
        return response()->json($grafik);
    }
    public function countDivisi2()
    {
        $blmdiverifikasi = DB::table('ireq_mst')
            ->select(DB::raw('count(ireq_id) as blmdiverifikasi'))
            ->where('ireq_status','P')
            ->pluck('blmdiverifikasi')
            ->first();
        $atasandivisi = DB::table('ireq_mst')
            ->select(DB::raw('count(ireq_id) as atasandivisi'))
            ->where('ireq_status','NA1')
            ->orWhere('ireq_status','A1')
            ->pluck('atasandivisi')
            ->first();
        $manager = DB::table('ireq_mst')
            ->select(DB::raw('count(ireq_id) as manager'))
            ->where('ireq_status','NA2')
            ->orWhere('ireq_status','A2')
            ->pluck('manager')
            ->first();
        $reject = DB::table('ireq_mst')
            ->select(DB::raw('count(ireq_id) as reject'))
            ->where(function($query){
                return $query
                ->where('ireq_status','RR')
                ->orWhere('ireq_status','RA1')
                ->orWhere('ireq_status','RA2');
            })
            ->pluck('reject')
            ->first();
        $blmdiassign = DB::table('ireq_mst')
            ->select(DB::raw('count(ireq_id) as blmdiassign'))
            ->where('ireq_status','A')
            ->pluck('blmdiassign')
            ->first();
        $sdgdikerjakan = DB::table('ireq_mst')
            ->select(DB::raw('count(ireq_id) as sdgdikerjakan'))
            ->where('ireq_status','T')
            ->pluck('sdgdikerjakan')
            ->first();
        $sdhdikerjakan = DB::table('ireq_mst')
            ->select(DB::raw('count(ireq_id) as sdhdikerjakan'))
            ->where('ireq_status','D')
            ->pluck('sdhdikerjakan')
            ->first();
        $sdhselesai = DB::table('ireq_mst')
            ->select(DB::raw('count(ireq_id) as sdhselesai'))
            ->where('ireq_status','C')
            ->pluck('sdhselesai')
            ->first();
        $total = DB::table('ireq_mst')
            ->select(DB::raw('count(ireq_id) as total'))
            ->whereNotNull('ireq_status')
            ->pluck('total')
            ->first();
        return response()->json(['blmDiverifikasi'=>$blmdiverifikasi,'atasandivisi'=>$atasandivisi,'manager'=>$manager,'reject'=>$reject,'blmdiassign'=>$blmdiassign,'sdgdikerjakan'=>$sdgdikerjakan,'sdhdikerjakan'=>$sdhdikerjakan,'sdhselesai'=>$sdhselesai,'totalRequest'=>$total]);
    }
    public function countDivisi3($fullname)
    {
        $grafik = DB::table('ireq_dtl as im')
        ->select(DB::raw("(SELECT COUNT(ireqd_id) FROM ireq_dtl WHERE ireq_dtl.ireq_status = 'T' AND ireq_dtl.ireq_assigned_to = '$fullname') as belumselesai"),
                 DB::raw("(SELECT COUNT(ireqd_id) FROM ireq_dtl WHERE ireq_dtl.ireq_status = 'D' AND ireq_dtl.ireq_assigned_to = '$fullname') as sudahdikerjakan"),
                 DB::raw("(SELECT COUNT(ireqd_id) FROM ireq_dtl WHERE ireq_dtl.ireq_status = 'C' AND ireq_dtl.ireq_assigned_to = '$fullname') as sudahselesai"))
        ->first();
        return response()->json($grafik);
    }
    function countDivisi4()
    {  
        $grafik = DB::table('ireq_mst as im')
        ->select(DB::raw("(SELECT COUNT(ireq_id) FROM ireq_mst WHERE ireq_mst.ireq_status IN ( 'NA2','NA1')) as blmdiverifikasi"),
                 DB::raw("(SELECT COUNT(ireq_id) FROM ireq_mst WHERE ireq_mst.ireq_status IN ( 'A2','A1' )) as sudahdiverifikasi"),
                 DB::raw("(SELECT COUNT(ireq_id) FROM ireq_mst WHERE ireq_mst.ireq_status IN ('RA2','RR','RA1')) as direject"),
                 DB::raw("(SELECT COUNT(ireq_id) FROM ireq_mst WHERE ireq_mst.ireq_status = 'T') as sedangdikerjakan"),
                 DB::raw("(SELECT COUNT(ireq_id) FROM ireq_mst WHERE ireq_mst.ireq_status = 'D') as sudahdikerjakan"),
                 DB::raw("(SELECT COUNT(ireq_id) FROM ireq_mst WHERE ireq_mst.ireq_status = 'C') as sudahselesai"),
                 DB::raw("(SELECT COUNT(ireq_id) FROM ireq_mst WHERE ireq_mst.ireq_status IS NOT NULL) as totalrequest"))
        ->first();
        return response()->json($grafik);
    }
    function countAdmin()
    {
        $grafik = DB::table('ireq_mst as im')
        ->select(DB::raw("(SELECT COUNT(ireq_id) FROM ireq_mst WHERE ireq_mst.ireq_status IN ('P','NA1','NA2')) as belumdiverifikasi"),
                 DB::raw("(SELECT COUNT(ireq_id) FROM ireq_mst WHERE ireq_mst.ireq_status IN ('A1','A2')) as sudahdiverifikasi"),
                 DB::raw("(SELECT COUNT(ireq_id) FROM ireq_mst WHERE ireq_mst.ireq_status IN ('RR','RA1','RA2')) as direject"),
                 DB::raw("(SELECT COUNT(ireq_id) FROM ireq_mst WHERE ireq_mst.ireq_status = 'T') as sedangdikerjakan"),
                 DB::raw("(SELECT COUNT(ireq_id) FROM ireq_mst WHERE ireq_mst.ireq_status = 'D') as sudahdikerjakan"),
                 DB::raw("(SELECT COUNT(ireq_id) FROM ireq_mst WHERE ireq_mst.ireq_status = 'C') as sudahselesai"),
                 DB::raw("(SELECT COUNT(ireq_id) FROM ireq_mst WHERE ireq_mst.ireq_status IS NOT NULL) as countrequest"))
        ->first();
        return response()->json($grafik);
    }
    public function getTahun()
    {
        $grafik = DB::table('VREQ_MST_TAHUN')->get();
        $grafik1 = DB::table('VREQ_MST_STATUS')->get();
        $grafik2 = DB::table('VREQ_MST_BULAN')->get();
        $grafik3 = DB::table('VREQ_PER_STATUS')->get();
        $personnel = DB::table('ireq_dtl')
        ->select('ireq_assigned_to',DB::raw("count(ireqd_id) as jumlah"))
        ->whereNotNull('ireq_assigned_to')
        ->groupBy('ireq_assigned_to')
        ->get();
        $personnell = DB::table('ireq_dtl')
        ->select('ireq_assigned_to as name')
        ->whereNotNull('ireq_assigned_to')
        ->groupBy('ireq_assigned_to')
        ->get();

        return response()->json(['grafik'=>$grafik,'grafik1'=>$grafik1,'grafik2'=>$grafik2,'grafik3'=>$grafik3,'personnel'=>$personnel,'personnell'=>$personnell],200);
    }
    public function getTahunUser($bulanUser)
    {
        $grafik = DB::table('ireq_mst as im')
        ->select(DB::raw("TO_CHAR(im.ireq_date,'yyyy') as tahun"))
        ->whereMonth('im.ireq_date',$bulanUser)
        ->groupBy(DB::raw("TO_CHAR(im.ireq_date,'yyyy')"))
        ->orderBy(DB::raw("TO_CHAR(im.ireq_date,'yyyy')"),'DESC')
        ->get();
        return response()->json($grafik);
    }
    public function getTahunRequestor($bulanUser)
    {
        $grafik = DB::table('ireq_mst as im')
        ->select(DB::raw("TO_CHAR(im.ireq_date,'yyyy') as tahun"))
        ->whereMonth('im.ireq_date',$bulanUser)
        ->groupBy(DB::raw("TO_CHAR(im.ireq_date,'yyyy')"))
        ->orderBy(DB::raw("TO_CHAR(im.ireq_date,'yyyy')"),'DESC')
        ->get();
        return response()->json($grafik);
    }
    public function countStatusPerDivisi()
    {
        $grafik = DB::table('vreg_per_divuser_status')->get();
        return response()->json($grafik);
    }
    public function countPerDivUserTahun($tahunUser)
    {
        $grafik = DB::table('ireq_mst as im')
        ->leftjoin('divisi_refs as dr','im.ireq_divisi_user','dr.div_id')
        ->select('dr.div_name',DB::raw("count(im.ireq_id) as jumlah"))
        ->whereYear('im.ireq_date',$tahunUser)
        ->orderBy('dr.div_name','ASC') 
        ->groupBy('dr.div_name')
        ->get();
        return response()->json($grafik);
    }
    public function countPerDivRequestorTahun($tahunRequestor)
    {
        $grafik = DB::table('ireq_mst as im')
        ->leftjoin('divisi_refs as dr','im.ireq_divisi_requestor','dr.div_id')
        ->select('dr.div_name',DB::raw("count(im.ireq_id) as jumlah"))
        ->whereYear('im.ireq_date',$tahunRequestor)
        ->orderBy('dr.div_name','ASC')
        ->groupBy('dr.div_name')
        ->get();
        return response()->json($grafik);
    }
    public function countPerDivUserBulan($tahunnUser,$bulanUser)
    {
        $grafik = DB::table('ireq_mst as im')
        ->leftjoin('divisi_refs as dr','im.ireq_divisi_user','dr.div_id')
        ->select('dr.div_name',DB::raw("count(im.ireq_id) as jumlah"),DB::raw("TO_CHAR(im.ireq_date,'Month') as bulan"))
        ->whereYear('im.ireq_date',$tahunnUser)
        ->whereMonth('im.ireq_date',$bulanUser)
        ->orderBy('dr.div_name','ASC')
        ->groupBy('dr.div_name', DB::raw("TO_CHAR(im.ireq_date,'Month')"))
        ->get();
        return response()->json($grafik);
    }
    public function countPerDivRequestorBulan($tahunRequestor,$bulanRequestor)
    {
        $grafik = DB::table('ireq_mst as im')
        ->leftjoin('divisi_refs as dr','im.ireq_divisi_requestor','dr.div_id')
        ->select('dr.div_name',DB::raw("count(im.ireq_id) as jumlah"),
                  DB::raw("TO_CHAR(im.ireq_date,'Month') as bulan"))
        ->whereYear('im.ireq_date',$tahunRequestor)
        ->whereMonth('im.ireq_date',$bulanRequestor)
        ->orderBy('dr.div_name','ASC')
        ->groupBy('dr.div_name', DB::raw("TO_CHAR(im.ireq_date,'Month')"))
        ->get();
        return response()->json($grafik);
    }
    public function countPerDivUserStatus($statusUser)
    {
        $grafik = DB::table('ireq_mst as imm')
        ->select('dr.div_name',DB::raw("count(imm.ireq_id) as jumlah"),'lr.lookup_desc as name')
        ->leftjoin('divisi_refs as dr','imm.ireq_divisi_user','dr.div_id')
        ->leftjoin('lookup_refs as lr','imm.ireq_status','lr.lookup_code')
        ->whereRaw('LOWER(lr.lookup_type) LIKE ? ',[trim(strtolower('ict_status')).'%'])
        ->where('imm.ireq_status',$statusUser)
        ->orderBy('dr.div_name','ASC')
        ->groupBy('dr.div_name','lr.lookup_desc')
        ->get();
        return response()->json($grafik);
    }
    public function countPerDivRequestorStatus($statusRequestor)
    {
        $grafik = DB::table('ireq_mst as imm')
        ->select('dr.div_name',DB::raw("count(imm.ireq_id) as jumlah"),
                  DB::raw("CASE WHEN imm.ireq_status = 'A' Then 'Approved' WHEN imm.ireq_status = 'T' Then 'Penugasan' WHEN imm.ireq_status = 'R' Then 'Reject' WHEN imm.ireq_status = 'D' Then 'Done' WHEN imm.ireq_status = 'C' Then 'Close' WHEN imm.ireq_status = 'P' Then 'Permohonan' end as name "))
        ->leftjoin('divisi_refs as dr','imm.ireq_divisi_requestor','dr.div_id')
        ->orderBy('dr.div_name','ASC')
        ->where('imm.ireq_status',$statusRequestor)
        ->groupBy('dr.div_name',DB::raw("CASE WHEN imm.ireq_status = 'A' Then 'Approved' WHEN imm.ireq_status = 'T' Then 'Penugasan' WHEN imm.ireq_status = 'R' Then 'Reject' WHEN imm.ireq_status = 'D' Then 'Done' WHEN imm.ireq_status = 'C' Then 'Close' WHEN imm.ireq_status = 'P' Then 'Permohonan' end"))
        ->get();
        return response()->json($grafik);
    }
    public function countPerPersonnel()
    {
        $grafik= DB::table('VREQ_PER_ICTPERSON')->get();
        return response()->json($grafik); 
    }
    public function countPerStatusIct($ictPersonnel)
    {
        $grafik = DB::table('ireq_dtl as imm')
        ->leftjoin('lookup_refs as lr','imm.ireq_status','lr.lookup_code')
        ->select(DB::raw("count(imm.ireq_id) as jumlah"),'lr.lookup_desc as status')
        ->where('imm.ireq_assigned_to',$ictPersonnel)
        ->whereRaw('LOWER(lr.lookup_type) LIKE ? ',[trim(strtolower('ict_status')).'%'])
        ->groupBy('lr.lookup_desc')
        ->get();
        return response()->json($grafik);
    }
}
