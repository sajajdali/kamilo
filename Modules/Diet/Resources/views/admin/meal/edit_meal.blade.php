@extends('admin.layout.layout')
@section('title',$title ?? 'ویرایش وعده')
@section('content')


    <div id="content">
        <div class="row rtl yekan">
            <form method="post">
                {{ csrf_field() }}
                <div class="col-md-3">
                    <div class="panel">
                        <div class="panel-heading">
                            <span class="panel-title">انتشار خبر</span>
                        </div>
                        <div class="panel-body">
                            <div class="pull-right"><i class="fa fa-calendar" style="margin-left: 5px;"></i>
                                تاریخ انتشار :
                                {{ jdate('d F Y - h:i:s') }}
                            </div>
                            <div style="clear:both"></div>
                            <hr style="margin: 20px 0px"/>
                            <div class="col-xs-6 pull-right">
                                <button class="btn btn-primary btn-block" value="published" name="submit" type="submit">انتشار</button>
                            </div>
                            <div class="col-xs-6 pull-right">
                                <button class="btn btn-default btn-block" value="draft" name="submit" type="submit">پیش نویس</button>
                            </div>
                        </div>
                    </div>
                    <div class="panel">
                        <div class="panel-heading">
                            <span class="panel-title">مربوط به الگوی</span>

                        </div>
                        <div class="panel-body" id="categoryRejim">
                            <div class="admin-form option-group field section" style="height: 200px;overflow-y: scroll;border: 1px solid #ddd" >
                                @include('diet::admin.meal.__select_add_meal', array('cats' => $category,'under_line_counter'=>-1,'old_category'  =>  (isset($meal->category) ? $meal->category : '')))
                            </div>
                        </div>
                    </div>
                    <div class="panel">
                        <div class="panel-heading">
                            <span class="panel-title">نوع رژیم</span>
                        </div>
                        <div class="panel-body" style="direction: ltr;text-align: right">
                            <div class="admin-form">
                                <div class="switch switch-system round switch-inline pull-left">
                                    <input
                                            @if(old('submit') || isset($data['metas']['rejim_type_loss']))
                                            @if(old('rejim_type.loss',(isset($data['metas']['rejim_type_loss']) ? $data['metas']['rejim_type_loss'] : '')))
                                            checked
                                            @endif
                                            @elseif(Request::is('admin/meal/add')) checked  @endif name="rejim_type[loss]" type="checkbox"  id="exampleCheckboxSwitch01" value="1">
                                    <label for="exampleCheckboxSwitch01"></label>
                                </div>
                                <span class="yekan">کاهش وزن</span>
                                <hr class="short" />
                                <div class="switch switch-system round switch-inline pull-left">
                                    <input name="rejim_type[gain]" type="checkbox"
                                           @if(old('submit') || isset($data['metas']['rejim_type_gain']))
                                           @if(old('rejim_type.gain',(isset($data['metas']['rejim_type_gain']) ? $data['metas']['rejim_type_gain'] : ''))) checked @endif
                                           @endif id="exampleCheckboxSwitch12" value="1">
                                    <label for="exampleCheckboxSwitch12" style="margin-top: -2px;"></label>
                                </div>
                                <span class="yekan">افزایش وزن</span>
                                <hr class="short" />
                                <div class="switch switch-system round switch-inline pull-left">
                                    <input name="rejim_type[fixing]" type="checkbox"
                                           @if(old('submit') || isset($data['metas']['rejim_type_fixing']))
                                           @if(old('rejim_type.fixing',(isset($data['metas']['rejim_type_fixing']) ? $data['metas']['rejim_type_fixing'] : ''))) checked @endif
                                           @endif id="fixingDiet" value="1">
                                    <label for="fixingDiet" style="margin-top: -2px;"></label>
                                </div>
                                <span class="yekan">تثبیت وزن</span>
                            </div>
                        </div>

                    </div>
                    <div class="panel">
                        <div class="panel-heading">
                            <span class="panel-title">تنظیمات</span>
                        </div>
                        <div class="panel-body" style="direction: ltr;text-align: right">
                            <div class="admin-form">
                                <div class="switch switch-info round switch-inline pull-left">
                                    <input name="season[]" type="checkbox"  @if(old('season') || isset($data['metas']['season'])) @if(in_array('1',old('season',$data['metas']['season']))) checked @endif @else checked @endif id="exampleCheckboxSwitch6" value="1">
                                    <label for="exampleCheckboxSwitch6"></label>
                                </div>
                                <span class="yekan"> {{old('season.0')}}مربوط به فصل فهار</span>
                                <hr class="short" />
                                <div class="switch switch-info round switch-inline pull-left">
                                    <input name="season[]" type="checkbox" @if(old('season') || isset($data['metas']['season'])) @if(in_array('2',old('season',$data['metas']['season']))) checked @endif @else checked @endif id="exampleCheckboxSwitch7" value="2">
                                    <label for="exampleCheckboxSwitch7" style="margin-top: -2px;"></label>
                                </div>
                                <span class="yekan">مربوط به فصل تابستان</span>
                                <hr class="short" />
                                <div class="switch switch-info round switch-inline pull-left">
                                    <input name="season[]" type="checkbox" @if(old('season') || isset($data['metas']['season'])) @if(in_array('3',old('season',$data['metas']['season']))) checked @endif @else checked @endif id="exampleCheckboxSwitch8" value="3">
                                    <label for="exampleCheckboxSwitch8" style="margin-top: -2px;"></label>
                                </div>
                                <span class="yekan">مربوط به فصل پاییز</span>
                                <hr class="short" />
                                <div class="switch switch-info round switch-inline pull-left">
                                    <input name="season[]" type="checkbox" @if(old('season') || isset($data['metas']['season'])) @if(in_array('4',old('season',$data['metas']['season']))) checked @endif @else checked @endif id="exampleCheckboxSwitch9" value="4">
                                    <label for="exampleCheckboxSwitch9" style="margin-top: -2px;"></label>
                                </div>
                                <span class="yekan">مربوط به فصل زمستان</span>
                            </div>
                        </div>

                    </div>
                    <div class="panel">
                        <div class="panel-heading">
                            <span class="panel-title">مروبوط به روزهای {{old('days.all')}}</span>
                        </div>
                        <div class="form-group">
                            <div class="col-md-12">
                                <br />
                                <select class="col-md-12" id="multiselect2" name="days[]" multiple="multiple">
                                    <option @if((old('submit') && old('days')) || isset($data['metas']['days'])) @if(in_array('all',old('days' , $data['metas']['days']))) selected @endif @else selected @endif  value="all">هر روز</option>
                                    <option @if((old('submit') && old('days')) || isset($data['metas']['days'])) @if(in_array('1',old('days' , $data['metas']['days']))) selected @endif @endif value="1">شنبه</option>
                                    <option @if((old('submit') && old('days')) || isset($data['metas']['days'])) @if(in_array('2',old('days' , $data['metas']['days']))) selected @endif @endif value="2">یکشنبه</option>
                                    <option @if((old('submit') && old('days')) || isset($data['metas']['days'])) @if(in_array('3',old('days' , $data['metas']['days']))) selected @endif @endif value="3">دوشنبه</option>
                                    <option @if((old('submit') && old('days')) || isset($data['metas']['days'])) @if(in_array('4',old('days' , $data['metas']['days']))) selected @endif @endif value="4">سه شنبه</option>
                                    <option @if((old('submit') && old('days')) || isset($data['metas']['days'])) @if(in_array('5',old('days' , $data['metas']['days']))) selected @endif @endif value="5">چهار شنبه</option>
                                    <option @if((old('submit') && old('days')) || isset($data['metas']['days'])) @if(in_array('6',old('days' , $data['metas']['days']))) selected @endif @endif value="6">پنج شنبه</option>
                                    <option @if((old('submit') && old('days')) || isset($data['metas']['days'])) @if(in_array('7',old('days' , $data['metas']['days']))) selected @endif @endif value="7">جمعه</option>
                                </select>

                                <br />
                                <br />
                            </div>
                        </div>
                        <div style="clear: both" class="clear"></div>

                    </div>
                </div>
                <div class="col-md-9">
                    @if (Session::has('sys_error'))
                        <div class="alert alert-danger alert-dismissable rtl yekan">
                            <button aria-hidden="true" data-dismiss="alert" class="close pull-left" type="button">×</button>
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
                <!-- TAG MANAGER -->
                    <div class="panel mb50" id="xedit-demo">
                        <div class="panel-heading">
                            <span class="panel-title">ابتدا الگوی خود را انتخاب کنید </span>

                        </div>
                        <div class="panel-body">
                            <div class="alert alert-info">
                                برای ثبت وعده غذایی شما میبایست ابتدا یک الگو را حداقل انتخاب کنید
                            </div>
                        </div>
                    </div>
                    <div id="type_rejim_1">
                        <div class="col-md-12 rtl yekan">
                            <div class="panel">
                                <div class="panel-heading">
                                    <span class="panel-title">ویرایش وعده</span>
                                </div>
                                <div class="panel-body" id="cover_once_meal">


                                    @if(old('meal.step1',(isset($data['meal']['step1']) ? $data['meal']['step1'] : '')))
                                        @foreach(old('meal.step1',($data['meal']['step1'] ? $data['meal']['step1'] : '')) as $key=>$value)
                                            <div class="col-md-12 row meal-once">
                                                @unless($loop->first)
                                                    <hr/>
                                                @endunless
                                                <div class="form-group">
                                                    <div class="col-lg-10">
                                                        <select name="meal[step1][]" class="multiselect1">
                                                            <option value="-1" @if(old('meal.step1.'.$key,(isset($data['meal']['step1'][$key]) ? $data['meal']['step1'][$key] : '')) == '-1') selected @endif >انتخاب کنید</option>
                                                            <option value="1" @if(old('meal.step1.'.$key,(isset($data['meal']['step1'][$key]) ? $data['meal']['step1'][$key] : '')) == '1') selected @endif>صبحانه</option>
                                                            <option value="2" @if(old('meal.step1.'.$key,(isset($data['meal']['step1'][$key]) ? $data['meal']['step1'][$key] : '')) == '2') selected @endif>میان وعده ۱</option>
                                                            <option value="3" @if(old('meal.step1.'.$key,(isset($data['meal']['step1'][$key]) ? $data['meal']['step1'][$key] : '')) == '3') selected @endif>نهار</option>
                                                            <option value="4" @if(old('meal.step1.'.$key,(isset($data['meal']['step1'][$key]) ? $data['meal']['step1'][$key] : '')) == '4') selected @endif>میان وعده ۲</option>
                                                            <option value="5" @if(old('meal.step1.'.$key,(isset($data['meal']['step1'][$key]) ? $data['meal']['step1'][$key] : '')) == '5') selected @endif>شام</option>
                                                            <option value="6" @if(old('meal.step1.'.$key,(isset($data['meal']['step1'][$key]) ? $data['meal']['step1'][$key] : '')) == '6') selected @endif>میان وعده ۳</option>
                                                        </select>
                                                    </div>
                                                    <label  class="col-lg-2 control-label">انتخاب وعده</label>
                                                    <div style="clear: both"></div>
                                                </div>
                                                <div class="form-group">
                                                    <div class="col-lg-10">
                                                        <input name="calorie[step1][]" value="{{old('calorie.step1.'.$key,(isset($data['calorie']['step1'][$key]) ? $data['calorie']['step1'][$key] : ''))}}" type="text"  placeholder="کالری این وعده" class="is_calery_once_meal form-control">
                                                    </div>
                                                    <label  class="col-lg-2 control-label">کالری وعده</label>
                                                    <div style="clear: both"></div>
                                                </div>
                                                <div class="form-group mbn">
                                                    <div class="col-md-10 admin-form">
                                                        <div class="section">
                                                            <label class="field prepend-icon">
                                                                <textarea placeholder="متن دستور" name="content[step1][]" id="describtion" class="gui-textarea">{{old('content.step1.'.$key,(isset($data['content']['step1'][$key]) ? $data['content']['step1'][$key] : ''))}}</textarea>
                                                                <label class="field-icon" for="describtion"><i class="fa fa-comments"></i>
                                                                </label>
                                                                <span class="input-footer">
                                                                        <strong>توجه : </strong>این دستور فقط مربوط به همین وعده میباشد</span>
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <label for="tagmanager" class="col-md-2 control-label" style="padding-top: 5px;">دستور وعده</label>
                                                </div>
                                                <div class="form-group mbn">
                                                    <div class="col-md-10 base_food">
                                                        <input name="base_food[step1][]" type="text" value="{{old('base_food.step1.'.$key,(isset($data['base_food']['step1'][$key]) ? $data['base_food']['step1'][$key] : ''))}}" class="form-control tm-input-seo" placeholder="غذاهای اصلی این وعده">
                                                        <span class="help-block mt5"><i class="fa fa-bell"></i> برای اضافه شدن غذا دکمه Enter یا Tab را فشار دهید.</span>
                                                    </div>
                                                    <label  class="col-lg-2 control-label">غذاهای اصلی این وعده</label>
                                                </div>
                                            </div>
                                        @endforeach
                                    @endif

                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="type_rejim_2">
                        <div class="col-md-12 rtl yekan">
                            <div class="panel">
                                <div class="panel-heading">
                                    <span class="panel-title">وعده صبحانه</span>
                                </div>
                                <div class="panel-body">
                                    <div class="form-group">
                                        <div class="col-lg-10">
                                            <input name="calorie[step2][1]" value="{{old('calorie.step2.1',(isset($data['calorie']['step2']['1']) ? $data['calorie']['step2']['1'] : ''))}}" type="text" placeholder="کالری صبحانه" class="calorie_step2 form-control" id="inputLarge">
                                        </div>
                                        <label for="inputLarge" class="col-lg-2 control-label">کالری صبحانه</label>
                                        <div style="clear: both"></div>
                                    </div>
                                    <div class="form-group mbn">
                                        <div class="col-md-10 admin-form">
                                            <div class="section">
                                                <label class="field prepend-icon">
                                                    <textarea placeholder="متن دستور" name="content[step2][1]" id="describtion" class="gui-textarea">{{old('content.step2.1',(isset($data['content']['step2']['1']) ? $data['content']['step2']['1'] : ''))}}</textarea>
                                                    <label class="field-icon" for="describtion"><i class="fa fa-comments"></i>
                                                    </label>
                                                    <span class="input-footer">
                                                                <strong>توجه : </strong>این دستور فقط مربوط به صبحانه میباشد</span>
                                                </label>
                                            </div>
                                        </div>
                                        <label for="tagmanager" class="col-md-2 control-label" style="padding-top: 5px;">دستور وعده</label>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-lg-10">
                                            <textarea class="form-control textarea-grow" id="textArea1" rows="4" name="replace_food[step2][1]">{!! old('replace_food.step2.1',(isset($data['replace_food']['step2']['1']) ? $data['replace_food']['step2']['1'] : '')) !!}</textarea>
                                        </div>
                                        <label class="col-lg-2 control-label" for="textArea1">غذای جایگزین</label>
                                        <div style="clear: both"></div>
                                    </div>
                                    <div class="form-group mbn">
                                        <div class="col-md-10">
                                            <input value="{{old('base_food.step2.1',(isset($data['base_food']['step2']['1']) ? $data['base_food']['step2']['1'] : ''))}}" name="base_food[step2][1]" type="text" id="tagmanager1" class="form-control tm-input-seo" placeholder="کلمات کلیدی">
                                            <span class="help-block mt5"><i class="fa fa-bell"></i> برای اضافه شدن غذا دکمه Enter یا Tab را فشار دهید.</span>
                                        </div>
                                        <label for="inputLarge" class="col-lg-2 control-label">غذاهای اصلی</label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-12 rtl yekan">
                            <div class="panel ">
                                <div class="panel-heading">
                                    <span class="panel-title">میان وعده ۱۰ صبح</span>
                                </div>
                                <div class="panel-body">
                                    <div class="form-group">
                                        <div class="col-lg-10">
                                            <input  name="calorie[step2][2]" value="{{old('calorie.step2.2',(isset($data['calorie']['step2']['2']) ? $data['calorie']['step2']['2'] : ''))}}" type="text" placeholder="کالری میان وعده" class="calorie_step2 form-control" id="inputLarge2">
                                        </div>
                                        <label for="inputLarge2" class="col-lg-2 control-label">کالری</label>
                                        <div style="clear: both"></div>
                                    </div>
                                    <div class="form-group mbn">
                                        <div class="col-md-10 admin-form">
                                            <div class="section">
                                                <label class="field prepend-icon">
                                                    <textarea placeholder="متن دستور" name="content[step2][2]" id="describtion2" class="gui-textarea">{{old('content.step2.2',(isset($data['content']['step2']['2']) ? $data['content']['step2']['2'] : ''))}}</textarea>
                                                    <label class="field-icon" for="describtion2"><i class="fa fa-comments"></i>
                                                    </label>
                                                    <span class="input-footer">
                                                                <strong>توجه : </strong>این دستور فقط مربوط به میان وعده ۱۰ صبح میباشد</span>
                                                </label>
                                            </div>
                                        </div>
                                        <label for="tagmanager2" class="col-md-2 control-label" style="padding-top: 5px;">دستور وعده</label>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-lg-10">
                                            <textarea class="form-control textarea-grow" id="textArea2" rows="4" name="replace_food[step2][2]">{!! old('replace_food.step2.2',(isset($data['replace_food']['step2']['2']) ? $data['replace_food']['step2']['2'] : '')) !!}</textarea>
                                        </div>
                                        <label class="col-lg-2 control-label" for="textArea2">غذای جایگزین</label>
                                        <div style="clear: both"></div>
                                    </div>
                                    <div class="form-group mbn">
                                        <div class="col-md-10">
                                            <input value="{{old('base_food.step2.2',(isset($data['base_food']['step2']['2']) ? $data['base_food']['step2']['2'] : ''))}}" name="base_food[step2][2]" type="text" id="tagmanager2" class="form-control tm-input-seo" placeholder="کلمات کلیدی">
                                            <span class="help-block mt5"><i class="fa fa-bell"></i> برای اضافه شدن غذا دکمه Enter یا Tab را فشار دهید.</span>
                                        </div>
                                        <label for="inputLarge" class="col-lg-2 control-label">غذاهای اصلی</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12 rtl yekan">
                            <div class="panel">
                                <div class="panel-heading">
                                    <span class="panel-title">وعده نهار</span>
                                </div>
                                <div class="panel-body">
                                    <div class="form-group">
                                        <div class="col-lg-10">
                                            <input value="{{old('calorie.step2.3',(isset($data['calorie']['step2']['3']) ? $data['calorie']['step2']['3'] : ''))}}" name="calorie[step2][3]" type="text" placeholder="کالری نهار" class="calorie_step2 form-control" id="inputLarge3">
                                        </div>
                                        <label for="inputLarge3" class="col-lg-2 control-label">کالری  نهار</label>
                                        <div style="clear: both"></div>
                                    </div>
                                    <div class="form-group mbn">
                                        <div class="col-md-10 admin-form">
                                            <div class="section">
                                                <label class="field prepend-icon">
                                                    <textarea placeholder="متن دستور" name="content[step2][3]" id="describtion3" class="gui-textarea">{{old('content.step2.3',(isset($data['content']['step2']['3']) ? $data['content']['step2']['3'] : ''))}}</textarea>
                                                    <label class="field-icon" for="describtion3"><i class="fa fa-comments"></i>
                                                    </label>
                                                    <span class="input-footer">
                                                            <strong>توجه : </strong>این دستور فقط مربوط به نهار میباشد</span>
                                                </label>
                                            </div>
                                        </div>
                                        <label for="tagmanager3" class="col-md-2 control-label" style="padding-top: 5px;">دستور نهار</label>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-lg-10">
                                            <textarea class="form-control textarea-grow" id="textArea3" rows="4" name="replace_food[step2][3]">{!! old('replace_food.step2.3',(isset($data['replace_food']['step2']['3']) ? $data['replace_food']['step2']['3'] : '')) !!}</textarea>
                                        </div>
                                        <label class="col-lg-2 control-label" for="textArea3">غذای جایگزین</label>
                                        <div style="clear: both"></div>
                                    </div>
                                    <div class="form-group mbn">
                                        <div class="col-md-10">
                                            <input value="{{old('base_food.step2.3',(isset($data['base_food']['step2']['3']) ? $data['base_food']['step2']['3'] : ''))}}" name="base_food[step2][3]" type="text" id="tagmanager3" class="form-control tm-input-seo" placeholder="کلمات کلیدی">
                                            <span class="help-block mt5"><i class="fa fa-bell"></i> برای اضافه شدن غذا دکمه Enter یا Tab را فشار دهید.</span>
                                        </div>
                                        <label for="inputLarge" class="col-lg-2 control-label">غذاهای اصلی</label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-12 rtl yekan">
                            <div class="panel ">
                                <div class="panel-heading">
                                    <span class="panel-title">میان وعده عصر</span>
                                </div>
                                <div class="panel-body">
                                    <div class="form-group">
                                        <div class="col-lg-10">
                                            <input value="{{old('calorie.step2.4',(isset($data['calorie']['step2']['4']) ? $data['calorie']['step2']['4'] : ''))}}" name="calorie[step2][4]" type="text" placeholder="کالری میان وعده عصر" class="calorie_step2 form-control" id="inputLarge4">
                                        </div>
                                        <label for="inputLarge4" class="col-lg-2 control-label">کالری</label>
                                        <div style="clear: both"></div>
                                    </div>
                                    <div class="form-group mbn">
                                        <div class="col-md-10 admin-form">
                                            <div class="section">
                                                <label class="field prepend-icon">
                                                    <textarea placeholder="متن دستور" name="content[step2][4]" id="describtion4" class="gui-textarea">{{old('content.step2.4',(isset($data['content']['step2']['4']) ? $data['content']['step2']['4'] : ''))}}</textarea>
                                                    <label class="field-icon" for="describtion4"><i class="fa fa-comments"></i>
                                                    </label>
                                                    <span class="input-footer">
                                                            <strong>توجه : </strong>این دستور فقط مربوط به میان وعده عصر میباشد</span>
                                                </label>
                                            </div>
                                        </div>
                                        <label for="tagmanager4" class="col-md-2 control-label" style="padding-top: 5px;">دستور وعده</label>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-lg-10">
                                            <textarea class="form-control textarea-grow" id="textArea4" rows="4" name="replace_food[step2][4]">{!! old('replace_food.step2.4',(isset($data['replace_food']['step2']['4']) ? $data['replace_food']['step2']['4'] : '')) !!}</textarea>
                                        </div>
                                        <label class="col-lg-2 control-label" for="textArea4">غذای جایگزین</label>
                                        <div style="clear: both"></div>
                                    </div>
                                    <div class="form-group mbn">
                                        <div class="col-md-10">
                                            <input value="{{old('base_food.step2.4',(isset($data['base_food']['step2']['4']) ? $data['base_food']['step2']['4'] : ''))}}" name="base_food[step2][4]" type="text" id="tagmanager4" class="form-control tm-input-seo" placeholder="کلمات کلیدی">
                                            <span class="help-block mt5"><i class="fa fa-bell"></i> برای اضافه شدن غذا دکمه Enter یا Tab را فشار دهید.</span>
                                        </div>
                                        <label for="inputLarge" class="col-lg-2 control-label">غذاهای اصلی</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12 rtl yekan">
                            <div class="panel">
                                <div class="panel-heading">
                                    <span class="panel-title">وعده شام</span>
                                </div>
                                <div class="panel-body">
                                    <div class="form-group">
                                        <div class="col-lg-10">
                                            <input value="{{old('calorie.step2.5',(isset($data['calorie']['step2']['5']) ? $data['calorie']['step2']['5'] : ''))}}" name="calorie[step2][5]" type="text" placeholder="کالری شام" class="calorie_step2 form-control" id="inputLarge5">
                                        </div>
                                        <label for="inputLarge5" class="col-lg-2 control-label">کالری  شام</label>
                                        <div style="clear: both"></div>
                                    </div>
                                    <div class="form-group mbn">
                                        <div class="col-md-10 admin-form">
                                            <div class="section">
                                                <label class="field prepend-icon">
                                                    <textarea placeholder="متن دستور" name="content[step2][5]" id="describtion5" class="gui-textarea">{{old('content.step2.5',(isset($data['content']['step2']['5']) ? $data['content']['step2']['5'] : ''))}}</textarea>
                                                    <label class="field-icon" for="describtion5"><i class="fa fa-comments"></i>
                                                    </label>
                                                    <span class="input-footer">
                                                        <strong>توجه : </strong>این دستور فقط مربوط به شام میباشد</span>
                                                </label>
                                            </div>
                                        </div>
                                        <label for="tagmanager5" class="col-md-2 control-label" style="padding-top: 5px;">دستور شام</label>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-lg-10">
                                            <textarea class="form-control textarea-grow" id="textArea5" rows="4" name="replace_food[step2][5]">{!! old('replace_food.step2.5',(isset($data['replace_food']['step2']['5']) ? $data['replace_food']['step2']['5'] : '')) !!}</textarea>
                                        </div>
                                        <label class="col-lg-2 control-label" for="textArea5">غذای جایگزین</label>
                                        <div style="clear: both"></div>
                                    </div>
                                    <div class="form-group mbn">
                                        <div class="col-md-10">
                                            <input value="{{old('base_food.step2.5',(isset($data['base_food']['step2']['5']) ? $data['base_food']['step2']['5'] : ''))}}" name="base_food[step2][5]" type="text" id="tagmanager5" class="form-control tm-input-seo" placeholder="کلمات کلیدی">
                                            <span class="help-block mt5"><i class="fa fa-bell"></i> برای اضافه شدن غذا دکمه Enter یا Tab را فشار دهید.</span>
                                        </div>
                                        <label for="inputLarge" class="col-lg-2 control-label">غذاهای اصلی</label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-12 rtl yekan">
                            <div class="panel ">
                                <div class="panel-heading">
                                    <span class="panel-title">میان وعده بعد از شام</span>
                                </div>
                                <div class="panel-body">
                                    <div class="form-group">
                                        <div class="col-lg-10">
                                            <input value="{{old('calorie.step2.6',(isset($data['calorie']['step2']['6']) ? $data['calorie']['step2']['6'] : ''))}}" name="calorie[step2][6]" type="text" placeholder="کالری میان وعده بعد از شام" class="calorie_step2 form-control" id="inputLarge6">
                                        </div>
                                        <label for="inputLarge6" class="col-lg-2 control-label">کالری شام</label>
                                        <div style="clear: both"></div>
                                    </div>
                                    <div class="form-group mbn">
                                        <div class="col-md-10 admin-form">
                                            <div class="section">
                                                <label class="field prepend-icon">
                                                    <textarea placeholder="متن دستور" name="content[step2][6]" id="describtion6" class="gui-textarea">{{old('content.step2.6',(isset($data['content']['step2']['6']) ? $data['content']['step2']['6'] : ''))}}</textarea>
                                                    <label class="field-icon" for="describtion6"><i class="fa fa-comments"></i>
                                                    </label>
                                                    <span class="input-footer">
                                                    <strong>توجه : </strong>این دستور فقط مربوط به میان وعده بعد از شام میباشد</span>
                                                </label>
                                            </div>
                                        </div>
                                        <label for="tagmanager6" class="col-md-2 control-label" style="padding-top: 5px;">دستور وعده</label>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-lg-10">
                                            <textarea class="form-control textarea-grow" id="textArea6" rows="4" name="replace_food[step2][6]">{!! old('replace_food.step2.6',(isset($data['replace_food']['step2']['6']) ? $data['replace_food']['step2']['6'] : '')) !!}</textarea>
                                        </div>
                                        <label class="col-lg-2 control-label" for="textArea6">غذای جایگزین</label>
                                        <div style="clear: both"></div>
                                    </div>
                                    <div class="form-group mbn">
                                        <div class="col-md-10">
                                            <input value="{{old('base_food.step2.6',(isset($data['base_food']['step2']['6']) ? $data['base_food']['step2']['6'] : ''))}}" name="base_food[step2][6]" type="text" id="tagmanager6" class="form-control tm-input-seo" placeholder="کلمات کلیدی">
                                            <span class="help-block mt5"><i class="fa fa-bell"></i> برای اضافه شدن غذا دکمه Enter یا Tab را فشار دهید.</span>
                                        </div>
                                        <label for="inputLarge" class="col-lg-2 control-label">غذاهای اصلی</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="type_rejim_3">
                        <div class="col-md-12 rtl yekan">
                            <div class="panel">
                                <div class="panel-heading">
                                    <span class="panel-title">برنامه کامل یک روز</span>
                                </div>
                                <div class="panel-body">
                                    <div class="form-group">
                                        <div class="col-lg-10">
                                            <input value="{{old('calorie.step3.0')}}" name="calorie[step3][]" type="text" placeholder="کالری کل روز" class="form-control" id="inputLarge">
                                        </div>
                                        <label for="inputLarge" class="col-lg-2 control-label">کالری کل روز</label>
                                        <div style="clear: both"></div>
                                    </div>
                                    <div class="form-group mbn">
                                        <div class="col-md-10 admin-form">
                                            <div class="section">
                                                <textarea class="summernote" id="hometext" name="content[step3][0]" >{{old('content.step3.0')}}</textarea>
                                            </div>
                                        </div>
                                        <label for="tagmanager" class="col-md-2 control-label" style="padding-top: 5px;">دستور رژیم روز</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>






                </div>
            </form>
        </div>
    </div>
    <!-- Panel popup -->
    <div id="modal-panel" class="popup-basic bg-none mfp-with-anim mfp-hide rtl yekan">

        <div class="panel">
            <div class="panel-heading" style="text-align: right">
                            <span class="panel-icon"><i class="fa fa-pencil"></i>
                            </span>
                <span class="panel-title">حذف کاربر</span>
            </div>
            <div class="panel-body" style="text-align: right">
                <h3 class="mt5">آیا از حذف این کاربر اطمینان دارید؟</h3>
            </div>
            <div class="panel-footer text-left">
                <a id="delete_link">
                    <button class="btn btn-primary" type="button">بله حذف کن</button>
                </a>
            </div>
        </div>
    </div>
    <!-- End: Content -->
@endsection
@push('scripts')
    <style>
        #type_rejim_1,#type_rejim_2,#type_rejim_3{
            display:none;
        }
        #cover_once_meal{
            position:relative;
        }
        .add_meal_once_fixed{
            position: fixed;
            top:50px;
            left: 13%;
            z-index: 1500;
        }
        #add_meal_once{
            position: relative;
            float: left;
        }
        #start_meal_once,.list_plus_rejim{
            position: relative;
        }
        .remove_meal {
            background-color: #1e1e1e;
            border-radius: 3px;
            float: left;
            height: 21px;
            opacity: 0.8;
            padding: 2px 4px 0 0;
            width: 21px;
        }
        .remove_meal span{
            color: #ffffff;
        }
        .remove_meal span:hover{
            color: red;
        }

    </style>
    <style>
        .admin-form .switch > input:checked + label::after{
            margin-top:-1px;
        }
        .admin-form .switch > label::after{
            margin-top:-1px;
        }
        .remove{
            background: black none repeat scroll 0 0;
            color: white;
            font-size: 20px;
            left: 21px;
            padding: 5px;
            position: absolute;
            top: 0;
            z-index: 2;
        }
        .remove:hover{
            color:#c0c0c0;
        }
    </style>
    <link rel="stylesheet" type="text/css" href="{{ asset('admin/files/vendor/plugins/magnific/magnific-popup.css') }}">
    <!-- Page Plugins -->
    <script type="text/javascript" src="{{ asset('admin/files/vendor/plugins/magnific/jquery.magnific-popup.js') }}"></script>
    <!-- Admin Modals CSS -->
    <link rel="stylesheet" type="text/css" href="{{ asset('admin/files/assets/admin-tools/admin-plugins/admin-modal/adminmodal.css') }}">
    <!-- Summernote CSS  -->
    <link rel="stylesheet" type="text/css" href="{{ asset('admin/files/vendor/editors/summernote/summernote.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('admin/files/vendor/editors/summernote/summernote-bs3.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('admin/files/assets/css/jquery.tagit.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('admin/files/assets/css/tagit.ui-zendesk.css') }}">

    <!-- Summernote JS -->
    <script type="text/javascript" src="{{ asset('admin/files/vendor/editors/summernote/summernote.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('admin/files/vendor/editors/summernote/summernote-fa-IR.js') }}"></script>

    <script type="text/javascript" src="{{ asset('admin/files/assets/js/tag-it.js') }}"></script>
    <script type="text/javascript" src="{{ asset('admin/files/vendor/plugins/ladda/ladda.min.js') }}"></script>
    <link rel="stylesheet" type="text/css" href="{{ asset('admin/files/vendor/plugins/ladda/ladda.min.css') }}">
    <script type="text/javascript">
        jQuery(window).load(function () {
            @unless(old('submit') || Request::is('admin/meal/edit/*'))
            $("#add_meal_once").triggerHandler('click');
            @endunless
        });
        jQuery(document).ready(function() {



            /******* ----add meal -----******/
            var i = 0;


            $("body").on('click','.remove_meal',function () {
                // alert('salam');
                $(this).closest('.list_plus_rejim').slideDown('normal',function () {
                    $(this).remove();
                });

                return false;
            });
            $("#add_meal_once").on('click',function () {
                i++;
                var baseCopy = ' <div class="col-md-12 row list_plus_rejim number-'+i+' meal-once"><div style="clear:both"></div><hr/><a class="remove_meal" href="#"><span class="glyphicons glyphicons-remove_2"></span></a> شماره '+i+'<br /><br /> <div class="form-group"> <div class="col-lg-10"> <select name="meal[step1][]" class="multiselect1"> <option value="">انتخاب کنید</option> <option value="1">صبحانه</option> <option value="2">میان وعده ۱</option> <option value="3">نهار</option> <option value="4">میان وعده ۲</option> <option value="5">شام</option> <option value="6">میان وعده ۳</option> </select> </div><label class="col-lg-2 control-label">انتخاب وعده</label> <div style="clear: both"></div></div><div class="form-group"> <div class="col-lg-10"> <input value="" name="calorie[step1][]" type="text" placeholder="کالری این وعده" class="is_calery_once_meal form-control"> </div><label class="col-lg-2 control-label">کالری وعده</label> <div style="clear: both"></div></div><div class="form-group mbn"> <div class="col-md-10 admin-form"> <div class="section"> <label class="field prepend-icon"> <textarea placeholder="متن دستور" name="content[step1][]" id="describtion" class="gui-textarea"></textarea> <label class="field-icon" for="describtion"><i class="fa fa-comments"></i> </label> <span class="input-footer"> <strong>توجه : </strong>این دستور فقط مربوط به همین وعده میباشد</span> </label> </div></div><label for="tagmanager" class="col-md-2 control-label" style="padding-top: 5px;">دستور وعده</label> </div><div class="form-group mbn"> <div class="col-md-10 base_food"> <input value="" name="base_food[step1][]" type="text" class="form-control tm-input-seo" placeholder="غذاهای اصلی این وعده"> <span class="help-block mt5"><i class="fa fa-bell"></i> برای اضافه شدن غذا دکمه Enter یا Tab را فشار دهید.</span> </div><label class="col-lg-2 control-label">غذاهای اصلی این وعده</label> </div></div>';
                $("#cover_once_meal").append(baseCopy);
                $(".tm-input-seo").tagit({
                    allowSpaces: true,
                    minLength: 2,
                    removeConfirmation: true,
                });
                $('.multiselect1').multiselect({
                    buttonClass: 'multiselect dropdown-toggle btn btn-default btn-danger'
                });

                if(i > 1){
                    $('html, body').animate({
                        scrollTop: $( '.number-'+i).offset().top+200
                    }, 500);
                }

            });

            $(window).scroll(function(){
                if ($(this).scrollTop() > 270) {
                    $("#add_meal_once").css({"position":"absolute","top":$(this).scrollTop()-250 +"px","z-index":"150","left":"0px"});
                } else {
                    $("#add_meal_once").css({"position":"relative","top":"auto","z-index":"1500","left":"auto"});
                }
            });
            /******* ----add meal -----******/
            /******* -- upload image -------*/

            $('.multiselect1').multiselect({
                buttonClass: 'multiselect dropdown-toggle btn btn-default btn-danger'
            });

            $('#multiselect2').multiselect({
                includeSelectAllOption: true
            });

            // Init Summernote
            var noteStyle = 'success';
            var noteShadow = true;
            var noteOpacity ='1';
            var noteStack = "stack_bottom_left";
            var width = "290px";
            var Stacks = {
                stack_top_right: {
                    "dir1": "down",
                    "dir2": "left",
                    "push": "top",
                    "spacing1": 10,
                    "spacing2": 10
                },
                stack_top_left: {
                    "dir1": "down",
                    "dir2": "right",
                    "push": "top",
                    "spacing1": 10,
                    "spacing2": 10
                },
                stack_bottom_left: {
                    "dir1": "right",
                    "dir2": "up",
                    "push": "top",
                    "spacing1": 10,
                    "spacing2": 10
                },
                stack_bottom_right: {
                    "dir1": "left",
                    "dir2": "up",
                    "push": "top",
                    "spacing1": 10,
                    "spacing2": 10
                },
                stack_bar_top: {
                    "dir1": "down",
                    "dir2": "right",
                    "push": "top",
                    "spacing1": 0,
                    "spacing2": 0
                },
                stack_bar_bottom: {
                    "dir1": "up",
                    "dir2": "right",
                    "spacing1": 0,
                    "spacing2": 0
                },
                stack_context: {
                    "dir1": "down",
                    "dir2": "left",
                    "context": $("#stack-context")
                },
            }

            function sendFile(file, editor, welEditable) {

                data = new FormData();
                data.append("file", file);
                data.append("_token", "{{ csrf_token() }}");
                $.ajax({
                    data: data,
                    type: "POST",
                    url: "{{ url('admin/blog/upload') }}",
                    cache: false,
                    contentType: false,
                    timeout: 3000000,
                    processData: false,
                    success: function(url) {
                        new PNotify({
                            title: 'آپلود',
                            text: 'فایل با موفقیت بارگزاری شد.',
                            shadow: noteShadow,
                            opacity: noteOpacity,
                            addclass: noteStack,
                            type: noteStyle,
                            stack: Stacks[noteStack],
                            width: width,
                            delay: 3000,
                            addclass:'rtl yekan'
                        });
                        editor.insertImage(welEditable, url);
                    },
                }).fail(function(){
                    new PNotify({
                        title: 'آپلود',
                        text: 'متاسفانه فایل آپلود نشد.<br/>توجه داشته باشید فقط فایل تصویری مجاز است',
                        shadow: noteShadow,
                        opacity: noteOpacity,
                        addclass: noteStack,
                        type: 'danger',
                        stack: Stacks[noteStack],
                        width: width,
                        delay: 5000,
                        addclass:'rtl yekan'
                    });
                });
            }
            $('.summernote').summernote({
                lang: 'fa-IR', // default: 'en-US'
                height: 255, //set editable area's height
                focus: false, //set focus editable area after Initialize summernote
                oninit: function() {},
                onChange: function(contents, $editable) {},
                onImageUpload: function(files, editor, welEditable) {
                    sendFile(files[0], editor, welEditable);
                }
            });
            $(".tm-input-seo").tagit({
                allowSpaces: true,
                minLength: 2,
                removeConfirmation: true,
            });
            $(".tm-input").tagit({
                singleField: true,
                singleFieldNode: $('#mySingleField'),
                allowSpaces: true,
                minLength: 2,
                removeConfirmation: true,
                tagSource: function( request, response ) {
                    //console.log("1");
                    $.ajax({
                        url: "{{ url('/admin/blog/tag') }}",
                        data: { term:request.term },
                        dataType: "json",
                        success: function( data ) {
                            response( $.map( data, function( item ) {
                                return {
                                    label: item.name,
                                    value: item.name
                                }
                            }));
                        }
                    });
                }});
        });
    </script>

    <script>
        (function($){

            $(function(){

                checkStatusRejimDiv();
                $("#categoryRejim :input").on('change',function () {
                    var isValue = $(this).attr('data-type');
                    $("#type_rejim_" + isValue).show();
                    checkInput();
                });
                function checkStatusRejimDiv(){
                    var isValue = $("#categoryRejim :input:checked").attr('data-type');
                    $("#type_rejim_" + isValue).show();
                    checkInput();
                }
                function checkInput() {
                    var value = [];
                    $("#categoryRejim :input:checked").each(function(){
                        var input = $(this); // This is the jquery object of the input, do what you will
                        value.push(input.attr('data-type'));
                    });
                    if(value.length == 0){
                        $("#type_rejim_1,#type_rejim_2,#type_rejim_3").hide();
                    }
                    else{
                        for (i = 0; i < value.length; i++) {
                            if(i>0){
                                if(value[i-1] != value[i]){
                                    $("#categoryRejim :input:checked").prop('checked', false);
                                    $("#type_rejim_1,#type_rejim_2,#type_rejim_3").hide();
                                    alert('حتما میبایست گروه های انتخاب شده از یک نوع الگوی غذایی پیروی کنند');
                                    break;
                                }
                            }
                        }
                    }
                }

            });

        })(jQuery)

    </script>
@endpush