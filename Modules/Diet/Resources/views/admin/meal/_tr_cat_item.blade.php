<?php
$under_line_counter++;
?>
@foreach($table_cats as $cat)
    <tr>
        <td class="text-center">
            <div class="btn-group text-right yekan rtl text-center">
                @if($cat->status)
                    <button aria-expanded="false" data-toggle="dropdown"
                            class="btn btn-success br2 btn-xs fs12 dropdown-toggle yekan" type="button"> فعال
                        <span class="caret mr5"></span>
                    </button>
                @else
                    <button aria-expanded="false" data-toggle="dropdown"
                            class="btn btn-danger br2 btn-xs fs12 dropdown-toggle yekan" type="button"> غیرفعال
                        <span class="caret mr5"></span>
                    </button>
                @endif
                <ul role="menu" class="dropdown-menu txt_center">
                    @can('edit_admin_meal_category')
                        <li>
                            <a href="{{ url('admin/meal/category/edit/'.$cat->id) }}">ویرایش</a>
                        </li>
                    @endcan

                    @unless($cat->children()->exists())
                        @can('delete_admin_meal_category')
                            <li>
                                <a class="delet_cat" data-effect="mfp-rotateLeft"
                                   href="{{ url('admin/meal/category/delete/'.$cat->id) }}">حذف</a>
                            </li>
                        @endcan
                    @endunless
                    <li class="divider"></li>
                    <li>
                        <a href="{{ url('admin/meal/category/changeStatus/'.$cat->id) }}">
                            @if($cat->status)
                                غیرفعال سازی
                            @else
                                فعال سازی
                            @endif
                        </a>
                    </li>
                    <li class="divider"></li>
                    <li>
                        <a target="_blank" href="#">مشاهده</a>
                    </li>
                </ul>
            </div>
        </td>
        <td class="rtl yekan">
            {{ $cat->price . ' تومان' }}
        </td>
        <td class="rtl yekan">
            @if(isset(\Modules\Diet\Entities\Meal\Meal_category::$REJIMTYPES[$cat->rejim_type]))
                {{ \Modules\Diet\Entities\Meal\Meal_category::$REJIMTYPES[$cat->rejim_type] }}
            @endif
        </td>
        <td class="rtl yekan">
            <div @unless($cat->status) style="text-decoration: line-through;color: #999;" @endunless>
                @for($i=1;$i<$under_line_counter;$i++)
                    -
                @endfor
                {{ $cat->name }}
            </div>
        </td>
        <td class="text-center">
            <label class="options blocks mn">
                <input type="checkbox" value="{{ $cat->id }}" name="cat_id[]">
            </label>
        </td>
    </tr>
    @if($cat->children()->exists())
        @include('diet::admin.meal._tr_cat_item', array('table_cats' => $cat->children,'under_line_counter'=>$under_line_counter))
    @endif
@endforeach