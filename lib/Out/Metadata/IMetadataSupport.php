<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Out\Metadata;

interface IMetadataSupport {

    public function meta_get_title(): string;

    public function meta_get_keywords(): string;

    public function meta_get_description(): string;

    public function meta_get_og_support(): bool;

    public function meta_get_og_title(): string;

    public function meta_get_og_description(): string;

    public function meta_get_og_image_support(): bool;

    public function meta_get_og_image_context(): string;

    public function meta_get_og_image_owner(): string;

    public function meta_get_og_image_image(): string;
}
