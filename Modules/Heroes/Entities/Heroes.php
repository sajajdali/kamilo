<?php

namespace Modules\Heroes\Entities;

use Illuminate\Database\Eloquent\Model;
use Modules\Media\Models\Media;

class Heroes extends Model
{
    public $timestamps = true;

    protected $table = 'heroes';
    protected $casts = [
        'status' => 'boolean'
    ];

    /**
     * Local scope for get active heroes
     *
     * @param $query
     * @return mixed
     */
    public function scopeActive($query){
        return $query->whereStatus(true);
    }

    /**
     * Get image url
     *
     * @return \Illuminate\Contracts\Routing\UrlGenerator|string
     */
    public function getImageUrlAttribute(){
        return Media::get_media_url($this->image);
    }
}
