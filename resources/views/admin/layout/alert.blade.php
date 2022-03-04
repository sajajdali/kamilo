@php
    $type = $type ?? 'error';
    $message = $message ?? $errors;
    $show_errors = ( isset( $show_errors ) && $show_errors ) ? TRUE : FALSE;
    $icon_class = 'check';
    if ( $type == 'warning' || $type == 'danger' )
        $icon_class = 'exclamation';
@endphp

@if( $message && is_string( $message ) )
    <div class="alert alert-{{$type}} alert-dismissable rtl yekan">
        <button aria-hidden="true" data-dismiss="alert" class="close pull-left" type="button">×</button>
        <i class="fa fa-{{$icon_class}} pr10"></i>
        {!! $message !!}
        @if( $show_errors )
            <h3 class="mt5">لطفا خطا های زیر را برطرف و مجددا تلاش نمایید.</h3>
            <ul class="list">
                @if ( count( $errors ) )
                    @foreach($errors->all() as $m)
                        <li class="liste-item">{{ $m }}</li>
                    @endforeach
                @endif
            </ul>
        @endif
    </div>
@endif

@if( $message && is_array( $message ) )
    @if (count($message) > 0)
        <div class="alert alert-{{$type}} alert-dismissable mb30 rtl yekan">
            <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
            @if( $type == 'danger' )
                <h3 class="mt5">لطفا خطا های زیر را برطرف و مجددا تلاش نمایید.</h3>
            @endif
            <ul class="list">
                @if ( is_object( $message ) )
                    @foreach($message->all() as $m)
                        <li class="liste-item">{{ $m }}</li>
                    @endforeach
                @else
                    @foreach($message as $m)
                        <li class="liste-item">{{ $m }}</li>
                    @endforeach
                @endif
            </ul>
            @if( $show_errors )
                <h3 class="mt5">لطفا خطا های زیر را برطرف و مجددا تلاش نمایید.</h3>
                <ul class="list">
                    @if ( count( $errors ) )
                        @foreach($errors->all() as $m)
                            <li class="liste-item">{{ $m }}</li>
                        @endforeach
                    @endif
                </ul>
            @endif
        </div>
    @endif
@endif