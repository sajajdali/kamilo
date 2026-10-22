<?php

namespace Modules\Personalize\Http\Controllers;

use App\Base\AdminController;
use App\Base\Authorizable;
use Modules\Personalize\Models\Menu;
use Modules\Personalize\Models\MenuItem;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;

class MenuController extends AdminController
{
    /**
     * relational access
     * @var array
     */
    public static $access = [
        'storeitem'  => 'edit',
        'deleteItem' => 'edit',
        'updateItem' => 'edit',
        'getDetail'  => 'edit',
    ];
    
    
    public function index()
    {
        $this->authorize( 'personalize_edit_menus' );
        $title = 'مدیریت منو';
        $menu = Menu::all();
        
        return view( 'personalize::menu.menu', compact( 'title', 'menu' ) );
    }
    
    public function store( Request $request )
    {
        $this->authorize( 'personalize_edit_menus' );
        $this->validate( $request, [
            'title'    => 'required',
            'menu_key' => 'required|unique:menu',
        ] );
        
        $menu = new Menu();
        $menu->title = $request->input( 'title' );
        $menu->menu_key = $request->input( 'menu_key' );
        $menu->setting = '';
        $menu->save();
        $request->session()->flash( 'successful', 'منو با موفقیت ایجاد شد.' );
        
        return back();
    }
    
    public function delete( Request $request, Menu $menu )
    {
        $this->authorize( 'personalize_edit_menus' );
        $menu->delete();
        $request->session()->flash( 'successful', 'منو با موفقیت حذف شد.' );
        
        return back();
    }
    
    public function edit( Request $request, Menu $menu )
    {
        $this->authorize( 'personalize_edit_menus' );
        $title = 'ویرایش منو';
        $items = $menu->menuitems->where( 'parent_id', 0 );
        $all_items = $menu->menuitems;
        
        return view( 'personalize::menu.edit_menu', compact( 'title', 'items', 'menu', 'all_items' ) );
    }
    
    public function update( Request $request, Menu $menu )
    {
        $this->authorize( 'personalize_edit_menus' );
        $menu_items = json_decode( $request->input( 'menu' ), TRUE );
        if ( $this->menuHandler( $menu_items, 0, 0 ) )
        {
            $request->session()->flash( 'successful', 'تغییرات با موفقیت اعمال شد' );
            
            return back();
        } else
        {
            $request->session()->flash( 'sys_error', 'در حال حاضر امکان ایجاد تغییرات نمی باشد . لطفا مجددا تلاش نمایید.' );
            
            return back();
        }
    }
    
    //save helper
    public function menuHandler( $menu, $parent_id = 0, $priority = 0 )
    {
        foreach ( $menu as $mm )
        {
            $menu_item = MenuItem::find( $mm['id'] );
            $menu_item->parent_id = $parent_id;
            $menu_item->priority = $priority;
            $menu_item->save();
            if ( isset( $mm['children'] ) )
            {
                $this->menuHandler( $mm['children'], $mm['id'], 0 );
            }
            $priority++;
        }
        
        return TRUE;
    }
    
    public function storeitem( Request $request, Menu $menu )
    {
        $this->authorize('personalize_edit_menus');
        $validator = Validator::make( $request->all(), [
            'title.*' => 'required',
            'link'    => 'required',
        ] );
        if ( $validator->fails() )
        {
            $request->session()->flash( 'sys_error', 'برای ایجاد ایتم جدید تمام فیلدها را به صورت صحیح پر نمایید.' );
            
            return back()->withInput();
        } else
        {
            $menu_item = new MenuItem();
            $counter = 0;
            $title_input = $request->input( 'title' );
            $title = array();
            foreach ( Config::get( 'app.locales' ) as $key => $value )
            {
                $title[$key . '_title'] = $title_input[ $counter ];
                $counter++;
            }
            $menu_item->title = json_encode( $title );
            $menu_item->link = $request->input( 'link' );
            $menu_item->parent_id = 0;
            $menu_item->content = '';
            $menu_item->priority = 0;
            $menu->menuitems()->save( $menu_item );
            $request->session()->flash( 'successful', 'ایتم جدید با موفقیت ایجاد شد.' );
            
            return back();
        }
    }
    
    public function deleteItem( Request $request, Menu $menu )
    {
        $this->authorize('personalize_edit_menus');
        $validator = Validator::make( $request->all(), [
            'item_id' => 'required|numeric|min:1',
        ] );
        if ( $validator->fails() )
        {
            $request->session()->flash( 'sys_error', 'یک ایتم جهت حذف انتخاب نمایید.' );
            
            return back()->withInput();
        } else
        {
            $menu_item = MenuItem::find( $request->input( 'item_id' ) );
            if ( $menu_item->children->count() >= 1 )
            {
                $this->deleteHandler( $menu_item->children );
            }
            $menu_item->delete();
            $request->session()->flash( 'successful', 'ایتم با موفقیت حذف شد.' );
            
            return back();
        }
    }
    
    public function deleteHandler( $item )
    {
        foreach ( $item as $tt )
        {
            if ( $tt->children->count() >= 1 )
            {
                $this->deleteHandler( $tt->children );
            }
            $tt->delete();
        }
    }
    
    public function getDetail( Request $request )
    {
        $meta = MenuItem::find( $request->input( 'id' ) );
        $ret = csrf_field() . method_field( 'OPTIONS' ) . '
            <div class="panel-body p25"><div class="admin-form">';
        foreach ( Config::get( 'app.locales' ) as $key => $value )
        {
            $ret .= '<div class="section row">
                    <div class="col-md-12">
                        <label for="firstname" class="field prepend-icon">
                            <input value="' . $meta->translations( $key ) . '" type="text" name="title[]" id="title_' . $key . '" class="gui-input" placeholder="عنوان ' . $value . '">
                            <label for="title_' . $key . '" class="field-icon"><i class="fa fa-globe"></i>
                            </label>
                        </label>
                    </div>
                    <!-- end section -->
                </div>
                <!-- end section row section -->';
        }
        $ret .= '<div class="section row">
                        <div class="col-md-12">
                            <label for="firstname" class="field prepend-icon">
                                <input type="text" value="' . $meta->link . '" name="link" id="link" class="gui-input" placeholder="لینک">
                                <label for="link" class="field-icon"><i class="fa fa-link"></i>
                                </label>
                            </label>
                        </div>
                        <!-- end section -->
                    </div>
                    <!-- end section row section -->
                <div class="section">
                    <label for="comment" class="field prepend-icon">
                        <textarea class="gui-textarea" id="comment" name="content" placeholder="محتوا">' . $meta->content . '</textarea>
                        <label for="comment" class="field-icon"><i class="fa fa-html5"></i>
                        </label>
                                        <span class="input-footer rtl">
                                            <strong>محتوای HTML.</strong> این محتوا برای زیرمنو ها معنا دارد.</span>
                    </label>
                </div>
                <!-- end section -->

            </div>
            <!-- end .form-body section -->

            <div class="panel-footer admin-form">
                <button type="submit" class="button btn-primary">ویرایش</button>
            </div>
            </div>
            <input type="hidden" name="item_id" value="' . $request->input( 'id' ) . '" />
            <!-- end .form-footer section -->
        ';
        
        return $ret;
    }
    
    public function updateItem( Request $request, Menu $menu )
    {
        $this->authorize('personalize_edit_menus');
        $validator = Validator::make( $request->all(), [
            'title.*' => 'required',
            'link'    => 'required',
            'item_id' => 'required',
        ] );
        if ( $validator->fails() )
        {
            $request->session()->flash( 'sys_error', 'جهت ویرایش منو تمام فیلد ها را پر نمایید.' );
            
            return back()->withInput();
        } else
        {
            $menu_item = MenuItem::find( $request->input( 'item_id' ) );
            $counter = 0;
            $title_input = $request->input( 'title' );
            $title = array();
            foreach ( Config::get( 'app.locales' ) as $key => $value )
            {
                $title[$key . '_title'] = $title_input[ $counter ];
                $counter++;
            }
            $menu_item->title = json_encode( $title );
            $menu_item->link = $request->input( 'link' );
            $menu_item->content = $request->input( 'content' );
            $menu->menuitems()->save( $menu_item );
            $request->session()->flash( 'successful', 'ایتم با موفقیت بروزرسانی شد.' );
            
            return back();
        }
    }
}
