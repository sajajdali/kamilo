<?php

namespace Modules\Options\Models;

use Illuminate\Database\Eloquent\Model;

class Option extends Model
{
    public $timestamps = FALSE;
    protected $table = 'options';
    protected $fillable = [
        'name',
        'value',
    ];
    
    /**
     * this function return an option value by giving a name of that
     *
     * @param        $name
     * @param string $default
     *
     * @return mixed
     */
    public static function get_option( $name, $default = '' )
    {
        $name = explode( '.', $name );
        $hasChild = ( count( $name ) > 1 ) ? TRUE : FALSE;
        $option = self::where( 'name', '=', $name[0] )->first();
        
        if ( ! $option )
            return FALSE;
        
        $option = is_object( json_decode( $option->value ) ) ? json_decode( $option->value, TRUE ) : $option->value;
        
        if ( $hasChild && is_array( $option ) )
        {
            $value = $option;
            for ( $i = 1; $i < count( $name ); $i++ )
            {
                $value = $value[ $name[ $i ] ];
            }
            
            return $value;
        }
        
        
        
        return $option ?? $default;
    }
    
    /**
     * this function make an option in options table
     *
     * @param $name
     * @param $value
     *
     * @return bool
     */
    public static function add_option( $name, $value = '' )
    {
        if ( mb_strlen( $name ) >= 200 || ! preg_match( '/^([\w,-]*)$/', $name ) )
        {
            return FALSE;
        }
        $option = new self;
        $option->name = $name;
        $option->value = $value;
        if ( $option->save() )
        {
            return TRUE;
        } else
        {
            return FALSE;
        }
    }
    
    /**
     * this function update an option in options table
     *
     * @param $name
     * @param $value
     *
     * @return bool
     */
    public static function update_option( $name, $value = '' )
    {
        if ( mb_strlen( $name ) >= 200 || ! preg_match( '/^([\w,-]*)$/', $name ) )
        {
            return FALSE;
        }
        $option = self::where( 'name', '=', $name )->first();
        
        if ( $option )
        {
            $option->value = $value;
        } else
        {
            $option = new self;
            $option->name = $name;
            $option->value = $value;
        }
        if ( $option->save() )
        {
            return TRUE;
        } else
        {
            return FALSE;
        }
    }
}
