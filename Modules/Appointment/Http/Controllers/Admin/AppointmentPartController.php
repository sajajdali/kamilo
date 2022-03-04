<?php

namespace Modules\Appointment\Http\Controllers\Admin;

use App\Base\AdminApiController;
use App\User;
use Illuminate\Http\Request;
use Modules\Appointment\Entities\AppointmentPart;

class AppointmentPartController extends AdminApiController
{
    protected $routeBaseName = 'admin.appointment_part.';

    public function apiIndex(Request $request)
    {
        return response()->json(AppointmentPart::paginate(20), 200, array(), JSON_UNESCAPED_UNICODE);
    }

    public function webIndex(Request $request)
    {
        $parts = AppointmentPart::whereParentId(0)->paginate(20);
        return view('appointment::admin.appointment_part.index', compact('parts'));
    }


    function create()
    {

        if (request()->wantsJson()) {
            $doctorList = [];
            $doctors = User::role(AppointmentPart::DOCTOR_ROLES)->get();
            foreach ($doctors as $doctor){
                $doctorList[] = [
                    'id' => $doctor->id,
                    'name' => $doctor->full_name
                ];
            }
            $parts = AppointmentPart::all();
            $data['doctors'] = $doctorList;
            $data['parts'] = $parts;
            return response()->json($data, 200, array(), JSON_UNESCAPED_UNICODE);
        } else {
            $doctor = User::role(AppointmentPart::DOCTOR_ROLES)->get();
            $parts = AppointmentPart::whereParentId(0)->get();

            return view('appointment::admin.appointment_part.create', compact('doctor', 'parts'));
        }
    }

    function edit($id)
    {
        $part = AppointmentPart::findOrFail($id);
        $doctor = User::role(AppointmentPart::DOCTOR_ROLES)->get();
        $parts = AppointmentPart::whereParentId(0)->get();
        $partDoctor = $part->users()->get(['id'])->pluck('id')->toArray();
        return view('appointment::admin.appointment_part.edit', compact('doctor', 'parts', 'part', 'partDoctor'));
    }

    public function store(Request $request)
    {
        $roles = [
            'title' => 'required'
            ];
        $messages = [
            'title.required' => 'وارد کردن عنوان بخش الزامی است',
          'api_id.unique' => 'این ایدی API قبلا وارد شده است',
           // 'api_id.integer' => 'ایدی API باید به صورت عدد انگلیسی وارد شود'
        ];
   if($request->api_id != null){
            $roles['api_id'] = 'integer|unique:appointment_parts,api_id';
        }
        $request->validate($roles, $messages);
        $part = AppointmentPart::create($request->only(['title', 'parent_id', 'status','api_id']));
        $part->users()->sync($request->users);
        return parent::store($request);
    }

    function apiStore(Request $request)
    {
        return response()->json(['status' => true]);
    }

    function webStore(Request $request)
    {
        $request->session()->flash('successful', 'بخش جدید با موفقیت اضافه شد');
        return redirect()->route($this->routeBaseName . 'index');
    }

    public function update(Request $request, $id)
    {
        $roles = [
            'title' => 'required'
        ];
 $messages = [
            'title.required' => 'وارد کردن عنوان بخش الزامی است',
            'api_id.unique' => 'این ایدی API قبلا وارد شده است',
            'api_id.integer' => 'ایدی API باید به صورت عدد انگلیسی وارد شود'
        ];
$part = AppointmentPart::findOrFail($id);
        if($request->api_id != null && $request->api_id != $part->api_id){
            $roles['api_id'] = 'integer|unique:appointment_parts,api_id';
        }
        $request->validate($roles, $messages);
        $part->update($request->only(['title', 'parent_id', 'status','api_id']));
        $part->users()->sync($request->users);
        return parent::update($request, $id);
    }

    function apiUpdate(Request $request, $id)
    {
        return response()->json(['status'=> true]);
    }

    function webUpdate(Request $request, $id)
    {
        $request->session()->flash('successful', 'بخش با موفقیت ویرایش شد');
        return redirect()->route($this->routeBaseName . 'index');
    }

    public function destroy(Request $request, $id)
    {
        $appointment = AppointmentPart::findOrFail($id);
        if ($appointment->children()->exists()) {
            foreach ($appointment->children as $child) {
                $child->update(['parent_id' => 0]);
            }
        }
        try {
            $appointment->delete();
            $request->session()->flash('successful', 'بخش با موفقت حذف شد');
        } catch (\Exception $e) {
        }
        return parent::destroy($request, $id);
    }

    function apiDestroy(Request $request, $id)
    {
        return response()->json(['status' => true]);
    }

    function webDestroy(Request $request, $id)
    {
        return redirect()->route($this->routeBaseName . 'index');
    }


    function apiShow(Request $request, $id)
    {
        $data['part'] = AppointmentPart::findOrFail($id);
        $data['doctors'] = $data['part']->users()->get()->pluck('full_name','id');
        return response()->json($data, 200, array(), JSON_UNESCAPED_UNICODE);
    }

    function webShow(Request $request, $id)
    {
    }
}
