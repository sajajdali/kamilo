<?php

namespace Modules\Appointment\Http\Controllers\Admin;

use App\User;
use App\UserMeta;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Hash;
use Modules\Appointment\Entities\Appointment;
use Modules\Appointment\Entities\AppointmentUser;
use Modules\Appointment\Entities\oldAppointments\ReservationDoctors;
use Modules\Appointment\Entities\oldAppointments\ReservationList;
use Modules\Appointment\Entities\oldAppointments\Reservation;
use Modules\Appointment\Entities\oldAppointments\Useraura;
use Spatie\Permission\Models\Role;

class AppointmentOldController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */


    private function getLastPartTime($reservationId='',$dateReserv='',$timeReserv=''){

        // تعداد بیمار قابل پذیرش در هر ساعت

        $numberSick = Reservation::whereId($reservationId)->first()->number_sickie;
        $nobat = array();
        $i = 1;
        while($numberSick > 0){
            $nobat[$i] = $i;
            $numberSick--;
            $i++;
        }
        // تعداد بیمار قابل پذیرش در هر ساعت

        $reservationList = ReservationList::where(['medical_id' =>  $reservationId , 'date_reserv'  =>  $dateReserv , 'time_reserv' => $timeReserv]);
        if($reservationList->exists()){
            foreach ($reservationList as $reservations => $reservation){
                $type_time = $reservation->type_time;
                $search = array_search($type_time, $nobat);
                if($search > 0){
                    unset($nobat[$search]);
                }
            }


            $returnNobat = min($nobat);
        }
        else{
            $returnNobat = 1;
        }



        return $returnNobat;
        //return $search;
    }



    private function GetIsTimeResence($hourMinute='',$ResenceTime='',$added = 0){
        $isHourse 		= ( ($hourMinute - $ResenceTime) );
        if($added > 0){
            $isHourse += $added;

        }
        return 		date('H:i', mktime(0,$isHourse)).":00";

    }
    // زمان حضور بیمار در مطب
    private function creatCurrectGoTime($codePeigiri=''){
        $reservationList = ReservationList::where(['reservation_code'  =>  $codePeigiri])->orderBy('id','DESC')->first();
        if($reservationList != null){
            $medical_id = $reservationList->medical_id;
            $reservation = Reservation::whereId($medical_id)->first();
            $timeReserv = $reservationList->time_reserv;
            $type_time 	= $reservationList->type_time;
            $explode 	= explode("-",$timeReserv);
            $startHour 	= $explode[0];
            $explode2 	= explode(":",$startHour);
            $isHour 	= $explode2[0];
            $ResenceTime = $reservation->time_resence;
            $numberSick	= $reservation->number_sickie;
            $isVisited	= (60 / $numberSick);
            $yourTime	= ($type_time-1) * $isVisited;
            $hoursToMinute 	= ($isHour * 60 ) + $yourTime;
            return[
                'from'  => $this->GetIsTimeResence($hoursToMinute,$ResenceTime,''),
                'to'    =>  $this->GetIsTimeResence($hoursToMinute,$ResenceTime,(60 / $reservation->number_sickie))
            ];
        }
        else{
            return null;
        }
    }


    public function storeAppointment(Request $request)
    {
        $mobile = $request->mobile;
        $agentId = $request->agentId;
        $doctor_id = $request->doctor_id;
        $part_id = $request->part_id;
        $user_id = $request->user_id;
        $firstname = $request->firstname;
        $lastname = $request->lastname;
        $gender = $request->gender;
        $start_time = $request->start_time;
        $end_time = $request->end_time;
        $kind = $request->kind;
        $year = $request->year;
        $month = $request->month;
        $day = $request->day;
        $type = $request->type;
        $r_part = ($request->r_part != "undefined") ? $request->r_part : array();
        if(User::whereMobile($mobile)->exists()){
            $user_id = User::whereMobile($mobile)->get()->first()->id;
        }
        elseif ($user_id == 0 || !User::whereId($user_id)->exists()) {
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
            ]);
        } else {
            UserMeta::updateOrCreate(['user_id' => $user_id, 'meta_key' => UserMeta::FIRST_NAME],
                ['meta_value' => $firstname]);
            UserMeta::updateOrCreate(['user_id' => $user_id, 'meta_key' => UserMeta::LAST_NAME],
                ['meta_value' => $lastname]);
            UserMeta::updateOrCreate(['user_id' => $user_id, 'meta_key' => UserMeta::GENDER],
                ['meta_value' => $gender]);
        }

        $appointmetn = AppointmentUser::create([
            'agent_id' => $agentId,
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
            'ip' => $request->ip,
            'status' => AppointmentUser::STATUS_OK,
            'kind' => $kind,
            'visit' => AppointmentUser::VISIT_PENDING,
            'r_part' => Arr::wrap($r_part),
        ]);
        $appointmetn->code = $appointmetn->generateCode();
        $appointmetn->save();

        return response()->json(['status' => true, 'code' => $appointmetn->code], 200, array(), JSON_UNESCAPED_UNICODE);
    }

    private function convertPersianNumbersToEnglish($number)
    {
        $persian = array('۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹');
        $num = range(0, 9);
        return (int)str_replace($persian, $num, $number);
    }

    public function index()
    {


        $request = new \Illuminate\Http\Request();

        $doctorId_Reservation   = 3;
        $partId_Reservation     = 44;

        $doctorId_Appointment   = 29;
        $partId_Appointment     = 7;

        $list = ReservationDoctors::find($doctorId_Reservation)->Reservations->find($partId_Reservation)->ReservationLists()->whereInserted(0)->orderBy('id','DESC')->take(200)->get();
        if($list->count() == 0){
            dd("insert success");
        }
        //$listReservation = ReservationList::where('break_down',0)->orderBy('id','DESC')->limit(20)->get();
        foreach ($list as $reservations => $reservation) {



            //$dateReserv = $this->creatCurrectGoTime($reservation->reservation_code,Appointment::where(['appointment_part_id' => $partId_Appointment, 'user_id' => $doctorId_Appointment])->first()->time_for_visit['PUBLIC_TIME']);
            $dateReserv = $this->creatCurrectGoTime($reservation->reservation_code);

            $agentId  = ( Useraura::where('UserId',$reservation->admin_UserId)->exists() ? Useraura::where('UserId', $reservation->admin_UserId)->get()->first()->user_id : 1);

            if($dateReserv == null) continue;
            $dataAppointment = [
                'agentId'           => $agentId,
                'mobile'            => $reservation->mob,
                'doctor_id'         => $doctorId_Appointment,
                'part_id'           => $partId_Appointment,
                'user_id'           => 0,
                'firstname'         => $reservation->name,
                'lastname'          => '',
                'gender'            => ($reservation->jensiat == 'woman' ? 2 : 1),
                'start_time'        => $dateReserv['from'],
                'end_time'          => $dateReserv['to'],
                'day'               => $this->convertPersianNumbersToEnglish(jdate('d', $reservation->date_reserv)),
                'month'             => $this->convertPersianNumbersToEnglish(jdate('m', $reservation->date_reserv)),
                'year'              => $this->convertPersianNumbersToEnglish(jdate('Y', $reservation->date_reserv)),
                'kind'              => 1,
                'sms_status'        => 0,
                'sms_text_status'   => 0,
                'ip'                => '192.168.1.1',
                'type'              => 'IN_PERSON',
            ];
            $request->replace($dataAppointment);
            $request->setMethod('POST');
            $resultSuccess[] = "نوبت کاربر $reservation->name با موفقیت اضافه شد ";

            ReservationList::updateOrCreate(['id' => $reservation->id], ['inserted' => 1]);

            $this->storeAppointment($request);

        }
        dd($resultSuccess);
        //return view('appointment::index');
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        return view('appointment::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Response
     */
    public function show($id)
    {
        return view('appointment::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Response
     */
    public function edit($id)
    {
        return view('appointment::edit');
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Response
     */
    public function destroy($id)
    {
        //
    }
}
