<?php
ob_start();
require_once __DIR__ . DIRECTORY_SEPARATOR . "__bootstrap.php";
Router\Router::F()->run();

