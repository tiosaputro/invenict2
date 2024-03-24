<?php

namespace App\Exports;

use Illuminate\Support\Facades\DB;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class IctExportRejectManager implements FromView
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function view(): View{
        return view('excel/Laporan_Ict_Reject', [ 'Ict' => DB::table('ireq_mst as im')
        ->select('im.ireq_no','im.ireq_requestor','im.ireq_reason','vr.name as ireq_bu','im.ireq_user','dr.div_name',
                DB::raw("TO_CHAR(im.ireq_date,' dd Mon YYYY') as ireq_date"),'llr.lookup_desc as ireq_status')
        ->leftjoin('vcompany_refs as vr','im.ireq_bu','vr.company_code')
        ->leftjoin('lookup_refs as llr','im.ireq_status','llr.lookup_code')
        ->leftjoin('divisi_refs as dr','im.ireq_divisi_user','dr.div_id')
        ->where(function($query){
            return $query
            ->where('im.ireq_status','RR')
            ->orwhere('im.ireq_status','RA1')
            ->orwhere('im.ireq_status','RA2');
        })
        ->whereRaw('LOWER(llr.lookup_type) LIKE ? ',[trim(strtolower('ict_status')).'%'])
        ->orderBy('im.ireq_date','ASC')
        ->get()
        ]);
    }
}
