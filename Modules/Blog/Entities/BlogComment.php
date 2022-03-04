<?php

namespace Modules\Blog\Entities;

use Illuminate\Database\Eloquent\Model;

class BlogComment extends Model
{
    public $timestamps = true;

    CONST STATUS_PROVED = 1;
    CONST STATUS_REVIEW = 0;

    protected $table = 'blog_comments';

    protected $fillable = [
        'blog_id', 'parent', 'name', 'email', 'phone', 'ip', 'message', 'status'
    ];

    public function blog()
    {
        return $this->belongsTo(Blog::class);
    }

    public function scopeProved($q)
    {
        return $q->whereStatus(BlogComment::STATUS_PROVED);
    }
    public function children()
    {
        return $this->hasMany($this,'parent')->orderBy('id','ASC');
    }

    public function getDateAttribute()
    {
        return jdate('y/m/d',strtotime($this->created_at));
    }
}
