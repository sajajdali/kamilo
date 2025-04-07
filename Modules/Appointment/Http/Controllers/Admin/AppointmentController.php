<?php

namespace Modules\Appointment\Http\Controllers\Admin;

use App\Base\AdminApiController;
use App\User;
use App\UserMeta;
use Auth;
use Cassandra\Date;
use Hash;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Arr;
use Modules\Appointment\Entities\Appointment;
use Modules\Appointment\Entities\AppointmentException;
use Modules\Appointment\Entities\AppointmentPart;
use Modules\Appointment\Entities\AppointmentUser;
use Mpdf\Tag\Th;
use phpDocumentor\Reflection\Types\This;
use Spatie\Permission\Models\Role;
use Carbon\Carbon;


class AppointmentController extends AdminApiController
{
    public function statusSms()
    {
        $status = getInfoGhasedak();
        return view('appointment::admin.appointment.statusSms', compact('status'));
    }

    public function report()
    {
        return view('appointment::admin.report.index');
    }

    public function reportCreate()
    {
        return view('appointment::admin.report.create');
    }

    public function reportStore(Request $request)
    {
        $request->validate([
            'file' => 'required',
        ]);

        $fileName = 'report.'.$request->file->extension();

        $request->file->move(public_path('uploads/report'), $fileName);
        update_option('REPORT_FILE', $fileName);
        update_option('REPORT_COMMENT', $request->comment);
        update_option('REPORT_TITLE', $request->title);
        update_option('REPORT_TIME', jdate("Y/m/d ساعت H:i:s"));
        update_option('REPORT_TIME_CREATE_AT', Carbon::now());

        return redirect(route('admin.report.index'))
            ->with('successful','فرم با موفقیت اضافه شد');

    }

    function apiIndex(Request $request)
    {
        $doctors = User::role(AppointmentPart::DOCTOR_ROLES)->get('id')->map(function ($user) {
            $user['name'] = $user->full_name;
            $user['parts'] = $user->appointmentParts()->get(['id', 'title']);
            return $user;
        });
        return response()->json($doctors, 200, array(), JSON_UNESCAPED_UNICODE);
    }

    function webIndex(Request $request)
    {
        $data['admin_list'] = User::role(AppointmentPart::DOCTOR_ROLES)->get();
        $data['parts'] = AppointmentPart::whereParentId(0)->get();
        return view('appointment::admin.appointment.index', compact('data'));
    }

    public function create()
    {

        $dayOfWeek = [
            "1" => [
                'name' => "شنبه",
                "id" => "sat"
            ],
            "2" => [
                'name' => "یک‌شنبه",
                "id" => "sun"
            ],
            "3" => [
                'name' => "دوشنبه",
                "id" => "mon"
            ],
            "4" => [
                'name' => "سه‌شنبه",
                "id" => "tue"
            ],
            "5" => [
                'name' => "چهارشنبه",
                "id" => "wed"
            ],
            "6" => [
                'name' => "پنج‌شنبه",
                "id" => "thu"
            ],
            "7" => [
                'name' => "جمعه",
                "id" => "fri"
            ],
        ];
        $user_id = intval(request()->doctor_id);
        $consult_part_id = intval(request()->part_id);
        $part = AppointmentPart::findOrfail($consult_part_id);
        $data['admin_edited'] = User::findOrfail($user_id);
        foreach (Role::all() as $role) {
            if (!$role->hasPermissionTo("ADMIN")) {
                $all_role[] = $role;
            }
        }
        /*------------ Send old data ----------------*/
        if ($data['admin_edited']->appointment()->where('appointment_part_id', $consult_part_id)->exists()) {
            $old_data = $data['admin_edited']->appointment->where('appointment_part_id', $consult_part_id)->first();
            //visit time data
            $data['edit'][Appointment::JSON_PUBLIC_TIME] = $this->timeToString($old_data->time_for_visit[Appointment::JSON_PUBLIC_TIME]);
            $data['edit'][Appointment::JSON_DAY_BEFORE] = $old_data->setting[Appointment::JSON_DAY_BEFORE];
            $data['edit'][Appointment::JSON_DAY_AFTER] = $old_data->setting[Appointment::JSON_DAY_AFTER];
            $data['edit']['interference'] = $old_data->interference;

            //reformat time in part time
            if ($old_data->time_for_visit[Appointment::JSON_STATUS]) {
                $data['edit']['part_time'] = array();
                for ($i = 0; $i < sizeof($old_data->time_for_visit[Appointment::JSON_TIME]); $i++) {
                    $data['edit']['part_time'][$i][Appointment::JSON_TIME] = $this->timeToString($old_data->time_for_visit[Appointment::JSON_TIME][$i][Appointment::JSON_TIME]);
                }
            }
        } else {
            $data['edit'] = "";
            $old_data = null;
        }
        if (request()->wantsJson()) {
            $roleApi = [];
            foreach ($all_role as $item) {
                $roleApi[] = [
                    'id' => $item->id,
                    'title' => $item->display_name
                ];
            }
            return response()->json([
                'data' => $data,
                'old_data' => $old_data,
                'part' => $part,
                'visit_type' => Appointment::$VISIT_TYPE,
                'agreement' => Appointment::$AGREEMENT_TYPE,
                'day_of_week' => $dayOfWeek,
                'all_role' => $roleApi
            ], 200, array(), JSON_UNESCAPED_UNICODE);
        } else {

            $title = 'ویرایش بخش';
            return view('appointment::admin.appointment.create',
                compact('title', 'data', 'all_role', 'old_data', 'part', 'dayOfWeek'));
        }
    }

    public function store(Request $request)
    {


        $setting = [];
        $costInsert = [];
        $contentInsert = [];
        $timeForVisitInsert = [];
        $interferenceInsert = [];

        //region Cost Part
        $cost = ($request->cost != null ? $request->cost : '');
        $percent = ($request->percent != null ? $request->percent : '');
        $all_role = [];
        foreach (Role::all() as $role) {
            if (!$role->hasPermissionTo("ADMIN")) {
                $all_role[] = $role;
            }
        }
        foreach (Appointment::$AGREEMENT_TYPE as $key => $value) {
            $temp_cost = array();
            foreach ($all_role as $role) {
                $temp_cost[Appointment::JSON_COST][$role->id] = str_replace(' , ', '', $cost[$key][$role->id]);
                $temp_cost[Appointment::JSON_PERCENT][$role->id] = $percent[$key][$role->id];
            }
            $costInsert[$key] = $temp_cost;
        }
        //endregion
        //region Interference Part
        if ($request->interference != null) {
            $interferenceInsert[Appointment::JSON_STATUS] = true;
            $interferenceInsert[Appointment::JSON_ITEMS] = Arr::wrap($request->interference_parts);
        } else {
            $interferenceInsert[Appointment::JSON_STATUS] = false;
        }
        //endregion
        //region Time Part
        $has_multi_part = ($request->parttime_status != null ? $request->parttime_status : false);
        if ($has_multi_part) {
            $timeForVisitInsert[Appointment::JSON_STATUS] = true;
            $timeForVisitInsert[Appointment::JSON_SELECT_TYPE] = $request->parttime_multiselect;
            $visit_time = $request->time_for_visit;
            if ($visit_time != null) {
                $visit_array = explode(':', $visit_time);
                $timeForVisitInsert[Appointment::JSON_PUBLIC_TIME] = $visit_array[0] * 60 + $visit_array[1];
            } else {
                $timeForVisitInsert[Appointment::JSON_PUBLIC_TIME] = 0;
            }
            $visit_name_array = $request->add_time_name;
            $visit_time_array = $request->add_time_time;
            $counter_max = max(sizeof($visit_name_array), sizeof($visit_time_array));
            for ($i = 0; $i < $counter_max; $i++) {
                if ($visit_name_array[$i] != null && $visit_time_array[$i] != null) {
                    $array_temp = array();
                    $array_temp[Appointment::JSON_TITLE] = $visit_name_array[$i];
                    $visiti_time_temp = explode(':', $visit_time_array[$i]);
                    $visiti_time_temp = $visiti_time_temp[0] * 60 + $visiti_time_temp[1];
                    $array_temp[Appointment::JSON_TIME] = $visiti_time_temp;
                    $timeForVisitInsert[Appointment::JSON_TIME][] = $array_temp;
                }
            }
            if ($counter_max == 0) {
                $timeForVisitInsert[Appointment::JSON_STATUS] = false;
            }
        } else {
            $timeForVisitInsert[Appointment::JSON_STATUS] = false;
            $visit_time = $request->time_for_visit;
            $visit_array = explode(':', $visit_time);
            $timeForVisitInsert[Appointment::JSON_PUBLIC_TIME] = $visit_array[0] * 60 + $visit_array[1];
        }
        //endregion
        //region Setting Part
        $onlinePaySetting = array();
        if ($request->online_pay_status != null && $request->online_pay_status == 1) {
            $onlinePaySetting = [
                Appointment::JSON_STATUS => true,
                Appointment::JSON_UNPAID_APPOINTMENT_STATUS => $request->unpaid_appointment_status,
                Appointment::JSON_PAY_TYPE => $request->pay_type,
                Appointment::JSON_PAY_TYPE_PERCENT => $request->online_pay_type_percent ?? 100
            ];
        } else {
            $onlinePaySetting[Appointment::JSON_STATUS] = false;
        }
        $setting = [
            Appointment::JSON_DAY_BEFORE => $request->day_before,
            Appointment::JSON_DAY_AFTER => $request->day_after,
            Appointment::JSON_ONLINE_PAY => $onlinePaySetting,
            Appointment::JSON_CANCEL_BY_USER => $request->cancel_by_user ?? 0,
            Appointment::JSON_DAY_LEFT_TO_CANCEL => $request->cancel_by_user == 0 ? '0' : $request->day_left_to_cancel
        ];

        //endregion
        //region Content Part
        $time = ($request->time != null ? $request->time : '');
        $type = ($request->type != null ? $request->type : '');
        $active = ($request->active != null ? $request->active : '');
        $userMax = ($request->user_max != null ? $request->user_max : '');
        $userMaxType = ($request->USER_MAX_TYPE != null ?  $request->USER_MAX_TYPE  : '');
        $clerkMax = ($request->clerk_max != null ? $request->clerk_max : '');
        $clerkMaxType = ($request->CLERK_MAX_TYPE != null ? $request->CLERK_MAX_TYPE : '');
        $week_day = array('sat', 'sun', 'mon', 'tue', 'wed', 'thu', 'fri');
        for ($i = 0; $i < sizeof($week_day); $i++) {
            $day_temp = array();
            $day_name = $week_day[$i];
            $is_active = (isset($active[$day_name]) ? true : false);
            $day_temp[Appointment::JSON_STATUS] = $is_active;
            if ($is_active) {
                $time_temp[Appointment::JSON_AM][Appointment::JSON_FROM_TIME] = $time[$day_name]['am']['from'];
                $time_temp[Appointment::JSON_AM][Appointment::JSON_TO_TIME] = $time[$day_name]['am']['to'];
                $time_temp[Appointment::JSON_PM][Appointment::JSON_FROM_TIME] = $time[$day_name]['pm']['from'];
                $time_temp[Appointment::JSON_PM][Appointment::JSON_TO_TIME] = $time[$day_name]['pm']['to'];
                $day_temp[Appointment::JSON_TIME] = $time_temp;
                //type handler
                $day_temp[Appointment::JSON_TYPE] = $type[$day_name] ?? "";
                $day_temp[Appointment::JSON_USER_MAX] = $userMax[$day_name] ?? 0;
                $day_temp[Appointment::JSON_USER_MAX_TYPE] = isset($userMaxType[$day_name]) ? (int) $userMaxType[$day_name] : 0;
                $day_temp[Appointment::JSON_CLERK_MAX] = $clerkMax[$day_name] ?? 0;
                $day_temp[Appointment::JSON_CLERK_MAX_TYPE] = isset($clerkMaxType[$day_name]) ? (int) $clerkMaxType[$day_name] : 0;
            }
            $contentInsert[$day_name] = $day_temp;
        }

        //endregion

        if (Appointment::whereAppointmentPartIdAndUserId($request->appointment_part_id, $request->user_id)->exists()) {
            Appointment::whereAppointmentPartIdAndUserId($request->appointment_part_id,
                $request->user_id)->first()->update([
                'cost' => $costInsert,
                'content' => $contentInsert,
                'time_for_visit' => $timeForVisitInsert,
                'interference' => $interferenceInsert,
                'setting' => $setting,
            ]);
        } else {
            Appointment::create([
                'user_id' => $request->user_id,
                'appointment_part_id' => $request->appointment_part_id,
                'cost' => $costInsert,
                'content' => $contentInsert,
                'time_for_visit' => $timeForVisitInsert,
                'interference' => $interferenceInsert,
                'setting' => $setting,
            ]);
        }
        return parent::store($request);
    }

    function edit($id)
    {
        $doctor_id = request()->doctor_id;
        $part_id = request()->part_id;
        /*----------- Admin Detail ---------------*/
        $data['part_id'] = $part_id;
        $data['doctor_id'] = $doctor_id;
        /*-------------- End ----------------------*/

        return view('appointment::admin.appointment.edit', compact('data'));
    }

    function apiStore(Request $request)
    {
        return response()->json(['status' => true], Response::HTTP_OK, array(), JSON_UNESCAPED_UNICODE);
    }

    function webStore(Request $request)
    {
        $request->session()->flash('successful', 'زمان‌ها با موفقیت ویرایش و دخیره شده‌اند.');
        return redirect()->route('admin.appointment.index');
    }

    function apiShow(Request $request, $id)
    {

    }

    function webShow(Request $request, $id)
    {
    }

    function apiUpdate(Request $request, $id)
    {
    }

    function webUpdate(Request $request, $id)
    {
    }

    public function changeTime(Request $request)
    {
        if (!Auth::user()->can('add_admin_appointment_user')) {
            return 404;
        }
        $doctor_id = $request->doctor_id;
        $part_id = $request->part_id;
        $data['part'] = AppointmentPart::findOrFail($part_id);
        $data['appointment'] = Appointment::whereUserIdAndAppointmentPartId($doctor_id, $part_id)->first();
        $data['doctor_id'] = $doctor_id;
        $data['defualt_sms_text'] = "";
        $data['body-class'] = 'messages-page';

        $breadcrumbPartName[] = $data['part']->title;
        $parent_b = $data['part']->parent_id;
        while($parent_b != 0){
            $pTmp  = AppointmentPart::findOrFail($parent_b);
            $breadcrumbPartName[] = $pTmp->title;
            $parent_b = $pTmp->parent_id;
        }
        array_reverse($breadcrumbPartName);
        return view('appointment::admin.appointment.add', compact('data','breadcrumbPartName'));
    }

    public function add(Request $request)
    {
        if (!Auth::user()->can('add_admin_appointment_user')) {
            return 404;
        }
        $doctor_id = $request->doctor_id;
        $part_id = $request->part_id;
        $doctor = User::findOrFail($doctor_id);
        $data['part'] = AppointmentPart::findOrFail($part_id);
        $data['appointment'] = Appointment::whereUserIdAndAppointmentPartId($doctor_id, $part_id)->first();
        $data['doctor_id'] = $doctor_id;
        $data['defualt_sms_text'] = "";
        $data['body-class'] = 'messages-page';

        $breadcrumbPartName[] = $data['part']->title;
        $parent_b = $data['part']->parent_id;
        while($parent_b != 0){
            $pTmp  = AppointmentPart::findOrFail($parent_b);
            $breadcrumbPartName[] = $pTmp->title;
            $parent_b = $pTmp->parent_id;
        }
        array_reverse($breadcrumbPartName);
        $EditingAppointment = null;
        if(request()->get('code')){
            $EditingAppointment = AppointmentUser::whereId(request()->get('code'))->first();
        }
        return view('appointment::admin.appointment.add', compact('data','breadcrumbPartName','EditingAppointment'));
    }

    function HourToMin($string)
    {
        if (isset($string) && !empty($string)) {
            $time = explode(":", $string);
            return (($time[0] * 60) + $time[1]);
        } else {
            return 0;
        }
    }


    function getRecordedAppointment(
        $am_to,
        $stepTimeFrom,
        $visit_time,
        $userAppointment,
        $generalCounter,
        $class,
        $mainAppointmentId = 0,
        $changeCode = null
    ): array {
        $ret = '';
        $stepTimeTo = $stepTimeFrom + $visit_time;

        //only for change time appointment
        $addAppointmentText = 'ثبت نوبت';
        $buttonWidth        = '124';
        $btnLink            = 'btn-add-appointment';
        if($changeCode != null && $changeCode > 0){
            $btnLink            = 'btn-edit-appointment';
            $buttonWidth        = '200';
            $class              = 'system fw600 btn-gradient wrapped';
            $addAppointmentText = 'انتخاب این ساعت برای تغییر زمان';
        }
        //only for change time appointment
        while ($stepTimeTo <= $am_to) {
            if ($userAppointment->where('time_from', '<', $stepTimeTo)->count() >= 1) {
                $gapSecond = ($userAppointment->where('status', AppointmentUser::STATUS_OK)->where('kind',
                            AppointmentUser::KIND_MAIN_APPOINTMENT)->where('time_from', '<',
                            $stepTimeTo)->sortBy('time_from')->first()->time_from ?? 0) - $stepTimeFrom;
                if ($gapSecond >= 5 && $gapSecond < $visit_time) {
                    $ret .= '<tr class=""><td class="' . $class . ' text-center">' . $generalCounter . '</td><td>
                                            ' . $this->MinToHour($stepTimeFrom) . ' - ' . $this->MinToHour($stepTimeFrom + $gapSecond) . '
                                            </td>
                                            <td colspan="4" class="text-right">
                                             <button type="button" style="width: '.$buttonWidth.'px" data-time-start="' . $this->MinToHour($stepTimeFrom) . '" data-time-end="' . $this->MinToHour($stepTimeFrom + $gapSecond) . '" class="'.$btnLink.' btn btn-sm  btn-' . $class . ' ">'.$addAppointmentText.'</button>
                                             <strong class="text-danger mr10">زمان این نوبت کمتر از ' . $visit_time . ' دقیقه می‌باشد</strong>
                                            </td>
                                            </tr>';
                    $generalCounter++;
                }
                foreach ($userAppointment->where('time_from', '<',
                    $stepTimeTo)->sortBy('time_from') as $key => $userAppoint) {
                    [$retTmp, $generalCounter] = $this->displayRowAppointment($class, $userAppoint, $generalCounter,$mainAppointmentId,$changeCode);
                    $ret .= $retTmp;
                    if ($userAppoint->status == AppointmentUser::STATUS_OK && $userAppoint->kind == AppointmentUser::KIND_MAIN_APPOINTMENT) {
                        $stepTimeFrom = $userAppoint->time_to;
                    }
                    $userAppointment->forget($key);
                }
            } else {
                $ret .= '<tr><td class="' . $class . ' text-center">' . $generalCounter . '</td><td>
                                            ' . $this->MinToHour($stepTimeFrom) . ' - ' . $this->MinToHour($stepTimeTo) . '
                                            </td>
                                            <td colspan="4" class="text-center">
                                             <button type="button" style="width: '.$buttonWidth .'px" data-time-start="' . $this->MinToHour($stepTimeFrom) . '" data-time-end="' . $this->MinToHour($stepTimeTo) . '" class="'.$btnLink.' btn btn-sm btn-' . $class . ' btn-block">'.$addAppointmentText.'</button>
                                            </td>
                                            </tr>';
                $stepTimeFrom = $stepTimeTo;
                $generalCounter++;
            }
            $stepTimeTo = $stepTimeFrom + $visit_time;
        }
        return [$ret, $userAppointment, $generalCounter, $stepTimeFrom];
    }

    //ajax helpers
    public function getappointment(Request $request)
    {
        //region Part1 : Prepare data
        $doctor_id = $request->doctor_id;
        $part_id = $request->part_id;
        $year = $request->year;
        $month = $request->month;
        $changeCode = $request->changeCode ?? null;
        $day = $request->day;
        $day_names = array('sat', 'sun', 'mon', 'tue', 'wed', 'thu', 'fri');
        $day_name = $day_names[jdate('w', jmktime(0, 0, 0, $month, $day, $year), '', '', 'en')];

        // check mux
        $shortDay = strtolower(date(  "D" , jmktime(6,0,0,$month,$day,$year)));
        $appointment = Appointment::whereUserIdAndAppointmentPartId( $doctor_id, $part_id)->get()->first()->content[$shortDay];

        $errors = '';
        if ($appointment['STATUS']) {
            if($appointment['CLERK_MAX'] > 0){
                $condition = [
                    'year' => $year,
                    'month' => $month,
                    'day' => $day,
                    'status' =>  1,
                    'appointment_part_id' => $part_id,
                    'doctor_id' => $doctor_id,
                ];
                if($appointment[Appointment::JSON_CLERK_MAX_TYPE]){
                    unset($condition['appointment_part_id']);
                }
                $exactSimilarity = AppointmentUser::select('id')->where($condition)->count();

                if($exactSimilarity >= $appointment['CLERK_MAX']){
                    //error
                    $errors = '

                                        <tr class="info">
                                            <th class="" colspan="6">
                                                 <div class="alert alert-danger alert-dismissable">

<h3 class="mt5">تمام نوبت های  برای این روز پر است </h3>
<br>
حداکثر نوبت قابل ثبت توسط منشی برای روز انتخابی شما
                        <strong class="">' .$appointment['CLERK_MAX'].'</strong>
                         نوبت است که هم اکنون پر شده است.
                         <br>
                        آیا از ثبت نویت اطمینان دارید ؟
                        (تعداد ثبت شده '.$exactSimilarity.' نوبت)
                                                 </div>
                                            </th>

                                            </th>
                                        </tr>';

                    //error

                }
            }
        }
        // Check the maximum turnover for the clerk
        // check max
        $ret = '<thead>

                                        '.$errors.'
                                        <tr class="dark">
                                            <th class="text-center">نوبت</th>
                                            <th class="text-center">ساعت</th>
                                            <th class="text-center">نام و نام خانوادگی</th>
                                            <th class="text-center">موبایل</th>
                                            <th class="text-center">وضعیت</th>
                                            <th class="text-center">عملیات</th>
                                        </tr>
                                    </thead>';
        $ret .= '<tbody>';
        //endregion
        //region Part2_1 : Get doctor times
        // |->Check is day is exception
        $am_from = 0;
        $am_to = 0;
        $pm_from = 0;
        $pm_to = 0;
        $visit_time = 0;
        $exp_day = AppointmentException::where([
            ['user_id', $doctor_id],
            ['appointment_part_id', $part_id],
            ['day', $day],
            ['month', $month],
            ['year', $year]
        ]);
        $visit_time = optional(Appointment::where([
            ['user_id', $doctor_id],
            ['appointment_part_id', $part_id]
        ])->first())->time_for_visit[Appointment::JSON_PUBLIC_TIME];
        $interference = optional(Appointment::where([
            ['user_id', $doctor_id],
            ['appointment_part_id', $part_id]
        ])->first())->interference;
        if ($exp_day->exists()) {
            if ($exp_day->first()->type) {
                $content = $exp_day->first()->content;
                $am_from = $this->HourToMin(Arr::get($content, Appointment::JSON_GET_AM_FROM, 0));
                $am_to = $this->HourToMin(Arr::get($content, Appointment::JSON_GET_AM_TO, 0));
                $pm_from = $this->HourToMin(Arr::get($content, Appointment::JSON_GET_PM_FROM, 0));
                $pm_to = $this->HourToMin(Arr::get($content, Appointment::JSON_GET_PM_TO, 0));
            }
        }// |->if not Exception day
        else {
            $consolation = Appointment::where([['user_id', $doctor_id], ['appointment_part_id', $part_id]]);
            if ($consolation->exists()) {
                $consolation = $consolation->first();
                $partTimeContent = $consolation->content;
                if ($partTimeContent[$day_name][Appointment::JSON_STATUS]) {
                    $amFromTimeContent = Arr::get($partTimeContent, $day_name . "." . Appointment::JSON_GET_AM_FROM);
                    $amToTimeContent = Arr::get($partTimeContent, $day_name . "." . Appointment::JSON_GET_AM_TO);
                    $pmFromTimeContent = Arr::get($partTimeContent, $day_name . "." . Appointment::JSON_GET_PM_FROM);
                    $pmToTimeContent = Arr::get($partTimeContent, $day_name . "." . Appointment::JSON_GET_PM_TO);
                    $am_from = $this->HourToMin($amFromTimeContent);
                    $am_to = $this->HourToMin($amToTimeContent);
                    $pm_from = $this->HourToMin($pmFromTimeContent);
                    $pm_to = $this->HourToMin($pmToTimeContent);
                }
            }
        }
        //endregion
        //region Part3 : Get User Appointment data
        // |-> Variable for save data
        $am_from_show = $am_from;
        $generalCounter = 1;
        $partIds = [];
        if($interference != null && isset($interference[Appointment::JSON_STATUS]) && $interference[Appointment::JSON_STATUS]){
            $items = $interference[Appointment::JSON_ITEMS];
            if(in_array('all',$items)){
                $partIds = User::findOrFail($doctor_id)->appointmentParts()->pluck('id')->toArray();
            }else{
                $partIds = $items;
                $partIds[] = $part_id;
            }
        }else{
            $partIds[] = $part_id;
        }
        $userAppointment = AppointmentUser::whereIn('appointment_part_id', $partIds)->where([
            ['doctor_id', $doctor_id],
            ['month', $month],
            ['year', $year],
            ['day', $day]
        ])->get()->map(function ($item) {
            $item['time_from'] = $this->HourToMin($item['time_from']);
            $item['time_to'] = $this->HourToMin($item['time_to']);
            return $item;
        });
        $stepTimeFrom = 0;
        if ($am_from != $am_to) {
            if ($userAppointment->where('time_from', '<', $am_from)->count() >= 1) {
                $am_from = $userAppointment->where('status', AppointmentUser::STATUS_OK)->where('kind',
                        AppointmentUser::KIND_MAIN_APPOINTMENT)->where('time_from', '<', $am_from)->where('time_to',
                        '>', $am_from)->max('time_to') ?? $am_from;
                foreach ($userAppointment->where('time_from', '<',
                    $am_from)->sortBy('time_from') as $key => $userAppoint) {
                    [$retTmp, $generalCounter] = $this->displayRowAppointment('info', $userAppoint, $generalCounter,$part_id,$changeCode);
                    $ret .= $retTmp;
                    $userAppointment->forget($key);
                }
            }
            $ret .= "<tr class=\"alert\">
                         <td colspan=\"6\">حضور از ساعت  " . $this->MinToHour($am_from_show) . "  ق.ظ</td>
                        </tr>";
            $stepTimeFrom = $am_from;
            $stepTimeTo = 0;
            [$viewRet, $userAppointment, $generalCounter, $stepTimeTo] = $this->getRecordedAppointment($am_to,
                $stepTimeFrom, $visit_time, $userAppointment, $generalCounter, "alert", $part_id,$changeCode);
            $ret .= $viewRet;
            $ret .= "<tr class=\"alert\">
                         <td colspan=\"6\">حضور تا ساعت  " . $this->MinToHour($am_to) . " ق.ظ</td>
                        </tr>";
        } else {
            //if am time not set
            if ($pm_from != $pm_to) {//has pm time
                if ($userAppointment->where('time_to', '<', $pm_from)->count() >= 1) {
                    foreach ($userAppointment->where('time_to', '<',
                        $pm_from)->sortBy('time_from') as $key => $userAppoint) {
                        [$retTmp, $generalCounter] = $this->displayRowAppointment('info', $userAppoint,
                            $generalCounter,$part_id,$changeCode);
                        $ret .= $retTmp;
                        $userAppointment->forget($key);
                    }
                }
            }
        }
        if ($pm_from != $pm_to) {
            $restFrom = ($am_to > $stepTimeFrom) ? $am_to : $stepTimeFrom;
            $restTo = $pm_from;
            $stepTimeTo = 0;
            if ($am_from != $am_to) {
                $ret .= "<tr class=\"default\">
                         <td colspan=\"6\">زمان استراحت از ساعت " . $this->MinToHour($restFrom) . "</td>
                        </tr>";
                $stepTimeFrom = ($stepTimeTo > $restFrom) ? $stepTimeTo : $restFrom;
                [$viewRet, $userAppointment, $generalCounter, $stepTimeTo] = $this->getRecordedAppointment($restTo,
                    $stepTimeFrom, $visit_time, $userAppointment, $generalCounter, "default", $part_id,$changeCode);
                $ret .= $viewRet;
                $ret .= "<tr class=\"default\">
                         <td colspan=\"6\">زمان استراحت تا ساعت " . $this->MinToHour($restTo) . "</td>
                        </tr>";
            }
            $pmTimeFrom = $restTo;
            $pmTimeTo = $pm_to;
            $ret .= "<tr class=\"alert\">
                         <td colspan=\"6\">حضور از ساعت " . $this->MinToHour($pmTimeFrom) . " ب.ظ</td>
                        </tr>";
            $stepTimeFrom = ($stepTimeTo > $pmTimeFrom) ? $stepTimeTo : $pmTimeFrom;
            $stepTimeTo = 0;
            [$viewRet, $userAppointment, $generalCounter, $stepTimeTo] = $this->getRecordedAppointment($pmTimeTo,
                $stepTimeFrom, $visit_time, $userAppointment, $generalCounter, "alert",$part_id,$changeCode);
            $ret .= $viewRet;
            $ret .= "<tr class=\"alert\">
                         <td colspan=\"6\">حضور تا ساعت " . $this->MinToHour($pmTimeTo) . " ب.ظ</td>
                        </tr>";
        }
        if ($userAppointment->count() >= 1) {
            foreach ($userAppointment->sortBy('time_from') as $key => $userAppoint) {
                [$retTmp, $generalCounter] = $this->displayRowAppointment('info', $userAppoint, $generalCounter,$part_id,$changeCode);
                $ret .= $retTmp;
                $userAppointment->forget($key);
            }
        }
        //endregion
        return $ret;


    }

    public function displayRowAppointment(String $class, AppointmentUser $userAppoint, $generalCounter,$mainAppointmentId = 0,$changeCode = null): array
    {
        if($mainAppointmentId != 0 && $mainAppointmentId != $userAppoint->appointment_part_id && $userAppoint->kind != AppointmentUser::KIND_MAIN_APPOINTMENT){
            return ['', $generalCounter];
        }
        $counterApp = '';
        $statusTxt = 'تایید شده';
        //enhanced
        if ($userAppoint->status == AppointmentUser::STATUS_OK) {
            if ($userAppoint->kind == AppointmentUser::KIND_SUBSIDIARY_APPOINTMENT) {
                $clsAdd = "primary";
                $counterApp = 'بین مریض';
            } else {
                $clsAdd = 'success';
                $counterApp = $generalCounter;
                $generalCounter++;
            }
        } elseif ($userAppoint->status == AppointmentUser::STATUS_CANCELED) {
            $counterApp = $userAppoint->kind == AppointmentUser::KIND_SUBSIDIARY_APPOINTMENT ? 'بین مریض' : '-';
            $clsAdd = 'danger';
            $statusTxt = 'کنسل شده';
        } elseif ($userAppoint->status == AppointmentUser::STATUS_PENDING) {
            $counterApp = $userAppoint->kind == AppointmentUser::KIND_SUBSIDIARY_APPOINTMENT ? 'بین مریض' : '-';
            $statusTxt = 'در انتظار';
            $clsAdd = 'warning';
        } else {
            $clsAdd = '';
        }
        $options = '';
        if ($userAppoint->status == AppointmentUser::STATUS_OK) {
            $options .= '<li><a href="#" class="has_action_with_loading" data-action="cancel" data-id="' . $userAppoint->id . '">کنسل نوبت</a></li>';
        } elseif ($userAppoint->status == AppointmentUser::STATUS_PENDING) {
            $options .= '<li><a href="#" class="has_action_with_loading" data-action="approve" data-id="' . $userAppoint->id . '">تایید نوبت</a></li>';
        }
        if ($userAppoint->status == AppointmentUser::STATUS_OK && $userAppoint->kind == AppointmentUser::KIND_SUBSIDIARY_APPOINTMENT) {
            $options .= '<li><a href="#" class="has_action_with_loading" data-action="change_to_main" data-id="' . $userAppoint->id . '">تبدیل به نوبت اصلی</a></li>';
        } elseif ($userAppoint->status == AppointmentUser::STATUS_OK && $userAppoint->kind == AppointmentUser::KIND_MAIN_APPOINTMENT) {
            $options .= '<li><a href="#" class="has_action_with_loading"  data-action="change_to_sub" data-id="' . $userAppoint->id . '">تبدیل به نوبت بین مریض</a></li>';
        }
        $btnAction = '';
        if($mainAppointmentId != 0 && $userAppoint->appointment_part_id == $mainAppointmentId) {
            $btnAction = '<button type="button" class="btn btn-' . $clsAdd . ' dropdown-toggle" data-toggle="dropdown" aria-expanded="false">عملیات<span class="caret mr5"></span>
                                </button>';
        }else{
            $btnAction = '<span class="label label-danger">این نوبت در بخش '.$userAppoint->appointmentPart->title.' ثبت شده است</span>';
        }
        $ret = '';
        if($changeCode != null){
            if($changeCode == $userAppoint->code){
                $ret = '<tr class="warning">
                                            <td colspan="6" class="text-center">

                                                <p class="text-danger mn">شما در حال ویرایش زمان این نوبت هستید
                                                <span class="glyphicons glyphicons-down_arrow"></span>
                                                </p>
                                            </td>
                       </tr>';
                $clsAdd .= " system ";
            }
        }
        $ret .= '<tr class="' . $clsAdd . '" '.(($userAppoint->appointment_part_id != $mainAppointmentId) ? "style='opacity:0.5'" : "").'><td class="' . ((!empty($clsAdd)) ? $clsAdd : $class) . ' text-center">' . $counterApp . '</td><td>
                                            ' . $this->MinToHour($userAppoint->time_from) . ' - ' . $this->MinToHour($userAppoint->time_to) . '
                                            </td>
                                            <td class="text-center">
                                            ' . (optional($userAppoint->user)->full_name ?? '<span class="text-danger">این کاربر حذف شده است</span>')  .'
                                            </td>
                                            <td class="text-center">
                                            ' . (optional($userAppoint->user)->mobile ?? '<span class="text-danger"> -- </span>') . '
                                            </td>
                                            <td class="text-center">
                                            ' . $statusTxt . '
                                            </td>
                                            <td class="text-center">
                            <div class="btn-group">
                                '.$btnAction.'
                                <ul class="dropdown-menu" role="menu">';
        if (Auth::user()->can("edit_admin_appointment_user")) {
            $showUpdate = false;
            if ( Auth::user()->can("admin_type_full_appointment_user_access_cancel")){
                $showUpdate = true;
            }
            elseif (
                (Auth::user()->can("admin_type_limited_appointment_user_access_cancel")) &&
                Auth::id() == $userAppoint->agent_id
            )
            {
                $showUpdate = true;
            }
            if($showUpdate) {
                $ret .= $options;
            }
        }
        if (Auth::user()->can("delete_admin_appointment_user")) {
            $showDelete = false;
            if ( Auth::user()->can("admin_type_full_appointment_user_access_delete")){
                $showDelete = true;
            }
            elseif (
                (Auth::user()->can("admin_type_limited_appointment_user_access_delete")) &&
                Auth::id() == $userAppoint->agent_id
            )
            {
                $showDelete = true;
            }


            if($showDelete){
                $ret .= '<li class="divider"></li>
                                    <li>
                                    <a class="delete_blog"
                                       data-effect="mfp-rotateLeft"
                                       href="' . route('admin.appointment_user.destroy', $userAppoint->id) . '">حذف نوبت</a>
                                    </li>';
            }

        }
        $ret .= '</ul>
                            </div></td></tr>';
        return [$ret, $generalCounter];
    }

    public function ajaxgettime(Request $request)
    {
        $day_names = array('sat', 'sun', 'mon', 'tue', 'wed', 'thu', 'fri');
        $day_names_translate = array('شنبه', 'یکشنبه', 'دوشنبه', 'سه شنبه', 'چهارشنبه', 'پنج شنبه', 'جمعه');
        $ret = '
                    <tbody><tr><td>شنبه</td><td class="text-center"></td></tr>
                                <tr><td>یکشنبه</td><td class="text-center"></td></tr>
                                <tr><td>دوشنبه</td><td class="text-center"></td></tr>
                                <tr><td>سه شنبه</td><td class="text-center"></td></tr><tr>
                                    <td>چهارشنبه</td><td class="text-center"></td></tr>
                                <tr> <td>پنج شنبه</td><td class="text-center"></td></tr>
                                <tr> <td>جمعه</td><td class="text-center"></td></tr></tbody>';
        $doctor_id = $request->doctor_id;
        $part_id = $request->part_id;
        $consolation = Appointment::where([['appointment_part_id', $part_id], ['user_id', $doctor_id]]);
        if ($consolation->exists()) {
            $times = $consolation->first()->content;
            $ret = '<tbody>';
            for ($i = 0; $i < sizeof($day_names); $i++) {
                if ($times[$day_names[$i]][Appointment::JSON_STATUS]) {
                    $two_record = false;
                    $ret .= '<tr><td>' . $day_names_translate[$i] . '</td><td class="text-center">';
                    if (!empty($times[$day_names[$i]][Appointment::JSON_TIME][Appointment::JSON_AM][Appointment::JSON_FROM_TIME]) && $times[$day_names[$i]][Appointment::JSON_TIME][Appointment::JSON_AM][Appointment::JSON_TO_TIME]) {
                        $ret .= ' <span class="label selp-label-alert ml5">' . $times[$day_names[$i]][Appointment::JSON_TIME][Appointment::JSON_AM][Appointment::JSON_FROM_TIME] . ' - ' . $times[$day_names[$i]][Appointment::JSON_TIME][Appointment::JSON_AM][Appointment::JSON_TO_TIME] . ' ق.ظ</span>';
                        $two_record = true;
                    }
                    if (!empty($times[$day_names[$i]][Appointment::JSON_TIME][Appointment::JSON_PM][Appointment::JSON_FROM_TIME]) && $times[$day_names[$i]][Appointment::JSON_TIME][Appointment::JSON_PM][Appointment::JSON_TO_TIME]) {
                        if ($two_record) {
                            $ret .= '<br/><br/>';
                        }
                        $ret .= '<span class="label selp-label-warning ml5">' . $times[$day_names[$i]][Appointment::JSON_TIME][Appointment::JSON_PM][Appointment::JSON_FROM_TIME] . ' - ' . $times[$day_names[$i]][Appointment::JSON_TIME][Appointment::JSON_PM][Appointment::JSON_TO_TIME] . ' ب.ظ</span>';
                    }
                    $ret .= '</td></tr>';
                } else {
                    $ret .= '<tr><td>' . $day_names_translate[$i] . '</td><td class="text-center"></td></tr>';
                }
                $ret .= '</tbody>';
            }
        }

        return $ret;
    }

    public function calenderHelper(Request $request)
    {
        $part_id = $request->part_id;
        $doctor_id = $request->doctor_id;
        $appoint = Appointment::where([['user_id', $doctor_id], ['appointment_part_id', $part_id]]);
        if ($appoint->exists()) {
            $appoint = $appoint->first();
            $appoint = $appoint->content;
        } else {
            $appoint = array();
        }
        $day = $request->day;
        $month = intval($request->month);
        $year = $request->year;
        $holiday = (isset(config('custom.FIX_HOLIDAY')[$month]) ? config('custom.FIX_HOLIDAY')[$month] : array());
        if (isset(config('custom.HOLIDAY')[$year][$month])) {
            $holiday = array_merge($holiday, config('custom.HOLIDAY')[$year][$month]);
        }
        $time_second = jmktime(0, 0, 0, $month, $day, $year);
        $ret['currect_month'] = $month;
        $ret['currect_day'] = $day;
        $ret['month_name'] = jdate("F", jmktime(0, 0, 0, $month, 1, $year));
        $ret['currect_year'] = $year;
        $time_first_day = jmktime(0, 0, 0, $month, 1, $year);
        $data['calender']['firstday'] = jdate('w', $time_first_day, '', '', 'en');
        $data['calender']['length'] = jdate('t', $time_first_day, '', '', 'en');
        $data['calender']['lastday'] = jdate('w', jmktime(0, 0, 0, $month, $data['calender']['length'], $year), '', '',
            'en');
//        dd($data['calender']['length']);
        //add day before to today
        $data['calender']['today'] = jdate('j', $time_second, '', '', 'en');
        $div_content = array();
        $day_counter = 1;
        /*------------- start month -------------*/
        $ret['content'] = '<div style="height: 103px;" class="fc-row fc-week fc-widget-content">';
        $div_content['1'] = '<div class="fc-bg"><table><tbody><tr>';
        $div_content['2'] = '<div class="fc-content-skeleton"><table><thead><tr>';
        //add blank cell for start
        for ($i = 0; $i < $data['calender']['firstday']; $i++) {
            $div_content['1'] .= '<td data-date="' . $year . '-' . $month . '-" class="fc-day fc-widget-content fc-mon fc-past fc-other-month"></td>';
            $div_content['2'] .= '<td data-date="' . $year . '-' . $month . '-" class="fc-day-number fc-sun fc-past"></td>';
        }
        //add first week
        for ($i = 0; $i <= (6 - $data['calender']['firstday']); $i++) {
            $div_content['1'] .= '<td data-date="' . $year . '-' . $month . '-' . $day_counter . '" class="fc-day fc-widget-content fc-mon fc-past fc-event-container ' . (in_array($day_counter,
                    $holiday) ? 'bg-danger' : '') . '">
            ' . $this->dayContentHelper($year, $month, $day_counter, $appoint, $part_id, $doctor_id) . '</td>';
            $div_content['2'] .= '<td data-date="' . $year . '-' . $month . '-' . $day_counter . '" class="fc-day-number fc-sun fc-past ' . (in_array($day_counter,
                    $holiday) ? 'text-danger' : '') . '">' . $day_counter . '</td>';
            $day_counter++;
        }
        $div_content['1'] .= '</tr></tbody></table></div>';
        $div_content['2'] .= '</tr></tbody></table></div>';
        $ret['content'] .= $div_content['1'] . $div_content['2'] . '</div>';
        //add other week
        $counter_temp = 0;
        $temp_array = array();
        $temp_array['1'] = '';
        $temp_array['2'] = '';
        for ($i = $day_counter; $i <= $data['calender']['length']; $i++) {
            if ($counter_temp == 0) {
                $temp_array['1'] = '<div class="fc-bg"><table><tbody><tr>';
                $temp_array['2'] = '<div class="fc-content-skeleton"><table><thead><tr>';
            }
            $temp_array['1'] .= '<td data-date="' . $year . '-' . $month . '-' . $i . '" class="fc-day fc-widget-content fc-mon fc-past  ' . (in_array($i,
                    $holiday) ? 'bg-danger' : '') . '">' . $this->dayContentHelper($year, $month, $i, $appoint,
                    $part_id, $doctor_id) . '</td>';
            $temp_array['2'] .= '<td data-date="' . $year . '-' . $month . '-' . $i . '" class="fc-day-number fc-sun fc-past ' . (in_array($i,
                    $holiday) ? 'text-danger' : '') . '" ' . (in_array($i,
                    $holiday) ? 'text-danger' : '') . '>' . $i . '</td>';
            //add blank cell for last week
            if ($i == $data['calender']['length']) {
                for ($j = 0; $j < (6 - $data['calender']['lastday']); $j++) {
                    $temp_array['1'] .= '<td data-date="' . $year . '-' . $month . '-" class="fc-day fc-widget-content fc-mon fc-past fc-other-month"></td>';
                    $temp_array['2'] .= '<td data-date="' . $year . '-' . $month . '-" class="fc-day-number fc-sun fc-past"></td>';
                }
            }
            if ($counter_temp == 6 || $i == $data['calender']['length']) {
                $temp_array['1'] .= '</tr></tbody></table></div>';
                $temp_array['2'] .= '</tr></tbody></table></div>';
                $ret['content'] .= '<div style="height: 103px;" class="fc-row fc-week fc-widget-content">' . $temp_array['1'] . $temp_array['2'] . '</div>';
                $counter_temp = 0;
            } else {
                $counter_temp++;
            }
        }
        return response()->json($ret, 200, array(), JSON_UNESCAPED_UNICODE);
    }

    public function dayContentHelper($year, $month, $day, $appoint, $part_id, $doctor_id)
    {
        $holiday = (isset(config('custom.FIX_HOLIDAY')[$month]) ? config('custom.FIX_HOLIDAY')[$month] : array());
        //check old excception
        $day_exception = AppointmentException::where([
            ['user_id', $doctor_id],
            ['appointment_part_id', $part_id],
            ['year', $year],
            ['month', $month],
            ['day', $day]
        ]);
        if (isset(config('custom.HOLIDAY')[$year][$month])) {
            $holiday = array_merge($holiday, config('custom.HOLIDAY')[$year][$month]);
        }
        $day_names = array('sat', 'sun', 'mon', 'tue', 'wed', 'thu', 'fri');
        $day_count = jdate('w', jmktime(0, 0, 0, $month, $day, $year), '', '', 'en');
        $day_name = $day_names[$day_count];
        if ($appoint != null && $appoint[$day_name][Appointment::JSON_STATUS] && !in_array($day, $holiday)) {
            $text = '';
            if ($day_exception->exists() && $day_exception->first()->type) {
                $content = $day_exception->first()->content;
                if (!empty($content[Appointment::JSON_TIME][Appointment::JSON_AM][Appointment::JSON_FROM_TIME]) && !empty($content[Appointment::JSON_TIME][Appointment::JSON_AM][Appointment::JSON_TO_TIME])) {
                    $text .= 'از ساعت ' . $appoint[$day_name][Appointment::JSON_TIME][Appointment::JSON_AM][Appointment::JSON_FROM_TIME] . 'تا ساعت' . $content[Appointment::JSON_TIME][Appointment::JSON_AM][Appointment::JSON_TO_TIME] . 'ق.ظ';
                }
                if (!empty($content[Appointment::JSON_TIME][Appointment::JSON_PM][Appointment::JSON_FROM_TIME]) && !empty($content[Appointment::JSON_TIME][Appointment::JSON_PM][Appointment::JSON_TO_TIME])) {
                    if (!empty($text)) {
                        $text .= ' - ';
                    }
                    $text .= ' از ساعت ' . $content[Appointment::JSON_TIME][Appointment::JSON_PM][Appointment::JSON_FROM_TIME] . 'تا ساعت' . $content[Appointment::JSON_TIME][Appointment::JSON_PM][Appointment::JSON_TO_TIME] . 'ب.ظ';
                }
            } else {
                if (!empty($appoint[$day_name][Appointment::JSON_TIME][Appointment::JSON_AM][Appointment::JSON_FROM_TIME]) && !empty($appoint[$day_name][Appointment::JSON_TIME][Appointment::JSON_AM][Appointment::JSON_TO_TIME])) {
                    $text .= 'از ساعت ' . $appoint[$day_name][Appointment::JSON_TIME][Appointment::JSON_AM][Appointment::JSON_FROM_TIME] . 'تا ساعت' . $appoint[$day_name][Appointment::JSON_TIME][Appointment::JSON_AM][Appointment::JSON_TO_TIME] . 'ق.ظ';
                }
                if (!empty($appoint[$day_name][Appointment::JSON_TIME][Appointment::JSON_PM][Appointment::JSON_FROM_TIME]) && !empty($appoint[$day_name][Appointment::JSON_TIME][Appointment::JSON_PM][Appointment::JSON_TO_TIME])) {
                    if (!empty($text)) {
                        $text .= ' - ';
                    }
                    $text .= ' از ساعت ' . $appoint[$day_name][Appointment::JSON_TIME][Appointment::JSON_PM][Appointment::JSON_FROM_TIME] . 'تا ساعت' . $appoint[$day_name][Appointment::JSON_TIME][Appointment::JSON_PM][Appointment::JSON_TO_TIME] . 'ب.ظ';
                }
            }
            if ($day_exception->exists()) {
                if ($day_exception->first()->type) {
                    return '<a class="fc-day-grid-event fc-event fc-start fc-end fc-event-info  fc-draggable fc-resizable checkss" data-toggle="tooltip" title="' . $text . '"><div class="fc-content"> <span class="fc-title pull-right">حضور</span><i class="fa fa-remove remove_exeption" data-id="' . $day_exception->first()->id . '"></i></div><div class="fc-resizer"></div></a>';
                } else {
                    return '<a class="fc-day-grid-event fc-event fc-start fc-end fc-event-warning  fc-draggable fc-resizable checkss" data-toggle="tooltip" title="غیرفعال شده"><div class="fc-content"> <span class="fc-title pull-right">عدم حضور</span><i class="fa fa-plus remove_exeption" data-id="' . $day_exception->first()->id . '"></i></div><div class="fc-resizer"></div></a>';
                }
            } else {
                return '<a class="fc-day-grid-event fc-event fc-start fc-end fc-event-success  fc-draggable fc-resizable checkss" data-toggle="tooltip" title="' . $text . '"><div class="fc-content"> <span class="fc-title pull-right">حضور‌ </span><i class="fa fa-edit change_time_exception" data-date="' . $year . '-' . $month . '-' . $day . '"></i><i class="fa fa-remove add_exeption" data-type="false"></i></div><div class="fc-resizer"></div></a>';
            }
        } else {
            if ($day_exception->exists() && $day_exception->first()->type) {
                $text = '';
                $content = $day_exception->first()->content;
                if (!empty($content[Appointment::JSON_TIME][Appointment::JSON_AM][Appointment::JSON_FROM_TIME]) && !empty($content[Appointment::JSON_TIME][Appointment::JSON_AM][Appointment::JSON_TO_TIME])) {
                    $text .= 'از ساعت ' . $content[Appointment::JSON_TIME][Appointment::JSON_AM][Appointment::JSON_FROM_TIME] . 'تا ساعت' . $content[Appointment::JSON_TIME][Appointment::JSON_AM][Appointment::JSON_TO_TIME] . 'ق.ظ';
                }
                if (!empty($content[Appointment::JSON_TIME][Appointment::JSON_PM][Appointment::JSON_FROM_TIME]) && !empty($content[Appointment::JSON_TIME][Appointment::JSON_PM][Appointment::JSON_TO_TIME])) {
                    if (!empty($text)) {
                        $text .= ' - ';
                    }
                    $text .= ' از ساعت ' . $content[Appointment::JSON_TIME][Appointment::JSON_PM][Appointment::JSON_FROM_TIME] . 'تا ساعت' . $content[Appointment::JSON_TIME][Appointment::JSON_PM][Appointment::JSON_TO_TIME] . 'ب.ظ';
                }

                return '<a class="fc-day-grid-event fc-event fc-start fc-end fc-event-info  fc-draggable fc-resizable checkss" data-toggle="tooltip" title="' . $text . '"><div class="fc-content"> <span class="fc-title pull-right">حضور</span><i class="fa fa-remove remove_exeption" data-id="' . $day_exception->first()->id . '"></i></div><div class="fc-resizer"></div></a>';
            } else {
                return '<a class="compose-event-btn" data-date="' . $year . '-' . $month . '-' . $day . '" href="#calendarEvent" data-effect="mfp-flipInY"><i class="fa fa-plus"></i> اضافه کردن</a>';
            }
        }
    }

    public function exceptionHelper(Request $request)
    {
        $act = $request->act;
        if ($act == "add") {
            $type = $request->type;
            $content = $request->input('content');
            $part_id = $request->part_id;
            $doctor_id = $request->doctor_id;
            $date = explode("-", $content);
            $exception = new AppointmentException();
            $exception->user_id = $doctor_id;
            $exception->appointment_part_id = $part_id;
            $exception->day = $date[2];
            $exception->month = $date[1];
            $exception->year = $date[0];
            $exception->content = array();
            $exception->type = false;
            $exception->save();
            $ret['status'] = true;

            return response()->json($ret, 200, array(), JSON_UNESCAPED_UNICODE);
        } elseif ($act == "add_day") {
            $date = $request->date;
            $from_am = $request->from_am;
            $to_am = $request->to_am;
            $from_pm = $request->from_pm;
            $to_pm = $request->to_pm;
            $part_id = $request->part_id;
            $doctor_id = $request->doctor_id;
            $clerkMax = $request->clerkMax;
            $userMax = $request->userMax;
            $types = $request->types;
            $date = explode("-", $date);
            $exception = new AppointmentException();
            $exception->user_id = $doctor_id;
            $exception->appointment_part_id = $part_id;
            $exception->day = $date[2];
            $exception->month = $date[1];
            $exception->year = $date[0];
            $content = array();
            $content[Appointment::JSON_STATUS] = true;
            $content[Appointment::JSON_TIME][Appointment::JSON_AM][Appointment::JSON_FROM_TIME] = $from_am;
            $content[Appointment::JSON_TIME][Appointment::JSON_AM][Appointment::JSON_TO_TIME] = $to_am;
            $content[Appointment::JSON_TIME][Appointment::JSON_PM][Appointment::JSON_FROM_TIME] = $from_pm;
            $content[Appointment::JSON_TIME][Appointment::JSON_PM][Appointment::JSON_TO_TIME] = $to_pm;
            $content[Appointment::JSON_TYPE] = $types;
            $content[Appointment::JSON_CLERK_MAX] = $clerkMax;
            $content[Appointment::JSON_USER_MAX] = $userMax;
            $exception->content = $content;
            $exception->type = true;
            $exception->save();
            $ret['status'] = true;
            return response()->json($ret, 200, array(), JSON_UNESCAPED_UNICODE);
        } elseif ($act == 'getData') {
            $date = $request->date;
            $date = explode("-", $date);
            $part_id = $request->part_id;
            $doctor_id = $request->doctor_id;
            $appoint = Appointment::where([['user_id', $doctor_id], ['appointment_part_id', $part_id]]);
            if ($appoint->exists()) {
                $content = $appoint->first()->content;
                $day_names = array('sat', 'sun', 'mon', 'tue', 'wed', 'thu', 'fri');
                $day_count = jdate('w', jmktime(0, 0, 0, $date[1], $date[2], $date[0]), '', '', 'en');
                $day_name = $day_names[$day_count];
                $ret['status'] = $content[$day_name][Appointment::JSON_STATUS];
                $ret['from_am'] = $content[$day_name][Appointment::JSON_TIME][Appointment::JSON_AM][Appointment::JSON_FROM_TIME];
                $ret['to_am'] = $content[$day_name][Appointment::JSON_TIME][Appointment::JSON_AM][Appointment::JSON_TO_TIME];
                $ret['from_pm'] = $content[$day_name][Appointment::JSON_TIME][Appointment::JSON_PM][Appointment::JSON_FROM_TIME];
                $ret['to_pm'] = $content[$day_name][Appointment::JSON_TIME][Appointment::JSON_PM][Appointment::JSON_TO_TIME];
                $ret[Appointment::JSON_CLERK_MAX] = $content[$day_name][Appointment::JSON_CLERK_MAX];
                $ret[Appointment::JSON_USER_MAX] = $content[$day_name][Appointment::JSON_USER_MAX];
                $ret[Appointment::JSON_TYPE] = $content[$day_name][Appointment::JSON_TYPE];
                $ret['tarikh'] = $request->date;
            } else {
                $ret['status'] = false;
            }
            return response()->json($ret, 200, array(), JSON_UNESCAPED_UNICODE);
        } else {
            $content = $request->input('content');
            $exception = AppointmentException::find($content);
            try {
                $exception->delete();
            } catch (\Exception $e) {
            }
            $ret['status'] = true;
            return response()->json($ret, 200, array(), JSON_UNESCAPED_UNICODE);
        }
    }

    public function getajaxexception(Request $request)
    {
        $ret = '';
        $doctor_id = $request->doctor_id;
        $year = $request->year;
        $part_id = $request->part_id;
        $month = $request->month;
        $month_name = [
            'فروردین',
            'اردیبهشت',
            'خرداد',
            'تیر',
            'مرداد',
            'شهریور',
            'مهر',
            'آبان',
            'آذر',
            'دی',
            'بهمن',
            'اسفند'
        ];
        $exception = AppointmentException::where([
            ['user_id', $doctor_id],
            ['appointment_part_id', $part_id],
            ['month', $month],
            ['year', $year]
        ]);
        if ($exception->exists()) {
            $add_day = '';
            $cancel_day = '';
            foreach ($exception->get() as $except) {
                if ($except->type) {
                    $content = $except->content;
                    $toolkit = '';
                    $amFrom = Arr::get($content, Appointment::JSON_GET_AM_FROM, "00:00");
                    $amTo = Arr::get($content, Appointment::JSON_GET_AM_TO, "00:00");
                    $pmFrom = Arr::get($content, Appointment::JSON_GET_PM_FROM, "00:00");
                    $pmTo = Arr::get($content, Appointment::JSON_GET_PM_TO, "00:00");
                    if ($amFrom != $amTo) {
                        $toolkit .= 'از ' . $amFrom . ' ق.ظ تا ' . $amTo . ' ق.ظ';
                    }
                    if ($pmFrom != $pmTo) {
                        if (!empty($toolkit)) {
                            $toolkit .= ' - ';
                        }
                        $toolkit .= 'از ' . $pmFrom . 'ب.ظ تا ' . $pmTo . ' ب.ظ';
                    }
                    $add_day .= '
                     <div class="fc-event fc-event-success ui-draggable ui-draggable-handle" data-event="success" data-toggle="tooltip" data-original-title="' . $toolkit . '">
                                    <div class="fc-event-icon">
                                        <span class="fa fa-check"></span>
                                    </div>
                                    <div class="fc-event-desc">' . ($except->day) . ' ' . $month_name[$except->month - 1] . ' ' . ($year) . '
                                    </div>
                                </div>
                    ';
                } else {
                    $cancel_day .= '
                        <div class="fc-event fc-event-danger ui-draggable ui-draggable-handle" data-event="danger">
                                    <div class="fc-event-icon">
                                        <span class="fa fa-remove"></span>
                                    </div>
                                    <div class="fc-event-desc"><b>' . ($except->day) . ' ' . $month_name[$except->month - 1] . ' ' . ($year) . '</b> - عدم حضور</div>
                                </div>
                    ';
                }
            }
            $ret = '<h6 class="p10 pbn fs12"> روز های حضور: </h6>
                               ' . $add_day . '
                                <!-- Reoccuring Events -->
                                <h6 class="p10 pbn fs12"> روز های عدم حضور: </h6>
                                ' . $cancel_day . '
                                ';
        } else {
            $ret .= '
                <div class="alert alert-danger">
                هیچ روز استثنایی برای این ماه ثبت نشده.
</div>
            ';
        }

        return $ret;
    }



    public function getUser(Request $request)
    {
        if ($request->wantsJson()) {
            $user_mobile = $request->mobile;
            $data = [];
            if (User::whereMobile($user_mobile)->exists()) {
                $user = User::whereMobile($user_mobile)->first();

                $data['status'] = true;
                $data['user_id'] = $user->id;
                $data['first_name'] = $user->first_name;
                $data['last_name'] = $user->last_name;
                $data['gender'] = $user->gender;
                $data['age'] = $user->age;
                $data['acquainted'] = $user->acquainted;
                $data['service'] = '';
                $data['address'] = $user->address;



            } else {
                $data['status'] = false;
                $data['user_id'] = 0;
                $data['first_name'] = "";
                $data['gender'] = 1;
                $data['last_name'] = "";
                $data['age'] ='';
                $data['acquainted'] ='';
                $data['service'] ='';
                $data['address'] = '';
            }
            return response()->json($data, 200, array(), JSON_UNESCAPED_UNICODE);
        } else {
            return '404';
        }
    }

    private function timeToString(int $time): String
    {
        if ($time <= 0) {
            return "00:00";
        }
        $hr = intval($time / 60);
        $min = $time - ($hr * 60);
        if (strlen($hr) == 1) {
            $hr = "0" . $hr;
        }
        if (strlen($min) == 1) {
            $min = "0" . $min;
        }
        return $hr . ":" . $min;
    }

    function MinToHour($min)
    {
        $hour = intval($min / 60);
        $min2 = $min - ($hour * 60);
        if (strlen($hour) == 1) {
            $hour = '0' . $hour;
        }
        if (strlen($min2) == 1) {
            $min2 = '0' . $min2;
        }
        return ($hour . ":" . $min2);
    }

    private function checkTimeReserv($start_time,$end_time,$year,$month,$day,$part_id,$doctor_id){

        $errors = [];
        $appointmentDetail = AppointmentUser::where([
            'year' => $year,
            'month' => $month,
            'status'    =>  1,
            'day' => $day,
            'appointment_part_id' => $part_id,
            'doctor_id' => $doctor_id,
        ])->where(function ($q) use ($start_time, $end_time) {
            $q->orWhere([['time_from', '>', $start_time], ['time_from', '<', $end_time]])
                ->orWhere([['time_to', '>', $start_time], ['time_to', '<', $end_time]])
                ->orWhere(function ($q2) use ($start_time, $end_time) {
                    $q2->where('time_from', '<=', $start_time)
                        ->where('time_to', '>=', $start_time)
                        ->where('time_from', '<=', $end_time)
                        ->where('time_to', '>=', $end_time);
                });
        })->orderBy('time_from');

        if ($appointmentDetail->exists()) {
            $errors[] = "<b>هم اکنون " . $appointmentDetail->count() . " نوبت  در ساعتی که شمامیخواهید نوبتدهی کنید ثبت شده است</b>";
            $i = 0;
            foreach ($appointmentDetail->get() as $appointmentUser) {
                $i++;
                $timeReservFrom = substr_replace($appointmentUser->time_from, "", -3, 3);
                $timeReservTo = substr_replace($appointmentUser->time_to, "", -3, 3);
                $errors[] = "&nbsp; &nbsp; $i - نوبت  {$appointmentUser->code} در ساعت {$timeReservFrom} تا  {$timeReservTo} برای {$appointmentUser->user->fullName}  ثبت شده ";
            }
        }





        $exp_day = AppointmentException::where([
            ['user_id', $doctor_id],
            ['appointment_part_id', $part_id],
            ['day', $day],
            ['month', $month],
            ['year', $year]
        ]);
        if ($exp_day->exists()) {


            if ($exp_day->first()->type) {
                $content = $exp_day->first()->content;
                $am_from = $this->HourToMin(Arr::get($content, Appointment::JSON_GET_AM_FROM, 0));
                if($am_from == '00:00'){
                    $am_from = $this->HourToMin(Arr::get($content, Appointment::JSON_GET_PM_FROM, 0));
                }
                $pm_to = $this->HourToMin(Arr::get($content, Appointment::JSON_GET_PM_TO, 0));
                if($pm_to == '00:00'){
                    $pm_to = $this->HourToMin(Arr::get($content, Appointment::JSON_GET_AM_TO, 0));
                }
            }
        }// |->if not Exception day
        else {

            $consolation = Appointment::where([['user_id', $doctor_id], ['appointment_part_id', $part_id]]);
            if ($consolation->exists()) {

                $consolation = $consolation->first();
                $partTimeContent = $consolation->content;
                $day_names = array('sat', 'sun', 'mon', 'tue', 'wed', 'thu', 'fri');
                $day_name = $day_names[jdate('w', jmktime(0, 0, 0, $month, $day, $year), '', '', 'en')];

                if ($partTimeContent[$day_name][Appointment::JSON_STATUS]) {

                    $amFromTimeContent = Arr::get($partTimeContent, $day_name . "." . Appointment::JSON_GET_AM_FROM);
                    if($amFromTimeContent == '00:00'){
                        $amFromTimeContent = Arr::get($partTimeContent, $day_name . "." . Appointment::JSON_GET_PM_FROM);
                    }

                    $pmToTimeContent = Arr::get($partTimeContent, $day_name . "." . Appointment::JSON_GET_PM_TO);
                    if($pmToTimeContent == '00:00'){
                        $pmToTimeContent = Arr::get($partTimeContent, $day_name . "." . Appointment::JSON_GET_AM_TO);
                    }
                    $am_from = $this->HourToMin($amFromTimeContent);
                    $pm_to = $this->HourToMin($pmToTimeContent);
                }
            }
        }

        $timeToStart = $this->MinToHour($am_from);
        $checkOuttime = $this->MinToHour($pm_to);



        // if ($start_time < $timeToStart) {
        if (substr_replace($start_time, "", -3, 3) < $timeToStart) {
            $errors[] = 'ساعت انتخاب شده قبل از ساعت حضور پزشک در این بخش است';
        }
        if (substr_replace($end_time, "", -3, 3) > $checkOuttime) {
            $errors[] = 'ساعت اتمام ویزیت بعد از ساعت حضور پزشک میشود';
        }

        return $errors;
    }

    public function checkAppointmentChangeTime(Request $request)
    {

        if ($request->wantsJson() || !$request->wantsJson()) {

            $errors = null;

            $appointment_code = $request->appointment_code;
            $start_time = $request->start_time;
            // $end_time = $request->end_time;
            $year = $request->year;
            $month = $request->month;
            $day = $request->day;



            $oldAppointment = AppointmentUser::whereId(  $appointment_code)->first();
            $oldStartTime = $this->HourToMin($oldAppointment->time_from);
            $oldEndTime = $this->HourToMin($oldAppointment->time_to);
            $overTime = $oldEndTime - $oldStartTime;

            $end_time = $this->MinToHour($this->HourToMin($start_time) + $overTime).":00";


            $exactSimilarity = AppointmentUser::where([
                'year' => $year,
                'month' => $month,
                'day' => $day,
                'status'    =>  '1',
                'appointment_part_id' => $oldAppointment->appointment_part_id,
                'doctor_id' => $oldAppointment->doctor_id,
                'time_from' => $start_time,
                'time_to' => $end_time,
            ])->count();



            if ($exactSimilarity > 0) {
                $errors[] = 'نوبت این کاربر قبلا به این ساعت منتقل شده است <br>';
            } else {
                $errors = $this->checkTimeReserv($start_time, $end_time, $year, $month, $day, $oldAppointment->appointment_part_id, $oldAppointment->doctor_id);
            }


            if ($errors && count($errors) > 0) {
                $response = [
                    'error' => true,
                    'message' => $errors
                ];
            } else {
                $response = [
                    'error' => false
                ];
            }
            //endregion

            return response()->json($response, 200, array(), JSON_UNESCAPED_UNICODE);
            /*} else {
                return '404';
            }*/
        }
        else {
            return '404';
        }
    }

    public function checkAppointment(Request $request)
    {
        if ($request->wantsJson()) {

            $accessToInsert = true;

            $errors = null;

            $doctor_id = $request->doctor_id;
            $user_id = $request->user_id;
            $part_id = $request->part_id;
            $start_time = $request->start_time;
            $end_time = $request->end_time;
            $year = $request->year;
            $month = $request->month;
            $day = $request->day;
            $type = $request->type;
            $status = $request->status;



            $exactSimilarity = AppointmentUser::where([
                'year' => $year,
                'month' => $month,
                'day' => $day,
                'status'    =>  1,
                'type' => $type,
                'user_id' => $user_id,
                'appointment_part_id' => $part_id,
                'doctor_id' => $doctor_id,
                'time_from' => $start_time,
                'time_to' => $end_time,
            ])->count();

            if ($exactSimilarity > 0) {
                $errors[] = 'نوبت این کاربر در این ساعت قبلا ثبت شده است <br>
آیا مطمئن هستید که میخواهید در همین ساعت باز هم برایشان نوبت ثبت کنید؟';
            } else {
                $errors = $this->checkTimeReserv($start_time, $end_time, $year, $month, $day, $part_id,$doctor_id);
            }



            // Check the maximum turnover for the clerk
            $shortDay = strtolower(date(  "D" , jmktime(6,0,0,$month,$day,$year)));
            $appointment = Appointment::whereUserIdAndAppointmentPartId( $doctor_id, $part_id)->get()->first()->content[$shortDay];

            if ($appointment['STATUS'] && $status != '6') {
                if($appointment['CLERK_MAX'] > 0  ){
                    $condition = [
                        'year' => $year,
                        'month' => $month,
                        'day' => $day,
                        'status' =>  1,
                        'appointment_part_id' => $part_id,
                        'doctor_id' => $doctor_id,
                    ];
                    if($appointment[Appointment::JSON_CLERK_MAX_TYPE]){
                        unset($condition['appointment_part_id']);
                    }
                    $exactSimilarity = AppointmentUser::select('id')->where($condition)->count();

                    if($exactSimilarity >= $appointment['CLERK_MAX']){
                        //error
                        $errors[] = 'حداکثر نوبت قابل ثبت توسط منشی برای روز انتخابی شما
                        <strong class="text-danger">' .$appointment['CLERK_MAX'].'</strong>
                         نوبت است که هم اکنون پر شده است.
                        آیا از ثبت نویت اطمینان دارید ؟
                        (تعداد ثبت شده '.$exactSimilarity.' نوبت)';
                        if(Auth::user()->getRoleNames()[0] == 'CALL_CENTER') {
                            if( $status == '1' ) {
                                $accessToInsert = false;
                            }
                            else{
                                if($exactSimilarity >= ($appointment['CLERK_MAX'] + 2 )){
                                    $accessToInsert = false;
                                } else {
                                    $accessToInsert = true;
                                }
                            }
                        }
                        //error

                    }
                }
            }

            if (get_option( \Modules\Appointment\Entities\Appointment::SETTING_AGENT_STATUS ) == 0){
                if(Auth::user()->getRoleNames()[0] != 'SUPER_ADMIN') {
                    $errors[] = '<p style="white-space : pre">'.get_option( \Modules\Appointment\Entities\Appointment::MESSAGE_IF_TURN_APPOINTMENT_IS_INACTIVE).'</p>';
                    $accessToInsert = false;
                }
            }


            // Check the maximum turnover for the clerk

            // Check that someone else turn is registered

            $date = jalali_to_gregorian($year, $month , $day );
            $date = $date[2] . '/' . $date[1] . '/' . $date[0];
            //$someone = AppointmentUser::select('agent_id')->where([['user_id', $user_id],['created_at','>=',Carbon::createFromFormat('d/m/Y', $date)->subHours(48)->toDateTimeString()]])->orderBy('id','DESC')->first();
            $someone = AppointmentUser::select('agent_id')->where([['user_id', $user_id],['day',$day] , ['month',$month] , ['year' , $year] , ['status',1]])->orderBy('id','DESC')->first();

            if ($someone){
                $agentUserId = $someone->agent_id;
                if($agentUserId != null && $agentUserId != Auth::user()->id){
                    unset($errors);
                    $agentName = User::whereId($agentUserId)->first()->getFullNameAttribute();

                    $errors[] = 'در همین روز برای این کاربر توسط '.$agentName.' نوبت ثبت شده است <br/>
                    شما امکان ثبت نوبت برای این کاربر را ندارد و برای هماهنگی تغییر زمان و یا کنسل کردن نوبت با '.$agentName.' هماهنگ کنید';
                    if(Auth::user()->getRoleNames()[0] == 'CALL_CENTER') {
                        $accessToInsert = false;
                    }
                }
            }

            // Check that someone else turn is registered


            if(Auth::user()->getRoleNames()[0] == 'CALL_CENTER') {
                $firstTimeInDay = jmktime(2,0,0,jdate('m'),jdate('d'),jdate('Y'));// خروجی: 1297392334
                $timeReserv     = jmktime(date('H'),Date("m"),0,$month,$day,$year);// خروجی: 1297392334

                if($timeReserv < $firstTimeInDay){
                    unset($errors);
                    $errors[] = 'شما نمیتوانید برای روزهای گذشته نوبت ثبت کنید .';
                    if(Auth::user()->getRoleNames()[0] == 'CALL_CENTER') {
                        $accessToInsert = false;
                    }
                }
            }

            if ($errors && count($errors) > 0) {
                $response = [
                    'error' => true,
                    'message' => $errors,
                    'accessToInsert' => $accessToInsert,
                ];
            } else {
                $response = [
                    'error' => false
                ];
            }
            //endregion

            return response()->json($response, 200, array(), JSON_UNESCAPED_UNICODE);
            /*} else {
                return '404';
            }*/
        }
        else {
            return '404';
        }
    }

    function storeAppointmentUpdateTime(Request $request){
        $appointment_code = $request->code;
        $start_time = $request->start_time;
        $year = $request->year;
        $month = $request->month;
        $day = $request->day;
        $send_sms_after_change_time = $request->send_sms_after_change_time;



        $oldAppointment = AppointmentUser::where('id' , $appointment_code)->first();
        $oldStartTime = $this->HourToMin($oldAppointment->time_from);
        $oldEndTime = $this->HourToMin($oldAppointment->time_to);
        $overTime = $oldEndTime - $oldStartTime;

        $end_time = $this->MinToHour($this->HourToMin($start_time) + $overTime).":00";

        $oldAppointment->year = $year;
        $oldAppointment->month = $month;
        $oldAppointment->day = $day;
        $oldAppointment->time_from = $start_time;
        $oldAppointment->time_to = $end_time;
        $oldAppointment->save();



        if ($send_sms_after_change_time == 'true') {
            $smsAfter = get_option('SMS_AFTER_CHANGE_TIME', '', $oldAppointment->doctor_id);
            $smsAfter = $smsAfter ?: get_option('SMS_AFTER_CHANGE_TIME');
            if ($smsAfter) {
                $messgae = get_option('SMS_AFTER_CHANGE_TIME_MESSAGE', '', $oldAppointment->doctor_id);
                $appointmetn = AppointmentUser::whereId($appointment_code)->first();
//                $messgae = appointmentUserTextFilter($messgae, $appointmetn);
//                sendSmsNow($appointmetn->id, $messgae, AppointmentUser::class, $oldAppointment->doctor_id);
                $time = explode( ':', $appointmetn->time_from );
                smsNow( $appointmetn->user->mobile,
                    'kamiloEdit',
                    $appointmetn->appointmentPart->title,
                    "{$appointmetn->year}/{$appointmetn->month}/{$appointmetn->day}",
                    "{$time[0]}:{$time[1]}",
                    $appointmetn->detail[AppointmentUser::SHORT_LINK] ?? ''
                );
            }
        }

        return response()->json(['status' => true, 'code' => $appointment_code], 200, array(), JSON_UNESCAPED_UNICODE);

    }
    function storeAppointment(Request $request)
    {

        $mobile = $request->mobile;
        $doctor_id = $request->doctor_id;
        $part_id = $request->part_id;
        $user_id = $request->user_id;
        $firstname = $request->firstname;
        $service_detail = $request->service_detail;
        $identifierـcode = $request->identifierـcode;
        $second_mobile = $request->second_mobile;

        $acquainted = $request->acquainted;
        $appointmentFirstType = $request->appointment_first_type;
        $age = $request->age;
        $service = $request->service;
        $address = $request->address;

        $lastname = $request->lastname;
        $gender = $request->gender;
        $start_time = $request->start_time;
        $end_time = $request->end_time;
        $kind = $request->kind;
        $status = $request->status;
        $sms_status = $request->sms_status;
        $custom_sms_text = $request->custom_sms_text;
        $sms_text_status = $request->sms_text_status;
        $year = $request->year;
        $month = $request->month;
        $day = $request->day;
        $type = $request->type;
        $r_part = ($request->r_part != "undefined") ? $request->r_part : array();
        if ($user_id == 0 || !User::whereId($user_id)->exists()) {
            $user = User::create([
                'mobile' => $mobile,
                'email' => $mobile . "@user",
                'charge' => 0,
                'password' => Hash::make($mobile),
                'remember_token' => null,
                'last_login' => null,
            ]);
            $defRole = Role::where('is_default', true)->first();
            $user->assignRole($defRole);
            $user_id = $user->id;
            $user->userMetas()->saveMany([
                new UserMeta(['meta_key' => UserMeta::FIRST_NAME, 'meta_value' => $firstname]),
                new UserMeta(['meta_key' => UserMeta::LAST_NAME, 'meta_value' => $lastname]),
                new UserMeta(['meta_key' => UserMeta::GENDER, 'meta_value' => $gender]),
                new UserMeta(['meta_key' => UserMeta::AGENT, 'meta_value' => Auth::id()])
            ]);

            if(!empty($age)){
                $user->userMetas()->saveMany([
                    new UserMeta(['meta_key' => UserMeta::AGE, 'meta_value' => $age]),
                ]);
            }

            if(!empty($acquainted)){
                $user->userMetas()->saveMany([
                    new UserMeta(['meta_key' => UserMeta::ACQUAINTED, 'meta_value' => $acquainted]),
                ]);
            }

            if(!empty($address)){
                $user->userMetas()->saveMany([
                    new UserMeta(['meta_key' => UserMeta::ADDRESS, 'meta_value' => $address]),
                ]);
            }

        } else {
            UserMeta::updateOrCreate(['user_id' => $user_id, 'meta_key' => UserMeta::FIRST_NAME],
                ['meta_value' => $firstname]);
            UserMeta::updateOrCreate(['user_id' => $user_id, 'meta_key' => UserMeta::LAST_NAME],
                ['meta_value' => $lastname]);
            UserMeta::updateOrCreate(['user_id' => $user_id, 'meta_key' => UserMeta::GENDER],
                ['meta_value' => $gender]);
            if(!empty($age)){
                UserMeta::updateOrCreate(['user_id' => $user_id, 'meta_key' => UserMeta::AGE],
                    ['meta_value' => $age]);
            }
            if(!empty($acquainted)){
                UserMeta::updateOrCreate(['user_id' => $user_id, 'meta_key' => UserMeta::ACQUAINTED],
                    ['meta_value' => $acquainted]);
            }
            if(!empty($address)){
                UserMeta::updateOrCreate(['user_id' => $user_id, 'meta_key' => UserMeta::ADDRESS],
                    ['meta_value' => $address]);
            }

            $user = User::find($user_id);
        }

        $appointmetn = AppointmentUser::create([
            'agent_id' => Auth::id(),
            'user_id' => $user_id,
            'transaction_id' => null,
            'appointment_part_id' => $part_id,
            'doctor_id' => $doctor_id,
            'code' => '',
            'cost' => 0,
            'percent' => 0,
            'type' => $type,
            'day' => $day,
            'month' => $month,
            'year' => $year,
            'time_from' => $start_time,
            'time_to' => $end_time,
            'ip' => $request->ip(),
            'status' => AppointmentUser::STATUS_OK,
            'kind' => $kind,
            'status' => $status,
            'visit' => AppointmentUser::VISIT_PENDING,
            'r_part' => Arr::wrap($r_part),
        ]);

        //create short link
        $appointmetn->code = $appointmetn->generateCode();
        $appointmetn->save();

        $linkAddress = route('profile.appointment.result.show',[$appointmetn->code,$appointmetn->id]);
//        $shorterLink = \App\Http\Controllers\shortURLController::ShortLinkMaker($linkAddress);
        $shorterLink = $linkAddress;
        $baseShortLink = $shorterLink;
        $appointmentDetail =  [AppointmentUser::SHORT_LINK => $shorterLink];

        if(!empty($service_detail)){
            $appointmentDetail[UserMeta::SERVICE_DETAIL] = $service_detail;
        }

        if(!empty($appointmentFirstType)){
            $appointmentDetail[UserMeta::APPOINTMENT_FIRST_TYPE] = $appointmentFirstType;
        }

        if(!empty($service)){
            $appointmentDetail[UserMeta::SERVICE] = $service;
        }

        if(!empty($identifierـcode)){
            $appointmentDetail[AppointmentUser::IDENTIFIERـCODE] = $identifierـcode;
        }

        if(!empty($second_mobile)){
            $appointmentDetail[AppointmentUser::SECOND_MOBILE] = $second_mobile;
        }

        $appointmetn->detail = $appointmentDetail;

        //create short link
        $appointmetn->save();


        if ($sms_status == "1") {
            if ($sms_text_status == "1") {
                $messgae = $custom_sms_text;
            } else {
                $messgae = get_option('USER_APPOINTMENT_SMS_MESSAGE', '', $doctor_id);
            }
            //$messgae = appointmentUserTextFilter($messgae, $appointmetn);
            //sendSmsNow($appointmetn->id, $messgae, AppointmentUser::class, $doctor_id);
            $time = explode( ':', $appointmetn->time_from );


                smsNow(
                    $appointmetn->user->mobile,
                    'kamiloAdd',
                    $user->first_name,
                    "روز {$appointmetn->year}/{$appointmetn->month}/{$appointmetn->day}",
                    ($shorterLink ?? $baseShortLink)
                );

        }


        return response()->json(['status' => true, 'code' => $appointmetn->code], 200, array(), JSON_UNESCAPED_UNICODE);
    }

    public function loadMonth(Request $request)
    {
        $doctor_id = $request->doctor_id;
        $part_id = $request->part_id;
        $week_day = array('sat', 'sun', 'mon', 'tue', 'wed', 'thu', 'fri');
        $hasTime = Appointment::whereAppointmentPartIdAndUserId($part_id, $doctor_id)->exists();
        $appointment = Appointment::whereAppointmentPartIdAndUserId($part_id, $doctor_id)->first();
        $month = $request->month;
        $year = $request->year;
        $allExceptionThisMonth = AppointmentException::whereAppointmentPartIdAndUserIdAndMonthAndYear($part_id, $doctor_id,$month, $year)->get();
        $allUserAppointment = AppointmentUser::where([
            ['appointment_part_id', $part_id],
            ['doctor_id', $doctor_id],
            ['month', $month],
            ['year', $year],
            ['status', AppointmentUser::STATUS_OK],
            ['kind', AppointmentUser::KIND_MAIN_APPOINTMENT]
        ])->get();
        $time = jmktime(2, 0, 0, $month, 1, $year);
        $monthLength = jdate('t', $time, '', '', 'en');
        $now = time();
        $monthDays = [];
        for ($i = 1; $i <= $monthLength; $i++) {
            $AppointmetnInDay = $allUserAppointment->where('day', $i);
            $dayTime = $time + (24 * 60 * 60 * ($i - 1));
            $cls = 'cal_item_deactivate';
            $isException = false;
            if ($allExceptionThisMonth->where('day', $i)->count() >= 1) {
                $isException = true;
                if ($allExceptionThisMonth->firstWhere('day', $i)->type) {
                    $cls = 'cal_item';
                    $maxDayAppointment = Arr::get($allExceptionThisMonth->firstWhere('day', $i)->content,
                        Appointment::JSON_USER_MAX, "null");
                    $publicTime = Arr::get($appointment->time_for_visit, Appointment::JSON_PUBLIC_TIME, 0);
                    if ($publicTime != 0) {
                        $amFrom = $this->HourToMin(Arr::get($allExceptionThisMonth->firstWhere('day', $i)->content,
                            Appointment::JSON_GET_AM_FROM, "00:00"));
                        $amTo = $this->HourToMin(Arr::get($allExceptionThisMonth->firstWhere('day', $i)->content,
                            Appointment::JSON_GET_AM_TO, "00:00"));
                        $pmFrom = $this->HourToMin(Arr::get($allExceptionThisMonth->firstWhere('day', $i)->content,
                            Appointment::JSON_GET_PM_FROM, "00:00"));
                        $pmTo = $this->HourToMin(Arr::get($allExceptionThisMonth->firstWhere('day', $i)->content,
                            Appointment::JSON_GET_PM_TO, "00:00"));
                        $dayMaxTime = ($amTo - $amFrom) + ($pmTo - $pmFrom);
                        $appointmentCanSave = floor($dayMaxTime / $publicTime);
                        if ($appointmentCanSave > $maxDayAppointment) {
                            $maxDayAppointment = $appointmentCanSave;
                        }
                    }
                    if ($maxDayAppointment != null && $AppointmetnInDay->count() >= 1 && $AppointmetnInDay->count() >= intval($maxDayAppointment)) {
                        $cls .= " full_mark";
                    }
                }
            } else {
                if ($appointment != null) {
                    if (Arr::get($appointment->content,
                        $week_day[jdate('w', $dayTime, '', '', 'en')] . "." . Appointment::JSON_STATUS, false)) {
                        $cls = 'cal_item';
                        if ($AppointmetnInDay->count() >= 1) {
                            $maxAppointmentTamp = intval(Arr::get($appointment->content,
                                $week_day[jdate('w', $dayTime, '', '', 'en')] . "." . Appointment::JSON_USER_MAX,
                                "null"));
                            $publicTime = Arr::get($appointment->time_for_visit, Appointment::JSON_PUBLIC_TIME, 0);
                            $amFrom = $this->HourToMin(Arr::get($appointment->content,
                                $week_day[jdate('w', $dayTime, '', '', 'en')] . "." . Appointment::JSON_GET_AM_FROM,
                                "00:00"));
                            $amTo = $this->HourToMin(Arr::get($appointment->content,
                                $week_day[jdate('w', $dayTime, '', '', 'en')] . "." . Appointment::JSON_GET_AM_TO,
                                "00:00"));
                            $pmFrom = $this->HourToMin(Arr::get($appointment->content,
                                $week_day[jdate('w', $dayTime, '', '', 'en')] . "." . Appointment::JSON_GET_PM_FROM,
                                "00:00"));
                            $pmTo = $this->HourToMin(Arr::get($appointment->content,
                                $week_day[jdate('w', $dayTime, '', '', 'en')] . "." . Appointment::JSON_GET_PM_TO,
                                "00:00"));
                            $dayMaxTime = ($amTo - $amFrom) + ($pmTo - $pmFrom);
                            $appointmentCanSave = floor($dayMaxTime / $publicTime);
                            if ($appointmentCanSave > $maxAppointmentTamp) {
                                $maxAppointmentTamp = $appointmentCanSave;
                            }
                            if ($AppointmetnInDay->count() >= intval($maxAppointmentTamp)) {
                                $cls .= " full_mark";
                            }
                        }
                    }
                }
            }
            if ($dayTime < $now) {
                $cls .= " day_after ";
            }

            //holiday
            $holiday = (isset(config('custom.FIX_HOLIDAY')[$month]) ? config('custom.FIX_HOLIDAY')[$month] : array());
            if (isset(config('custom.HOLIDAY')[$year][$month])) {
                $holiday = (array_merge($holiday, config('custom.HOLIDAY')[$year][$month]));

            }
            $unique = array_merge(array_flip(array_flip($holiday)));
            if(count($unique) > 0){
                if(in_array(jdate('j', $dayTime, '', '', 'en'),$unique)){
                    if($isException == false){
                        $cls = 'cal_item_deactivate';
                    }
                    $cls .= " holiday ";
                }
            }
            //holiday

            $monthDays[] = [
                'day_name' => jdate('l', $dayTime),
                'day_number' => jdate('j', $dayTime, '', '', 'en'),
                'class' => $cls,
                'persian_format' => jdate1('l j F Y', $dayTime, 'en'),
                'time' => $dayTime
            ];
        }
        return response()->json(['days' => $monthDays , 'u' => $unique], 200, array(), JSON_UNESCAPED_UNICODE);
    }

    /**
     * Api delete user appointment
     *
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    function apiDestroy(Request $request, $id)
    {
        $appointment = AppointmentUser::findOrFail($id);
        $status = true;
        try {
            $appointment->delete();
            $request->session()->flash('successful', 'نوبت با موفقیت حذف شد');
        } catch (\Exception $e) {
            $status = false;
            $request->session()->flash('sys_error', 'در حال حاضر امکان حذف نوبت وجود ندارد');
        }
        return response()->json([$status], 200, array(), JSON_UNESCAPED_UNICODE);
    }

    /**
     * Web remove
     *
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    function webDestroy(Request $request, $id)
    {
        $appointment = AppointmentUser::findOrFail($id);
        try {
            $appointment->delete();
            $request->session()->flash('successful', 'نوبت با موفقیت حذف شد');
        } catch (\Exception $e) {
            $request->session()->flash('sys_error', 'در حال حاضر امکان حذف نوبت وجود ندارد');
        }
        return redirect()->back();
    }

    function changeAction(Request $request)
    {
        $appointmentUser = AppointmentUser::findOrFail($request->id);
        $message = '';
        switch ($request->action) {
            case 'cancel':
                $appointmentUser->status = AppointmentUser::STATUS_CANCELED;
                $appointmentUser->save();
                $message = 'نوبت شماره ' . $appointmentUser->code . ' با موفقیت کنسل شد.';
                break;
            case 'approve':
                $appointmentUser->status = AppointmentUser::STATUS_OK;
                $appointmentUser->save();
                $message = 'نوبت شماره ' . $appointmentUser->code . ' با موفقیت تایید شد.';
                break;
            case 'change_to_main':
                $appointmentUser->kind = AppointmentUser::KIND_MAIN_APPOINTMENT;
                $appointmentUser->save();
                $message = 'نوبت شماره ' . $appointmentUser->code . ' با موفقیت به نوبت اصلی تبدیل شد.';
                break;
            case 'change_to_sub':
                $appointmentUser->kind = AppointmentUser::KIND_SUBSIDIARY_APPOINTMENT;
                $appointmentUser->save();
                $message = 'نوبت شماره ' . $appointmentUser->code . ' با موفقیت به نوبت بین مریض تبدیل شد.';
                break;

        }
        return response()->json(['status' => true, 'message' => $message], Response::HTTP_OK, array(),
            JSON_UNESCAPED_UNICODE);
    }


    public function detail(Request $request)
    {
        $week_day = array('sat', 'sun', 'mon', 'tue', 'wed', 'thu', 'fri');
        $roles = [
            'part_id' => ['required','exists:appointment_parts,api_id'],
            'doctor_id' => ['required'],
            'date'  => ['required','date_format:Y-m-d']
        ];
        $message = [
            'part_id.required' => 'بخش مورد نظر را انتخاب کنید',
            'part_id.exists' => 'بخش مورد نظر وارد نشده',
            'doctor_id.required' => 'پزشک مورد نظر را انتخاب کنید',
            'doctor_id.exists' => 'پزشک مورد نظر پیدا نشد',
            'date.required' => 'وارد کردن تاریخ الزامی است',
            'date.date_format' => 'فرمت تاریخ وارد شده صحیح نمی‌باشد',
        ];
        $request->validate($roles,$message);

        $date = strtotime($request->date);
        $partId = $request->part_id;
        $doctor_id = $request->doctor_id;
        $userMeta = UserMeta::whereMetaKeyAndMetaValue(UserMeta::USER_API_ID,$doctor_id);
        if($userMeta->exists()){
            $doctor_id = $userMeta->first()->user_id;
        }else{
            return response()->json(['error'=>false],Response::HTTP_UNAUTHORIZED,array(),JSON_UNESCAPED_UNICODE);
        }
        $findPart = AppointmentPart::whereApiId($partId);
        if($findPart->exists()){
            $partId = $findPart->first()->id;
        }else{
            return response()->json(['error'=>false],Response::HTTP_UNAUTHORIZED,array(),JSON_UNESCAPED_UNICODE);
        }
        $appointment = Appointment::whereUserIdAndAppointmentPartId($doctor_id,$partId);
        $response = array();
        if($appointment->exists()){
            $appointment = $appointment->first();
            $content = $appointment->content;
            $dayInWeek = $week_day[jdate('w',$date,'','','en')];
            $response['data']['day'] = $content[$dayInWeek];
            $response['data']['time_for_visit'] = $appointment->time_for_visit['PUBLIC_TIME'];
            $oldUserAppointment = AppointmentUser::whereAppointmentPartIdAndDoctorIdAndDayAndMonthAndYear($partId,$doctor_id,intval(jdate('j',$date,'','','en')),intval(jdate('n',$date,'','','en')),intval(jdate('Y',$date,'','','en')));
            $response['data']['records'] = $oldUserAppointment->get()->map(function ($appointment) {
                $data = array();
                $data['id'] = $appointment->id;
                $data['user'] = $appointment->user->full_name;
                $data['time_from'] = $appointment->time_from;
                $data['time_to'] = $appointment->time_to;
                $data['type'] = $appointment->kind ? "نوبت اصلی" : "نوبت بین مریض";
                return $data;
            });
        }else{
            $response['date'] = [];
        }
        return response()->json($response,Response::HTTP_OK,array(),JSON_UNESCAPED_UNICODE);
    }


}
