<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace controllers\FrontEnd;

/**
 * Description of PublicTutorialController
 *
 * @author eve
 */
class PublicTutorialController extends AbstractFrontendController {

    //put your code here
    protected function API_list() {
        \Content\Video\FrontLister::F(\DataMap\InputDataMap::F())->run($this->out);
    }

    protected function API_get() {
        $id = \DataMap\InputDataMap::F()->get_filtered("id", ["IntMore0"]);
        \Filters\FilterManager::F()->raise_array_error(compact('id'));
        $item = \Content\Video\VideoGroup::C($id);
        $item->active ? 0 : \Errors\common_error::R("not found");
        $item->marshall_mode = \Content\Video\VideoGroup::MARSHALL_MODE_TRIM;
        $this->out->add('package', $item);
        if (\DataMap\InputDataMap::F()->get_filtered("with_acl", ["Boolean", "DefaultFalse"])) {
            $acl = [];
            $key = implode("", [\Content\Video\VideoGroup::ACCESS_KEY, $item->id]);
            if ($item->cost > 0) {
                $acl[$key] = \Auth\ProductAccessMonitor::F()->has_access_to_tutorial((string) $item->id);
            } else {
                $acl[$key] = true;
            }
            $this->out->add("acl", $acl);
        }
    }
    
    protected function API_get_tutorial(){
        $id = \DataMap\InputDataMap::F()->get_filtered("id", ["IntMore0"]);
        $uid = \DataMap\InputDataMap::F()->get_filtered("uid", ["Strip", "Trim", "NEString"]);
        \Filters\FilterManager::F()->raise_array_error(compact('id', 'uid'));
        $item = \Content\Video\VideoItem::C($id, $uid);
        $item->marshall_mode = \Content\Video\VideoGroup::MARSHALL_MODE_TRIM;
        $this->out->add('tutorial', $item);
        if (\DataMap\InputDataMap::F()->get_filtered("with_acl", ["Boolean", "DefaultFalse"])) {
            $acl = [];
            $key = implode("", [\Content\Video\VideoGroup::ACCESS_KEY, $item->id]);
            if ($item->package_cost > 0) {
                $acl[$key] = \Auth\ProductAccessMonitor::F()->has_access_to_tutorial((string) $item->id);
            } else {
                $acl[$key] = true;
            }
            $this->out->add("acl", $acl);
        }
    }

    

}
