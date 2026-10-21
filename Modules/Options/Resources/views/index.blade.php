@extends('admin.layout.layout')
@section('content')
    <div id="looxBody">
        <form action="" method="post" id="pmwOptionsForm" enctype="multipart/form-data">
            {{ csrf_field() }}
            <div id="looxBox">
                @include( 'admin.layout.alert', ['type' => 'success', 'message' => session('successful') ])
                @include( 'admin.layout.alert' )
                <header>
                    <div class="save_pmw_options hide-if-no-js">
                        <p class="submit">
                            <input name="submit" id="submit" class="button button-primary" value="ذخیره تغییرات" type="submit">
                        </p>
                        <p class="save_message" style="display: none;"></p>
                        <i class="fa fa-circle-o-notch fa-spin fa-2x fa-fw" style="display: none;"></i>
                    </div>
                    <h2>تنظیمات وب سایت
                    </h2>
                </header>
                <div id="looxWrapper">
                    @php
                        $OptionsHelper->make_options( $options );
                    @endphp
                </div>
                <footer>
                    <div class="save_pmw_options hide-if-no-js">
                        <p class="submit">
                            <input name="submit" id="submit" class="button button-primary" value="ذخیره تغییرات" type="submit">
                        </p>
                        <p class="save_message" style="display: none;"></p>
                        <i class="fa fa-circle-o-notch fa-spin fa-2x fa-fw" style="display: none;"></i>
                    </div>
                </footer>
            </div>
        </form>
    </div>
@endsection
