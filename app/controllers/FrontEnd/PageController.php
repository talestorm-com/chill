<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace controllers\FrontEnd;

/**

 * @property string $error_message
 *
 */
class PageController extends AbstractFrontendController {
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
    protected $error_message = null;

    protected function __get__error_message() {
        return $this->error_message;
    }

    public function getErrorMessage() {
        return $this->error_message;
    }

    public function actionIndex() {
        $alias = $this->route_params->get_filtered('alias', ['Strip', 'Trim', 'NEString', 'DefaultNull']);
        $alias ? false : \Errors\common_error::R("invalid request");
        $this->seoRedirect($alias);
        $page = \Content\Infopage\Infopage::C($alias);
        \smarty\SMW::F()->smarty->assign('page', $page);
        $requested_layout = \Helpers\Helpers::NEString($page->properties->get_filtered("default_layout", ["Strip", "Trim", "NEString", "DefaultNull"]), "front/layout");
        $requested_template = \Helpers\Helpers::NEString($page->properties->get_filtered("default_template", ["Strip", "Trim", "NEString", "DefaultNull"]), "page");
        $this->render_view($this->get_requested_layout($requested_layout), $this->get_requested_template($requested_template));
        \Router\NotFoundError::R("not found");
    }

    public function action404() {
        if (!headers_sent()) {
            header("HTTP/1.0 404 Not Found", true);
        }
        $this->error_message = "Страница не найдена";
        $page = \Content\Infopage\Infopage::C('home');
        \smarty\SMW::F()->smarty->assign('page', $page);
        $this->render_view($this->get_requested_layout("front/layout"), $this->get_requested_template('page_404_e'));
        ///never exec
        \smarty\SMW::F()->smarty->assign('error', \Router\Router::F()->route->get_params()->get('error'));

        $this->render_view($this->get_requested_layout("front/layout"), 'page_404_e');
    }

    public function action500() {
        \smarty\SMW::F()->smarty->assign('error', \Router\Router::F()->route->get_params()->get('error'));
        if (!headers_sent()) {
            header("HTTP/1.0 500 internal server error", true);
        }
        $this->render_view($this->get_requested_layout("front/layout"), 'page_500_e');
    }
    public function action403000() {
        \smarty\SMW::F()->smarty->assign('error', \Router\Router::F()->route->get_params()->get('error'));
        if (!headers_sent()) {
            header("HTTP/1.0 403 access forbidden", true);
        }
        $this->render_view($this->get_requested_layout("front/layout"), 'page_403000_e');
    }

    public function seoRedirect($alias) {
        $translitName = $this->route_params->get_filtered('translit_name', ['Strip', 'Trim', 'NEString', 'DefaultNull']);
        if ($translitName){
            return;
        }
        if ('soap_page'=== $alias){
            $soapId = $this->route_params->get_filtered('soap_id', ['Strip', 'Trim', 'IntMore0', 'DefaultNull']);
            $DB = \DB\DB::F();
            $query = "SELECT * FROM media__content__season WHERE id = {$soapId} ;";
            $content= $DB->queryAll($query);
            $content= current($content);
            if(empty($content)){
                return;
            }
            $translName = $this->createTranslitName($content['common_name']);
            $urlRedirect = implode("", [\Router\Request::F()->https ? "https://" : "http://", \Router\Request::F()->host,
                                        mb_strtolower(\Router\Request::F()->request_path),'-'.$translName]);
            header("Location: $urlRedirect");
            die();
        }
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
