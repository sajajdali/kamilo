<?php

namespace Modules\Diet\Http\Controllers\Admin\Meal;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Modules\Diet\Entities\Meal\Meal_category;

class CategoryController extends Controller
{
    public $paginate_count = 50;


    public function index(Request $request)
    {
        $this->authorize('view_admin_meal_category');
        global $paginate_count;
        $title = 'مدیریت موضوعات ر‍ژیم';
        /*------------- All categor for selec------*/
        $all_cat = Meal_category::where('parent', '0')->get();
        /*-------------- End ----------------------*/
        if ($request->input('act') != null) {
            $table_cats = Meal_category::where(function ($q) use ($request) {
                if ($request->input('title') != null) {
                    $q->where('name', 'like', '%' . $request->input('title') . '%');
                }
                if ($request->input('group') != null) {
                    $q->where('parent', $request->input('group'));
                } else {
                    $q->where('parent', 0);
                }
            })->paginate($paginate_count);
        } else {
            $table_cats = Meal_category::where('parent', '0')->paginate($paginate_count);
        }
        $table_cats->appends(Input::except('page'));

        return view('diet::admin.meal.category', compact('title', 'data', 'all_cat', 'table_cats'));
    }

    public function store(Request $request)
    {
        $this->authorize('add_admin_meal_category');
        $messages = [
            'name.required' => 'وارد کردن نام الگو ضروری میباشد',
            'price.required' => 'وارد کردن قیمت الگو ضروری میباشد',
            'type.between' => 'لطفا نوع الگو را مشخص کنید',
            'rejim_type.between' => 'لطفا نوع رژیم را مشخص کنید',
            'icon.image' => 'لطفا فایل عکسی انتخاب نمایید',
        ];
        $this->validate($request, [
            'name' => 'required',
            'price' => 'required',
            'type' => 'required|integer|between:1,10',
            'rejim_type' => 'required|integer|between:1,5',
            'icon' => 'image'
        ], $messages);
        $parent_id = $request->input('parent');
        $cat = new Meal_category();
        $cat->parent = $parent_id;
        $cat->name = $request->input('name');
        $cat->status = true;
        $cat->type = $request->input('type');
        $cat->rejim_type = $request->input('rejim_type');
        $cat->guid = $request->input('link');
        $cat->color = $request->input('color');
        $cat->price = str_replace(' , ', '', $request->input('price'));
        if ($request->hasFile('icon')) {
            $orginam_name = str_replace('.' . $request->file('icon')->getClientOriginalExtension(), '', $request->file('icon')->getClientOriginalName());
            $orginam_name = str_replace(' ', '', $orginam_name);
            $fileName = $orginam_name . uniqid('__img_') . '.' . $request->file('icon')->getClientOriginalExtension();
            $request->file('icon')->move(public_path('uploads/diet'), $fileName);
        } else {
            $fileName = '';
        }
        $cat->img = $fileName;
        $cat->desc_before = $request->input('desc_before');
        $cat->desc_after = $request->input('desc_after');
        $cat->save();
        $cat->disease()->sync($request->input('disease'));
        $request->session()->flash('successful', 'الگو با موفقیت اضافه شد.');

        return redirect(url('/admin/meal/category'));
    }

    public function delete(Request $request, Meal_category $catagory)
    {
        $this->authorize('delete_admin_meal_category');
        if ($catagory->children()->exists()) {
            $request->session()->flash('sys_error', 'امکان حفظ الگو با زیرمجموعه وجود ندارد. ابتدا میبایست زیرمجموعه ها را حذف و سپس اقدام به حذف الگو نمایید');

            return redirect(url('/admin/meal/category'));
        } else {
            if (!empty($catagory->img) && file_exists(public_path('uploads/diet') . '/' . $catagory->img)) {
                unlink(public_path('uploads/diet') . '/' . $catagory->img);
            }
            $catagory->delete();
            $request->session()->flash('successful', 'الگو با موفقیت حذف شد');

            return redirect(url('/admin/meal/category'));
        }
    }

    public function deleteGroup(Request $request)
    {
        $this->authorize('group_delete_admin_meal_category');
        $cats_id = $request->input('cat_id');
        if (sizeof($cats_id) >= 1) {
            $has_child = false;
            foreach ($cats_id as $cat) {
                if (Meal_category::find($cat)->children()->exists()) {
                    $has_child = true;
                    break;
                }
            }
            if ($has_child) {
                $request->session()->flash('sys_error', 'یک یا چند موضوع انتخابی دارای زیرموضوع می باشند.');

                return redirect(url('/admin/meal/category'));
            } else {
                foreach ($cats_id as $cat) {
                    $cat_temp = Meal_category::find($cat);
                    if (!empty($cat_temp->img) && file_exists(public_path('uploads/diet') . '/' . $cat_temp->img)) {
                        unlink(public_path('uploads/diet') . '/' . $cat_temp->img);
                    }
                    $cat_temp->delete();
                }
                $request->session()->flash('successful', 'موضوع های انتخابی با موفقیت حذف شده اند.');

                return redirect(url('/admin/meal/category'));
            }
        } else {
            $request->session()->flash('sys_error', 'هیچ موضوعی برای حذف انتخاب نشده است.');

            return redirect(url('/admin/blog/category'));
        }
    }

    public function edit(Request $request, Meal_category $catagory)
    {
        $this->authorize('edit_admin_meal_category');
        global $paginate_count;
        $title = 'ویرایش موضوع';
        /*------------- All categor for selec------*/
        $all_cat = Meal_category::where('parent', '0')->get();

        /*-------------- End ----------------------*/

        return view('diet::admin.meal.edit_category', compact('title', 'data', 'all_cat', 'catagory'));
    }

    public function update(Request $request, Meal_category $catagory)
    {
        $this->authorize('edit_admin_meal_category');
        $messages = [
            'name.required' => 'وارد کردن نام الگو ضروری میباشد',
            'price.required' => 'وارد کردن قیمت الگو ضروری میباشد',
            'type.between' => 'لطفا نوع الگو را مشخص کنید',
            'rejim_type.between' => 'لطفا نوع رژیم را مشخص کنید',
            'icon.image' => 'لطفا فایل عکسی انتخاب نمایید',
        ];
        $this->validate($request, [
            'name' => 'required',
            'price' => 'required',
            'type' => 'required|integer|between:1,10',
            'rejim_type' => 'required|integer|between:1,5',
            'icon' => 'image'
        ], $messages);
        $parent_id = $request->input('parent');
        $catagory->parent = $parent_id;
        $catagory->type = $request->input('type');
        $catagory->rejim_type = $request->input('rejim_type');
        $catagory->name = $request->input('name');
        $catagory->guid = $request->input('link');
        $catagory->color = $request->input('color');
        $catagory->price = str_replace(' , ', '', $request->input('price'));
        if ($request->hasFile('icon')) {
            if (!empty($catagory->img) && file_exists(public_path('uploads/diet') . '/' . $catagory->img)) {
                unlink(public_path('uploads/diet') . '/' . $catagory->img);
            }
            $orginam_name = str_replace('.' . $request->file('icon')->getClientOriginalExtension(), '', $request->file('icon')->getClientOriginalName());
            $orginam_name = str_replace(' ', '', $orginam_name);
            $fileName = uniqid('img_') . '.' . $request->file('icon')->getClientOriginalExtension();
            $request->file('icon')->move(public_path('uploads/diet'), $fileName);
            $catagory->img = $fileName;
        }
        $catagory->desc_before = $request->input('desc_before');
        $catagory->desc_after = $request->input('desc_after');
        $catagory->save();
        $catagory->disease()->sync($request->input('disease'));
        $request->session()->flash('successful', 'موضوع با موفقیت ویرایش شد.');

        return redirect(url('/admin/meal/category'));
    }

    public function changeStatus(Request $request, Meal_category $catagory)
    {
        $this->authorize('edit_admin_meal_category');
        $this->changeStatusIteration($catagory);
        if ($catagory->status) {
            $request->session()->flash('successful', 'رژیم با موفقیت فعال شد');
        } else {
            $request->session()->flash('successful', 'رژیم با موفقیت غیرفعال شد.');
        }

        return redirect(url('/admin/meal/category'));
    }

    public function changeStatusIteration(Meal_category $catagory)
    {
        $this->authorize('edit_admin_meal_category');
        if (Meal_category::whereParent($catagory->id)->exists()) {
            foreach (Meal_category::whereParent($catagory->id)->get() as $cat) {
                if (Meal_category::whereParent($catagory->id)->exists()) {
                    $this->changeStatusIteration($cat);
                } else {
                    $cat->status = !$cat->status;
                    $cat->save();
                }
            }
        }
        $catagory->status = !$catagory->status;
        $catagory->save();
    }

    //
}
