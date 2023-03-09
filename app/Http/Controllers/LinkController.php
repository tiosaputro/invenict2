<?php

namespace App\Http\Controllers;

use App\Model\Link;
class LinkController extends Controller
{
    public function cekVerif($code)
    {
        $link = Link::findOrFail($code);
        return json_encode($link);
    }
}
