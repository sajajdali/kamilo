<?php
/**
 * add queue for sms
 *
 * @param $to
 * @param $message
 * @param $date
 * @param null $model
 */
function sendSms($to,$message,$date,$model = null){
    \App\Queue::addQueue($to,$message,$model,\App\Queue::TYPE_SMS,$date);
}

/**
 * Add sms to queue
 *
 * @param $to
 * @param $message
 * @param null $model
 */
function sendSmsNow($to,$message,$model = ''){
    return \App\Queue::addQueue($to,$message,$model,\App\Queue::TYPE_SMS,\Carbon\Carbon::now(),\App\Queue::PRIORITY_URGENT,true);
}

function getInfoGhasedak(){
    $api = new \Ghasedak\GhasedakApi(env('API_KEY_SMS'));
    return $api->AccountInfo()->items;
}

function smsNow($mobile , $template , $param1, $param2 = null, $param3 = null, $param4 = null){
    try
    {

        $receptor = $mobile;
        $api = new \Ghasedak\GhasedakApi(env('API_KEY_SMS'));
        $api->Verify($receptor,1,$template , $param1 , $param2 , $param3, $param4);
    }
    catch(\Ghasedak\Exceptions\ApiException $e){
        echo $e->errorMessage();
    }
    catch(\Ghasedak\Exceptions\HttpException $e){
        echo $e->errorMessage();
    }
}

function is_assoc($arr)
{
    return array_keys($arr) !== range(0, count($arr) - 1);
}
function sendAfeSMS(array $to,array $message){

    $listMessage = $listNumber = $result = [];
    $i = $b = 0;
    foreach ($message as  $sms){
        $listMessage[] = $sms;
        $listNumber[] = $to[$b];
        $b++;
        $i++;
        if ($i > 88){
            $result[] = sendAfeSMSLast($listNumber,$listMessage);
            unset($listNumber, $listMessage);
            $i = 0;
        }
    }
    if(isset($listNumber) && count($listNumber) > 0){
        $result[] = sendAfeSMSLast($listNumber,$listMessage);
    }
    return ($result);
}
function sendAfeSMSLast(array $to,array $message){
    $sms = new \App\Http\Controllers\SmsController();
    $smsAmount = $sms->calculateSMSAmount($message);
    if($sms->checkBalance($smsAmount)){
        $sms->updateBallanceSms($smsAmount);
        $url = 'http://www.afe.ir/WebService/V8/BoxService.asmx?wsdl';
        $method = 'SendMessagePeerToPeer';
        $NumberSendingSms = array();
        $type = array();
        for ($i = 0; $i < sizeof($to); $i++) {
            $NumberSendingSms[] = "30006832";
            $type[] = "1";
        }
        $param = array('Username' => "mtwiran", 'Password' => "5465168", 'Number' => $NumberSendingSms, 'Mobile' => $to, 'Message' => $message, 'Type' => $type);

       try{
            ini_set("soap.wsdl_cache_enabled", "0");
            $client = new SoapClient($url);
            $result = $client->__SoapCall($method, array($param));
            $merge = $method . 'Result';
            if ($result->$merge->string != '')
                return ['result' => $result->$merge->string , 'numbers' => $to];
            else
                return ['result'=> $result->$merge , 'numbers' => $to];
        }catch (SoapFault $e){

        }
    }else{
        return ['result'=> 'charge end' , 'numbers' => $to];

    }
}

/**
 * Text filter for appointment
 *
 * @param string $message
 * @param \Modules\Appointment\Entities\AppointmentUser $appointmentUser
 * @param \App\User $user
 * @return string
 */
function appointmentUserTextFilter(string $message,\Modules\Appointment\Entities\AppointmentUser $appointmentUser) : string {
    $filter = [
        "{نام}",
        "{نام-خانوادگی}",
        "{جنسیت}",
        "{پزشک}",
        "{تاریخ}",
        "{ساعت}",
        "{بخش}",
        "{اپراتور}",
        "{جزئیات-نوبت}"
    ];
    $data = [
        $appointmentUser->user->first_name,
        $appointmentUser->user->last_name,
        $appointmentUser->user->gender == '1' ? "آقا" : "خانم",
        "دکتر ".$appointmentUser->doctor->full_name,
        jdate('l j F',jmktime(0,0,0,$appointmentUser->month,$appointmentUser->day,$appointmentUser->year)),
       substr($appointmentUser->time_from, 0, -3),
        optional($appointmentUser->appointmentPart)->title,
        optional($appointmentUser->agent)->full_name,
        (isset($appointmentUser->detail['SHORT_LINK']) ? $appointmentUser->detail['SHORT_LINK'] : '')
    ];
    $message = str_replace($filter,$data,$message);
    return $message;
}

function tpl_assets($path = '' )
{
    return \Module::asset( $path );
}

function getSmsCredit()
{
    $url = 'http://www.afe.ir/WebService/webservice.asmx?wsdl';
    $method = 'GetRemainingCredit';
    $merge = $method . 'Result';
    $param = ['Username' => "mtwiran", 'Password' => "5465168"];
    try {
        ini_set("soap.wsdl_cache_enabled", "0");
        $client = new SoapClient($url);
        $result = $client->__SoapCall($method, array($param));
        return $result->$merge;
    } catch (SoapFault $e) {
        return $e->getMessage();
    }
}

function checkAccessDeleteOrUpdate(){
    if(auth()->user()->getRoleNames()[0] == 'CALL_CENTER') {
        return true;
    }
    if(auth()->user()->getRoleNames()[0] == 'SUPER_ADMIN') {
        return true;
    }
    return false;
}
