<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace ProtectedMedia;

/**
 * Description of ProtectedGalleryItemUpdater
 *
 * @author eve
 */
class ProtectedGalleryItemUpdater {

    /** @var \DataMap\IDataMap */
    private $input;

    /** @var int */
    private $user_id = null;

    protected function __construct(\DataMap\IDataMap $input, int $user_id = null) {
        $this->input = $input;
        $this->user_id = intval($user_id);
    }

    public static function F(\DataMap\InputDataMap $input, int $user_id = null) {
        return new static($input, $user_id);
    }

    /**
     * incremental update for gallery item and returns its uid
     * @param \DB\SQLTools\SQLBuilder $b
     * @return string
     */
    public function run(\DB\SQLTools\SQLBuilder $b, &$restore_aspect = false): string {
        $b->inc_counter();
        $uid = $this->input->get_filtered('uid', ["Strip", "Trim", "NEString"]);
        \Filters\FilterManager::F()->raise_array_error(['uid' => $uid]);
        $data = \Filters\FilterManager::F()->apply_filter_datamap($this->input, [
            'title' => ["Trim"],
            'preset' => ["Trim"],
            "info" => ["Trim"],
            'version' => ["IntMore0"],
            'aspect' => ["Float"],
        ]);
        if ($data['aspect'] === -1.0) {
            unset($data["aspect"]);
            $restore_aspect = true;
        }
        $cdata = [];
        foreach ($data as $key => $value) {
            if ($value instanceof \Filters\EmptyValue) {
                continue;
            }
            $cdata[$key] = $value;
        }

        \Filters\FilterManager::F()->raise_array_error($cdata);
        $control = \Filters\FilterManager::F()->apply_filter_datamap($this->input, [
            "check_version" => ["Int", "DefaultNull"],
        ]);
        if (count($cdata)) {
            $qp = "UPDATE protected__gallery__item SET %s WHERE uid=:P{$b->c}uid AND owner_id=:P{$b->c}owner %s;";
            $ups = [];
            $c = 0;
            foreach ($cdata as $key => $value) {
                $key_param = sprintf(":P%d_%d_%s", $b->counter, $c, $key);
                $ups[] = sprintf(" `%s`=%s ", $key, $key_param);
                $b->push_param($key_param, $value);
                $c++;
            }
            if (count($ups)) {

                $qend = "";
                if ($control["check_version"] !== null) {
                    $qend = " AND version=:P{$b->c}cver ";
                    $b->push_param(":P{$b->c}cver", $control['check_version']);
                }
                $b->push(sprintf($qp, implode(",", $ups), $qend));
                $b->push_params([
                    ":P{$b->c}uid" => $uid,
                    ":P{$b->c}owner" => $this->user_id ? $this->user_id : (\Auth\Auth::F()->is_authentificated() ? \Auth\Auth::F()->get_id() : null ),
                ]);
            }
        }
        $b->inc_counter();
        return $uid;
    }

}
