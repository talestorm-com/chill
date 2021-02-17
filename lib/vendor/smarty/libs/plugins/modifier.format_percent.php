<?php

/**
 * @return string formatted
 */
function smarty_modifier_format_percent($input,$unit='%') {
    return  implode("", [number_format(floatval($input), 4, ".", ""), "{$unit}"]);    
}
