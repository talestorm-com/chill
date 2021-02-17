<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace controllers\FrontEnd;

/**
 * Description of SearchController
 *
 * @author eve
 */
class SearchController extends AbstractFrontendController {

    //put your code here

    public static function get_default_action() {
        return "Index";
    }

    public function actionIndex() {

        $this->render_view($this->get_requested_layout("/front/layout"), $this->get_requested_template("default"));
    }

    public function actionSearch() {
        $term = \DataMap\InputDataMap::F()->get_filtered("query", ['Strip', 'Trim', 'NEString', 'SQLSafeString', 'DefaultNull']);
        $token = \DataMap\InputDataMap::F()->get_filtered("token", ['Strip', 'Trim', 'NEString', 'DefaultEmptyString']);
        $this->check_csrf_throw($token, 'search', false);
        $results = null;
        if ($term && mb_strlen($term, 'UTF-8') >= 3) {
            $results = \Content\MediaContentRibbon\MediaContentRibbonSearch::F($term, 0, 100, \Language\LanguageList::F()->get_current_language(), \Language\LanguageList::F()->get_default_language());
        }
        \smarty\SMW::F()->smarty->assign("result", $results);
        $this->render_view($this->get_requested_layout('front/layout'), $this->get_requested_template('chill_search'));
    }

}
