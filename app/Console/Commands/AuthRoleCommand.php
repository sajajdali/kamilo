<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Spatie\Permission\Models\Role;

class AuthRoleCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
	protected $signature = 'auth:role {name} {default?} {--R|remove}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create or remove role';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
	    $role = $this->argument('name');
	    $defualt = $this->argument('default') or false;
	    if( $is_remove = $this->option('remove') ) {
		    // remove permission
		    if(Role::where('name', 'LIKE', '%' . $role)->delete()) {
			    $this->warn('Role ' . $role . ' deleted.');
		    } else {
			    $this->warn('No role for ' . $role . ' found!');
		    }
	    }else{
	    	Role::firstOrCreate(['name' => $role,'is_default'=>$defualt ]);
		    $this->info('role '.$role.' created successfully');
	    }
    }
}
