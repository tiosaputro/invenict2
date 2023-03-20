<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Divisi_refs extends Model
{
    protected $fillable = [
        'div_id',
        'div_code',
        'div_name',
        'div_verificator',
        'div_active',
        'creation_date',
        'created_by',
        'last_updated_by',
        'last_update_date',
        'program_name'
    ];
    protected $table= 'divisi_refs';
    protected $primaryKey ='div_id';
    public $incrementing = false;
    public $timestamps = false;

    public static function ListDivision(){
        $list = Divisi_refs::select('div_id as code',DB::raw("(div_code ||'-'|| div_name) as name"))
        ->orderBy('div_name','ASC')
        ->get();
        return $list;
    }
    public static function Divisi(){
        $list = Divisi_refs::select('div_id','div_code','div_name','div_verificator')
        ->orderBy('div_name','ASC')
        ->get();
        return $list;
    }
}
