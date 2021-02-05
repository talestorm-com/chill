<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace PublicMedia\Writer\Gallery;

/**
 * Description of writer
 *
 * @author eve
 */

/**
 * Description of FilterPresetWriter
 *
 * @author eve
 * @property \DataMap\IDataMap $data_input
 * @property \DataMap\IDataMap $common_input
 * @property \DB\SQLTools\SQLBuilder $builder
 * @property string $temp_var
 * @property integer $result_id
 * @property string[] $messages
 * @property \DataMap\IDataMap $runtime
 * @property int $user_id
 * @property \PublicMedia\PublicMediaGallery $medial_object
 *
 */
class Writer {

    use \common_accessors\TCommonAccess;

    protected static $part_writers = [
        CommonWriter::class,
        TextWriter::class,
        TimeWriter::class,
    ];
    protected static $post_writers = [
        TagWriter::class,
        GalleryPreviewWriter::class,
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

    /** @var integer */
    protected $result_id;

    /** @var string[] */
    protected $messages;
    protected $user_id;

    /** @var \PublicMedia\PublicMediaGallery */
    protected $medial_object;

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

    public function __get__medial_object() {
        return $this->medial_object;
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
    }

    /**
     * @param \DataMap\IDataMap $data_input
     * @param \DataMap\IDataMap $common_input
     * @return \PublicMedia\Writer\Gallery\Writer
     */
    public static function F(\DataMap\IDataMap $data_input, \DataMap\IDataMap $common_input, int $user_id): Writer {
        return new static($data_input, $common_input, $user_id);
    }

    public function run(): int {
        foreach (static::$part_writers as $writer_class) {
            \Out\Out::F()->add("writer_iteration", $writer_class);
            $writer_class::F()->run($this);
        }
        $this->result_id = $this->builder->execute_transact($this->temp_var);
        try {
            $this->medial_object = \PublicMedia\PublicMediaGallery::F()->load_by_id($this->result_id);
        } catch (\Throwable $e) {
            $this->medial_object = null;
            throw $e;
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

}
