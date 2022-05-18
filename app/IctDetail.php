<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class IctDetail extends Model
{
    protected $fillable = [
        'ireqd_id',
        'ireq_id',
        'ireq_type',
        'invent_code',
        'ireq_desc',
        'ireq_qty',
        'ireq_status',
        'ireq_reason',
        'ireq_assigned_to1',
        'ireq_assigned_to1_reason',
        'ireq_assigned_to2',
        'ireq_assigned_date',
        'ireq_remark',
        'creation_date',
        'created_by',
        'last_update_date',
        'last_updated_by',
        'program_name',
        'ireq_value',
        'ireq_note',
        'ireq_note_personnel'
    ];
    protected $table = 'ireq_dtl';
    protected $primaryKey = 'ireqd_id';
    public $timestamps = false;
}
