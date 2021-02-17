<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Helpers;

class Helpers {

    public static function NEString($input, $default = null) {
        if (is_string($input)) {
            $input = trim($input);
            return mb_strlen($input, 'UTF-8') ? $input : $default;
        }
        return $default;
    }

    /**
     * 
     * @param string $input
     * @param string $_ [optional]
     */
    public static function FirstNEString($input) {
        $values = func_get_args();
        for ($i = 0; $i < count($values); $i++) {
            $x = static::NEString($values[$i], null);
            if ($x) {
                return $x;
            }
        }
        return null;
    }

    /**
     * 
     * @param string $input
     * @param string $_ [optional]
     */
    public static function FirstNEString_HTML_ENC($input) {
        $args = func_get_args();
        $result = call_user_func_array([__CLASS__, 'FirstNEString'], $args);
        return $result ? htmlentities($result, ENT_COMPAT || ENT_QUOTES, 'UTF-8') : null;
    }

    public static function safe_array($m): array {
        return is_array($m) ? $m : [];
    }

    public static function safe_array_wrap($m): array {
        return is_array($m) ? $m : [$m];
    }

    public static function ref_classs_to_root(string $class): string {
        return "\\" . trim($class, "\\/");
    }

    public static function hash_array_values(array $x): array {
        $m = array_values($x);
        return array_combine($m, $m);
    }

    public static function hash_array_keys(array $x): array {
        $m = array_keys($x);
        return array_combine($m, $m);
    }

    /**
     * class exists with rel to root namespace 
     * @param string $class
     * @return bool
     */
    public static function class_exists(string $class): bool {
        $class = static::ref_classs_to_root($class);
        return class_exists($class);
    }

    public static function interface_exists(string $interface): bool {
        $interface = static::ref_classs_to_root($interface);
        return interface_exists($interface);
    }

    /**
     * checks when <b>$class</b> implements all of <b>interface</b>
     * @param string $class
     * @param string $interface
     * @param string $_ [optional]
     * @throws HelperError
     * @return bool
     */
    public static function class_implements(string $class, string $interface): bool {
        /* @var $interfaces string[] */
        $interfaces = array_slice(func_get_args(), 1);
        $class = static::ref_classs_to_root($class);
        if (static::class_exists($class)) {
            if (count($interfaces)) {
                $implements = static::hash_array_values(class_implements($class));
                foreach ($interfaces as $interface) {
                    $interface = static::ref_classs_to_root($interface);
                    if (static::interface_exists($interface)) {
                        if (!array_key_exists(ltrim($interface, "\\/"), $implements)) {
                            return false;
                        }
                    } else {
                        HelperError::RF("interface `%s` does not exists to check with class `%s` in `%s`", $interface, $class, __METHOD__);
                    }
                }
                return true;
            } else {
                HelperError::RF("no interfaces provided in `%s` for class `%s`", __METHOD__, $class);
            }
        } else {
            HelperError::RF("class not exists `%s` in `%s`", $class, __METHOD__);
        }
        return true;
    }

    /**
     * check when <b>$class</b> is a descedant of all <b>$superclass</b>es
     * @param string $class
     * @param string $superclass
     * @param string $_ [optional]
     * @throws HelperError
     * @return bool
     */
    public static function class_inherits(string $class, string $superclass): bool {
        /* @var $superclasses string[] */

        $superclasses = array_slice(func_get_args(), 1);
        $class = static::ref_classs_to_root($class);
        if (static::class_exists($class)) {
            if (count($superclasses)) {
                $inherits = static::hash_array_values(class_parents($class));
                foreach ($superclasses as $parent) {
                    $parent = static::ref_classs_to_root($parent);
                    if (static::class_exists($parent)) {
                        if (!array_key_exists(ltrim($parent, "\\/"), $inherits)) {
                            return false;
                        }
                    } else {
                        HelperError::RF("superclass `%s` does not exists to check with class `%s` in `%s`", $parent, $class, __METHOD__);
                    }
                }
                return true;
            } else {
                HelperError::RF("no superclasses provided in `%s` for class `%s`", __METHOD__, $class);
            }
        } else {
            HelperError::RF("class not exists `%s` in `%s`", $class, __METHOD__);
        }
        return false;
    }

    public static function add_params_to_url(string $url, array $params): string {
        $paramstr = [];
        foreach ($params as $key => $value) {
            $paramstr[] = urlencode($key) . "=" . urlencode($value);
        }
        $pst = implode("&", $paramstr);
        if (strpos($url, "?") === false) {
            return "{$url}?{$pst}";
        }
        return "{$url}&{$pst}";
    }

    public static function translit(string $input): string {
        $allowed = ['a', 'b', 'c', 'd', 'e', 'f', 'g',
            'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u',
            'v', 'w', 'x', 'y', 'z', '1', '2', '3', '4', '5', '6', '7', '8', '9', '0', '_'];

        $trans = ['а' => 'a', 'б' => 'b', 'в' => 'v', 'г' => 'g', 'д' => 'd', 'е' => 'e', 'ё' => 'jo', 'ж' => 'j', 'з' => 'z', 'и' => 'i', 'й' => 'ji',
            'к' => 'k', 'л' => 'l', 'м' => 'm', 'н' => 'n', 'о' => 'o',
            'п' => 'p', 'р' => 'r', 'с' => 's', 'т' => 't', 'у' => 'u', 'ф' => 'f', 'х' => 'h', 'ц' => 'c', 'ч' => 'ch', 'ш' => 'sh',
            'щ' => 'sh', 'ы' => 'y', 'э' => 'e', 'ю' => 'u', 'я' => 'ya', ' ' => '_'];

        $s = mb_strtolower($input, 'UTF-8');
        $o = '';
        for ($i = 0; $i < mb_strlen($s, 'UTF-8'); $i++) {
            $c = mb_substr($s, $i, 1, 'UTF-8');
            if (!in_array($c, $allowed)) {
                if (isset($trans[$c])) {
                    $c = $trans[$c];
                } else {
                    $c = '';
                }
            }
            $o .= $c;
        }
        return $o;
    }

    /**
     * generates unique alias
     * @param string $table db table name
     * @param string $alias  current suggested alias
     * @param type $id  identifier of current object
     * @param \DB\IDBAdapter $db_adapter  adapter
     * @param string $field  field_name
     * @param string $id_field id field name
     * @return string
     */
    public static function uniqueAlias(string $table, string $alias, $id = null, \DB\IDBAdapter $db_adapter = null, string $field = 'alias', string $id_field = 'id') {
        /* @var $db \DB\IDBAdapter  */
        $db = $db_adapter ? $db_adapter : \DB\DB::F();
        $query = null;
        $params = [];
        if ($id !== null) {
            $query = "SELECT `{$field}` from `{$table}` WHERE `{$field}`=:Palias AND `{$id_field}`!=:Pid;";
            $params[":Pid"] = $id;
        } else {
            $query = "SELECT `{$field}` from `{$table}` WHERE `{$field}`=:Palias;";
        }
        $check = $alias;
        $params[":Palias"] = $check;
        $row = $db->queryRow($query, $params);
        $counter = 0;
        while ($row) {
            $counter++;
            $check = "{$alias}_{$counter}";
            $params[":Palias"] = $check;
            $row = $db->queryRow($query, $params);
        }
        return $check;
    }

    public static function rm_dir_recursive(string $path) {
        $path = rtrim($path, "\\/");
        if (file_exists($path) && is_dir($path) && is_writeable($path)) {
            $list = scandir($path);
            foreach ($list as $filename) {
                if ($filename !== '.' && $filename !== '..') {
                    $lpath = $path . DIRECTORY_SEPARATOR . $filename;
                    if (file_exists($lpath)) {
                        if (is_dir($lpath)) {
                            static::rm_dir_recursive($lpath);
                        } else if (is_writable($lpath)) {
                            @unlink($lpath);
                        }
                    }
                }
            }
            @rmdir($path);
        }
    }

    /**
     * удаляет файлы в указанной директории, которые совпадают с одним из регулярных выражений
     * Не рекурсивная, папки не удаляет
     * @param string $path
     * @param string[] $regex_list
     */
    public static function rm_files_by_regex(string $path, array $regex_list) {
        if (file_exists($path) && is_dir($path) && is_writable($path)) {
            $files = scandir($path);
            $apath = rtrim($path, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
            foreach ($files as $file) {
                if (static::matching_one($file, $regex_list)) {
                    if (is_file($apath . $file) && is_writeable($apath . $file)) {
                        @unlink($apath . $file);
                    }
                }
            }
        }
    }

    /**
     * ПРоверяет на соответствие <b>хотя бы одному</b> из переданных регулярных выражений
     * @param string $text
     * @param string[] $regex_list
     * @return bool
     */
    public static function matching_one(string $text, array $regex_list): bool {
        foreach ($regex_list as $regex) {
            if (preg_match($regex, $text)) {
                return true;
            }
        }
        return false;
    }

    public static function mk_password(int $len = 10) {
        $password_base = "abcdefghijklmnpqrstuxyzwv1234567890ascdfwqxfkl3458";
        $password_base = implode("", [$password_base, mb_strtoupper($password_base, 'UTF-8')]);
        $r = [];
        for ($i = 0; $i < $len; $i++) {
            $index = mt_rand(0, mb_strlen($password_base, 'UTF-8') - 1);
            $r[] = mb_substr($password_base, $index, 1, 'UTF-8');
        }
        return implode("", $r);
    }

    /**
     * returns array of paginator items or FALSE if no need pagination
     * @param int $total
     * @param int $page
     * @param int $perpage
     * @return boolean|array
     */
    public static function mk_paginator(int $total, int $page = 0, int $perpage = 24) {
        if ($total > 0 && $perpage > 0) {
            $pages = ceil($total / $perpage);
            if ($pages > 1) {
                $items = [];
                $index = [];
                for ($i = 0; $i < 3; $i++) {
                    if ($i < $pages) {
                        $value = $i + 1;
                        $key = "P{$i}";
                        if (!array_key_exists($key, $index)) {
                            $item = ['key' => $key, 'page' => (int) $i, 'value' => (int) $value, 'current' => ($page === $i)];
                            $index[$key] = $item;
                            $items[] = $item;
                        }
                    }
                }
                for ($i = $page - 2; $i < $page + 3; $i++) {
                    if ($i >= 0 && $i < $pages) {
                        $value = $i + 1;
                        $key = "P{$i}";
                        if (!array_key_exists($key, $index)) {
                            $item = ['key' => $key, 'page' => (int) $i, 'value' => (int) $value, 'current' => ($page === $i)];
                            $index[$key] = $item;
                            $items[] = $item;
                        }
                    }
                }
                for ($i = $pages - 3; $i < $pages; $i++) {
                    if ($i >= 0 && $i < $pages) {
                        $value = $i + 1;
                        $key = "P{$i}";
                        if (!array_key_exists($key, $index)) {
                            $item = ['key' => $key, 'page' => (int) $i, 'value' => (int) $value, 'current' => ($page === $i),];
                            $index[$key] = $item;
                            $items[] = $item;
                        }
                    }
                }
                $last_item_page = null;
                $des_items = [];

                foreach ($items as $item) {
                    if ($last_item_page !== null && (($last_item_page + 1) !== $item['page'])) {
                        $des_items[] = null;
                    }
                    $des_items[] = $item;
                    $last_item_page = (int) $item['page'];
                }
                return $des_items;
            }
        }
        return false;
    }

    public static function guid_v4() {
        return sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x', mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0x0fff) | 0x4000, mt_rand(0, 0x3fff) | 0x8000, mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff));
    }

    /**
     * 
     * @param string $section
     * @return string
     */
    public static function csrf_mk(string $section = 'default', bool $reusable = false, int $ttl = 86400): string {
        $key = "csrf_mk_{$section}";
        $ssl = \OpenSSL\OpenSSL::F('csrf', 512);
        $t = time() + $ttl;
        $value = $ssl->sign(implode(":", [$key, $t, \Router\Request::F()->host]));
        $values = \DataMap\SessionDataMap::F()->get_filtered($key, ['NEArray', 'DefaultEmptyArray']);
        $values = array_filter($values, function($val, $key) {
            if (is_array($val)) {
                if (array_key_exists('ttl', $val)) {
                    if (intval($val['ttl']) && intval($val['ttl']) > time()) {
                        return true;
                    }
                }
            } else if (is_numeric($val) && intval($val) > time()) {
                return true;
            }
            return false;
        }, ARRAY_FILTER_USE_BOTH);
        if (count($values) > 50) {
            uasort($values, function($a, $b) {
                $value_a = intval(is_array($a) ? $a['ttl'] : $a);
                $value_b = intval(is_array($b) ? $b['ttl'] : $b);
                return $value_a - $value_b;
            });
            $values = array_slice($values, count($values) - 50, 50, true);
        }
        $values[$value] = $reusable ? ['ttl' => $t, 'r' => true] : $t;
        \DataMap\SessionDataMap::F()->set($key, $values);
        return $value;
    }

    /**
     * 
     * @param string $section
     * @param string $value
     * @return bool
     */
    public static function csrf_check(string $section = 'default', string $value, bool $remove_key = true): bool {
        $key = "csrf_mk_{$section}";
        $src_values = \DataMap\SessionDataMap::F()->get_filtered($key, ['NEArray', 'DefaultEmptyArray']);
        if (array_key_exists($value, $src_values)) {
            $kp = is_array($src_values[$value]) ? $src_values[$value]['ttl'] : $src_values[$value];
            $ckeck = implode(":", [$key, $kp, static::referrer_host()]);
            $ssl = \OpenSSL\OpenSSL::F('csrf', 512);
            if ($ssl->checkSign($ckeck, $value)) {
                if (!is_array($src_values[$value])) {
                    if ($remove_key) {
                        unset($src_values[$value]);
                        \DataMap\SessionDataMap::F()->set($key, $src_values);
                    }
                }
                return true;
            }
        }
        return false;
    }

    public static function csrf_remove(string $value, string $section = 'default') {
        $key = "csrf_mk_{$section}";
        $src_values = \DataMap\SessionDataMap::F()->get_filtered($key, ['NEArray', 'DefaultEmptyArray']);
        if (array_key_exists($value, $src_values)) {
            if (!is_array($src_values[$value])) {
                unset($src_values[$value]);
                \DataMap\SessionDataMap::F()->set($key, $src_values);
            }
        }
    }

    /**
     * 
     * @param string $section
     * @param string $value
     * @throws CSRFError
     */
    public static function csrf_check_throw(string $section = 'default', string $value,bool $remove_key = true) {
        if (!static::csrf_check($section, $value,$remove_key)) {
            CSRFError::R(CSRFError::MESSAGE);
        }
    }

    public static function referrer_host(): string {
        if (isset($_SERVER) && is_array($_SERVER)) {
            if (array_key_exists('HTTP_REFERER', $_SERVER) && is_string($_SERVER['HTTP_REFERER'])) {
                if (mb_strlen($_SERVER['HTTP_REFERER'], 'UTF-8') > 3) {
                    $host = parse_url($_SERVER['HTTP_REFERER'], PHP_URL_HOST);
                    if ($host && mb_strlen($host, 'UTF-8')) {
                        return $host;
                    }
                }
            }
        }
        return '';
    }

}
