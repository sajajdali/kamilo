<?php

namespace Modules\Appointment\Entities\oldAppointments;

use Illuminate\Database\Eloquent\Model;

class ReservationDoctors extends Model
{
    protected $fillable = [];
    protected $connection = 'mysql2';
    protected $table = 'reservation_doctors';

    public function Reservations()
    {
        return $this->hasMany(Reservation::class, 'doctor_id','id');
    }
}
