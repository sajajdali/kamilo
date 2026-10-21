<?php
$under_line_counter++;
?>
@foreach($parts->children as $part)
    <tr class="part_children_{{ $parts->id }}" style="display: none">
        <td class="text-center">
            <div class="btn-group text-right yekan rtl txt_right">
                @if($part->status)
                    <button type="button"
                            class="btn btn-success br2 btn-xs fs12 dropdown-toggle yekan"
                            data-toggle="dropdown" aria-expanded="false">
                        فعال
                        @else
                            <button type="button"
                                    class="btn btn-danger br2 btn-xs fs12 dropdown-toggle yekan"
                                    data-toggle="dropdown" aria-expanded="false">
                                غیرفعال
                                @endif

                                <span class="caret mr5"></span>
                            </button>
                            <ul class="dropdown-menu txt_right" role="menu">
                                @can('edit_admin_appointment_part')
                                    <li>
                                        <a href="{{ route('admin.appointment_part.edit', $part->id) }}">ویرایش</a>
                                    </li>
                                @endcan
                                @can('delete_admin_appointment_part')
                                    <li>
                                        <a class="delete_blog"
                                           data-effect="mfp-rotateLeft"
                                           href="{{ route('admin.appointment_part.destroy', $part->id) }}">حذف</a>
                                    </li>
                                @endcan
                            </ul>
                    </button>

            </div>
        </td>
        <td class="rtl yekan txt_center">{{ number_format($part->users->count()) }}</td>
        <td class="rtl yekan" style="padding-right: {{ ($under_line_counter*24) }}px">
            @if($part->children()->exists())
                <span class="fa fa-plus-square text-success open_tress state_cls" data-pid="{{ $part->id }}"></span>
            @endif
            {{ $part->title }}
        </td>
    </tr>
    @if($part->children()->exists())
        @include('appointment::admin.appointment_part._part_tr', array('parts' => $part,'under_line_counter'=>$under_line_counter))
    @endif
@endforeach