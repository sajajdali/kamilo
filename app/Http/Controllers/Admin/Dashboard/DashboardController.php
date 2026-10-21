<?php

namespace App\Http\Controllers\Admin\Dashboard;

use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\Blog\Entities\Blog;
use Modules\Diet\Entities\Request\Request_rejim_program;
use Modules\Support\Entities\Ticket;
use Spatie\Permission\Models\Role;

class DashboardController extends Controller
{
	public function index() {
		$data = array();
		$data['top']['user_count'] = User::all()->count();
		$data['top']['blog_count'] = Blog::all()->count();
		$data['top']['diet_request_count'] = Request_rejim_program::active()->count();
		$data['top']['ticket_count'] = Ticket::all()->count();

		$data['chart']['X'] = [];
		$data['chart']['diet_request']['Y'] = [];
		$data['chart']['user']['Y'] = [];

		$now = Carbon::now();
		$dayBefore = Carbon::now()->subDays(30);
        for($i=0;$i<30;$i++){
            $dayBefore->addDay(1);
            $data['chart']['X'][] = jdate('j F',$dayBefore->timestamp);
            $data['chart']['diet_request']['Y'][] = Request_rejim_program::active()->whereBetween('created_at',[$dayBefore->startOfDay()->toDateTimeString(),$dayBefore->endOfDay()->toDateTimeString()])->count();
            $data['chart']['user']['Y'][] = User::whereBetween('created_at',[$dayBefore->startOfDay()->toDateTimeString(),$dayBefore->endOfDay()->toDateTimeString()])->count();
        }

        $data['waitingTicket'] = Ticket::whereStatus(Ticket::STATUS_WAITING)->count();
        $data['offlineDiet'] = Request_rejim_program::active()->offline()->whereLink("")->count();
		return view('admin.dashboard.dashboard',compact('data'));
    }
}
