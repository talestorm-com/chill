<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace ModulesSupport\Products;

interface IModuleProducts extends \ModulesSupport\IModule {

    public function advt_list_products(\Out\IOut $out, \ADVTable\Data\IData $input = null);

    public function advt_get_table_columns(): array;

    public function editor_post_product(\Out\IOut $out, \DataMap\IDataMap $input = null);

    public function editor_get_product(\Out\IOut $out, \DataMap\IDataMap $input = null);

    public function list_products_of_catgeory(\Out\IOut $out, \DataMap\IDataMap $input = null);

    public function link_products(\Out\IOut $out, \DataMap\IDataMap $input = null);

    public function move_products(\Out\IOut $out, \DataMap\IDataMap $input = null);

    public function unlink_products(\Out\IOut $out, \DataMap\IDataMap $input = null);

    public function remove_products(\Out\IOut $out, \DataMap\IDataMap $input = null);

    public function apply_sort(\Out\IOut $out, array $sorts);
}
