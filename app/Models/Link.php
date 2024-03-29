<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
class Link extends Model
{
    protected $fillable = [
        'link_id',
        'link_action',
        'expired_at',
        'usr_id',
        'ireq_id',
        'created_at',
        'updated_at',
    ];
    protected $table= 'link_dtl';
    protected $primaryKey ='link_id';
    public $incrementing = false;
    public $timestamps = true;

    public static function createLink($usr_id, $ireq_id, $link){
       $save = Link::create([
            'link_id'=> md5(Carbon::now()),
            'link_action'=> $link,
            'expired_at'=>Carbon::parse(Carbon::now()->addDays(1))->copy()->tz('Asia/Jakarta')->format('Y-m-d H:i:s'),
            'usr_id'=>$usr_id,
            'ireq_id'=>$ireq_id,
            'created_at' => now()
        ]);
        return $save;
    }
    public static function createLinkReviewer($ireq_id){
       $save = Link::create([
            'link_id'=> md5(Carbon::now()),
            'link_action'=> 'Ict Request Reviewer Detail Permohonan',
            'ireq_id'=>$ireq_id,
            'created_at' => now()
        ]);
        return $save;
    }
}
