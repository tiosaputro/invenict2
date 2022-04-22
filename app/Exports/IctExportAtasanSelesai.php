<?php

namespace App\Exports;

use DB;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class IctExportAtasanSelesai implements FromView
{
    /**
    * @return \Illuminate\Support\Collection
    */
    function __construct($usr_name){
        $this->usr_name = $usr_name;
    }
    public function view(): View
    {
        return view('excel/Laporan_Ict_Selesai', [ 'Ict' => DB::table('ireq_dtl as id')
        ->select('imm.ireq_no','id.ireq_desc','id.ireq_qty','id.ireq_remark','id.ireqd_id','dr.div_name',
        'imm.ireq_user', 'id.ireq_assigned_to1', 'llr.lookup_desc as ireq_status', 'imm.ireq_requestor',
        'vr.name as ireq_bu','lr.lookup_desc as ireq_type',DB::raw("TO_CHAR(imm.ireq_date,' dd Mon YYYY') as ireq_date"),
        DB::raw("(im.invent_code ||'-'|| im.invent_desc) as name"))
        ->leftjoin('invent_mst as im','id.invent_code','im.invent_code')
        ->leftjoin('lookup_refs as lr','id.ireq_type','lr.lookup_code')
        ->join('ireq_mst as imm','id.ireq_id','imm.ireq_id')        
        ->leftjoin('lookup_refs as llr','imm.ireq_status','llr.lookup_code')
        ->leftjoin('vcompany_refs as vr','imm.ireq_bu','vr.company_code')
        ->leftjoin('divisi_refs as dr','imm.ireq_divisi_user','dr.div_id')
        ->where('id.ireq_status','C')
        ->where('dr.div_verificator',$this->usr_name)
        ->whereRaw('LOWER(lr.lookup_type) LIKE ? ',[trim(strtolower('req_type')).'%'])
        ->whereRaw('LOWER(llr.lookup_type) LIKE ? ',[trim(strtolower('ict_status')).'%'])
        ->orderBy('id.creation_date','ASC')
        ->get()
        ]);
    }
}
