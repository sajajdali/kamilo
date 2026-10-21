<!DOCTYPE html>
<!--[if lt IE 7 ]>
<html class="ie ie6" lang="en"> <![endif]-->
<!--[if IE 7 ]>
<html class="ie ie7" lang="en"> <![endif]-->
<!--[if IE 8 ]>
<html class="ie ie8" lang="en"> <![endif]-->
<!--[if (gte IE 9)|!(IE)]><!-->
<html lang="en">
    <!--<![endif]-->
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
        <meta name="description" content="">
        <meta name="author" content="">
        <meta name="keywords" content="">
        <title>{{ ucfirst($title) }} Module API Documentation</title>
        <link rel="stylesheet" type="text/css" href="{{ URL::asset('/admin/doc') }}/fonts/font-awesome-4.3.0/css/font-awesome.min.css">
        <link rel="stylesheet" type="text/css" href="{{ URL::asset('/admin/doc') }}/fonts/IRANSans/css/fontiran.css">
        <link rel="stylesheet" type="text/css" href="{{ URL::asset('/admin/doc') }}/css/stroke.css">
        <link rel="stylesheet" type="text/css" href="{{ URL::asset('/admin/doc') }}/css/bootstrap.css">
        <link rel="stylesheet" type="text/css" href="{{ URL::asset('/admin/doc') }}/css/animate.css">
        <link rel="stylesheet" type="text/css" href="{{ URL::asset('/admin/doc') }}/css/prettyPhoto.css">
        <link rel="stylesheet" type="text/css" href="{{ URL::asset('/admin/doc') }}/css/style.css">
        <link rel="stylesheet" type="text/css" href="{{ URL::asset('/admin/doc') }}/js/syntax-highlighter/styles/shCore.css" media="all">
        <link rel="stylesheet" type="text/css" href="{{ URL::asset('/admin/doc') }}/js/syntax-highlighter/styles/shThemeRDark.css" media="all">
        <link rel="stylesheet" type="text/css" href="{{ URL::asset('/admin/doc') }}/highlight/default.min.css" media="all">
        <link rel="stylesheet" type="text/css" href="{{ URL::asset('/admin/doc') }}/highlight/atom-one-dark.css" media="all">
        <!-- CUSTOM -->
        <link rel="stylesheet" type="text/css" href="{{ URL::asset('/admin/doc') }}/css/custom.css">
    </head>
    <body>
        {!! $content !!}
        <script src="{{ URL::asset('/admin/doc') }}/js/jquery.min.js"></script>
        <script src="{{ URL::asset('/admin/doc') }}/js/bootstrap.min.js"></script>
        <script src="{{ URL::asset('/admin/doc') }}/js/retina.js"></script>
        <script src="{{ URL::asset('/admin/doc') }}/js/jquery.fitvids.js"></script>
        <script src="{{ URL::asset('/admin/doc') }}/js/wow.js"></script>
        <script src="{{ URL::asset('/admin/doc') }}/js/jquery.prettyPhoto.js"></script>
        <!-- CUSTOM PLUGINS -->
        <script src="{{ URL::asset('/admin/doc') }}/js/custom.js"></script>
        <script src="{{ URL::asset('/admin/doc') }}/js/main.js"></script>
        <script src="{{ URL::asset('/admin/doc') }}/js/syntax-highlighter/scripts/shCore.js"></script>
        <script src="{{ URL::asset('/admin/doc') }}/js/syntax-highlighter/scripts/shBrushXml.js"></script>
        <script src="{{ URL::asset('/admin/doc') }}/js/syntax-highlighter/scripts/shBrushCss.js"></script>
        <script src="{{ URL::asset('/admin/doc') }}/js/syntax-highlighter/scripts/shBrushJScript.js"></script>
        <script src="{{ URL::asset('/admin/doc') }}/highlight/highlight.pack.js"></script>
        <script>hljs.initHighlightingOnLoad();</script>
    </body>
</html>
