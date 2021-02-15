<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

$path = __DIR__.DIRECTORY_SEPARATOR."forms".DIRECTORY_SEPARATOR;

$forms = ["form1","form2","form3","form4"];

$out = [];
foreach ($forms as $fn){
    $out[$fn]= file_get_contents("{$path}{$fn}.html");
    
}

$e = json_encode($out);
$e = preg_replace("/\\\\n\s{1,}/i", "\\n", $e);

file_put_contents(__DIR__.DIRECTORY_SEPARATOR."forms.json", $e);