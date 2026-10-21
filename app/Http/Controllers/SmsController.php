<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Modules\Options\Models\Option;

class SmsController extends Controller
{
    private $costPerSmsFa , $costPerSmsEn , $ballanceSms ;


    public function __construct()
    {
        $this->costPerSmsFa = 35;
        $this->costPerSmsEn = 45;
        $this->ballanceSms = 'ballance_sms';
    }

    private function englishMessage( $string = '' ) {
        return ( bool ) ! preg_match( '/[\\x80-\\xff]+/' , $string );
    }

    private function calculateSingleSms($message)
    {
        if($this->englishMessage($message)) {
            $smsLenght      = 140;
            $LongSmsLengh   = 133;
            $costPerSms     = $this->costPerSmsEn;
        } else {
            $smsLenght      = 70;
            $LongSmsLengh   = 67;
            $costPerSms     = $this->costPerSmsFa;
        }

        $lengthMessage  =  mb_strlen($message);
        $moneyMessage = ($lengthMessage > $smsLenght) ? (ceil($lengthMessage /  $LongSmsLengh) * $costPerSms) : $costPerSms;
        return  $moneyMessage ;
    }

    public function calculateSMSAmount($message)
    {
        $amount = 0;
        if(is_array($message)){
            foreach ($message as $sms){
                $amount += $this->calculateSingleSms($sms);
            }
        } else {
            $amount = $this->calculateSingleSms($message);
        }
        return $amount;
    }

    public function checkBalance($smsAmount = 0){
         $balance = getSmsCredit();
        if($balance >= $smsAmount){
            return true;
        } else {
            return false;
        }
    }

    public function updateBallanceSms($smsAmount)
    {
        return Option::whereName('ballance_sms')->decrement('value' , $smsAmount) ?? false;
    }
    
    
    //
}
