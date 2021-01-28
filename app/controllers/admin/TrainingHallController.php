<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace controllers\admin;

/**
 * Description of TrainingHallController
 *
 * @author eve
 */
class TrainingHallController extends AbstractAdminController {

    public function get_desktop_component_id() {
        return "desktop.TrainingHall";
    }

    public function actionIndex() {
        $this->render_view('admin', '../common_index');
    }

    protected function API_list() {
        \Content\TrainingHall\TrainingHallLister::F(\DataMap\InputDataMap::F())->run($this->out);
    }

    protected function API_meta() {
        $r = [
            'dadata_key' => \PresetManager\PresetManager::F()->get_filtered('DADATA_KEY', ['Trim', 'NEString', 'DefaultNull']),
            'features_icons' => $this->get_features_icons(),
        ];
        $this->out->add('meta', $r);
    }

    protected function get_features_icons() {
        $root_dir = \Config\Config::F()->WEB_ROOT . "assets" . DIRECTORY_SEPARATOR . "features" . DIRECTORY_SEPARATOR;
        $result = [];
        if (!(file_exists($root_dir) && is_dir($root_dir) && is_readable($root_dir))) {
            @mkdir($root_dir, 0777, true);
        }
        if ((file_exists($root_dir) && is_dir($root_dir) && is_readable($root_dir))) {
            $lst = scandir($root_dir);
            foreach ($lst as $file) {
                $path = $root_dir . $file;
                if (file_exists($path) && is_file($path) && is_readable($path)) {
                    if (preg_match("/\.(jpg|png)$/i", $file)) {
                        $result[] = $file;
                    }
                }
            }
        }
        return $result;
    }

    protected function API_get_features_icons() {
        $this->out->add("features", $this->get_features_icons());
    }

    protected function API_get(int $rid = null) {
        $id = $rid ? $rid : \DataMap\InputDataMap::F()->get_filtered('id', ['IntMore0', 'DefaultNull']);
        $id ? 0 : \Errors\common_error::R("invalid request");
        $hal = \Content\TrainingHall\TrainingHall::F($id);
        //$hal && $hal->valid ? 0 : \Errors\common_error::R("not found");
        $this->out->add('hall', $hal);
        $this->API_meta();
    }

    protected function API_post() {
        $input = \DataMap\InputDataMap::F();
        $json = $input->get_filtered("data", ["NEString", "JSONString", "DefaultNull"]);
        $json ? 0 : \Errors\common_error::R("invalid input");
        $data_input = \DataMap\CommonDataMap::F()->rebind($json);
        $rid = \Content\TrainingHall\writer\writer::F($data_input, $input)->run();
        $this->API_get($rid);
    }

    protected function API_remove() {
        $id = \DataMap\InputDataMap::F()->get_filtered("id_to_remove", ["IntMore0", "DefaultNull"]);
        if ($id) {
            \Content\TrainingHall\Remover::F()->run($id);
        }
        $this->API_list();
    }

}
