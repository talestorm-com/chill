<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

\Config\Config::init_instance([
    'CONFIG_DIR' => realpath(__DIR__) . DIRECTORY_SEPARATOR,
    'BASE_DIR' => realpath(__DIR__ . DIRECTORY_SEPARATOR . "..") . DIRECTORY_SEPARATOR,
    'WEB_ROOT' => realpath(__DIR__ . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . "www") . DIRECTORY_SEPARATOR,
    'LIB_DIR' => realpath(__DIR__ . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . "lib") . DIRECTORY_SEPARATOR,
    'LOG_DIR' => realpath(__DIR__ . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . "app_logs") . DIRECTORY_SEPARATOR,
    'DB' => require_once __DIR__ . DIRECTORY_SEPARATOR . "database.conf.php",
    'ROUTES' => require_once __DIR__ . DIRECTORY_SEPARATOR . "routes.conf.php",
    'SMARTY_BASE_DIR' => realpath(__DIR__ . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . "_smarty") . DIRECTORY_SEPARATOR,
    'HTML_LAYOUT_DIR' => realpath(__DIR__ . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . "_layouts") . DIRECTORY_SEPARATOR,
    'VIEW_PATH' => realpath(__DIR__ . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . "_views") . DIRECTORY_SEPARATOR,
    'DEFAULT_CONTROLLER_NAMESPACE' => 'FrontEnd',
    'LOGIN_URL' => null,
    'COMMON_ASSETS' => require_once __DIR__ . DIRECTORY_SEPARATOR . "common_assets.conf.php",
    'COMPONENT_CACHE_DIR' => realpath(__DIR__ . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . "www") . DIRECTORY_SEPARATOR . "assets" . DIRECTORY_SEPARATOR . "com_cache" . DIRECTORY_SEPARATOR,
    'COM_DEV_DIR' => realpath(__DIR__ . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . "_components") . DIRECTORY_SEPARATOR,
    'OPENSSL_KEY_PATH' => realpath(__DIR__ . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . "_keys") . DIRECTORY_SEPARATOR,
    'EVENT_REGISTRY' => realpath(__DIR__ . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . "_events") . DIRECTORY_SEPARATOR,
    'IMAGE_STORAGE_PATH' => realpath(__DIR__ . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . "_media") . DIRECTORY_SEPARATOR,
    'IMAGE_WEB_BASE_PATH' => realpath(__DIR__ . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . "www" . DIRECTORY_SEPARATOR . "media") . DIRECTORY_SEPARATOR,
    'IMAGE_WEB_BASE_URL' => "/media/",
    'MUTEX_DIR' => realpath(__DIR__ . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . "_mutex") . DIRECTORY_SEPARATOR,
    'WTPHP_EXECUTOR_PATH' => 'php',
    'PHP_EXECUTOR_PATH' => 'php',
    'IMAGE_FLY_EXTENSIONS' => require_once __DIR__ . DIRECTORY_SEPARATOR . "image_fly_extensions.conf.php",
    'LOCAL_TMP_PATH' => realpath(__DIR__ . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . "_tmp") . DIRECTORY_SEPARATOR,
    'PROTECTED_STORAGE_BASE' => realpath(__DIR__ . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . "_protected_media") . DIRECTORY_SEPARATOR,
    'PROTECTED_VIDEOTUTORIALS_BASE' => realpath(__DIR__ . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . "_protected_video") . DIRECTORY_SEPARATOR,
    'PUBLIC_STORAGE_BASE' => realpath(__DIR__ . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . "_public_media") . DIRECTORY_SEPARATOR,
    'CDN_API_ID' => '3a491355-c95a-4448-a558-b7ebaa380238',
    'CDN_KEY' => '8e71a2f3-b9de-443f-bb37-928540dd52b2',
    'GEOIP_LIST' => require_once __DIR__ . DIRECTORY_SEPARATOR . 'bwl_country.conf.php',
]);
