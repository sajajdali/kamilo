<?php

namespace Modules\Page\Entities;

use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Lang;
use Spatie\Permission\Traits\HasRoles;

class Page extends Model
{

	protected $guard_name = 'web'; // or whatever guard you want to use
	protected $table = 'pages';
	protected $fillable = [
		'title', 'content','counter','image'
	];

	/**
	 * User article
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	 */
	public function user()
	{
		return $this->belongsTo(User::class);
	}

	/**
	 * Get title from JSON content
	 *
	 * @param string $lang
	 *
	 * @return mixed
	 */
	public function getTitle($lang = 'fa')
	{
		if(empty($lang)) {
			if (!empty(Lang::getLocale())) {
				$language = Lang::getLocale();
			} else {
				$language = Config::get('app.fallback_locale');
			}
		}else{
			$language = $lang;
		}
		$title = json_decode($this->title,true);
		return $title[$language];
	}

	/**
	 * Get content from JSON content
	 *
	 * @param string $lang
	 *
	 * @return mixed
	 */
	public function getContent($lang = 'fa')
	{
		if(empty($lang)) {
			if (!empty(Lang::getLocale())) {
				$language = Lang::getLocale();
			} else {
				$language = Config::get('app.fallback_locale');
			}
		}else{
			$language = $lang;
		}
		$title = json_decode($this->content,true);
		$title = $title['content'];
		return $title[$language];
	}

	/**
	 * Get describtion from content
	 *
	 * @return string
	 */
	public function getDescribtion()
	{
		$content = json_decode($this->content);
		if(isset($content->DESCRIBTION)){
			return $content->DESCRIBTION;
		}else{
			return 'وبسایت زندگی آرام';
		}
	}

	/**
	 * Get keyword from JSON content
	 *
	 * @return array
	 */
	public function getKeyword()
	{
		$content = json_decode($this->content);
		if(isset($content->KEYWORD)){
			return explode(',',$content->KEYWORD);
		}else{
			$key = array();
			$key[] = 'زندگی';
			$key[] = 'مشاوره';
			return $key;
		}
	}

	/**
	 * Link generator
	 *
	 * @return string
	 */
	public function link()
	{
		$title = urlencode(str_replace(' ','-',$this->getTitle()));
		$id    = $this->id;
		return ('page/'.$id.'/'.$title.'.html');
	}
    /*
     * Jalali date
     */
    public function getDateAttribute(){
        return jdate('j F Y',$this->created_at->timestamp);
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
}
