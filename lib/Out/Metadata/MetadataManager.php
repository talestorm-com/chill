<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Out\Metadata;

/**
 * @property string $title
 * @property string $description
 * @property string $keywords
 * @property string $og_url
 * @property string $og_title
 * @property string $og_description
 * @property string $og_locale
 * @property bool $og_support
 * @property bool $og_image_support
 * @property string $sv_title
 * @property string $sv_keywords
 * @property string $sv_description
 * @property string $sv_og_title
 * @property string $sv_og_description
 * @property string $sv_og_image
 * @property string $og_image_context
 * @property string $og_image_owner
 * @property string $og_image  

 */
class MetadataManager implements \common_accessors\IMarshall {

    use \common_accessors\TCommonAccess,
        \common_accessors\TDefaultMarshaller;

    //<editor-fold defaultstate="collapsed" desc="props">

    /** @var string */
    protected $title;

    /** @var string */
    protected $description;

    /** @var string */
    protected $keywords;

    /** @var string */
    protected $og_title;

    /** @var string */
    protected $og_description;

    /** @var string */
    protected $og_locale;

    /** @var string */
    protected $og_image;

    /** @var string */
    protected $og_image_context;

    /** @var string */
    protected $og_image_owner;

    /** @var bool */
    protected $og_support;

    /** @var bool */
    protected $og_image_support;

    /** @var bool */
    public $show_title_prefix;

    //</editor-fold>
    //<editor-fold defaultstate="collapsed" desc="getters">

    /** @return string */
    protected function __get__title() {
        return $this->title;
    }

    protected function __get__sv_title() {
        $prefix = \PresetManager\PresetManager::F()->get_filtered(\PresetManager\PresetManager::PAGE_DEFAULT_TITLE, ["Strip", "Trim", "NEString", "DefaultNull"]);
        $prefix ? 0 : $prefix = "website";
        $prefix_split = \PresetManager\PresetManager::F()->get_filtered(\PresetManager\PresetManager::PAGE_TITLE_SEPARATOR, ["Strip", "NEString", "DefaultNull"]);
        $prefix_split ? 0 : $prefix_split = " ";
        $current_title = \Filters\FilterManager::F()->apply_chain($this->title, ["Strip", "Trim", "NEString", "DefaultNull"]);
        if (!$this->show_title_prefix) {
            return $current_title;
        }
        return $current_title ? implode($prefix_split, [$prefix, $current_title]) : $prefix;
    }

    /** @return string */
    protected function __get__description() {
        return $this->description;
    }

    protected function __get__sv_description() {
        $description = \Filters\FilterManager::F()->apply_chain($this->description, ["Strip", "Trim", "NEString", "DefaultNull"]);
        if (!$description) {
            $description = \PresetManager\PresetManager::F()->get_filtered(\PresetManager\PresetManager::PAGE_DEFAULT_DESCRIPTION, ["Strip", "Trim", "NEString", "DefaultEmptyString"]);
        }
        return $description;
    }

    /** @return string */
    protected function __get__keywords() {
        return $this->keywords;
    }

    protected function __get__sv_keywords() {
        $keywords = \Filters\FilterManager::F()->apply_chain($this->keywords, ["Strip", "Trim", "NEString", "DefaultNull"]);
        if (!$keywords) {
            $keywords = \PresetManager\PresetManager::F()->get_filtered(\PresetManager\PresetManager::PAGE_DEFAULT_KEYWORDS, ["Strip", "Trim", "NEString", "DefaultEmptyString"]);
        }
        return $keywords;
    }

    /** @return string */
    protected function __get__og_url() {
        $rq = \Router\Request::F();
        $path = strip_tags(trim($rq->request_path, "\\/"));  
        return "{$rq->request_protocol}://{$rq->host}/{$path}";
        //return $this->og_url;
    }

    /** @return string */
    protected function __get__og_title() {
        return $this->og_title;
    }

    protected function __get__sv_og_title() {
        $result = \Filters\FilterManager::F()->apply_chain($this->og_title, ["Strip", "Trim", "NEString", "DefaultNull"]);
        return $result ? $result : $this->__get__sv_title();
    }

    /** @return string */
    protected function __get__og_description() {
        return $this->og_description;
    }

    protected function __get__sv_og_description() {
        $result = \Filters\FilterManager::F()->apply_chain($this->og_description, ["Strip", "Trim", "NEString", "DefaultNull"]);
        return $result ? $result : $this->__get__description();
    }

    /** @return string */
    protected function __get__og_locale() {
        return $this->og_locale;
    }

    /** @return string */
    protected function __get__og_image() {
        return $this->og_image;
    }

    /** @return string */
    protected function __get__og_image_context() {
        return $this->og_image_context;
    }

    /** @return string */
    protected function __get__og_image_owner() {
        return $this->og_image_owner;
    }

    protected function __get__sv_og_image() {
        $context = \Filters\FilterManager::F()->apply_chain($this->og_image_context, ["Strip", "Trim", "NEString", "DefaultNull"]);
        $owner_id = \Filters\FilterManager::F()->apply_chain($this->og_image_owner, ["Strip", "Trim", "NEString", "DefaultNull"]);
        $image = \Filters\FilterManager::F()->apply_chain($this->og_image, ["Strip", "Trim", "NEString", "DefaultNull"]);
        $rq = \Router\Request::F();
        if ($context && $owner_id && $image) {
            return "{$rq->request_protocol}://{$rq->host}/media/{$context}/{$owner_id}/{$image}.SW_400H_520.jpg";
        }
        if (!\ImageFly\MediaContextInfo::F()->context_exists("social_fallback")) {
            \ImageFly\MediaContextInfo::F()->register_media_context("social_fallback", 1600, 1600, 400, 400);
        }
        return "{$rq->request_protocol}://{$rq->host}/media/fallback/1/social_fallback.SW_300H_300.jpg";
    }

    /** @return bool */
    protected function __get__og_support() {
        return $this->og_support;
    }

    /** @return bool */
    protected function __get__og_image_support() {
        return $this->og_image_support;
    }

    //</editor-fold>

    public function __construct() {
        $this->og_locale = \PresetManager\PresetManager::F()->get_filtered(\PresetManager\IPresetsKeys::META_OG_LOCALE, ["Strip", "Trim", "NEString", "DefaultNull"]);
        $this->og_locale ? 0 : $this->og_locale = "ru_RU";
        $this->og_support = true;
        $this->og_image_support = true;
        $this->show_title_prefix = true;
    }

    /**
     * 
     * @return \Out\Metadata\MetadataManager
     */
    public static function F(): MetadataManager {
        return new static();
    }

    /**
     * 
     * @param string $title
     * @return $this
     */
    public function set_title(string $title = null) {
        $this->title = \Helpers\Helpers::NEString($title, null);
        return $this;
    }

    /**
     * 
     * @param string $og_title
     * @return $this
     */
    public function set_og_title(string $og_title = null) {
        $this->og_title = \Helpers\Helpers::NEString($og_title, null);
        return $this;
    }

    /**
     * 
     * @param bool $support
     * @return $this
     */
    public function set_og_support(bool $support) {
        $this->og_support = $support;
        return $this;
    }

    /**
     * 
     * @param string $description
     * @return $this
     */
    public function set_description(string $description = null) {
        $this->description = \Helpers\Helpers::NEString($description, null);
        return $this;
    }

    /**
     * 
     * @param string $keywords
     * @return $this
     */
    public function set_keywords(string $keywords = null) {
        $this->keywords = \Helpers\Helpers::NEString($keywords, null);
        return $this;
    }

    /**
     * 
     * @param string $og_description
     * @return $this
     */
    public function set_og_description(string $og_description = null) {
        $this->og_description = \Helpers\Helpers::NEString($og_description, null);
        return $this;
    }

    /**
     * 
     * @param bool $og_image_support
     * @return $this
     */
    public function set_og_image_support(bool $og_image_support) {
        $this->og_image_support = $og_image_support;
        return $this;
    }

    /**
     * 
     * @param string $context
     * @param mixed $owner_id
     * @param string $image
     * @return $this
     */
    public function set_og_image_data(string $context = null, $owner_id = null, string $image = null) {
        $this->og_image_context = \Helpers\Helpers::NEString($context, null);
        $this->og_image_owner = \Helpers\Helpers::NEString($owner_id, null);
        $this->og_image = \Helpers\Helpers::NEString($image, null);
        return $this;
    }

    /**
     * 
     * @param \Out\Metadata\IMetadataSupport $mso
     */
    public function set_metadata(IMetadataSupport $mso) {
        $this->title = $mso->meta_get_title();
        $this->description = $mso->meta_get_description();
        $this->keywords = $mso->meta_get_keywords();
        $this->og_support = $mso->meta_get_og_support();
        if ($this->og_support) {
            $this->og_title = $mso->meta_get_og_title();
            $this->og_description = $mso->meta_get_og_description();
            $this->og_image_support = $mso->meta_get_og_image_support();
            if ($this->og_image_support) {
                $this->og_image_context = $mso->meta_get_og_image_context();
                $this->og_image_owner = $mso->meta_get_og_image_owner();
                $this->og_image = $mso->meta_get_og_image_image();
            }
        }
    }

    public function render() {
        $smarty = \smarty\SMW::F()->smarty;
        $old_this = $smarty->getTemplateVars('this');
        $smarty->assign('this', $this);
        $result = $smarty->fetch(\Config\Config::F()->VIEW_PATH . "metadata" . DIRECTORY_SEPARATOR . "meta_common.tpl");
        $smarty->assign("this", $old_this);
        return $result;
    }

}
