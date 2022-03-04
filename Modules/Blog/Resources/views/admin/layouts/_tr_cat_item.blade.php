<?php
$under_line_counter++;
?>
@foreach($table_cats as $cat)
    <tr>
        <td class="text-right">
            <div class="btn-group text-right yekan rtl txt_right">
                <button aria-expanded="false" data-toggle="dropdown" class="btn btn-success br2 btn-xs fs12 dropdown-toggle yekan" type="button"> فعال
                    <span class="caret mr5"></span>
                </button>
                <ul role="menu" class="dropdown-menu txt_right">
                    @can('edit_admin_blog_category')
                    <li>
                        <a href="{{ route('admin.blog.category.edit', $cat->id) }}">ویرایش</a>
                    </li>
                    @endcan
                    @unless($cat->children()->exists())
                        @can('delete_admin_blog_category')
                        <li>
                            <a class="delet_cat" data-effect="mfp-rotateLeft" href="{{ route('admin.blog.category.destroy', $cat->id) }}">حذف</a>
                        </li>
                        @endcan
                    @endunless
                    <li class="divider"></li>
                        <li>
                            <a target="_blank" href="{{ url($cat->getLink()) }}">مشاهده</a>
                        </li>
                </ul>
            </div>
        </td>
        <td class="txt_center yekan">{{ number_format($cat->counter) }}</td>
        <td class="rtl yekan">
            @for($i=1;$i<$under_line_counter;$i++)
                -
            @endfor
        {{ $cat->title }}
        </td>
    </tr>
    @if($cat->children()->exists())
        @include('blog::admin.layouts._tr_cat_item', array('table_cats' => $cat->children,'under_line_counter'=>$under_line_counter))
    @endif
@endforeach