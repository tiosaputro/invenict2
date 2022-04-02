<?php

namespace App\Exports;

use DB;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class IctExportVerifikasi implements FromView
{
    /**
    * @return \Illuminate\Support\Collection
    */
    function __construct($usr_name) {
        $this->usr_name = $usr_name;
    }
    public function view(): View
    {
        return view('excel/Laporan_Ict_Verifikasi', [ 'Ict' => DB::table('ireq_mst as im')
        ->select('im.ireq_no','dr.div_name','im.ireq_user','im.ireq_requestor','vr.name as ireq_bu','lr.lookup_desc as ireq_type','llr.lookup_desc as ireq_status',
                DB::raw("TO_CHAR(im.ireq_date,' dd Mon YYYY') as ireq_date"))
        ->leftjoin('vcompany_refs as vr','im.ireq_bu','vr.company_code')
        ->leftjoin('divisi_refs as dr','im.ireq_divisi_user','dr.div_id')
        ->leftJoin('lookup_refs as lr',function ($join) {
            $join->on('im.ireq_type','lr.lookup_code')
                  ->whereRaw('LOWER(lr.lookup_type) LIKE ? ',[trim(strtolower('req_type')).'%']);
        })
        ->leftjoin('lookup_refs as llr','im.ireq_status','llr.lookup_code')
        ->where('im.ireq_requestor',$this->usr_name)
        ->where(function($query){
            return $query
            ->where('im.ireq_status','A1')
            ->orwhere('im.ireq_status','A2');
        })
        ->whereRaw('LOWER(llr.lookup_type) LIKE ? ',[trim(strtolower('ict_status')).'%'])
        ->orderBy('im.creation_date','ASC')
        ->get()
        ]);
    }
}
