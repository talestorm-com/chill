<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace PublicMedia\Writer\Item;

/**
 * Description of writer
 *
 * @author eve
 */

/**
 * Description of 
 *
 * @author eve
 * @property \DataMap\IDataMap $data_input
 * @property \DataMap\IDataMap $common_input
 * @property \DB\SQLTools\SQLBuilder $builder
 * @property string $temp_var
 * @property int $result_id
 * @property string[] $messages
 * @property \DataMap\IDataMap $runtime 
 * @property int $user_id
 * @property \PublicMedia\PublicMediaGallery $medial_object
 * @property UploadInfo $upload_info
 * @property \PublicMedia\PublicMediaItemShort $writed_item
 * 
 */
class Writer {

    use \common_accessors\TCommonAccess;

    protected static $part_writers = [
        CommonWriter::class,
    ];
    protected static $post_writers_safe = [
        Uploader::class,
    ];
    protected static $post_writers = [
        TagWriter::class,
        QtyWriter::class,
        ReTagger::class,
        CacheReset::class,
    ];

    //<editor-fold defaultstate="collapsed" desc="props">

    /** @var \DataMap\IDataMap */
    protected $data_input;

    /** @var \DataMap\IDataMap */
    protected $common_input;

    /** @var \DataMap\IDataMap */
    protected $runtime;

    /** @var \DB\SQLTools\SQLBuilder */
    protected $builder;

    /** @var string */
    protected $temp_var;

    /** @var int */
    protected $result_id;

    /** @var string[] */
    protected $messages;
    protected $user_id;

    /** @var \PublicMedia\PublicMediaGallery */
    protected $medial_object;

    /** @var UploadInfo */
    protected $upload_info;

    /** @var \PublicMedia\PublicMediaItemShort */
    protected $writed_item;

    //</editor-fold>
    //<editor-fold defaultstate="collapsed" desc="getters">

    /** @return \DataMap\IDataMap */
    protected function __get__data_input() {
        return $this->data_input;
    }

    /** @return \DataMap\IDataMap */
    protected function __get__common_input() {
        return $this->common_input;
    }

    /** @return \DB\SQLTools\SQLBuilder */
    protected function __get__builder() {
        return $this->builder;
    }

    /** @return string */
    protected function __get__temp_var() {
        return $this->temp_var;
    }

    /** @return integer */
    protected function __get__result_id() {
        return $this->result_id;
    }

    /** @return string[] */
    protected function __get__messages() {
        return $this->messages;
    }

    protected function __get__runtime() {
        return $this->runtime;
    }

    protected function __get__user_id() {
        return $this->user_id;
    }

    public function get_creator_id() {
        return $this->user_id;
    }

    public function check_write_access(int $object_owner) {
        return !!($object_owner === $this->user_id && $object_owner);
    }

    protected function __get__medial_object() {
        return $this->medial_object;
    }

    protected function __get__upload_info() {
        return $this->upload_info;
    }

    /** @return \PublicMedia\PublicMediaItemShort */
    protected function __get__writed_item() {
        return $this->writed_item;
    }

    //</editor-fold>





    public function __construct(\DataMap\IDataMap $data_input, \DataMap\IDataMap $common_input, int $user_id) {

        $this->data_input = $data_input;
        $this->common_input = $common_input;
        $this->builder = \DB\SQLTools\SQLBuilder::F();
        $this->temp_var = "@a" . md5(__METHOD__);
        $this->messages = [];
        $r = [];
        $this->runtime = \DataMap\CommonDataMap::F()->rebind($r);
        $this->user_id = $user_id;
        // gallery_id only requires on create and complitely useless on update if no transfer occured
        $gallery_id = $this->data_input->get_filtered("gallery_id", ["IntMore0", "DefaultNull"]);
        if ($gallery_id) {
            $this->init_medial_object($gallery_id);            
        }
        $this->upload_info = UploadInfo::F();
    }

    public function init_medial_object(int $id) {
        if ($id) {
            $this->medial_object = \PublicMedia\PublicMediaGalleryShort::F()->load_by_id($id);
            $this->medial_object && $this->medial_object->valid && $this->medial_object->owner_id === $this->user_id ? 0 : $this->medial_object = null;
        }
    }

    /**
     * @param \DataMap\IDataMap $data_input
     * @param \DataMap\IDataMap $common_input
     * @return \PublicMedia\Writer\Item\Writer
     */
    public static function F(\DataMap\IDataMap $data_input, \DataMap\IDataMap $common_input, int $user_id): Writer {
        return new static($data_input, $common_input, $user_id);
    }

    public function run(): int {
        foreach (static::$part_writers as $writer_class) {
            $writer_class::F()->run($this);
        }
        if (!$this->upload_info->file && $this->runtime->get("mode") === 'create') {
            \Errors\common_error::R("no image or video found in upstream, or it has unknown format");
        }
        if (!$this->upload_info->measurement && $this->runtime->get("mode") === 'create') {
            \Errors\common_error::R("no image or video found in upstream, or it has unknown format");
        }
        $this->result_id = $this->builder->execute_transact($this->temp_var);
        $this->writed_item = \PublicMedia\PublicMediaItemShort::F()->load($this->result_id);
        foreach (static::$post_writers_safe as $writer_class) {
            try {
                $writer_class::F()->run($this);
            } catch (\Throwable $e) {
                $this->rollback();
                throw $e;
            }
        }
        foreach (static::$post_writers as $writer_class) {
            try {
                $writer_class::F()->run($this);
            } catch (\Throwable $e) {
                $this->messages[] = $e->getMessage();
            }
        }
        return $this->result_id;
    }

    public function append_message(string $s) {
        $this->messages[] = $s;
    }

    protected function rollback() {
        if ($this->runtime->get("mode") === "create") {
            \DB\SQLTools\SQLBuilder::F()->push("DELETE FROM public__gallery__item WHERE id=:P ;")
                    ->push_params([":P" => $this->result_id])->execute();
        }
    }

}
