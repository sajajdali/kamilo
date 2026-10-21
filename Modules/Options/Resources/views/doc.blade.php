<div id="wrapper">
    <div class="container">
        <section id="top" class="section docs-heading">
            <div class="row">
                <div class="col-md-12">
                    <div class="big-title text-center">
                        <h1>مستندات ماژول تنظیمات</h1>
                    </div>
                    <!-- end title -->
                </div>
                <!-- end 12 -->
            </div>
            <!-- end row -->
            <hr>
        </section>
        <!-- end section -->
        <div class="row">
            <div class="col-md-3 float-right">
                <nav class="docs-sidebar" data-spy="affix" data-offset-top="300" data-offset-bottom="200" role="navigation">
                    <ul class="nav">
                        <li>
                            <a href="#start">شروع به کار</a>
                        </li>
                        <li>
                            <a href="#howto">قالب تنظیمات</a>
                        </li>
                        <li>
                            <a href="#functions">توابع</a>
                        </li>
                        <li>
                            <a href="#foreign">استفاده خارجی</a>
                        </li>
                        <li>
                            <a href="#list_item">گزینه های تکرار شونده</a>
                        </li>
                    </ul>
                </nav>
            </div>
            <div class="col-md-9 float-right">
                <section class="section" id="start">
                    <div class="row">
                        <div class="col-md-12 left-align">
                            <h2 class="dark-text">شروع کار
                                <hr>
                            </h2>
                            <div class="row">
                                <div class="col-md-12 full">
                                    <div class="intro1">
                                        <ul>
                                            <li><strong>نام ماژول : </strong>تنظیمات(Options)</li>
                                            <li><strong>تاریخ انتشار : </strong>2018/7/24</li>
                                            <li><strong>تست شده تا لاراول : </strong> v 5.6</li>
                                            <li><strong>توسط : </strong>
                                                <a href="http://weberz.ir" target="_blank">Payam Jafari</a></li>
                                        </ul>
                                    </div>
                                    <hr>
                                    <div>
                                        <p>با استفاده از این ماژول یک گزینه به منو ادمین اضافه میشود تا تنظیمات دلخواه خود را در درون آن قرار دهیم.</p>
                                        <p>پس از قرار دادن ماژول در درون پوشه modules شما باید در کنسول یا ترمینال دستور زیر را وارد نمایید.</p>
<pre><code class="php">
{!!
htmlspecialchars( 'php artisan module:publish options' )
!!}

</code></pre>
                                        <p>سپس برای قرار گرفتن دسترسی ها و نقش های مخصوص دستور زیر را وارد نمایید</p>
<pre><code class="php">
{!!
htmlspecialchars( 'php artisan auth:permission options' )
!!}

</code></pre>
                                        <p>و بعد برای ساختن table مورد نظر برای این ماژول دستور زیر را وارد نمایید</p>
<pre><code class="php">
{!!
htmlspecialchars( 'php artisan module:migrate options' )
!!}

</code></pre>
                                    </div>
                                </div>
                            </div>
                            <!-- end row -->
                        </div>
                    </div>
                </section>
                <hr>
                <section class="section" id="howto">
                    <div class="row">
                        <div class="col-md-12 left-align">
                            <h2 class="dark-text">چگونه تنظیمات خود را اضافه کنیم
                                <hr>
                            </h2>
                            <div class="row">
                                <div class="col-md-12 full">
                                    <div>
                                        <p>برای قرار دادن تنظیمات دلخواه خود در صفحه تنظیمات میتوانید فایل Options.php در پوشه Config را دستکاری نمایید</p>
                                        <p>همچنین برای قرار دادن تنظیمات مربوط به هر ماژول میتوانید یک فایل با همین نام در پوشه Config هر ماژول بسازید و مقادیر دلخواه خود را در درون آن قرار دهید</p>
                                        <pre><code class="php">
{!!
htmlspecialchars("
return [
    'menu'    => [
        گزینه های منو در این قسمت قرار میگیرند
    ],
    'content' => [
        محتوا های تنظیمات در این قسمت قرار میگیرند
    ],
];
")
!!}

</code></pre>
                                        <p>نمونه ساختن یک منو را در کد زیر مشاهده میکنید</p>
<pre><code class="php">
{!!
htmlspecialchars("
return [
    'menu'    => [
        [
          'id'             => 'general', // آی دی یکتا
          'title'          => 'تنظیمات عمومی',
          'icon'           => 'home', // بر اساس font awesome
          'children'       => [],
          'class'          => '',
          'before_content' => '',
          'after_content'  => '',
        ],
    ],
    'content' => [
        ...
    ],
];
")
!!}

</code></pre>
                                        <p>نمونه ساختن یک محتوا برای هر منو را مشاهده میکنید</p>
                                        <p><strong>توجه داشته باشید مقدار id در منو و محتوا باید برابر باشند</strong>. به عنوان مثال در کد بالا و پایین مقدار id برابر با general است</p>
<pre><code class="php">
{!!
htmlspecialchars("
return [
    'menu'    => [
        ...
    ],
    'content' => [
        [
          'id'             => 'general', // آی دی یکتا
          'title'          => 'تنظیمات عمومی',
          'class'          => '',
          'before_content' => '',
          'after_content'  => '',
          'settings'       => ... // تنظیمات مربوط به این قسمت مثلا input یا checkbox
        ]
    ],
];
")
!!}

</code></pre>
                                        <br>
                                        <p>ساختار های مختلف تنظیمات را در جدول زیر مشاهده میکنید</p>
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>نام</th>
                                                    <th>پارامتر ها</th>
                                                    <th>توضیحات</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>input</td>
                                                    <td>
                                                        class [string]: کلاس css اختصاصی <hr>
                                                        type [string]: نوع ساختار مثل number,email,url <hr>
                                                        name [string]: مقدار خاصیت name <hr>
                                                        placeholder [string]: مقدار خاصیت placeholder
                                                    </td>
                                                    <td>برای قرار دادن پارامتر ها کافیست آنها را در آرایه ای به نام input_args قرار دهید</td>
                                                </tr>
                                                <tr>
                                                    <td>textarea</td>
                                                    <td>
                                                        class [string]: کلاس css اختصاصی <hr>
                                                        name [string]: مقدار خاصیت name <hr>
                                                        placeholder [string]: مقدار خاصیت placeholder
                                                    </td>
                                                    <td>برای قرار دادن پارامتر ها کافیست آنها را در آرایه ای به نام input_args قرار دهید</td>
                                                </tr>
                                                <tr>
                                                    <td>radio</td>
                                                    <td>
                                                        class [string]: کلاس css اختصاصی <hr>
                                                        name [string]: مقدار خاصیت name <hr>
                                                        items [array]: انتخاب ها
                                                    </td>
                                                    <td>برای قرار دادن پارامتر ها کافیست آنها را در آرایه ای به نام input_args قرار دهید</td>
                                                </tr>
                                                <tr>
                                                    <td>select<br>dropdown</td>
                                                    <td>
                                                        class [string]: کلاس css اختصاصی <hr>
                                                        name [string]: مقدار خاصیت name <hr>
                                                        items [array]: انتخاب ها
                                                    </td>
                                                    <td>برای قرار دادن پارامتر ها کافیست آنها را در آرایه ای به نام input_args قرار دهید<br>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>checkbox<br>switch</td>
                                                    <td>
                                                        class [string]: کلاس css اختصاصی <hr>
                                                        name [string]: مقدار خاصیت name
                                                    </td>
                                                    <td>برای قرار دادن پارامتر ها کافیست آنها را در آرایه ای به نام input_args قرار دهید</td>
                                                </tr>
                                                <tr>
                                                    <td>image</td>
                                                    <td>
                                                        name [string]: مقدار خاصیت name
                                                    </td>
                                                    <td>این ساختار یک گزینه برای آپلود تصویر میسازد و آی دی فایل را ذخیره میکند</td>
                                                </tr>
                                                <tr>
                                                    <td>file</td>
                                                    <td>
                                                        name [string]: مقدار خاصیت name
                                                    </td>
                                                    <td>این ساختار یک گزینه برای آپلود فایل میسازد و آی دی فایل را ذخیره میکند</td>
                                                </tr>
                                                <tr>
                                                    <td>list_item</td>
                                                    <td>
                                                        <a href="#list_item">برای مشاهده توضیحات مربوط به این ساختار لطفا کلیک کنید</a>
                                                    </td>
                                                    <td>برای قرار دادن پارامتر ها کافیست آنها را در آرایه ای به نام list_item_args قرار دهید</td>
                                                </tr>
                                                <tr>
                                                    <td>custom</td>
                                                    <td>
                                                        custom [callable]: یک تابع به این پارامتر معرفی میشود
                                                    </td>
                                                    <td>در این ساختار شما کافیست یک تابع را به پارامتری به نام custom بدهید تا محتویات آن اجرا شود</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                        <p>نمونه ی تمامی ساختار های گفته شده در جدول بالا را در کد زیر میتوانید مشاهده کنید</p>
                                        <a class="btn btn-info pull-left clearfix show_bellow_code">مشاهده کد</a>
                                        <pre><code class="php">
{!!
htmlspecialchars("
return [
    'menu'    => [
        ...
    ],
    'content' => [
        [
          'id'             => 'general', // آی دی یکتا
          'title'          => 'تنظیمات عمومی',
          'class'          => '',
          'before_content' => '',
          'after_content'  => '',
          'settings'       => [
                [ // input example setting
                  'title'          => 'عنوان تست',
                  'value'          => get_option( 'test_name' ),
                  'type'           => 'input',
                  'input_args'     => [
                      'name' => 'test_name',
                      'class' => 'custom_class',
                      'type' => 'text',
                      'placeholder' => 'مقدار را در اینجا وارد نمایید',
                  ],
                  'class'          => '',
                  'id'             => '',
                  'description'    => '',
                  'before_content' => '',
                  'after_content'  => '',
                ],
                [ // textarea example setting
                  'title'          => 'عنوان تست',
                  'value'          => get_option( 'test_name' ),
                  'type'           => 'textarea',
                  'input_args'     => [
                      'name' => 'test_name',
                      'class' => 'custom_class',
                      'placeholder' => 'مقدار را در اینجا وارد نمایید',
                  ],
                  'class'          => '',
                  'id'             => '',
                  'description'    => '',
                  'before_content' => '',
                  'after_content'  => '',
                ],
                [ // radio example setting
                  'title'          => 'عنوان تست',
                  'value'          => get_option( 'test_name' ),
                  'type'           => 'radio',
                  'input_args'     => [
                      'name' => 'test_name',
                      'class' => 'custom_class',
                      'items' => [
                        'value1' => 'عنوان 1',
                        'value2' => 'عنوان 2',
                        'value3' => 'عنوان 3',
                      ]
                  ],
                  'class'          => '',
                  'id'             => '',
                  'description'    => '',
                  'before_content' => '',
                  'after_content'  => '',
                ],
                [ // select example setting
                  'title'          => 'عنوان تست',
                  'value'          => get_option( 'test_name' ),
                  'type'           => 'select',
                  'input_args'     => [
                      'name' => 'test_name',
                      'class' => 'custom_class',
                      'items' => [
                        'value1' => 'عنوان 1',
                        'value2' => 'عنوان 2',
                        'value3' => 'عنوان 3',
                      ]
                  ],
                  'class'          => '',
                  'id'             => '',
                  'description'    => '',
                  'before_content' => '',
                  'after_content'  => '',
                ],
                [ // checkbox example setting
                  'title'          => 'عنوان تست',
                  'value'          => get_option( 'test_name' ),
                  'type'           => 'checkbox',
                  'input_args'     => [
                      'name' => 'test_name',
                      'class' => 'custom_class',
                  ],
                  'class'          => '',
                  'id'             => '',
                  'description'    => '',
                  'before_content' => '',
                  'after_content'  => '',
                ],
                [ // image example setting
                  'title'          => 'عنوان تست',
                  'value'          => get_option( 'test_name' ),
                  'type'           => 'image',
                  'input_args'     => [
                      'name' => 'test_name',
                  ],
                  'class'          => '',
                  'id'             => '',
                  'description'    => '',
                  'before_content' => '',
                  'after_content'  => '',
                ],
                [ // list_item example setting
                  'title'          => 'عنوان تست',
                  'value'          => '',
                  'type'           => 'list_item',
                  'list_item_args' => [
                      'fields' => array(
                          'title' => array(
                              'title' => 'عنوان',
                              'type' => 'input',
                              'name' => 'sliders[title][]',
                          ),
                          'url' => array(
                              'title' => 'لینک اسلایدر',
                              'type' => 'input',
                              'name' => 'sliders[url][]',
                          ),
                          'image' => array(
                              'title' => 'تصویر',
                              'type' => 'image',
                              'name' => 'sliders[image][]',
                          ),
                      ),
                      'items' => get_option( 'sliders' ),
                      'singular_name' => 'اسلاید',
                  ],
                  'class'          => '',
                  'id'             => '',
                  'description'    => '',
                  'before_content' => '',
                  'after_content'  => '',
                ],
                [ // custom example setting
                  'title'          => 'عنوان تست',
                  'value'          => get_option( 'test_name' ),
                  'type'           => 'custom',
                  'custom'         => function(){
                    echo 'test';
                  },
                  'class'          => '',
                  'id'             => '',
                  'description'    => '',
                  'before_content' => '',
                  'after_content'  => '',
                ],
          ]
        ]
    ],
];
")
!!}

</code></pre>
                                    </div>
                                </div>
                            </div>
                            <!-- end row -->
                        </div>
                    </div>
                </section>
                <section class="section" id="functions">
                    <div class="row">
                        <div class="col-md-12 left-align">
                            <h2 class="dark-text">توابع
                                <hr>
                            </h2>
                            <div class="row">
                                <div class="col-md-12 full">
                                    <div>
                                        <pre><code class="php">
{!!
'<span class="hljs-literal">get_option</span>( <strong class="hljs-name">string</strong> $option, <strong class="hljs-name">mixed</strong> $default = false )'
!!}
                                        </code></pre>
                                        <p>با استفاده از این تابع میتوانیم مقدار یک گزینه را دریافت کنیم</p>
                                        <hr>
                                        <pre><code class="php">
{!!
'<span class="hljs-literal">add_option</span>( <strong class="hljs-name">string</strong> $name, <strong class="hljs-name">mixed</strong> $value = \'\' )'
!!}
                                        </code></pre>
                                        <p>با استفاده از این تابع میتوانیم یک گزینه را به دیتابیس Options اضافه کنیم</p>
                                        <hr>
                                        <pre><code class="php">
{!!
'<span class="hljs-literal">update_option</span>( <strong class="hljs-name">string</strong> $name, <strong class="hljs-name">mixed</strong> $value = \'\' )'
!!}
                                        </code></pre>
                                        <p>با استفاده از این تابع میتوانیم یک گزینه را ویرایش کنیم</p>
                                        <p><strong> اگر در هنگام اجرای این تابع گزینه وارد شده وجود نداشته باشد. تابع یک گزینه با همان نام میسازد</strong></p>
                                    </div>
                                </div>
                            </div>
                            <!-- end row -->
                        </div>
                    </div>
                </section>
                <section class="section" id="foreign">
                    <div class="row">
                        <div class="col-md-12 left-align">
                            <h2 class="dark-text">استفاده از تنظیمات در خارج از ماژول
                                <hr>
                            </h2>
                            <div class="row">
                                <div class="col-md-12 full">
                                    <div>
                                        <p>اگر میخواهید از فیلد های موجود در صفحه ی تنظیمات ماژول در جای دیگری استفاده کنید میتوانید از یک کلاس به نام OptionsBuilder استفاده کنید</p>
                                        <p>تنها کافیست برای استفاده از توابع این کلاس کد زیر را در بالای فایل خود وارد نمایید</p>
                                        <pre><code class="php">
{!!
'use Modules\Options\Helpers\OptionsBuilder;'
!!}
                                        </code></pre>
                                        <p>سپس با یک نمونه سازی از این کلاس، از متود های درون آن استفاده کنید</p>
                                        <pre><code class="php">
{!!
'
$OptionsBuilder = new OptionsBuilder;

echo $OptionsBuilder->make_selectbox( "name", "value", [
    "choice1" => "انتخاب اول",
    "choice2" => "انتخاب دوم",
    "choice3" => "انتخاب سوم",
], "custom_css_class" );
'
!!}
                                        </code></pre>
                                        <table class="table table-bordered">
                                            <thead>
                                            <tr>
                                                <th>نام</th>
                                                <th>پارامتر ها</th>
                                                <th>توضیحات</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <tr>
                                                <td>make_radio_buttons</td>
                                                <td dir="ltr">
                                                    $name [string]: <span dir="rtl">خاصیت name برای input</span> <hr>
                                                    $value [mixed]: <span dir="rtl">آخرین مقدار</span> <hr>
                                                    $items [array]: <span dir="rtl">گزینه ها به صورت آرایه</span> <hr>
                                                    $class [string]: <span dir="rtl">کلاس css</span>
                                                </td>
                                                <td>با این تابع میتوانید یک انتخاب رادیویی بسازید</td>
                                            </tr>
                                            <tr>
                                                <td>make_selectbox</td>
                                                <td dir="ltr">
                                                    $name [string]: <span dir="rtl">خاصیت name برای select</span> <hr>
                                                    $value [mixed]: <span dir="rtl">آخرین مقدار</span> <hr>
                                                    $items [array]: <span dir="rtl">گزینه ها به صورت آرایه</span> <hr>
                                                    $class [string]: <span dir="rtl">کلاس css</span>
                                                </td>
                                                <td>با این تابع میتوانید یک انتخاب لیستی بسازید</td>
                                            </tr>
                                            <tr>
                                                <td>make_custom_checkbox</td>
                                                <td dir="ltr">
                                                    $name [string]: <span dir="rtl">خاصیت name برای input</span> <hr>
                                                    $value [mixed]: <span dir="rtl">آخرین مقدار</span> <hr>
                                                    $class [string]: <span dir="rtl">کلاس css</span>
                                                </td>
                                                <td>با این تابع میتوانید یک چک باکس بسازید</td>
                                            </tr>
                                            <tr>
                                                <td>make_list_item</td>
                                                <td dir="ltr">
                                                    <a href="#list_item">برای مشاهده توضیحات مربوط به این ساختار لطفا کلیک کنید</a>
                                                </td>
                                                <td>با این تابع میتوانید یک چک باکس بسازید</td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <!-- end row -->
                        </div>
                    </div>
                </section>
                <section class="section" id="list_item">
                    <div class="row">
                        <div class="col-md-12 left-align">
                            <h2 class="dark-text">گزینه های تکرار شونده
                                <hr>
                            </h2>
                            <div class="row">
                                <div class="col-md-12 full">
                                    <div>
                                        <p>گزینه های تکرار شونده یک جعبه متشکل از گزینه هایی است که میتوان آن ها را تکرار کرد. به عنوان مثال ما میتوانیم برای ساخت اسلایدر در صفحه اصلی سایت از این ویژگی استفاده کنیم.</p>
                                        <pre><code class="php">
{!!
"
\$OptionsBuilder = new OptionsBuilder;

\$OptionsBuilder-><span class=\"hljs-literal\">make_list_item</span>( [
      'fields' => [
          'title' => [
              'title' => 'عنوان',
              'type' => 'input',
              'name' => 'sliders[title][]',
          ],
          'url' => [
              'title' => 'لینک اسلایدر',
              'type' => 'input',
              'name' => 'sliders[url][]',
          ],
          'image' => [
              'title' => 'تصویر',
              'type' => 'image',
              'name' => 'sliders[image][]',
          ],
          'radio_box' => [
              'title' => 'انتخاب چند تایی',
              'type' => 'radio',
              'choices' => [
                  'choice1' => 'انتخاب اول',
                  'choice2' => 'انتخاب دوم',
                  'choice3' => 'انتخاب سوم',
                  'choice4' => 'انتخاب چهارم',
              ],
              'name' => 'sliders[radio_box][]',
          ],
          'select_box' => [
              'title' => 'انتخاب چند تایی',
              'type' => 'select',
              'choices' => [
                  'choice1' => 'انتخاب اول',
                  'choice2' => 'انتخاب دوم',
                  'choice3' => 'انتخاب سوم',
                  'choice4' => 'انتخاب چهارم',
              ],
              'name' => 'sliders[select_box][]',
          ],
          'yes_or_no' => [
              'title' => 'چک باکس',
              'type' => 'checkbox',
              'name' => 'sliders[yes_or_no][]',
          ],
      ],
      'items' => get_option( 'sliders' ),
      'singular_name' => 'اسلاید',
] );
"
!!}
                                            </code></pre>
                                        <p>مثال استفاده از این ویژگی در صفحه تنظیمات را در قسمت
                                            <a href="#howto">قالب تنظیمات</a> میتوانید مشاهده کنید</p>
                                    </div>
                                </div>
                            </div>
                            <!-- end row -->
                        </div>
                    </div>
                </section>
            </div>
            <!-- // end .col -->
        </div>
        <!-- // end .row -->
    </div>
    <!-- // end container -->
</div>
<!-- end wrapper -->