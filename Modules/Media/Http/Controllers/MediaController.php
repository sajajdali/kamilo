<?php

namespace Modules\Media\Http\Controllers;

use Auth;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Intervention\Image\Laravel\Facades\Image;
use Modules\Media\Models\Media;
use Illuminate\Support\Facades\File;

class MediaController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        return view( 'media::index' );
    }
    
    /**
     * Store a newly created resource in storage.
     *
     * @param  Request $request
     *
     * @return Response
     */
    public function store( Request $request )
    {
        $files = $request->files->all();
        if ( $files )
        {
            $errors = [];
            foreach ( $files as $file )
            {
                $file_name = $file->getClientOriginalName();
                $file_ext = Media::get_ext( $file_name );
                $file_mime = $file->getClientMimeType();
                $file_size = $file->getClientSize();
                
                // file name without file format
                $just_name = Media::get_just_name( $file_name );
                
                $this->find_and_change_file_name( $file_name, $just_name, $file_ext );
                
                // check file size
                if ( $file_size > Media::MAX_FILE_SIZE )
                {
                    $errors[ $file->getClientOriginalName() ] = [
                        'text' => 'حجم فایل بیش از حد مجاز 10 مگابایت میباشد',
                    ];
                    continue;
                }
                
                // check file mime type
                if ( $request->_type == 'image' )
                {
                    if ( ! in_array( $file_mime, Media::ALLOWED_IMAGE_MIME_TYPES ) )
                    {
                        $errors[ $file->getClientOriginalName() ] = [
                            'text' => 'لطفا یک فایل تصویری وارد نمایید',
                        ];
                        continue;
                    }
                } else
                {
                    if ( ! in_array( $file_mime, Media::ALLOWED_FILE_MIME_TYPES ) )
                    {
                        $errors[ $file->getClientOriginalName() ] = [
                            'text' => 'فرمت فایل مجاز نمیباشد',
                        ];
                        continue;
                    }
                }
                
                $media = new Media();
                $media->user_id = Auth::id();
                $media->name = $just_name;
                $media->path = $file_name;
                $media->type = $file_mime;
                
                if ( $media->save() )
                {
                    $file->move( public_path( Media::UPLOAD_PATH ), $file_name );
                    
                    // resize image and make thumbnails
                    if ( in_array( $file_mime, Media::ALLOWED_CROPABLE_IMAGE_TYPES ) )
                    {
                        $img = Image::decodePath( public_path( Media::UPLOAD_PATH . '/' . $file_name ) );
                        $crop_size = $img->width();
                        if ( $img->width() > $img->height() )
                            $crop_size = $img->height();
                        $img->crop( $crop_size, $crop_size );
                        $img->resize( 150, 150 );
                        $img->save( public_path( Media::UPLOAD_PATH . '/' . $just_name . '-thumbnail.' . $file_ext ) );
                    }
                }
                else
                {
                    $errors[ $file->getClientOriginalName() ] = [
                        'text' => 'در ارسال فایل مشکلی پیش آمد لطفا مجددا تلاش نمایید',
                    ];
                }
            }
            if ( $errors )
            {
                die( json_encode( [ 'type' => 'error', 'result' => $errors ], JSON_UNESCAPED_UNICODE ) );
            }
            else
            {
                $thumbnail_url = '';
                if ( in_array( $file_mime, Media::ALLOWED_CROPABLE_IMAGE_TYPES ) )
                {
                    $thumbnail_url = url( Media::UPLOAD_PATH . '/' . $just_name . '-thumbnail.' . $file_ext );
                }else if ( in_array( $file_mime, Media::ALLOWED_IMAGE_MIME_TYPES ) )
                {
                    $thumbnail_url = url( Media::UPLOAD_PATH . '/' . $just_name . '.' . $file_ext );
                }
                die( json_encode( [
                    'type' => 'success',
                    'result' => [
                        $file->getClientOriginalName() => [
                            'id' => $media->id,
                            'thumbnail' => $thumbnail_url
                        ]
                    ]
                ], JSON_UNESCAPED_UNICODE ) );
            }
        }
    }
    
    /**
     * this method delete a file
     * @param Request $request
     */
    public function delete( Request $request )
    {
        $file = Media::findOrFail( $request->file_id );
        $file_name = Media::get_file_name_from_path( $file->path );
        File::delete( public_path ( Media::UPLOAD_PATH . '/' . $file->path ) );
        File::delete( public_path ( Media::UPLOAD_PATH . '/' . Media::get_just_name( $file_name ) . '-thumbnail.' . Media::get_ext( $file_name ) ) );
        $file->delete();
    }
    
    public function refresh( Request $request )
    {
        die( Media::get_latest_files( true, $request->_media_type ) );
    }
    
    private function find_and_change_file_name( &$file_name, &$just_name, $file_ext )
    {
        if ( Media::where( 'path', 'LIKE', '%' . $file_name )->first() )
        {
            $just_name = $just_name . '-1';
            $file_name = $just_name . '.' . $file_ext;
            if ( Media::where( 'path', 'LIKE', '%' . $file_name )->first() )
            {
                $this->find_and_change_file_name( $file_name, $just_name, $file_ext );
            }
        }
    }
}
