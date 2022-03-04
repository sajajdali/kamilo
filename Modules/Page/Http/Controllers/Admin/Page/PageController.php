<?php

namespace Modules\Page\Http\Controllers\Admin\Page;

use App\Base\AdminController;
use App\Base\Authorizable;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Modules\Page\Entities\Page;
use Spatie\Permission\Models\Role;

class PageController extends AdminController {

	use Authorizable;

	/**
	 * Display a listing of the resource.
	 * @return Response
	 */
	public function index(Request $request) {
		$user = Auth::user();
		if($request->input('act') != null) {
			$pages = Page::where(function($q) use ($user) {
				if( ! $user->can('admin_all_page_access')) {
					$q->whereUserId($user->id);
				}
			})->orderBy('created_at', 'desc')->paginate($this->paginate_count);
		} else {
			$pages = Page::where(function($q) use ($user) {
				if( ! $user->can('admin_all_page_access')) {
					$q->whereUserId($user->id);
				}
			})->orderBy('created_at', 'desc')->paginate($this->paginate_count);
		}
		$data              = array();
		$data['all_admin'] = Role::all();

		return view('page::admin.list', compact('data', 'pages'));
	}

	/**
	 * Show the form for creating a new resource.
	 * @return Response
	 */
	public function create() {
		return view('page::admin.create');
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  Request $request
	 *
	 * @return Response
	 */
	public function store(Request $request) {
		$this->validate($request, [
			'title.*'    => 'required',
			'hometext.*' => 'required',
			'bodytext.*' => 'required',
		]);
		$page          = new Page();
		$user          = Auth::user();
		$page->counter = 0;
		$counter       = 0;
		$title         = array();
		$content       = array();
		$hometext      = array();
		$title2        = $request->input('title');
		$hometext2     = $request->input('hometext');
		foreach(Config::get('app.locales') as $key => $value) {
			$title[$key]    = $title2[$counter];
			$hometext[$key] = $hometext2[$counter];
			$counter ++;
		}
		$page->title        = json_encode($title, JSON_UNESCAPED_UNICODE);
		$page->image        = $request->main_image;
		$content['content'] = $hometext;
		if($request->input('describtion') != null) {
			$content['DESCRIBTION'] = $request->input('describtion');
		}
		if($request->input('keywords') != null) {
			$content['KEYWORD'] = $request->input('keywords');
		}
		if($request->input('main_image') != null) {
			$content['MAIN_IMAGE'] = str_replace(url('uploads/page'), '', $request->input('main_image'));
		}
		$page->content = json_encode($content, JSON_UNESCAPED_UNICODE);
		$user->pages()->save($page);
		$request->session()->flash('successful', 'صفحه جدید با موفقیت ایجاد شد.');

		return redirect()->route('admin.pages.index');
	}

	/**
	 * Show the form for editing the specified resource.
	 * @return Response
	 */
	public function edit(Request $request, Page $page) {
		$title           = 'ایجاد صفحه جدید';
		$data['page']    = $page;
		$data['content'] = json_decode($page->content);

		return view('page::admin.edit', compact('title', 'data', 'page'));
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  Request $request
	 *
	 * @return Response
	 */
	public function update(Request $request, Page $page) {
		$this->validate($request, [
			'title.*'    => 'required',
			'hometext.*' => 'required',
			'bodytext.*' => 'required',
		]);
		$counter   = 0;
		$title     = array();
		$content   = array();
		$hometext  = array();
		$title2    = $request->input('title');
		$hometext2 = $request->input('hometext');
		foreach(Config::get('app.locales') as $key => $value) {
			$title[$key]    = $title2[$counter];
			$hometext[$key] = $hometext2[$counter];
			$counter ++;
		}
		$page->title        = json_encode($title, JSON_UNESCAPED_UNICODE);
		$content['content'] = $hometext;
        $page->image        = $request->main_image;
        if($request->input('describtion') != null) {
			$content['DESCRIBTION'] = $request->input('describtion');
		}
		if($request->input('keywords') != null) {
			$content['KEYWORD'] = $request->input('keywords');
		}
		if($request->input('main_image') != null) {
			$content['MAIN_IMAGE'] = str_replace(url('uploads/page'), '', $request->input('main_image'));
		}
		$page->content = json_encode($content, JSON_UNESCAPED_UNICODE);
		$page->save();
		$request->session()->flash('successful', 'صفحه با موفقیت ویرایش شد.');

		return redirect(url('/admin/pages'));
	}

	/**
	 * Remove the specified resource from storage.
	 * @return Response
	 */
	public function destroy(Request $request, Page $page) {
		$page->delete();
		request()->session()->flash('successful', 'صفحه با موفقیت حذف شد');

		return redirect()->route('admin.pages.index');
	}
}
