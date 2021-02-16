<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace controllers\FrontEnd;

use Content\MediaContent\Readers\ctCOLLECTION\MediaContentObject;

/**
 * Description of ChillCatalogController
 *
 * @author eve
 */
class ChillCatalogController extends AbstractFrontendController {
    const RUS = [
        'А', 'Б', 'В', 'Г', 'Д', 'Е', 'Ё', 'Ж', 'З', 'И', 'Й', 'К', 'Л', 'М', 'Н', 'О', 'П', 'Р', 'С', 'Т', 'У', 'Ф', 'Х', 'Ц', 'Ч', 'Ш', 'Щ', 'Ъ', 'Ы', 'Ь', 'Э', 'Ю', 'Я', 'а', 'б', 'в', 'г', 'д', 'е', 'ё', 'ж', 'з', 'и', 'й', 'к', 'л', 'м', 'н', 'о', 'п', 'р', 'с', 'т', 'у', 'ф', 'х', 'ц', 'ч', 'ш', 'щ', 'ъ', 'ы', 'ь', 'э', 'ю', 'я',
    ];
    const LAT = [
        'A', 'B', 'V', 'G', 'D', 'E', 'E', 'Gh', 'Z', 'I', 'Y', 'K', 'L', 'M', 'N', 'O', 'P', 'R', 'S', 'T', 'U', 'F', 'H', 'C', 'Ch', 'Sh', 'Sch', 'Y', 'Y', 'Y', 'E', 'Yu', 'Ya', 'a', 'b', 'v', 'g', 'd', 'e', 'e', 'gh', 'z', 'i', 'y', 'k', 'l', 'm', 'n', 'o', 'p', 'r', 's', 't', 'u', 'f', 'h', 'c', 'ch', 'sh', 'sch', 'y', 'y', 'y', 'e', 'yu', 'ya'
    ];
    const SIMBL = [
        ' ',',','.','\'','"','#','«','»',';',':','_','=','+','(','^','%','$','@',')','{','}','[',']','<','>'
    ];
    const SIMBL2 = [
        '!','?'
    ];
    protected function get_genre_list() {
        $language = \Language\LanguageList::F()->get_current_language();
        $def_language = \Language\LanguageList::F()->get_default_language();
        $query = "
            SELECT A.id,COALESCE(S1.name,S2.name) name
            FROM media__content__genre A
            LEFT JOIN media__content__genre__strings S1 ON(S1.id=A.id AND S1.language_id='%s')
            LEFT JOIN media__content__genre__strings S2 ON(S2.id=A.id AND S2.language_id='%s')
            ORDER BY A.sort,A.id DESC            
            ";
        return \DB\DB::F()->queryAll(sprintf($query, $language, $def_language));
    }

    protected function get_emoji_list() {
        $language = \Language\LanguageList::F()->get_current_language();
        $def_language = \Language\LanguageList::F()->get_default_language();
        $query = "
            SELECT A.id,A.tag,COALESCE(S1.name,S2.name) name
            FROM media__emoji A
            LEFT JOIN media__emoji__strings S1 ON(S1.id=A.id AND S1.language_id='%s')
            LEFT JOIN media__emoji__strings S2 ON(S2.id=A.id AND S2.language_id='%s')
            ORDER BY A.sort,A.id DESC            
            ";
        return \DB\DB::F()->queryAll(sprintf($query, $language, $def_language));
    }

    protected function get_country_list() {
        $language = \Language\LanguageList::F()->get_current_language();
        $def_language = \Language\LanguageList::F()->get_default_language();
        $query = "
            SELECT A.id,COALESCE(S1.name,S2.name) name
            FROM media__content__origin_country A
            LEFT JOIN media__content__origin__country__strings S1 ON(S1.id=A.id AND S1.language_id='%s')
            LEFT JOIN media__content__origin__country__strings S2 ON(S2.id=A.id AND S2.language_id='%s')
            ORDER BY A.id DESC            
            ";
        return \DB\DB::F()->queryAll(sprintf($query, $language, $def_language));
    }

    protected function get_items_by_genre(array $genre) {
        $r = ['genre_id' => $genre['id'], 'genre_name' => $genre['name'], 'items' => []];

        return $r;
    }

    public function actionIndex() {
        $emoji_list = $this->get_emoji_list();
        $country_list = $this->get_country_list();
        $genre_list = $this->get_genre_list();
        $items_by_genre = [];
        foreach ($genre_list as $row) {
            $items_by_genre[] = \Content\MediaContentRibbon\MediaContentGenredList::F(intval($row['id']), 0, 25, \Language\LanguageList::F()->get_current_language(), \Language\LanguageList::F()->get_default_language());
        }
        \smarty\SMW::F()->smarty->assign('emoji_list', $emoji_list);
        \smarty\SMW::F()->smarty->assign('country_list', $country_list);
        \smarty\SMW::F()->smarty->assign('genre_list', $genre_list);
        \smarty\SMW::F()->smarty->assign('rows', $items_by_genre);
        $this->render_view($this->get_requested_layout('front/layout'), $this->get_requested_template('default'));
    }

    public function actionCollection() {
        $collection_id = $this->route_params->get_filtered("collection_id", ['IntMore0', 'DefaultNull']);
        $collection_id ? 0 : \Router\NotFoundError::RF("not found");
        $collection = \Content\MediaContent\Readers\ctCOLLECTION\MediaContentObject::F($collection_id);
        $this->seoRedirect($collection);
        $collection->remove_disabled();
        \smarty\SMW::F()->smarty->assign('collection', $collection);

        //Устанавливаем мета-теги title, description, если они есть, иначе остается значение по умолчанию
        $meta_manager = \Router\Router::F()->route->get_controller_class()::F()->get_meta_manager();
        if ($collection->get__meta_title()) {
            $meta_manager->show_title_prefix = false;
            $meta_manager->set_title($collection->get__meta_title());
        }
        if ($collection->get__meta_description()) $meta_manager->set_description($collection->get__meta_description());

        $this->render_view($this->get_requested_layout('front/layout'), $this->get_requested_template('collection'));
    }

    public function last_contents(int $c = 3) {
        return \Content\MediaContentRibbon\MediaContentLastXList::F($c, \Language\LanguageList::F()->get_current_language(), \Language\LanguageList::F()->get_default_language());
    }

    public function seoRedirect(MediaContentObject $content) {
        $translitName = $this->route_params->get_filtered('translit_name', ['Strip', 'Trim', 'NEString', 'DefaultNull']);
        if ($translitName){
            return;
        }
        $translName = $this->createTranslitName($content->common_name);
        $urlRedirect = implode("", [\Router\Request::F()->https ? "https://" : "http://", \Router\Request::F()->host,
                                    mb_strtolower(\Router\Request::F()->request_path),'-'.$translName]);
        header("Location: $urlRedirect");
        die();
    }


    protected function createTranslitName($name)
    {
        $translName = mb_strtolower(str_replace(self::RUS, self::LAT, $name));
        $translName = (str_replace(self::SIMBL, '-', $translName));
        $translName = (str_replace(self::SIMBL2, '', $translName));
        $translName = trim($translName, '-');
        $translName = preg_replace('/(\-){2,}/', '$1', $translName);
        return $translName;
    }
}
