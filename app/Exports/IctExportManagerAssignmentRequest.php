<?php

namespace App\Exports;

use Illuminate\Support\Facades\DB;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class IctExportManagerAssignmentRequest implements FromView
{
    
    public function view(): View{
        return view('excel/Laporan_Ict_Sedang_Dikerjakan', [ 'Ict' => DB::table('ireq_mst as im')
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
        ->get()
        ]);
    }
}
