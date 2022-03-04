<?php

namespace App\Http\Controllers\Admin\Doc;

use App\Base\AdminController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;

class DocController extends AdminController
{
    /**
     * @param Request $request
     * @param         $module
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index( Request $request, $module )
    {
        $content = ( View::exists( $module . '::doc' ) ) ? View::make( $module . '::doc' )->with( [ 'url' => url( 'admin/doc/' . $module ) ] )->render() : '';
        
        return view( 'admin.layout.doc' )->with( [ 'title' => $module, 'content' => $content ] );
    }
}
