<?php

namespace Modules\Blog\Http\Controllers\Admin\Blog;

use App\Base\AdminController;
use App\UserMeta;
use Modules\Blog\Entities\BlogComment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;

class CommentController extends AdminController
{
    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        global $paginate_count;
        $title = 'نظرات بلاگ';
        /*----------- admin Detail ---------------*/
        $user = Auth::user();
        /*-------------- End ----------------------*/
        if ($request->input('act') != null) {
            $comments = BlogComment::where(function ($query) use ($request) {
                if ($request->input('blog_id') != null) {
                    $query->where('blog_id', $request->input('blog_id'));
                }
                if ($request->input('comment-status') != null && $request->input('comment-status') != 'انتخاب کنید') {
                    $status = $request->input('comment-status');
                    if ($status == 3) {
                        $status = 0;
                    }
                    $query->where('status', $status);
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
                if ($request->input('username') != null) {
                    $query->where('name', 'LIKE', '%' . $request->input('username') . '%');
                }
            })->whereHas('blog', function ($query) use ($user) {
                if( ! $user->can('admin_all_blog_access')) {
                    $query->whereUserId($user->id);
                }
            })->with(array('blog' => function ($query) use ($user) {
                if( ! $user->can('admin_all_blog_access')) {
                    $query->whereUserId($user->id);
                }
            }))->where('parent', 0)->orderBy('created_at', 'desc')->paginate($paginate_count);
        } else {
            $comments = BlogComment::where('parent', 0)->orderBy('created_at', 'desc')->paginate($paginate_count);
            $blogList = BlogComment::where('parent', 0)->orderBy('created_at', 'desc')->get();
        }
        $comments->appends(Input::except('page'));
        return view('blog::admin.comment.index', compact('title', 'data', 'comments', 'blogList'));
    }

    /**
     * @param Request $request
     * @param BlogComment $comment
     * @param $status
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function changeStatus(Request $request, BlogComment $comment, $status)
    {
		$this->authorize("change_status_admin_blog_comments");

        $comment->status = $status;
        $comment->save();
        $request->session()->flash('successful', 'تغییرات با موفقیت اعمال شد.');
        return redirect(route('admin.blog.comments.index'));
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function changeGroup(Request $request)
    {
        $this->authorize("change_group_admin_blog_comments");

        $act = $request->input('act_type');
        if ($act == 1) {
            foreach ($request->input('comment_id') as $comment) {
                $cc = BlogComment::find($comment);
                $cc->delete();
            }
            $request->session()->flash('successful', 'تغییرات با موفقیت اعمال شد.');
            return redirect(route('admin.blog.comments.index'));
        } elseif ($act == 2) {
            foreach ($request->input('comment_id') as $comment) {
                $cc = BlogComment::find($comment);
                $cc->status = 1;
                $cc->save();
            }
            $request->session()->flash('successful', 'تغییرات با موفقیت اعمال شد.');
            return redirect(route('admin.blog.comments.index'));
        } elseif ($act == 3) {
            foreach ($request->input('comment_id') as $comment) {
                $cc = BlogComment::find($comment);
                $cc->status = 2;
                $cc->save();
            }
            $request->session()->flash('successful', 'تغییرات با موفقیت اعمال شد.');
            return redirect(route('admin.blog.comments.index'));
        } else {
            $request->session()->flash('sys_error', 'عملیات انتخابی صحیح نمی باشد.');
            return redirect(route('admin.blog.comments.index'));
        }
    }

    public function edit(Request $request, BlogComment $comment)
    {
        $title = 'نظرات بلاگ';
        /*----------- admin Detail ---------------*/
        $user = Auth::user();
        /*-------------- End ----------------------*/

        return view('blog::admin.comment.edit', compact('title', 'data', 'comment'));

    }

    /**
     * @param Request $request
     * @param BlogComment $comment
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update(Request $request, BlogComment $comment)
    {
        $this->validate($request, [
            'answer' => 'required|min:3',
        ]);

        $hasChildren = count(BlogComment::where('parent', $comment->id)->get());
        if ($hasChildren == 0) {
            $new_coomet = new BlogComment();
        } else {
            $new_coomet = BlogComment::where('parent', $comment->id)->first();
        }

        $user = Auth::user();
        $new_coomet->message = $request->input('answer');
        $new_coomet->parent = $comment->id;
        $new_coomet->name = $user->full_name;
        $new_coomet->email = $user->email;
        $new_coomet->status = 1;
        $new_coomet->ip = $request->ip();
        $comment->blog->comments()->save($new_coomet);
        $request->session()->flash('successful', 'پاسخ نظر با موفقیت ارسال شد.');
        return redirect(route('admin.blog.comments.index'));
    }

    public function destroy(Request $request, BlogComment $comment)
    {
        $comment->delete();
        $request->session()->flash('successful', 'نظر با موفقیت حذف شد');
        return redirect(route('admin.blog.comments.index'));
    }
}
