@extends('admin.layout.layout')
@section('title',$title ?? 'اضافه کردن ویدیو جدید')
@section('content')
    <!-- Start: Topbar -->
    <header id="topbar" class="ph10">
        <div class="topbar-right rtl yekan">
            <ol class="breadcrumb">
                <li class="crumb-trail">افزودن ویدیو</li>
            </ol>
        </div>
    </header>
    <!-- End: Topbar -->

    <div id="content">
        <div class="row rtl yekan">
            <form method="post" enctype="multipart/form-data" action="{{ route('admin.aparat.store') }}">
                @csrf
                <div class="col-md-3">
                    <div class="panel">
                        <div class="panel-heading">
                            <span class="panel-title">انتشار</span>
                        </div>
                        <div class="panel-body admin-form">

                            <div style="clear:both"></div>
                            <hr style="margin: 20px 0px"/>
                            <div class="col-xs-6 pull-right">
                                <button class="btn btn-primary btn-block" value="published" name="submit" type="submit">
                                    انتشار
                                </button>
                            </div>
                            <div class="col-xs-6 pull-right">
                                <button class="btn btn-default btn-block" value="draft" name="submit" type="submit">پیش
                                    نویس
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="panel">
                        <div class="panel-heading">
                            <span class="panel-title">عکس</span>
                        </div>
                        <div class="panel-body" style="direction: ltr;text-align: right">
                            <div class="text-center admin-form">
                                {!! make_media_uploader([ 'type' => 'image', 'name' => 'main_image', 'value' => old('main_image') ]) !!}
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-9">
                    @if (Session::has('sys_error'))
                        <div class="alert alert-danger alert-dismissable rtl yekan">
                            <button aria-hidden="true" data-dismiss="alert" class="close pull-left" type="button">×
                            </button>
                            <i class="fa fa-remove pr10"></i>
                            <strong>خطا!</strong>
                            {{ Session::get('sys_error') }}
                        </div>
                    @endif
                    @if (count($errors) > 0)
                        <div class="alert alert-danger alert-dismissable mb30 rtl yekan">
                            <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                            <h3 class="mt5">خطا!</h3>

                            <p>لطفا خطا های زیر را برطرف و مجددا تلاش نمایید.</p>
                            <ul class="list">
                                @foreach($errors->all() as $err)
                                    <li class="liste-item">{{ $err }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif


                    <div class="panel">
                        <div class="panel-heading">
                            <span class="panel-title">عنوان</span>
                            <ul class="nav panel-tabs-border panel-tabs">
                            </ul>
                        </div>
                        <div class="panel-body">
                            <div class="tab-content pn br-n">
                                <div class="tab-pane active" id="tab">
                                    <div class="form-group">
                                        <div class="col-lg-10">
                                            <input value="{{ old('title') }}" name="title"
                                                   type="text"
                                                   placeholder="عنوان" class="form-control"
                                                   id="inputLarge">
                                        </div>
                                        <label for="inputLarge"
                                               class="col-lg-2 control-label">عنوان </label>
                                        <div style="clear: both"></div>
                                    </div>
                                    <div style="clear: both"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="panel">
                        <div class="panel-heading">
                            <span class="panel-title">محتوا</span>
                            <ul class="nav panel-tabs-border panel-tabs">
                            </ul>
                        </div>
                        <div class="panel-body">
                            <div class="tab-content pn br-n">
                                <div class="tab-pane active" id="tab">
                                    <div class="form-group">
                                        <div class="col-lg-10">
                                            <textarea class="form-control" name="content" placeholder="محتوا"
                                                      id="textArea2">{{ old('content') }}</textarea>
                                        </div>
                                        <label class="col-lg-2 control-label" for="textArea2">محتوا</label>
                                    </div>
                                    <div style="clear: both"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <!-- Panel popup -->
@endsection
@push('scripts')

    <style>
        .admin-form .switch > input:checked + label::after {
            margin-top: -1px;
        }

        .admin-form .switch > label::after {
            margin-top: -1px;
        }

        .remove {
            background: black none repeat scroll 0 0;
            color: white;
            font-size: 20px;
            left: 21px;
            padding: 5px;
            position: absolute;
            top: 0;
            z-index: 2;
        }

        .remove:hover {
            color: #c0c0c0;
        }
    </style>
@endpush
