<?php

namespace Modules\Options\Http\Controllers;

use App\Base\AdminController;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Options\Helpers\OptionsBuilder;
use Nwidart\Modules\Facades\Module;

class OptionsController extends AdminController
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    //view_admin_
    public function index()
    {
        $this->authorize('show_options_in_admin');
        $options = $this->merge_all_options();
        $OptionsHelper = new OptionsBuilder;
        
        return view( 'options::index' )->with( [
            'options'       => $options,
            'OptionsHelper' => $OptionsHelper,
        ] );
    }
    
    public function update( Request $request )
    {
        $this->authorize('update_options_in_admin');
        $inputs = $request->all();
        $files = $request->files->all();
        unset( $inputs['_token'], $inputs['submit'] );
        $errors = [];
        
        $this->upload_files( $files, $inputs );
        
        if ( $inputs )
        {
            foreach ( $inputs as $key1 => $input )
            {
                if ( is_array( $input ) )
                {
                    $input = json_encode( $input, JSON_UNESCAPED_UNICODE );
                } else if ( is_null( $input ) )
                {
                    $input = '';
                } else if ( empty( $input ) )
                {
                
                }
                
                update_option( $key1, $input );
            }
        }
        
        if ( $errors )
        {
            return back()->with( [ 'errors' => $errors ] );
        } else
        {
            $request->session()->flash( 'successful', 'تنظیمات با موفقیت ذخیره شد.' );
            
            return back();
        }
    }
    
    private function upload_files( $files, & $inputs )
    {
        if ( $files )
        {
            foreach ( $files as $key1 => $file )
            {
                if ( is_object( $file ) && $file->isValid() )
                {
                    $inputs[ $key1 ] = $file->getClientOriginalName();
                    $file->move( public_path( 'uploads' ), $file->getClientOriginalName() );
                } else
                {
                    $this->upload_files( $files[ $key1 ], $inputs[ $key1 ] );
                }
            }
        }
    }
    
    private function merge_all_options()
    {
        $path = Module::getPath();
        $options = include $path . '/Options/Config/Options.php';
        $route = scandir( $path );
        
        unset( $route[ array_search( '.', $route, TRUE ) ] );
        unset( $route[ array_search( '..', $route, TRUE ) ] );
        
        if ( $route )
        {
            foreach ( $route as $r )
            {
                if ( is_dir( $path . '/' . $r ) && $r != 'Options' )
                {
                    if ( is_file( $path . '/' . $r . '/Config/Options.php' ) )
                    {
                        $options2 = include $path . '/' . $r . '/Config/Options.php';
                        $options = $this->array_merge( $options, $options2 );
                    }
                }
            }
        }
        return $options;
    }
    
    private function array_merge( array $array1, array $array2 )
    {
        if ( isset( $array2['menu'] ) )
            $array1['menu'] = array_merge( $array1['menu'], $array2['menu'] );
        if ( isset( $array2['content'] ) )
            $array1['content'] = array_merge( $array1['content'], $array2['content'] );
        
        return $array1;
    }
}
