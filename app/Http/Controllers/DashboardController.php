<?php

namespace App\Http\Controllers;
use App\Helpers\ResponseFormatter;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\Ict;
use App\Models\Mng_user;
use App\Services\DashboardServices;

class DashboardController extends Controller
{
    protected $dashboardServices;

    public function __construct(DashboardServices $services)
    {
        $this->dashboardServices = $services;
    }
    function index(){
        $cekrole = Mng_User::roles();
        if($cekrole->contains('Admin')){
            $admin = $this->countAdmin();
            return $admin;
        }
        else if($cekrole->contains('Manager')){
            $manager = $this->countIctManager();
            return $manager;
        } 
        else if($cekrole->contains('Reviewer Bentu')){
            $bentu = $this->countReviewerKurau();
            return $bentu;
        } 
        else if($cekrole->contains('Reviewer Kurau')){
            $kurau = $this->countReviewerKurau();
            return $kurau;
        } 
        else if($cekrole->contains('Reviewer Jakarta')){
            $jakarta = $this->countReviewerJakarta();
            return ($jakarta);
        } 
        else if($cekrole->contains('Personnel ICT')){
            $personel = $this->countPersonnel();
            return ($personel);
        } 
        else if($cekrole->contains('Atasan Requestor Divisi')){
            $higherlevel = $this->countHigherLevel();
            return ($higherlevel);
        } 
        else if($cekrole->contains('Requestor Divisi') OR ($cekrole->contains('Requestor Divisi'))){
            $requestor = $this->countUser();
            return ($requestor);
        } 
    }
    function countUser()
    {
        $usr_id = Auth::user()->usr_id;
        $dashboard = $this->dashboardServices->CountDataDashboard();
        $grafik['belumdiverifikasirequestor'] = collect($dashboard)->whereIN('ireq_status',['NA1','NA2'])->where('ireq_requestor',$usr_id)->count();
        $grafik['sudahdiverifikasirequestor'] = collect($dashboard)->whereIN('ireq_status',['A1','A2'])->where('ireq_requestor',$usr_id)->count();
        $grafik['sedangdireviewrequestor'] = collect($dashboard)->where('ireq_status','P')->where('ireq_requestor',$usr_id)->count();
        $grafik['direjectrequestor'] = collect($dashboard)->whereIN('ireq_status',['RR','RA1','RA2','RT'])->where('ireq_requestor',$usr_id)->count();
        $grafik['sedangdikerjakanrequestor'] = collect($dashboard)->whereIN('ireq_status',['T','NT'])->where('ireq_requestor',$usr_id)->count();
        $grafik['sudahdikerjakanrequestor'] = collect($dashboard)->where('ireq_status','D')->where('ireq_requestor',$usr_id)->count();
        $grafik['sudahselesairequestor'] = collect($dashboard)->where('ireq_status','C')->where('ireq_requestor',$usr_id)->count();
        $grafik['countrequestrequestor'] = collect($dashboard)->whereNotNull('ireq_status')->where('ireq_requestor',$usr_id)->count();

        return ResponseFormatter::success($grafik,'Successfully Get Data Dashboard');
    }
    function countHigherLevel()
    {
        $usr_email = Auth::user()->usr_email;
        $grafik = DB::table('ireq_mst as im')
        ->select(DB::raw("(SELECT COUNT(ireq_id) FROM ireq_mst LEFT JOIN divisi_refs ON ireq_mst.ireq_divisi_user = divisi_refs.div_id WHERE ireq_mst.ireq_status IN ('NA1','NA2') AND divisi_refs.div_verificator = '$usr_email') as belumdiverifikasiHigherLevel"),
        DB::raw("(SELECT COUNT(ireq_id) FROM ireq_mst LEFT JOIN divisi_refs ON ireq_mst.ireq_divisi_user = divisi_refs.div_id WHERE ireq_mst.ireq_status = 'P' AND divisi_refs.div_verificator = '$usr_email') as sedangdireviewHigherLevel"),
                 DB::raw("(SELECT COUNT(ireq_id) FROM ireq_mst LEFT JOIN divisi_refs ON ireq_mst.ireq_divisi_user = divisi_refs.div_id WHERE ireq_mst.ireq_status IN ('A1','A2') AND divisi_refs.div_verificator = '$usr_email') as sudahdiverifikasiHigherLevel"),
                 DB::raw("(SELECT COUNT(ireq_id) FROM ireq_mst LEFT JOIN divisi_refs ON ireq_mst.ireq_divisi_user = divisi_refs.div_id WHERE ireq_mst.ireq_status IN ('RA1','RR','RA2') AND divisi_refs.div_verificator = '$usr_email') as direjectHigherLevel"),
                 DB::raw("(SELECT COUNT(ireq_id) FROM ireq_mst LEFT JOIN divisi_refs ON ireq_mst.ireq_divisi_user = divisi_refs.div_id WHERE ireq_mst.ireq_status IN ('NT','RT') AND divisi_refs.div_verificator = '$usr_email') as penugasanrequestHigherLevel"),
                 DB::raw("(SELECT COUNT(ireq_id) FROM ireq_mst LEFT JOIN divisi_refs ON ireq_mst.ireq_divisi_user = divisi_refs.div_id WHERE ireq_mst.ireq_status = 'T' AND divisi_refs.div_verificator = '$usr_email') as sedangdikerjakanHigherLevel"),
                 DB::raw("(SELECT COUNT(ireq_id) FROM ireq_mst LEFT JOIN divisi_refs ON ireq_mst.ireq_divisi_user = divisi_refs.div_id WHERE ireq_mst.ireq_status = 'D' AND divisi_refs.div_verificator = '$usr_email') as sudahdikerjakanHigherLevel"),
                 DB::raw("(SELECT COUNT(ireq_id) FROM ireq_mst LEFT JOIN divisi_refs ON ireq_mst.ireq_divisi_user = divisi_refs.div_id WHERE ireq_mst.ireq_status = 'C' AND divisi_refs.div_verificator = '$usr_email') as sudahselesaiHigherLevel"),
                 DB::raw("(SELECT COUNT(ireq_id) FROM ireq_mst LEFT JOIN divisi_refs ON ireq_mst.ireq_divisi_user = divisi_refs.div_id WHERE ireq_mst.ireq_status IS NOT NULL AND divisi_refs.div_verificator = '$usr_email') as totalHigherLevel"))
        ->first();
        return ResponseFormatter::success($grafik,'Successfully Get Data Dashboard');
    }
    function countReviewerJakarta()
    {
        $dashboard = $this->dashboardServices->CountDataDashboard();
        $grafik['blmDiverifikasiJakarta'] = collect($dashboard)->where('ireq_status','P')->where('ireq_loc','OJ')->count();
        $grafik['atasandivisiJakarta'] = collect($dashboard)->where('ireq_status','NA1')->orwhere('ireq_status','A1')->where('ireq_loc','OJ')->count();
        $grafik['managerJakarta'] = collect($dashboard)->where('ireq_status','NA2')->orwhere('ireq_status','A2')->where('ireq_loc','OJ')->count();
        $grafik['rejectJakarta'] = collect($dashboard)->where('ireq_status','RR')->orwhere('ireq_status','RA1')->orwhere('ireq_status','RA2')->where('ireq_loc','OJ')->count();
        $grafik['sdgdikerjakanJakarta'] = collect($dashboard)->where('ireq_status','T')->where('ireq_loc','OJ')->count();
        $grafik['sdhdikerjakanJakarta'] = collect($dashboard)->where('ireq_status','D')->where('ireq_loc','OJ')->count();
        $grafik['sdhselesaiJakarta'] = collect($dashboard)->where('ireq_status','C')->where('ireq_loc','OJ')->count();
        $grafik['penugasanRequestJakarta'] = collect($dashboard)->where('ireq_status','NT')->where('ireq_status','RT')->where('ireq_loc','OJ')->count();
        $grafik['totalRequestJakarta'] = collect($dashboard)->whereNotNull('ireq_status')->where('ireq_loc','OJ')->count();
        return response()->json($grafik);
    }
    function countReviewerKurau()
    {
        $dashboard = $this->dashboardServices->CountDataDashboard();
        $grafik['blmDiverifikasiKurau'] = collect($dashboard)->where('ireq_status','P')->where(function ($query){ 
            return $query ->where('ireq_loc','OK')->OrWhere('ireq_loc','FK');})->count();
        $grafik['atasandivisiKurau'] = collect($dashboard)->where(function ($query){ 
            return $query ->where('ireq_status','NA1')->orwhere('ireq_status','A1');
        })->where('ireq_loc','OK')->OrWhere('ireq_loc','FK')->count();
        $grafik['managerKurau'] = collect($dashboard)->where(function ($query){ 
            return $query ->where('ireq_status','NA2')->orwhere('ireq_status','A2');
        })->where('ireq_loc','OK')->OrWhere('ireq_loc','FK')->count();
        $grafik['rejectKurau'] = collect($dashboard)->where(function ($query){ 
            return $query ->where('ireq_status','RR')->orwhere('ireq_status','RA1')->orwhere('ireq_status','RA2');
        })->where(function ($query){ 
            return $query ->where('ireq_loc','OK')->OrWhere('ireq_loc','FK');})->count();
        $grafik['sdgdikerjakanKurau'] = collect($dashboard)->where('ireq_status','T')->where(function ($query){ 
            return $query ->where('ireq_loc','OK')->OrWhere('ireq_loc','FK');})->count();
        $grafik['sdhdikerjakanKurau'] = collect($dashboard)->where('ireq_status','D')->where(function ($query){ 
            return $query ->where('ireq_loc','OK')->OrWhere('ireq_loc','FK');})->count();
        $grafik['sdhselesaiKurau'] = collect($dashboard)->where('ireq_status','C')->where(function ($query){ 
            return $query ->where('ireq_loc','OK')->OrWhere('ireq_loc','FK');})->count();
        $grafik['penugasanRequestKurau'] = collect($dashboard)->where(function ($query){ 
            return $query ->where('ireq_status','NT')->orwhere('ireq_status','RT');
        })->where('ireq_loc','OK')->OrWhere('ireq_loc','FK')->count();
        $grafik['totalRequestKurau'] = collect($dashboard)->where(function ($query){ 
            return $query ->where('ireq_loc','OK')->orwhere('ireq_loc','FK');
        })->whereNotNull('ireq_status')->count();

        return response()->json($grafik);
    }
    function countReviewerBentu()
    {
        $blmdiverifikasi = DB::table('ireq_mst')
            ->select(DB::raw('count(ireq_id) as blmdiverifikasi'))
            ->where('ireq_status','P')
            ->where(function ($query){
                return $query
                ->Where('ireq_loc','OB')
                ->OrWhere('ireq_loc','FB');
            })
            ->pluck('blmdiverifikasi')
            ->first();
        $atasandivisi = DB::table('ireq_mst')
            ->select(DB::raw('count(ireq_id) as atasandivisi'))
            ->where(function ($query){
                return $query
                ->Where('ireq_loc','OB')
                ->OrWhere('ireq_loc','FB');
            })
            ->where(function ($query){
                return $query
                ->where('ireq_status','NA1')
                ->orWhere('ireq_status','A1');
            })
            ->pluck('atasandivisi')
            ->first();
        $manager = DB::table('ireq_mst')
            ->select(DB::raw('count(ireq_id) as manager'))
            ->where(function ($query){
                return $query
                ->Where('ireq_loc','OB')
                ->OrWhere('ireq_loc','FB');
            })
            ->where(function ($query){
                return $query
                ->where('ireq_status','NA2')
                ->orWhere('ireq_status','A2');
            })
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
            ->where(function ($query){
                return $query
                ->Where('ireq_loc','OB')
                ->OrWhere('ireq_loc','FB');
            })
            ->pluck('reject')
            ->first();
        $sdgdikerjakan = DB::table('ireq_mst')
            ->select(DB::raw('count(ireq_id) as sdgdikerjakan'))
            ->where('ireq_status','T')
            ->where(function ($query){
                return $query
                ->Where('ireq_loc','OB')
                ->OrWhere('ireq_loc','FB');
            })
            ->pluck('sdgdikerjakan')
            ->first();
        $sdhdikerjakan = DB::table('ireq_mst')
            ->select(DB::raw('count(ireq_id) as sdhdikerjakan'))
            ->where('ireq_status','D')
            ->where(function ($query){
                return $query
                ->Where('ireq_loc','OB')
                ->OrWhere('ireq_loc','FB');
            })
            ->pluck('sdhdikerjakan')
            ->first();
        $sdhselesai = DB::table('ireq_mst')
            ->select(DB::raw('count(ireq_id) as sdhselesai'))
            ->where('ireq_status','C')
            ->where(function ($query){
                return $query
                ->Where('ireq_loc','OB')
                ->OrWhere('ireq_loc','FB');
            })
            ->pluck('sdhselesai')
            ->first();
        $penugasanRequest = DB::table('ireq_mst')
            ->select(DB::raw('count(ireq_id) as tes'))
            ->where(function ($query){
                return $query
                ->Where('ireq_loc','OB')
                ->OrWhere('ireq_loc','FB');
            })
            ->where(function ($query){
                return $query
                ->where('ireq_status','NT')
                ->orWhere('ireq_status','RT');
            })
            ->pluck('tes')
            ->first();
        $total = DB::table('ireq_mst')
            ->select(DB::raw('count(ireq_id) as total'))
            ->whereNotNull('ireq_status')
            ->where(function ($query){
                return $query
                ->Where('ireq_loc','OB')
                ->OrWhere('ireq_loc','FB');
            })
            ->pluck('total')
            ->first();
        return response()->json(['blmDiverifikasiBentu'=>$blmdiverifikasi,'atasandivisiBentu'=>$atasandivisi,'managerBentu'=>$manager,'rejectBentu'=>$reject,'sdgdikerjakanBentu'=>$sdgdikerjakan,'sdhdikerjakanBentu'=>$sdhdikerjakan,'sdhselesaiBentu'=>$sdhselesai,'totalRequestBentu'=>$total,'penugasanRequestBentu'=>$penugasanRequest]);
    }
    function countPersonnel()
    {
        $fullname = Auth::user()->usr_id;
        $grafik = DB::table('ireq_dtl as im')
        ->select(DB::raw("(SELECT COUNT(ireqd_id) FROM ireq_dtl WHERE ireq_dtl.ireq_status = 'NT' AND ireq_dtl.ireq_assigned_to1 = '$fullname') as penugasanrequestPersonnel"),
                 DB::raw("(SELECT COUNT(ireqd_id) FROM ireq_dtl WHERE ireq_dtl.ireq_status = 'RT' AND ireq_dtl.ireq_assigned_to1 = '$fullname') as rejectedPersonnel"),
                 DB::raw("(SELECT COUNT(ireqd_id) FROM ireq_dtl WHERE ireq_dtl.ireq_status = 'T' AND COALESCE(ireq_dtl.ireq_assigned_to2,ireq_dtl.ireq_assigned_to1) = '$fullname') as belumselesaiPersonnel"),
                 DB::raw("(SELECT COUNT(ireqd_id) FROM ireq_dtl WHERE ireq_dtl.ireq_status = 'D' AND COALESCE(ireq_dtl.ireq_assigned_to2,ireq_dtl.ireq_assigned_to1) = '$fullname') as sudahdikerjakanPersonnel"),
                 DB::raw("(SELECT COUNT(ireqd_id) FROM ireq_dtl WHERE ireq_dtl.ireq_status = 'C' AND COALESCE(ireq_dtl.ireq_assigned_to2,ireq_dtl.ireq_assigned_to1) = '$fullname') as sudahselesaiPersonnel"))
        ->first();
        
        return ResponseFormatter::success($grafik,'Successfully Get Data Dashboard');
    }
    function countIctManager()
    {
        $grafik = DB::table('ireq_mst as im')
        ->select(DB::raw("(SELECT COUNT(ireq_id) FROM ireq_mst WHERE ireq_mst.ireq_status IN ( 'NA2','NA1')) as blmdiverifikasiManager"),
                 DB::raw("(SELECT COUNT(ireq_id) FROM ireq_mst WHERE ireq_mst.ireq_status IN ( 'A2','A1' )) as sudahdiverifikasiManager"),
                 DB::raw("(SELECT COUNT(ireq_id) FROM ireq_mst WHERE ireq_mst.ireq_status = 'P') as sedangdireviewManager"),
                 DB::raw("(SELECT COUNT(ireq_id) FROM ireq_mst WHERE ireq_mst.ireq_status IN ('RA2','RR','RA1')) as direjectManager"),
                 DB::raw("(SELECT COUNT(ireq_id) FROM ireq_mst WHERE ireq_mst.ireq_status IN ('NT','RT')) as penugasanRequestManager"),
                 DB::raw("(SELECT COUNT(ireq_id) FROM ireq_mst WHERE ireq_mst.ireq_status = 'T') as sedangdikerjakanManager"),
                 DB::raw("(SELECT COUNT(ireq_id) FROM ireq_mst WHERE ireq_mst.ireq_status = 'D') as sudahdikerjakanManager"),
                 DB::raw("(SELECT COUNT(ireq_id) FROM ireq_mst WHERE ireq_mst.ireq_status = 'C') as sudahselesaiManager"),
                 DB::raw("(SELECT COUNT(ireq_id) FROM ireq_mst WHERE ireq_mst.ireq_status IS NOT NULL) as totalrequestManager"))
        ->first();
        
        return ResponseFormatter::success($grafik,'Successfully Get Data Dashboard');
    }
    function countAdmin()
    {
        $grafik = DB::table('ireq_mst as im')
        ->select(DB::raw("(SELECT COUNT(ireq_id) FROM ireq_mst WHERE ireq_mst.ireq_status IN ('P','NA1','NA2')) as belumdiverifikasiAdmin"),
                 DB::raw("(SELECT COUNT(ireq_id) FROM ireq_mst WHERE ireq_mst.ireq_status IN ('A1','A2')) as sudahdiverifikasiAdmin"),
                 DB::raw("(SELECT COUNT(ireq_id) FROM ireq_mst WHERE ireq_mst.ireq_status IN ('RR','RA1','RA2')) as direjectAdmin"),
                 DB::raw("(SELECT COUNT(ireq_id) FROM ireq_mst WHERE ireq_mst.ireq_status = 'T') as sedangdikerjakanAdmin"),
                 DB::raw("(SELECT COUNT(ireqd_id) FROM ireq_dtl WHERE ireq_dtl.ireq_status = 'D') as sudahdikerjakanAdmin"),
                 DB::raw("(SELECT COUNT(ireqd_id) FROM ireq_dtl WHERE ireq_dtl.ireq_status = 'C') as sudahselesaiAdmin"),
                 DB::raw("(SELECT COUNT(ireq_id) FROM ireq_mst WHERE ireq_mst.ireq_status IS NOT NULL) as countrequestAdmin"))
        ->first();
        return ResponseFormatter::success($grafik,'Successfully Get Data Dashboard');
    }
    function getTahun()
    {
        $data['grafik'] = DB::table('VREQ_MST_TAHUN')->get();
        $data['grafik1'] = DB::table('VREQ_MST_STATUS')->get();
        $data['grafik2'] = DB::table('VREQ_MST_BULAN')->get();
        $data['grafik3'] = DB::table('VREQ_PER_STATUS')->get();
        $data['personnel'] = DB::table('ireq_dtl')
        ->select(DB::raw("COALESCE(ireq_assigned_to2,ireq_assigned_to1) AS ireq_assigned_to"),DB::raw("count(ireqd_id) as jumlah"))
        ->whereNotNull('ireq_assigned_to1')
        ->groupBy(DB::raw("COALESCE(ireq_assigned_to2, ireq_assigned_to1)"))
        ->get();
        $data['personnell'] = DB::table('ireq_dtl')
        ->select(DB::raw("COALESCE(ireq_assigned_to2,ireq_assigned_to1) AS name"))
        ->whereNotNull(DB::raw("COALESCE(ireq_assigned_to2,ireq_assigned_to1)"))
        ->groupBy(DB::raw("COALESCE(ireq_assigned_to2,ireq_assigned_to1)"))
        ->get();

        return response()->json($data,200);
    }
    function getTahunUser($bulanUser)
    {
        $grafik = DB::table('ireq_mst as im')
        ->select(DB::raw("TO_CHAR(im.ireq_date,'yyyy') as tahun"))
        ->whereMonth('im.ireq_date',$bulanUser)
        ->groupBy(DB::raw("TO_CHAR(im.ireq_date,'yyyy')"))
        ->orderBy(DB::raw("TO_CHAR(im.ireq_date,'yyyy')"),'DESC')
        ->get();
        return response()->json($grafik);
    }
    function getTahunRequestor($bulanUser)
    {
        $grafik = DB::table('ireq_mst as im')
        ->select(DB::raw("TO_CHAR(im.ireq_date,'yyyy') as tahun"))
        ->whereMonth('im.ireq_date',$bulanUser)
        ->groupBy(DB::raw("TO_CHAR(im.ireq_date,'yyyy')"))
        ->orderBy(DB::raw("TO_CHAR(im.ireq_date,'yyyy')"),'DESC')
        ->get();
        return response()->json($grafik);
    }
    function countStatusPerDivisi()
    {
        $grafik = DB::table('vreg_per_divuser_status')->get();
        return response()->json($grafik);
    }
    function countPerDivUserTahun($tahunUser)
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
    function countPerDivRequestorTahun($tahunRequestor)
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
    function countPerDivUserBulan($tahunnUser,$bulanUser)
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
    function countPerDivRequestorBulan($tahunRequestor,$bulanRequestor)
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
    function countPerDivUserStatus($statusUser)
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
    function countPerDivRequestorStatus($statusRequestor)
    {
        $grafik = DB::table('ireq_mst as imm')
        ->select('dr.div_name',DB::raw("count(imm.ireq_id) as jumlah"),'lr.lookup_desc as name')
        ->leftjoin('divisi_refs as dr','imm.ireq_divisi_requestor','dr.div_id')
        ->leftjoin('lookup_refs as lr','imm.ireq_status','lr.lookup_code')
        ->orderBy('dr.div_name','ASC')
        ->where('imm.ireq_status',$statusRequestor)
        ->whereRaw('LOWER(lr.lookup_type) LIKE ? ',[trim(strtolower('ict_status')).'%'])
        ->groupBy('dr.div_name','lr.lookup_desc')
        ->get();
        return response()->json($grafik);
    }
    function countPerPersonnel()
    {
        $grafik= DB::table('VREQ_PER_ICTPERSON')->get();
        return response()->json($grafik); 
    }
    function countPerStatusIct($ictPersonnel)
    {
        $grafik = DB::table('ireq_dtl as id')
            ->leftjoin('lookup_refs as lr','id.ireq_status','lr.lookup_code')
            ->select(DB::raw("count(id.ireq_id) as jumlah"),'lr.lookup_desc as status')
            ->where(DB::raw("COALESCE(id.ireq_assigned_to2, id.ireq_assigned_to1)"),$ictPersonnel)
            ->whereRaw('LOWER(lr.lookup_type) LIKE ? ',[trim(strtolower('ict_status')).'%'])
            ->groupBy('lr.lookup_desc',DB::raw("CASE WHEN id.ireq_status = 'P' Then 1 WHEN id.ireq_status = 'NA1' Then 2 WHEN id.ireq_status = 'NA2' Then 3 WHEN
                id.ireq_status = 'A1' Then 4 WHEN id.ireq_status = 'A2' Then 5
                WHEN id.ireq_status = 'RR' Then 6 WHEN id.ireq_status = 'RA1' Then 7
                WHEN id.ireq_status = 'RA2' THEN 8 WHEN id.ireq_status = 'NT' Then 9 WHEN 
                id.ireq_status = 'RT' Then 10 WHEN id.ireq_status = 'T' Then 11 WHEN id.ireq_status = 'D' 
                Then 12 WHEN id.ireq_status = 'C' Then 13 end "))
                ->orderBy(DB::raw("CASE WHEN id.ireq_status = 'P' Then 1 WHEN id.ireq_status = 'NA1' Then 2 WHEN id.ireq_status = 'NA2' Then 3 WHEN
                id.ireq_status = 'A1' Then 4 WHEN id.ireq_status = 'A2' Then 5
                WHEN id.ireq_status = 'RR' Then 6 WHEN id.ireq_status = 'RA1' Then 7
                WHEN id.ireq_status = 'RA2' THEN 8 WHEN id.ireq_status = 'NT' Then 9 WHEN 
                id.ireq_status = 'RT' Then 10 WHEN id.ireq_status = 'T' Then 11 WHEN id.ireq_status = 'D' 
                Then 12 WHEN id.ireq_status = 'C' Then 13 end "))
            ->get();
        return response()->json($grafik);
    }
    function getStatus(){
        $status = DB::table('VREQ_MST_STATUS')->get();
        return response()->json($status);
    }
}
