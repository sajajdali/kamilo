<?php

namespace Modules\Appointment\Http\Controllers\Profile;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Appointment\Entities\Appointment;
use Modules\Appointment\Entities\AppointmentUser;
use Session;

class AppointmentResultController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function cancel($code , $id){
        Session::flash('cancelAppointment', true);
        return redirect()->route('profile.appointment.result.show',[$code,$id]);
    }
    
    public function result($code , $id)
    {

        $canCancelAppointmentUser       = false;
        $requestCancelAppointment       = false;
        if (Session::has('cancelAppointment'))
        {
            $requestCancelAppointment = true;
            Session::forget('cancelAppointment');
        }

        $appointmentUser = AppointmentUser::whereCodeAndId($code,$id);
        if($appointmentUser->exists()){
            $appointmentDetail = $appointmentUser->first();

            $detailCancel       =   Appointment::where(['appointment_part_id' => $appointmentDetail->appointment_part_id , 'user_id' => $appointmentDetail->doctor->id])->first();
            $dayLeftToCancel    =   isset($detailCancel->setting['DAY_LEFT_TO_CANCEL']) ? $detailCancel->setting['DAY_LEFT_TO_CANCEL'] : 1;
            $appointmentDetail->cancelByUser =   isset($detailCancel->setting['CANCEL_BY_USER']) ? $detailCancel->setting['CANCEL_BY_USER'] : 0;
            $appointmentDetail->shortLink = $appointmentDetail->detail;

            $appointmentDetail->shortLink = $appointmentDetail->shortLink['SHORT_LINK'];

            if($appointmentDetail->cancelByUser){
                $todayTime          = mktime(0,0,0,date('m'),date('d'),date('Y'));
                $dateReserv         = jmktime(0,0,0,$appointmentDetail->month,$appointmentDetail->day,$appointmentDetail->year)  ;

                if($dateReserv <=  $todayTime){
                    $appointmentDetail->cancelByUser = 0;
                }elseif ($dateReserv <  ($todayTime + ($dayLeftToCancel * 86400))){
                    $appointmentDetail->cancelByUser = 0;
                }else{
                    $canCancelAppointmentUser = true;
                }

            }

            // cancel appointment
            $appointmentDetail->showCancelMessage = false;
            if ($requestCancelAppointment && $canCancelAppointmentUser){

                if(get_option(Appointment::SETTING_SMS_AFTER_CANCEL)){
                    $messgae = appointmentUserTextFilter(get_option(Appointment::SETTING_SMS_AFTER_CANCEL_MESSAGE), $appointmentDetail);
                    sendSmsNow($appointmentDetail->id, $messgae, "Modules\Appointment\Entities\AppointmentUser");
                }
                AppointmentUser::whereCodeAndId($code,$id)->update(['status' => 2]);
                $appointmentDetail->cancelByUser = 0;
                $appointmentDetail->status = 2;
                $appointmentDetail->showCancelMessage = true;
            }
            // cancel appointment




        } else {
            return view('appointment::profile/appointment/not_found');

        }


        return view('appointment::profile/appointment/show_result',compact('appointmentDetail'));
    }


}
