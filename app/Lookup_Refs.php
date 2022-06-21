<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

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

}
