<?php

namespace Modules\Support\Http\Controllers\Admin\Support;

use App\Base\AdminController;
use App\User;
use App\UserMeta;
use Modules\Support\Entities\Part;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PartController extends AdminController
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        global $paginate_count;
        $title = 'بخش های پشتیبانی';
        $main_part = Part::where('parent_id', '0')->get();
        $table_part = Part::where('parent_id', '0')->paginate($paginate_count);
        return view('support::admin.parts.index', compact('title', 'main_part', 'table_part'));

    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(Request $request)
    {
        $part = new Part();
        $part->parent_id = $request->input('parent');
        $part->title = $request->input('title');
        $part->setting = '';
        $part->save();
        $request->session()->flash('successful', 'بخش جدید با موفقیت اضافه شد.');
        return redirect(route('admin.support.parts.index'));
    }

    /**
     * @param Request $request
     * @param Part $part
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(Request $request, Part $part)
    {
        $title = 'ویرایش بخش - ' . $part->title;
        $main_part = Part::where('parent_id', '0')->get();
        return view('support::admin.parts.edit', compact('title', 'main_part', 'part'));
    }

    /**
     * @param Request $request
     * @param Part $part
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update(Request $request, Part $part)
    {
        $part->title = $request->input('title');
        $part->parent_id = $request->input('parent');
        $part->setting = '';
        $part->save();
        $request->session()->flash('successful', 'بخش با موفقیت ویرایش شد.');
        return redirect(route('admin.support.parts.index'));
    }

    /**
     * @param Request $request
     * @param Part $part
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @throws \Exception
     */
    public function destroy(Request $request, Part $part)
    {
        if ($part->children()->exists()) {
            self::iterativeDelete($part);
        }
        $part->delete();
        $request->session()->flash('successful', 'بخش مورد نظر با موفقیت حذف شد.');
        return redirect(route('admin.support.parts.index'));
    }

    /**
     * @param Part $part
     * @return bool
     */
    public function iterativeDelete(Part $part)
    {
        foreach ($part->children as $pp) {
            if ($pp->children()->exists()) {
                self::iterativeDelete($pp);
            }
            $pp->delete();
        }
        return true;
    }

    /**
     * @param Request $request
     * @param Part $part
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function changeStatus(Request $request, Part $part)
    {
        $this->authorize('change_status_admin_support_part');

        if ($part->children()->exists() && $part->status) {
            self::iterativeChangeStatus($part);
        }
        $part->status = !$part->status;
        $part->save();
        $request->session()->flash('successful', 'تغییر وضعیت با موفقیت انجام شد.');
        return redirect(route('admin.support.parts.index'));
    }

    /**
     * @param Part $part
     * @return bool
     */
    public function iterativeChangeStatus(Part $part)
    {
        foreach ($part->children as $pp) {
            if ($pp->children()->exists()) {
                self::iterativeChangeStatus($pp);
            }
            if ($pp->status) {
                $pp->status = !$pp->status;
                $pp->save();
            }
        }
        return true;
    }

    public function users(Request $request, Part $part)
    {
        $users = User::all();
        $admin_users = [];
        foreach ($users as $user) {
            if ($user->hasAnyPermission(['ADMIN', 'SUPERADMIN_ACCESS'])) {
                $admin_users[] = $user;
            }
        }
        $part_users = $part->users;
        return view('support::admin.parts.edit_user', compact('part', 'admin_users', 'part_users'));
    }

    public function updateUser(Request $request, Part $part)
    {
        $users = [];
        if ($request->input('users') != null)
        {
            foreach ($request->input('users') as $user) {
                $users[] = (int)$user;
            }
        }
        $part->users()->sync($users);
        return redirect(route('admin.support.parts.index', $part));
    }
}
