<!DOCTYPE html>
<html prefix="og: http://ogp.me/ns#" xmlns="http://www.w3.org/1999/xhtml" xml:lang="{{ config('app.locale') }}" lang="{{ config('app.locale') }}">
<head>
    {!! setting('site_meta_head_tags', '') !!}
    <meta http-equiv="Content-Language" content="{{ config('app.locale') }}" />
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
