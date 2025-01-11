<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Catalog extends Model
{
    protected $fillable = [
        'catalog_id',
        'catalog_name',
        'catalog_desc',
        'catalog_priority',
        'catalog_type',
        'catalog_request_type',
        'catalog_stat',
        'parent_id',
        'creation_date',
        'created_by',
        'last_update_date',
        'last_updated_by',
        'program_name',
    ];
    protected $table= 'catalog_refs';
    protected $primaryKey ='catalog_id';
    public $incrementing = false;
    public $timestamps = false;
    
}
