<?php

namespace App\Exports;

use Illuminate\Support\Facades\DB;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class IctExportPermohonanAtasan implements FromView
{
    /**
    * @return \Illuminate\Support\Collection
    */
    function __construct($usr_email) {
        $this->usr_email = $usr_email;
    }
    public function view(): View{
        return view('excel/Laporan_Ict_Permohonan', [ 'Ict' => 
        DB::table('ireq_mst as im')
        ->SELECT('dr.div_name','im.ireq_id','im.ireq_no',DB::raw("TO_CHAR(im.ireq_date,' dd Mon YYYY') as ireq_date"),'im.ireq_status as ireq_statuss','im.ireq_user',
        'im.ireq_requestor','lr.lookup_desc as ireq_status','vr.name as ireq_bu')
        ->LEFTJOIN('vcompany_refs as vr','im.ireq_bu','vr.company_code')
        ->LEFTJOIN('divisi_refs as dr','im.ireq_divisi_user','dr.div_id')
        ->LEFTJOIN('lookup_refs as lr','im.ireq_status','lr.lookup_code')
        ->WHERE('dr.div_verificator',$this->usr_email)
        ->WHERERaw('LOWER(lr.lookup_type) LIKE ? ',[trim(strtolower('ict_status')).'%'])
        ->WHERE(function($query){
            return $query
            ->WHERE('im.ireq_status','NA1')
            ->orwhere('im.ireq_status','NA2')
            ->orwhere('im.ireq_status','P');
        })
        ->groupBy('dr.div_name','vr.name','im.ireq_id','im.ireq_no','im.ireq_date','im.ireq_status','im.ireq_user','im.creation_date','im.ireq_requestor','lr.lookup_desc')
        ->ORDERBY('im.ireq_date','DESC')
        ->get()
        ]);
    }
}
