<?php

namespace App\Exports;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class IctExportReviewerIctManager implements FromView
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function view(): View{
        if(Auth::user()->usr_loc == 'OJ'){
            return view('excel/Laporan_Ict_Permohonan', [ 'Ict' => DB::table('ireq_mst as im')
                ->select('im.ireq_no','im.ireq_user','im.ireq_requestor','vr.name as ireq_bu','lr.lookup_desc as ireq_type','dr.div_name',
                        DB::raw("TO_CHAR(im.ireq_date,' dd Mon YYYY') as ireq_date"),'llr.lookup_desc as ireq_status')
                ->leftjoin('vcompany_refs as vr','im.ireq_bu','vr.company_code')
                ->leftJoin('lookup_refs as lr',function ($join) {
                    $join->on('im.ireq_type','lr.lookup_code')
                        ->whereRaw('LOWER(lr.lookup_type) LIKE ? ',[trim(strtolower('req_type')).'%']);
                })
                ->leftjoin('divisi_refs as dr','im.ireq_divisi_user','dr.div_id')
                ->leftjoin('lookup_refs as llr','im.ireq_status','llr.lookup_code')
                ->WHERE(function($query){
                    return $query
                    ->WHERE('im.ireq_status','NA2')
                    ->orwhere('im.ireq_status','A2');
                })
                ->where('im.ireq_loc','OJ')
                ->whereRaw('LOWER(llr.lookup_type) LIKE ? ',[trim(strtolower('ict_status')).'%'])
                ->ORDERBY('im.ireq_date','DESC')
                ->get()
                ]);
        }else if(Auth::user()->usr_loc == 'OB' OR Auth::user()->usr_loc == 'FB' ) {
            return view('excel/Laporan_Ict_Permohonan', [ 'Ict' => DB::table('ireq_mst as im')
                ->select('im.ireq_no','im.ireq_user','im.ireq_requestor','vr.name as ireq_bu','lr.lookup_desc as ireq_type','dr.div_name',
                        DB::raw("TO_CHAR(im.ireq_date,' dd Mon YYYY') as ireq_date"),'llr.lookup_desc as ireq_status')
                ->leftjoin('vcompany_refs as vr','im.ireq_bu','vr.company_code')
                ->leftJoin('lookup_refs as lr',function ($join) {
                    $join->on('im.ireq_type','lr.lookup_code')
                        ->whereRaw('LOWER(lr.lookup_type) LIKE ? ',[trim(strtolower('req_type')).'%']);
                })
                ->leftjoin('divisi_refs as dr','im.ireq_divisi_user','dr.div_id')
                ->leftjoin('lookup_refs as llr','im.ireq_status','llr.lookup_code')
                ->WHERE(function($query){
                    return $query
                    ->WHERE('im.ireq_status','NA2')
                    ->orwhere('im.ireq_status','A2');
                })
                ->WHERE(function ($query){
                    return $query
                    ->WHERE('im.ireq_loc','OB')
                    ->OrWhere('im.ireq_loc','FB');
                })
                ->whereRaw('LOWER(llr.lookup_type) LIKE ? ',[trim(strtolower('ict_status')).'%'])
                ->ORDERBY('im.ireq_date','DESC')
                ->get()
                ]);
            
        }
        else if (Auth::user()->usr_loc == 'OK' OR Auth::user()->usr_loc == 'FK'){
            return view('excel/Laporan_Ict_Permohonan', [ 'Ict' => DB::table('ireq_mst as im')
                ->select('im.ireq_no','im.ireq_user','im.ireq_requestor','vr.name as ireq_bu','lr.lookup_desc as ireq_type','dr.div_name',
                        DB::raw("TO_CHAR(im.ireq_date,' dd Mon YYYY') as ireq_date"),'llr.lookup_desc as ireq_status')
                ->leftjoin('vcompany_refs as vr','im.ireq_bu','vr.company_code')
                ->leftJoin('lookup_refs as lr',function ($join) {
                    $join->on('im.ireq_type','lr.lookup_code')
                        ->whereRaw('LOWER(lr.lookup_type) LIKE ? ',[trim(strtolower('req_type')).'%']);
                })
                ->leftjoin('divisi_refs as dr','im.ireq_divisi_user','dr.div_id')
                ->leftjoin('lookup_refs as llr','im.ireq_status','llr.lookup_code')
                ->WHERE(function($query){
                    return $query
                    ->WHERE('im.ireq_status','NA2')
                    ->orwhere('im.ireq_status','A2');
                })
                ->WHERE(function ($query){
                    return $query
                    ->WHERE('im.ireq_loc','OK')
                    ->OrWhere('im.ireq_loc','FK');
                })
                ->whereRaw('LOWER(llr.lookup_type) LIKE ? ',[trim(strtolower('ict_status')).'%'])
                ->ORDERBY('im.ireq_date','DESC')
                ->get()
                ]);
            }
    }
}
