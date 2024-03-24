<?php

namespace App\Exports;

use Illuminate\Support\Facades\DB;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class LaporanReqPerPersonnel implements FromView
{
    
    public function view(): View{
        return view('excel/Laporan_Req_Per_Personnel', [ 'status'=> DB::table('ireq_dtl')
        ->select('ireq_assigned_to1',DB::raw("count(ireqd_id) as jumlah"))
        ->whereNotNull('ireq_assigned_to1')
        ->groupBy('ireq_assigned_to1')
        ->get()
        ]);
    }
}
