<?php

function currentMeal($meal)
{
    switch($meal){
        case 1:
            $out = 'صبحانه';
            break;
        case 2:
            $out = 'میان وعده ۱';
            break;
        case 3:
            $out = 'نهار';
            break;
        case 4:
            $out = 'میان وعده ۲';
            break;
        case 5:
            $out = 'شام';
            break;
        case 6:
            $out = 'میان وعده ۳';
            break;
        case 0:
            $out = 'تمام روز';
            break;
    }

    return $out;
}

/*
    function creatAndInsertRejim($payment_for,$rejimType='easy',$rejimBody='1',$isBee=null,$chatId='',$sendAutoSms=true){
        global $db;
        $rs =$db->fetch_single_row('requestrejim','id',$payment_for);
        $moch = $rs->moch;
        $vazn = $rs->vazn;
        $sen = $rs->sen;
        $ghad = $rs->ghad;
        $UserId = $rs->UserId;
        $robot_id = $rs->robot_id;
       // $jensiat		= self::getProfileFiledN($chatId,'jensiat');



        $typeBody 		= self::typeBody($vazn,$ghad,$moch,$sen,$jensiat,$rejimBody,$rejimType); 	// لدست آوردن اطلاعات کلی

        if($isBee > 0){
            $BEE			= $isBee;		// بدست آوردن bee برای رژیم
        }
        else{
            $BEE			= round($typeBody['BEE']);		// بدست آوردن bee برای رژیم
        }
        $currectCalory 		= self::currectCalory($BEE);			// بدست آوردن کالری برای هر وعده
        $time = time();
        # $rejimTypeBmi		= getCurrectRejimType($rejimType);
        # $rejimTypeDatabase 	= getKeyRejim($rejimTypeBmi);
        $form_data = array(
            'BEE' => $BEE,
            'vazn' => $vazn,
            'UserId' => $UserId,
            'date' => $time,
            'requestrejim_id' => $payment_for,
            'rejimType' => $rejimType,
            'rejimBody' => $rejimBody
        );

        $db->insert('rejimed',$form_data);

        $DBrejimed      = $db->fetch_single_row_last('rejimed','id','DESC');
        $insert_id      = $DBrejimed->id;

        $code		= self::randomCode($insert_id);
        $data=array('rejim_code'=>$code);
        $db->update('rejimed',$data,'id',$insert_id);

        // پیدا کردن شماره جلسه رژیم
        $count  = $db->fetch_count('requestrejim',['id'],['chat_id'=>$chatId,'active'=>'yes']);
        $no_shift	= $count+1;
        // پیدا کردن شماره جلسه رژیم



        $dateEndRejim	= time() + ( 86400 * 14);
        $data=array('no_shift'=>$no_shift,'rejimedId'=>$insert_id,'rejimBody'=>$rejimBody,'active'=>'yes','active_payment'=>'yes','date_end'=>$dateEndRejim,'inUse'=>'1');
        $db->update('requestrejim',$data,'id',$payment_for);


        $db->custom_query("UPDATE `auto_sms` SET `status`='new_rejimed' WHERE  (`UserId`='$UserId' AND `status`='none' AND `type_send`='after_give_rejim')");

        // send auto message
        if($sendAutoSms){
            self::createAutomaticSms($UserId,$payment_for,$no_shift,null,$chatId,$robot_id);
        }
        // send auto message



        $data2=array('no_shift'=>$no_shift,'rejimedId'=>$insert_id,'rejimBody'=>$rejimBody);
        $db->update('requestrejim',$data2,'id',$payment_for);
        $db->custom_query("UPDATE `requestrejim` SET `inUse`='0' WHERE (`id`<>'$payment_for' AND `UserId`='$UserId') ");
        $Meal 		= array();

        $rejimId = $UserId;
        $Meal['breakfast'] 	= self::createRejim($rejimId,'sobh'	,$currectCalory['breakfast']	,1 ,$insert_id	,$rejimType	,$rejimBody);
        $Meal['snack1'] 	= self::createRejim($rejimId,'nash'	,$currectCalory['snack1']	,2 ,$insert_id	,$rejimType	,$rejimBody);
        $Meal['lunch'] 		= self::createRejim($rejimId,'nahar'	,$currectCalory['lunch']	,3 ,$insert_id	,$rejimType	,$rejimBody);
        $Meal['snack2'] 	= self::createRejim($rejimId,'nash2'	,$currectCalory['snack2']	,4 ,$insert_id	,$rejimType	,$rejimBody);
        $Meal['dinner'] 	= self::createRejim($rejimId,'sham'	,$currectCalory['dinner']	,5 ,$insert_id	,$rejimType	,$rejimBody);
        $Meal['snack3'] 	= self::createRejim($rejimId,'nash3'	,$currectCalory['snack3']	,6 ,$insert_id	,$rejimType	,$rejimBody);
        $out = array(
            'code' 		=> $code
        );

        return $out;
    }
*/
//====== محاسبه وزن ایده آل
function getCurrectWeight($vazn)
{
    $start = $end = $vazn;
    if($vazn <= 60){
        $start = $vazn - 8;
        $end = $vazn + 7;
    }elseif($vazn > 60 && $vazn <= 75){
        $start = $vazn - 5;
        $end = $vazn + 10;
    }else{
        $start = $vazn - 5;
        $end = $vazn + 15;
    }

    return (object)$out = array(
        'start' => $start,
        'end' => $end
    );

}

function dayToTargetWeight()
{
    $diffrentWeight = CurrentWeight()->overweightOrUnderweight;
    if($diffrentWeight > 0){
        $date_to_current_weight = $diffrentWeight * 7;
    }else{
        $date_to_current_weight = '-';
    }

    return $date_to_current_weight;
}

function dateToTargetWeight()
{
    $diffrentWeight = CurrentWeight()->diffrentWeight;
    if($diffrentWeight > 0){
        $time_to_current_weight = $diffrentWeight * 7;
        $date_to_current_weight = jdate("d F Y", (time() + (abs($time_to_current_weight * 86400))));
    }else{
        $date_to_current_weight = '-';
    }

    return $date_to_current_weight;
}

function CurrentWeight($user_id = null)
{
    if($user_id == null){
        $user = Auth::user();
    }else{
        $user = \App\User::find($user_id);
    }
    $isWeightDB = \Modules\Diet\Entities\Request\Request_rejim_program::select('weight')->where('user_id', $user->id)->where('active', 1)->orderBy('id', 'DESC')->limit(1)->first();
    if($isWeightDB){
        $isWeight = $isWeightDB->weight;
    }else{
        $isWeight = $user->weight; // 92
    }
    $startWeight = $user->weight; // 95
    $targetWeight = $user->target_weight; //78
    $typeWeight = 3; // normal weight
    $loseOrGainWeight = 0;
    if($isWeightDB && ($startWeight != $isWeight)){
        // need to lose weight
        if($startWeight > $targetWeight){
            $typeWeight = 1;
            $loseOrGainWeight = $startWeight - $isWeight;
        }
        // need to lose weight
        // need to gain weight
        elseif($startWeight < $targetWeight){
            $typeWeight = 2;
            $loseOrGainWeight = $isWeight - $startWeight;
        }
        // need to gain weight
    }else{
        if($startWeight > $targetWeight){
            $typeWeight = 1;
        }elseif($startWeight < $targetWeight){
            $typeWeight = 2;
        }
    }
    if($loseOrGainWeight > 0){
        $diffrent = $startWeight - $targetWeight;
        $darsadKol = 100 / $diffrent;
        $isVaznDarsad = $startWeight - $isWeight;
        $progressCompleteDiet = round($darsadKol * $isVaznDarsad);
    }elseif($isWeightDB){
        $progressCompleteDiet = 100;
    }else{
        $progressCompleteDiet = 0;
    }
    // Overweight or Underweight
    if($typeWeight == 1){
        $overweightOrUnderweight = $isWeight - $targetWeight;
    }elseif($typeWeight == 2){
        $overweightOrUnderweight = $targetWeight - $isWeight;
    }else{
        $overweightOrUnderweight = 0;
    }

    // Overweight or Underweight
    return (object)['isVazn' => $isWeight, 'loseOrGainWeight' => $loseOrGainWeight, 'typeWeight' => $typeWeight, 'progressCompleteDiet' => $progressCompleteDiet, 'overweightOrUnderweight' => $overweightOrUnderweight];
    // پیدا کردن وزن کنونی و وزن مناسب
}

//====== محاسبه وزن ایده آل
function typeBody($vazn = '', $ghad = '', $doremoch = '', $sen = '', $jensiat = '', $rejimBody = '1', $rejimLevel = 'none',$activityRate = '')
{
    if($jensiat != 1 && $jensiat != 2){
        $jensiat = ($jensiat == 'Male' ? 1 : 2);
    }
    $result = array();
    $vazn = $vazn;
    $ghad = $ghad;
    $doremoch = $doremoch;
    $sen = $sen;
    $jensiat = $jensiat;
    $JOSE = round($ghad / $doremoch); // andaze jose meghdare (r)
    $BMI = round(	(($vazn / ($ghad * $ghad)) * 10000) , 1); // BMI badan dar safhe 23
    $result['BMI'] = $BMI;
    if($BMI <= 18.5){
        $result['bodyType'] = 'کم وزنی';
    }elseif($BMI >= 18.5 && $BMI <= 24.9){
        $result['bodyType'] = 'طبیعی';
    }elseif($BMI >= 25 && $BMI <= 29.9){
        $result['bodyType'] = 'اضافه وزن';
    }elseif($BMI >= 30 && $BMI <= 34.9){
        $result['bodyType'] = 'چاقی درجه I';
    }elseif($BMI >= 35 && $BMI <= 39.9){
        $result['bodyType'] = 'چاقی در جه II';
    }elseif($BMI >= 40){
        $result['bodyType'] = 'چاقی درجه III';
    }
    // end of mohasebe namye tode badan
    /////******* END MHASEBAT **********/////////////
    /////******* START NAMAYESHI**********/////////////
    // start taeen andazs jose bar hasbe $JOSE
    switch($jensiat){
        case '1'; {
            if($JOSE > 10.4){
                $JOSEKOL = 'کوچک';
            }elseif($JOSE >= 9.6 && $JOSE <= 10.4){
                $JOSEKOL = 'متوسط';
            }elseif($JOSE < 9.6){
                $JOSEKOL = 'بزرگ';
            }
            break;
        }
        case '2'; {
            if($JOSE > 11){
                $JOSEKOL = 'کوچک';
            }elseif($JOSE >= 10.1 && $JOSE <= 11){
                $JOSEKOL = 'متوسط';
            }elseif($JOSE < 10.1){
                $JOSEKOL = 'بزرگ';
            }
            break;
        }

    }
    $result['jose'] = isset($JOSEKOL) ? $JOSEKOL : '';
    $JOSE; // adade size jose
    // end taeen andazs jose bar hasbe $JOSE
    // start mohasebe IBW badan va vazne ideall
    switch($jensiat){
        case '1'; {
            $IBW = 48 + ($ghad - 150);
            break;
        }
        case '2'; {
            $IBW = 45 + ($ghad - 150);
            break;
        }
    }
    // end mohasebe IBW badan va vazne ideall
    // start IBW base jose bozorg 10% va base jose kocka -10%
//        switch($JOSEKOL){
//            case 'بزرگ';{
//                $darsade_IBW = (10 * $IBW)/100;
//                //$gIBW = $IBW + $darsade_IBW;
//                break;
//            }
//            case 'کوچک';{
//                $darsade_IBW = (10 * $IBW)/100;
//                //$IBW = $IBW - $darsade_IBW;
//
//                break;
//            }
//        }
    if($vazn >= $IBW){
        $EZAFE_VAZN = $vazn - $IBW;
        $result['EZAFE_VAZN_type'] = 1;
        $result['EZAFE_VAZN_result'] = $EZAFE_VAZN;
    }else{
        $EZAFE_VAZN = '0';
        $result['EZAFE_VAZN_type'] = 2;
        $result['EZAFE_VAZN_result'] = $IBW-$vazn;
    }
    $result['EZAFE_VAZN'] = $EZAFE_VAZN;
    if($EZAFE_VAZN > 0){
        $CURRECT_VAZN = $vazn - $EZAFE_VAZN;
    }else{
        $CURRECT_VAZN = $IBW;
    }
    // ENd IBW base jose bozorg 10% va base jose kocka -10%
    $result['CURRECT_VAZN'] = abs($CURRECT_VAZN); // وزن ایده عال شما
    $result['Jensiat'] = $jensiat; // وزن ایده عال شما
    // start mohasebe darsade IBW
    $D_IBW = round(($vazn / $IBW) * 100); // darsade vazne ide all
    if($D_IBW >= 200){
        $D_IBW = 'چاقی شدید';
    }elseif($D_IBW > 130 && $D_IBW < 199){
        $D_IBW = 'چاقی';
    }elseif($D_IBW > 110 && $D_IBW < 129){
        $D_IBW = 'اضافه وزن';
    }elseif($D_IBW > 91 && $D_IBW < 109){
        $D_IBW = 'طبیعی';
    }elseif($D_IBW > 80 && $D_IBW < 90){
        $D_IBW = 'سوء تغذیه خفیف';
    }elseif($D_IBW > 70 && $D_IBW < 79){
        $D_IBW = 'سوء تغذیه متوسط';
    }elseif($D_IBW <= 69){
        $D_IBW = 'سوء تغذیه شدید';
    }
    $result['vaziat_badan'] = $D_IBW;
    // end mohasebe darsade IBW
    // start estefade az bmi
    /* agar BMI < 40 bod az vazne feli estefade mishavad va agar boaorgtar az 40 bood az vazne ideal*/
//	if($BMI >= 40){
//		$vazn_badan = $IBW;
//	}else{
//		$vazn_badan = $vazn;
//	}
    // END start estefade az bmi
    // mohasebe enerjo va kallery (BEE)
    $vazn_badan = $vazn;
    switch($jensiat){
        case '1'; {
            $BEE = round(((66.47 + (13.75 * $vazn_badan)) + (5 * $ghad)) - (6.76 * $sen)); // mohasebe enerjo va kallery
            break;
        }
        case '2'; {
            $BEE = round(655.1 + (9.56 * $vazn_badan) + (1.85 * $ghad) - (4.68 * $sen)); // mohasebe enerjo va kallery
            break;
        }
    }
    if($activityRate == ""){
        $BEE *= 1.65;
    }else{
        switch($activityRate){
            case "veryLow":
                $BEE *= 1.2;
                break;
            case "low":
                $BEE *= 1.45;
                break;
            case "medium":
                $BEE *= 1.65;
                break;
            case "large":
                $BEE *= 1.85;
                break;
            default:
                $BEE *= 2.2;
                break;
        }
    }
    //echo $BEE;
    // START zarayebe faaleiat
    /*
        switch ($faaliat){
            case 5;{
                $BEE *= 1.2;
            break;
            }
            case 2;{
                $BEE *=  1.45;
            break;
            }
            case 3;{
                $BEE *=1.65;
            break;
            }
            case 4;{
                $BEE *=1.85;
            break;
            }
            case 5;{
                $BEE *=2.2;
            break;
            }
        }
        */
    // در صورتی که درخواست رژیم کاهش وزن داشته باشد
    if($rejimBody == 1){
        if($rejimLevel == 'none'){
            if($BEE < 1500){
                $BEE = $BEE - 500;
            }else{
                $BEE = $BEE - 800;
            }
            if($BEE <= 1000){
                $BEE = 1000;
            }
        }else{
            switch($rejimLevel){
                case '3':
                    $BEE = $BEE - 1000;
                    break;
                case '2':
                    $BEE = $BEE - 800;
                    break;
                case '1':
                    $BEE = $BEE - 500;
                    break;
                default:
                    $BEE = $BEE - 800;
                    break;
            }

        }
        if($BEE <= 800){
            $BEE = 800;
        }
    }
    // در صورتی که درخواست رژیم کاهش وزن داشته باشد
    // در صورتی که درخواست رژیم افزایش وزن داشته باشد
    elseif($rejimBody == 2){
        if($BEE < 1500){
            $BEE = $BEE + 500;
        }else{
            $BEE = $BEE + 1000;
        }
    }else{
        $BEE -= 300;
        if($BEE <= 800){
            $BEE = 800;
        }
    }
    // در صورتی که درخواست رژیم افزایش وزن داشته باشد
    $result['type'] = 'success';
    $result['BEE'] = $BEE;

    return $result;
}

function roundNum($n, $x = 50)
{
//    $num = (ceil($n) % $x === 0) ? ceil($n) : round(($n - $x / 2) / $x) * $x;
//
//    return ($num == 0) ? 50 : $num;
    return round($n / $x) * $x;
}

function currectCalory($BEE, $rejimType = 'normal')
{
    if($rejimType == 'normal'){
        $Percent['breakfast'] = 0.2;
        $Percent['snack1'] = 0.1;
        $Percent['lunch'] = 0.3;
        $Percent['snack2'] = 0.1;
        $Percent['dinner'] = 0.2;
        $Percent['snack3'] = 0.1;
    }
    $return[1] = roundNum($BEE * $Percent['breakfast']); // breakfast
    $return[2] = roundNum($BEE * $Percent['snack1']); //snack1
    $return[3] = roundNum($BEE * $Percent['lunch']); //lunch
    $return[4] = roundNum($BEE * $Percent['snack2']); //snack2
    $return[5] = roundNum($BEE * $Percent['dinner']); //dinner
    $return[6] = roundNum($BEE * $Percent['snack3']); //snack3

    return $return;
}

function calculate_age($date_of_birth)
{
    $unix_today = time(); //Current unix timestamp
    $unix_diff = $unix_today - $date_of_birth;
    $current_age = floor($unix_diff / (60 * 60 * 24 * 365));

    return (Integer)$current_age;
}

function isAge($user_id = null)
{
    if($user_id == null){
        $user = \Auth::user();
    }else{
        $user = \App\User::find($user_id);
    }
    $birthday_year = $user->birthday_year;
    $birthday_month = $user->birthday_month;
    $birthday_day = $user->birthday_day;
    $ktate = jmktime(12, 1, 1, $birthday_month, $birthday_day, $birthday_year);
    $age = calculate_age($ktate);
    return $age;
}

function makeRejim($catRejim)
{
    $totalCalories = 0;
    $requestRejimProgram = \Modules\Diet\Entities\Request\Request_rejim_program::find($catRejim);
    if($requestRejimProgram){
        $typeBody = typeBody(
            $requestRejimProgram->weight,
            $requestRejimProgram->height,
            $requestRejimProgram->wrist,
            isAge($requestRejimProgram->user_id),
            \App\User::find($requestRejimProgram->user_id)->gender,
            $requestRejimProgram->rejim_body,
            $requestRejimProgram->rejim_level,
            \App\User::find($requestRejimProgram->user_id)->activity_rate

        );    // لدست آوردن اطلاعات کلی
        $BEE = round($typeBody['BEE']);        // بدست آوردن bee برای رژیم
        $totalCalories = roundNum($BEE);
        $currectCalory = currectCalory($BEE);            // بدست آوردن کالری برای هر وعده
//		foreach($currectCalory as $calTemp) $totalCalories = $totalCalories + $calTemp;
        // get number seasion af get rejim
        $no_shift = \Modules\Diet\Entities\Request\Request_rejim_program::where('user_id', $requestRejimProgram->user_id)->whereActive(1)->get()->count();
        // get number seasion af get rejim
        // change in use rejim
        \Modules\Diet\Entities\Request\Request_rejim_program::where('id', '<>', $requestRejimProgram->id)->whereUserId($requestRejimProgram->user_id)->update(['inUse' => '0']);
        // change in use rejim
        $dateEndRejim = (new Carbon\Carbon('now'))->addDays(15)->toDateTimeString();
        $requestRejimProgram->calorie = $BEE;
        $requestRejimProgram->date_end = $dateEndRejim;
        $requestRejimProgram->no_shift = $no_shift;
        $requestRejimProgram->inUse = 1;
        $requestRejimProgram->save();
        if($requestRejimProgram->rejim_body == 1){

        }
        $rejimType = isset(\Modules\Diet\Entities\Meal\Meal::$MEALTYPES[$requestRejimProgram->rejim_body]) ? \Modules\Diet\Entities\Meal\Meal::$MEALTYPES[$requestRejimProgram->rejim_body] : \Modules\Diet\Entities\Meal\Meal::$MEALTYPES[1];
        $user_id = $requestRejimProgram->user_id;
        // create and insert rejim
        $mealRejimed = 1;
        while($mealRejimed < 7){
            //Calc total calorie for rejim one page
            if($requestRejimProgram->request_type == 2){
                $calorie = $totalCalories;
            }else{
                $calorie = $currectCalory[$mealRejimed];
            }
            //End
            $oldId = \Modules\Diet\Entities\Request\Request_rejim_filed::where(function($q) use ($user_id, $mealRejimed, $calorie) {
                $q->where('user_id', $user_id);
                $q->where('meal', $mealRejimed);
                $q->where('calorie', $calorie);
            })->pluck('meal_id');
            $Rejim = createTableRejim($catRejim, $requestRejimProgram, $mealRejimed, $calorie, $rejimType, $user_id, null, $oldId->toArray());
            if((collect($Rejim)->search('', true))){
                $emptyDaies = \Illuminate\Support\Arr::where($Rejim, function($value, $key) {
                    return empty($value);
                });
                $limit = sizeof($emptyDaies);
                $Rejim = createTableRejim($catRejim, $requestRejimProgram, $mealRejimed, $calorie, $rejimType, $user_id, $Rejim, \Illuminate\Support\Arr::flatten($Rejim), $limit);
            }
            insertMealRejim($catRejim, $requestRejimProgram->rejim_type, $Rejim, $mealRejimed, $calorie, $user_id);
            if($requestRejimProgram->request_type == 2) break; // Break loop for rejim one page
            $mealRejimed++;
        }
        // create and insert rejim

//        \App\Optimization\SmsAuto::generateSmsInQueue($requestRejimProgram);


    }
}

function createTableRejim($catRejim, $requestRejimProgram = null, $meal_type = 1, $calorie = 50, $rejim_type = 'rejim_type_loss', $user_id, $days = null, $listIdNotIn = array(), $limit = 7)
{
    $category = $requestRejimProgram->rejim_type;
    $isSeason = jdate('b', '', '', '', 'en'); // season
    // all meal in category
    $AllMealsCountSelected =
        \Modules\Diet\Entities\Request\Request_rejim_filed::where('user_id', $user_id)->where('rejim_type', $catRejim)->where('repeat', 0)->whereNotIn('id', function($query) {
            $query->from('request_rejim_filed')->select('meal_id');
        })->get()->count();
    // all meal in category
    // all meal in category
    $AllMeals =
        \Modules\Diet\Entities\Meal\Meal_category::find($category)->meal()->select('id')
            ->whereHas('metas', function($query) use ($isSeason, $rejim_type, $requestRejimProgram, $calorie) {
                $query->whereMetaKey('season')->whereMetaValue($isSeason);
            })->whereHas('metas', function($query) use ($isSeason, $rejim_type, $requestRejimProgram, $calorie) {
                $query->whereMetaKey($rejim_type)->whereMetaValue(1);
            })->whereHas('metas', function($query) use ($isSeason, $rejim_type, $requestRejimProgram, $calorie) {
                if($requestRejimProgram->request_type == 2){
                    $query->whereMetaKey('total_calorie')->whereMetaValue($calorie);
                }
            })
            ->whereMeal($meal_type);
    if($requestRejimProgram->request_type == 1){
        $AllMeals->whereCalorie($calorie);
    }
    $AllMeals = $AllMeals->count();
    // all meal in category
    if($AllMeals == $AllMealsCountSelected){
        \Modules\Diet\Entities\Request\Request_rejim_filed::where('user_id', $user_id)->where('rejim_type', $catRejim)->update(['repeat' => 1]);
    }
    $Meals =
        \Modules\Diet\Entities\Meal\Meal_category::find($category)->meal()
            ->whereHas('metas', function($query) use ($isSeason, $rejim_type, $requestRejimProgram, $calorie) {
                $query->whereMetaKey('season')->whereMetaValue($isSeason);
            })->whereHas('metas', function($query) use ($isSeason, $rejim_type, $requestRejimProgram, $calorie) {
                $query->whereMetaKey($rejim_type)->whereMetaValue(1);
            })->whereHas('metas', function($query) use ($isSeason, $rejim_type, $requestRejimProgram, $calorie) {
                if($requestRejimProgram->request_type == 2){
                    $query->whereMetaKey('total_calorie')->whereMetaValue($calorie);
                }
            })
            ->whereMeal($meal_type)
            ->whereNotIn('id', $listIdNotIn)
            ->whereNotIn('id', function($query) use ($catRejim) {
                $query->from('request_rejim_filed')->select('meal_id')->where('rejim_type', $catRejim)->where('repeat', 0);
            })
            ->inRandomOrder();
    if($requestRejimProgram->request_type == 1){
        $Meals->whereCalorie($calorie);
    }
    $Meals->limit($limit)->get()
        ->sortBy(function($meal) {
            return $meal->metas->where('meta_key', 'days')->count();
        });
    if($Meals->count() < $limit && $AllMealsCountSelected > 0){
        \Modules\Diet\Entities\Request\Request_rejim_filed::where('user_id', $user_id)->where('rejim_type', $catRejim)->update(['repeat' => 1]);
    }
    //sort by special days
    if($days == null){
        $ProgramDay = [
            1 => '',
            2 => '',
            3 => '',
            4 => '',
            5 => '',
            6 => '',
            7 => '',
        ];
    }else{
        $ProgramDay = $days;
    }
    if($Meals->count() == 0){
        if($calorie <= 50){
            return $ProgramDay;
        }else{
            $calorie = $calorie - 50;

            return $ProgramDay = createTableRejim($catRejim, $requestRejimProgram, $meal_type, $calorie, $rejim_type, $user_id, $ProgramDay, $listIdNotIn, $limit);
        }
    }
    $daySelected = [];
    if($Meals->count() > 0){
        // only for special days
        $i = 0;
        foreach($Meals->get() as $meal){
            $listIdNotIn[] = $meal->id; // list selected meal id
            $Metas = $meal->metas;
            $MetaDays = $Metas->where('meta_key', 'days');
            foreach($MetaDays as $key){
                if($key->meta_value != 'all'){
                    if(!empty($ProgramDay[$key->meta_value])){
                        continue;
                    }
                    $daySelected[] = $key->meta_value;
                    $ProgramDay[$key->meta_value] = $meal->id;
                    break;
                }
            }
        }
        // only for special days
        $emptyDaies = \Illuminate\Support\Arr::where($ProgramDay, function($value, $key) {
            return empty($value);
        });
        $uniqueIds = array_keys($emptyDaies);
        // dreate another day [normal days]
        if($uniqueIds > 0){
            foreach($Meals->get() as $meal){
                $listIdNotIn[] = $meal->id; // list selected meal id
                $Metas = $meal->metas;
                $MetaDays = $Metas->where('meta_key', 'days');
                foreach($MetaDays as $key){
                    if($key->meta_value == 'all' && !empty($uniqueIds[$i])){
                        $isKey = $uniqueIds[$i];
                        $ProgramDay[$isKey] = $meal->id;
                        $daySelected[] = "$isKey";
                        $i++;
                    }
                }
            }
        }
    }
    if((collect($ProgramDay)->search('', true))){
        $limit = 7 - count($daySelected);
        $ProgramDay = createTableRejim($catRejim, $requestRejimProgram, $meal_type, $calorie, $rejim_type, $user_id, $ProgramDay, $listIdNotIn, $limit);
    }

    return $ProgramDay;
}

function insertMealRejim($catRejim, $rejim_type, $day_meals, $meal, $calorie, $user_id)
{
    $rejimDay = \Illuminate\Support\Arr::where($day_meals, function($value, $key) {
        return !empty($value);
    });
    if(count($rejimDay) == 0){
        return Null;
    }
    foreach($rejimDay as $key => $value){
        $Request_rejim_filed = new \Modules\Diet\Entities\Request\Request_rejim_filed();
        $Request_rejim_filed->request_rejim_id = $catRejim;
        $Request_rejim_filed->rejim_type = $rejim_type;
        $Request_rejim_filed->meal = $meal;
        $Request_rejim_filed->meal_id = $value;
        $Request_rejim_filed->user_id = $user_id;
        $Request_rejim_filed->day = $key;
        if(\Modules\Diet\Entities\Meal\Meal::find($value)->metas()->where('meta_key','total_calorie')->exists()){
            $Request_rejim_filed->calorie = \Modules\Diet\Entities\Meal\Meal::find($value)->metas()->where('meta_key','total_calorie')->first()->meta_value;
        }else{
            $Request_rejim_filed->calorie = $calorie;
        }
        $Request_rejim_filed->save();
    }

    return $day_meals;
}
