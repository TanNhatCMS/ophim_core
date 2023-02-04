<?php

use Backpack\Settings\app\Models\Setting;
use Ophim\Core\Models\Theme;

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
if (!function_exists('ping_sitemap')) {
    function ping_sitemap($sitemapUrl){
        //Google
        $returnCode = myCurl("http://www.google.com/webmasters/sitemaps/ping?sitemap=".$sitemapUrl);
        $returnGoogle = "Google Sitemaps has been pinged (return code: $returnCode).";
        //Bing / MSN
        $returnCode = myCurl("https://www.bing.com/webmaster/ping.aspx?siteMap=".$sitemapUrl);
        $returnBing  = "Bing / MSN Sitemaps has been pinged (return code: $returnCode).";
        //ASK
        $returnCode = myCurl("http://submissions.ask.com/ping?sitemap=".$sitemapUrl);
        $returnASK "ASK.com Sitemaps has been pinged (return code: $returnCode).";
        return $returnGoogle.$returnBing.$returnASK  ;
    }
}
//$data = file_get_contents("https://www.google.com/webmasters/tools/ping?sitemap={$sitemap}");
//$status = ( strpos($data,"Sitemap Notification Received") !== false ) ? "OK" : "ERROR";
//echo "Submitting Google Sitemap: {$status}\n";
