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