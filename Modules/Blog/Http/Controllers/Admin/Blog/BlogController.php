<?php

namespace Modules\Blog\Http\Controllers\Admin\Blog;

use App\Base\AdminController;
use App\Base\Authorizable;
use App\UserMeta;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use Modules\Blog\Entities\Blog;
use Modules\Blog\Entities\BlogCategory;
use Modules\Blog\Entities\BlogMeta;


class BlogController extends AdminController
{
//    use Authorizable;

    /**
     * Display a listing of the resource.
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        global $paginate_count;
        $title = 'مدیریت اخبار';
        /*----------- admin Detail ---------------*/
        $user = Auth::user();
        /*-------------- End ----------------------*/
        if ($request->input('act') != null) {
            $news = Blog::where(function ($query) use ($user, $request) {
                if (!$user->can('admin_all_blog_access')) {
                    $query->whereUserId($user->id);
                }
                if ($request->input('news-status') != 0) {
                    $query->where('published', $request->input('news-status'));
                }
                if ($request->input('from') != null) {
                    $from = explode('/', $request->input('from'));
                    $from = jalali_to_gregorian($from[0], $from[1], $from[2], '-');
                    $from = $from . ' 00:00:00';
                    $query->where('created_at', '>=', $from);
                }
                if ($request->input('to') != null) {
                    $to = explode('/', $request->input('to'));
                    $to = jalali_to_gregorian($to[0], $to[1], $to[2], '-');
                    $to = $to . ' 00:00:00';
                    $query->where('created_at', '<=', $to);
                }
                if ($request->input('title') != null) {
                    $query->where('title', 'LIKE', '%' . $request->input('title') . '%');
                }
            })->orderBy('created_at', 'desc')->paginate($paginate_count);
        } else {
            $news = Blog::where(function ($query) use ($user) {
                if (!$user->can('admin_all_blog_access')) {
                    $query->whereUserId($user->id);
                }
            })->orderBy('created_at', 'desc')->paginate($paginate_count);
        }
        $news->appends(Input::except('page'));
        return view('blog::admin.index', compact('title', 'data', 'news'));
    }

    /**
     * Show the form for creating a new resource.
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        $title = 'ارسال خبر';
        /*----------- admin Detail ---------------*/
        $user = Auth::user();
        /*-------------- End ----------------------*/
        $categories = BlogCategory::where('parent', '0')->get();
        return view('blog::admin.create', compact('title', 'data', 'categories'));
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'title' => 'required',
            'hometext' => 'required',
            'bodytext' => 'required',
        ]);
        $blog = new Blog();
        $blog->user_id = Auth::user()->id;
        $blog->counter = 0;
        $blog->comment = 0;
        if ($request->input('comment') != null) {
            $blog->comment = $request->input('comment');
        }
        if ($request->input('submit') == 'published') {
            $blog->published = 1;
        } else {
            $blog->published = 2;
        }
        if ($request->input('main_page') != null) {
            $blog->main_page = true;
        } else {
            $blog->main_page = false;
        }
        $blog->show_at_home_top = $request->input('show_at_home_top', 0);
        $blog->show_at_home_bottom = $request->input('show_at_home_bottom', 0);

        $blog->details = json_encode([]);

        $blog->type = $request->get('type');

        $blog->title = $request->input('title');
        $blog->home_text = $request->input('hometext');
        $blog->body_text = $request->input('bodytext');

        $blog->save();

        // Blog Meta
        $keywords = $request->input('keywords');
        if ($keywords != null) {
            $keywords = explode(',', $keywords);
            if (sizeof($keywords) >= 1) {
                foreach ($keywords as $key) {
                    $blog_meta = new BlogMeta();
                    $blog_meta->meta_key = BlogMeta::META_KEY_TAG;
                    $blog_meta->meta_value = $key;
                    $blog->metas()->save($blog_meta);
                }
            }
        }

        if ($request->input('main_image') != null) {
            $mainImage = $request->input('main_image');
            $mainImage = str_replace(url('uploads/blog'), '', $mainImage);
            $blog_meta = new BlogMeta();
            $blog_meta->meta_key = BlogMeta::META_KEY_MAIN_IMAGE;
            $blog_meta->meta_value = $mainImage;
            $blog->metas()->save($blog_meta);
        }

        if ($request->input('describtion') != null) {
            $blog_meta = new BlogMeta();
            $blog_meta->meta_key = BlogMeta::META_KEY_DESCRIPTION;
            $blog_meta->meta_value = $request->input('describtion');
            $blog->metas()->save($blog_meta);
        }

        // Category
        if (sizeof($request->input('cat', [])) >= 1) {
            foreach ($request->input('cat', []) as $cat_id) {
                $blog->categories()->attach($cat_id);
                BlogCategory::where('id', $cat_id)->increment('counter');
            }
        }

        $request->session()->flash('successful', 'خبر جدید ارسال شد.');
        return redirect(route('admin.blog.index'));
    }

    /**
     * Show the form for editing the specified resource.
     * @param Request $request
     * @param Blog $blog
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(Request $request, Blog $blog)
    {
        $title = 'ویرایش خبر';
        /*----------- admin Detail ---------------*/
        $user = Auth::user();
        /*-------------- End ----------------------*/
        $categories = BlogCategory::where('parent', '0')->get();
        return view('blog::admin.edit', compact('title', 'data', 'categories', 'blog'));
    }

    /**
     * Update the specified resource in storage.
     * @param  Request $request
     * @param Blog $blog
     * @return Response
     */
    public function update(Request $request, Blog $blog)
    {
        $this->validate($request, [
            'title' => 'required',
            'hometext' => 'required',
            'bodytext' => 'required',
        ]);

        if ($request->input('comment') != null) {
            $blog->comment = $request->input('comment');
        }

        if ($request->input('submit') == 'save_public') {
            $blog->published = 1;
        }

        if ($request->input('main_page') != null) {
            $blog->main_page = true;
        } else {
            $blog->main_page = false;
        }

        $blog->show_at_home_top = $request->input('show_at_home_top', 0);
        $blog->show_at_home_bottom = $request->input('show_at_home_bottom', 0);

        $blog->details = json_encode([]);

        $blog->type = $request->get('type');

        // Save Texts
        $blog->title = $request->input('title');
        $blog->home_text = $request->input('hometext');
        $blog->body_text = $request->input('bodytext');

        $blog->save();

        // Update Tags
        if ($request->input('keywords') != null) {
            if ($blog->metas->where('meta_key', BlogMeta::META_KEY_TAG)->count() >= 1) {
                $blog_meta = $blog->metas()->where('meta_key', BlogMeta::META_KEY_TAG)->first();
                $blog_meta->meta_value = $request->input('keywords');
                $blog_meta->save();
            } else {
                $blog_meta = new BlogMeta();
                $blog_meta->meta_key = BlogMeta::META_KEY_TAG;
                $blog_meta->meta_value = $request->input('keywords');
                $blog->metas()->save($blog_meta);
            }
        } else {
            if ($blog->metas->where('meta_key', BlogMeta::META_KEY_TAG)->count() >= 1) {
                $blog->metas()->where('meta_key', BlogMeta::META_KEY_TAG)->first()->delete();
            }
        }

        // Update Image
//        $blog_meta = new BlogMeta();
//        $blog_meta->meta_key = BlogMeta::META_KEY_MAIN_IMAGE;
//        $blog_meta->meta_value = $request->input('main_image');
//        $blog->metas()->save($blog_meta);
        $request->main_image = $request->main_image ?? '';
        BlogMeta::updateOrCreate( [ 'blog_id' => $blog->id, 'meta_key' => BlogMeta::META_KEY_MAIN_IMAGE ], [ 'meta_value' => $request->main_image ] );

//        if ($request->input('image_news') != null) {
//            if ($blog->metas->where('meta_key', 'MAIN_IMAGE')->count() >= 1) {
//
//                $blog_meta = $blog->metas()->where('meta_key', BlogMeta::META_KEY_MAIN_IMAGE)->first();
//
//                $mainImageBase = $request->input('main_image');
//                $mainImage = str_replace(url('uploads/blog'), '', $mainImageBase);
//                $blog_meta->meta_value = $mainImage;
//                $blog_meta->save();
//            } else {
//                $blog_meta = new BlogMeta();
//                $blog_meta->meta_key = BlogMeta::META_KEY_MAIN_IMAGE;
//                $mainImage = $request->input('main_image');
//                $mainImage = str_replace(url('uploads/blog'), '', $mainImage);
//
//                $blog_meta->meta_value = $mainImage;
//                $blog->metas()->save($blog_meta);
//            }
//        } else {
//            if ($blog->metas->where('meta_key', BlogMeta::META_KEY_MAIN_IMAGE)->count() >= 1) {
//                $blog->metas()->where('meta_key', BlogMeta::META_KEY_MAIN_IMAGE)->first()->delete();
//            }
//        }

        // Update Description
        if ($request->input('describtion') != null) {
            if ($blog->metas->where('meta_key', BlogMeta::META_KEY_DESCRIPTION)->count() >= 1) {
                $blog_meta = $blog->metas()->where('meta_key', BlogMeta::META_KEY_DESCRIPTION)->first();
                $blog_meta->meta_value = $request->input('describtion');
                $blog_meta->save();
            } else {
                $blog_meta = new BlogMeta();
                $blog_meta->meta_key = BlogMeta::META_KEY_DESCRIPTION;
                $blog_meta->meta_value = $request->input('describtion');
                $blog->metas()->save($blog_meta);
            }
        } else {
            if ($blog->metas->where('meta_key', BlogMeta::META_KEY_DESCRIPTION)->count() >= 1) {
                $blog->metas()->where('meta_key', BlogMeta::META_KEY_DESCRIPTION)->first()->delete();
            }
        }

        // Sync Category
        foreach ($request->input('cat', []) as $cat_id) {
            if ($blog->categories()->where('id', $cat_id)->count() == 0) {
                DB::table('blog_categories')
                    ->where('id', $cat_id)
                    ->increment('counter');
            }
        }
        foreach ($blog->categories as $cat) {
            if (!in_array($cat->id, $request->input('cat', []))) {
                $blog->categories()->where('id', $cat->id)->first()->decrement('counter');
            }
        }
        $blog->categories()->sync($request->input('cat', []));

        $request->session()->flash('successful', 'خبر با موفقیت بروزرسانی شد.');
        return redirect(route('admin.blog.index'));
    }

    /**
     * Remove the specified resource from storage.
     * @param Request $request
     * @param Blog $blog
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @throws \Exception
     */
    public function destroy(Request $request, Blog $blog)
    {
        foreach ($blog->categories as $cat) {
            BlogCategory::where('id', $cat->id)->decrement('counter');
        }
        $blog->delete();
        $request->session()->flash('successful', 'خبر با موفقیت حذف شد.');
        return redirect(route('admin.blog.index'));
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function changeGroup(Request $request)
    {
        $this->authorize("change_group_admin_blog");

        $act = $request->input('act_type');
        $blog_ids = $request->input('blog_id');
        if ($act == 1) {
            // Delete Group Blog
            foreach ($blog_ids as $blog_id) {
                $blog = Blog::find($blog_id);
                foreach ($blog->categories as $cat) {
                    DB::table('blog_categories')
                        ->where('id', $cat->id)
                        ->decrement('counter');
                }
                $blog->delete();
            }
        } elseif ($act == 2) {
            // Published All Item
            foreach ($blog_ids as $blog_id) {
                $blog = Blog::find($blog_id);
                if ($blog->published != 1) {
                    $blog->published = 1;
                    $blog->save();
                }
            }
        } else {
            foreach ($blog_ids as $blog_id) {
                $blog = Blog::find($blog_id);
                if ($blog->published != 2) {
                    $blog->published = 2;
                    $blog->save();
                }
            }
        }
        $request->session()->flash('successful', 'تغییرات با موفقیت اعمال شد.');
        return redirect(route('admin.blog.index'));
    }

    /**
     * @param Request $request
     * @return string
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function upload(Request $request)
    {
        $this->authorize("upload_admin_blog");

        $validator = Validator::make($request->all(), [
            'file' => 'image',
        ]);
        if ($validator->fails()) {
            return '0';
        } else {
            $this_year = jdate('o', '', '', '', 'en');
            $this_month = jdate('m', '', '', '', 'en');
            if (!is_dir((public_path('uploads/blog') . '/' . $this_year))) {
                mkdir((public_path('uploads/blog') . '/' . $this_year));
            }
            if (!is_dir((public_path('uploads/blog') . '/' . $this_year . '/' . $this_month))) {
                mkdir((public_path('uploads/blog') . '/' . $this_year . '/' . $this_month));
            }
            if ($request->hasFile('file')) {
                $orginam_name = str_replace('.' . $request->file('file')->getClientOriginalExtension(), '', $request->file('file')->getClientOriginalName());
                $orginam_name = str_replace(' ', '', $orginam_name);
                $fileName = $orginam_name . uniqid('__img_') . '.' . $request->file('file')->getClientOriginalExtension();
                $request->file('file')->move(public_path('uploads/blog') . '/' . $this_year . '/' . $this_month, $fileName);
                return url('uploads/blog') . '/' . $this_year . '/' . $this_month . '/' . $fileName;
            } else {
                return '0';
            }
        }
    }

    /**
     * @param Request $request
     * @return string
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function removeImage(Request $request)
    {
        $this->authorize("remove_image_admin_blog");

        $validator = Validator::make($request->all(), [
            'file' => 'required',
        ]);
        if ($validator->fails()) {
            return '0';
        } else {
            $fileNameBase = $request->input('file');
            $fileName = str_replace(url('uploads/blog'), '', $fileNameBase);
            if (File::exists(public_path('uploads/blog' . $fileName))) {
                File::delete(public_path('uploads/blog' . $fileName));
                return '1';
            }
            return '0';
        }
    }

    /**
     * @param Request $request
     * @param Blog $blog
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function changeStatus(Request $request, Blog $blog)
    {
        $this->authorize("change_status_admin_blog");

        if ($blog->published == 1) {
            $blog->published = 2;
        } else {
            $blog->published = 1;
        }
        $blog->save();
        $request->session()->flash('successful', 'تغییر وضعیت با موفقیت انجام شد.');
        return redirect(route('admin.blog.index'));
    }

    /**
     * @param Request $request
     * @return string
     */
    public function tags(Request $request)
    {
        $search = $request->input('term');
        $return = BlogMeta::whereMetaKey(BlogMeta::META_KEY_TAG)->where('meta_value', 'LIKE', '%' . $search . '%')->take(10)->get();
        return json_encode($return);
    }
}
