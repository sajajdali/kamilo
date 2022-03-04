<?php

namespace Modules\Appointment\Entities;

use App\User;
use Illuminate\Database\Eloquent\Model;

/**
 * Modules\Appointment\Entities\AppointmentException
 *
 * @property int $id
 * @property int|null $user_id
 * @property int|null $appointment_part_id
 * @property int $day
 * @property int $month
 * @property int $year
 * @property string $content
 * @property int $type
 * @property-read \Modules\Appointment\Entities\AppointmentPart|null $appointmentPart
 * @property-read \App\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Appointment\Entities\AppointmentException newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Appointment\Entities\AppointmentException newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Appointment\Entities\AppointmentException query()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Appointment\Entities\AppointmentException whereAppointmentPartId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Appointment\Entities\AppointmentException whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Appointment\Entities\AppointmentException whereDay($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Appointment\Entities\AppointmentException whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Appointment\Entities\AppointmentException whereMonth($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Appointment\Entities\AppointmentException whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Appointment\Entities\AppointmentException whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Appointment\Entities\AppointmentException whereYear($value)
 * @mixin \Eloquent
 */
class AppointmentException extends Model
{
    protected $guarded = ['id'];
    public $timestamps = false;

    protected $casts = [
        'content' => 'json',
        'type' => 'Boolean'
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function appointmentPart()
    {
        return $this->belongsTo(AppointmentPart::class);
    }
}
