<?php

namespace Modules\Appointment\Http\Controllers\Admin;

use App\Base\AdminApiController;
use App\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Appointment\Entities\AppointmentPart;
use Modules\Appointment\Entities\AppointmentSms;

class AppointmentSmsController extends AdminApiController
{
    protected $routeBaseName = 'admin.appointment_sms.';

    /**
     * Create
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    function create()
    {
        $parts = AppointmentPart::whereParentId(0)->get();
        $doctors = User::role(AppointmentPart::DOCTOR_ROLES)->get();
        return view('appointment::admin.appointment_sms.create', compact('parts', 'doctors'));
    }
    /**
     * Edit
     *
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    function edit($id)
    {
        $sms = AppointmentSms::findOrFail($id);
        $parts = AppointmentPart::whereParentId(0)->get();
        return view('appointment::admin.appointment_sms.edit', compact('parts', 'doctors','sms'));
    }
    /**
     * Store from api
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|Response
     */
    function apiStore(Request $request)
    {
        $roles = [
            'part_id' => 'required|integer',
            'users' => 'required',
            'message' => 'required',
            'day_before' => 'integer',
        ];
        $messages = [
            'day_before.integer' => 'روز را فقط با اعداد انگلیسی وارد کنید'
        ];
        $request->validate($roles, $messages);
        $part_id = $request->part_id;
        $status = $request->status;
        $users = $request->users;
        $message = $request->message;
        $day_before = $request->day_before;

        $insertData = [
            'appointment_part_id' => $part_id,
            'message' => $message,
            'day_before' => $day_before,
            'status' => $status
        ];

        foreach ($users as $user) {
            $insertData['user_id'] = $user;
            AppointmentSms::create($insertData);
        }
        return response(['status'=>true],Response::HTTP_OK,array(),JSON_UNESCAPED_UNICODE);
    }
    /**
     * Store
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    function webStore(Request $request)
    {
        $roles = [
            'part_id' => 'required|integer',
            'users' => 'required',
            'message' => 'required',
            'day_before' => 'integer',
        ];
        $messages = [
            'day_before.integer' => 'روز را فقط با اعداد انگلیسی وارد کنید'
        ];
        $request->validate($roles, $messages);
        $part_id = $request->part_id;
        $status = $request->status;
        $users = $request->users;
        $message = $request->message;
        $day_before_type = $request->day_before_type;
        $day_before = $request->day_before;

        $insertData = [
            'appointment_part_id' => $part_id,
            'message' => $message,
            'day_before' => ($day_before_type == 0) ? 0 : $day_before,
            'status' => $status
        ];

        foreach ($users as $user) {
            $insertData['user_id'] = $user;
            AppointmentSms::create($insertData);
        }
        $request->session()->flash('successful', 'پیامک با موفقیت اضافه شد');
        return redirect()->route($this->routeBaseName . 'index');
    }
    /**
     * Display all
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    function apiIndex(Request $request)
    {
        if (isset($request->act) && $request->act == "search") {
            $sms = AppointmentSms::where(function($q) use($request) {
                if($request->part_id != null && intval($request->part_id) != 0){
                    $q->whereAppointmentPartId($request->part_id);
                }
                if($request->doctor_id != null && intval($request->doctor_id) != 0){
                    $q->whereUserId($request->doctor_id);
                }
            })->paginate(20);
        } else {
            $sms = AppointmentSms::paginate(20);
        }
        $data = array();
        $data['data'] = $sms;
        $doctors = User::role(AppointmentPart::DOCTOR_ROLES)->get();
        foreach ($doctors as $doctor){
            $doctorList[] = [
                'id' => $doctor->id,
                'name' => $doctor->full_name,
                'parts' => $doctor->appointmentParts
            ];
        }
        $data['doctors'] = $doctors;
        $data['parts'] = AppointmentPart::all();
        return response()->json($data,Response::HTTP_OK,array(),JSON_UNESCAPED_UNICODE);
    }
    /**
     * Display all
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    function webIndex(Request $request)
    {
        if (isset($request->act) && $request->act == "search") {
            $sms = AppointmentSms::where(function($q) use($request) {
                if($request->part_id != null && intval($request->part_id) != 0){
                    $q->whereAppointmentPartId($request->part_id);
                }
                if($request->doctor_id != null && intval($request->doctor_id) != 0){
                    $q->whereUserId($request->doctor_id);
                }
            })->paginate(20);
        } else {
            $sms = AppointmentSms::paginate(20);
        }
        $doctors = User::role(AppointmentPart::DOCTOR_ROLES)->get();
        $parts = AppointmentPart::all();
        return view('appointment::admin.appointment_sms.index', compact('sms', 'doctors', 'parts'));
    }
    function apiShow(Request $request, $id){}
    function webShow(Request $request, $id){}
    /**
     * Update from api
     *
     * @param Request $request
     * @param $id
     * @return \Illuminate\Contracts\Routing\ResponseFactory|Response
     */
    function apiUpdate(Request $request, $id)
    {
        /** @var AppointmentSms $sms */
        $sms = AppointmentSms::findOrFail($id);
        $roles = [
            'message' => 'required',
            'day_before' => 'integer',
        ];
        $messages = [
            'day_before.integer' => 'روز را فقط با اعداد انگلیسی وارد کنید'
        ];
        $request->validate($roles, $messages);
        $sms->status = $request->status;
        $sms->message = $request->message;
        $sms->day_before = $request->day_before;
        $sms->save();
        return response(['status'=>true,'data'=>$sms],Response::HTTP_OK,array(),JSON_UNESCAPED_UNICODE);
    }
    /**
     * Update store
     *
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    function webUpdate(Request $request, $id)
    {
        /** @var AppointmentSms $sms */
        $sms = AppointmentSms::findOrFail($id);
        $roles = [
            'message' => 'required',
            'day_before' => 'integer',
        ];
        $messages = [
            'day_before.integer' => 'روز را فقط با اعداد انگلیسی وارد کنید'
        ];
        $request->validate($roles, $messages);
        $sms->status = $request->status;
        $sms->message = $request->message;
        $day_before_type = $request->day_before_type;
        $day_before = $request->day_before;
        $sms->day_before = ($day_before_type == 0) ? 0 : $day_before;
        $sms->save();
        $request->session()->flash('successful', 'پیامک با موفقیت ویرایش شد');
        return redirect()->route($this->routeBaseName . 'index');
    }
    /**
     * Api delete appointment sms
     *
     * @param Request $request
     * @param $id
     * @return \Illuminate\Contracts\Routing\ResponseFactory|Response
     */
    function apiDestroy(Request $request, $id)
    {
        $sms = AppointmentSms::findOrFail($id);
        try {
            $sms->delete();
            $res = ['status'=>true];
        } catch (Exception $e) {
            $res = ['status'=>false];
        }
        return response($res,Response::HTTP_OK,array(),JSON_UNESCAPED_UNICODE);
    }
    /**
     * Web delete
     *
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    function webDestroy(Request $request, $id)
    {
        $sms = AppointmentSms::findOrFail($id);
        try {
            $sms->delete();
            $request->session()->flash('successful', 'پیامک با موفقیت حذف شد');
        } catch (Exception $e) {
            $request->session()->flash('successful', 'خطا در سرور لطفا مجددا تلاش کنید');
        }
        return redirect()->route($this->routeBaseName . 'index');
    }
}
