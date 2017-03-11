<?php

if (!function_exists('setting')) {
    function setting($key, $default = null)
    {
        return artworx\omegacp\Facades\Omega::setting($key, $default);
    }
}

if (!function_exists('menu')) {
    function menu($menuName, $type = null, array $options = [])
    {
        return artworx\omegacp\Models\Menu::display($menuName, $type, $options);
    }
}
