<?php

namespace Modules\Aparat\Http\Controllers\Admin;

use App\Base\AdminController;
use App\Base\Authorizable;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\Aparat\Entities\Aparat;

class AparatController extends AdminController
{
    use Authorizable;
    protected $routeBaseName = 'admin.aparat.';

    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        $aparats = Aparat::paginate(20);
        return view('aparat::admin.index',compact('aparats'));
    }

    /**
     * create new content
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        return view('aparat::admin.create',compact('aparats'));
    }

    /**
     * Store new video
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $message = [
            'title.required'   => "لطفا عنوان را وارد نمایید",
            'main_image.required' => "لطفا تصویر مورد نظر را وارد نمایید",
            'content.required' => "لطفا محتوای ویدیو را وارد نمایید",
        ];
        $request->validate([
            'title'    => 'required',
            'main_image' => 'required',
            'content' => 'required'
        ], $message);
        $user = Auth::user();
        $title = $request->title;
        $image = $request->main_image;
        $heroes = new Aparat();
        $heroes->title = $title;
        $heroes->user_id = $user->id;
        $heroes->image = $image;
        $heroes->content = $request->content;
        if($request->submit == "published"){
            $heroes->status = true;
        }else{
            $heroes->status = false;
        }
        $heroes->save();
        $request->session()->flash('successful', 'ویدیو جدید با موفقیت اضافه شد');

        return redirect()->route($this->routeBaseName . 'index');
    }

    /**
     * Edit video
     *
     * @param Request $request
     * @param Aparat $aparat
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(Request $request,Aparat $aparat)
    {
        return view('aparat::admin.edit',compact('aparat'));
    }

    /**
     * store edit video
     *
     * @param Request $request
     * @param Aparat $aparat
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request,Aparat $aparat)
    {
        $message = [
            'title.required'   => "لطفا عنوان را وارد نمایید",
            'main_image.required' => "لطفا تصویر مورد نظر را وارد نمایید",
            'content.required' => "لطفا محتوای ویدیو را وارد نمایید",
        ];
        $request->validate([
            'title'    => 'required',
            'main_image' => 'required',
            'content' => 'required',
        ], $message);
        $title = $request->title;
        $image = $request->main_image;
        $aparat->title = $title;
        $aparat->image = $image;
        $aparat->content = $request->content;
        if($request->submit != "published"){
            $aparat->status = !$aparat->status;
        }
        $aparat->save();
        $request->session()->flash('successful', 'ویدیو با موفقیت ویرایش شد');

        return redirect()->route($this->routeBaseName . 'index');
    }

    /**
     * destroy video
     *
     * @param Request $request
     * @param $aparat
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Request $request,$aparat)
    {
        $aparat = Aparat::findOrfail($aparat);
        $aparat->delete();
        $request->session()->flash('successful', 'ویدیو از لیست با موفقیت حذف شد.');

        return redirect()->route($this->routeBaseName . 'index');
    }

    public function saman()
    {
    }

}
