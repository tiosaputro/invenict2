<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    protected $fillable = [
        'loc_code',
        'loc_desc',
        'loc_email',
        'creation_date',
        'created_by',
        'last_update_date',
        'updated_by',
        'program_name'

    ];
    protected $table= 'location_refs';
    protected $primaryKey ='loc_code';
    public $incrementing = false;
    public $timestamps = false;

    public static function listLocation(){
        $loc = Location::select('loc_code as code','loc_desc as name')->orderBy('loc_desc','ASC')->get();
        return $loc;
    }
}
