<?php

use Modules\Options\Models\Option;

/**
 * this function return an option value by giving a name of that
 *
 * @param        $name
 * @param string $default
 *
 * @return mixed
 */
function get_option( $name, $default = '' )
{
    return $option = Option::get_option( $name, $default );
}

/**
 * this function make an option in options table
 *
 * @param $name
 * @param $value
 *
 * @return bool
 */
function add_option( $name, $value = '' )
{
    return Option::add_option( $name, $value );
}

/**
 * this function update an option in options table
 *
 * @param $name
 * @param $value
 *
 * @return bool
 */
function update_option( $name, $value = '' )
{
    return Option::update_option( $name, $value );
}



