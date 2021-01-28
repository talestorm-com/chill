<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace ImageFly;

/**
 * Description of FormImageUploader
 *
 * @author eve
 * @property string $field_name
 * @property string $owner_id
 * @property string $context
 * @property bool $url_mode
 * @property \DataMap\IDataMap $input
 * @property FormImageUploaderLog $log
 * 
 * 
 */
class FormImageUploader {

    use \common_accessors\TCommonAccess;

    //<editor-fold defaultstate="collapsed" desc="props">

    /** @var string */
    protected $field_name;

    /** @var string */
    protected $owner_id;

    /** @var string */
    protected $context;

    /** @var bool */
    protected $url_mode;

    /** @var \DataMap\IDataMap */
    protected $input;

    /** @var FormImageUploaderLog */
    protected $log;

    //</editor-fold>
    //<editor-fold defaultstate="collapsed" desc="getters">

    /** @return string */
    protected function __get__field_name() {
        return $this->field_name;
    }

    /** @return string */
    protected function __get__owner_id() {
        return $this->owner_id;
    }

    /** @return string */
    protected function __get__context() {
        return $this->context;
    }

    /** @return bool */
    protected function __get__url_mode() {
        return $this->url_mode;
    }

    /** @return \DataMap\IDataMap */
    protected function __get__input() {
        return $this->input;
    }

    /** @return FormImageUploaderLog */
    protected function __get__log() {
        return $this->log;
    }

    //</editor-fold>








    public function __construct(string $field_name, string $context, string $owner_id, \DataMap\IDataMap $input = null) {
        $this->field_name = $field_name;
        $this->context = $context;
        $this->owner_id = $owner_id;
        $this->input = $input ? $input : \DataMap\InputDataMap::F();
        $mode = $this->input->get_filtered("{$this->field_name}_transfer_mode", ["Strip", "Trim", "NEString", "DefaultEmptyString"]);
        $this->url_mode = (strcasecmp($mode, "content/data-url") === 0);
        $this->log = FormImageUploaderLog::F();
    }

    /**
     * 
     * @param string $field_name
     * @param string $context
     * @param string $owner_id
     * @param \DataMap\IDataMap $input
     * @return \ImageFly\FormImageUploader
     */
    public static function F(string $field_name, string $context, string $owner_id, \DataMap\IDataMap $input = null): FormImageUploader {
        return new static($field_name, $context, $owner_id, $input);
    }

    public function run(): FormImageUploader {
        \ImageFly\MediaContextInfo::F()->context_exists($this->context) ? FALSE : \ImageFly\ImageFlyError::RF("unknown media context `%s`", $this->context);
        if ($this->url_mode) {
            $this->run_url_mode();
        } else {
            $this->run_common_mode();
        }
        return $this;
    }

    protected function run_url_mode() {
        var_dump($this->input);
        die();
    }

    protected function run_common_mode() {
        $uploads = $this->input->get_filtered("{$this->field_name}_uploads", ["NEArray", "ArrayOfNEString", "NEArray", "DefaultEmptyArray"]);
        $removes = $this->input->get_filtered("{$this->field_name}_removes", ["NEArray", "ArrayOfNEString", "NEArray", "DefaultNull"]);
        $orders = $this->input->get_filtered("{$this->field_name}_orders", ["NEArray", "ArrayOfNEString", "NEArray", "DefaultNull"]);
        $orr = [];
        $orr['uploads'] = $uploads;
        $orr['u2'] = [];
        $orr['b'] = [];
        foreach ($uploads as $upload_id) {
            $field_name = "{$this->field_name}_file_{$upload_id}";
            $uploaded_file = \DataMap\FileMap::F()->get_by_field_name($field_name);

            if (count($uploaded_file) === 1) {
                try {
                    $orr['b'][]=$upload_id;
                    $orr['u2'][] = md5($upload_id . "0");
                    ImageFly::F()->process_upload_manual($this->context, $this->owner_id, $upload_id, $uploaded_file[0]);
                } catch (\Throwable $e) {
                    $this->log->on_error($e);
                }
            } else if (count($uploaded_file) > 1) {
                for ($i = 0; $i < count($uploaded_file); $i++) {
                    try {
                        $orr['u2'][] = md5($upload_id . $i);
                        ImageFly::F()->process_upload_manual($this->context, $this->owner_id, ($i===0?$upload_id:md5($upload_id . $i)), $uploaded_file[$i]);
                    } catch (\Throwable $e) {
                        $this->log->on_error($e);
                    }
                }
            }
        }
        \Out\Out::F()->add("uplog", $orr);
        if ($removes) {
            foreach ($removes as $remove) {
                ImageFly::F()->remove_image($this->context, $this->owner_id, $remove);
            }
        }
        \Out\Out::F()->add("orders", $orders);
        if ($orders) {
            \ImageFly\ImageInfoManager::F()->reorder_images($this->context, $this->owner_id, $orders);
        }
    }

}
