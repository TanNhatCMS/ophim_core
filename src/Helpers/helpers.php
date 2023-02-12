<?php

use Backpack\Settings\app\Models\Setting;
use Ophim\Core\Models\Theme;
use Ophim\Core\Models\Episode;

if (!function_exists('get_theme_option')) {
    function get_theme_option($key, $fallback = null)
    {
        $theme = Theme::getActivatedTheme();

        if (is_null($theme)) return $fallback;

        $props = collect(array_merge(
            array_column($theme->options, 'value', 'name') ?? [],
            is_array($theme->value) ? $theme->value : []
        ));

        return $props[$key] ?? $fallback;
    }
}
if (!function_exists('get_theme_info')) {
    function get_theme_info($key, $fallback = null)
    {
        $theme = Theme::getActivatedTheme();
        if (is_null($theme)) return $fallback;
        return $theme[$key] ?? $fallback;
    }
}
if (!function_exists('get_theme_version')) {
    function get_theme_version()
    {
        if (!\Composer\InstalledVersions::isInstalled(get_theme_info('package_name'))) {
            return 'Unknown';
        }
        return  explode('@', \PackageVersions\Versions::getVersion(get_theme_info('package_name')) ?? 0)[0];
    }
}
if (!function_exists('get_crud_version')) {
    function get_crud_version()
    {
        if (!\Composer\InstalledVersions::isInstalled('tannhatcms/crud')) {
            return 'Unknown';
        }
        return  explode('@', \PackageVersions\Versions::getVersion('tannhatcms/crud') ?? 0)[0];
    }
}
if (!function_exists('CheckPermission')) {
    function CheckPermission($key)
    {
        if (!backpack_user()->hasPermissionTo($key)) {
            abort(403);
        }
    }
}

if (!function_exists('myCurl')) {
// cUrl handler to ping the Sitemap submission URLs for Search Engines…
function myCurl($url){
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    return $httpCode;
    }
}
if (!function_exists('ping_pingomatic')) {
    function ping_pingomatic($url, $title){
        return " PingOmatic(".myCurl("https://pingomatic.com/ping/?chk_blogs=on
        &chk_feedburner=on&chk_tailrank=on&chk_superfeedr=on&title=".$title."&blogurl=".$url)."). ";
    }
}

if (!function_exists('ping_sitemap')) {
    function ping_sitemap($sitemapUrl){
        //Google
        $returnGoogle = "Đã ping Google(".myCurl("http://www.google.com/webmasters/sitemaps/ping?sitemap=".$sitemapUrl)."), ";
        //Bing / MSN
        $returnBing  = "& Bing/MSN(".myCurl("https://www.bing.com/webmaster/ping.aspx?siteMap=".$sitemapUrl)."), ";
        return $returnGoogle.$returnBing;
    }
}
//$data = file_get_contents("https://www.google.com/webmasters/tools/ping?sitemap={$sitemap}");
//$status = ( strpos($data,"Sitemap Notification Received") !== false ) ? "OK" : "ERROR";
//echo "Submitting Google Sitemap: {$status}\n";

if (!function_exists('count_episodes_error')) {
    function count_episodes_error($sitemapUrl){
        return Episode::where('has_report', true)->count();
    }
}