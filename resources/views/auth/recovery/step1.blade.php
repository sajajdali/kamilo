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


                <form action="{{ route('user.recovery.step1') }}" class="signin__form tellmepass" method="POST">
                    @csrf
                    @if($errors->any())
                        <div class="signin__err2" style="text-align: center;color: white;text-shadow: 0 3px 6px rgba(255,120,0,0.86);">ایمیل/شماره موبایل صحیح نمی‌باشد</div>
                    @endif
                    @if (Session::has('sys_error'))
                        <div class="signin__err2" style="text-align: center;color: white;text-shadow: 0 3px 6px rgba(255,120,0,0.86);">{{ Session::get('sys_error') }}</div>
                    @endif
                    <div class="signin__input">
                        <span><img src="{{ tpl_assets('frontend:ex/media/signin/formuser.png') }}" alt=""></span>
                        <input type="text" name="term" id="" placeholder="ایمیل \ شماره موبایل" value="{{ old('term') }}">
                    </div>
                    <div class="signin__btns signin__btns--marginbot">
                        <button class="btn btn--abc" type="submit">بازیابی رمز</button>
                    </div>
                    <div class="signin__btns signin__btns--row ">
                        <a href="{{ route('login') }}" style="color: white">ورود</a>
                        <i></i>
                        <a href="{{ route('register') }}" style="color: white">عضویت</a>
                    </div>
                </form>


            </div>
        </div>
    </div>
</div>
<script src="{{ tpl_assets('frontend:ex/template/js/script.js') }}?v=2bb801c8"></script>
<script>
    $(document).ready(function () {
        $(".fancyframe").fancybox({
            'type': 'iframe'       // tell the script to create an iframe
        });
    });
</script>
</body>
</html>