<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Mng_User;
use App\Models\SupervisorModel;
use App\Services\SupervisorServices;
use Illuminate\Support\Facades\Auth;

class SupervisorController extends Controller
{
    protected $to;
    protected $userMenu;
    protected $supervisorService;
    function __construct(SupervisorServices $service){
        $this->supervisorService = $service;
        $this->middleware(['auth:sanctum', function ($request, $next) {
            $this->to = "/supervisor-refs";
            $this->userMenu = Mng_User::menu();
            if ($this->userMenu->contains($this->to)) {
                return $next($request);
            } else {
                return response(["message" => "Cannot Access"], 403);
            }
        }]);
    }
    function index(){
        $data['spv'] = $this->supervisorService->getAllData();
        return json_encode($data);
    }
    function addSpv(){
        $data['user'] = $this->supervisorService->addSpv();
        return json_encode($data);
    }
    function store(Request $request){
        if($request->spv_id){
            $spv = SupervisorModel::find($request->spv_id);
            $spv->spv_name = $request->spv_name;
            $spv->spv_job_title = $request->spv_job_title;
            $spv->last_updated_by = Auth::user()->usr_id;
            $spv->last_update_date = now();
            $spv->save();
        }else{
            SupervisorModel::create([
                'spv_id' => generate_id(),
                'spv_name'=>$request->spv_name,
                'spv_job_title' => $request->spv_job_title,
                'create_date' => now(),
                'created_by' => Auth::user()->usr_id
            ]);
        }
        return json_encode('Success');
    }
    function find($id){
        $data['spv'] = SupervisorModel::findOrFail($id);
        $data['user'] = $this->supervisorService->addSpv();
        return json_encode($data);
    }
    function delete($id){
        SupervisorModel::find($id)->delete();
        return json_encode('Success');
    }
}
