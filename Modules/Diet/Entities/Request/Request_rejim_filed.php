<?php

namespace Modules\Diet\Entities\Request;

use Illuminate\Database\Eloquent\Model;
use Modules\Diet\Entities\Meal\Meal;

class Request_rejim_filed extends Model
{
    protected $table = 'request_rejim_filed';
    //

    public function request_rejim_program()
    {
        return $this->belongsTo(Request_rejim_program::class);
    }

    public function meal(){
        return $this->belongsTo(Meal::class,'meal_id');
    }
}
