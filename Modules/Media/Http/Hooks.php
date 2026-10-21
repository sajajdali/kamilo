<?php

namespace Modules\Media\Http;

use Eventy;
use Modules\Media\Models\Media;
use View;
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
        /**
         * add css files to admin pages
         */
        Eventy::addAction( 'cms_admin_head', function () {
            $url = Module::asset( 'media:ex' );
            echo <<<html
            <link rel="stylesheet" href="$url/css/jquery.fileuploader.min.css">
            <link rel="stylesheet" href="$url/admin/css/media.css">
html;
        } );
        
        /*
         * add file upload modal to admin pages
         */
        Eventy::addAction( 'cms_admin_foot', function () {
            $files = Media::get_latest_files();
            echo View::make( 'media::modal', [ 'files' => $files ] )->render();
        } );
        
        /**
         * add scripts to admin pages
         */
        Eventy::addAction( 'cms_admin_foot', function () {
            $url = Module::asset( 'media:ex' );
            $store_url = route( 'media.admin.file_store' );
            $delete_url = route( 'media.admin.file_store' );
            $refresh_url = route( 'media.admin.refresh_list' );
            $max_file_size = Media::MAX_FILE_SIZE;
            $csrf = csrf_token();
            echo <<<html
            <script >
                var _file_uploader_data = {
                    store_url: '$store_url',
                    delete_url: '$delete_url',
                    refresh_url: '$refresh_url',
                    max_file_size: '$max_file_size',
                    token: '$csrf'
                }
            </script>
            <script type="text/javascript" src="$url/js/jquery.fileuploader.min.js"></script>
            <script type="text/javascript" src="$url/admin/js/media.js"></script>
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

