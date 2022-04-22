<?php

namespace App\Exports;

use DB;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class IctExportSedangDikerjakan implements FromView
{
    function __construct($usr_name){
        $this->usr_name = $usr_name;
    }
    public function view(): View
    {
        return view('excel/Laporan_Ict_Sedang_Dikerjakan', [ 'Ict' => DB::table('ireq_mst as im')
        ->select('im.ireq_no','im.ireq_user','dr.div_name','im.ireq_requestor','vr.name as ireq_bu','lr.lookup_desc as ireq_type','im.ireq_assigned_to1',
                DB::raw("TO_CHAR(im.ireq_date,' dd Mon YYYY') as ireq_date"))
        ->leftjoin('vcompany_refs as vr','im.ireq_bu','vr.company_code')
        ->leftJoin('lookup_refs as lr',function ($join) {
            $join->on('im.ireq_type','lr.lookup_code')
                  ->whereRaw('LOWER(lr.lookup_type) LIKE ? ',[trim(strtolower('req_type')).'%']);
        })
        ->leftjoin('divisi_refs as dr','im.ireq_divisi_user','dr.div_id')
        ->where('im.ireq_status','T')
        ->where('im.ireq_requestor',$this->usr_name)
        ->orderBy('im.creation_date','ASC')
        ->get()
        ]);
    }
}
