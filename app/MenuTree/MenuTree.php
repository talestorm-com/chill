<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace MenuTree;

/**
 * @property string $css_class
 * @property string $alias
 * @property string $description
 * @property int $id
 */
class MenuTree extends \Tree\Tree {

    use \common_accessors\TCommonImport;

    //<editor-fold defaultstate="collapsed" desc="props">
    /** @var string */
    protected $css_class;

    /** @var string */
    protected $alias;

    /** @var string */
    protected $description;

    /** @var int */
    protected $id;

//</editor-fold>
    //<editor-fold defaultstate="collapsed" desc="getters">
    /** @return string */
    protected function __get__css_class() {
        return $this->css_class;
    }

    /** @return string */
    protected function __get__alias() {
        return $this->alias;
    }

    /** @return string */
    protected function __get__description() {
        return $this->description;
    }

    /** @return int */
    protected function __get__id() {
        return $this->id;
    }

//</editor-fold>

    public function load(): \Tree\ITree {
        if (is_int($this->arg)) {
            return $this->load_int($this->arg);
        } else if (is_string($this->arg)) {
            return $this->load_string($this->arg);
        }
        \Errors\common_error::R("invalid argument");
    }

    protected function load_int(int $id): \Tree\ITree {
        $query = "SELECT * FROM menu WHERE id=:P";
        return $this->load_query($query, [":P" => $id]);
        //$rows = \DB\DB::F()->queryAll($query, [":P" => $id]);
        //$this->import($rows);
        //return $this;
    }

    protected function load_string(string $alias): \Tree\ITree {
        $query = "SELECT * FROM menu WHERE alias=:P";
        return $this->load_query($query, [":P" => $alias]);
        //$rows = \DB\DB::F()->queryAll($query, [":P" => $alias]);
        //$this->import($rows);
        //return $this;
    }

    protected function load_query(string $query, array $params): \Tree\ITree {
        $row = \DB\DB::F()->queryRow($query, $params);
        if ($row) {
            $this->import_props($row);
            $items_query = "SELECT * FROM menu__items WHERE menu_id=:P";
            $items_rows = \DB\DB::F()->queryAll($items_query, [":P" => $this->id]);
            $this->import($items_rows);
        }
        return $this;
    }

    protected function t_common_import_get_filters() {
        return [
            'id' => ['IntMore0', 'DefaultNull'],
            'alias' => ['Strip', 'Trim', 'NEString', 'DefaultNull'],
            'description' => ['Strip', 'Trim', 'NEString', 'DefaultNull'],
            'css_class' => ['Strip', 'Trim', 'NEString', 'DefaultNull'],
        ];
    }

    public static function transform_arg_to_string($argument = null): string {
        return (string) $argument;
    }

    public function get_cache_dependency() {
        return \Cache\FileBeaconDependency::F('front_menus');
    }

    public function get_node_instance(): \Tree\ITreeNode {
        return MenuTreeItem::F();
    }

    public static function clear_dependency_beacon() {
        \Cache\FileBeaconDependency::F('front_menus')->reset_dependency_beacons();
    }

//    protected function get_template_file(string $template) {
//        return \Content\ContentViewResolver::F()->
//                $base_dir = \Config\Config::F()->VIEW_PATH . "modules" . DIRECTORY_SEPARATOR . "menu" . DIRECTORY_SEPARATOR;
//        return "{$base_dir}{$template}.tpl";
//    }

    public function render(\Smarty $smarty = null, string $template = null, string $item_template = null, bool $return = false) {
        $smarty = $smarty ? $smarty : \smarty\SMW::F()->smarty;
        $item_template_file = \Content\ContentViewResolver::F()->resolve_path_for_class(MenuTreeItem::class, $item_template);
        $template_file = \Content\ContentViewResolver::F()->resolve_path_for_object($this, $template);

        $smarty->assign('menu_nodes', $this->root);
        $smarty->assign('menu_item_template', $item_template_file);
        if ($return) {
            try {
                return $smarty->fetch($template_file);
            } catch (\Throwable $e) {
                return "<!-- {$e->getMessage()} -->";
            }
        } else {
            try {
                $smarty->display($template_file);
            } catch (\Throwable $e) {
                echo "<!-- {$e->getMessage()} -->";
            }
        }
    }

    public function __sleep() {
        return array_merge(parent::__sleep(), ['css_class', 'alias', 'description', 'id']);
    }

}
