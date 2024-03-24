<?php

namespace App\Exports;

use Illuminate\Support\Facades\DB;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class IctExportManagerPermohonan implements FromView
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function view(): View{
        return view('excel/Laporan_Ict_Permohonan', [ 'Ict' => DB::table('ireq_mst as im')
        ->SELECT('im.ireq_id','im.ireq_no','im.ireq_date','im.ireq_user','im.ireq_requestor',
        'dr.div_name','lr.lookup_desc as ireq_status','vr.name as ireq_bu',DB::raw("TO_CHAR(im.ireq_date,' dd Mon YYYY') as ireq_date"))
        ->LEFTJOIN('divisi_refs as dr','im.ireq_divisi_user','dr.div_id')
        ->LEFTJOIN('vcompany_refs as vr','im.ireq_bu','vr.company_code')
        ->LEFTJOIN('lookup_refs as lr','im.ireq_status','lr.lookup_code')
        ->WHERE(function($query){
            return $query
            ->WHERE('im.ireq_status','NA2')
            ->Orwhere('im.ireq_status','NA1')
            ->Orwhere('im.ireq_status','P');
        })
        ->WHERERaw('LOWER(lr.lookup_type) LIKE ? ',[trim(strtolower('ict_status')).'%'])
        ->ORDERBY('im.ireq_date','DESC')
        ->get()
        ]);
    }
}
