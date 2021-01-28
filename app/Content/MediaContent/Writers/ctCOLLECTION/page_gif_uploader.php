<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Content\MediaContent\Writers\ctCOLLECTION;

/**
 * Description of lent_gif_uploader
 *
 * @author eve
 */
class page_gif_uploader {

    /**
     * 
     * @return \static
     */
    public static function F() {
        return new static ();
    }

    public function run(Writer $writer) {
        $map = \DataMap\FileMap::F();
        $file = $map->get_by_field_name("gif_image2");
        if (count($file)) {
            $file = $file[0];
            /** @var $file \DataMap\UploadedFile */
            $this->run_with_file($file, $writer);
        }
    }

    protected function run_with_file(\DataMap\UploadedFile $file, Writer $writer) {
        $cdn_file_path = "/COLLECTION/{$writer->result_id}/lent/";
        $cdn_request = \CDN_DRIVER\CDNUploadRequest::F();
        try {
            $cdn_request->run($cdn_file_path, $file->tmp_name, "page_gif.gif");
            if ($cdn_request->success) {
                $cdn_id = $cdn_request->result["id"];
                $cdn_url = $cdn_request->result["cdn_url"];
                $this->on_cdn_data($cdn_id, $cdn_url, $writer);
            }
        } catch (\Throwable $e) {
            
        }
    }

    protected function on_cdn_data(string $cdn_id, string $cdn_url, Writer $w) {
        $b = \DB\SQLTools\SQLBuilder::F();
        $b->push("UPDATE  media__lent__mode_page SET gif_cdn_id2=:P{$b->c}cdn_id,gif_cdn_url2=:P{$b->c}cdn_url WHERE id=:P{$b->c}owner;")
                ->push_params([
                    ":P{$b->c}owner" => $w->result_id, ":P{$b->c}cdn_id" => $cdn_id, ":P{$b->c}cdn_url" => $cdn_url,
                ])->execute_transact();
        $w->environment->set("skip_cdn_check2", true);
    }

}
