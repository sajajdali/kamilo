<?php

namespace Modules\Appointment\Entities;

use App\User;
use Illuminate\Database\Eloquent\Model;

/**
 * Modules\Appointment\Entities\AppointmentPart
 *
 * @property int $id
 * @property string $title
 * @property int $parent_id
 * @property mixed $status
 * @property-read \Illuminate\Database\Eloquent\Collection|\Modules\Appointment\Entities\AppointmentException[] $appointmentExceptions
 * @property-read \Illuminate\Database\Eloquent\Collection|\Modules\Appointment\Entities\AppointmentUser[] $appointmentUsers
 * @property-read \Illuminate\Database\Eloquent\Collection|\Modules\Appointment\Entities\Appointment[] $appointments
 * @property-read \App\User $users
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Appointment\Entities\AppointmentPart newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Appointment\Entities\AppointmentPart newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Appointment\Entities\AppointmentPart query()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Appointment\Entities\AppointmentPart whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Appointment\Entities\AppointmentPart whereParentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Appointment\Entities\AppointmentPart whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Appointment\Entities\AppointmentPart whereTitle($value)
 * @mixin \Eloquent
 * @property-read \Illuminate\Database\Eloquent\Collection|\Modules\Appointment\Entities\AppointmentPart[] $children
 */
class AppointmentPart extends Model
{
    public $timestamps = false;
    protected $guarded = [];
    protected $casts = [
        'status' => 'Boolean'
    ];

    /** @var array doctor role ids */
    const DOCTOR_ROLES = [4,5];

    public function users()
    {
        return $this->belongsToMany(User::class);
    }

        public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }

    public function appointmentExceptions()
    {
        return $this->hasMany(AppointmentException::class);
    }

    public function appointmentUsers()
    {
        return $this->hasMany(AppointmentUser::class);
    }

    public function children()
    {
        return $this->hasMany(AppointmentPart::class,'parent_id');
    }
}
