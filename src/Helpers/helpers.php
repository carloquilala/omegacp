<?php

if (!function_exists('setting')) {
    function setting($key, $default = null)
    {
        return AI\Omega\Facades\Omega::setting($key, $default);
    }
}

if (!function_exists('menu')) {
    function menu($menuName, $type = null, array $options = [])
    {
        return AI\Omega\Models\Menu::display($menuName, $type, $options);
    }
}
