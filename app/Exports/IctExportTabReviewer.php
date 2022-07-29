<?php

namespace App\Exports;

use DB;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class IctExportTabReviewer implements FromView
{
    /**
    * @return \Illuminate\Support\Collection
    */
    function __construct($usr_name) {
        $this->usr_name = $usr_name;
    }
    public function view(): View
    {
        return view('excel/Laporan_Ict_tab_reviewer', [ 'Ict' => DB::table('ireq_mst as im')
        ->LEFTJOIN('vcompany_refs as vr','im.ireq_bu','vr.company_code')
        ->LEFTJOIN('lookup_refs as lr','im.ireq_status','lr.lookup_code')
        ->LEFTJOIN('divisi_refs as dr','im.ireq_divisi_user','dr.div_id')
        ->SELECT('vr.name as ireq_bu','im.ireq_requestor','im.ireq_status as status','dr.div_name','im.ireq_id','im.ireq_no',DB::raw("TO_CHAR(im.ireq_date,' dd Mon YYYY') as ireq_date"),'im.ireq_user','lr.lookup_desc as ireq_status')
        ->WHERE('im.created_by',$this->usr_name)
        ->WHERERaw('LOWER(lr.lookup_type) LIKE ? ',[trim(strtolower('ict_status')).'%'])
        ->WHERE(function($query){
            return $query
            ->WHERE('im.ireq_status','NA1')
            ->orwhere('im.ireq_status','NA2');
        })            
        ->ORDERBY('im.ireq_date','DESC')
        ->get()
        ]);
    }
}
