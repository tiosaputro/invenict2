<?php

namespace App\Exports;

use Illuminate\Support\Facades\DB;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class IctExportPersonnelReject implements FromView
{
    function __construct($usr_fullname){
        $this->usr_fullname = $usr_fullname;
    }
    public function view(): View
    {
        return view('excel/Laporan_Ict_Detail_Reject', [ 'Ict' =>DB::table('ireq_dtl as id')
        ->SELECT('imm.ireq_no','id.ireq_assigned_to1_reason','imm.ireq_id','id.ireq_assigned_remark','id.ireq_desc','id.ireq_qty','id.ireq_remark','id.ireqd_id','dr.div_name',
            'imm.ireq_user', DB::raw("COALESCE(id.ireq_assigned_to2,id.ireq_assigned_to1) AS ireq_assigned_to"),'llr.lookup_desc as ireq_status', 'imm.ireq_requestor',
            'vr.name as ireq_bu','lr.lookup_desc as ireq_type','imm.ireq_date','id.ireq_status as status', DB::raw("(crs.catalog_name ||' - '|| cr.catalog_name) as name"))
        ->LEFTJOIN('lookup_refs as lr','id.ireq_type','lr.lookup_code')
        ->LEFTJOIN('catalog_refs as cr',function ($join) {
            $join->on('id.invent_code','cr.catalog_id');
        })
        ->LEFTJOIN('catalog_refs as crs',function ($join) {
            $join->on('cr.parent_id','crs.catalog_id');
        })
        ->LEFTJOIN('ireq_mst as imm','id.ireq_id','imm.ireq_id')        
        ->LEFTJOIN('lookup_refs as llr','id.ireq_status','llr.lookup_code')
        ->LEFTJOIN('vcompany_refs as vr','imm.ireq_bu','vr.company_code')
        ->LEFTJOIN('divisi_refs as dr','imm.ireq_divisi_user','dr.div_id')
        ->WHERE('id.ireq_status','RT')
        ->WHERE('id.ireq_assigned_to1',$this->usr_fullname)
        ->WHERERaw('LOWER(lr.lookup_type) LIKE ? ',[trim(strtolower('req_type')).'%'])
        ->WHERERaw('LOWER(llr.lookup_type) LIKE ? ',[trim(strtolower('ict_status')).'%'])
        ->ORDERBY('imm.ireq_date','DESC')
        ->ORDERBY('id.ireqd_id','ASC')
        ->get()
        ]);
    }
}
