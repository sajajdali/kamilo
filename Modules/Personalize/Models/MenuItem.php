<?php

namespace Modules\Personalize\Models;

use Modules\Personalize\Helpers\helperString;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Lang;

class MenuItem extends Model
{
    public $timestamps = FALSE;
    protected $table = 'menu_items';
    protected $fillable = [
        'title',
        'link',
        'parent_id',
        'content',
        'priority',
    ];
    
    public function menu()
    {
        return $this->belongsTo( Menu::class );
    }
    
    public function children()
    {
        return $this->hasMany( MenuItem::class, 'parent_id' )->orderBy( 'priority', 'ASC' );
    }
    
    public function translations( $lang = NULL )
    {
        if ( empty( $lang ) )
        {
            if ( ! empty( Lang::getLocale() ) )
            {
                $language = Lang::getLocale();
            } else
            {
                $language = Config::get( 'app.fallback_locale' );
            }
        } else
        {
            $language = $lang;
        }
        $text = json_decode( $this->title, TRUE );
        if ( $text != NULL )
        {
            return $text[ $language . '_title' ];
        } else
        {
            return NULL;
        }
    }
    
    
    public static function get_menu_items( $menu_key, $id = 0 )
    {
        $menu_items = [];
        $active = FALSE;
        $menus = Menu::where( 'menu_key', '=', $menu_key )->first()
            ->menuitems()
            ->where( 'parent_id', '=', $id )
            ->get();
        foreach ( $menus as $a )
        {
            $children = [];
            $link = $a->link;
            if ( empty( $a->link ) )
            {
                $link = 'javascript:void(0);';
            }
            if ( helperString::startsWith( $a->link, 'http://' ) || helperString::startsWith( $a->link, 'www.' ) )
            {
                $link = $a->link;
            } else
            {
                $link = url( $a->link );
                if ( \Request::is( $a->link ) )
                {
                    $active = TRUE;
                }
            }
            
            $menu_items[] = [
                'id'     => $a->id,
                'title'  => $a->translations( 'fa' ),
                'url'    => $link,
                'parent' => $a->parent_id,
                'active' => $active,
            ];
            
            $menu_items = array_merge( $menu_items, self::get_menu_items( 'main_menu', $a->id ) );
        }
        
        return $menu_items;
    }
    
    public static function make_menu_items( $menu_key, $id = NULL )
    {
        if ( ! $menu_key )
            return FALSE;
        $menu_items = self::get_menu_items( $menu_key );
        if ( $menu_items )
        {
            foreach ( $menu_items as $key => $item )
            {
                $item = (object)$item;
                $link = $item->url;
                $title = $item->title;
                $isset_id = NULL;
                if ( $id )
                {
                    $isset_id = $item->parent == $id;
                } else
                {
                    $isset_id = ! $item->parent;
                }
                if ( $isset_id )
                {
                    $li_class = '';
                    if ( isset( $menu_items[ $key + 1 ] ) && $item->id == $menu_items[ $key + 1 ]['parent'] ) $li_class .= 'haschild';
                    echo '<li class="' . $li_class . '"><a href="' . $link . '">' . $title . '</a>';
                    if ( isset( $menu_items[ $key + 1 ] ) && $item->id == $menu_items[ $key + 1 ]['parent'] )
                    {
                        echo '<ul class="sub-menu">';
                        self::make_menu_items( $menu_key, $item->id );
                        echo '</ul>';
                    }
                    echo '</li>';
                }
            }
        }
    }
}
