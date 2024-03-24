<?php

namespace App\Exports;

use App\Models\Mutasi;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use App\Services\MutasiServices;

class MutasiExport implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Mutasi::All()->map(function ($val) {
            return [
                $val->invent_code,
                $val->invent_desc,
                $val->imutasi_tgl_dari,
            ];
        });
    }
    public function headings(): array
    {
        return [
            'Code',
            'Items',
            'From Date',
        ];
    }
    // public function columnFormats(): array
    // {
    //     return [
    //         'C' => NumberFormat::FORMAT_DATE_YYYYMMDD,
    //     ];
    // }
    // public function view(): View{
    //     return view('excel/Laporan_Mutasi',['Mutasi'=> DB::table('invent_mutasi as im')
    //     ->Select('im.invent_code','im.imutasi_lokasi','im.imutasi_pengguna','im.imutasi_keterangan','imm.invent_desc',
    //                 DB::raw("TO_CHAR(im.imutasi_tgl_dari,' dd Mon YYYY') as imutasi_tgl_dari"),
    //                 DB::raw("TO_CHAR(im.imutasi_tgl_sd,' dd Mon YYYY') as imutasi_tgl_sd"))
    //         ->join('invent_mst as imm','im.invent_code','imm.invent_code')
    //         ->orderBy('im.creation_date','ASC')
    //         ->get()
    // ]);
    // }
}
