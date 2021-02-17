<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace controllers\FrontEnd;

/**
 * Description of FilterPresetController
 *
 * @author eve
 */
class FilterPresetController extends AbstractFrontendController {

    protected function API_list() {
        \Content\FilterPreset\FrontLister::F(\DataMap\InputDataMap::F())->run($this->out);
    }

    protected function API_list_filters() {
        \Content\FilterPreset\PresetFrontLister::F(\DataMap\InputDataMap::F())->run($this->out);
    }

    protected function API_package_info() {
        $id = \DataMap\InputDataMap::F()->get_filtered("id", ["IntMore0"]);
        \Filters\FilterManager::F()->raise_array_error(compact('id'));
        $item = \Content\FilterPreset\FilterPreset::C($id);
        $item->active ? 0 : \Errors\common_error::R("not found");
        $item->marshall_mode = \Content\FilterPreset\FilterPreset::MARSHALL_MODE_TRIM;
        $this->out->add('package', $item);
        if (\DataMap\InputDataMap::F()->get_filtered("with_acl", ["Boolean", "DefaultFalse"])) {
            $acl = [];
            $key = implode("", [\Content\FilterPreset\FilterPreset::ACCESS_KEY, $item->id]);
            if ($item->cost > 0) {
                $acl[$key] = \Auth\ProductAccessMonitor::F()->has_access_to_preset((string) $item->id);
            } else {
                $acl[$key] = true;
            }
            $this->out->add("acl", $acl);
        }
    }

    protected function API_preset_info() {
        $id = \DataMap\InputDataMap::F()->get_filtered("id", ["IntMore0"]);
        $uid = \DataMap\InputDataMap::F()->get_filtered("uid", ["Strip", "Trim", "NEString"]);
        \Filters\FilterManager::F()->raise_array_error(compact('id', 'uid'));
        $item = \Content\FilterPreset\FilterPresetItem::C($id, $uid);
        $item->marshall_mode = \Content\FilterPreset\FilterPreset::MARSHALL_MODE_TRIM;
        $this->out->add('preset', $item);
        if (\DataMap\InputDataMap::F()->get_filtered("with_acl", ["Boolean", "DefaultFalse"])) {
            $acl = [];
            $key = implode("", [\Content\FilterPreset\FilterPreset::ACCESS_KEY, $item->id]);
            if ($item->package_cost > 0) {
                $acl[$key] = \Auth\ProductAccessMonitor::F()->has_access_to_preset((string) $item->id);
            } else {
                $acl[$key] = true;
            }
            $this->out->add("acl", $acl);
        }
    }

    protected function API_get_preset() {
        $id = \DataMap\InputDataMap::F()->get_filtered("id", ["IntMore0"]);
        $uid = \DataMap\InputDataMap::F()->get_filtered("uid", ["Strip", "Trim", "NEString"]);
        \Filters\FilterManager::F()->raise_array_error(compact('id', 'uid'));
        $item = \Content\FilterPreset\FilterPresetItem::C($id, $uid);
        if ($item->package_cost > 0) {
            \Auth\ProductAccessMonitor::F()->has_access_to_preset((string) $item->id) ? 0 : \Auth\AuthError::R(\Auth\AuthError::ACCESS_DENIED);
        }
        $this->out->add("preset_content", $item->preset);
    }

    protected function API_get_preset2() {
        $uid = \DataMap\InputDataMap::F()->get_filtered("uid", ["Strip", "Trim", "NEString"]);
        \Filters\FilterManager::F()->raise_array_error(compact('uid'));
        $item = \Content\FilterPreset\FilterPresetItem::C2($uid);
        if ($item->package_cost > 0) {
            \Auth\ProductAccessMonitor::F()->has_access_to_preset((string) $item->id) ? 0 : \Auth\AuthError::R(\Auth\AuthError::ACCESS_DENIED);
        }
        $this->out->add("preset_content", $item->preset);
    }

}
