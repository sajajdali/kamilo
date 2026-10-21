<?php

namespace Modules\Diet\Entities\Meal;

use Illuminate\Database\Eloquent\Model;

class Meal extends Model
{
    public $timestamps = true;
    protected $table = 'meal';
    protected $fillable = [
        'calorie',
        'meal',
        'content'
    ];
    CONST MEAL_META_TYPE_LOSE = "rejim_type_loss";
    CONST MEAL_META_TYPE_GAIN = "rejim_type_gain";
    CONST MEAL_META_TYPE_FIXING = "rejim_type_fixing";
    public static $MEALTYPES = [
        1 => self::MEAL_META_TYPE_LOSE,
        2 => self::MEAL_META_TYPE_GAIN,
        3 => self::MEAL_META_TYPE_FIXING
    ];

    public function category()
    {
        return $this->belongsToMany(Meal_category::class, 'category_meal', 'meal_id', 'meal_category_id');
    }

    public function metas()
    {
        return $this->hasMany(Meal_Meta::class);
    }

    public function children()
    {
        return $this->hasMany($this, 'parent');
    }
    //
}
