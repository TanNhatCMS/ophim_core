<!DOCTYPE html>
<html prefix="og: http://ogp.me/ns#" xmlns="http://www.w3.org/1999/xhtml" xml:lang="{{ config('app.locale') }}" lang="{{ config('app.locale') }}">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta charSet="utf-8" >
    <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1" />
    <meta http-equiv="Content-Language" content="{{ config('app.locale') }}" />
    <meta name="robots" content="index,follow" >
    <meta name="revisit-after" content="1 days" >
    <meta name="ROBOTS" content="index,follow" >
    <meta name="googlebot" content="index,follow" >
    <meta name="BingBOT" content="index,follow" >
    <meta name="yahooBOT" content="index,follow" >
    <meta name="slurp" content="index,follow" >
    <meta name="msnbot" content="index,follow" >
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />

    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta property="fb:app_id" content="{{ setting('social_facebook_app_id') }}" >
    <link rel="shortcut icon" href="{{ setting('site_meta_shortcut_icon') }}" type="image/png" >
    {!! SEO::generate() !!}

    @stack('header')
    {!! get_theme_option('additional_css') !!}
    {!! get_theme_option('additional_header_js') !!}
</head>

<body 
@stack('body_attributes')
{!! get_theme_option('body_attributes', '') !!}>
    @yield('body')
    {!! get_theme_option('additional_body_js') !!}
    @yield('footer')
    @stack('scripts')
    {!! get_theme_option('additional_footer_js') !!}
</body>

</html>
