<?php

namespace Modules\Diet\Entities\Meal;

use Illuminate\Database\Eloquent\Model;
use Modules\Diet\Entities\Disease\Disease;

class Meal_category extends Model
{
    CONST REJIM_TYPE_WEIGHT_LOSS = 1;
    CONST REJIM_TYPE_WEIGHT_GAIN = 2;
    CONST REJIM_TYPE_WEIGHT_STABILIZATION = 3;
    CONST REJIM_TYPE_WEIGHT_OFFLINE = 4;
    CONST REJIM_TYPE_SPECIAL = 5;

    public static $REJIMTYPES = [
        self::REJIM_TYPE_WEIGHT_LOSS => "کاهش وزن",
        self::REJIM_TYPE_WEIGHT_GAIN => "افزایش وزن",
        self::REJIM_TYPE_WEIGHT_STABILIZATION => "تثبیت وزن",
        self::REJIM_TYPE_WEIGHT_OFFLINE => "رژیم‌های آفلاین",
        self::REJIM_TYPE_SPECIAL => "گروه خاص"
    ];
    public $timestamps = true;
    protected $table = 'meal_category';
    public function meal()
    {
        return $this->belongsToMany(Meal::class,'category_meal','meal_category_id','meal_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function disease()
    {
        return $this->belongsToMany(Disease::class, 'meal_category_disease');
    }

    public function children()
    {
        return $this->hasMany($this, 'parent');
    }
    //
}
