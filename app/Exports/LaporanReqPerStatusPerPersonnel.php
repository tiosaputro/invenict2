<?php

namespace App\Exports;

use DB;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class LaporanReqPerStatusPerPersonnel implements FromView
{
    function __construct($ictPersonnel) {
        $this->ictPersonnel = $ictPersonnel;
    }
    public function view(): View
    {
        return view('excel/Laporan_Req_Per_Status_Per_Personnel', [ 'status'=> DB::table('ireq_dtl as id')
        ->leftjoin('lookup_refs as lr','id.ireq_status','lr.lookup_code')
        ->select(DB::raw("count(id.ireq_id) as jumlah"),'lr.lookup_desc as status','id.ireq_assigned_to as name')
        ->where('id.ireq_assigned_to',$this->ictPersonnel)
        ->whereRaw('LOWER(lr.lookup_type) LIKE ? ',[trim(strtolower('ict_status')).'%'])
        ->groupBy('lr.lookup_desc','id.ireq_assigned_to',DB::raw("CASE WHEN id.ireq_status = 'P' Then 1 WHEN id.ireq_status = 'NA1' Then
        2 WHEN id.ireq_status = 'NA2' Then 3 WHEN
         id.ireq_status = 'A1' Then 4 WHEN id.ireq_status = 'A2' Then 5
         WHEN id.ireq_status = 'RR' Then 6 WHEN id.ireq_status = 'RA1' Then 7
         WHEN id.ireq_status = 'RA2' THEN 8 WHEN id.ireq_status = 'T' Then 9 
         WHEN id.ireq_status = 'D' Then 10 WHEN id.ireq_status = 'C' Then 11 end "))
        ->orderBy(DB::raw("CASE WHEN id.ireq_status = 'P' Then 1 WHEN id.ireq_status = 'NA1' Then
        2 WHEN id.ireq_status = 'NA2' Then 3 WHEN
         id.ireq_status = 'A1' Then 4 WHEN id.ireq_status = 'A2' Then 5
         WHEN id.ireq_status = 'RR' Then 6 WHEN id.ireq_status = 'RA1' Then 7
         WHEN id.ireq_status = 'RA2' THEN 8 WHEN id.ireq_status = 'T' Then 9 
         WHEN id.ireq_status = 'D' Then 10 WHEN id.ireq_status = 'C' Then 11 end "))
        ->get()
        ]);
    }
}
