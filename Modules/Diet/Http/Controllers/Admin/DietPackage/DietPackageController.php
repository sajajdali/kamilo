<?php

namespace Modules\Diet\Http\Controllers\Admin\DietPackage;

use App\Base\AdminController;
use App\Base\Authorizable;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Diet\Entities\DietPackage\DietPackage;

class DietPackageController extends AdminController
{
    use Authorizable;

    /**
     * List of packages
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $title = 'پکیج‌های ویژه';
        $packages = DietPackage::paginate(20);
        return view('diet::admin.package.index', compact('packages', 'title'));
    }

    /**
     * View new package
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        return view('diet::admin.package.create');
    }

    public function store(Request $request)
    {
        $roles = [
            'name'  => 'required',
            'color' => 'required',
            'price' => 'required|integer',
            'charge' => 'required|integer',
        ];
        $messages = [
            'name.required' => 'وارد کردن عنوان پکیج الزامی می‌باشد',
            'color.required' => 'انتخاب رنگ بسته الزامی می‌باشد',
            'price.required' => 'وارد کردن فیمت بسته الزامی می‌باشد',
            'price.integer' => 'قیمت بسته باید به صورت عددی وارد شود',
            'charge.required' => 'وارد کردن شارژ بسته الزامی می‌باشد',
            'charge.integer' => 'شارژ بسته باید به صورت عددی وارد شود',
        ];
        $request->validate($roles, $messages);
        DietPackage::create([
            'user_id' => \Auth::id(),
            'status' => true,
            'title' => $request->name,
            'price' => $request->price,
            'charge' => $request->charge,
            'color' => $request->color,
            'counter' => 0,
            'details' => ['details' => $request->detail]
        ]);

        $request->session()->flash('successful', 'پکیج جدید با موفقیت اضافه شد');

        return redirect()->route('admin.diet.package.index');
    }

    /**
     * Update package
     *
     * @param Request $request
     * @param $package
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(Request $request,$package)
    {
        $package = DietPackage::findOrFail($package);
        $details = $package->details;
        $details = $details['details'];
        return view('diet::admin.package.edit',compact('package','details'));
    }

    /**
     * Store update
     *
     * @param Request $request
     * @param $package
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request,$package)
    {
        $roles = [
            'name'  => 'required',
            'color' => 'required',
            'price' => 'required|integer',
            'charge' => 'required|integer',
        ];
        $messages = [
            'name.required' => 'وارد کردن عنوان پکیج الزامی می‌باشد',
            'color.required' => 'انتخاب رنگ بسته الزامی می‌باشد',
            'price.required' => 'وارد کردن فیمت بسته الزامی می‌باشد',
            'price.integer' => 'قیمت بسته باید به صورت عددی وارد شود',
            'charge.required' => 'وارد کردن شارژ بسته الزامی می‌باشد',
            'charge.integer' => 'شارژ بسته باید به صورت عددی وارد شود',
        ];
        $request->validate($roles, $messages);
        $package = DietPackage::findOrfail($package);
        $package->update([
            'user_id' => \Auth::id(),
            'title' => $request->name,
            'price' => $request->price,
            'charge' => $request->charge,
            'color' => $request->color,
            'details' => ['details' => $request->detail]
        ]);
        $request->session()->flash('successful', 'پکیج با موفقیت بروزرسانی شد');

        return redirect()->route('admin.diet.package.index');
    }

    /**
     * Remove package
     *
     * @param Request $request
     * @param $package
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Request $request,$package)
    {
        $package = DietPackage::findOrfail($package);
        try{
            $package->delete();
            $request->session()->flash('successful', 'پکیج با موفقیت حذف شد');

            return redirect()->route('admin.diet.package.index');
        }catch (\Exception $e){
            $request->session()->flash('sys_error', 'خطا در اتصال به پایگاه داده‌ها');

            return redirect()->route('admin.diet.package.index');
        }
    }

    public function changeState(Request $request, $package)
    {
        $this->authorize('edit_admin_diet_package');
        $package = DietPackage::findOrfail($package);
        $package->update([
            'status' => !$package->status
        ]);
        $request->session()->flash('successful', 'وضعیت پکیج با موفقیت بروزرسانی شد');

        return redirect()->route('admin.diet.package.index');
    }
}
