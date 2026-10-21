<?php

namespace Modules\Diet\Entities\DietPackage;

use Illuminate\Database\Eloquent\Model;

class DietPackage extends Model
{
    protected $table = "diet_packages";
    protected $guarded = ['id'];
    protected $casts = [
        'details' => 'array',
        'status' => 'boolean'
    ];

    public function scopeActive($query)
    {
        return $query->whereStatus(true);
    }


}

