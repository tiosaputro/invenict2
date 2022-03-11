<?php

namespace App\Exports;

use DB;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class LaporanReqDivUserPerBulan implements FromView
{
    /**
    * @return \Illuminate\Support\Collection
    */
    function __construct($tahunnUser,$bulanUser) {
        $this->tahunnUser = $tahunnUser;
        $this->bulanUser = $bulanUser;
    }
    public function view(): View
    {
        return view('excel/Laporan_Req_Div_User_Per_Bulan', [ 'status'=> DB::table('ireq_mst as im')
        ->leftjoin('divisi_refs as dr','im.ireq_divisi_user','dr.div_id')
        ->select('dr.div_name',DB::raw("count(im.ireq_id) as jumlah"), DB::raw("TO_CHAR(im.ireq_date,'Month') as bulan"),
                DB::raw("TO_CHAR(im.ireq_date,'YYYY') as Tahun"))
        ->whereYear('im.ireq_date',$this->tahunnUser)
        ->whereMonth('im.ireq_date',$this->bulanUser)
        ->orderBy('dr.div_name','ASC')
        ->groupBy('dr.div_name', DB::raw("TO_CHAR(im.ireq_date,'Month')"),DB::raw("TO_CHAR(im.ireq_date,'YYYY')"))
        ->get()
        ]);
    }
}
