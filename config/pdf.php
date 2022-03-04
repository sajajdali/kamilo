<?php

return [
	'mode'                  => 'utf-8',
	'format'                => 'A4',
	'author'                => '',
	'subject'               => '',
	'keywords'              => '',
	'creator'               => 'Laravel Pdf',
	'display_mode'          => 'fullpage',
	'tempDir'               => base_path('/temp/'),
    'font_path' => base_path('resources/fonts/irs'),
	'font_data' => [
    'iransans' => [
        'R'  => 'IRANSansWeb_Medium.ttf',    // regular font
        'B'  => 'IRANSansWeb_Bold.ttf',       // optional: bold font
        'I'  => 'IRANSansWeb_Medium.ttf',     // optional: italic font
        'BI' => 'IRANSansWeb_Bold.ttf', // optional: bold-italic font
        'useOTL' => 0xFF,    // required for complicated langs like Persian, Arabic and Chinese
        'useKashida' => 75  // required for complicated langs like Persian, Arabic and Chinese
    ]
    // ...add as many as you want.
]
];
