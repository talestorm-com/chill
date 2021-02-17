<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Content\MediaContentFront\MediaContentFrontSOAP;

/**
 * Description of SeriesListItem
 *
 * @author eve
 * @property int $id
 * @property int $season_id
 * @property boolean $vertical
 * @property float $price
 * @property int $num
 * @property string $common_name
 * @property string $name
 * @property string $intro
 * @property string $info
 * @property string $default_poster
 * @property \Content\IImageCollection $images
 * @property \Content\MediaContentFront\FileList\FileList $files
 * @property string $image_url
 * @property string $default_preview
 * @property Float $duration
 * 
 */
class SeriesListItem implements \common_accessors\IMarshall {

    use \common_accessors\TCommonAccess,
        \common_accessors\TDefaultMarshaller,
        \common_accessors\TCommonImport;


    //<editor-fold defaultstate="collapsed" desc="props">

    /** @var int */
    protected $id;

    /** @var int */
    protected $season_id;

    /** @var boolean */
    protected $vertical;

    /** @var float */
    protected $price;

    /** @var int */
    protected $num;

    /** @var string */
    protected $common_name;

    /** @var string */
    protected $name;

    /** @var string */
    protected $intro;

    /** @var string */
    protected $info;

    /** @var string */
    protected $default_poster;

    /** @var \Content\IImageCollection */
    protected $images;

    /** @var \Content\MediaContentFront\FileList\FileList */
    protected $files;

    /** @var string */
    protected $default_preview;

    //</editor-fold>
    //<editor-fold defaultstate="collapsed" desc="getters">

    /** @return int */
    protected function __get__id() {
        return $this->id;
    }

    /** @return int */
    protected function __get__season_id() {
        return $this->season_id;
    }

    /** @return boolean */
    protected function __get__vertical() {
        return $this->vertical;
    }

    /** @return float */
    protected function __get__price() {
        return $this->price;
    }

    /** @return int */
    protected function __get__num() {
        return $this->num;
    }

    /** @return string */
    protected function __get__common_name() {
        return $this->common_name;
    }

    /** @return string */
    protected function __get__name() {
        return $this->name;
    }

    /** @return string */
    protected function __get__intro() {
        return $this->intro;
    }

    /** @return string */
    protected function __get__info() {
        return $this->info;
    }

    /** @return string */
    protected function __get__default_poster() {
        return $this->default_poster;
    }

    /** @return \Content\IImageCollection */
    protected function __get__images() {
        return $this->images;
    }

    /** @return \Content\MediaContentFront\FileList\FileList */
    protected function __get__files() {
        return $this->files;
    }

    protected function __get__image_url() {
        if ($this->default_preview) {
            return sprintf("/media/%s/%s/%s", \Content\MediaContent\Readers\ctSEASON\MediaContentObject::MEDIA_CONTEXT_PREVIEW, $this->id, $this->default_preview);
        }
        if ($this->default_poster) {
            return sprintf("/media/%s/%s/%s", \Content\MediaContent\Readers\ctSEASONSERIES\MediaContentObject::MEDIA_CONTEXT_POSTERS, $this->id, $this->default_poster);
        }
        if (count($this->images)) {
            $image = $this->images->get_image_by_index();
            return sprintf("/media/%s/%s/%s", $image->context, $image->owner_id, $image->image);
        }
        return sprintf("/media/fallback/1/%s", \Content\MediaContent\Readers\ctSEASONSERIES\MediaContentObject::MEDIA_CONTEXT_POSTERS);
    }

    /** @return string */
    protected function __get__default_preview() {
        return $this->default_preview;
    }

    protected function __get__duration() {
        if (count($this->files)) {
            try {
                $file = $this->files->items[0];
                $jsi = json_decode($file->info, true);
                if (is_array($jsi) && array_key_exists('advanced', $jsi) && is_array($jsi['advanced'])) {
                    $tt = $jsi['advanced'];
                    if (array_key_exists('format', $tt) && is_array($tt['format']) && array_key_exists('duration', $tt['format'])) {
                        return \Filters\FilterManager::F()->apply_chain($tt['format']['duration'], ['Float', 'DefaultNull']);
                    } else if (array_key_exists('video_streams', $tt) && is_array($tt['video_streams']) && count($tt['video_streams']) && is_array($tt['video_streams'][0]) && array_key_exists('duration', $tt['video_streams'][0])) {
                        return \Filters\FilterManager::F()->apply_chain($tt['video_streams'][0]['duration'], ['Float', 'DefaultNull']);
                    } else if (array_key_exists('audio_streams', $tt) && is_array($tt['audio_streams']) && count($tt['audio_streams']) && is_array($tt['audio_streams'][0]) && array_key_exists('duration', $tt['audio_streams'][0])) {
                        return \Filters\FilterManager::F()->apply_chain($tt['audio_streams'][0]['duration'], ['Float', 'DefaultNull']);
                    }
                }
            } catch (\Throwable $e) {
                
            }
        }
        return null;
    }

    //</editor-fold>

    public function __construct(array $data) {
        $this->import_props($data);
    }

    /**
     * 
     * @param array $data
     * @return \static
     */
    public static function F(array $data) {
        return new static($data);
    }

    protected function t_common_import_get_filters(): array {
        return [
            'id' => ['IntMore0'], //int
            'season_id' => ['IntMore0'], //int
            'vertical' => ['Boolean', 'DefaultFalse'], //boolean
            'price' => ['Float', 'Default0'], //float
            'num' => ['IntMore0'], //int
            'common_name' => ['Strip', 'Trim', 'NEString'], //string
            'name' => ['Strip', 'Trim', 'NEString'], //string
            'intro' => ['Trim', 'NEString', 'DefaultEmptyString'], //string
            'info' => ['Trim', 'NEString', 'DefaultEmptyString'], //string
            'default_poster' => ['Strip', 'Trim', 'NEString', 'DefaultNull'], //string            
            'default_preview' => ['Strip', 'Trim', 'NEString', 'DefaultNull'], //string            
        ];
    }

    protected function t_common_import_after_import() {
        if ($this->id) {
            $this->images = \Content\DefaultImageCollection::F(\Content\MediaContent\Readers\ctSEASONSERIES\MediaContentObject::MEDIA_CONTEXT_POSTERS, $this->id);
            $this->files = \Content\MediaContentFront\FileList\FileList::F()->load($this->id);
        }
    }
    
    protected function t_default_marshaller_on_props_to_marshall( &$props) {
        $props['duration']='duration';
    }
    
    protected function t_default_marshaller_export_property_duration() {
        return $this->__get__duration();
    }

}
