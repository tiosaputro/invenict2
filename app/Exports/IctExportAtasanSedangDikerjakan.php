<?php

namespace App\Exports;

use DB;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class IctExportAtasanSedangDikerjakan implements FromView
{
    function __construct($usr_email){
        $this->usr_email = $usr_email;
    }
    public function view(): View
    {
        return view('excel/Laporan_Ict_Sedang_Dikerjakan', [ 'Ict' => DB::table('ireq_mst as im')
        ->SELECT('dr.div_name','im.ireq_id','im.ireq_no','im.ireq_date','im.ireq_status as ireq_statuss','im.ireq_user',
        'im.ireq_requestor','lr.lookup_desc as ireq_status','vr.name as ireq_bu',DB::raw("COALESCE(im.ireq_assigned_to2,im.ireq_assigned_to1) AS ireq_assigned_to"))
        ->LEFTJOIN('vcompany_refs as vr','im.ireq_bu','vr.company_code')
        ->LEFTJOIN('divisi_refs as dr','im.ireq_divisi_user','dr.div_id')
        ->LEFTJOIN('lookup_refs as lr','im.ireq_status','lr.lookup_code')
        ->WHERE('dr.div_verificator',$this->usr_email)
        ->WHERERaw('LOWER(lr.lookup_type) LIKE ? ',[trim(strtolower('ict_status')).'%'])
        ->WHERE('im.ireq_status','T')
        ->groupBy(DB::raw("COALESCE(im.ireq_assigned_to2,im.ireq_assigned_to1)"),'dr.div_name','vr.name','im.ireq_id','im.ireq_no','im.ireq_date','im.ireq_status','im.ireq_user','im.creation_date','im.ireq_requestor','lr.lookup_desc')
        ->ORDERBY('im.ireq_date','DESC')
        ->get()
        ]);
    }
}
