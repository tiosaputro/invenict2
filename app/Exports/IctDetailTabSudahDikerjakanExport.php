<?php

namespace App\Exports;

use DB;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class IctDetailTabSudahDikerjakanExport implements FromView
{
    /**
    * @return \Illuminate\Support\Collection
    */
    function __construct($code) {
        $this->code = $code;
    }
    public function view(): View
    {
        return view('excel/Laporan_Ict_Detail_tab_sedang_dikerjakan', [  'detail' => DB::table('ireq_dtl as id')
        ->select('id.*','im.invent_desc','imm.ireq_requestor','imm.ireq_no','llr.lookup_desc as ireq_type',
        'vr.name as ireq_bu',DB::raw("TO_CHAR(imm.ireq_date,' dd Mon YYYY') as ireq_date"),
        'lr.lookup_desc as ireq_status', 'lr.lookup_desc as ireqq_status',DB::raw("(im.invent_code ||'-'|| im.invent_desc) as name"))
        ->leftjoin('invent_mst as im','id.invent_code','im.invent_code')
        ->leftjoin('ireq_mst as imm','id.ireq_id','imm.ireq_id')
        ->leftjoin('lookup_refs as llr','id.ireq_type','llr.lookup_code')
        ->leftjoin('lookup_refs as lr','id.ireq_status','lr.lookup_code')
        ->leftjoin('vcompany_refs as vr','imm.ireq_bu','vr.company_code')
        ->where('id.ireq_id',$this->code)
        ->whereRaw('LOWER(llr.lookup_type) LIKE ? ',[trim(strtolower('req_type')).'%'])
        ->whereRaw('LOWER(lr.lookup_type) LIKE ? ',[trim(strtolower('ict_status')).'%'])
        ->get()
        ]);
    }
}
