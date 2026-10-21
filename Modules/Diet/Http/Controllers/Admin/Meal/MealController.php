<?php

namespace Modules\Diet\Http\Controllers\Admin\Meal;

use App\Base\AdminController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Modules\Diet\Entities\Meal\Meal;
use Modules\Diet\Entities\Meal\Meal_category;
use Modules\Diet\Entities\Meal\Meal_Meta;

class MealController extends AdminController
{
    public $paginate_count = 100;


    public function test()
    {
        $requestId = 1;
        $rehun = makeRejim(1);

        return $rehun;
    }

    public function index(Request $request)
    {
        $this->authorize('view_admin_meal');
        global $paginate_count;
        $title = 'مدیریت وعده‌ها';
        /*----------- Admin Detail ---------------*/
        $user = Auth::user();
        /*-------------- End ----------------------*/
        if ($request->input('act') != null) {
            $meal = Meal::where(function ($query) use ($user, $request) {
                if ($request->input('forMeal') != 0) {
                    $query->where('meal', $request->input('forMeal'));
                }
                if ($request->input('mealType') == 0 || $request->input('mealType') != 2) {
                    if ($request->input('from') != null) {
                        $query->where('calorie', '>=', $request->input('from'));
                    }
                    if ($request->input('to') != null) {
                        $query->where('calorie', '<=', $request->input('to'));
                    }
                }
            })->where(function ($query) use ($user, $request) {
                if ($request->input('mealType') != 0) {
                    if ($request->input('mealType') == 1) {
                        $query->where('meal_type', $request->input('mealType'));
                    } else {
                        $query->where([['meal_type', 2], ['meal', 1]]);
                    }
                } else {
                    $query->where('meal_type', 1)->orwhere([['meal_type', 2], ['meal', 1]]);
                }
            })->whereHas('category', function ($query) use ($request) {
                if ($request->input('mealCategory') != 0) {
                    $query->where('meal_category_id', $request->input('mealCategory'));
                }
            })->whereHas('metas', function ($query) use ($request) {
                if ($request->input('mealType') != 0 && $request->input('mealType') == 2) {
                    if ($request->input('from') != null) {
                        $query->where([['meta_key', 'total_calorie'], ['meta_value', '>=', $request->input('from')]]);
                    }
                    if ($request->input('to') != null) {
                        $query->where([['meta_key', 'total_calorie'], ['meta_value', '<=', $request->input('to')]]);
                    }
                }
            })->orderBy('created_at', 'desc')->paginate($paginate_count);
        } else {
            $meal = Meal::where('meal_type', 1)->orwhere([['meal_type', 2], ['meal', 1]])->orderBy('id', 'desc')->paginate($paginate_count);
        }
        $meal->appends(Input::except('page'));
        $data['category'] = Meal_category::all();

        return view('diet::admin.meal.dashboard', compact('title', 'data', 'meal'));
    }

    public function add()
    {
        $this->authorize('add_admin_meal');
        $title = 'اضافه کردن وعده جدید';
        /*----------- Admin Detail ---------------*/
        $user = Auth::user();
        /*-------------- End ----------------------*/
        $category = Meal_category::where('parent', '0')->get();

        return view('diet::admin.meal.add_meal', compact('title', 'data', 'category'));
    }

    public function delete(Request $request, Meal $meal)
    {
        $this->authorize('delete_admin_meal');

        Meal::whereParent($meal->id)->delete();
        $meal->delete();
        $request->session()->flash('successful', 'وعده با موفقیت حذف شد');

        return redirect(url('/admin/meal'));
    }

    public function edit(Request $request, Meal $meal)
    {
        $this->authorize('edit_admin_meal');

        $title = 'ویرایش وعده';
        /*----------- Admin Detail ---------------*/
        $user = Auth::user();
        /*-------------- End ----------------------*/
        $category = Meal_category::where('parent', '0')->get();
        $baseFood = array();
        foreach ($meal->metas as $list_metas) {
            if ($list_metas->meta_key == 'base_food') {
                $baseFood[] = $list_metas->meta_value;
            } elseif ($list_metas->meta_key == 'days') {
                $data['metas']['days'][] = $list_metas->meta_value;
            } elseif ($list_metas->meta_key == 'season') {
                $data['metas']['season'][] = $list_metas->meta_value;
            } else {
                $data['metas'][$list_metas->meta_key] = $list_metas->meta_value;
            }

            //echo "meta_key : ".$list_metas->meta_key." | meta value : ".$list_metas->meta_value .'<br />';
        }
        switch ($meal->meal_type) {
            case 1:
                $data['meal']['step1'][] = $meal->meal;
                $data['calorie']['step1'][] = $meal->calorie;
                $data['content']['step1'][] = $meal->content;
                $content['step1'][] = $meal->content;
                if (count($baseFood) > 0) {
                    $data['base_food']['step1'][] = implode(",", $baseFood);
                }
                break;
            case 2:
                $parent = $meal->parent;
                $data['meal']['step1'][] = $meal->meal;
                $data['calorie']['step1'][] = $meal->calorie;
                $data['content']['step1'][] = $meal->content;
                $content['step1'][] = $meal->content;
                if (count($baseFood) > 0) {
                    $data['base_food']['step1'][] = implode(",", $baseFood);
                }
                $i = 1;
                foreach ($meal->children as $key) {
                    //$meta = Meal_Meta::where('meal_id',$key->id)->get();
                    $meta = $key->metas;
                    $baseFood = [];
                    foreach ($meta->where('meta_key', 'base_food') as $list_metas) {
                        //TODO: chanaged
//							if($list_metas->meta_key == 'base_food'){
                        $baseFood[] = $list_metas->meta_value;
//							}
                    }
                    if (!$meta->where('meta_key', 'replace_food')->isEmpty()) {
                        $data['replace_food']['step2'][$i] = $meta->where('meta_key', 'replace_food')->first()->meta_value;
                    }
                    if (count($baseFood) > 0) {
                        $data['base_food']['step2'][$i] = implode(",", $baseFood);
                    }
                    $data['calorie']['step2'][$i] = $key->calorie;
                    $data['content']['step2'][$i] = $key->content;
                    $i++;
                }
                break;
        }
        //dd($meal);
        $data['mealCat'] = $meal->category;

        return view('diet::admin.meal.edit_meal', compact('title', 'data', 'category', 'meal', 'content'));
    }

    protected function addDetalList($request, $Meal, $mealId)
    {
        $this->authorize('group_delete_admin_meal');
        if ($mealId != null) {
            Meal_Meta::where('meal_id', $mealId)->where('meta_key', Meal::MEAL_META_TYPE_LOSE)->delete();
            Meal_Meta::where('meal_id', $mealId)->where('meta_key', Meal::MEAL_META_TYPE_GAIN)->delete();
            Meal_Meta::where('meal_id', $mealId)->where('meta_key', Meal::MEAL_META_TYPE_FIXING)->delete();
        }
        if ($request->input('rejim_type.loss') != null) {
            $mealMetas = new Meal_Meta();
            $mealMetas->meta_key = Meal::MEAL_META_TYPE_LOSE;
            $mealMetas->meta_value = $request->input('rejim_type.loss');
            $Meal->metas()->save($mealMetas);
        }
        if ($request->input('rejim_type.gain') != null) {
            $mealMetas = new Meal_Meta();
            $mealMetas->meta_key = Meal::MEAL_META_TYPE_GAIN;
            $mealMetas->meta_value = $request->input('rejim_type.gain');
            $Meal->metas()->save($mealMetas);
        }
        if ($request->input('rejim_type.fixing') != null) {
            $mealMetas = new Meal_Meta();
            $mealMetas->meta_key = Meal::MEAL_META_TYPE_FIXING;
            $mealMetas->meta_value = $request->input('rejim_type.fixing');
            $Meal->metas()->save($mealMetas);
        }
        if ($mealId != null) {
            Meal_Meta::where('meal_id', $mealId)->where('meta_key', 'season')->delete();
        }
        if ($request->input('season.*') != null) {
            foreach ($request->input('season.*') as $key => $value) {
                $mealMetas = new Meal_Meta();
                $mealMetas->meta_key = 'season';
                $mealMetas->meta_value = $value;
                $Meal->metas()->save($mealMetas);
            }
        }
        if ($mealId != null) {
            Meal_Meta::where('meal_id', $mealId)->where('meta_key', 'days')->delete();
        }
        if ($request->input('days.*') != null) {
            foreach ($request->input('days.*') as $key) {
                $mealMetas = new Meal_Meta();
                $mealMetas->meta_key = 'days';
                $mealMetas->meta_value = $key;
                $Meal->metas()->save($mealMetas);
                if ($key == 'all') {
                    break;
                }
            }
        }
    }

    public function store(Request $request, $mealId = null)
    {
        $this->authorize('edit_admin_meal');
        $messages = [
            'cat.required' => 'لطفا یک الگو را برای این وعده انتخاب کنید',
            'calorie.step1.*' => 'لطفا میزان کالری را به درستی برای وعده غذایی وارد کنید',
            'content.step1.*' => 'لطفا دستور وعده را وارد کنید',
            'meal.step1.*' => 'لطفا وعده را وارد کنید',
            'days.*' => 'لطفا حداقل یک روز را برای این الگو انتخاب کنید',
            'calorie.step2.*' => 'لطفا میزان کالری را به درستی برای وعده غذایی وارد کنید',
            'content.step2.*' => 'لطفا دستور وعده را وارد کنید',
            'meal.step2.*' => 'لطفا وعده را وارد کنید',
            'calorie.step3.*' => 'لطفا میزان کالری را به درستی برای وعده غذایی وارد کنید',
            'content.step3.*' => 'لطفا دستور وعده را وارد کنید',
            'meal.step3.*' => 'لطفا وعده را وارد کنید',
            'season.required' => 'لطفا حداقل یک فصل را انتخاب کنید',
        ];
        $this->validate($request, [
            'cat' => 'required',
            'days' => 'required',
            'season' => 'required'
        ], $messages);
        $listCat = $request->cat;
        foreach ($listCat as $cat) {
            $catMod = $cat;
            $typeCategory = Meal_category::where('id', $catMod)->first()->type;
            break;
        }
        $mealHelper = null;
        switch ($typeCategory) {
            // رژیم بر مبنای چرخشی
            case 1:
                $this->validate($request, [
                    'meal.step1.*' => 'required',
                    'content.step1.*' => 'required',
                    'calorie.step1.*' => 'required|integer',
                ], $messages);
                foreach ($request->input('meal.step1') as $key => $meal) {
                    if ($mealId == null) {
                        $Meal = new Meal();
                    } else {
                        $Meal = Meal::where('id', $mealId)->first();
                    }
                    $Meal->calorie = $request->input("calorie.step1.$key");
                    $Meal->meal = $meal;
                    $Meal->meal_type = 1;
                    $Meal->content = $request->input("content.step1.$key");
                    $Meal->save();
                    $Meal->category()->sync($request->input('cat'));
                    $this->addDetalList($request, $Meal, $mealId);
                    if ($mealId != null) {
                        Meal_Meta::where('meal_id', $mealId)->where('meta_key', 'base_food')->delete();
                    }
                    $base_foodArray = $request->input('base_food.step1.*');
                    if ($base_foodArray != null) {
                        foreach ($base_foodArray as $key => $base_food) {
                            $base_food = explode(',', $base_food);
                            if (sizeof($base_food) >= 1) {
                                foreach ($base_food as $one_base_foof) {
                                    $mealMetas = new Meal_Meta();
                                    $mealMetas->meta_key = 'base_food';
                                    $mealMetas->meta_value = $one_base_foof;
                                    $Meal->metas()->save($mealMetas);
                                }
                            }
                        }
                    }
                }
                $request->session()->flash('successful', 'وعده با موفقیت اضافه شد');
//                return redirect(url('/admin/meal'));
                break;
            // رژیم بر مبنای چرخشی
            case 2:
                $this->validate($request, [
                    'meal.step2.*' => 'required',
                    'content.step2.*' => 'required',
                    'calorie.step2.*' => 'required|integer',
                ], $messages);
                $counter = 0;
                $parent = null;
                $totalCalories = 0;
                foreach ($request->input('calorie.step2.*') as $key => $meal) {
                    $i = $key + 1;
                    if ($mealId == null) {
                        $Meal = new Meal();
                    } else {
                        //TODO: add parent here
                        $Meal = Meal::where([['meal', $i], ['parent', $mealId]])->first();
//						$mealId = $Meal->id;
                        $parent = $Meal->parent;
                    }
                    $Meal->calorie = $request->input("calorie.step2.$i");
                    $totalCalories = $totalCalories + $Meal->calorie;
                    $Meal->meal = $i;
                    $Meal->meal_type = 2;
                    $Meal->parent = $parent;
                    $Meal->content = $request->input("content.step2.$i");
                    $Meal->save();
                    if ($counter == 0) {
                        $Meal->category()->sync($request->input('cat'));
                        $counter++;
                    }
                    $mealId2 = $Meal->id;
                    if ($parent == null) {
                        //TODO: $Meal==$lastMealDatabase
                        $lastMealDatabase = Meal::orderBy('id', 'desc')->first();
                        $parent = $lastMealDatabase->id;
                        $lastMealDatabase->parent = $parent;
                        $lastMealDatabase->save();
                    }
                    $this->addDetalList($request, $Meal, $mealId2);
                    $replaceFood = $request->input("replace_food.step2.$i");
                    if ($replaceFood != null && Meal_Meta::where([['meal_id', $mealId2], ['meta_key', 'replace_food']])->exists()) {
                        Meal_Meta::where([['meal_id', $mealId2], ['meta_key', 'replace_food']])->update(array(
                            'meta_value' => $replaceFood,
                        ));
                    } else if ($replaceFood == null && Meal_Meta::where([['meal_id', $mealId2], ['meta_key', 'replace_food']])->exists()) {
                        Meal_Meta::where([['meal_id', $mealId2], ['meta_key', 'replace_food']])->delete();
                    } else if ($replaceFood != null && !Meal_Meta::where([['meal_id', $mealId2], ['meta_key', 'replace_food']])->exists()) {
                        $mealMetas = new Meal_Meta();
                        $mealMetas->meta_key = 'replace_food';
                        $mealMetas->meta_value = $replaceFood;
                        if ($replaceFood != null) $Meal->metas()->save($mealMetas);
                    }
                    $base_foodArray = $request->input("base_food.step2.$i");
                    Meal_Meta::where([['meal_id', $mealId2], ['meta_key', 'base_food']])->delete();
                    if ($base_foodArray != null) {
                        $base_food = explode(',', $base_foodArray);
                        if (sizeof($base_food) >= 1) {
                            foreach ($base_food as $one_base_foof) {
                                $mealMetas = new Meal_Meta();
                                $mealMetas->meta_key = 'base_food';
                                $mealMetas->meta_value = $one_base_foof;
                                $Meal->metas()->save($mealMetas);
                            }
                        }
                    }
                }
                //store total calories
                if ($parent != null && Meal_Meta::where([['meal_id', $parent], ['meta_key', 'total_calorie']])->exists()) {
                    Meal_Meta::where([['meal_id', $parent], ['meta_key', 'total_calorie']])->update(array(
                        'meta_value' => $totalCalories,
                    ));
                } else {
                    $mealMetas = new Meal_Meta();
                    $mealMetas->meta_key = 'total_calorie';
                    $mealMetas->meta_value = $totalCalories;
                    Meal::find($parent)->metas()->save($mealMetas);
                }
                $request->session()->flash('successful', 'خبر با موفقیت بروزرسانی شد.');
//                return redirect(url('/admin/meal'));
                break;
            default:
                $this->validate($request, [
                    'meal.step3.*' => 'required',
                    'content.step3.*' => 'required',
                    'calorie.step3.*' => 'required|integer',
                ], $messages);
                $Meal = new Meal();
                $Meal->calorie = $request->input("calorie.step3.0");
                $Meal->meal = '0';
                $Meal->meal_type = 3;
                $Meal->content = $request->input("content.step3.0");
                $Meal->save();
                $Meal->category()->sync($request->input('cat'));
                $this->addDetalList($request, $Meal, $mealId);
        }

        return redirect(url('/admin/meal'));
//        if(sizeof($request->input('cat')) >= 1) {
//            foreach ($request->input('cat') as $catd_id) {
//                $Meal->category()->attach($catd_id);
//                DB::table('meal_category')
//                    ->where('id', $catd_id)
//                    ->increment('counter');
//            }
//        }
    }
}
