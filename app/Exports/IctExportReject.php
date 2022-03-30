<?php

namespace App\Exports;

use DB;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class IctExportReject implements FromView
{
    /**
    * @return \Illuminate\Support\Collection
    */
    function __construct($usr_name) {
        $this->usr_name = $usr_name;
    }
    public function view(): View
    {
        return view('excel/Laporan_Ict_Reject', [ 'Ict' => DB::table('ireq_mst as im')
        ->select('im.ireq_no','im.ireq_requestor','im.ireq_user','im.ireq_reason','vr.name as ireq_bu','lr.lookup_desc as ireq_type',
                DB::raw("TO_CHAR(im.ireq_date,' dd Mon YYYY') as ireq_date"),'llr.lookup_desc as ireq_status','dr.div_name')
        ->leftjoin('vcompany_refs as vr','im.ireq_bu','vr.company_code')
        ->leftjoin('lookup_refs as lr','im.ireq_type','lr.lookup_code')
        ->leftjoin('lookup_refs as llr','im.ireq_status','llr.lookup_code')
        ->leftjoin('divisi_refs as dr','im.ireq_divisi_user','dr.div_id')
        ->where('im.ireq_requestor',$this->usr_name)
        ->where(function ($query){
            return $query
            ->where('im.ireq_status','RR')
            ->Orwhere('im.ireq_status','RA1')
            ->Orwhere('im.ireq_status','RA2');
        })
        ->whereRaw('LOWER(lr.lookup_type) LIKE ? ',[trim(strtolower('req_type')).'%'])
        ->whereRaw('LOWER(llr.lookup_type) LIKE ? ',[trim(strtolower('ict_status')).'%'])
        ->orderBy('im.creation_date','ASC')
        ->get()
        ]);
    }
}
