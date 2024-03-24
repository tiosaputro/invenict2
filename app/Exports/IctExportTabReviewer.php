<?php

namespace App\Exports;

use Illuminate\Support\Facades\DB;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use App\Services\IctRequestorServices;

class IctExportTabReviewer implements FromView
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function __construct() {
    }
    public function view(): View{
        $ictRequestorServices = app(IctRequestorServices::class);
        return view('excel/Laporan_Ict_tab_reviewer', 
        [ 
            'Ict' => $ictRequestorServices->getDataWithFilter('NA1','NA2', NULL, NULL, NULL) 
        ]);
    }
}
