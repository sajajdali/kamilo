<?php

namespace Modules\Personalize\Models;

use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    CONST KEY_TOP_RIGHT = 'top_right';
    CONST KEY_TOP_LEFT = 'top_left';
    CONST KEY_MAIN = 'main';


    public $timestamps = false;
    protected $table = 'menu';
    protected $fillable = [
        'title', 'menu_key', 'setting'
    ];

    public function menuitems()
    {
        return $this->hasMany(MenuItem::class)->orderBy('priority');
    }

}
