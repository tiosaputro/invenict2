<?php

namespace App\Exports;

use DB;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class IctExportManagerSudahSelesai implements FromView
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function view(): View
    {
        return view('excel/Laporan_Ict_Selesai', [ 'Ict' => DB::table('ireq_dtl as id')
        ->LEFTJOIN('ireq_mst as im','id.ireq_id','im.ireq_id')
        ->LEFTJOIN('vcompany_refs as vr','im.ireq_bu','vr.company_code')
        ->LEFTJOIN('divisi_refs as dr','im.ireq_divisi_user','dr.div_id')
        ->LEFTJOIN('lookup_refs as lr',function ($join) {
            $join->on('id.ireq_status','lr.lookup_code')
                  ->WHERERaw('LOWER(lr.lookup_type) LIKE ? ',[trim(strtolower('ict_status')).'%']);
        })
        ->LEFTJOIN('lookup_refs as lrs',function ($join) {
            $join->on('id.ireq_type','lrs.lookup_code')
                  ->WHERERaw('LOWER(lrs.lookup_type) LIKE ? ',[trim(strtolower('req_type')).'%']);
        })
        ->LEFTJOIN('lookup_refs as lrfs',function ($join) {
            $join->on('id.invent_code','lrfs.lookup_code')
                  ->WHERERaw('LOWER(lrfs.lookup_type) LIKE ? ',[trim(strtolower('kat_peripheral')).'%']);
        })
        ->SELECT('vr.name as ireq_bu','im.ireq_no','id.ireq_id','id.ireq_remark',DB::raw("COALESCE(id.ireq_assigned_to2,id.ireq_assigned_to1) AS ireq_assigned_to"),'id.ireqd_id',
        'lr.lookup_desc as ireq_status','lrs.lookup_desc as ireq_type','lrfs.lookup_desc as name',DB::raw("TO_CHAR(im.ireq_date,' dd Mon YYYY') as ireq_date"),'im.ireq_requestor','im.ireq_user',
        'dr.div_name','id.ireq_qty','id.ireq_status as status')
        ->WHERE('id.ireq_status','C')
        ->ORDERBY('im.ireq_date','DESC')
        ->ORDERBY('id.ireqd_id','ASC')
        ->get()
        ]);
    }
}
