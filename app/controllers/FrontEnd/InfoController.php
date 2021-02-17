<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace controllers\FrontEnd;

/**
 * Description of InfoController
 *
 * @author eve
 */
class InfoController extends AbstractFrontendController {

    protected function API_fos_c() {
        $data = \Filters\FilterManager::F()->apply_filter_datamap(\DataMap\PostDataMap::F(), [
            'name' => ["Strip", "Trim", "NEString"],
            "email" => ["EmailMatch"],
            "message" => ["Strip", "Trim", "NEString"],
            "ppa" => ["Boolean", "DefaultTrue"],
        ]);
        \Filters\FilterManager::F()->raise_array_error($data);
        $data["ppa"] ? 0 : \Errors\common_error::R("personal policy approving is required");
        \Content\RequestProfile\Async\QuestionTask::mk_params()->add("data", $data)->run();
    }

    protected function API_fos() {
        $data = \DataMap\InputDataMap::F()->get_filtered('data', ['Trim', 'NEString', 'JSONString', 'NEArray', 'DefaultNull']);
        $data ? 0 : \Errors\common_error::R("invalid request");
        $map = \DataMap\CommonDataMap::F()->rebind($data);
        $processor = \ChillFosProcessor\Processor::F($map);
        $processor->run();
    }

    public function API_content_type_list() {
        $this->out->add('data', \Content\MediaContent\MediaContentTypeList::F());
    }

    public function API_media_preset_collection() {
        $this->out->add('aspect_presets', \ImageFly\presets\ImageFlyAspectPresetCollection::F());
    }

    public function API_set_preferred_language() {
        $lang = \DataMap\InputDataMap::F()->get_filtered("language", ['Strip', 'Trim', 'NEString', 'DefaultNull']);
        if ($lang) {
            $lo = \Language\LanguageList::F()->get_language($lang);
            if ($lo) {
                \DataMap\CookieDataMap::F()->set("content_language", $lo->id);
            }
        }
    }

    public function API_actor_role_list() {
        $this->out->add('actor_role_list', \MediaActorRole\MediaActorRole::F());
    }

    public function API_check_user_access() {
        $content_id = \DataMap\InputDataMap::F()->get_filtered("content_id", ['IntMore0', 'DefaultNull']);
        $content_id ? 0 : \Errors\common_error::HR("no content_id", 500);
        $content_info = $this->get_access_data($content_id);
        $this->out->add('content_info', $content_info); //|| floatval($content_info['price']) <= 0
        $this->out->add('access', (intval($content_info['time_left']) > 0 ) && $content_info['links'] != null ? 1 : 0);
    }

    protected function get_access_data(int $content_id) {
        $content_info = Helpers\SoapAccessReader::run($content_id);
        $content_info ? 0 : \Errors\common_error::R("unknown content");
        return $content_info;
    }

    public function API_request_access() {
        $content_id = \DataMap\InputDataMap::F()->get_filtered("content_id", ['IntMore0', 'DefaultNull']);
        $content_id ? 0 : \Errors\common_error::HR("no content_id", 500);
        if (!$this->auth->is_authentificated()) {
            \Errors\common_error::R("auth_required");
        }
        $result = Helpers\SoapAccessRequestor::run($content_id, $this->auth->id);
//        $content_info = $this->get_access_data($content_id);
//        $user_monery = \Filters\FilterManager::F()->apply_chain(\DB\DB::F()->queryScalar("SELECT money FROM user__wallet WHERE id=:P ", [":P" => $this->auth->get_id()]), ['Float', 'Default0']);
//        if ($user_monery < floatval($content_info['price'])) {
//            \Errors\common_error::R("no_money");
//        }
//        $file_list = \Content\MediaContent\FileList\VideoFileList::F($content_id);
//        if (!count($file_list)) {
//            \Errors\common_error::R("no files");
//        }
//        $files_to_deprivate = [];
//        $deadline = time() + (60 * 60 * 24);
//        foreach ($file_list as $file) { /* @var $file  \Content\MediaContent\FileList\FileListItem */
//            $request = \CDN_DRIVER\CDNTmpRequest::F();
//            $request->run($file->cdn_id, $deadline);
//            $files_to_deprivate[] = [
//                'id' => $file->cdn_id,
//                'size' => $file->size,
//                'content_type' => $file->content_type,
//                'url' => $request->link_result,
//                'deadline' => $request->result_ttl,
//            ];
//        }
        $this->out->add('files_to_deprivate', $result['links']);
        $this->out->add("transaction_id", $result['transaction_id']);
        //$this->out->add('files_to_deprivate', $files_to_deprivate);
//        $builder = \DB\SQLTools\SQLBuilder::F();
//        $rv = "@a" . md5(__METHOD__);
//        $tid = $builder
//                        ->push("INSERT INTO user__history(user_id,ts,action,param1,param2,amount) VALUES(
//                        :P{$builder->c}user_id,
//                        NOW(),
//                        'payment_local',
//                        'content',
//                        :P{$builder->c}content_id,
//                        :P{$builder->c}amount);")
//                        ->push("SET {$rv} = LAST_INSERT_ID();")
//                        ->push_params([
//                            ":P{$builder->c}user_id" => $this->auth->id,
//                            ":P{$builder->c}content_id" => $content_id,
//                            ":P{$builder->c}amount" => floatval($content_info['price']),
//                        ])
//                        ->inc_counter()
//                        ->push("INSERT INTO media__content__user__access (media_id,user_id,deadline,links) VALUES(
//                    :P{$builder->c}id,
//                    :P{$builder->c}uid,
//                    :P{$builder->c}ttl,
//                    :P{$builder->c}links)                    
//                    ON DUPLICATE KEY UPDATE deadline=VALUES(deadline),links=VALUES(links);")
//                        ->push_params([
//                            ":P{$builder->c}id" => $content_id,
//                            ":P{$builder->c}uid" => $this->auth->get_id(),
//                            ":P{$builder->c}ttl" => $deadline, ":P{$builder->c}links" => json_encode($files_to_deprivate),
//                        ])
//                        ->inc_counter()
//                        ->push("UPDATE user__wallet SET money = money-:P{$builder->c}summ WHERE id=:P{$builder->c}user;")
//                        ->push_params([
//                            ":P{$builder->c}summ" => floatval($content_info['price']),
//                            ":P{$builder->c}user" => $this->auth->get_id()
//                        ])->execute_transact($rv);
//        $this->out->add("transaction_id", $tid);
        $this->API_check_user_access();
    }

    public function actionSuccessPayment() {
        //file_put_contents(__DIR__ . DIRECTORY_SEPARATOR . "dump.log", print_r(['G' => $_GET, 'P' => $_POST, 'R' => file_get_contents("php://input")], true));
        die('ok');
    }

    //<editor-fold defaultstate="collapsed" desc="MORE readers">    
    public function API_TAG_SEARCH_MORE_SOAP() {
        $offset = \Filters\FilterManager::F()->apply_chain("offset", ['IntMore0', 'Default0']);
        $perpage = \Filters\FilterManager::F()->apply_chain('perpage', ['IntMore0', 'DefaultNull']);
        $tag_id = \Filters\FilterManager::F()->apply_chain("tag_id", ['IntMore0', 'Default0']);
        $perpage ? 0 : $perpage = 100;
        $language = \Language\LanguageList::F()->get_current_language();
        $default_language = \Language\LanguageList::F()->get_default_language();
        $this->out->add("tagged_soap", \Content\MediaContentRibbon\MediaContentRibbonTagSOAP::F($tag_id, $offset, $perpage, $language, $default_language));
    }

    public function API_TAG_SEARCH_MORE_NEWS() {
        $offset = \Filters\FilterManager::F()->apply_chain("offset", ['IntMore0', 'Default0']);
        $perpage = \Filters\FilterManager::F()->apply_chain('perpage', ['IntMore0', 'DefaultNull']);
        $tag_id = \Filters\FilterManager::F()->apply_chain("tag_id", ['IntMore0', 'Default0']);
        $perpage ? 0 : $perpage = 100;
        $language = \Language\LanguageList::F()->get_current_language();
        $default_language = \Language\LanguageList::F()->get_default_language();
        $this->out->add("tagged_news", \Content\MediaContentRibbon\MediaContentRibbonTagTEXT::F($tag_id, $offset, $perpage, $language, $default_language));
    }

    public function API_TAG_SEARCH_MORE_GIFS() {
        $offset = \Filters\FilterManager::F()->apply_chain("offset", ['IntMore0', 'Default0']);
        $perpage = \Filters\FilterManager::F()->apply_chain('perpage', ['IntMore0', 'DefaultNull']);
        $tag_id = \Filters\FilterManager::F()->apply_chain("tag_id", ['IntMore0', 'Default0']);
        $perpage ? 0 : $perpage = 100;
        $language = \Language\LanguageList::F()->get_current_language();
        $default_language = \Language\LanguageList::F()->get_default_language();
        $this->out->add("tagged_gifs", \Content\MediaContentRibbon\MediaContentRibbonTagGIF::F($tag_id, $offset, $perpage, $language, $default_language));
    }

    public function API_GENRE_SEARCH_MORE_SOAP() {
        $offset = \Filters\FilterManager::F()->apply_chain("offset", ['IntMore0', 'Default0']);
        $perpage = \Filters\FilterManager::F()->apply_chain('perpage', ['IntMore0', 'DefaultNull']);
        $genre_id = \Filters\FilterManager::F()->apply_chain("genre_id", ['IntMore0', 'Default0']);
        $perpage ? 0 : $perpage = 100;
        $language = \Language\LanguageList::F()->get_current_language();
        $default_language = \Language\LanguageList::F()->get_default_language();
        $this->out->add("genred_soaps", \Content\MediaContentRibbon\MediaContentGenredListSOAP::F($genre_id, $offset, $perpage, $language, $default_language));
    }

    public function API_GENRE_SEARCH_MORE_VIDEO() {
        $offset = \Filters\FilterManager::F()->apply_chain("offset", ['IntMore0', 'Default0']);
        $perpage = \Filters\FilterManager::F()->apply_chain('perpage', ['IntMore0', 'DefaultNull']);
        $genre_id = \Filters\FilterManager::F()->apply_chain("genre_id", ['IntMore0', 'Default0']);
        $perpage ? 0 : $perpage = 100;
        $language = \Language\LanguageList::F()->get_current_language();
        $default_language = \Language\LanguageList::F()->get_default_language();
        $this->out->add("genred_videos", \Content\MediaContentRibbon\MediaContentGenredListVIDEO::F($genre_id, $offset, $perpage, $language, $default_language));
    }

    public function API_EMO_SEARCH_MORE_SOAP() {
        $offset = \Filters\FilterManager::F()->apply_chain("offset", ['IntMore0', 'Default0']);
        $perpage = \Filters\FilterManager::F()->apply_chain('perpage', ['IntMore0', 'DefaultNull']);
        $emo_id = \Filters\FilterManager::F()->apply_chain("emoji_id", ['IntMore0', 'Default0']);
        $perpage ? 0 : $perpage = 100;
        $language = \Language\LanguageList::F()->get_current_language();
        $default_language = \Language\LanguageList::F()->get_default_language();
        $this->out->add("emojed_soaps", \Content\MediaContentRibbon\MediaContentEmojedListSOAP::F($emo_id, $offset, $perpage, $language, $default_language));
    }

    public function API_EMO_SEARCH_MORE_VIDEO() {
        $offset = \Filters\FilterManager::F()->apply_chain("offset", ['IntMore0', 'Default0']);
        $perpage = \Filters\FilterManager::F()->apply_chain('perpage', ['IntMore0', 'DefaultNull']);
        $emo_id = \Filters\FilterManager::F()->apply_chain("emoji_id", ['IntMore0', 'Default0']);
        $perpage ? 0 : $perpage = 100;
        $language = \Language\LanguageList::F()->get_current_language();
        $default_language = \Language\LanguageList::F()->get_default_language();
        $this->out->add("emojed_videos", \Content\MediaContentRibbon\MediaContentEmojedListVIDEO::F($emo_id, $offset, $perpage, $language, $default_language));
    }

    public function API_ORIGIN_SEARCH_MORE_SOAP() {
        $offset = \Filters\FilterManager::F()->apply_chain("offset", ['IntMore0', 'Default0']);
        $perpage = \Filters\FilterManager::F()->apply_chain('perpage', ['IntMore0', 'DefaultNull']);
        $orig_id = \Filters\FilterManager::F()->apply_chain("origin_id", ['IntMore0', 'Default0']);
        $perpage ? 0 : $perpage = 100;
        $language = \Language\LanguageList::F()->get_current_language();
        $default_language = \Language\LanguageList::F()->get_default_language();
        $this->out->add("origined_soaps", \Content\MediaContentRibbon\MediaContentOriginedListSOAP::F($orig_id, $offset, $perpage, $language, $default_language));
    }

    public function API_ORIGIN_SEARCH_MORE_VIDEO() {
        $offset = \Filters\FilterManager::F()->apply_chain("offset", ['IntMore0', 'Default0']);
        $perpage = \Filters\FilterManager::F()->apply_chain('perpage', ['IntMore0', 'DefaultNull']);
        $orig_id = \Filters\FilterManager::F()->apply_chain("origin_id", ['IntMore0', 'Default0']);
        $perpage ? 0 : $perpage = 100;
        $language = \Language\LanguageList::F()->get_current_language();
        $default_language = \Language\LanguageList::F()->get_default_language();
        $this->out->add("origined_videos", \Content\MediaContentRibbon\MediaContentOriginedListVIDEO::F($orig_id, $offset, $perpage, $language, $default_language));
    }

    public function API_QUERY_SEARCH_MORE_SOAP() {
        $offset = \Filters\FilterManager::F()->apply_chain("offset", ['IntMore0', 'Default0']);
        $perpage = \Filters\FilterManager::F()->apply_chain('perpage', ['IntMore0', 'DefaultNull']);
        $query_string = \Filters\FilterManager::F()->apply_chain("query_string", ['Strip', 'Trim', 'NEString', 'SQLSafeString', 'DefaultEmptyString']);
        $perpage ? 0 : $perpage = 100;
        $language = \Language\LanguageList::F()->get_current_language();
        $default_language = \Language\LanguageList::F()->get_default_language();
        if ($query_string && mb_strlen($query_string, 'UTF-8') > 3) {
            $this->out->add("found_soap", \Content\MediaContentRibbon\MediaContentRibbonSearchSOAP::F($query_string, $offset, $perpage, $language, $default_language));
        } else {
            $this->out->add("found_soap", []);
        }
    }

    public function API_QUERY_SEARCH_MORE_NEWS() {
        $offset = \Filters\FilterManager::F()->apply_chain("offset", ['IntMore0', 'Default0']);
        $perpage = \Filters\FilterManager::F()->apply_chain('perpage', ['IntMore0', 'DefaultNull']);
        $query_string = \Filters\FilterManager::F()->apply_chain("query_string", ['Strip', 'Trim', 'NEString', 'SQLSafeString', 'DefaultEmptyString']);
        $perpage ? 0 : $perpage = 100;
        $language = \Language\LanguageList::F()->get_current_language();
        $default_language = \Language\LanguageList::F()->get_default_language();
        if ($query_string && mb_strlen($query_string, 'UTF-8') > 3) {
            $this->out->add("found_news", \Content\MediaContentRibbon\MediaContentRibbonSearchTEXT::F($query_string, $offset, $perpage, $language, $default_language));
        } else {
            $this->out->add("found_news", []);
        }
    }

    public function API_QUERY_SEARCH_MORE_GIFS() {
        $offset = \Filters\FilterManager::F()->apply_chain("offset", ['IntMore0', 'Default0']);
        $perpage = \Filters\FilterManager::F()->apply_chain('perpage', ['IntMore0', 'DefaultNull']);
        $query_string = \Filters\FilterManager::F()->apply_chain("query_string", ['Strip', 'Trim', 'NEString', 'SQLSafeString', 'DefaultEmptyString']);
        $perpage ? 0 : $perpage = 100;
        $language = \Language\LanguageList::F()->get_current_language();
        $default_language = \Language\LanguageList::F()->get_default_language();
        if ($query_string && mb_strlen($query_string, 'UTF-8') > 3) {
            $this->out->add("found_gifs", \Content\MediaContentRibbon\MediaContentRibbonSearchGIF::F($query_string, $offset, $perpage, $language, $default_language));
        } else {
            $this->out->add("found_gifs", []);
        }
    }

    //</editor-fold>

    protected function API_post_review() {
        $this->auth->is_authentificated() ? 0 : \Errors\common_error::R("auth_rqrd");
        Helpers\MediaReviewCreator::run();
    }

    protected function API_get_trailer_data() {
        $id = \DataMap\InputDataMap::F()->get_filtered('id', ['IntMore0', 'DefaultNull']);
        $id ? 0 : \Errors\common_error::R("not found");
        $this->out->add('trailer_data', \Content\MediaContentFront\TrailerList\TrailerListItem::from_db($id));
    }

    protected function API_player_id() {
        $vil = \DataMap\InputDataMap::F()->get_filtered('vil', ['NEArray', 'ArrayOfStrippedNEString', 'NEArray', 'DefaultNull']);
        $vil ? 0 : \Errors\common_error::R("invalid request");
        $query = \CDN_DRIVER\ChillPlayer::F($vil);
        $this->out->add('player_id', $query->get_player_id());
    }
    
    protected function API_players_ids(){
        $vilg = \DataMap\InputDataMap::F()->get_filtered('vilg', [ 'NEArray', 'DefaultNull']);
        $vilg ? 0 : \Errors\common_error::R("invalid request");
        $players=[];
        foreach ($vilg as $key=>$value){
            $query = \CDN_DRIVER\ChillPlayer::F($value);
            $players[$key]=$query->get_player_id();
        }
        $this->out->add('players_ids', $players);
    }
    
    protected function API_filelist(){
        die('disabled');
        \CDN_DRIVER\FileListTask::run();
    }

}
