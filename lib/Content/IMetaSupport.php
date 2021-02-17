<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Content;

interface IMetaSupport {

    public function get_page_title(): string;

    public function get_page_meta_title(): string;

    public function get_page_keywords(): string;

    public function get_page_meta_description(): string;
}
