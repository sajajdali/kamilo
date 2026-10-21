<?php

namespace Modules\Aparat\Entities;

use App\User;
use Illuminate\Database\Eloquent\Model;
use Modules\Media\Models\Media;

class Aparat extends Model
{
    public $timestamps = true;
    protected $table = 'aparats';

    /**
     * Status local scope
     *
     * @param $q
     * @return mixed
     */
    public function scopeActive($q)
    {
        return $q->whereStatus(true);
    }
    /**
     * Get image url
     *
     * @return \Illuminate\Contracts\Routing\UrlGenerator|string
     */
    public function getImageUrlAttribute(){
        return Media::get_media_url($this->image);
    }

    public function getFrameUrlAttribute()
    {
        return route('frontend.videos.show',$this->id);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

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

    public static function getLastVideoInSideBar(){
        $datas = Aparat::active()->orderBy('id','DESC')->take(5)->get();
        $videos = array();
        foreach ($datas as $data){
            $videos[]=[
                'image' => $data->image_url,
                'author' => $data->author,
                'date' => $data->date,
                'title' => $data->title,
                'frame_url' => $data->frame_url,
            ];
        }
        return $videos;
    }
    public function getDateAttribute(){
        return jdate('j F Y',$this->created_at->timestamp);
    }
}
