<!DOCTYPE html>
<html lang="fa">
<head>
    <title>عضویت در سیستم</title>
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

                <form action="{{ route('login') }}" class="signin__form signin__form--hidden enterr" method="POST">
                    @csrf
                    <div class="signin__err">متاسفانه چیزی اشتباه است</div>
                    @if($errors->any())
                        <div class="signin__err2" style="text-align: center;color: white;text-shadow: 0 3px 6px rgba(255,120,0,0.86);">ایمیل/شماره موبایل صحیح نمی‌باشد</div>
                    @endif
                    <div class="signin__input">
                        <span><img src="{{ tpl_assets('frontend:ex/media/signin/formuser.png') }}" alt=""></span>
                        <input type="text" name="email" value="{{ old('email') }}" id="username" placeholder="ایمیل \ شماره موبایل">
                    </div>
                    <div class="signin__input">
                        <span><img src="{{ tpl_assets('frontend:ex/media/signin/formpass.png') }}" alt=""></span>
                        <input type="password" name="password" id="password" placeholder="رمز ورود">
                    </div>
                    <div class="signin__btns">
                        <button class="btn btn--ff5 letsgetinside" type="submit">ورود</button>
                        <button class="btn btn--abc letsSignUp" type="submit">عضویت</button>
                    </div>
                    <div class="signin__remember">
                        <label for="remm" class="input-container">
                            <input type="checkbox" name="" id="remm">
                            مرا به خاطر بسپار
                            <span class="input-container__checkmark input-container__checkmark--white"></span>
                        </label>
                        <div>
                            <a class="losturpass" href="">رمزت رو فراموش کردی؟</a>
                        </div>
                    </div>
                </form>

                <form action="{{ route('register') }}" class="signin__form signupp" method="POST">
                    @csrf
                    <div class="signin__err">متاسفانه چیزی اشتباه است</div>
                    @if($errors->any())
                        <div class="signin__err2" style="text-align: center;color: white;text-shadow: 0 3px 6px rgba(255,120,0,0.86);">{{ $errors->first() }}</div>
                    @endif
                    <div class="signin__input">
                        <span><img src="{{ tpl_assets('frontend:ex/media/signin/phone.png') }}" alt=""></span>
                        <input type="text" name="mobile" value="{{ old('mobile') }}" id="mobileNum" placeholder="شماره موبایل">
                    </div>
                    <div class="signin__input">
                        <span><img src="{{ tpl_assets('frontend:ex/media/signin/formuser.png') }}" alt=""></span>
                        <input type="text" name="email" value="{{ old('email') }}" id="" placeholder="ایمیل">
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
                        <button class="btn btn--abc goSignUp" type="submit">عضویت</button>
                        <button class="btn btn--ff5" type="button" onclick="location.href='{{ 'login' }}';">ورود</button>
                    </div>
                </form>

                <form action="" class="signin__form signin__form--hidden tellmepass">
                    <div class="signin__input">
                        <span><img src="../media/signin/formuser.png" alt=""></span>
                        <input type="text" name="" id="" placeholder="ایمیل \ شماره موبایل">
                    </div>
                    <div class="signin__btns signin__btns--marginbot">
                        <button class="btn btn--abc" type="submit">بازیابی رمز</button>
                    </div>
                    <div class="signin__btns signin__btns--row ">
                        <button class="entr">ورود</button>
                        <i></i>
                        <button class="letsSignUp">عضویت</button>
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