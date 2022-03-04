<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Nwidart\Modules\Facades\Module;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class AuthPermissionCommand extends Command {
	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'auth:permission {name} {role?} {--R|remove}';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Create permmission from module name';

	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct() {
		parent::__construct();
	}

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function handle() {
		$mName  = $this->argument('name');
		$role   = $this->argument('role');
		if($mName == "global") {
			$permmissions = array();
			$permissionArray = config('permissions');
			foreach($permissionArray as $per) {
				$permmissions[] = $per['group']['name'];
			}
			foreach($per['permissions'] as $key => $value) {
				if($key == "Radio") {
					continue;
				}
				foreach($value as $key2 => $item) {
					$permmissions[] = $key2;
				}
			}
			//radio items
			if(isset($per['permissions']['Radio'])) {
				foreach($per['permissions']['Radio'] as $key => $value) {
					foreach($value as $key2 => $value) {
						$permmissions[] = $key2;
					}
				}
			}
			if(sizeof($permmissions) >= 1) {
				foreach($permmissions as $permission) {
					if($is_remove = $this->option('remove')) {
						// remove permission
						if(Permission::where('name', $permission)->delete()) {
							$this->info('Permissions ' . $permission . ' deleted.');
						} else {
							$this->warn('No permissions for ' . $permission . ' found!');
						}

					} else {
						$permissionObjext = Permission::firstOrCreate(['name' => $permission]);
						$this->info('Permmision "' . $permission . '" added successfully');
						if($role = Role::where('name', $role)->first()) {
							$role->syncPermissions($permissionObjext);
							$this->info('Permmision added to role ' . $role->name . ' successfully');
						}
					}
				}
			}
			return;
		}
		$module = Module::find($mName);
		if($module != null) {
			$permmissions = array();
			$permissionArray = config($mName . '.Permission');
			$permmissions[]  = $permissionArray['group']['name'];
			foreach($permissionArray['permissions'] as $key => $value) {
				if($key == "Radio") {
					continue;
				}
				foreach($value as $perm => $perName) {
					$permmissions[] = $perm;
				}
			}
			if(isset($permissionArray['permissions']['Radio'])) {
				foreach($permissionArray['permissions']['Radio'] as $key => $value) {
					foreach($value as $perm => $perName) {
						$permmissions[] = $perm;
					}
				}
			}
			if($permmissions != null) {
				foreach($permmissions as $permission) {
					if($is_remove = $this->option('remove')) {
						// remove permission
						if(Permission::where('name', $permission)->delete()) {
							$this->info('Permissions ' . $permission . ' deleted.');
						} else {
							$this->warn('No permissions for ' . $permission . ' found!');
						}

					} else {
						$permissionObjext = Permission::firstOrCreate(['name' => $permission]);
						$this->info('Permmision "' . $permission . '" added successfully');
						if($role = Role::where('name', $role)->first()) {
							$role->syncPermissions($permissionObjext);
							$this->info('Permmision added to role ' . $role->name . ' successfully');
						}
					}
				}
			} else {
				$this->error('Config file dose not find');
			}
		} else {
			$this->error('There is no module with name "' . $mName . '".');
		}

		return;
	}
}
