<?php

namespace App\Exports;

use Illuminate\Support\Facades\DB;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class IctExportSedangDikerjakan implements FromView
{
    function __construct($usr_name){
        $this->usr_name = $usr_name;
    }
    public function view(): View{
        return view('excel/Laporan_Ict_Sedang_Dikerjakan', [ 'Ict' => DB::table('ireq_mst as im')
        ->SELECT('vr.name as ireq_bu','im.ireq_requestor','im.ireq_id','im.ireq_status as status','im.ireq_no',DB::raw("TO_CHAR(im.ireq_date,' dd Mon YYYY') as ireq_date"),'im.ireq_user','im.ireq_requestor','dr.div_name',DB::raw("COALESCE(im.ireq_assigned_to2,im.ireq_assigned_to1) AS ireq_assigned_to"),'lr.lookup_desc as ireq_status')
        ->LEFTJOIN('divisi_refs as dr','im.ireq_divisi_user','dr.div_id')
        ->LEFTJOIN('vcompany_refs as vr','im.ireq_bu','vr.company_code')
        ->LEFTJOIN('lookup_refs as lr','im.ireq_status','lr.lookup_code')
        ->WHERE('im.ireq_status','T')
        ->WHERERaw('LOWER(lr.lookup_type) LIKE ? ',[trim(strtolower('ict_status')).'%'])
        ->WHERE('im.created_by',$this->usr_name)
        ->groupBy('vr.name','im.ireq_requestor','im.ireq_status','im.ireq_id','im.ireq_no','im.ireq_date','im.ireq_user','im.ireq_requestor','dr.div_name','im.creation_date','lr.lookup_desc',DB::raw("COALESCE(im.ireq_assigned_to2,im.ireq_assigned_to1)"))          
        ->ORDERBY('im.ireq_date','DESC')
        ->get()
        ]);
    }
}
