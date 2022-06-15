<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Payment_request extends Model
{
    protected $fillable = [
        'pr_id',
        'ireq_id',
        'ireqd_id',
        'pr_pic_name',
        'pr_submit_date',
        'pr_recv_cash_date',
        'pr_purchase_date',
        'pr_recv_item_date',
        'pr_hand_over_date',
        'pr_settlement_date',
        'creation_date',
        'created_by',
        'last_update_date',
        'last_updated_by',
        'program_name',
    ];
    protected $table= 'pr_mst';
    protected $primaryKey ='pr_id';
    public $incrementing = false;
    public $timestamps = false;
    
}
