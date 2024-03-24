<?php

namespace App\Http\Controllers;

use App\Models\Link;
class LinkController extends Controller
{
    function cekVerif($code){
        $link = Link::findOrFail($code);
        return json_encode($link);
    }
}
