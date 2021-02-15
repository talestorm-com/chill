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
function smarty_modifier_pipe_replace($input) {
    $input = \Helpers\Helpers::NEString($input, null);
    if ($input) {
        $ti = preg_replace("/\\n/i", "<br>", $input);
        $ti = preg_replace("/\\r/i", "", $ti);
        $ti = preg_replace("/\|/i", "<br>", $ti);
        return $ti;
    }
    return null;
}
