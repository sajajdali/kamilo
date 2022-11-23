<?php

namespace Modules\Appointment\Entities;

use App\User;
use Illuminate\Database\Eloquent\Model;

/**
 * Modules\Appointment\Entities\Appointment
 *
 * @property int $id
 * @property int|null $user_id
 * @property int|null $appointment_part_id
 * @property array $cost
 * @property array $content
 * @property array $time_for_visit
 * @property string $interference
 * @property array $setting
 * @property-read \Modules\Appointment\Entities\AppointmentPart|null $appointmentPart
 * @property-read \App\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Appointment\Entities\Appointment newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Appointment\Entities\Appointment newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Appointment\Entities\Appointment query()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Appointment\Entities\Appointment whereAppointmentPartId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Appointment\Entities\Appointment whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Appointment\Entities\Appointment whereCost($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Appointment\Entities\Appointment whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Appointment\Entities\Appointment whereInterference($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Appointment\Entities\Appointment whereSetting($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Appointment\Entities\Appointment whereTimeForVisit($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Appointment\Entities\Appointment whereUserId($value)
 * @mixin \Eloquent
 */
class Appointment extends Model
{

    CONST SETTING_USER_STATUS = "APPOINTMENT_USER_SYSTEM_STATUS";
    CONST SETTING_AGENT_STATUS = "APPOINTMENT_AGENT_SYSTEM_STATUS";
    CONST MESSAGE_IF_TURN_APPOINTMENT_IS_INACTIVE = "MESSAGE_IF_TURN_APPOINTMENT_IS_INACTIVE";
    CONST SETTING_SMS_AFTER_CANCEL = "SETTING_SMS_AFTER_CANCEL";
    CONST SETTING_SMS_AFTER_CHANGE_TIME = "SMS_AFTER_CHANGE_TIME";
    CONST SETTING_SMS_AFTER_CHANGE_TIME_MESSAGE = "SMS_AFTER_CHANGE_TIME_MESSAGE";
    CONST SETTING_SMS_AFTER_CANCEL_MESSAGE = "SETTING_SMS_AFTER_CANCEL_MESSAGE";
    CONST APPOINTMENT_SHOW_CLINIC_ADDRESS = "APPOINTMENT_SHOW_CLINIC_ADDRESS"; // show  clinic address after get appointment
    CONST SHARE_DETAIL = "SHARE_DETAIL"; // show  clinic address after get appointment
    CONST VISIT_TYPE_IN_PERSON = 'IN_PERSON';
    CONST VISIT_TYPE_IN_ONLINE = 'ONLINE';
    CONST VISIT_TYPE_IN_TEL = 'TEL';
    public static $VISIT_TYPE = [
        self::VISIT_TYPE_IN_PERSON => 'حضوری',
        self::VISIT_TYPE_IN_ONLINE => 'آنلاین',
        self::VISIT_TYPE_IN_TEL => 'نلفنی',
    ];

    CONST AGREEMENT_TYPE_ORG = "FREE";

    public static $AGREEMENT_TYPE = [
        self::AGREEMENT_TYPE_ORG => 'هزینه‌ها',
    ];

    CONST JSON_COST = "COST";
    CONST JSON_PERCENT = "PERCENT";
    CONST JSON_STATUS = "STATUS";
    CONST JSON_CANCEL_BY_USER = "CANCEL_BY_USER";
    CONST JSON_DAY_LEFT_TO_CANCEL = "DAY_LEFT_TO_CANCEL";
    CONST JSON_ITEMS = "ITEMS";
    CONST JSON_SELECT_TYPE = "SELECT_TYPE";
    CONST JSON_PUBLIC_TIME = "PUBLIC_TIME";
    CONST JSON_TITLE = "TITLE";
    CONST JSON_TIME = "TIME";

    CONST JSON_UNPAID_APPOINTMENT_STATUS = "UNPAID_APPOINTMENT_STATUS";
    CONST JSON_DAY_BEFORE = "DAY_BEFORE";
    CONST JSON_DAY_AFTER = "DAY_AFTER";
    CONST JSON_ONLINE_PAY = "ONLINE_PAY";
    CONST JSON_PAY_TYPE = "PAY_TYPE";
    CONST JSON_PAY_TYPE_PERCENT = "ONLINE_PAY_TYPE_PERCENT";
    CONST JSON_AM = "AM";
    CONST JSON_PM = "PM";
    CONST JSON_FROM_TIME = "FROM";
    CONST JSON_TO_TIME = "TO";
    CONST JSON_TYPE = "TYPE";
    CONST JSON_USER_MAX = "USER_MAX";
    CONST JSON_USER_MAX_TYPE = "USER_MAX_TYPE";
    CONST JSON_CLERK_MAX = "CLERK_MAX";
    CONST JSON_CLERK_MAX_TYPE = "CLERK_MAX_TYPE";
    public static $ACQUAINTED = [
        1 => 'دایورتی',
        2 => 'سایت',
        3 => 'آنلاین (اینستا و تلگرام)',
        4 => 'تلفنی',
        5 => 'CRM',
        6 => 'روبیکا',
    ];
    public static $JSON_KEYWORD = [
        self::JSON_STATUS => 'متغیر عمومی وضعیت',
        self::JSON_ITEMS => 'متغیر عمومی ایتم‌ها',
        self::JSON_TITLE => 'عنوان‌ها',
        self::JSON_TIME => 'زمان‌ها',
        'COST' => [
            self::JSON_COST => "هزینه",
            self::JSON_PERCENT => "درصد پزشک"
        ],
        'TIME_FOR_VISIT' => [
            self::JSON_SELECT_TYPE => "نوع انتخاب بخش زمان‌بندی",
            self::JSON_PUBLIC_TIME => "زمان ویزیت اصلی",
        ],
        'SETTING' => [
            self::JSON_UNPAID_APPOINTMENT_STATUS => 'وضعیت نوبت پرداخت نشده',
            self::JSON_DAY_AFTER => "امکان نوبت‌گیری تا چند روز بعد",
            self::JSON_DAY_BEFORE => "امکان نوبت گیری تا چند روز قبل",
            self::JSON_ONLINE_PAY => "تنظیمات پرداخت آنلاین",
            self::JSON_PAY_TYPE => "نوع پرداخت آنلاین",
            self::JSON_PAY_TYPE_PERCENT => "درصد بیعانه",
            self::JSON_CANCEL_BY_USER => "امکان کنسل کردن توسط کاربر",
            self::JSON_DAY_LEFT_TO_CANCEL => "چند روز مانده به نوبت بیمار بتواند نوبتش را کنسل کند",
        ],
        'CONTENT' => [
            self::JSON_AM => "تایم صبح",
            self::JSON_PM => "تایم بعدظهر",
            self::JSON_FROM_TIME => "از ساعت",
            self::JSON_TO_TIME => "تا ساعت",
            self::JSON_TYPE => "نوع نوبت",
            self::JSON_USER_MAX => "حداکثر نوبت قابل ثبت کاربر",
            self::JSON_CLERK_MAX => "حداکثر نوبت قابل ثبت منشی",
        ]
    ];

    const JSON_GET_AM_FROM = self::JSON_TIME . "." . self::JSON_AM . "." . self::JSON_FROM_TIME;
    const JSON_GET_AM_TO = self::JSON_TIME . "." . self::JSON_AM . "." . self::JSON_TO_TIME;
    const JSON_GET_PM_FROM = self::JSON_TIME . "." . self::JSON_PM . "." . self::JSON_FROM_TIME;
    const JSON_GET_PM_TO = self::JSON_TIME . "." . self::JSON_PM . "." . self::JSON_TO_TIME;


    public $timestamps = false;
    protected $table = 'appointments';
    protected $guarded = ['id'];
    protected $casts = [
        'cost' => 'json',
        'content' => 'json',
        'time_for_visit' => 'json',
        'interference' => 'json',
        'setting' => 'json',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function appointmentPart()
    {
        return $this->belongsTo(AppointmentPart::class);
    }

    public static function timeToMinutes($time = array())
    {
        $time_return = 0;
        if (sizeof($time) == 0) {
            return 0;
        } else {
            $am = $time['am'];
            $pm = $time['pm'];
            $hour_am = 0;
            $min_am = 0;
            $hour_pm = 0;
            $min_pm = 0;
            if (!empty($am['from']) && !empty($am['to'])) {
                $am_from_array = explode(':', $am['from']);
                $am_to_array = explode(':', $am['to']);
                $hour_am = $am_to_array[0] - $am_from_array[0];
                $min_am = $am_to_array[1] - $am_from_array[1];
            }
            if (!empty($pm['from']) && !empty($pm['to'])) {

                $pm_from_array = explode(':', $pm['from']);
                $pm_to_array = explode(':', $pm['to']);
                $hour_pm = $pm_to_array[0] - $pm_from_array[0];
                $min_pm = $pm_to_array[1] - $pm_from_array[1];
            }
            $time_return = $min_pm + $min_am + (($hour_pm + $hour_am) * 60);
            return $time_return;
        }
    }


    public static function timeToString(int $time): String
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
}
