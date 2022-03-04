<?php

namespace Modules\Diet\Entities\Disease;

use Illuminate\Database\Eloquent\Model;
use Modules\Diet\Entities\Meal\Meal_category;

class Disease extends Model
{
    protected $table = 'diseases';
    public $timestamps = true;
    protected $fillable = [
        'name',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function mealCategories()
    {
        return $this->belongsToMany(Meal_category::class, 'meal_category_disease');
    }
}
