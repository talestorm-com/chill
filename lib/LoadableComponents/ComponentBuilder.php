<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace LoadableComponents;

/**
 * @property string $fqcn
 * @property string $MC
 * @property string $MD
 * @property string $component_path
 */
class ComponentBuilder {

    use \common_accessors\TCommonAccess;

    //<editor-fold defaultstate="collapsed" desc="props && getters">
    /** @var string */
    protected $fqcn;

    /** @var string */
    protected $MC;

    /** @var string */
    protected $MD;

    /** @var string */
    protected $component_path;

    /** @return string */
    protected function __get__fqcn() {
        return $this->fqcn;
    }

    /** @return string */
    protected function __get__MC() {
        return $this->MC;
    }

    /** @return string */
    protected function __get__MD() {
        return $this->MD;
    }

    /** @return string */
    protected function __get__component_path() {
        return $this->component_path;
    }

    //</editor-fold>

    public function __construct(string $fqcn) {
        $this->fqcn = $fqcn;
    }

    public function build_component() {
        $base_dir = \Config\Config::F()->COM_DEV_DIR;

        $rpath = str_ireplace(".", DIRECTORY_SEPARATOR, trim($this->fqcn, '.'));

        $component_dir = "{$base_dir}{$rpath}" . DIRECTORY_SEPARATOR;
        if (file_exists($component_dir) &&
                is_dir($component_dir) &&
                is_readable($component_dir) &&
                file_exists($component_dir . "index.js") &&
                is_file($component_dir . "index.js") &&
                is_readable($component_dir . "index.js")) {
            return $this->_build($component_dir);
        }
        ComponentNotFound::RF("component not found:`%s`", $this->fqcn);
    }

    protected function _build(string $path) {
        $this->component_path = $path;

        $mcs = explode(".", $this->fqcn);
        for ($i = 0; $i < count($mcs); $i++) {
            $mcs[$i] = ucfirst(mb_strtolower($mcs[$i]));
        }
        $this->MC = implode("", $mcs);
        $this->MD = implode("", [$this->MC, md5($this->component_path)]);

        ob_start();
        require_once "{$this->component_path}index.js";
        $component_text = ob_get_clean();
        if (!headers_sent()) {
            header("Content-Type: application/javascript", true);
        }
        die($this->post_process($component_text));
    }

    protected function post_process(string $component_text): string {
        if (!file_exists($this->component_path . "no_compress")) {
            $component_text = $this->compress_js($component_text);
        }
        if (!file_exists($this->component_path . "no_cache")) {
            file_put_contents(\Config\Config::F()->COMPONENT_CACHE_DIR . "{$this->fqcn}.js", $component_text, LOCK_EX);
        }
        return $component_text;
    }

    protected function compress_js(string $js_text): string {
        return \Out\assets\minifiers\AssetMinifier::F()->minify_js($js_text);
    }

    protected function compress_css(string $css_text): string {
        return \Out\assets\minifiers\AssetMinifier::F()->minify_css($css_text);
    }

    public function build_templates(string $var_name = 'TPLS', string $sub = null): string {
        $ra = [];
        $vp = $this->component_path . "TPL" . DIRECTORY_SEPARATOR;
        $sub ? $vp .= $sub . DIRECTORY_SEPARATOR : FALSE;
        if (file_exists($vp) && is_dir($vp)) {
            $fileList = scandir($vp);
            foreach ($fileList as $file) {
                $m = [];
                if (preg_match('/^(?P<nam>[^\.]{1,})\.(html|xml|svg|json)$/', $file, $m) && is_file($vp . $file)) {
                    $ra[$m['nam']] = file_get_contents($vp . $file);
                }
            }
        }
        return "=========TEMPLATES FOR `{$this->fqcn}`=========*/\nvar {$var_name} = " . json_encode($ra, JSON_FORCE_OBJECT) . ";\n/* ======END OF TEMPLATES FOR `{$this->fqcn}` =======";
    }

    public function create_style(string $id = null, string $retvar = 'STYLE'): string {
        $id = $id ? $id : $this->MC;
        $cssContent = null;
        $path = $this->component_path . "style.css";
        if (file_exists($path) && is_readable($path) && is_file($path)) {
            $cssContent = $this->compress_css(file_get_contents($path));
        }
        $encodedContent = json_encode(['id' => $id, 'css' => $cssContent]);
        if ($retvar) {
            return "style-->*/{$retvar}={$encodedContent};/*<--style";
        }
        return "*/{$encodedContent}/*";
    }

    public function create_svg(string $retvar = 'SVG') {
        $svg_content = null;
        $path = $this->component_path . "icons.svg";
        if (file_exists($path) && is_readable($path) && is_file($path)) {
            $svg_content = file_get_contents($path);
        }
        if ($svg_content) {
            $encoded_content = json_encode(['svg' => $svg_content]);
            return "*/{$retvar}={$encoded_content};/*";
        }
        return "";
    }

    public function include_lib(string $lib_name) {
        $path = $this->component_path . "jslib" . DIRECTORY_SEPARATOR;
        $lib_path = "{$path}{$lib_name}";
        if (!preg_match("/.*\.js/i", $lib_path)) {
            $lib_path .= ".js";
        }
        if (file_exists($lib_path)) {
            ob_start();
            include $lib_path;
            $text = ob_get_clean();
            return "*/{$text}/*";
        }
        return "library not found: {$lib_path}";
    }

    public function include_lib_prebuild(string $lib_name, $builder) {
        $path = $this->component_path . "jslib" . DIRECTORY_SEPARATOR;
        $lib_path = "{$path}{$lib_name}";
        if (!preg_match("/.*\.js/i", $lib_path)) {
            $lib_path .= ".js";
        }
        $builder_name = "{$path}{$builder}";
        if (!preg_match("/.*\.php/i", $builder_name)) {
            $builder_name .= ".php";
        }
        if (file_exists($builder_name)) {
            ob_start();
            include $builder_name;
            ob_end_clean();
        } else {
            return "*/library not found: {$builder_name}/*";
        }
        if (file_exists($lib_path)) {
            ob_start();
            include $lib_path;
            $text = ob_get_clean();
            return "*/{$text}/*";
        }
        return "*/library not found: {$lib_path}/*";
    }

    /**
     * 
     * @param string $fqcn
     * @return \LoadableComponents\ComponentBuilder
     */
    public static function F(string $fqcn): ComponentBuilder {
        return new static($fqcn);
    }

    public function get_preference($preference_name) {
        return \PresetManager\PresetManager::F()->get_filtered($preference_name, ["Trim", "NEString", "DefaultEmptyString"]);
    }

}
