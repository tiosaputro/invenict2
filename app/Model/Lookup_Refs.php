<?php

namespace App\Model;;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class Lookup_Refs extends Model
{
    protected $fillable = [
        'lookup_code',
        'lookup_type',
        'lookup_desc',
        'lookup_status',
        'creation_date',
        'created_by',
        'last_update_date',
        'last_updated_by',
        'program_name',
    ];
    protected $primaryKey = 'lookup_code';
    protected $table = 'lookup_refs';
    public $incrementing = false;
    public $timestamps = false;

    public static function Merk(){
        $lookup = Lookup_Refs::Select('lookup_code as code','lookup_desc as name')
            ->where('lookup_status','T')
            ->whereRaw('LOWER(lookup_type) LIKE ? ',[trim(strtolower('merk')).'%'])
            ->orderBy('lookup_desc','ASC')
            ->get();
        return $lookup;
    }
    public static function Kondisi(){
        $lookup = Lookup_Refs::Select('lookup_code as code','lookup_desc as name')
            ->where('lookup_status','T')
            ->whereRaw('LOWER(lookup_type) LIKE ? ',[trim(strtolower('kondisi')).'%'])
            ->orderBy('lookup_desc','ASC')
            ->get();
        return $lookup;
    }
    public static function StatusIct(){
        $lookup = Lookup_Refs::Select('lookup_code as code','lookup_desc as name')
            ->where('lookup_status','T')
            ->where(function($query){
                return $query
                ->where('lookup_code','D')
                ->orwhere('lookup_code','T');
            })
            ->whereRaw('LOWER(lookup_type) LIKE ? ',[trim(strtolower('ict_status')).'%'])
            ->orderBy('lookup_desc','DESC')
            ->get();
        return $lookup;
    }
    
    public static function Type(){
        $lookup = Lookup_Refs::Select('lookup_code as code','lookup_desc as name')
            ->where('lookup_status','T')
            ->whereRaw('LOWER(lookup_type) LIKE ? ',[trim(strtolower('req_type')).'%'])
            ->orderBy('lookup_desc','ASC')
            ->get();
        return $lookup;
    }
    public static function Kategori_peripheral(){
        $lookup = Lookup_Refs::Select('lookup_code as code','lookup_desc as name')
        ->where('lookup_status','T')
        ->whereRaw('LOWER(lookup_type) LIKE ? ',[trim(strtolower('kat_peripheral')).'%'])
        ->orderBy('lookup_desc','ASC')
        ->get();
        return $lookup;
    }
    public static function Valuta(){
        $lookup = Lookup_Refs::Select('lookup_code as code',DB::raw("(lookup_code ||'-'|| lookup_desc) as name"))
        ->where('lookup_status','T')
        ->whereRaw('LOWER(lookup_type) LIKE ? ',[trim(strtolower('mata uang')).'%'])
        ->orderBy('lookup_desc','ASC')
        ->get();
        return $lookup;
    }
    
    public static function Pay_Methode(){
        $lookup = Lookup_Refs::Select('lookup_code as code','lookup_desc as name')
        ->where('lookup_status','T')
        ->whereRaw('LOWER(lookup_type) LIKE ? ',[trim(strtolower('pay methode')).'%'])
        ->orderBy('lookup_desc','ASC')
        ->get();
        return $lookup;
    }
    public static function Prioritas(){
        $lookup = Lookup_Refs::Select('lookup_code as code','lookup_desc as name')
        ->where('lookup_status','T')
        ->whereRaw('LOWER(lookup_type) LIKE ? ',[trim(strtolower('req_prio')).'%'])
        ->orderBy('lookup_desc','ASC')
        ->get();
        return $lookup;
    }
}
