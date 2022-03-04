<?php
$under_line_counter++;
?>
@foreach($table_part as $pp)
    <tr @if(sizeof($reference) >= 1)class="
            @foreach($reference as $re)
                ref_{{ $re }}
            @endforeach
        " @endif>
        <td class="text-right">
            <div class="btn-group text-right yekan rtl txt_right">
                @if($pp->status)
                    <button type="button" class="btn btn-success br2 btn-xs fs12 dropdown-toggle yekan" data-toggle="dropdown" aria-expanded="false"> فعال
                        <span class="caret mr5"></span>
                    </button>
                @else
                    <button type="button" class="btn btn-danger br2 btn-xs fs12 dropdown-toggle yekan" data-toggle="dropdown" aria-expanded="false"> غیرفعال
                        <span class="caret mr5"></span>
                    </button>
                @endif
                <ul class="dropdown-menu txt_right" role="menu">
                    @can('delete_admin_support_part')
                        <li>
                            <a data-effect="mfp-rotateLeft" class="delete_part" href="{{ route('admin.support.parts.destroy', $pp->id) }}">حذف</a>
                        </li>
                    @endcan
                    @can('edit_admin_support_part')
                        <li>
                            <a href="{{ route('admin.support.parts.edit', $pp->id) }}">ویرایش</a>
                        </li>
                    @endcan
                    @can('users_admin_support_part')
                        <li>
                            <a href="{{ route('admin.support.parts.users', $pp->id) }}">مشاوران</a>
                        </li>
                    @endcan
                    @can('change_status_admin_support_part')
                        <li>
                            <a href="{{ route('admin.support.parts.changeStatus', $pp->id) }}">
                                @if($pp->status)
                                    غیرفعال کردن بخش
                                 @else
                                    فعال کردن بخش
                                @endif
                            </a>
                        </li>
                    @endcan
                    <li class="divider"></li>
                    <li>
                        <a href="{{ url($pp->title) }}" target="_blank">مشاهده</a>
                    </li>
                </ul>
            </div>
        </td>
        <td class="txt_center yekan">{{ tr_num(number_format($pp->children()->count())) }}</td>
        <td class="txt_center yekan">{{ tr_num(number_format($pp->users()->count())) }}</td>
        <td class="rtl yekan">
                <span style="margin-right: {{ (($under_line_counter-1)*30) }}px;" class=""></span>
            @if($pp->children()->exists())
                <i class="fa fa-minus-square text-info toggle_part" data-rid="{{ $pp->id }}"></i>
            @endif
            {{ $pp->title }}</td>
    </tr>
    @if($pp->children()->exists())
        @include('support::admin.layouts._tr_part_item', array('table_part' => $pp->children,'under_line_counter'=>$under_line_counter,'reference'=>array_merge($reference,array($pp->id))))
    @endif
@endforeach