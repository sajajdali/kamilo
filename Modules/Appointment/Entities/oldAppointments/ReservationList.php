<?php

namespace Modules\Appointment\Entities\oldAppointments;

use Illuminate\Database\Eloquent\Model;

class ReservationList extends Model
{
    protected $connection = 'mysql2';
    protected $table = 'reservation_list';
    protected $fillable = ['inserted'];
    public $timestamps = false;

    public function Reservation(){
        return $this->belongsTo(Reservation::class,'medical_id','id');
    }

}
