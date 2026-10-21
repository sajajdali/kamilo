<?php


namespace Modules\Appointment\Observers;


use App\Queue;
use Carbon\Carbon;
use Modules\Appointment\Entities\AppointmentSms;
use Modules\Appointment\Entities\AppointmentUser;

class AppointmentUserObserver
{
    /**
     * Handle create new appointment
     *
     * @param AppointmentUser $appointmentUser
     */

    public function created(AppointmentUser $appointmentUser)
    {


        if($appointmentUser->status == AppointmentUser::STATUS_OK){
            $smsList = AppointmentSms::active()->whereAppointmentPartIdAndUserId($appointmentUser->appointment_part_id,$appointmentUser->doctor_id);
            $timeStampAppointment = jmktime(0,0,0,$appointmentUser->month,$appointmentUser->day,$appointmentUser->year);
            if($smsList->exists()){
                foreach ($smsList->get() as $sms){
                    $date = Carbon::createFromTimestamp($timeStampAppointment)->subDay($sms->day_before);
                    $message = appointmentUserTextFilter($sms->message,$appointmentUser);
                    sendSms($appointmentUser->id,$message,$date,"Modules\Appointment\Entities\AppointmentUser");
                }
            }
        }
    }

    /**
     * Handle delete appointment
     *
     * @param AppointmentUser $appointmentUser
     */
    public function deleted(AppointmentUser $appointmentUser)
    {
        $allQueueSms = Queue::where([['model',"Modules\Appointment\Entities\AppointmentUser"],['to',$appointmentUser->id],['status',Queue::STATUS_SEND_PENDING]]);
        if($allQueueSms->exists()){
            foreach ($allQueueSms->get() as $item){
                $item->status = Queue::STATUS_SEND_CANCELED;
                $item->save();
            }
        }
    }

    /**
     * Handle update method
     *
     * @param AppointmentUser $appointmentUser
     */


    public function updating (AppointmentUser $appointmentUser)
    {
        if($appointmentUser->status == AppointmentUser::STATUS_CANCELED){
            $allQueueSms = Queue::where([['model',"Modules\Appointment\Entities\AppointmentUser"],['to',$appointmentUser->id],['status',Queue::STATUS_SEND_PENDING]]);
            if($allQueueSms->exists()){
                foreach ($allQueueSms->get() as $item){
                    $item->status = Queue::STATUS_SEND_CANCELED;
                    $item->save();
                }
            }
        } else {
            $partId     = $appointmentUser->getOriginal('appointment_part_id');
            $doctorId   = $appointmentUser->getOriginal('doctor_id');
            $day        = $appointmentUser->getOriginal('day');
            $month      = $appointmentUser->getOriginal('month');
            $year       = $appointmentUser->getOriginal('year');
            $time_from  = $appointmentUser->getOriginal('time_from');
            $time_to    = $appointmentUser->getOriginal('time_to');

            if (
                $partId     != $appointmentUser->appointment_part_id ||
                $doctorId   != $appointmentUser->doctor_id ||
                $day        != $appointmentUser->day ||
                $month      != $appointmentUser->month ||
                $year       != $appointmentUser->year ||
                $time_from  != $appointmentUser->time_from ||
                $time_to    != $appointmentUser->time_to)
            {

                // remove old sms
                $allQueueSms = Queue::where([['model',"Modules\Appointment\Entities\AppointmentUser"],['to',$appointmentUser->id],['status',Queue::STATUS_SEND_PENDING]]);
                if($allQueueSms->exists()){
                    foreach ($allQueueSms->get() as $item){
                        $item->status = Queue::STATUS_SEND_CANCELED;
                        $item->delete();
                        $item->save();
                    }

                }
                // remove old sms

                $allQueueSms = Queue::where([['model',"Modules\Appointment\Entities\AppointmentUser"],['to',$appointmentUser->id]]);
                if(!$allQueueSms->exists()){
                    $smsList = AppointmentSms::active()->whereAppointmentPartIdAndUserId($appointmentUser->appointment_part_id,$appointmentUser->doctor_id);
                    $timeStampAppointment = jmktime(0,0,0,$appointmentUser->month,$appointmentUser->day,$appointmentUser->year);
                    if($smsList->exists()){
                        foreach ($smsList->get() as $sms){
                            $date = Carbon::createFromTimestamp($timeStampAppointment)->subDay($sms->day_before);
                            $message = appointmentUserTextFilter($sms->message,$appointmentUser);
                            sendSms($appointmentUser->id,$message,$date,"Modules\Appointment\Entities\AppointmentUser");
                        }
                    }
                }
            }
        }
    }

}