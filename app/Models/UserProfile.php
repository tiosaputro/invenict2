<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserProfile extends Model
{
    protected $fillable = [
        'id',
        'user_id',
        'profile_detail',
        'created_by',
        'updated_by',
        'created_at',
        'updated_at',
        'path_photo',
        'phone',
        'user_email',
    ];
    protected $table='user_profile';
    protected $primaryKey='id';
    public $incrementing = false;
}
