<?php

namespace Modules\Appointment\Entities;

use App\User;
use Illuminate\Database\Eloquent\Model;

/**
 * Modules\Appointment\Entities\AppointmentSms
 *
 * @property int $id
 * @property int $appointment_part_id
 * @property int $user_id
 * @property string $message
 * @property mixed $day_before چند روز قبل برای کاربر پیامک ارسال شود
 * @property mixed $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Modules\Appointment\Entities\AppointmentPart $appointmentPart
 * @property-read \App\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Appointment\Entities\AppointmentSms newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Appointment\Entities\AppointmentSms newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Appointment\Entities\AppointmentSms query()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Appointment\Entities\AppointmentSms whereAppointmentPartId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Appointment\Entities\AppointmentSms whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Appointment\Entities\AppointmentSms whereDayBefore($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Appointment\Entities\AppointmentSms whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Appointment\Entities\AppointmentSms whereMessage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Appointment\Entities\AppointmentSms whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Appointment\Entities\AppointmentSms whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Appointment\Entities\AppointmentSms whereUserId($value)
 * @mixin \Eloquent
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Appointment\Entities\AppointmentSms active()
 */
class AppointmentSms extends Model
{
    protected $guarded = ['id'];
    public $timestamps = true;
    protected $table = "appointment_sms";
    protected $casts = [
        'status' => 'Boolean',
        'day_before' => 'Integer'
    ];

    public function scopeActive($query)
    {
        /** @var $query AppointmentSms */
        return $query->whereStatus(true);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function appointmentPart()
    {
        return $this->belongsTo(AppointmentPart::class);
    }
}
