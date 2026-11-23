<!DOCTYPE html>
<html lang="fa">
<head>
    <title>ورود</title>
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
<div class="page">
    <div class="section section--signin" style="background-image: url('{{ tpl_assets('frontend:ex//media/signin/singinbg.jpg') }}');">
        <div class="section-wrap">
            <div class="signin">

                <div class="signin__toplogo" style="display: none">
                    <i></i>
                    <i class="signin__logowrap">
                        <img src="{{ tpl_assets('frontend:ex/media/signin/logorejimfit.png') }}" alt="">
                    </i>
                    <i></i>
                </div>

                <form action="{{ route('login') }}" class="signin__form enterr" method="POST">
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
                    </div>
                    <div class="signin__remember">
                        <label for="remm" class="input-container">
                            <input type="checkbox" name="remember" id="remm">
                            مرا به خاطر بسپار
                            <span class="input-container__checkmark input-container__checkmark--white"></span>
                        </label>
                        <div>
                            @if (Route::has('user.recovery.step1'))
                                <a class="" href="{{ route('user.recovery.step1') }}" style="display: none;">رمزت رو فراموش کردی؟</a>
                            @endif
                        </div>
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
