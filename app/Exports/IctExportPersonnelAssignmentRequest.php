<?php

namespace App\Exports;

use DB;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class IctExportPersonnelAssignmentRequest implements FromView
{
    function __construct($usr_fullname){
        $this->usr_fullname = $usr_fullname;
    }
    public function view(): View
    {
        return view('excel/Laporan_Ict_Sedang_Dikerjakan', [ 'Ict' => DB::table('ireq_mst as im')
        ->SELECT('im.ireq_no','im.ireq_requestor','vr.name as ireq_bu','im.ireq_reason','im.ireq_user','dr.div_name','im.ireq_assigned_to1 AS ireq_assigned_to',
                DB::raw("TO_CHAR(im.ireq_date,' dd Mon YYYY') as ireq_date"),'llr.lookup_desc as ireq_status')
        ->LEFTJOIN('vcompany_refs as vr','im.ireq_bu','vr.company_code')
        ->LEFTJOIN('ireq_dtl as id','im.ireq_id','id.ireq_id')
        ->LEFTJOIN('lookup_refs as llr','im.ireq_status','llr.lookup_code')
        ->LEFTJOIN('divisi_refs as dr','im.ireq_divisi_user','dr.div_id')
        ->WHERE('id.ireq_status','NT')
        ->WHERERaw('LOWER(llr.lookup_type) LIKE ? ',[trim(strtolower('ict_status')).'%'])
        ->WHERE('id.ireq_assigned_to1',$this->usr_fullname)
        ->ORDERBY('im.ireq_date','DESC')
        ->get()
        ]);
    }
}
