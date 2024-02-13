<?php

namespace App\Services;

use App\Models\Ict;

class DashboardServices
{
    public function CountDataDashboard(){
        $data = Ict::All();
        return $data;
    }
}