<?php

namespace App\Http\Controllers\Admin\Role;

use App\Base\AdminController;
use App\Base\Authorizable;
use App\User;
use Illuminate\Http\Request;
use Nwidart\Modules\Facades\Module;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleController extends AdminController {
	use Authorizable;
	protected $routeBaseName = 'admin.role.';

	/**
	 * Role list
	 *
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
	 */
	public function index() {
		$roles = Role::all();

		return view('admin.role.index', compact('roles'));
	}

	/**
	 * Store role
	 *
	 * @param Request $request
	 */
	public function store(Request $request) {
		$messages = [
			'title.required'        => 'عنوان نقش را وارد نمایید',
			'display_name.required' => 'نام نمایشی نقش را وارد نمایید',
			'is_admin.required'     => 'وضعیت نقش را انتخاب نمایید',
		];
		$this->validate($request, [
			'title'        => 'required',
			'display_name' => 'required',
//			'access'      => 'required',
			'is_admin'     => 'required|min:1',
		], $messages);
		$role               = new Role();
		$role->name         = $request->input('title');
		$role->display_name = $request->input('display_name');
		$role->guard_name   = "web";
		if($request->input('is_admin') == "2" && $request->input('is_default') != null && $request->input('is_default') == "1") {
			$role->is_default = true;
		} else {
			$role->is_default = false;
		}
		$role->save();
		if($request->input('is_admin') == "1") {
			$adminPermission = Permission::findByName("ADMIN");
			$role->givePermissionTo($adminPermission);
		}
		if($request->input('administor') != null && $request->input('administor') == "1") {
			$sperAdminPermission = Permission::findByName("SUPERADMIN");
			$role->givePermissionTo($sperAdminPermission);
			$request->session()->flash('successful', 'نقش جدید با مووفقیت ایجاد شد');

			return redirect()->route($this->routeBaseName . 'index');
		}
		//main access
		foreach($request->input('access') as $access) {
			$permission = Permission::findByName($access);
			if($permission->exists()) {
				$role->givePermissionTo($permission);
			}
		}
		//radio accesss
		$modules = Module::allEnabled();
		foreach($modules as $module) {
			$moduleConfig = config($module->getLowerName() . ".Permission") ?? array();
			if(sizeof($moduleConfig) == 0) {
				continue;
			}
			if(isset($moduleConfig['permissions']['Radio']) && sizeof($moduleConfig['permissions']['Radio']) >= 1) {
				foreach($moduleConfig['permissions']['Radio'] as $key => $radioItems) {
					if($request->input($key) != null) {
						$permission = Permission::findByName($request->input($key));
						if($permission->exists()) {
							$role->givePermissionTo($permission);
						}
					}
				}
			}
		}
		$mainConfig = config('permissions');
		foreach($mainConfig as $item) {
			if(isset($item['permissions']['Radio']) && sizeof($item['permissions']['Radio']) >= 1) {
				foreach($item['permissions']['Radio'] as $key => $radioItems) {
					if($request->input($key) != null) {
						$permission = Permission::findByName($request->input($key));
						if($permission->exists()) {
							$role->givePermissionTo($permission);
						}
					}
				}
			}
		}
		$request->session()->flash('successful', 'نقش جدید با مووفقیت ایجاد شد');

		return redirect()->route($this->routeBaseName . 'index');
	}

	/**
	 * Change defualt role
	 *
	 * @param Request $request
	 * @param Role $role
	 *
	 * @return \Illuminate\Http\RedirectResponse
	 * @throws \Illuminate\Auth\Access\AuthorizationException
	 */
	public function show(Request $request, Role $role) {
		$this->authorize("edit_admin_role");
		if( ! $role->hasPermissionTo("ADMIN")) {
			$defualtRole             = Role::where('is_default', true)->first();
			$defualtRole->is_default = false;
			$defualtRole->save();
			$role->is_default = true;
			$role->save();
		}
		$request->session()->flash('successful', 'نقش پیشفرض با موفقیت تغییر کرد');

		return redirect()->route($this->routeBaseName . 'index');
	}

	/**
	 * Remove role
	 *
	 * @param Request $request
	 * @param Role $role
	 *
	 * @return \Illuminate\Http\RedirectResponse
	 *
	 */
	public function destroy(Request $request, Role $role) {
		if( ! $role->is_default) {
			$users   = User::role($role->name)->get();
			$defRole = Role::where('is_default', true)->first();
			foreach($users as $user) {
				$user->removeRole($role);
				$user->assignRole($defRole);
			}
			$role->delete();
		}
		$request->session()->flash('successful', 'نقش با موفقیت حذف شد');

		return redirect()->route($this->routeBaseName . 'index');
	}

	/**
	 * Edit role
	 *
	 * @param Request $request
	 * @param Role $role
	 *
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
	 */
	public function edit(Request $request, Role $role) {
		return view('admin.role.edit', compact('role'));
	}

	/**
	 * Store edited role
	 *
	 * @param Request $request
	 * @param Role $role
	 *
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function update(Request $request,Role $role) {
		$messages = [
			'title.required'        => 'عنوان نقش را وارد نمایید',
			'display_name.required' => 'نام نمایشی نقش را وارد نمایید',
			'is_admin.required'     => 'وضعیت نقش را انتخاب نمایید',
		];
		$this->validate($request, [
			'title'        => 'required',
			'display_name' => 'required',
//			'access'      => 'required',
			'is_admin'     => 'required|min:1',
		], $messages);
		$role->name         = $request->input('title');
		$role->display_name = $request->input('display_name');
		$roleDefault = Role::where('is_default',true)->first();
		if($request->input('is_admin') == "2" && $request->input('is_default') != null && $request->input('is_default') == "1") {
			$roleDefault->is_default = false;
			$roleDefault->save();
			$role->is_default = true;
		}
		$role->save();
		$allPermissionArray = array();
		if($request->input('is_admin') == "1") {
			$allPermissionArray[] = Permission::whereName("ADMIN")->first()->id;
		}
		if($request->input('administor') != null && $request->input('administor') == "1") {
			$allPermissionArray[] = Permission::findByName("SUPERADMIN")->first()->id;
			$role->syncPermissions($allPermissionArray);
			$request->session()->flash('successful', 'نقش جدید با مووفقیت ایجاد شد');

			return redirect()->route($this->routeBaseName . 'index');
		}
		//main access
		foreach($request->input('access') as $access) {
			$permission = Permission::findByName($access);
			if($permission->exists()) {
				$allPermissionArray[] = $permission->id;
			}
		}
		//radio accesss
		$modules = Module::allEnabled();
		foreach($modules as $module) {
			$moduleConfig = config($module->getLowerName() . ".Permission") ?? array();
			if(sizeof($moduleConfig) == 0) {
				continue;
			}
			if(isset($moduleConfig['permissions']['Radio']) && sizeof($moduleConfig['permissions']['Radio']) >= 1) {
				foreach($moduleConfig['permissions']['Radio'] as $key => $radioItems) {
					if($request->input($key) != null) {
						$permission = Permission::findByName($request->input($key));
						if($permission->exists()) {
							$allPermissionArray[] = $permission->id;
						}
					}
				}
			}
		}
		$mainConfig = config('permissions');
		foreach($mainConfig as $item) {
			if(isset($item['permissions']['Radio']) && sizeof($item['permissions']['Radio']) >= 1) {
				foreach($item['permissions']['Radio'] as $key => $radioItems) {
					if($request->input($key) != null) {
						$permission = Permission::findByName($request->input($key));
						if($permission->exists()) {
							$allPermissionArray[]  = $permission->id;
						}
					}
				}
			}
		}
		$role->permissions()->sync($allPermissionArray);
		$request->session()->flash('successful', 'نقش با موفقیت ویرایش شد');

		return redirect()->route($this->routeBaseName . 'index');
	}

}
