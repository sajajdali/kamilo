<?php

namespace Modules\Blog\Http\Controllers\Admin\Blog;

use App\Base\AdminController;
use App\UserMeta;
use Modules\Blog\Entities\BlogCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Input;


class CategoryController extends AdminController
{
    public function index(Request $request)
    {
        global $paginate_count;
        $title = 'مدیریت موضوعات وبلاگ';
        /*----------- admin Detail ---------------*/
        $user = Auth::user();
        /*-------------- End ----------------------*/
        /*------------- All categories for select ------*/
        $all_cat = BlogCategory::where('parent', '0')->get();
        /*-------------- End ----------------------*/
        if ($request->input('act') != null) {
            $table_cats = BlogCategory::where(function ($q) use ($request) {
                if ($request->input('group') != null) {
                    $q->where('parent', $request->input('group'));
                } else {
                    $q->where('parent', 0);
                }
                if ($request->input('title') != null) {
                    $q->where('title', 'LIKE', '%' . $request->input('title') . '%');
                }
            })->paginate($paginate_count);
        } else {
            $table_cats = BlogCategory::where('parent', '0')->paginate($paginate_count);
        }
        $table_cats->appends(Input::except('page'));
        return view('blog::admin.category.index', compact('title', 'data', 'all_cat', 'table_cats'));
    }

    public function get_cat_json($parent_id = 0)
    {
        $ret = array();
        $cats = BlogCategory::where('parent', $parent_id)->get();
        foreach ($cats as $cat) {
            if (BlogCategory::where('parent', $cat->id)->exists()) {
                $this->get_cat_json($cat->id);
            } else {
                $ret[] = $cat;
            }
        }
        return $ret;
    }

    /**
     * Show the form for creating a new resource.
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        $title = 'اضافه کردن موضوع جدید';
        /*----------- admin Detail ---------------*/
        $user = Auth::user();
        /*-------------- End ----------------------*/
        /*------------- All categor for selec------*/
        $all_cat = BlogCategory::where('parent', '0')->get();
        /*-------------- End ----------------------*/
        return view('blog::admin.category.create', compact('title', 'data', 'all_cat'));
    }

    public function store(Request $request)
    {
        $parent_id = $request->input('parent');
        $cat = new BlogCategory();
        $cat->parent = $parent_id;
        $cat->counter = 0;
        $cat->title = $request->input('title');
        $cat->save();

        $request->session()->flash('successful', 'موضوع با موفقیت اضافه شد.');
        return redirect(route('admin.blog.category.index'));
    }

    public function destroy(Request $request, BlogCategory $category)
    {
        if ($category->children()->exists()) {
            $request->session()->flash('sys_error', 'امکان حفظ موضوع با زیرموضوع وجود ندارد.');
            return redirect(route('admin.blog.category.index'));
        } else {
            $category->delete();
            $request->session()->flash('successful', 'موضوع با موفقیت حذف شد.');
            return redirect(route('admin.blog.category.index'));
        }
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function deleteGroup(Request $request)
    {
        $this->authorize('delete_group_admin_blog_category');

        $cats_id = $request->input('cat_id');
        if (sizeof($cats_id) >= 1) {
            $has_child = false;
            foreach ($cats_id as $cat) {
                if (BlogCategory::find($cat)->children()->exists()) {
                    $has_child = true;
                    break;
                }
            }
            if ($has_child) {
                $request->session()->flash('sys_error', 'یک یا چند موضوع انتخابی دارای زیرموضوع می باشند.');
                return redirect(route('admin.blog.category.index'));
            } else {
                foreach ($cats_id as $cat) {
                    BlogCategory::find($cat)->delete();
                }
                $request->session()->flash('successful', 'موضوع های انتخابی با موفقیت حذف شده اند.');
                return redirect(route('admin.blog.category.index'));
            }
        } else {
            $request->session()->flash('sys_error', 'هیچ موضوعی برای حذف انتخاب نشده است.');
            return redirect(route('admin.blog.category.index'));
        }
    }

    public function edit(Request $request, BlogCategory $category)
    {
        $title = 'ویرایش موضوع';
        /*----------- admin Detail ---------------*/
        $user = Auth::user();
        /*-------------- End ----------------------*/
        /*------------- All categor for selec------*/
        $all_cat = BlogCategory::where('parent', '0')->get();
        /*-------------- End ----------------------*/
        return view('blog::admin.category.edit', compact('title', 'data', 'all_cat', 'category'));
    }

    public function update(Request $request, BlogCategory $category)
    {
        $parent_id = $request->input('parent');
        $category->parent = $parent_id;
        $category->title = $request->input('title');
        $category->save();

        $request->session()->flash('successful', 'موضوع با موغفیت ویرایش شد.');
        return redirect(route('admin.blog.category.index'));
    }
}
