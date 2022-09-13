<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Catalog;
use DB;
use Carbon\Carbon;
use Auth;

class CatalogController extends Controller
{
    function __construct(){
        $date = Carbon::now();
        $this->newCreation =Carbon::parse($date)->copy()->tz('Asia/Jakarta')->format('Y-m-d H:i:s');
        $this->newUpdate = Carbon::parse($date)->copy()->tz('Asia/Jakarta')->format('Y-m-d H:i:s');
    }
    function index(){
        $catalog = Catalog::select('catalog_id','catalog_name','catalog_desc',DB::raw("CASE WHEN catalog_request_type = 'P' Then 'Peripheral' WHEN catalog_request_type = 'S' Then 'Service' end as catalog_request_type"))
        ->get();
        return json_encode($catalog);
    }
    function save(Request $request){
        $message = [
            'catalog_name.required'=>'Catalog Name Not Filled',
            'catalog_stat.required'=>'Catalog Stat Not Filled',
            'catalog_type.required'=>'Catalog Type Not Filled',
            'catalog_request_type.required'=>'Catalog Request Type Not Filled'
        ];
        $request->validate([
            'catalog_name' => 'required',
            'catalog_type'=>'required',
            'catalog_stat'=>'required',
            'catalog_request_type'=>'required',
        ],$message);
        $catalog = Catalog::create([
            'catalog_name'=>$request->catalog_name,
            'catalog_type'=>$request->catalog_type,
            'catalog_desc'=>$request->catalog_desc,
            'catalog_stat'=>$request->catalog_stat,
            'catalog_request_type'=>$request->catalog_request_type,
            'parent_id'=>$request->parent_id,
            'created_by'=>Auth::user()->usr_name,
            'creation_date'=>$this->newCreation,
            'program_name'=>'catalogController@save'
        ]);
        $msg = [
            'success' => true,
            'message' => 'Created Successfully'
        ];
        return response()->json($msg);
    }
    function edit($code){
        $catalog = Catalog::find($code);
        return json_encode($catalog);
    }
    function update(Request $request, $code){
        $message = [
            'catalog_name.required'=>'Catalog Name Not Filled',
            'catalog_stat.required'=>'Catalog Stat Not Filled',
            'catalog_type.required'=>'Catalog Type Not Filled',
            'catalog_request_type.required'=>'Catalog Request Type Not Filled'
        ];
        $request->validate([
            'catalog_name' => 'required',
            'catalog_type'=>'required',
            'catalog_stat'=>'required',
            'catalog_request_type'=>'required',
        ],$message);

        $cat = Catalog::find($code);
        $cat->catalog_name = $request->catalog_name;
        $cat->catalog_type=$request->catalog_type;
        $cat->catalog_desc=$request->catalog_desc;
        $cat->catalog_stat=$request->catalog_stat;
        $cat->catalog_request_type=$request->catalog_request_type;
        $cat->parent_id=$request->parent_id;
        $cat->created_by=Auth::user()->usr_name;
        $cat->creation_date=$this->newCreation;
        $cat->program_name='catalogController@save';
        $cat->save();
        $msg = [
            'success' => true,
            'message' => 'Updated Successfully'
        ];
        return response()->json($msg);
    }
    function delete($catalog_id){
        $catalog = Catalog::find($catalog_id);
        $catalog->delete();
        return json_encode('Success Deleted');
    }
    function parentCatalog(){
        $catalog = Catalog::select('catalog_name as name','catalog_id as code')->where('catalog_type','N')->get();
        return json_encode($catalog);
    }
    function CatalogRequest($tipereq){
        $catalog = Catalog::select('parent_id','catalog_id','catalog_name',
            DB::raw("CASE WHEN catalog_type = 'N' Then 'false' WHEN catalog_type = 'L' Then 'true' end as catalog_type"))
        ->where('catalog_request_type',$tipereq)
        ->where('catalog_stat','T')
        ->get();
        $tree = $this->parseTree($catalog);
        return json_encode($tree);
    }
    function parseTree($tree, $root = 0){
        $return = array();
          foreach($tree as $child => $parent) {
            if($parent->parent_id == $root) {
              unset($tree[$child]);
                $return[] = array(
                    'key'=> $parent->catalog_id,
                    'label'     => $parent->catalog_name,
                    'selectable'  => filter_var($parent->catalog_type, FILTER_VALIDATE_BOOLEAN),
                    'children'     => $this->parseTree($tree, $parent->catalog_id)
                );
            }
        }
        return empty($return) ? null : $return;    
    }

}
