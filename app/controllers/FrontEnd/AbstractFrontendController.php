<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace controllers\FrontEnd;

/**
 * Description of AbstractFrontendController
 *
 * @author studio2
 * @property \DataMap\IDataMap $route_params
 * @property \Basket\Basket $basket
 * @property \VisitCounter\VisitCounter $visit_counter
 */
abstract class AbstractFrontendController extends \controllers\abstract_controller {

    /** @var \DataMap\IDataMap */
    protected $route_params;

    /** @var \Basket\Basket */
    protected $basket;

    /** @var \VisitCounter\VisitCounter */
    protected $visit_counter;
    protected $referal_link;

    protected function __get__route_params() {
        return $this->route_params;
    }

    protected function __get__basket() {
        return $this->basket;
    }

    public function not_found() {
        \Router\NotFoundError::R("not found");
    }

    protected function on_after_init() {
        $result = parent::on_after_init();
        $this->route_params = \Router\Router::F()->route->get_params();
        $this->basket = \Basket\Basket::F();
        $this->visit_counter = \VisitCounter\VisitCounter::F();
        $this->referal_link = \Referal\ReferalLink::F();
        if (!\GEOList\GEOList::F()->has_access_client() && $this->route_params->get('alias') === 'soap_page') {
            \GEOList\GEOList::F()->disable();
            \Router\RenderableCodeError::HR("access denied", 403000);
        }
        try {
            if ($this->auth->is_authentificated()) {
                setcookie('gauid', $this->auth->get_id(), 0, "/", null, true, false);
            } else {
                setcookie('gauid', '', 0, "/", null, true, false);
            }
        } catch (\Throwable $ee) {
            
        }
        return $result;
    }

    public function get_current_language() {
        return \Language\LanguageList::F()->get_current_language()->id;
    }

    /** @return \Out\Metadata\MetadataManager */
    public function get_meta_manager() {
        return $this->out->meta;
    }

    public function get_meta_title() {
        return $this->get_meta_manager()->sv_title;
    }

    public function get_meta_description() {
        return $this->get_meta_manager()->sv_description;
    }

    public function is_og_support() {
        return $this->get_meta_manager()->og_support;
    }

    public function get_og_title() {
        return $this->get_meta_manager()->sv_og_title;
    }

    public function get_og_description() {
        return $this->get_meta_manager()->sv_og_description;
    }

    public function get_og_url() {
        return htmlentities(strip_tags($this->current_url()));
    }

    public function is_og_image_support() {
        return $this->get_meta_manager()->og_image_support;
    }

    public function get_og_image_url() {
        return $this->get_meta_manager()->sv_og_image;
    }

    public function mk_csrf(string $section = 'default', bool $reusable = false, int $ttl = 86400): string {
        return \Helpers\Helpers::csrf_mk($section, $reusable, $ttl);
    }

    public function check_csrf(string $value, string $section = 'default', bool $remove_key = true): bool {
        return \Helpers\Helpers::csrf_check($section, $value, $remove_key);
    }

    public function check_csrf_throw(string $value, string $section = 'default', bool $remove_key = true) {
        return \Helpers\Helpers::csrf_check_throw($section, $value, $remove_key);
    }

}
