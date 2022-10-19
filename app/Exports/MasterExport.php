<?php

namespace App\Exports;

use Illuminate\Support\Facades\DB;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class MasterExport implements FromView
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function view(): View
    {
        return view('excel/Laporan_Master',['Master'=> DB::table('invent_mst as im')
        ->select('im.invent_code','im.invent_desc','im.invent_type','lrs.lookup_desc as invent_brand')
        ->LEFTJOIN('lookup_refs as lrs',function ($join) {
            $join->on('im.invent_brand','lrs.lookup_code')
                  ->WHERERaw('LOWER(lrs.lookup_type) LIKE ? ',[trim(strtolower('merk')).'%']);
        })
        ->leftjoin('vcompany_refs', function($join)
        {
            $join->on('im.invent_bu','=','vcompany_refs.company_code');
        })
        ->orderBy('im.invent_code','ASC')
        ->get()
        ]);    
    }
}
