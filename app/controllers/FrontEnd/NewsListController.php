<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace controllers\FrontEnd;

/**
 * Description of NewsListController
 *
 * @author eve
 */
class NewsListController extends AbstractFrontendController {

    public $perpage;

    protected function on_after_init() {
        $this->perpage = \DataMap\InputDataMap::F()->get_filtered('perpage', ['IntMore0', 'DefaultNull']);
        $this->perpage ? 0 : $this->perpage = 100;
        return parent::on_after_init();
    }

    public function actionIndex() {
        $page = $this->route_params->get_filtered('page', ['IntMore0', 'Default0']);
        $language = \Language\LanguageList::F()->get_current_language();
        $default_language = \Language\LanguageList::F()->get_default_language();
        $data = $this->get_data($page, $language, $default_language);
        foreach ($data as $key => $value) {
            \smarty\SMW::F()->smarty->assign($key, $value);
        }
        $this->render_view($this->get_requested_layout('front/layout'), $this->get_requested_template("default"));
    }

    protected function get_data(int $page = 0, \Language\LanguageItem $language = null, \Language\LanguageItem $default_language = null) {
        $language ? 0 : $language = \Language\LanguageList::F()->get_current_language();
        $default_language ? 0 : $default_language = \Language\LanguageList::F()->get_default_language();
        $offset = $this->perpage * $page;
        $tquery = "
            SELECT SQL_CALC_FOUND_ROWS A.id,A.enabled,B.common_name,B.default_poster,
            COALESCE(S1.name,S2.name)name,T.tag_id tag_id, COALESCE(TS1.name,TS2.name) tag_name,
            DATE_FORMAT(B.post,'%%d.%%m.%%Y %%H:%%i') news_post_string,
            DATE_FORMAT(B.post,'%%d.%%m.%%Y') news_post_date_string,
            DATE_FORMAT(B.post,'%%H:%%i') news_post_time_string,
            CASE WHEN RTT.qty = 0 OR RTT.qty IS NULL THEN 0 ELSE ROUND(COALESCE(RTT.average,0) / COALESCE(RTT.qty,1)) END  ratestars,
            null dmy
            FROM media__content__text B JOIN media__content A ON(A.id=B.id)
            LEFT JOIN media__content__tag__list T ON(T.media_id=A.id AND T.sort=0)
            LEFT JOIN media__content__text__strings__lang_%s S1 ON(S1.id=A.id)
            LEFT JOIN media__content__text__strings__lang_%s S2 ON(S2.id=A.id)
            LEFT JOIN media__content__tag__strings TS1 ON(TS1.id=T.tag_id AND TS1.language_id='%s')
            LEFT JOIN media__content__tag__strings TS2 ON(TS2.id=T.tag_id AND TS2.language_id='%s')
            LEFT JOIN media__content__review__accumulator RTT ON(RTT.media_id=A.id)
            WHERE enabled=1            
            ORDER BY  B.post DESC, A.id DESC
            LIMIT %s OFFSET %s;            
            ";
        $query = sprintf($tquery, $language, $default_language, $language, $default_language, intval($this->perpage), intval($offset));
        $rows = \DB\DB::F()->queryAll($query);
        $total = \DB\DB::F()->queryScalari("SELECT FOUND_ROWS();");
        $paginator = \Helpers\Helpers::mk_paginator($total, $page, $this->perpage);
        return['items' => $rows, 'paginator' => $paginator, 'total' => $total, 'perpage' => $this->perpage, 'page' => $page];
    }

}
