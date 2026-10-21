<?php

namespace Modules\Diet\Entities\Meal;

use Illuminate\Database\Eloquent\Model;
use Modules\Diet\Entities\Meal;

class Meal_Meta extends Model
{
    protected $table = 'meal_meta';
    protected $fillable = [
        'meta_key',
        'meta_value'
    ];
    public $timestamps = false;

    public function meal()
    {
        return $this->belongsTo(Meal::class);
    }
    //
}
