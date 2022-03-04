<?php

namespace Modules\Blog\Entities;

use Illuminate\Database\Eloquent\Model;

class BlogCategory extends Model
{
    CONST TYPE_VIEW_NOT_SHOW_IN_HOME = 0;
    CONST TYPE_VIEW_SHOW_IN_HOME = 1;

    CONST ID_NORMAL = 1;

    public $timestamps = false;

    protected $table = 'blog_categories';

    protected $fillable = [
        'title', 'counter', 'parent', 'typeView'
    ];

    public function blogs()
    {
        return $this->belongsToMany(Blog::class, 'blog_category', 'category_id', 'blog_id');
    }

    public function children()
    {
        return $this->hasMany($this, 'parent');
    }

    public function getLink()
    {
        if ($this != null) {
            $title = urlencode(str_replace(' ', '-', $this->title));
            $id = $this->id;
            return ('category/' . $id . '/' . $title);
        } else {
            return '';
        }
    }

    public static function getTreeHtml($type = 1)
    {
        if ($type == 2)
            $cats = self::where(['parent' => BlogCategory::ID_NORMAL])->get();
        return self::generateTreeHtml($cats);
    }

    private static function generateTreeHtml($parents)
    {
        $html = '';

        foreach ($parents as $a) {
            $html .= '<li class="cat-item ';
            if ($a->children()->count() > 0) {
                $html .= ' has-child ';
            }

            $html .= '"><a href="';
            $html .= url('blog/category/' . $a->id . '/' . $a->title) . '"';

            $html .= '>';
            $html .= $a->title . '</a>';
            if ($a->children()->count() > 0) {

                $html .= '<ul class="sub-cats">' . self::generateTreeHtml($a->children) . '</ul>';
            }
            $html .= '</li>';
        }

        return $html;
    }
}
