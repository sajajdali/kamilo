<?php

namespace App\Http\Controllers\Admin\User;

use App\Base\AdminController;
use App\Base\Authorizable;
use App\Support\AdminActivityLogger;
use App\User;
use App\UserMeta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Role;

class UserController extends AdminController {
	use Authorizable;
	protected $routeBaseName = 'admin.user.';

	/**
	 * Display all user
	 *
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
	 */
	public function index() {
		$user  = Auth::user();
if(request()->act != null && request()->act == "search"){
            $users = User::where(function($query){
                if(request()->user_id){
                    $query->whereId(request()->user_id);
                }
                if(request()->email){
                    $query->whereEmail(request()->email);
                }
                if(request()->mobile){
                    $query->whereMobile(request()->mobile);
                }
            })->whereHas('userMetas', function($q) use ($user) {
                if(!$user->can('admin_all_user_access')){
                    $q->where([['meta_key', UserMeta::AGENT], ['meta_value', $user->id]]);
                }
                if(request()->first_name){
                    $q->where([['meta_key', UserMeta::FIRST_NAME], ['meta_value','LIKE', '%'.request()->first_name.'%']]);
                }
            })->whereHas('userMetas', function($q) use ($user) {
                if(request()->last_name){
                    $q->where([['meta_key', UserMeta::LAST_NAME], ['meta_value','LIKE', '%'.request()->last_name.'%']]);
                }
            })->whereHas('roles', function($q) {
                if(intval(request()->role_id) != 0){
                    $q->whereId(request()->role_id);
                }
            })->orderBy('id','DESC')->paginate(50)->appends(request()->query());
        }else{
            $users = User::whereHas('userMetas', function($q) use ($user) {
                if(!$user->can('admin_all_user_access')){
                    $q->where([['meta_key', UserMeta::AGENT], ['meta_value', $user->id]]);
                }
            })->orderBy('id','DESC')->paginate(50)->appends(request()->query());
        }
		return view('admin.user.index', compact('users'));
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create() {
		$roles = Role::all();

		return view('admin.user.create', compact('roles'));
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  \Illuminate\Http\Request $request
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function store(Request $request) {
		$message = [
			'firstname.required'  => 'لطفا نام کاربر را وارد کنید',
			'lastname.required'   => 'لطفا نام خانوادگی کاربر را وارد کنید',
			'mobile.required'     => 'لطفا موبایل کاربر را وارد کنید',
			'mobile.min'          => 'شماره موبایل به درستی وارد نشده',
			'mobile.unique'       => 'این شماره موبایل قبلا در سایت ثبت شده است',
			'email.required'      => 'ایمیل کاربر را وارد نمایید',
			'email.email'         => 'ایمیل کاربر به درستی وارد نشده',
			'email.unique'        => 'این ایمیل قبلا در سایت ثبت شده است',
			'password.required'   => 'کلمه عبور کاربر را وارد نمایید',
			'password.confirmed'  => 'کلمه عبور و تکرار آن یکسان نیست',
			'role.required'       => 'نقش کاربر را انتخاب نمایید',
			'profile_image.image' => 'تصویر کاربر فقط به صورت فایل تصویری مجار است',
            'user_id.integer' => 'ایدی API باید به صورت عدد انگلیسی وارد شود',
            'user_id.unique' => 'کاربر با این ایدی قبلا در سیستم ثبت شده است'
		];
		$roles = [
			'firstname'     => 'required|max:255',
			'lastname'      => 'required|max:255',
			'mobile'        => 'required|min:11|unique:users',
			'email'         => 'required|email|max:255|unique:users',
			'password'      => 'required|confirmed',
			'role'          => 'required',
			'profile_image' => 'image|max:5000',
            //'user_id' => 'integer',
		];
		if($request->user_id != null){
		    $roles['user_id'] = 'integer|unique:users,id';
		}
		$this->validate($request,$roles , $message);
		$mobile   = $request->input('mobile');
		$mobile   = str_replace(' ', '', $mobile);
		$mobile   = str_replace('(', '', $mobile);
		$mobile   = str_replace(')', '', $mobile);
		$birthday = explode("/", $request->input('bri'));
		$b_day    = $birthday[2] ?? 0;
		$b_month  = $birthday[1] ?? 0;
		$b_year   = $birthday[0] ?? 0;
		
		$userCreate = [
            'email' => $request->input('email'),
            'mobile' => $mobile,
            'password' => bcrypt($request->input('password')),
            'last_login' => date('Y-m-d H:i:s'),
        ];
        if($request->user_id != null){
            $userCreate['id'] = $request->user_id;
        }
        $user = User::create($userCreate);
        
		//roles
		$user->roles()->sync($request->input('role'));
		//user metas
		$user->userMetas()->saveMany([
			new UserMeta(['meta_key' => UserMeta::FIRST_NAME, 'meta_value' => $request->input('firstname')]),
			new UserMeta(['meta_key' => UserMeta::LAST_NAME, 'meta_value' => $request->input('lastname')]),
			new UserMeta(['meta_key' => UserMeta::AGENT, 'meta_value' => Auth::user()->id])
		]);
		if($b_day != 0 && $b_month != 0 && $b_year != 0) {
			$user->userMetas()->saveMany([
				new UserMeta(['meta_key' => UserMeta::BIRTHDAY_YEAR, 'meta_value' => $b_year]),
				new UserMeta(['meta_key' => UserMeta::BIRTHDAY_MONTH, 'meta_value' => $b_month]),
				new UserMeta(['meta_key' => UserMeta::BIRTHDAY_DAY, 'meta_value' => $b_day])
			]);
		}
        if ($request->has('national_code')) {
            $user->userMetas()->updateOrCreate(
                ['meta_key' => UserMeta::NATIONAL_CODE],
                ['meta_value' => $request->input('national_code')]
            );
        }
		if($request->hasFile('profile_image')) {
			$fileName = $user->id . uniqid('__img_') . '.' . $request->file('profile_image')->getClientOriginalExtension();;
			$request->file('profile_image')->move(public_path('uploads/avatar'), $fileName);
			$img = new UserMeta(['meta_key' => UserMeta::PROFILE_AVATAR, 'meta_value' => $fileName]);
			$user->userMetas()->save($img);
		}
		if($request->user_id != null){
            $user->userMetas()->saveMany([
                new UserMeta(['meta_key' => UserMeta::USER_API_ID, 'meta_value' => $request->user_id])
            ]);
        }
        AdminActivityLogger::log('user.created.full', $user, [], [
            'user' => AdminActivityLogger::modelValues($user),
            'roles' => $user->roles()->pluck('id')->toArray(),
            'metas' => $this->userMetaSnapshot($user),
        ], [
            'module' => 'user',
            'description' => 'کاربر از پنل مدیریت اضافه شد',
            'request_payload' => $request->except(['password', 'password_confirmation', '_token']),
        ]);
		$request->session()->flash('successful', 'کاربر با موفقیت اضافه شد.');

		return redirect()->route($this->routeBaseName . 'index');
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int $id
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function edit(Request $request, User $user) {
		$roles = Role::all();

		return view('admin.user.edit', compact('user', 'roles'));
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  \Illuminate\Http\Request $request
	 * @param  int $id
	 *
	 * @return \Illuminate\Http\Response
	 */

    public function update(Request $request, User $user) {
        $oldSnapshot = $this->userAuditSnapshot($user);
        $request->merge([
            'mobile' => $this->normalizeMobile($request->input('mobile')),
            'second_mobile' => $this->normalizeMobile($request->input('second_mobile')),
        ]);

        $message = [
            'firstname.required'  => 'لطفا نام کاربر را وارد کنید',
            'lastname.required'   => 'لطفا نام خانوادگی کاربر را وارد کنید',
            'password.confirmed'  => 'کلمه عبور و تکرار آن یکسان نیست',
            'role.required'       => 'نقش کاربر را انتخاب نمایید',
            'profile_image.image' => 'تصویر کاربر فقط به صورت فایل تصویری مجار است',
            'user_id.integer'     => 'ایدی API باید به صورت عدد انگلیسی وارد شود',
            'mobile.required'     => 'لطفا موبایل کاربر را وارد کنید',
            'mobile.min'          => 'شماره موبایل به درستی وارد نشده',
            'mobile.unique'       => 'این شماره موبایل قبلا در سایت ثبت شده است',
            'second_mobile.min'   => 'شماره دوم به درستی وارد نشده است',
            'second_mobile.different' => 'شماره دوم باید با شماره اصلی متفاوت باشد',
        ];

        $this->validate($request, [
            'firstname'     => 'required|max:255',
            'lastname'      => 'required|max:255',
            'mobile'        => [
                'required',
                'min:11',
                Rule::unique($user->getTable(), 'mobile')->ignore($user->getKey(), $user->getKeyName()),
            ],
            'second_mobile' => 'nullable|min:11|max:20|different:mobile',
            'password'      => 'confirmed',
            'role'          => 'required',
            'profile_image' => 'image|max:5000',
        ], $message);

        $user->mobile = $request->input('mobile');

        // اگر پسورد پر شده بود، عوضش کن
        if ($request->filled('password')) {
            $user->password = bcrypt($request->input('password'));
        }

        // ذخیره تغییرات روی خود user
        $user->save();

        //update roles
        $user->roles()->sync($request->input('role'));

        //update metas
        $birthday = explode("/", $request->input('bri'));
        $b_day    = $birthday[2] ?? 0;
        $b_month  = $birthday[1] ?? 0;
        $b_year   = $birthday[0] ?? 0;

        $user->userMetas()->updateOrCreate(
            ['meta_key' => UserMeta::FIRST_NAME],
            ['meta_value' => $request->input('firstname')]
        );
        $user->userMetas()->updateOrCreate(
            ['meta_key' => UserMeta::FIRST_NAME],
            ['meta_value' => $request->input('firstname')]
        );

        $user->userMetas()->updateOrCreate(
            ['meta_key' => UserMeta::LAST_NAME],
            ['meta_value' => $request->input('lastname')]
        );
        $user->userMetas()->updateOrCreate(
            ['meta_key' => UserMeta::BIRTHDAY_YEAR],
            ['meta_value' => $b_year]
        );
        if ($request->has('national_code')) {
            $user->userMetas()->updateOrCreate(
                ['meta_key' => UserMeta::NATIONAL_CODE],
                ['meta_value' => $request->input('national_code')]
            );
        }
        if ($request->filled('second_mobile')) {
            $user->userMetas()->updateOrCreate(
                ['meta_key' => UserMeta::SECOND_MOBILE],
                ['meta_value' => $request->input('second_mobile')]
            );
        } else {
            $user->userMetas()->where('meta_key', UserMeta::SECOND_MOBILE)->delete();
        }
        $user->userMetas()->updateOrCreate(
            ['meta_key' => UserMeta::BIRTHDAY_MONTH],
            ['meta_value' => $b_month]
        );
        $user->userMetas()->updateOrCreate(
            ['meta_key' => UserMeta::BIRTHDAY_DAY],
            ['meta_value' => $b_day]
        );

        if($request->user_id != null){
            $user->userMetas()->updateOrCreate(
                ['meta_key' => UserMeta::USER_API_ID],
                ['meta_value' => $request->user_id]
            );
        }

        if($request->hasFile('profile_image')) {
            $fileName = $user->id . uniqid('__img_') . '.' . $request->file('profile_image')->getClientOriginalExtension();
            $request->file('profile_image')->move(public_path('uploads/avatar'), $fileName);
            $user->userMetas()->updateOrCreate(
                ['meta_key' => UserMeta::PROFILE_AVATAR],
                ['meta_value' => $fileName]
            );
        }

        $user->load('roles', 'userMetas');
        AdminActivityLogger::log('user.updated.full', $user, $oldSnapshot, $this->userAuditSnapshot($user), [
            'module' => 'user',
            'description' => 'اطلاعات کاربر از پنل مدیریت ویرایش شد',
            'request_payload' => $request->except(['password', 'password_confirmation', '_token']),
        ]);

        $request->session()->flash('successful', 'کاربر با موفقیت ,ویرایش شد.');

        return redirect()->route($this->routeBaseName . 'index');
    }

    private function normalizeMobile($mobile)
    {
        $mobile = trim((string) $mobile);
        $mobile = strtr($mobile, [
            '۰' => '0',
            '۱' => '1',
            '۲' => '2',
            '۳' => '3',
            '۴' => '4',
            '۵' => '5',
            '۶' => '6',
            '۷' => '7',
            '۸' => '8',
            '۹' => '9',
            '٠' => '0',
            '١' => '1',
            '٢' => '2',
            '٣' => '3',
            '٤' => '4',
            '٥' => '5',
            '٦' => '6',
            '٧' => '7',
            '٨' => '8',
            '٩' => '9',
        ]);
        $mobile = str_replace([' ', '(', ')', '-', '_'], '', $mobile);

        return preg_replace('/\s+/u', '', $mobile);
    }
	/**
	 * Remove the user
	 *
	 * @param Request $request
	 * @param User $user
	 *
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function destroy(Request $request, User $user) {
        $oldSnapshot = $this->userAuditSnapshot($user);
		$user->delete();
        AdminActivityLogger::log('user.deleted.full', $user, $oldSnapshot, [], [
            'module' => 'user',
            'description' => 'کاربر از پنل مدیریت حذف شد',
        ]);
		$request->session()->flash('successful', 'کاربر با موفقیت حذف شد');

		return redirect()->route($this->routeBaseName . 'index');
	}

    private function userAuditSnapshot(User $user)
    {
        return [
            'user' => AdminActivityLogger::modelValues($user),
            'roles' => $user->roles()->pluck('id')->toArray(),
            'metas' => $this->userMetaSnapshot($user),
        ];
    }

    private function userMetaSnapshot(User $user)
    {
        return $user->userMetas()
            ->get(['meta_key', 'meta_value'])
            ->pluck('meta_value', 'meta_key')
            ->toArray();
    }
}
