<!DOCTYPE html>
<html lang="fa">
<head>
    <title>بازیابی کلمه عبور</title>
    <meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7"/>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta property="og:image:width" content="500">
    <meta property="og:image:height" content="340">
    <link rel="stylesheet" href="{{ tpl_assets('frontend:ex/template/css/main.css') }}?v=16989f3a">
    <script>
        var LANG = 'FA';
    </script>
</head>
<body>
@php
    include base_path( 'Modules/Frontend/Resources/assets/ex/template/images/sprite.svg' );
@endphp
<div class="page">
    <div class="section section--signin" style="background-image: url('{{ tpl_assets('frontend:ex//media/signin/singinbg.jpg') }}');">
        <div class="section-wrap">
            <div class="signin">

                <div class="signin__toplogo">
                    <i></i>
                    <i class="signin__logowrap">
                        <img src="{{ tpl_assets('frontend:ex/media/signin/logorejimfit.png') }}" alt="">
                    </i>
                    <i></i>
                </div>

                <form action="{{ route('user.recovery.step2') }}" class="signin__form signupp" method="POST">
                    @method('PUT')
                    @csrf
                    <input type="hidden" name="user_id" value="{{ encrypt($user->id) }}">
                    <div class="signin__err">متاسفانه چیزی اشتباه است</div>
                    @if (Session::has('sys_error'))
                        <div class="signin__err2" style="text-align: center;color: white;text-shadow: 0 3px 6px rgba(255,120,0,0.86);">{{ Session::get('sys_error') }}</div>
                    @endif
                    @if($errors->any())
                        <div class="signin__err2" style="text-align: center;color: white;text-shadow: 0 3px 6px rgba(255,120,0,0.86);">{{ $errors->first() }}</div>
                    @endif
                    <div class="signin__input">
                        <span><img src="{{ tpl_assets('frontend:ex/media/signin/formuser.png') }}" alt=""></span>
                        <input type="text" name="code" value="{{ old('code') }}" id="" placeholder="کد تایید">
                    </div>
                    <div class="signin__input">
                        <span><img src="{{ tpl_assets('frontend:ex/media/signin/formpass.png') }}" alt=""></span>
                        <input type="password" name="password" id="firstPass" placeholder="کلمه عبور">
                    </div>
                    <div class="signin__input">
                        <span><img src="{{ tpl_assets('frontend:ex/media/signin/formpass.png') }}" alt=""></span>
                        <input type="password" name="password_confirmation" id="repeatPass" placeholder="تکرار کلمه عبور">
                    </div>
                    <div class="signin__btns signin__btns--marginbot">
                        <button class="btn btn--abc goSignUp" type="submit">بررسی</button>
                    </div>
                </form>


            </div>
        </div>
    </div>
</div>
<script src="{{ tpl_assets('frontend:ex/template/js/script.js') }}?v=2bb801c7"></script>
<script>
    $(document).ready(function () {
        $(".fancyframe").fancybox({
            'type': 'iframe'       // tell the script to create an iframe
        });
    });
</script>
</body>
</html>