<?php

namespace Modules\Appointment\Entities\oldAppointments;

use Illuminate\Database\Eloquent\Model;


class Reservation extends Model
{
    protected $connection = 'mysql2';
    protected $table = 'reservation';
    protected $fillable = [];

    public function ReservationLists()
    {
        return $this->hasMany(ReservationList::class,'medical_id','id');
    }

    public function ReservationDoctor()
    {
        return $this->belongsTo(ReservationDoctors::class, 'doctor_id','id');
    }
}
    