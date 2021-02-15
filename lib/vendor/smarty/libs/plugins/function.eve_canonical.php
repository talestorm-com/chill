<?php

function smarty_function_eve_canonical($params, $template) {
    $map = \DataMap\CommonDataMap::F();
    if (is_array($params)) {
        $map->rebind($params);
    }
    $assign = $map->get_filtered('assign', ['Strip', 'Trim', 'NEString', 'DefaultNull']);
    $server_map = \DataMap\CommonDataMap::F();
    if (isset($_SERVER) && is_array($_SERVER)) {
        $server_map->rebind($_SERVER);
    }
    $result = null;
    $host = $server_map->get_filtered('HTTP_HOST', ['Strip', 'Trim', 'NEString', 'DefaultNull']);
    if ($host) {
        $url = $server_map->get_filtered('REQUEST_URI', ['Strip', 'Trim', 'NEString', 'DefaultNull']);
        if ($url) {
            if (($pos = mb_strpos($url, '?', 0, 'UTF-8')) !== false) {
                $url = mb_substr($url, 0, $pos, 'UTF-8');
            }
            $result = sprintf("%s://%s%s", Router\Request::F()->https ? "https" : "http", $host, $url);
        } else {
            $result = sprintf("%s://%s/", Router\Request::F()->https ? "https" : "http", $host);
        }
    }
    if ($assign) {
        $template->assign($assign, $result);
        return '';
    }
    return $result;
}
