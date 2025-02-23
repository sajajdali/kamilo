<?php

namespace Modules\Appointment\Http\Controllers\Admin;

use App\Base\AdminApiController;
use App\User;
use App\UserMeta;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Modules\Appointment\Entities\Appointment;
use Modules\Appointment\Entities\AppointmentPart;
use Modules\Appointment\Entities\AppointmentUser;
use Modules\Appointment\Exports\AppointmentUserExport;
use Modules\Finance\Entities\Transaction;

class AppointmentUserController extends AdminApiController
{
    protected $routeBaseName = 'admin.appointment_user.';

    function apiIndex(Request $request)
    {
        // TODO: Implement apiIndex() method.
    }

    /**
     * Web Index
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|AppointmentUserExport
     */
    function webIndex(Request $request)
    {

        $parts = AppointmentPart::whereParentId(0)->get();
        $doctors = User::role(AppointmentPart::DOCTOR_ROLES)->get();
        if ($request->export != null && $request->export == "1") {
            if ($request->act != null && $request->act == "search") {

                $mAppointmentUser = AppointmentUser::withTrashed(Auth::user()->viewTrasheed())->where(function ($query) use ($request) {
                    /** @var AppointmentUser $query */
                    if (!Auth::user()->can('admin_type_full_appointment_user_access')) {
                        $doctorRolsid = AppointmentPart::DOCTOR_ROLES;
                        if (in_array(auth()->user()->roles->first()->id, $doctorRolsid)) {
                            $query->whereDoctorId(Auth::id());
                        } else {
                            $query->whereAgentId(Auth::id());
                        }
                    }
                    if (intval($request->user_id) != 0) {
                        $query->whereUserId($request->user_id);
                    }

                    if ($request->start_date_store_appointment != null) {

                        $from = explode('/', $request->input('start_date_store_appointment'));
                        $from = jalali_to_gregorian($from[0], $from[1], $from[2], '-');
                        $from = $from . ' 00:00:00';
                        $query->where('updated_at', '>=', $from);

                    }

                    if ($request->end_date_store_appointment != null) {

                        $from = explode('/', $request->input('end_date_store_appointment'));
                        $from = jalali_to_gregorian($from[0], $from[1], $from[2], '-');
                        $from = $from . ' 23:59:59';
                        $query->where('updated_at', '<=', $from);

                    }

                    if (intval($request->agent_id) != 0) {
                        $query->whereAgentId($request->agent_id);
                    }
                    if (intval($request->part_id) != 0) {
                        $query->whereAppointmentPartId($request->part_id);
                    }
                    if (intval($request->doctor_id) != 0) {
                        $query->whereDoctorId($request->doctor_id);
                    }
                    if (intval($request->kind) != -1) {
                        $query->whereKind($request->kind);
                    }
                    if (intval($request->status) != -1) {
                        if ($request->status == AppointmentUser::STATUS_HAS_IDENTIFIERـCODE) {
                            $query->whereNotNull('detail->' . AppointmentUser::IDENTIFIERـCODE);
                        } elseif ($request->status == AppointmentUser::STATUS_PANAHI) {
                            $query->where('status', '<>', AppointmentUser::STATUS_DELETED);
                        } else {
                            $query->whereStatus($request->status);
                        }
                    }
                    if ($request->code != null) {
                        $query->whereCode($request->code);
                    }
                    if ($request->start_date != $request->end_date && $request->start_date != null) {
                        $date = explode("/", $request->start_date);
                        $query->where([["year", ">=", $date[0]], ["month", ">=", $date[1]], ["day", ">=", $date[2]]]);
                    }
                    if ($request->start_date != $request->end_date && $request->end_date != null) {
                        $date = explode("/", $request->end_date);
                        $query->where([["year", "<=", $date[0]], ["month", "<=", $date[1]], ["day", "<=", $date[2]]]);
                    }
                    if ($request->start_date == $request->end_date && $request->start_date != null && $request->end_date != null) {
                        $date = explode("/", $request->end_date);
                        $query->where([["year", "=", $date[0]], ["month", "=", $date[1]], ["day", "=", $date[2]]]);
                    }
                    if ($request->start_clock != null) {
                        $query->where('time_from', '>=', $request->start_clock . ":00");
                    }
                    if ($request->end_clock != null) {
                        $query->where('time_to', '<=', $request->end_clock . ":00");
                    }
                    if ($request->identifierـcode != null) {
                        if (\auth()->user()->getAuthIdentifier() == 1) {
                            $query->whereJsonContains('detail->' . AppointmentUser::IDENTIFIERـCODE, $request->identifierـcode);
                        }
                    }
                    if ($request->appointment_first_type) {
                        $query->whereJsonContains('detail->APPOINTMENT_FIRST_TYPE' , $request->appointment_first_type);

                    }
                    if (intval($request->transaction) != 0) {
                        $query->whereHas('transaction', function ($query) use ($request) {
                            /** @var $query Transaction */
                            if (intval($request->transaction) == 1) {
                                $query->whereStatus(Transaction::STATUS_SUCCESS);
                            } else {
                                $query->where('status', '<>', Transaction::STATUS_SUCCESS);
                            }
                        });
                    }
                })->whereHas('user', function ($query) use ($request) {
                    /** @var $query User */
                    if ($request->mobile != null) {
                        $query->whereMobile($request->mobile);
                    }
                    if ($request->acquainted) {
                        $query->whereHas('userMetas', function ($q) use ($request) {
                            $q->where([
                                ['meta_key', 'ACQUAINTED'],
                                ['meta_value', "=", $request->acquainted]
                            ]);
                        });
                    }

                    if ($request->city) {

                        $querySearch = $request->city;
                        $ondition = '=';
                        if (strpos($request->city, '+') !== false) {
                            $querySearch = str_replace("+", "", $request->city);
                            $ondition = '<>';
                        }


                        $query->whereHas('userMetas', function ($q) use ($querySearch, $ondition) {
                            $q->where([
                                ['meta_key', UserMeta::ADDRESS],
                                ['meta_value', $ondition, $querySearch]
                            ])->where([
                                ['meta_key', UserMeta::ADDRESS],
                                ['meta_value', '!=', '']
                            ]);
                        });
                    }
                    if ($request->firstname) {
                        $query->whereHas('userMetas', function ($q) use ($request) {
                            $q->where([
                                ['meta_key', UserMeta::FIRST_NAME],
                                ['meta_value', "LIKE", "%" . $request->firstname . "%"]
                            ]);
                        });
                    }
                    if ($request->lastname) {
                        $query->whereHas('userMetas', function ($q) use ($request) {
                            $q->where([
                                ['meta_key', UserMeta::LAST_NAME],
                                ['meta_value', "LIKE", "%" . $request->lastname . "%"]
                            ]);
                        });
                    }
                })->whereHas('agent', function ($query) use ($request) {
                    if ($request->agent_name != null) {
                        $query->whereHas('userMetas', function ($q) use ($request) {
                            $q->where([
                                ['meta_key', UserMeta::FIRST_NAME],
                                ['meta_value', "LIKE", "%" . $request->agent_name . "%"]
                            ])->orwhere([
                                ['meta_key', UserMeta::LAST_NAME],
                                ['meta_value', "LIKE", "%" . $request->agent_name . "%"]
                            ]);
                        });
                    }
                })->get();
            } else {

                $mAppointmentUser = AppointmentUser::withTrashed(Auth::user()->viewTrasheed())->where(function ($query) {
                    if (!Auth::user()->can('admin_type_full_appointment_user_access')) {
                        $doctorRolsid = AppointmentPart::DOCTOR_ROLES;
                        if (in_array(auth()->user()->roles->first()->id, $doctorRolsid)) {
                            $query->whereDoctorId(Auth::id());
                        } else {
                            $query->whereAgentId(Auth::id());
                        }
                    }
                })->get();
            }
        } else {
//            dd(Auth::user()->viewTrasheed());

            if ($request->act != null && $request->act == "search") {

                $mAppointmentUser = AppointmentUser::withTrashed(Auth::user()->viewTrasheed())->where(function ($query) use ($request) {
                    /** @var AppointmentUser $query */
                    if (!Auth::user()->can('admin_type_full_appointment_user_access')) {
                        $doctorRolsid = AppointmentPart::DOCTOR_ROLES;
                        if (in_array(auth()->user()->roles->first()->id, $doctorRolsid)) {
                            $query->whereDoctorId(Auth::id());
                        } else {
                            $query->whereAgentId(Auth::id());
                        }
                    }
                    if (intval($request->user_id) != 0) {
                        $query->whereUserId($request->user_id);
                    }
                    if ($request->start_date_store_appointment != null) {

                        $from = explode('/', $request->input('start_date_store_appointment'));
                        $from = jalali_to_gregorian($from[0], $from[1], $from[2], '-');
                        $from = $from . ' 00:00:00';
                        $query->where('created_at', '>=', $from);

                    }

                    if ($request->end_date_store_appointment != null) {

                        $from = explode('/', $request->input('end_date_store_appointment'));
                        $from = jalali_to_gregorian($from[0], $from[1], $from[2], '-');
                        $from = $from . ' 23:59:59';
                        $query->where('created_at', '<=', $from);

                    }
                    if (intval($request->agent_id) != 0) {
                        $query->whereAgentId($request->agent_id);
                    }
                    if (intval($request->part_id) != 0) {
                        $query->whereAppointmentPartId($request->part_id);
                    }
                    if (intval($request->doctor_id) != 0) {
                        $query->whereDoctorId($request->doctor_id);
                    }
                    if (intval($request->kind) != -1) {
                        $query->whereKind($request->kind);
                    }

                    if (intval($request->status) != -1) {
                        if ($request->status == AppointmentUser::STATUS_HAS_IDENTIFIERـCODE) {
                            $query->whereNotNull('detail->' . AppointmentUser::IDENTIFIERـCODE);
                        } elseif ($request->status == AppointmentUser::STATUS_PANAHI) {
                            $query->where('status', '<>', AppointmentUser::STATUS_DELETED);
                        } else {
                            $query->whereStatus($request->status);
                        }
                    }

                    if ($request->code != null) {
                        $query->whereCode($request->code);
                    }
                    if ($request->start_date != $request->end_date && $request->start_date != null) {
                        $date = explode("/", $request->start_date);
                        $query->where([["year", ">=", $date[0]], ["month", ">=", $date[1]], ["day", ">=", $date[2]]]);
                    }
                    if ($request->start_date != $request->end_date && $request->end_date != null) {
                        $date = explode("/", $request->end_date);
                        $query->where([["year", "<=", $date[0]], ["month", "<=", $date[1]], ["day", "<=", $date[2]]]);
                    }
                    if ($request->start_date == $request->end_date && $request->start_date != null && $request->end_date != null) {
                        $date = explode("/", $request->end_date);
                        $query->where([["year", "=", $date[0]], ["month", "=", $date[1]], ["day", "=", $date[2]]]);
                    }
                    if ($request->start_clock != null) {
                        $query->where('time_from', '>=', $request->start_clock . ":00");
                    }
                    if ($request->end_clock != null) {
                        $query->where('time_to', '<=', $request->end_clock . ":00");
                    }
                    if ($request->identifierـcode != null) {
                        if (\auth()->user()->getAuthIdentifier() == 1) {
                            $query->whereJsonContains('detail->' . AppointmentUser::IDENTIFIERـCODE, $request->identifierـcode);
                        }
                    }
                    if ($request->appointment_first_type) {
                        $query->whereJsonContains('detail->APPOINTMENT_FIRST_TYPE' , $request->appointment_first_type);

                    }

                    if (intval($request->transaction) != 0) {
                        $query->whereHas('transaction', function ($query) use ($request) {
                            /** @var $query Transaction */
                            if (intval($request->transaction) == 1) {
                                $query->whereStatus(Transaction::STATUS_SUCCESS);
                            } else {
                                $query->where('status', '<>', Transaction::STATUS_SUCCESS);
                            }
                        });
                    }
                })->whereHas('user', function ($query) use ($request) {
                    /** @var $query User */
                    if ($request->mobile != null) {
                        $query->whereMobile($request->mobile);
                    }
                    if ($request->acquainted) {
                        $query->whereHas('userMetas', function ($q) use ($request) {
                            $q->where([
                                ['meta_key', 'ACQUAINTED'],
                                ['meta_value', "=", $request->acquainted]
                            ]);
                        });
                    }

                    if ($request->city) {

                        $querySearch = $request->city;
                        $ondition = '=';
                        if (strpos($request->city, '+') !== false) {
                            $querySearch = str_replace("+", "", $request->city);
                            $ondition = '<>';
                        }


                        $query->whereHas('userMetas', function ($q) use ($querySearch, $ondition) {
                            $q->where([
                                ['meta_key', UserMeta::ADDRESS],
                                ['meta_value', $ondition, $querySearch]
                            ])->where([
                                ['meta_key', UserMeta::ADDRESS],
                                ['meta_value', '!=', '']
                            ]);
                        });
                    }
                    if ($request->firstname) {
                        $query->whereHas('userMetas', function ($q) use ($request) {
                            $q->where([
                                ['meta_key', UserMeta::FIRST_NAME],
                                ['meta_value', "LIKE", "%" . $request->firstname . "%"]
                            ]);
                        });
                    }
                    if ($request->lastname) {
                        $query->whereHas('userMetas', function ($q) use ($request) {
                            $q->where([
                                ['meta_key', UserMeta::LAST_NAME],
                                ['meta_value', "LIKE", "%" . $request->lastname . "%"]
                            ]);
                        });
                    }
                })->whereHas('agent', function ($query) use ($request) {
                    if ($request->agent_name != null) {
                        $query->whereHas('userMetas', function ($q) use ($request) {
                            $q->where([
                                ['meta_key', UserMeta::FIRST_NAME],
                                ['meta_value', "LIKE", "%" . $request->agent_name . "%"]
                            ])->orwhere([
                                ['meta_key', UserMeta::LAST_NAME],
                                ['meta_value', "LIKE", "%" . $request->agent_name . "%"]
                            ]);
                        });
                    }
                })->orderBy('id', 'DESC')->paginate(30)->appends(request()->query());
            } else {
                $mAppointmentUser = AppointmentUser::withTrashed(Auth::user()->viewTrasheed())->where(function ($query) {
                    if (!Auth::user()->can('admin_type_full_appointment_user_access')) {
                        $doctorRolsid = AppointmentPart::DOCTOR_ROLES;
                        if (in_array(auth()->user()->roles->first()->id, $doctorRolsid)) {
                            $query->whereDoctorId(Auth::id());
                        } else {
                            $query->whereAgentId(Auth::id());
                        }

                    }
                })->orderBy('id', 'DESC')->paginate(50)->appends(request()->query());

            }
        }
        if ($request->export != null && $request->export == "1") {
            $dataSheets = [];
            $multiplied = $mAppointmentUser->map(function ($item, $key) {
                /** @var AppointmentUser timestamp */
                $item->timestamp = jmktime(0, 0, 0, $item->month, $item->day, $item->year);
                return $item;
            });
            $uniqueDay = Arr::sort(array_keys($multiplied->groupBy('timestamp')->toArray()));
            foreach ($uniqueDay as $item) {
                $dataSheets[] = [
                    'title' => jdate('l d F', $item),
                    'titleExellPage' => "لیست نوبت ها برای " . jdate('l d F Y', $item),
                    "items" => $mAppointmentUser->where('timestamp', $item)->pluck('id')->toArray()
                ];
            }
            return new AppointmentUserExport($dataSheets);
        }


        //dd(Auth::user()->checkPermissionTo('admin_type_limited_appointment_user_access_cancel'));
        return view('appointment::admin.appointment_user.index', compact('mAppointmentUser', 'parts', 'doctors'));
    }


    function create()
    {
        // TODO: Implement create() method.
    }

    /**
     * Edit user appointments
     *
     * @param $id
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    function edit($id)
    {
        $mAppointmentUser = AppointmentUser::findOrFail($id);
        $act = request()->act;
        $message = '';
        switch ($act) {
            case 'cancel':
                $mAppointmentUser->status = AppointmentUser::STATUS_CANCELED;
                if (request()->get('sms') == 'send') {
//                    $messgae = appointmentUserTextFilter(get_option(Appointment::SETTING_SMS_AFTER_CANCEL_MESSAGE), $mAppointmentUser);
//                    sendSmsNow($mAppointmentUser->id, $messgae, "Modules\Appointment\Entities\AppointmentUser");
                    $time = explode(':', $mAppointmentUser->time_from);
                    smsNow($mAppointmentUser->user->mobile,
                        'kamiloCancel',
                        "{$mAppointmentUser->year}/{$mAppointmentUser->month}/{$mAppointmentUser->day}",
                        "{$time[0]}:{$time[1]}",
                        $mAppointmentUser->detail[AppointmentUser::SHORT_LINK] ?? ''
                    );
                }
                $message = "نوبت " . $mAppointmentUser->code . " با موفقیت کنسل شد";
                break;
            case 'approve':
                $mAppointmentUser->status = AppointmentUser::STATUS_OK;
                $message = "نوبت " . $mAppointmentUser->code . " با موفقیت تایید شد";
                break;
            case 'change_to_main':
                $mAppointmentUser->kind = AppointmentUser::KIND_MAIN_APPOINTMENT;
                $message = "نوبت " . $mAppointmentUser->code . " با موفقیت به نوبت اصلی تبدیل شد.";
                break;
            case 'change_to_subsidiary':
                $mAppointmentUser->kind = AppointmentUser::KIND_SUBSIDIARY_APPOINTMENT;
                $message = "نوبت " . $mAppointmentUser->code . " با موفقیت به نوبت بین مریض تبدیل شد.";
                break;
        }
        $mAppointmentUser->save();
        if (request()->wantsJson()) {
            return response()->json(['message' => $message], Response::HTTP_OK, array(), JSON_UNESCAPED_UNICODE);
        } else {
            request()->session()->flash('successful', $message);
            return redirect()->route($this->routeBaseName . "index");
        }
    }

    function apiStore(Request $request)
    {
        // TODO: Implement apiStore() method.
    }

    function webStore(Request $request)
    {
        // TODO: Implement webStore() method.
    }


    function apiShow(Request $request, $id)
    {
        // TODO: Implement apiShow() method.
    }

    function webShow(Request $request, $id)
    {
        // TODO: Implement webShow() method.
    }

    function apiUpdate(Request $request, $id)
    {
        // TODO: Implement apiUpdate() method.
    }

    /**
     * Change appointment status and kind
     *
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    function webUpdate(Request $request, $id)
    {
        $act = $request->act;
        $appointments = $request->appointment_user;
        if ($act == "approve") {
            foreach ($appointments as $id) {
                $appointment = AppointmentUser::find($id);
                if ($appointment->exists() && $appointment->status == AppointmentUser::STATUS_PENDING) {
                    $appointment->status = AppointmentUser::STATUS_OK;
                    $appointment->save();
                }
            }
            request()->session()->flash('successful', "نوبت‌های انتخابی با موفقیت تایید شدن");
            return redirect()->route($this->routeBaseName . "index");
        } elseif ($act == "delete") {
            if (!Auth::user()->can('delete_admin_appointment_user')) {
                request()->session()->flash('sys_error', "شما به حذف نوبت ها دسترسی ندارید");
            }
            $errors = [];
            $successShow = false;

            foreach ($appointments as $id) {
                $appointment = AppointmentUser::find($id);
                if (
                    (checkAccessDeleteOrUpdate() == false) &&
                    (Auth::user()->can('admin_type_limited_appointment_user_access_delete') && Auth::id() != $appointment->agent_id)
                ) {
                    $errors[] = 'فقط نوبت هایی که خودتان ثبت کردید را میتوانید حذف کنید - امکان حذف نوبت برای ' . $appointment->user->full_name . ' را ندارید';
                    continue;
                }
                if ($appointment->exists()) {
                    try {
                        $successShow = true;
                        $appointment->status = AppointmentUser::STATUS_DELETED;
                        $appointment->save();
                        $appointment->delete();
                    } catch (\Exception $e) {
                    }
                }
            }
            if (count($errors) > 0) {
                $errors = implode(" ", $errors);
                request()->session()->flash('sys_error', $errors);
            }
            if ($successShow) {
                request()->session()->flash('successful', "نوبت‌های انتخابی با موفقیت حذف شدن");
            }
            return redirect()->route($this->routeBaseName . "index");
        } elseif ($act == 'cancel') {

            if (!Auth::user()->can('cancel_admin_appointment_user')) {
                request()->session()->flash('sys_error', "شما به کنسل کردن نوبت ها دسترسی ندارید");
            }
            $errors = [];
            $successShow = false;

            $smsStatus = $request->sms_status;
            foreach ($appointments as $id) {
                $appointment = AppointmentUser::find($id);
                if (
                    (checkAccessDeleteOrUpdate() == false) &&
                    (Auth::user()->can('admin_type_limited_appointment_user_access_cancel') && Auth::id() != $appointment->agent_id)
                ) {
                    $errors[] = 'فقط نوبت هایی که خودتان ثبت کردید را میتوانید کنسل کنید - امکان کنسلی نوبت برای ' . $appointment->user->full_name . ' را ندارید';
                    continue;
                }
                if ($appointment->exists() && ($appointment->status == AppointmentUser::STATUS_OK || $appointment->status == AppointmentUser::STATUS_PENDING)) {
                    $appointment->status = AppointmentUser::STATUS_CANCELED;
                    if ($smsStatus == "1") {
                        $time = explode(':', $appointment->time_from);
                        smsNow($appointment->user->mobile,
                            'kamiloCancel',
                            "{$appointment->year}/{$appointment->month}/{$appointment->day}",
                            "{$time[0]}:{$time[1]}",
                            $appointment->detail[AppointmentUser::SHORT_LINK] ?? ''
                        );

                    }
                    $successShow = true;
                    $appointment->save();
                }
            }
            if (count($errors) > 0) {
                $errors = implode(" ", $errors);
                request()->session()->flash('sys_error', $errors);
            }
            if ($successShow) {
                request()->session()->flash('successful', "نوبت‌های انتخابی با موفقیت کنسل شدن");
            }
            return redirect()->route($this->routeBaseName . "index");
        } else {
            request()->session()->flash('sys_error', "عملیات انتخابی شما پشتیبانی نمی‌شود");
            return redirect()->route($this->routeBaseName . "index");
        }
    }

    function apiDestroy(Request $request, $id)
    {
        $appointment = AppointmentUser::findOrFail($id);
        $status = true;
        try {
            $appointment->status = AppointmentUser::STATUS_DELETED;
            $appointment->save();
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
            $appointment->status = AppointmentUser::STATUS_DELETED;
            $appointment->save();
            $appointment->delete();
            $request->session()->flash('successful', 'نوبت با موفقیت حذف شد');
        } catch (\Exception $e) {
            $request->session()->flash('sys_error', 'در حال حاضر امکان حذف نوبت وجود ندارد');
        }
        return redirect()->back();
    }

    /**
     * @param Request $request
     * @param AppointmentUser $appointment
     * @return \Illuminate\Http\RedirectResponse
     */
    public function sendStandardSmsStore(Request $request, AppointmentUser $appointment)
    {
        $partName = $appointment->appointmentPart->title;
        $doctorFullName = $appointment->doctor->full_name;
        $template = $request->get('template');
        $time_from = $request->get('time_from');

        $firstName = $appointment->user->first_name;
        $mobile = $appointment->user->mobile;
        $link = $appointment->detail['SHORT_LINK'] ?? '';

        //date appointment
        $day = $appointment->day;
        $month = $appointment->month;
        $year = $appointment->year;
        $dateAppointment = "{$year}/{$month}/{$day}";
        $dayName = jdate('l', jmktime(0, 0, 0, $month, $day, $year));


        //date appointment

        $detail = $appointment->detail;
        $detail['STANDARD_SMS'][$template] = [
            'send' => true,
            'admin_id' => Auth::id(),
            'send_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ];
        $appointment->detail = $detail;
        $appointment->save();
        if ($template == 'kamiloReminder1' || $template == 'kamiloReminder2'){
            smsNow($mobile, $template, $firstName, $time_from, $link);
        }
        elseif ($template == 'kamiloReminder3' || $template == 'kamiloReminder4'){
            smsNow($mobile, $template, $firstName,$dateAppointment,$dayName, $time_from, $link);
        }
        elseif ($template == 'kamiloReminder5'){
            smsNow($mobile, $template, $firstName,$dateAppointment,$dayName, $link);
        }
        elseif ($template == 'kamiloReminder6' ||$template == 'kamiloReminder9'){
            smsNow($mobile, $template, $firstName,$dateAppointment,$dayName, $time_from);
        }
        elseif ($template == 'kamiloReminder7' || $template == 'kamiloReminder8'){
            smsNow($mobile, $template, $firstName,$dateAppointment,$dayName);
        }
        elseif ($template == 'kamiloReminder10'){
            smsNow($mobile, $template, $firstName);
        }
        elseif ($template == 'kamiloReminder11' ||  $template == 'kamiloReminder12' || $template == 'kamiloReminder14'){
            smsNow($mobile, $template, $firstName,$dayName,$dateAppointment,$time_from,$partName,$doctorFullName,$link);
        }
        elseif ($template == 'kamiloReminder13'){
            smsNow($mobile, $template, $firstName);
        }

        $request->session()->flash('successful', 'پیامک با موفقیت ارسال شد');
        return redirect()->back();

    }

    public function sendStandardSms(AppointmentUser $appointment)
    {
        $time = explode(':', $appointment->time_from);
        $timeFrom = "{$time[0]}:{$time[1]}";
        return view('appointment::admin.standard_sms.index', compact('appointment', 'timeFrom'));

    }
}
