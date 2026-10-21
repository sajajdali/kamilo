<?php

namespace Modules\Diet\Http\Controllers\Admin\Diet;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Modules\Diet\Entities\Request\Request_rejim_program;

class OfflineDietController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('view_admin_user_offline_diet');
        $user = Auth::user();
        $title = 'رژیم‌های آفلاین';
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
                if ($request->input('file') != 0) {
                    if ($request->input('file') == 1) {
                        $q->where('link', null);
                    } else {
                        $q->where('link', '<>', null);
                    }
                }
                $q->where('offline', '1');
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
            })->orderBy('created_at', 'desc')->paginate(20);
            $data['rejimList']->appends(Input::except('page'));
        } else {
            $data['rejimList'] = Request_rejim_program::where('offline', '1')->orderBy('created_at', 'desc')->paginate(20);
            //chart data
            $chart = array();
            $day_of_month = jdate('j', '', '', '', 'en');
            for ($i = 1; $i <= $day_of_month; $i++) {
                $start_date = date('Y-m-d H:i:s', jmktime(0, 0, 0, jdate('n', '', '', '', 'en'), $i, jdate('Y', '', '', '', 'en')));
                $end_date = date('Y-m-d H:i:s', jmktime(24, 59, 59, jdate('n', '', '', '', 'en'), $i, jdate('Y', '', '', '', 'en')));
                $chart[] = Request_rejim_program::where(function ($query) use ($start_date, $end_date, $user) {
                    $query->where('offline', '1');
                    $query->where('active', true);
                    $query->where('created_at', '>=', $start_date);
                    $query->where('created_at', '<=', $end_date);
                })->count();;
            }
            $data['statistics']['chart'] = $chart;
        }

        return view('diet::admin.diet.offline.dashboard', compact('data', 'user', 'title'));
    }

    public function detail(Request $request, Request_rejim_program $program)
    {
        $this->authorize('edit_admin_user_offline_diet');
        $data = array();
        $title = "تجویز رژیم آفلاین";
        $data['user'] = $program->user;
        $data['program'] = $program;
        return view('diet::admin.diet.offline.dietDetail', compact('data', 'title'));
    }

    public function store(Request $request, Request_rejim_program $program)
    {
        $this->authorize('edit_admin_user_offline_diet');
        $user = $program->user;
        $messages = [
            'file3.required' => 'لطفا فایل رژیم را انتخاب نمایید',
            'file3.mimes' => 'لطفا از فرمت pdf استفاده نمایید',
            'file3.max' => 'حداکثر حجم فایل باید کمتر از ۸ مگابایت باشد',
        ];
        $this->validate($request, [
            'file3' => 'required|mimes:pdf|max:800000',
        ], $messages);
        $fileName = $user->id . '_RejimFIT_' . $program->number_request . '.' . $request->file('file3')->getClientOriginalExtension();
        $request->file('file3')->move(public_path('uploads/dietFiles'), $fileName);

        $no_shift = Request_rejim_program::where('user_id', $program->user_id)->whereActive(1)->get()->count();
        // change in use rejim
        Request_rejim_program::where('id', '<>', $program->id)->whereUserId($program->user_id)->update(['inUse' => '0']);
        $dateEndRejim = (new Carbon('now'))->addDays(15)->toDateTimeString();
        if ($program->link == null) {
            $program->calorie = 0;
            $program->date_end = $dateEndRejim;
            $program->no_shift = $no_shift;
            $program->inUse = 1;
        }
        $program->link = $fileName;
        $program->save();

        //push answer
        \Session::flash('successful', 'فایل رژیم با موفقیت برای کاربر ارسال شد');
        return redirect(url('admin/offlineDiet'));
    }
}