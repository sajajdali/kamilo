<?php

namespace Modules\Blog\Entities;

use Illuminate\Database\Eloquent\Model;

class BlogMeta extends Model
{
    CONST META_KEY_TAG = 'KEYWORD';
    CONST META_KEY_DESCRIPTION = 'DESCRIPTION';
    CONST META_KEY_MAIN_IMAGE = 'MAIN_IMAGE';

    public $timestamps = false;

    protected $table = 'blog_meta';

    protected $fillable = [
        'meta_key', 'meta_value',
    ];

    public function blog()
    {
        return $this->belongsTo(Blog::class);
    }
}
