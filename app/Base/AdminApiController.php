<?php
namespace App\Base;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

abstract class AdminApiController extends AdminController{
    use Authorizable;
    //number of item in page
    protected $paginateCount = 100;

    public function index(Request $request)
    {
        if($request->wantsJson()){
            return $this->apiIndex($request);
        }else{
            return $this->webIndex($request);
        }
    }
    public function show(Request $request,$id)
    {
        if($request->wantsJson()){
            return $this->apiShow($request,$id);
        }else{
            return $this->webShow($request,$id);
        }
    }
    public function store(Request $request)
    {
        if($request->wantsJson()){
            return $this->apiStore($request);
        }else{
            return $this->webStore($request);
        }
    }
    public function update(Request $request, $id)
    {
        if($request->wantsJson()){
            return $this->apiUpdate($request,$id);
        }else{
            return $this->webUpdate($request,$id);
        }
    }
    public function destroy(Request $request,$id)
    {
        if($request->wantsJson()){
            return $this->apiDestroy($request,$id);
        }else{
            return $this->webDestroy($request,$id);
        }
    }
    abstract function create();
    abstract function edit($id);


    abstract function apiStore(Request $request);
    abstract function webStore(Request $request);
    abstract function apiIndex(Request $request);
    abstract function webIndex(Request $request);
    abstract function apiShow(Request $request,$id);
    abstract function webShow(Request $request,$id);
    abstract function apiUpdate(Request $request,$id);
    abstract function webUpdate(Request $request,$id);
    abstract function apiDestroy(Request $request,$id);
    abstract function webDestroy(Request $request,$id);
}
