<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace controllers\admin;

class PresetsController extends AbstractAdminController {
   
    
    public function get_desktop_component_id() {
        return "desktop.presets";
    }

    public function actionIndex() {        
        $this->render_view('admin', '../common_index');
    }

    protected function API_list() {
        $this->out->add("presets", \PresetManager\PresetManager::F());
    }

    protected function API_new() {

        $preset_name = $this->GP->get_filtered("name", ['Strip', 'Trim', 'NEString', 'DefaultNull']);
        $preset_name ? 0 : \Errors\common_error::R("invalid request");
        $preset_name = preg_replace("/\s/i", "_", $preset_name);
        $preset_name = \Filters\FilterManager::F()->apply_chain($preset_name, ['Strip', 'Trim', 'NEString', 'DefaultNull']);
        $preset_name ? 0 : \Errors\common_error::R("invalid request");
        \PresetManager\PresetManager::F()->exists($preset_name) ? \Errors\common_error::RF("`%s` alredy exists", $preset_name) : 0;
        \PresetManager\PresetManager::F()->set($preset_name, "");        
        \PresetManager\PresetManager::F()->flush();
        \PresetManager\PresetManager::release_singleton();
        $this->API_list();
    }

    protected function API_remove() {
        $preset_name = $this->GP->get_filtered("id", ['Strip', 'Trim', 'NEString', 'DefaultNull']);
        $preset_name ? 0 : \Errors\common_error::R("invalid request");
        \PresetManager\PresetManager::F()->exists($preset_name) ? 0 : \Errors\common_error::RF("`%s` not exists", $preset_name);
        \PresetManager\PresetManager::F()->remove($preset_name);
        \PresetManager\PresetManager::F()->flush();
        \PresetManager\PresetManager::release_singleton();
        $this->API_list();
    }

    protected function API_set() {
        $preset_name = $this->GP->get_filtered("name", ['Strip', 'Trim', 'NEString', 'DefaultNull']);
        $preset_name ? 0 : \Errors\common_error::R("invalid request");
        \PresetManager\PresetManager::F()->exists($preset_name) ? 0 : \Errors\common_error::RF("`%s` not exists", $preset_name);
        $value = $this->GP->get_filtered_def("value", ['NEString', 'DefaultNull']);
        \PresetManager\PresetManager::F()->set($preset_name, $value);
        \PresetManager\PresetManager::F()->flush();
        \PresetManager\PresetManager::release_singleton();
        $this->out->add("new", \PresetManager\PresetManager::F()->get_filtered($preset_name, ['NEString', 'DefaultNull']));
    }

}
