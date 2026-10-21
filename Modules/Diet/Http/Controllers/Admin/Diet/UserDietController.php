<?php

namespace Modules\Diet\Http\Controllers\Admin\Diet;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Modules\Diet\Entities\Request\Request_rejim_program;

class UserDietController extends Controller
{

    public function index(Request $request)
    {
        $this->authorize('view_admin_user_meal');
        $user = Auth::user();
        $title = 'رژیم های دریافتی';
        if ($request->input('act') != null) {
            $data['rejimList'] = Request_rejim_program::where(function ($q) use ($request) {
                if ($request->input('code') != null) {
                    $q->where('number_request', $request->input('code'));
                }
                if ($request->input('start_at') != null) {
                    $from = explode('/', $request->input('start_at'));
                    $from = jalali_to_gregorian($from[0], $from[1], $from[2], '-');
                    $from = $from . ' 00:00:00';
                    $q->where('created_at', '>=', $from);
                }
                if ($request->input('end_at') != null) {
                    $to = explode('/', $request->input('end_at'));
                    $to = jalali_to_gregorian($to[0], $to[1], $to[2], '-');
                    $to = $to . ' 00:00:00';
                    $q->where('created_at', '<=', $to);
                }
            })->whereHas('user', function ($query) use ($request) {
                //serach by sender name
                if ($request->input('name') != null) {
                    $query->whereHas('metas', function ($q) use ($request) {
                        $q->where('meta_key', 'FIRSTNAME')
                            ->where('meta_value', 'LIKE', '%' . $request->input('name') . '%')
                            ->orwhere('meta_key', 'LASTNAME')
                            ->where('meta_value', 'LIKE', '%' . $request->input('name') . '%');
                    });
                }
            })->whereHas('transaction', function ($query) use ($request) {
                //serach by sender name
                if ($request->input('status') != 0) {
                    if ($request->input('status') == 1) {
                        $query->where('transaction_meta', 'پرداخت آنلاین از اپلیکیشن برای رژیم');
                    } else {
                        $query->where('transaction_meta', '<>', 'پرداخت آنلاین از اپلیکیشن برای رژیم');
                    }
                }
            })->orderBy('created_at', 'desc')->paginate(20);
            $data['rejimList']->appends(Input::except('page'));
        } else {
            $data['rejimList'] = Request_rejim_program::orderBy('created_at', 'desc')->paginate(20);
            //chart data
            $chart = array();
            $day_of_month = jdate('j', '', '', '', 'en');
            for ($i = 1; $i <= $day_of_month; $i++) {
                $start_date = date('Y-m-d H:i:s', jmktime(0, 0, 0, jdate('n', '', '', '', 'en'), $i, jdate('Y', '', '', '', 'en')));
                $end_date = date('Y-m-d H:i:s', jmktime(24, 59, 59, jdate('n', '', '', '', 'en'), $i, jdate('Y', '', '', '', 'en')));
                $chart[] = Request_rejim_program::where(function ($query) use ($start_date, $end_date, $user) {
                    $query->where('active', true);
                    $query->where('created_at', '>=', $start_date);
                    $query->where('created_at', '<=', $end_date);
                })->count();;
            }
            $data['statistics']['chart'] = $chart;

        }

        return view('diet::admin.diet.dashboard', compact('data', 'user', 'title'));
    }

    public function delete(Request $request)
    {
        $this->authorize('delete_admin_user_meal');
        Request_rejim_program::where('active', 0)->delete();
        $request->session()->flash('sys_error', 'همه پرداخت نشده ها با موفقیت حذف شد');

        return redirect()->back();
    }

    public function deleteOneItem(Request $request, Request_rejim_program $program)
    {
        $this->authorize('delete_admin_user_meal');
        $program->delete();
        $request->session()->flash('successful', 'رژیم با موفقیت حذف شد');
        return redirect()->back();
    }
}
