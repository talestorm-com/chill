<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace controllers\FrontEnd;

/**
 * контроллер API - сюда тянем вообще все API вызовы
 *
 * @author eve
 */
class PublicController extends AbstractFrontendController {

    const DEBUG_MODE = false;
    const MESSAGE_INVALID_REQUEST = "invalid request";

    //<editor-fold defaultstate="collapsed" desc="обвес">    
    protected function on_before_protected_api_action(string $protected_action, string $requested_action = null) {
        if (!\Auth\Auth::F()->is_authentificated()) {
            \Auth\AuthError::R("authorization required");
        }
    }

    protected function actionAPI() {
        $this->out->add('status', 'ok');
        try {
            $this->out->add('controller', get_called_class());
            $action = \DataMap\InputDataMap::F()->get_filtered('action', ['Strip', 'Trim', 'NEString', 'DefaultNull']);
            $call_action = $action ? "API_{$action}" : "API_default";
            $call_protected_action = $action ? "PAPI_{$action}" : null;
            if ($call_protected_action && method_exists($this, $call_protected_action)) {
                $this->on_before_api_action($call_protected_action, $action);
                $this->on_before_protected_api_action($call_protected_action, $action);
                $this->$call_protected_action();
            } else if (method_exists($this, $call_action)) {
                $this->on_before_api_action($call_action, $action);
                $this->$call_action();
            } else {
                \Errors\common_error::RF("no API action `%s` found in `%s`", $call_action, get_called_class());
            }
        } catch (\Auth\AuthError $e) {
            $this->out->add('status', 'auth');
            $this->out->replace_section('error_info', [
                'message' => $e->getMessage(),
                'place' => (static::DEBUG_MODE) ? sprintf("%s at line %s", $e->getFile(), $e->getLine()) : null,
                'trace' => (static::DEBUG_MODE) ? $e->getTraceAsString() : null,
            ]);
        } catch (\Throwable $e) {
            $this->out->add('status', 'error');
            $this->out->replace_section('error_info', [
                'message' => $e->getMessage(),
                'place' => (static::DEBUG_MODE) ? sprintf("%s at line %s", $e->getFile(), $e->getLine()) : null,
                'trace' => (static::DEBUG_MODE) ? $e->getTraceAsString() : null,
            ]);
        }
        $this->out_json($this->out->marshall());
    }

    protected function on_before_api_action(string $action_method, string $action) {
        $r = parent::on_before_api_action($action_method, $action);
        if (\DataMap\InputDataMap::F()->get_filtered("require_user_info", ['Boolean', 'DefaultFalse'])) {
            $this->include_user_info();
        }
        if (\DataMap\InputDataMap::F()->get_filtered('require_metadata', ['Boolean', 'DefaultFalse'])) {
            $this->API_metadata();
        }
        return $r;
    }

    protected function include_user_info() {
        $this->out->add('user_info', \Auth\Auth::F()->is_authentificated() ? \Auth\Auth::F()->get_user_info() : null);
        $this->out->add('deposit', \Auth\Auth::F()->is_authentificated() ? \Filters\FilterManager::F()->apply_chain(\DB\DB::F()->queryScalar("SELECT money FROM user__wallet WHERE id=:P", [":P" => \Auth\Auth::F()->get_id()]), ['Float', 'Default0']) : null);
    }

    //</editor-fold>
    //<editor-fold defaultstate="collapsed" desc="сетка">    
    protected function API_grid() {
        $page = \DataMap\InputDataMap::F()->get_filtered('page', ['IntMore0', 'Default0']);
        $this->out->add("grid", \Content\MediaContentRibbon\V2\RibbonLent::F(\Language\LanguageList::F()->get_current_language(), $page));
    }

    //</editor-fold>    
    //<editor-fold defaultstate="collapsed" desc="справочники">
    protected function API_metadata() {
        $language = \Language\LanguageList::F()->get_current_language();
        $def_language = \Language\LanguageList::F()->get_default_language();
        $this->out->add('metadata', [
            'emo' => \DB\DB::F()->queryAll(sprintf("SELECT A.id,A.tag,COALESCE(S1.name,S2.name) name FROM media__emoji A LEFT JOIN media__emoji__strings S1 ON(S1.id=A.id AND S1.language_id='%s') LEFT JOIN media__emoji__strings S2 ON(S2.id=A.id AND S2.language_id='%s')ORDER BY A.sort,A.id DESC", $language, $def_language)),
            'genre' => \DB\DB::F()->queryAll(sprintf("SELECT A.id,COALESCE(S1.name,S2.name) name FROM media__content__genre A LEFT JOIN media__content__genre__strings S1 ON(S1.id=A.id AND S1.language_id='%s') LEFT JOIN media__content__genre__strings S2 ON(S2.id=A.id AND S2.language_id='%s') ORDER BY A.sort,A.id DESC", $language, $def_language)),
            'age' => \AgeRestriction\AgeRestriction::get_all(),
            'stick' => \Content\Stickers\StickerList::F(),
        ]);
    }

    protected function API_lang() {
        $this->out->add('tokens', \Language\LanguageTokenList::F());
    }

    //</editor-fold>
    //<editor-fold defaultstate="collapsed" desc="Данные по запросам">
    //<editor-fold defaultstate="collapsed" desc="по эмоции">
    protected function API_emo() {
        $per_page = 100;
        $emoji_id = \DataMap\InputDataMap::F()->get_filtered('id', ['IntMore0', 'DefaultNull']);
        $emoji_id ? 0 : \Errors\common_error::R(static::MESSAGE_INVALID_REQUEST);
        $page = \DataMap\InputDataMap::F()->get_filtered('page', ['IntMore0', 'DefaultNull']);
        $this->out->add("list", \Content\MediaContentRibbon\MediaContentEmojedList::F($emoji_id, $page * $per_page, $per_page, \Language\LanguageList::F()->get_current_language(), \Language\LanguageList::F()->get_default_language()));
    }

    //</editor-fold>
    //<editor-fold defaultstate="collapsed" desc="по жанру">    
    protected function API_genre() {
        $per_page = 100;
        $genre = \DataMap\InputDataMap::F()->get_filtered('id', ['IntMore0', 'DefaultNull']);
        $genre ? 0 : \Errors\common_error::R(static::MESSAGE_INVALID_REQUEST);
        $page = \DataMap\InputDataMap::F()->get_filtered('page', ['IntMore0', 'DefaultNull']);
        $this->out->add("list", \Content\MediaContentRibbon\MediaContentGenredList::F($genre, $page * $per_page, $per_page, \Language\LanguageList::F()->get_current_language(), \Language\LanguageList::F()->get_default_language()));
    }

    //</editor-fold>
    //<editor-fold defaultstate="collapsed" desc="поиск">    
    protected function API_search() {
        $per_page = 100;
        $query = \DataMap\InputDataMap::F()->get_filtered('q', ['Strip', 'Trim', 'NEString', 'DefaultNull']);
        !$query || mb_strlen($query, 'UTF-8') < 3 ? \Errors\common_error::R(static::MESSAGE_INVALID_REQUEST) : 0;
        $page = \DataMap\InputDataMap::F()->get_filtered('page', ['IntMore0', 'DefaultNull']);
        $this->out->add('list', \Content\MediaContentRibbon\MediaContentRibbonSearch::F($query, $per_page * $page, $per_page, \Language\LanguageList::F()->get_current_language(), \Language\LanguageList::F()->get_default_language()));
    }

    //</editor-fold>
    //</editor-fold>
    //<editor-fold defaultstate="collapsed" desc="страницы">
    protected function API_page() {
        $alias = \DataMap\InputDataMap::F()->get_filtered('alias', ['Strip', 'Trim', 'NEString', 'DefaultNull']);
        $alias ? 0 : \Errors\common_error::R(static::MESSAGE_INVALID_REQUEST);
        try {
            $page = \Content\Infopage\Infopage::F();
            $page->load($alias);
            $page->id && $page->published ? 0 : \Errors\common_error::R("m");
            $this->out->add('page', $page);
        } catch (\Throwable $e) {
            \Errors\common_error::HR("not found", 404);
        }
    }

    //</editor-fold>
    //<editor-fold defaultstate="collapsed" desc="Кабинет (копия)">
    protected function PAPI_profile() {
        $this->include_user_info();
        $this->out->add('referal_link', \Referal\ReferalLink::mk_referal_link(\Auth\Auth::F()->get_id()));
    }

    protected function PAPI_promo() {
        $value = \DataMap\InputDataMap::F()->get_filtered('value', ['Strip', 'Trim', 'NEString', 'DefaultNull']);
        $value ? 0 : \Errors\common_error::R('not found');
        $promo = \Promo\Promo::F(null, $value);
        $used = \DB\DB::F()->queryRow("select * from chill__promo__user WHERE promo_id=:P AND user_id=:PP", [":P" => $promo->id, ":PP" => $this->auth->get_id()]);
        $used ? \Errors\common_error::R("alredy used") : 0;
        \DB\SQLTools\SQLBuilder::F()->push("
            INSERT INTO  user__wallet (id,money) VALUES(:Puser,0) ON DUPLICATE KEY UPDATE money=money+VALUES(money);
            UPDATE user__wallet A SET money=money+:Pval WHERE id=:Puser;
            INSERT INTO chill__promo__user (promo_id,user_id,activated) VALUES(:Ppromo,:Puser,NOW());
            ")->push_params([
            ":Puser" => $this->auth->get_id(),
            ":Pval" => $promo->value,
            ":Ppromo" => $promo->id,
        ])->execute_transact();
        $this->include_user_info();
    }

    protected function PAPI_profile_update() {
        Helpers\CabinetUserWriter::run();
        $this->PAPI_profile();
    }

    protected function PAPI_wallet() {
        $this->out->add('link', Helpers\PayportCreateLink::run());
        $this->out->add("note", "Напоминаю - крайне важно чтобы после редиректа на домен chillvision.ru со страницы ПС пользователь попал туда АВТОРИЗОВАННЫМ!!!\nТоесть необходимо либо установить авторизационную куку и включить куки для вебвиева, либо перехватить переход и выставить авторизационный заголовок ДО перехода!");
    }

    //</editor-fold>
    //<editor-fold defaultstate="collapsed" desc="сериал && collection">
    protected function API_soap() {
        $soap_id = \DataMap\InputDataMap::F()->get("id", ['IntMore0', 'DefaultNull']);
        $soap_id ? 0 : \Errors\common_error::R(static::MESSAGE_INVALID_REQUEST);
        $soap = \Content\MediaContentFront\MediaContentFrontSOAP\MediaContentObject::FACTORY($soap_id);
        $soap && $soap->id ? 0 : \Errors\common_error::HR("not found", 404);
        $this->out->add('soap', $soap);
    }

    protected function API_collection() {
        $id = \DataMap\InputDataMap::F()->get("id", ['IntMore0', 'DefaultNull']);
        $id ? 0 : \Errors\common_error::R(static::MESSAGE_INVALID_REQUEST);
        $collection = \Content\MediaContent\Readers\ctCOLLECTION\MediaContentObject::F($id);
        $collection && $collection->id ? 0 : \Errors\common_error::HR("not found", 404);
        $collection->remove_disabled();
        $this->out->add('collection', $collection);
    }

    //</editor-fold>
    //<editor-fold defaultstate="collapsed" desc="payed ops">    
    public function API_check_user_access() {
        $content_id = \DataMap\InputDataMap::F()->get_filtered("content_id", ['IntMore0', 'DefaultNull']);
        $content_id ? 0 : \Errors\common_error::HR("no content_id", 500);
        $content_info = Helpers\SoapAccessReader::run($content_id);
        $content_info ? 0 : \Errors\common_error::HR("not found", 404);
        $this->out->add('content_info', $content_info); //|| floatval($content_info['price']) <= 0
        $this->out->add('access', (intval($content_info['time_left']) > 0 ) && $content_info['links'] != null ? 1 : 0);
    }

    public function PAPI_request_access() {
        $content_id = \DataMap\InputDataMap::F()->get_filtered("content_id", ['IntMore0', 'DefaultNull']);
        $content_id ? 0 : \Errors\common_error::HR("no content_id", 500);
        $result = Helpers\SoapAccessRequestor::run($content_id, $this->auth->id);
        $this->out->add('files_to_deprivate', $result['links']);
        $this->out->add("transaction_id", $result['transaction_id']);
        $this->API_check_user_access();
    }

    public function API_get_media_reviews() {
        $qty = 100;
        $id = \DataMap\InputDataMap::F()->get_filtered('id', ['IntMore0', 'DefaultNull']);
        $page = \DataMap\InputDataMap::F()->get_filtered('page', ['IntMore0', 'Default0']);
        $result = [];
        if ($id) {
            $result = \Review\ContentReviewsList::F($id, $qty, $page * $qty);
        }
        $this->out->add('reviews', $result);
    }

    public function PAPI_post_media_review() {
        Helpers\MediaReviewCreator::run();
    }

    public function API_get_chill_comments() {
        $page = \DataMap\InputDataMap::F()->get_filtered('page', ['IntMore0', 'Default0']);
        $this->out->add('list', \Content\ChillComment\ChillCommentList::F($page, 100));
    }

    public function PAPI_post_chill_comment() {
        Helpers\ChillCommentWriter::run();
    }

    public function PAPI_vote_chill_comment() {
        Helpers\ChillVoteWriter::run();
    }

    protected function PAPI_test() {
        $this->out->add("test", "success");
    }

}
