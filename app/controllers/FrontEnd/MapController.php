<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace controllers\FrontEnd;

class MapController extends \controllers\FrontEnd\AbstractFrontendController {

    public static function get_default_action() {
        return "Index";
    }

    public function actionIndex() {
        // списки не велики, используем все разом
        \smarty\SMW::F()->smarty->assign("map_controller_mode", "offline");
        $shops = \Basket\OfflineShopList::C();
        \smarty\SMW::F()->smarty->assign("shops", $shops);
        \smarty\SMW::F()->smarty->assign("marshaled_shops", $shops->marshall());
        $this->render_view($this->get_requested_layout("front/layout"), $this->get_requested_template("default"));
    }

    public function actionPartners() {
        \smarty\SMW::F()->smarty->assign("map_controller_mode", "partners");
        \smarty\SMW::F()->smarty->assign("shops", \Basket\PartnerList::C());
        $this->render_view($this->get_requested_layout("front/layout"), $this->get_requested_template("default"));
    }

    protected function API_get_partner() {
        $partner_id = $this->GP->get_filtered("id", ["IntMore0", "DefaultNull"]);
        if ($partner_id) {
            $partner = \Basket\PartnerList::C()->get_by_id($partner_id, null);
            $this->out->add("partner", $partner);
        } else {
            $this->out->add("partner", null);
        }
        $this->out->add("api_key", \PresetManager\PresetManager::F()->get_filtered("MAP_BOX_KEY", ['Trim', "NEString", "DefaultEmptyString"]));
    }

}
