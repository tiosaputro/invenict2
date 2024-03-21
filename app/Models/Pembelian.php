<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
class Pembelian extends Model
{
    protected $fillable = [
        'purchase_id',
        'purchase_date',
        'purchase_petugas',
        'suplier_code',
        'purchase_pay_methode',
        'valuta_code',
        'purchase_rate',
        'purchase_total',
        'purchase_total_rp',
        'purchase_status',
        'purchase_remark',
        'creation_date',
        'created_by',
        'last_update_date',
        'last_update_by',
        'program_name',
    ];
    protected $table='purchase_mst';
    protected $primaryKey='purchase_id';
    public $timestamps = false;

    public static function createDataPembelian($request){
        $createData = Pembelian::create([
            'purchase_date'=>Carbon::createFromFormat('D M d Y H:i:s e+',$request->purch_date)->copy()->tz('Asia/Jakarta')->format('Y-m-d'),
            'purchase_petugas'=>$request->petugas,
            'suplier_code'=>$request->supp,
            'purchase_pay_methode'=>$request->pay,
            'valuta_code'=> $request->money,
            'purchase_remark'=>$request->ket,
            'creation_date'=> now(),
            'created_by'=> Auth::user()->usr_id,
            'program_name'=> "Pembelian_Save"
        ]);
        return $createData;
    }
}
