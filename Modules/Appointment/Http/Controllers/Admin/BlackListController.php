<?php

namespace Modules\Appointment\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Appointment\Entities\BlackList;

class BlackListController extends Controller
{
    public function index(Request $request)
    {
        $blackLists = BlackList::orderBy('id', 'DESC');
        // new date filter
        if ($request->get('start_date') <> null){
            $startDate = convertNumber($request->get('start_date'));
            $date = explode("/", convertNumber($startDate));
            $gdate = jalali_to_gregorian($date[0], $date[1], $date[2]);
            $startDateCarbon = Carbon::create($gdate[0], $gdate[1], $gdate[2]);
            $blackLists->whereDate('created_at' ,'>=' ,$startDateCarbon );
        }
        if ($request->get('end_date') <> null){
            $startDate = convertNumber($request->get('end_date'));
            $date = explode("/", convertNumber($startDate));
            $gdate = jalali_to_gregorian($date[0], $date[1], $date[2]);
            $startDateCarbon = Carbon::create($gdate[0], $gdate[1], $gdate[2]);
            $blackLists->whereDate('created_at' ,'<=' ,$startDateCarbon );
        }

        if ($request->get('mobile') <> null){
            $blackLists->where('mobile'  ,$request->get('mobile') );
        }
        // new date filter

        $blackLists = $blackLists->paginate(30)->appends(request()->query());

        return view('appointment::admin.black_list.index', compact('blackLists'));
    }
}
