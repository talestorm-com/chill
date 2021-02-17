<?php

/**
 * Smarty plugin
 *
 * @package    Smarty
 * @subpackage PluginsModifier
 */

/**
 * Smarty capitalize modifier plugin
 * Type:     modifier
 * Name:     capitalize
 * Purpose:  capitalize words in the string
 * {@internal {$string|capitalize:true:true} is the fastest option for MBString enabled systems }}
 *
 * @param string  $string    string to capitalize
 * @param boolean $uc_digits also capitalize "x123" to "X123"
 * @param boolean $lc_rest   capitalize first letters, lowercase all following letters "aAa" to "Aaa"
 *
 * @return string capitalized string
 * @author Monte Ohrt <monte at ohrt dot com>
 * @author Rodney Rehm
 */
function smarty_modifier_phone_as_link($input) {
    $input = \Helpers\Helpers::NEString($input, null);
    if ($input) {
        $ti = preg_replace("/\D/i", "", $input);
        if (mb_strlen($ti, 'UTF-8') >= 11) {
            return "+{$ti}";
        }
    }
    return null;
}
