<?php

namespace Modules\Appointment\Entities;

use App\User;
use Illuminate\Database\Eloquent\Model;

class AppointmentIdentifierHistory extends Model
{
    const ACTION_ADDED = 'added';
    const ACTION_UPDATED = 'updated';
    const ACTION_REMOVED = 'removed';

    protected $guarded = ['id'];

    public function appointmentUser()
    {
        return $this->belongsTo(AppointmentUser::class);
    }

    public function actor()
    {
        return $this->belongsTo(User::class, 'changed_by');
    }
}
