<?php

namespace App\Exports;

use DB;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class IctExportPermohonan implements FromView
{
    /**
    * @return \Illuminate\Support\Collection
    */
    function __construct($usr_name) {
        $this->usr_name = $usr_name;
    }
    public function view(): View
    {
        return view('excel/Laporan_Ict_Permohonan', [ 'Ict' => DB::table('ireq_mst as im')
        ->select('im.ireq_no','im.ireq_user','im.ireq_requestor','vr.name as ireq_bu','lr.lookup_desc as ireq_type','dr.div_name',
                DB::raw("TO_CHAR(im.ireq_date,' dd Mon YYYY') as ireq_date"),'llr.lookup_desc as ireq_status')
        ->leftjoin('vcompany_refs as vr','im.ireq_bu','vr.company_code')
        ->leftjoin('lookup_refs as lr','im.ireq_type','lr.lookup_code')
        ->leftjoin('divisi_refs as dr','im.ireq_divisi_user','dr.div_id')
        ->leftjoin('lookup_refs as llr','im.ireq_status','llr.lookup_code')
        ->where('im.ireq_status','P')
        ->where('im.ireq_requestor',$this->usr_name)
        ->whereRaw('LOWER(lr.lookup_type) LIKE ? ',[trim(strtolower('req_type')).'%'])
        ->whereRaw('LOWER(llr.lookup_type) LIKE ? ',[trim(strtolower('ict_status')).'%'])
        ->get()
        ]);
    }
}
