<?php

namespace Modules\Heroes\Http\Controllers\Admin;

use App\Base\AdminController;
use App\Base\Authorizable;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\Heroes\Entities\Heroes;

class HeroesController extends AdminController
{
    use Authorizable;
    protected $routeBaseName = 'admin.heroes.';

    /**
     * Heroes list
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        if($request->act != null){
            $heroes = Heroes::orderBy('id','DESC')->where(function($q) use($request){
                if($request->input('heroes-status') != 0){
                    if($request->input('heroes-status') == 1){
                        $q->whereStatus(true);
                    }else{
                        $q->whereStatus(false);
                    }
                }
                if($request->title != null){
                    $q->where('title','LIKE','%'.$request->title.'%');
                }
            })->paginate(10);
        }else{
            $heroes = Heroes::orderBy('id','DESC')->paginate(10);
        }
        return view('heroes::admin.index',compact('heroes'));
    }

    /**
     * Create new heroes
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        return view('heroes::admin.create');
    }

    /**
     * Store new heroes
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $message = [
            'title.required'   => "لطفا عنوان را وارد نمایید",
            'main_image.required' => "لطفا تصویر مورد نظر را وارد نمایید",
        ];
        $request->validate([
            'title'    => 'required',
            'main_image' => 'required',
        ], $message);
        $user = Auth::user();
        $title = $request->title;
        $image = $request->main_image;
        $heroes = new Heroes();
        $heroes->title = $title;
        $heroes->user_id = $user->id;
        $heroes->image = $image;
        if($request->submit == "published"){
            $heroes->status = true;
        }else{
            $heroes->status = false;
        }
        $heroes->save();
        $request->session()->flash('successful', 'قهرمان جدید با موفقیت اضافه شد');

        return redirect()->route($this->routeBaseName . 'index');
    }

    /**
     * Edit hero
     *
     * @param Request $request
     * @param Heroes $hero
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(Request $request,Heroes $hero)
    {
        return view('heroes::admin.edit',compact('hero'));
    }

    /**
     * Update heroes
     *
     * @param Request $request
     * @param Heroes $hero
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request,Heroes $hero)
    {
        $message = [
            'title.required'   => "لطفا عنوان را وارد نمایید",
            'main_image.required' => "لطفا تصویر مورد نظر را وارد نمایید",
        ];
        $request->validate([
            'title'    => 'required',
            'main_image' => 'required',
        ], $message);
        $title = $request->title;
        $image = $request->main_image;
        $hero->title = $title;
        $hero->image = $image;
        if($request->submit != "published"){
            $hero->status = !$hero->status;
        }
        $hero->save();
        $request->session()->flash('successful', 'قهرمان با موفقیت ویرایش شد.');

        return redirect()->route($this->routeBaseName . 'index');
    }

    /**
     * Remove Hero from list
     *
     * @param Request $request
     * @param $heroe
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Request $request,$heroe)
    {
        $heroe = Heroes::findOrfail($heroe);
        $heroe->delete();
        $request->session()->flash('successful', 'قهرمان از لیست با موفقیت حذف شد.');

        return redirect()->route($this->routeBaseName . 'index');
    }
}
