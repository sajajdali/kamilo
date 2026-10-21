<?php

namespace Modules\Appointment\Entities;

use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Finance\Entities\Transaction;

/**
 * Modules\Appointment\Entities\AppointmentUser
 *
 * @property int $id
 * @property int|null $user_id
 * @property int $transaction_id
 * @property int|null $appointment_part_id
 * @property int|null $doctor_id
 * @property string $code
 * @property float $cost
 * @property float $percent
 * @property string $type
 * @property int $day
 * @property int $month
 * @property int $year
 * @property string $time_from
 * @property string $time_to
 * @property string $ip
 * @property int $status
 * @property int $visit
 * @property int $kind
 * @property int $is_confirmed
 * @property int|null $confirmed_by
 * @property \Illuminate\Support\Carbon|null $confirmed_at
 * @property string|null $confirmed_time_from
 * @property string|null $confirmed_service_name
 * @property string|null $confirmed_comment
 * @property string $r_part
 * @property string $detail
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Modules\Appointment\Entities\AppointmentPart|null $appointmentPart
 * @property-read \App\User|null $doctor
 * @property-read \App\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Appointment\Entities\AppointmentUser newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Appointment\Entities\AppointmentUser newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Appointment\Entities\AppointmentUser query()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Appointment\Entities\AppointmentUser whereAppointmentPartId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Appointment\Entities\AppointmentUser whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Appointment\Entities\AppointmentUser whereCost($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Appointment\Entities\AppointmentUser whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Appointment\Entities\AppointmentUser whereDay($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Appointment\Entities\AppointmentUser whereDoctorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Appointment\Entities\AppointmentUser whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Appointment\Entities\AppointmentUser whereIp($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Appointment\Entities\AppointmentUser whereMonth($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Appointment\Entities\AppointmentUser wherePercent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Appointment\Entities\AppointmentUser whereRPart($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Appointment\Entities\AppointmentUser whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Appointment\Entities\AppointmentUser whereTimeFrom($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Appointment\Entities\AppointmentUser whereTimeTo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Appointment\Entities\AppointmentUser whereTransactionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Appointment\Entities\AppointmentUser whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Appointment\Entities\AppointmentUser whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Appointment\Entities\AppointmentUser whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Appointment\Entities\AppointmentUser whereVisit($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Appointment\Entities\AppointmentUser whereYear($value)
 * @mixin \Eloquent
 * @property int|null $agent_id
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Query\Builder|\Modules\Appointment\Entities\AppointmentUser onlyTrashed()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Appointment\Entities\AppointmentUser whereAgentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Appointment\Entities\AppointmentUser whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Appointment\Entities\AppointmentUser whereKind($value)
 * @method static \Illuminate\Database\Query\Builder|\Modules\Appointment\Entities\AppointmentUser withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\Modules\Appointment\Entities\AppointmentUser withoutTrashed()
 * @property-read \Modules\Finance\Entities\Transaction|null $transaction
 */
class AppointmentUser extends Model
{

    protected static function boot()
    {
        parent::boot();


    }


    use SoftDeletes;
    protected $guarded = ['id'];
    protected $casts = [
        'kind' => 'Boolean',
        'is_confirmed' => 'Boolean',
        'confirmed_at' => 'datetime',
        'r_part' => 'json',
        'detail' => 'json'
    ];

    protected function asJson($value)
    {
        return json_encode($value,JSON_UNESCAPED_UNICODE);
    }


    /**
     * @param array $casts
     */


    CONST SHORT_LINK = 'SHORT_LINK';
    CONST STATUS_PENDING = 0;
    CONST STATUS_OK = 1;
    CONST STATUS_CANCELED = 2;
    CONST STATUS_DELETED = 3;
    CONST STATUS_PANAHI = 4;
    CONST STATUS_HAS_IDENTIFIERـCODE = 5;
    CONST STATUS_PENDING_CALL = 6;
    CONST IDENTIFIERـCODE = "IDENTIFIERـCODE";
    CONST SECOND_MOBILE = "SECOND_MOBILE";
    CONST MEDICAL_RECORD_NUMBER = "MEDICAL_RECORD_NUMBER";

    public static $status = [
        self::STATUS_OK => [
            'title' => 'نوبت تایید شده',
            'class' => 'success',
            'icon' => 'glyphicons glyphicons-ok_2'
        ],
        self::STATUS_PENDING => [
            'title' => 'نوبت در انتظار',
            'class' => 'warning',
            'icon' => 'fa fa-info-circle'
        ],
        self::STATUS_CANCELED => [
            'title' => 'نوبت کنسل شده',
            'class' => 'cancel',
            'icon' => 'glyphicons glyphicons-remove_2'
        ],
        self::STATUS_DELETED => [
            'title' => 'نوبت حذف شده',
            'class' => 'danger',
            'icon' => 'glyphicons glyphicons-remove_2'
        ],
        self::STATUS_PANAHI => [
            'title' => 'همه نوبت های بجز حذف شده ها',
            'class' => 'success',
            'icon' => 'glyphicons glyphicons-ok_2'
        ],
        self::STATUS_HAS_IDENTIFIERـCODE => [
            'title' => 'نوبت هایی که کد معرف دارند',
            'class' => 'success',
            'icon' => 'glyphicons glyphicons-gift'
        ],
        self::STATUS_PENDING_CALL => [
            'title' => 'نوبت تماس های بدون وقت',
            'class' => 'alert',
            'icon' => 'glyphicons glyphicons-headphones'
        ],

    ];

    CONST KIND_MAIN_APPOINTMENT = 1;
    CONST KIND_SUBSIDIARY_APPOINTMENT = 0;
    public static $kinds = [
        self::KIND_MAIN_APPOINTMENT => [
            'title' => 'نوبت اصلی'
        ],
        self::KIND_SUBSIDIARY_APPOINTMENT => [
            'title' => 'بین مریض'
        ],
    ];

    const VISIT_PENDING = 0;
    const VISIT_VISITED = 1;
    const VISIT_USER_CANCEL = 2;
    public static $visitStatus = [
        self::VISIT_PENDING => [
            'title' => 'در انتظار'
        ],
        self::VISIT_VISITED => [
            'title' => 'ویزیت شده'
        ],
        self::VISIT_USER_CANCEL => [
            'title' => 'عدم حضور بیمار'
        ]
    ];

    public function user()
    {
        return $this->belongsTo(User::class,'user_id');
    }

    public function agent()
    {
        return $this->belongsTo(User::class,'agent_id');
    }

    public function doctor()
    {
        return $this->belongsTo(User::class,'doctor_id');
    }

    public function confirmedBy()
    {
        return $this->belongsTo(User::class, 'confirmed_by');
    }

    public function identifierHistories()
    {
        return $this->hasMany(AppointmentIdentifierHistory::class)->orderBy('created_at', 'desc');
    }

    public function appointmentPart()
    {
        return $this->belongsTo(AppointmentPart::class);
    }

    public function generateCode(){
        $code = $this->id ?? 0;
        for ($i=0;$i<=4;$i++){
            $code .= rand(1,9);
        }
        return $code;
    }

    public function transaction()
    {
        return $this->belongsTo(Transaction::class,'transaction_id');
    }

    public function addQueueSms()
    {

    }
}
