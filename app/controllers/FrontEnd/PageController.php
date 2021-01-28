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

}
