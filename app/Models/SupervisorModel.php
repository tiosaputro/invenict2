<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SupervisorModel extends Model
{
    protected $fillable = [
        'spv_id',
        'spv_name',
        'spv_job_title',
        'create_date',
        'created_by',
        'last_update_date',
        'last_updated_by',
    ];
    protected $table='supervisor_refs';
    protected $primaryKey='spv_id';
    public $incrementing = false;
    public $timestamps = false;
}
