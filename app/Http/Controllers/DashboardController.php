<?php
namespace App\Http\Controllers;

use App\Helpers\ResponseFormatter;
use App\Models\Mng_user;
use App\Services\DashboardServices;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    protected $dashboardServices;

    public function __construct(DashboardServices $services)
    {
        $this->dashboardServices = $services;
    }
    public function index()
    {
        $cekrole = Mng_User::roles();
        if ($cekrole->contains('Admin')) {
            $admin = $this->countAdmin();
            return $admin;
        } elseif ($cekrole->contains('Manager')) {
            $manager = $this->countIctManager();
            return $manager;
        } elseif ($cekrole->contains('Reviewer Bentu')) {
            $bentu = $this->countReviewerKurau();
            return $bentu;
        } elseif ($cekrole->contains('Reviewer Kurau')) {
            $kurau = $this->countReviewerKurau();
            return $kurau;
        } elseif ($cekrole->contains('Reviewer Jakarta')) {
            $jakarta = $this->countReviewerJakarta();
            return ($jakarta);
        } elseif ($cekrole->contains('Personnel ICT')) {
            $personel = $this->countPersonnel();
            return ($personel);
        } elseif ($cekrole->contains('Atasan Requestor Divisi')) {
            $higherlevel = $this->countHigherLevel();
            return ($higherlevel);
        } elseif ($cekrole->contains('Requestor Divisi') || ($cekrole->contains('Requestor Divisi'))) {
            $requestor = $this->countUser();
            return ($requestor);
        }
    }

    private function countStatuses($dashboard, $usr_id = null)
    {
        $statuses = [
            'belumdiverifikasi' => ['NA1', 'NA2'],
            'sudahdiverifikasi' => ['A1', 'A2'],
            'sedangdireview'    => ['P'],
            'direject'          => ['RR', 'RA1', 'RA2', 'RT'],
            'sedangdikerjakan'  => ['T', 'NT'],
            'sudahdikerjakan'   => ['D'],
            'sudahselesai'      => ['C'],
            'countrequest'      => null,
        ];

        $grafik = [];
        foreach ($statuses as $key => $status) {
            if ($status) {
                $grafik[$key . 'requestor'] = collect($dashboard)->whereIN('ireq_status', $status)->when($usr_id, function ($query) use ($usr_id) {
                    return $query->where('ireq_requestor', $usr_id);
                })->count();
            } else {
                $grafik[$key . 'requestor'] = collect($dashboard)->whereNotNull('ireq_status')->when($usr_id, function ($query) use ($usr_id) {
                    return $query->where('ireq_requestor', $usr_id);
                })->count();
            }
        }

        return $grafik;
    }

    public function countUser()
    {
        $dashboard = $this->dashboardServices->CountDataDashboard();
        $grafik    = $this->countStatuses($dashboard, Auth::user()->usr_id);

        return ResponseFormatter::success($grafik, 'Successfully Get Data Dashboard');
    }
    public function countHigherLevel()
    {
        $data   = $this->dashboardServices->countHigherLevel();
        $grafik = $this->countStatuses($data);
        return ResponseFormatter::success($grafik, 'Successfully Get Data Dashboard');
    }

    private function countGrafik($dashboard, $location, $locationValues)
    {
        $grafik                     = [];
        $grafik['blmDiverifikasi']  = collect($dashboard)->where('ireq_status', 'P')->whereIn($location, $locationValues)->count();
        $grafik['atasandivisi']     = collect($dashboard)->whereIn('ireq_status', ['NA1', 'A1'])->whereIn($location, $locationValues)->count();
        $grafik['manager']          = collect($dashboard)->whereIn('ireq_status', ['NA2', 'A2'])->whereIn($location, $locationValues)->count();
        $grafik['reject']           = collect($dashboard)->whereIn('ireq_status', ['RR', 'RA1', 'RA2'])->whereIn($location, $locationValues)->count();
        $grafik['sdgdikerjakan']    = collect($dashboard)->where('ireq_status', 'T')->whereIn($location, $locationValues)->count();
        $grafik['sdhdikerjakan']    = collect($dashboard)->where('ireq_status', 'D')->whereIn($location, $locationValues)->count();
        $grafik['sdhselesai']       = collect($dashboard)->where('ireq_status', 'C')->whereIn($location, $locationValues)->count();
        $grafik['penugasanRequest'] = collect($dashboard)->whereIn('ireq_status', ['NT', 'RT'])->whereIn($location, $locationValues)->count();
        $grafik['totalRequest']     = collect($dashboard)->whereNotNull('ireq_status')->whereIn($location, $locationValues)->count();
        return $grafik;
    }

    public function countReviewerJakarta()
    {
        $dashboard = $this->dashboardServices->CountDataDashboard();
        $grafik    = $this->countGrafik($dashboard, 'ireq_loc', ['OJ']);
        return ResponseFormatter::success($grafik, 'Successfully Get Data Dashboard');
    }
    public function countReviewerKurau()
    {
        $dashboard = $this->dashboardServices->CountDataDashboard();
        $grafik    = $this->countGrafik($dashboard, 'ireq_loc', ['OK', 'FK']);
        return ResponseFormatter::success($grafik, 'Successfully Get Data Dashboard');
    }
    public function countReviewerBentu()
    {
        $dashboard = $this->dashboardServices->CountDataDashboard();
        $grafik    = $this->countGrafik($dashboard, 'ireq_loc', ['OB', 'FB']);

        return ResponseFormatter::success($grafik, 'Successfully Get Data Dashboard');
    }
    public function countPersonnel()
    {
        $data = $this->dashboardServices->countPersonnel();
        $grafik['penugasanrequestPersonnel'] = collect($data)
            ->where('ireq_status', 'NT')
            ->where('ireq_assigned_to1', Auth::user()->usr_id)
            ->count();

        $grafik['rejectedPersonnel'] = collect($data)
            ->where('ireq_status', 'RT')
            ->where('ireq_assigned_to1', Auth::user()->usr_id)
            ->count();

        $grafik['belumselesaiPersonnel'] = collect($data)
            ->where('ireq_status', 'T')
            ->filter(function ($item) {
                return ($item->ireq_assigned_to2 ?? $item->ireq_assigned_to1) == Auth::user()->usr_id;
            })
            ->count();

        $grafik['sudahdikerjakanPersonnel'] = collect($data)
            ->where('ireq_status', 'D')
            ->filter(function ($item) {
                return ($item->ireq_assigned_to2 ?? $item->ireq_assigned_to1) == Auth::user()->usr_id;
            })
            ->count();

        $grafik['sudahselesaiPersonnel'] = collect($data)
            ->where('ireq_status', 'C')
            ->filter(function ($item) {
                return ($item->ireq_assigned_to2 ?? $item->ireq_assigned_to1) == Auth::user()->usr_id;
            })
            ->count();
        return ResponseFormatter::success($grafik, 'Successfully Get Data Dashboard');
    }
    public function countIctManager()
    {
        $dashboard                          = $this->dashboardServices->CountDataDashboard();
        $grafik['blmdiverifikasimanager']   = collect($dashboard)->whereIN('ireq_status', ['NA1', 'NA2'])->count();
        $grafik['sudahdiverifikasimanager'] = collect($dashboard)->whereIN('ireq_status', ['A1', 'A2'])->count();
        $grafik['sedangdireviewmanager']    = collect($dashboard)->where('ireq_status', 'P')->count();
        $grafik['direjectmanager']          = collect($dashboard)->whereIN('ireq_status', ['RA2', 'RR', 'RA1'])->count();
        $grafik['penugasanrequestmanager']  = collect($dashboard)->whereIN('ireq_status', ['NT', 'RT'])->count();
        $grafik['sedangdikerjakanmanager']  = collect($dashboard)->where('ireq_status', 'T')->count();
        $grafik['sudahdikerjakanmanager']   = collect($dashboard)->where('ireq_status', 'D')->count();
        $grafik['sudahselesaimanager']      = collect($dashboard)->where('ireq_status', 'C')->count();
        $grafik['totalrequestmanager']      = collect($dashboard)->whereNotNull('ireq_status')->count();

        return ResponseFormatter::success($grafik, 'Successfully Get Data Dashboard');
    }
    public function countAdmin()
    {
        $dashboard                        = $this->dashboardServices->CountDataDashboard();
        $grafik['belumdiverifikasiadmin'] = collect($dashboard)->whereIN('ireq_status', ['P', 'NA1', 'NA2'])->count();
        $grafik['sudahdiverifikasiadmin'] = collect($dashboard)->whereIN('ireq_status', ['A1', 'A2'])->count();
        $grafik['direjectadmin']          = collect($dashboard)->whereIN('ireq_status', ['RA2', 'RR', 'RA1'])->count();
        $grafik['sudahdikerjakanadmin']   = collect($dashboard)->whereIN('ireq_status', ['NT', 'RT'])->count();
        $grafik['sedangdikerjakanadmin']  = collect($dashboard)->where('ireq_status', 'T')->count();
        $grafik['sudahdikerjakanadmin']   = collect($dashboard)->where('ireq_status', 'D')->count();
        $grafik['sudahselesaiadmin']      = collect($dashboard)->where('ireq_status', 'C')->count();
        $grafik['totalRequestadmin']      = collect($dashboard)->whereNotNull('ireq_status')->count();
        return ResponseFormatter::success($grafik, 'Successfully Get Data Dashboard');
    }
    public function getTahun()
    {
        $data['grafik']    = DB::table('VREQ_MST_TAHUN')->get();
        $data['grafik1']   = DB::table('VREQ_MST_STATUS')->get();
        $data['grafik2']   = DB::table('VREQ_MST_BULAN')->get();
        $data['grafik3']   = DB::table('VREQ_PER_STATUS')->get();
        $data['personnel'] = DB::table('ireq_dtl')
            ->select(DB::raw("COALESCE(ireq_assigned_to2,ireq_assigned_to1) AS ireq_assigned_to"), DB::raw("count(ireqd_id) as jumlah"))
            ->whereNotNull('ireq_assigned_to1')
            ->groupBy(DB::raw("COALESCE(ireq_assigned_to2, ireq_assigned_to1)"))
            ->get();
        $data['personnell'] = DB::table('ireq_dtl')
            ->select(DB::raw("COALESCE(ireq_assigned_to2,ireq_assigned_to1) AS name"))
            ->whereNotNull(DB::raw("COALESCE(ireq_assigned_to2,ireq_assigned_to1)"))
            ->groupBy(DB::raw("COALESCE(ireq_assigned_to2,ireq_assigned_to1)"))
            ->get();

        return response()->json($data, 200);
    }
    public function getTahunUser($bulanUser)
    {
        $grafik = DB::table('ireq_mst as im')
            ->select(DB::raw("TO_CHAR(im.ireq_date,'yyyy') as tahun"))
            ->whereMonth('im.ireq_date', $bulanUser)
            ->groupBy(DB::raw("TO_CHAR(im.ireq_date,'yyyy')"))
            ->orderBy(DB::raw("TO_CHAR(im.ireq_date,'yyyy')"), 'DESC')
            ->get();
        return response()->json($grafik);
    }
    public function getTahunRequestor($bulanUser)
    {
        $grafik = DB::table('ireq_mst as im')
            ->select(DB::raw("TO_CHAR(im.ireq_date,'yyyy') as tahun"))
            ->whereMonth('im.ireq_date', $bulanUser)
            ->groupBy(DB::raw("TO_CHAR(im.ireq_date,'yyyy')"))
            ->orderBy(DB::raw("TO_CHAR(im.ireq_date,'yyyy')"), 'DESC')
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
            ->leftjoin('divisi_refs as dr', 'im.ireq_divisi_user', 'dr.div_id')
            ->select('dr.div_name', DB::raw("count(im.ireq_id) as jumlah"))
            ->whereYear('im.ireq_date', $tahunUser)
            ->orderBy('dr.div_name', 'ASC')
            ->groupBy('dr.div_name')
            ->get();
        return response()->json($grafik);
    }
    public function countPerDivRequestorTahun($tahunRequestor)
    {
        $grafik = DB::table('ireq_mst as im')
            ->leftjoin('divisi_refs as dr', 'im.ireq_divisi_requestor', 'dr.div_id')
            ->select('dr.div_name', DB::raw("count(im.ireq_id) as jumlah"))
            ->whereYear('im.ireq_date', $tahunRequestor)
            ->orderBy('dr.div_name', 'ASC')
            ->groupBy('dr.div_name')
            ->get();
        return response()->json($grafik);
    }
    public function countPerDivUserBulan($tahunnUser, $bulanUser)
    {
        $grafik = DB::table('ireq_mst as im')
            ->leftjoin('divisi_refs as dr', 'im.ireq_divisi_user', 'dr.div_id')
            ->select('dr.div_name', DB::raw("count(im.ireq_id) as jumlah"), DB::raw("TO_CHAR(im.ireq_date,'Month') as bulan"))
            ->whereYear('im.ireq_date', $tahunnUser)
            ->whereMonth('im.ireq_date', $bulanUser)
            ->orderBy('dr.div_name', 'ASC')
            ->groupBy('dr.div_name', DB::raw("TO_CHAR(im.ireq_date,'Month')"))
            ->get();
        return response()->json($grafik);
    }
    public function countPerDivRequestorBulan($tahunRequestor, $bulanRequestor)
    {
        $grafik = DB::table('ireq_mst as im')
            ->leftjoin('divisi_refs as dr', 'im.ireq_divisi_requestor', 'dr.div_id')
            ->select('dr.div_name', DB::raw("count(im.ireq_id) as jumlah"),
                DB::raw("TO_CHAR(im.ireq_date,'Month') as bulan"))
            ->whereYear('im.ireq_date', $tahunRequestor)
            ->whereMonth('im.ireq_date', $bulanRequestor)
            ->orderBy('dr.div_name', 'ASC')
            ->groupBy('dr.div_name', DB::raw("TO_CHAR(im.ireq_date,'Month')"))
            ->get();
        return response()->json($grafik);
    }
    public function countPerDivUserStatus($statusUser)
    {
        $grafik = DB::table('ireq_mst as imm')
            ->select('dr.div_name', DB::raw("count(imm.ireq_id) as jumlah"), 'lr.lookup_desc as name')
            ->leftjoin('divisi_refs as dr', 'imm.ireq_divisi_user', 'dr.div_id')
            ->leftjoin('lookup_refs as lr', 'imm.ireq_status', 'lr.lookup_code')
            ->whereRaw('LOWER(lr.lookup_type) LIKE ? ', [trim(strtolower('ict_status')) . '%'])
            ->where('imm.ireq_status', $statusUser)
            ->orderBy('dr.div_name', 'ASC')
            ->groupBy('dr.div_name', 'lr.lookup_desc')
            ->get();
        return response()->json($grafik);
    }
    public function countPerDivRequestorStatus($statusRequestor)
    {
        $grafik = DB::table('ireq_mst as imm')
            ->select('dr.div_name', DB::raw("count(imm.ireq_id) as jumlah"), 'lr.lookup_desc as name')
            ->leftjoin('divisi_refs as dr', 'imm.ireq_divisi_requestor', 'dr.div_id')
            ->leftjoin('lookup_refs as lr', 'imm.ireq_status', 'lr.lookup_code')
            ->orderBy('dr.div_name', 'ASC')
            ->where('imm.ireq_status', $statusRequestor)
            ->whereRaw('LOWER(lr.lookup_type) LIKE ? ', [trim(strtolower('ict_status')) . '%'])
            ->groupBy('dr.div_name', 'lr.lookup_desc')
            ->get();
        return response()->json($grafik);
    }
    public function countPerPersonnel()
    {
        $grafik = DB::table('VREQ_PER_ICTPERSON')->get();
        return response()->json($grafik);
    }
    public function countPerStatusIct($ictPersonnel)
    {
        $grafik = DB::table('ireq_dtl as id')
            ->leftjoin('lookup_refs as lr', 'id.ireq_status', 'lr.lookup_code')
            ->select(DB::raw("count(id.ireq_id) as jumlah"), 'lr.lookup_desc as status')
            ->where(DB::raw("COALESCE(id.ireq_assigned_to2, id.ireq_assigned_to1)"), $ictPersonnel)
            ->whereRaw('LOWER(lr.lookup_type) LIKE ? ', [trim(strtolower('ict_status')) . '%'])
            ->groupBy('lr.lookup_desc', DB::raw("CASE WHEN id.ireq_status = 'P' Then 1 WHEN id.ireq_status = 'NA1' Then 2 WHEN id.ireq_status = 'NA2' Then 3 WHEN
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
    public function getStatus()
    {
        $status = DB::table('VREQ_MST_STATUS')->get();
        return response()->json($status);
    }
}
