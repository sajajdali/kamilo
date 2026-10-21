<!DOCTYPE html>
<html lang="fa">
<head>

    <title>مشاهده جزئیات نوبت شماره {{ $appointmentDetail->code}} برای {{$appointmentDetail->user->full_name}}</title>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="title" content="مشاهده جزئیات نوبت شماره {{ $appointmentDetail->code}} برای {{$appointmentDetail->user->full_name}}">
    <meta name="description" content="مشاهده جزئیات نوبت شماره {{ $appointmentDetail->code}} برای {{$appointmentDetail->user->full_name}}">
    <meta name="keywords" content="مشاهده جزئیات نوبت شماره {{ $appointmentDetail->code}} برای {{$appointmentDetail->user->full_name}}">

    <meta property="og:title" content="مشاهده جزئیات نوبت شماره {{ $appointmentDetail->code}} برای {{$appointmentDetail->user->full_name}}">
    <meta property="og:url" content="{{route('profile.appointment.result.cancel',[$appointmentDetail->code,$appointmentDetail->id])}}">
    <meta property="og:description" content="{{ $appointmentDetail->code}} برای {{$appointmentDetail->user->full_name}}">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css" integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous">

    <link rel="stylesheet" href="{{ URL::asset('css/main.css?v=16989f3a') }}">

    <script>
        var LANG = 'FA';
    </script>
</head>
<body>

<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="0" height="0" style="position:absolute">

</svg>

<div class="page page-home">

    <div class="header">

        <div class="header__wrap section-wrap">


        </div>

    </div>


    <div class="section section__info ">
        <div class="section-wrap ">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-md-12">
                        @if(isset($appointmentDetail->showCancelMessage) && $appointmentDetail->showCancelMessage == true)
                        <div class="alert alert-success">
                            نوب شما با موفقیت کنسل شد .
                            <br>
                            در صورتی که میخواهید مجدد نوبت بگیرید با شماره های کلینیک تماس بگیرید
                        </div>
                        @endif
                        <h2>
                            @if($appointmentDetail->status == 1)
                                نوبت شما در حالت <span>تایید شده</span> در سیستم میباشد
                             @elseif($appointmentDetail->status == 0)
                                نوبت شما در حالت <span class="text-warning">در انتظار تایید </span> در سیستم میباشد
                            @elseif($appointmentDetail->status == 2)
                                <div class="alert alert-danger  ">
نوبت شما کنسل شده است
                                </div>
                               نوبت شما در حالت  <span class="text-danger">کنسل شده</span> میباشد
                            @endif
                        </h2>

                        <p class="details">
                            جزییات نوبت شما:
                        </p>
                        <table class="table table-striped table-bordered ">
                            <thead>
                            <tr>
                                <th>نام و نام خانوداگی</th>
                                <th>{{$appointmentDetail->user->full_name}}</th>

                            </tr>
                            </thead>

                            <tbody>
                            <tr>
                                <td>نام بخش</td>
                                <td>{{$appointmentDetail->appointmentPart->title}}</td>
                            </tr>
                            <tr>
                                <td>پزشک شما</td>
                                <td>{{$appointmentDetail->doctor->full_name}}</td>
                            </tr>
                            <tr>
                                <td>تاریخ نوبت</td>
                                <td>
                                        {{ jdate('l j F',jmktime(0,0,0,$appointmentDetail->month,$appointmentDetail->day,$appointmentDetail->year)) }}
                                </td>
                            </tr>
                            <tr>
                                <td>ساعت حضور</td>
                                <td>
                                    @if($appointmentDetail->status == 2)
                                        <span class="bg-danger text text-light">نوبت شما کنسل شده است</span>
                                     @else
                                        {{ substr($appointmentDetail->time_from, 0, -3) }}
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td>مشاور شما</td>
                                <td>{{ $appointmentDetail->agent->full_name }}</td>
                            </tr>
                            <tr>
                                <td>شماره پیگیری</td>
                                <td>{{ $appointmentDetail->code}}</td>
                            </tr>


                            </tbody>
                        </table>
                        @if(get_option('APPOINTMENT_SHOW_CLINIC_ADDRESS'))
                            <p class="address">
                          <span>
                              آدرس کلینیک:
                          </span>
                                {{ get_option('user_appointment_clinic_address') }}
                            </p>
                            <div id="map">
                                <iframe src="{{get_option('user_appointment_clinic_map')}}" width="100%" height="350px"></iframe>
                            </div>
                        @endif
                        <a class="telephone-number" href="tel:{{get_option('user_appointment_tell')}}">
                            <p>
                                <i class="fas fa-phone"></i>
                                تلفن تماس
                            </p>
                            <p >
                                {{get_option('user_appointment_tell')}}
                            </p>
                        </a>

{{--                            @if($appointmentDetail->cancelByUser && $appointmentDetail->status != 2)--}}
{{--                                <div class="buttons">--}}
{{--                                    <a id="various1" href="#cancel-fancybox" class="danger">کنسل کردن نوبت</a>--}}
{{--                                    <a id="various2" href="#share-fancybox" class="alternate ">ارسال به دیگران</a>--}}
{{--                                </div>--}}
{{--                            @else--}}
                                <div class="one-btn">
                                    <a id="various4" href="#share-fancybox"  class="alternate">ارسال به دیگران</a>
                                </div>
{{--                            @endif--}}


                        <div class="cancel-text" >
                            <p>
                                در صورتی که امکان حضور ندارید حتما نوبت خود را کنسل و یا با شماره
                                {{str_replace('-','',get_option('user_appointment_tell'))}}
                                                     تماس بگیرید
                                                 </p>
                                                 <div class=" text-center">
                                                     <img src="http://{{$appointmentDetail->shortLink}}/qr" width="149" height="149" alt="qr">
                                                 </div>
                                             </div>
                                             <!--****************fancy box cancel******************-->
                                             <div style="display: none;">
                                                 <div id="cancel-fancybox" style="width:400px;height:200px;overflow:auto;padding: 35px 27px 0px 27px;">
                                                     <p>
                                                         آیا از کنسل کردن نوبت خود اطمینان دارید؟
                                                     </p>
                                                     <div class="y-n">
                                                         <a href="{{route('profile.appointment.result.cancel',[$appointmentDetail->code,$appointmentDetail->id])}}" class="danger">
                                                             بله
                                                         </a>
                                                         <a href="" class="success">
                                                             خیر
                                                         </a>
                                                     </div>
                                                 </div>
                                             </div>
                                             <!--****************fancy box cancel******************-->

                                             <!--****************fancy box share******************-->
                                             <div style="display: none;">
                                                 <div id="share-fancybox" style="width:400px;height:200px;overflow:auto;padding: 35px 27px 0px 27px;">
                                                     <p href="whatsapp://send?text={{$appointmentDetail->shortLink}}" data-action="share/whatsapp/share">
                                                         تمایل دارید این صفحه را در چه جایی به اشتراک بگذارید
                                                     </p>
                                                     <div class="share-box">

                                    <a href="whatsapp://send?text={{$appointmentDetail->shortLink}}" data-action="share/whatsapp/share">
                                        <img src="{{URL::asset('images/media/index/whatsapp.png')}}" alt="">
                                    </a>
                                    <a href="https://telegram.me/share/url?url={{$appointmentDetail->shortLink}}">
                                        <img src="{{URL::asset('images/media/index/telegram.png')}}" alt="">
                                    </a>
                                </div>
                            </div>
                        </div>
                        <!--****************fancy box share******************-->
                    </div>
                </div>
            </div>
        </div>
    </div>




    <div class="section section__footer-bgColor">
        <div class="section-wrap">
            <div class="footer-section-03 text-center">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-12">
                            <a href="https://selakteb.com">
                                <p>
                                    Design by selakteb.com
                                    <i class="far fa-copyright"></i>
                                </p>

                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="{{URL::asset('js/script.js?v=2bb801c7')}}"></script>


</body>
</html>
