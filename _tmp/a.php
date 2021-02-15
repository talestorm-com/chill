<?php
$a = json_decode(file_get_contents(__DIR__.DIRECTORY_SEPARATOR."CDN_DRIVER_TranscoderTask"),true);
echo count($a);
echo "\n";
