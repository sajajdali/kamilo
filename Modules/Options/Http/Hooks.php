<?php

namespace Modules\Options\Http;

use Eventy;
use Nwidart\Modules\Facades\Module;

class Hooks
{
    public function __construct()
    {
    
    }
    
    /**
     * in this method we have action hooks
     */
    public function Actions()
    {
        Eventy::addAction( 'cms_admin_head', function () {
            $url = Module::asset( 'options:ex' );
            echo <<<html
            <link rel="stylesheet" href="$url/fonts/IRANSans/css/fontiran.css">
            <link rel="stylesheet" href="$url/admin/css/admin_style.css">
            <link rel="stylesheet" href="$url/admin/css/options.css">
html;
        } );
        Eventy::addAction( 'cms_admin_foot', function () {
            $url = Module::asset( 'options:ex' );
            echo <<<html
            <script type="text/javascript" src="$url/admin/js/admin_scripts.js"></script>
            <script type="text/javascript" src="$url/admin/js/options.js"></script>
html;
        } );
    }
    
    /**
     * in this method we have filter hooks
     */
    public function Filters()
    {
    
    }
}

