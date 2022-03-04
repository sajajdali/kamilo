<?php

namespace Modules\Media\Models;

use Illuminate\Database\Eloquent\Model;

class Media extends Model
{
    protected $table = 'media';
    protected $fillable = [
        'name',
        'path',
        'type',
        'created_at',
        'updated_at',
    ];
    const ALLOWED_IMAGE_MIME_TYPES = [
        'image/jpeg',
        'image/gif',
        'image/png',
        'image/svg+xml',
        'image/x-icon',
    ];
    const ALLOWED_FILE_MIME_TYPES = [
        'image/jpeg',
        'image/gif',
        'image/png',
        'image/svg+xml',
        'image/x-icon',
        'application/zip',
        'application/x-rar-compressed',
        'application/pdf',
        'text/plain',
        'video/mp4',
    ];
    const ALLOWED_CROPABLE_IMAGE_TYPES = [
        'image/jpeg',
        'image/png',
    ];
    const UPLOAD_PATH = '/uploads';
    const MAX_FILE_SIZE = 10485760;
    
    /**
     * this function return latest files have been uploaded
     *
     * @param bool $return_html
     *
     * @return array|null|object|string
     */
    public static function get_latest_files( $return_html = TRUE, $media_type = NULL, $number = 100 )
    {
        $media_type = $media_type ?? 'file';
        
        if ( $media_type == 'image' )
            $files = self::whereIn( 'type', self::ALLOWED_IMAGE_MIME_TYPES )->orderByDesc( 'created_at' )->take( $number )->get();
        else
            $files = self::all()->sortByDesc( 'created_at' )->take( $number );
        
        if ( $return_html == FALSE )
        {
            return $files;
        }
        
        $html = '';
        $html .= "
        <div class=\"ajax-file-upload-container latest-files\">
        ";
        if ( $files )
        {
            foreach ( $files as $file )
            {
                $file_name = mb_substr( $file->name, 0, 37 ) . '...';
                $file_full_name = self::get_file_name_from_path( $file->path );
                $file_size = media_get_file_size( filesize( public_path( self::UPLOAD_PATH . '/' . $file->path ) ) );
                $thumbnail = '';
                $thumbnail_url = '';
                if ( in_array( $file->type, self::ALLOWED_CROPABLE_IMAGE_TYPES ) )
                {
                    $thumbnail_url = url( self::UPLOAD_PATH . '/' . self::get_just_name( $file_full_name ) . '-thumbnail.' . self::get_ext( $file_full_name ) );
                    $thumbnail = "background-image: url(\"$thumbnail_url\")";
                } else if ( in_array( $file->type, self::ALLOWED_IMAGE_MIME_TYPES ) )
                {
                    $thumbnail_url = url( self::UPLOAD_PATH . '/' . self::get_just_name( $file_full_name ) . '.' . self::get_ext( $file_full_name ) );
                    $thumbnail = "background-image: url(\"$thumbnail_url\")";
                }
                $html .= "
                <div class=\"ajax-file-upload-statusbar past-file\" data-file-name='$file_full_name' data-file-id='$file->id' data-thumbnail='$thumbnail_url' title='$file_full_name'>
                    <div class=\"ajax-file-upload-preview\" style='$thumbnail'></div>
                    <div class=\"ajax-file-upload-filename\">{$file_name}
                        <span class=\"ajax-file-upload-filename-size\">( $file_size )</span>
                    </div>
                    <div style=\"\" class=\"ajax-file-upload-red ajax-file-upload-delete\"><i class='fa fa-close'></i></div>
                    <div style=\"\" class=\"ajax-file-upload-blue ajax-file-upload-select\">انتخاب</div>
                </div>
                ";
            }
        }
        $html .= "</div>";
        
        return $html;
    }
    
    /**
     * this function returns file name without extension
     *
     * @param $file_name
     *
     * @return string
     */
    public static function get_just_name( $file_name )
    {
        $file_name_exploded = explode( '.', $file_name );
        
        // file name without file format
        array_pop( $file_name_exploded );
        
        return implode( '.', $file_name_exploded );
    }
    
    /**
     * this function returns file extension
     *
     * @param $file_name
     *
     * @return mixed
     */
    public static function get_ext( $file_name )
    {
        $file_name_exploded = explode( '.', $file_name );
        
        return end( $file_name_exploded );
    }
    
    /**
     * this function returns file name by getting file path
     *
     * @param $file_name
     *
     * @return mixed
     */
    public static function get_file_name_from_path( $file_path )
    {
        $file_name_exploded = explode( '/', $file_path );
        
        return end( $file_name_exploded );
    }
    
    public static function get_media_url( $media_id, $size = 'full' )
    {
        $file = self::findOrFail( $media_id );
        $file_full_name = self::get_file_name_from_path( $file->path );
        $is_cropable = in_array( $file->type, self::ALLOWED_CROPABLE_IMAGE_TYPES );
        
        if ( $size == 'full' || ! $is_cropable )
            $size = '';
        if ( $size == 'thumbnail' && $is_cropable )
            $size = '-' . $size;
        
        return url( self::UPLOAD_PATH . '/' . self::get_just_name( $file_full_name ) . $size . '.' . self::get_ext( $file_full_name ) );
    }
    
    public static function make_media_uploader( array $args = [] )
    {
        $defaults = [
            'type'  => 'image',
            'id'    => md5( uniqid( rand( 1, 999 ) ) ),
            'name'  => '',
            'value' => '',
        ];
        $args = (object)array_merge( $defaults, $args );
        
        $_value = htmlspecialchars( $args->value );
        $_remove_button_hidden_class = $_value ? '' : 'hidden';
        $file_single_name = 'فایل';
        $file = $_value ? self::find( $_value ) : '';
        $file_name = is_object( $file ) ? self::get_file_name_from_path( $file->path ) : '';
        $file_alt = is_object( $file ) ? $file->name : '';
        $file_url = is_object( $file ) ? self::get_media_url( $file->id, 'thumbnail' ) : '';
        if ( $args->type == 'image' )
            $file_single_name = 'تصویر';
        
        return "
        <div id='$args->id' class='media-upload-buttons' data-upload-type='$args->type'>
            " . ( ( $args->type == 'file' ) ? "<p class='file-name'>$file_name</p>" : '' ) . "
            " . ( ( $args->type == 'image' ) ? "<img src='$file_url' alt='$file_alt'>" : '' ) . "
            <input type='hidden' name='{$args->name}' value='$_value' >
            <div class='this-buttons'>
                <button class='button delete-current-media $_remove_button_hidden_class'>حذف $file_single_name</button>
                <button class='button open-media-modal-button'>آپلود $file_single_name</button>
            </div>
        </div>
        ";
    }
}
