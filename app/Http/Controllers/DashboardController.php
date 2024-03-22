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
        $data = $this->dashboardServices->countHigherLevel();
        $grafik['belumdiverifikasihigherlevel'] = collect($data)->whereIN('ireq_status',['NA1','NA2'])->count();
        $grafik['sedangdireviewhigherlevel'] = collect($data)->where('ireq_status',['P'])->count();
        $grafik['sudahdiverifikasihigherlevel'] = collect($data)->whereIN('ireq_status',['A1','A2'])->count();
        $grafik['direjecthigherlevel'] = collect($data)->whereIN('ireq_status',['RA1','RR','RA2'])->count();
        $grafik['penugasanrequesthigherlevel'] = collect($data)->whereIN('ireq_status',['NT','RT'])->count();
        $grafik['sedangdikerjakanhigherlevel'] = collect($data)->whereIN('ireq_status',['T'])->count();
        $grafik['sudahdikerjakanhigherlevel'] = collect($data)->whereIN('ireq_status',['D'])->count();
        $grafik['sudahselesaihigherlevel'] = collect($data)->whereIN('ireq_status',['C'])->count();
        $grafik['totalhigherlevel'] = collect($data)->whereNotNull('ireq_status')->count();
        return ResponseFormatter::success($grafik,'Successfully Get Data Dashboard');
    }
    function countReviewerJakarta()
    {
        $dashboard = $this->dashboardServices->CountDataDashboard();
        $grafik['blmDiverifikasijakarta'] = collect($dashboard)->where('ireq_status','P')->where('ireq_loc','OJ')->count();
        $grafik['atasandivisijakarta'] = collect($dashboard)->where('ireq_status',['NA1','A1'])->where('ireq_loc','OJ')->count();
        $grafik['managerjakarta'] = collect($dashboard)->whereIn('ireq_status',['NA2','A2'])->where('ireq_loc','OJ')->count();
        $grafik['rejectjakarta'] = collect($dashboard)->whereIn('ireq_status',['RR','RA1','RA2'])->where('ireq_loc','OJ')->count();
        $grafik['sdgdikerjakanjakarta'] = collect($dashboard)->where('ireq_status','T')->where('ireq_loc','OJ')->count();
        $grafik['sdhdikerjakanjakarta'] = collect($dashboard)->where('ireq_status','D')->where('ireq_loc','OJ')->count();
        $grafik['sdhselesaijakarta'] = collect($dashboard)->where('ireq_status','C')->where('ireq_loc','OJ')->count();
        $grafik['penugasanRequestjakarta'] = collect($dashboard)->whereIn('ireq_status',['NT','RT'])->where('ireq_loc','OJ')->count();
        $grafik['totalRequestjakarta'] = collect($dashboard)->whereNotNull('ireq_status')->where('ireq_loc','OJ')->count();
        return ResponseFormatter::success($grafik,'Successfully Get Data Dashboard');
    }
    function countReviewerKurau()
    {
        $dashboard = $this->dashboardServices->CountDataDashboard();
        $grafik['blmDiverifikasikurau'] = collect($dashboard)->where('ireq_status','P')->where(function ($query){ return $query ->where('ireq_loc','OK')->OrWhere('ireq_loc','FK');})->count();
        $grafik['atasandivisikurau'] = collect($dashboard)->where(function ($query){return $query ->where('ireq_status','NA1')->orwhere('ireq_status','A1');})->where('ireq_loc','OK')->OrWhere('ireq_loc','FK')->count();
        $grafik['managerkurau'] = collect($dashboard)->where(function ($query){return $query ->where('ireq_status','NA2')->orwhere('ireq_status','A2');})->where('ireq_loc','OK')->OrWhere('ireq_loc','FK')->count();
        $grafik['rejectkurau'] = collect($dashboard)->where(function ($query){return $query ->where('ireq_status','RR')->orwhere('ireq_status','RA1')->orwhere('ireq_status','RA2');})->where(function ($query){ return $query ->where('ireq_loc','OK')->OrWhere('ireq_loc','FK');})->count();
        $grafik['sdgdikerjakankurau'] = collect($dashboard)->where('ireq_status','T')->where(function ($query){return $query ->where('ireq_loc','OK')->OrWhere('ireq_loc','FK');})->count();
        $grafik['sdhdikerjakankurau'] = collect($dashboard)->where('ireq_status','D')->where(function ($query){return $query ->where('ireq_loc','OK')->OrWhere('ireq_loc','FK');})->count();
        $grafik['sdhselesaikurau'] = collect($dashboard)->where('ireq_status','C')->where(function ($query){return $query ->where('ireq_loc','OK')->OrWhere('ireq_loc','FK');})->count();
        $grafik['penugasanRequestkurau'] = collect($dashboard)->where(function ($query){return $query ->where('ireq_status','NT')->orwhere('ireq_status','RT');})->where('ireq_loc','OK')->OrWhere('ireq_loc','FK')->count();
        $grafik['totalRequestkurau'] = collect($dashboard)->where(function ($query){ return $query ->where('ireq_loc','OK')->orwhere('ireq_loc','FK');})->whereNotNull('ireq_status')->count();

        return ResponseFormatter::success($grafik,'Successfully Get Data Dashboard');
    }
    function countReviewerBentu()
    {
        $dashboard = $this->dashboardServices->CountDataDashboard();
        $grafik['blmDiverifikasibentu'] = collect($dashboard)->where('ireq_status','P')->where(function ($query){ return $query ->where('ireq_loc','OB')->OrWhere('ireq_loc','FB');})->count();
        $grafik['atasandivisibentu'] = collect($dashboard)->where(function ($query){return $query ->where('ireq_status','NA1')->orwhere('ireq_status','A1');})->where('ireq_loc','OB')->OrWhere('ireq_loc','FB')->count();
        $grafik['managerbentu'] = collect($dashboard)->where(function ($query){return $query ->where('ireq_status','NA2')->orwhere('ireq_status','A2');})->where('ireq_loc','OB')->OrWhere('ireq_loc','FB')->count();
        $grafik['rejectbentu'] = collect($dashboard)->where(function ($query){return $query ->where('ireq_status','RR')->orwhere('ireq_status','RA1')->orwhere('ireq_status','RA2');})->where(function ($query){ return $query ->where('ireq_loc','OB')->OrWhere('ireq_loc','FB');})->count();
        $grafik['sdgdikerjakanbentu'] = collect($dashboard)->where('ireq_status','T')->where(function ($query){return $query ->where('ireq_loc','OB')->OrWhere('ireq_loc','FB');})->count();
        $grafik['sdhdikerjakanbentu'] = collect($dashboard)->where('ireq_status','D')->where(function ($query){return $query ->where('ireq_loc','OB')->OrWhere('ireq_loc','FB');})->count();
        $grafik['sdhselesaibentu'] = collect($dashboard)->where('ireq_status','C')->where(function ($query){return $query ->where('ireq_loc','OB')->OrWhere('ireq_loc','FB');})->count();
        $grafik['penugasanRequestbentu'] = collect($dashboard)->where(function ($query){return $query ->where('ireq_status','NT')->orwhere('ireq_status','RT');})->where('ireq_loc','OB')->OrWhere('ireq_loc','FB')->count();
        $grafik['totalRequestbentu'] = collect($dashboard)->where(function ($query){ return $query ->where('ireq_loc','OB')->orwhere('ireq_loc','FB');})->whereNotNull('ireq_status')->count();
        
        return ResponseFormatter::success($grafik,'Successfully Get Data Dashboard');
    }
    function countPersonnel()
    {
        $data = $this->dashboardServices->countPersonnel();
        $grafik['penugasanrequestPersonnel'] = collect($data)->where('ireq_status','NT')->where('ireq_assigned_to1',Auth::user()->usr_id)->count();
        $grafik['rejectedPersonnel'] = collect($data)->where('ireq_status','RT')->where('ireq_assigned_to1',Auth::user()->usr_id)->count();
        $grafik['belumselesaiPersonnel'] = collect($data)->where('ireq_status','T')->where(DB::raw('COALESCE(ireq_dtl.ireq_assigned_to2,ireq_dtl.ireq_assigned_to1)'),Auth::user()->usr_id)->count();
        $grafik['sudahdikerjakanPersonnel'] = collect($data)->where('ireq_status','D')->where(DB::raw('COALESCE(ireq_dtl.ireq_assigned_to2,ireq_dtl.ireq_assigned_to1)'),Auth::user()->usr_id)->count();
        $grafik['sudahselesaiPersonnel'] = collect($data)->where('ireq_status','C')->where(DB::raw('COALESCE(ireq_dtl.ireq_assigned_to2,ireq_dtl.ireq_assigned_to1)'),Auth::user()->usr_id)->count();
        
        return ResponseFormatter::success($grafik,'Successfully Get Data Dashboard');
    }
    function countIctManager()
    {
        $dashboard = $this->dashboardServices->CountDataDashboard();
        $grafik['blmdiverifikasimanager'] = collect($dashboard)->whereIN('ireq_status',['NA1','NA2'])->count();
        $grafik['sudahdiverifikasimanager'] = collect($dashboard)->whereIN('ireq_status',['A1','A2'])->count();
        $grafik['sedangdireviewmanager'] = collect($dashboard)->where('ireq_status','P')->count();
        $grafik['direjectmanager'] = collect($dashboard)->whereIN('ireq_status',['RA2','RR','RA1'])->count();
        $grafik['penugasanrequestmanager'] = collect($dashboard)->whereIN('ireq_status',['NT','RT'])->count();
        $grafik['sedangdikerjakanmanager'] = collect($dashboard)->where('ireq_status','T')->count();
        $grafik['sudahdikerjakanmanager'] = collect($dashboard)->where('ireq_status','D')->count();
        $grafik['sudahselesaimanager'] = collect($dashboard)->where('ireq_status','C')->count();
        $grafik['totalrequestmanager'] = collect($dashboard)->whereNotNull('ireq_status')->count();
        
        return ResponseFormatter::success($grafik,'Successfully Get Data Dashboard');
    }
    function countAdmin()
    {
        $dashboard = $this->dashboardServices->CountDataDashboard();
        $grafik['belumdiverifikasiadmin'] = collect($dashboard)->whereIN('ireq_status',['P','NA1','NA2'])->count();
        $grafik['sudahdiverifikasiadmin'] = collect($dashboard)->whereIN('ireq_status',['A1','A2'])->count();
        $grafik['direjectadmin'] = collect($dashboard)->whereIN('ireq_status',['RA2','RR','RA1'])->count();
        $grafik['sudahdikerjakanadmin'] = collect($dashboard)->whereIN('ireq_status',['NT','RT'])->count();
        $grafik['sedangdikerjakanadmin'] = collect($dashboard)->where('ireq_status','T')->count();
        $grafik['sudahdikerjakanadmin'] = collect($dashboard)->where('ireq_status','D')->count();
        $grafik['sudahselesaiadmin'] = collect($dashboard)->where('ireq_status','C')->count();
        $grafik['totalRequestadmin'] = collect($dashboard)->whereNotNull('ireq_status')->count();
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
