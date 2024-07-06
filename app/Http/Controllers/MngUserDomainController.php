<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UserDomain;
use App\Helpers\ldap_connection;
use App\Helpers\ResponseFormatter;
use App\Services\MngUserDomainServices;
use Illuminate\Support\Facades\Auth;
use App\Models\Mng_User;

class MngUserDomainController extends Controller
{
    protected $domainService;
    protected $to;
    protected $userMenu;
    public function __construct(MngUserDomainServices $usrDomainService){
        $this->domainService = $usrDomainService;
        $this->middleware(['auth:sanctum', function ($request, $next) {
            $this->to = "/user-domain";
            $this->userMenu = Mng_User::menu();
            if ($this->userMenu->contains($this->to)) {
                return $next($request);
            } else {
                return response(["message" => "Cannot Access"], 403);
            }
        }]);
    }
    function index(){
        $data = $this->domainService->getAllData();
        return ResponseFormatter::success($data,'Successfully get data');
    }
    public function update(){
        $ldap = new ldap_connection();
        $filter = "(&(description=User)(|(useraccountcontrol=66048)(useraccountcontrol=512)))"; //filter user enable
        $dataDir = $ldap->getAllData($filter);
        $totalData = count($dataDir);

        if(!empty($dataDir)){
            $this->domainService->deleteDirectory();
            foreach ($dataDir as $key => $row){
                $row['usr_domain'] = str_replace('"', '', $row['userprincipalname']);
                $row['usr_fullname'] = str_replace('"', '', $row['displayname']);
                $row['usr_department'] = str_replace('"', '', $row['department']);
                $row['usr_bu'] = str_replace('"', '', $row['company']);
                $row['usr_division'] = str_replace('"', '', $row['division']);

                $user = new UserDomain();
                $user->id = generate_id();
                $user->usr_domain = $row['usr_domain'];
                $user->usr_fullname = $row['usr_fullname'];
                $user->usr_department = $row['usr_department'];
                $user->usr_division = $row['usr_division'];
                $user->usr_bu = $row['usr_bu'];
                $user->created_by = (Auth::user()) ? Auth::user()->usr_id : 'INVENICT' ;
                $user->created_at = now();
                $user->save();
            }
            return ResponseFormatter::success($totalData,'Successfully update data');
        }
        return ResponseFormatter::success('Tidak ada data yang diperbaharui!');
    }

}
