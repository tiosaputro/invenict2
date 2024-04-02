<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserDomain extends Model
{
    protected $fillable = [
        'id',
        'usr_domain',
        'profile_detail',
        'created_by',
        'created_at',
        'usr_fullname',
        'usr_bu',
        'usr_department',
        'usr_division',
    ];
    protected $table='MNG_USER_DOMAIN';
    protected $primaryKey='id';
    public $incrementing = false;
    public $timestamps = false;
}
