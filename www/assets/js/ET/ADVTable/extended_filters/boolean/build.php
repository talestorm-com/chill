<?php

$templates = [];
$name = "boolean.dev.js";
$oname = "boolean.js"; //минификация пока не нужна 

function incTpl($file, $outname) {
    global $templates;
    if (file_exists(__DIR__ . DIRECTORY_SEPARATOR . $file)) {
        $templates[$outname] = file_get_contents($file);
    }
}

function outTpl($varName = 'TPL') {
    global $templates;
    return ">>>>>>>TEMPLATES*/\n {$varName}=" . json_encode($templates) . ";\n/*<<<<<templates";
}

function out_style($var = 'style') {
    $css = file_get_contents(rtrim(__DIR__, "\\/") . DIRECTORY_SEPARATOR . "style.css");
    return "*/ {$var} = " . json_encode(['css' => $css]) . " /*";
}

ob_start();
include __DIR__ . DIRECTORY_SEPARATOR . $name;

$output = ob_get_clean();
file_put_contents(__DIR__ . DIRECTORY_SEPARATOR . $oname, $output);

die('done');

