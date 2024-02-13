<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LogUrl extends Model
{
    public $incrementing = false;
    protected $table = "log_url";

    protected $fillable = [
        'id','url','created_by','urltime','created_at', 'updated_at', 'tipe'
    ];

}
