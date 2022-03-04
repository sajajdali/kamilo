<?php

namespace Modules\Blog\Entities;

use App\User;
use Illuminate\Database\Eloquent\Model;
use Modules\Media\Models\Media;
use PhpParser\Node\Expr\Array_;

class Blog extends Model
{
    CONST TYPE_NORMAL = 0;

    public $timestamps = true;

    protected $table = 'blogs';

    protected $fillable = [
        'user_id', 'name', 'text', 'counter', 'comment', 'type', 'published',
        'show_at_home_top', 'show_at_home_bottom', 'main_page', 'details',
    ];

    public function scopePublished($query){
        return $query->wherePublished(true);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function categories()
    {
        return $this->belongsToMany(BlogCategory::class, 'blog_category', 'blog_id', 'category_id');
    }

    public function comments()
    {
        return $this->hasMany(BlogComment::class);
    }

    public function metas()
    {
        return $this->hasMany(BlogMeta::class);
    }

    public function getTagsAttribute()
    {
        return $this->metas()->whereMetaKey(BlogMeta::META_KEY_TAG);
    }

    public function getDescriptionAttribute()
    {
        return $this->metas()->whereMetaKey(BlogMeta::META_KEY_DESCRIPTION);
    }

    public function getImageAttribute()
    {
        $image =  $this->metas()->whereMetaKey(BlogMeta::META_KEY_MAIN_IMAGE);
        if($image->exists()){
            $image = $image->first()->meta_value;
            return Media::get_media_url($image);
        }
        return $this->metas()->whereMetaKey(BlogMeta::META_KEY_MAIN_IMAGE);
    }

    public function imageURL()
    {
        $image = $this->image()->first();

        if (!empty($image))
            return url('uploads/blog' . $image);
        return url('images/NoPhoto.jpg');

    }

    public function mainImg()
    {
        $hometext = $this->get__HOMETEXT();
        $doc2 = new \DOMDocument();
        $doc2->loadHTML($hometext);
        $xml = simplexml_import_dom($doc2);
        $imgss = $doc2->getElementsByTagName('img')->item(0);
        if (!empty($imgss)) {
            $imagess = $imgss->getAttribute('src');
        } else {
            $imagess = url('images/NoPhoto.jpg');
        }
        return $imagess;
    }

    /**
     * get link complete view blog
     *
     * @return \Illuminate\Contracts\Routing\UrlGenerator|string
     */
    public function getLinkAttribute()
    {
        $title = urlencode(str_replace(' ', '-', $this->title));
        $id = $this->id;
        return route('frontend.blog.detail',['blog'=>$id,'slug'=>$title]);
    }

    public function getCommentTree()
    {
        return $this->generateCommentHtmlTree($this->comments()->where(['parent' => 0, 'status' => 1])->get());
    }

    private function generateCommentHtmlTree($comments)
    {
        $html = '';
        if (!empty($comments)) {
            foreach ($comments as $comment) {
                $html .= "<div class='comment'>";
                $html .= "<div class='comment-body'><div class='comment-image'>
                                                <img src=\"" . asset('images/nophoto_user.png') . "\" alt=\"{$comment->name}\">
                                            </div>
                                            <div class=\"comment-details\">
                                                <span class=\"author-name\">{$comment->name}</span>
                                                <p class=\"comment-content\">
                                                  {$comment->message}
                                                </p>
                                            </div></div>";

                if ($comment->children()->count() > 0) {
                    $html .= $this->generateCommentHtmlTree($comment->children);
                }
                $html .= "</div>";
            }
            return $html;
        } else {
            return '';
        }
    }

    /**
     * Get Jalali date blog
     *
     * @return string
     */
    public function getDateAttribute(){
        return jdate('j F Y',$this->created_at->timestamp);
    }

    /**
     * Get blog author full name
     *
     * @return array
     */
    public function getAuthorAttribute()
    {
        if($this->user != null){
            return [
                "name" => $this->user->full_name,
                "avatar" => $this->user->avatar
            ];
        }
        return [
            "name" => "",
            "avatar" => ""
        ];
    }
}
