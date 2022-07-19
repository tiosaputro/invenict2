<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Ict extends Model
{
    protected $fillable= [
        'ireq_id',
        'ireq_no',
        'ireq_date',
        'ireq_type',
        'ireq_requestor',
        'ireq_bu',
        'ireq_approver1',
        'ireq_approver1_date',
        'ireq_verificator',
        'ireq_approver2',
        'ireq_approver2_date',
        'ireq_approver2_remark',
        'ireq_status',
        'ireq_reason',
        'ireq_assigned_to1',
        'ireq_assigned_date',
        'ireq_assigned_to2',
        'ireq_assigned_to1_reason',
        'ireq_user',
        'ireq_divisi_requestor',
        'ireq_divisi_user',
        'ireq_verificator_remark',
        'ireq_prio_level',
        'creation_date',
        'created_by',
        'last_update_date',
        'last_updated_by',
        'program_name',
        'ireq_date_closing',
        'ireq_loc'
    ];
    protected $table ='ireq_mst';
    protected $primaryKey ='ireq_id';
    public $timestamps = false;
}
