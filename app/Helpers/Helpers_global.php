
<?php

use App\Models\LogUrl;
use App\Models\Mng_user;
use Illuminate\Contracts\Session\Session;
use Illuminate\Support\Facades\Auth;

if (!function_exists('generate_id')) {
    function generate_id(){
        return \Illuminate\Support\Str::random(10) . date('his');
    }
}
if (!function_exists('generate_id_number')) {
    function generate_id_number(){
        $random_number = mt_rand(1000000000, 9999999999);
        // Concatenate the random number with the current time in the format 'his' (hour, minute, second)
        return $random_number . date('His');
    }
}
if (!function_exists('formatDate')) {
    function formatDate($date, $date_format){
        if (empty($date)) {
            return '';
        }
        return \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $date)->format($date_format);
    }
}

function insertTimePage($tipe = '')
{
    $actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http') . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
    // $time = time() + microtime(true) - $_SERVER['REQUEST_TIME_FLOAT'];
    $time = microtime(true) - LARAVEL_START;

    // echo (microtime(true) - LARAVEL_START). "<br>";
    // exit(time() . ' || ' . microtime(true) . ' || ' . $_SERVER['REQUEST_TIME_FLOAT']);

    if ($time > 0) {
        $insert = new LogUrl();
        $insert->id = generate_id();
        $insert->tipe = $tipe;
        $insert->url = $actual_link;
        $insert->urltime = $time;
        $insert->created_by = Auth::user()->id;
        $insert->created_at = now();
        $insert->updated_at = now();
        $insert->save();
    }
}

function insertTimePageDetail($detail = '')
{
    $actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http') . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
    // $time = time() + microtime(true) - $_SERVER['REQUEST_TIME_FLOAT'];
    $time = microtime(true) - LARAVEL_START;

    // echo (microtime(true) - LARAVEL_START). "<br>";
    // exit(time() . ' || ' . microtime(true) . ' || ' . $_SERVER['REQUEST_TIME_FLOAT']);

    if ($time > 0) {
        $insert = new LogUrl();
        $insert->id = generate_id();
        $insert->tipe = '-';
        $insert->detail_log = $detail;
        $insert->url = $actual_link;
        $insert->urltime = $time;
        $insert->created_by = Auth::user()->id;
        $insert->created_at = now();
        $insert->updated_at = now();
        $insert->save();
    }
}

function getTimePage()
{
    $time = microtime(true) - LARAVEL_START;
    return $time;
}

function selisihTimePage($angka_1 = 0)
{
    // // $angka_1 -> Waktu Baru
    $lastTime = session('last_time');
    if (empty($lastTime)) {
        $lastTime = 0;
    }
    $selisih = $angka_1 - $lastTime;
    session()->put('last_time', $angka_1);
    return $selisih;
}
