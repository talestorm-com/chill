<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Content\MediaContent\Writers\ctGIF;

/**
 * Description of ImageUploader
 *
 * @author eve
 */
class ImageUploader {

    /**
     * 
     * @return \static
     */
    public static function F() {
        return new static ();
    }

    public function run(Writer $writer) {
        $map = \DataMap\FileMap::F();
        $file = $map->get_by_field_name("gif_file");
        if (count($file)) {
            $file = $file[0];
            /** @var $file \DataMap\UploadedFile */
            $this->run_with_file($file, $writer);
        }
    } 

    protected function run_with_file(\DataMap\UploadedFile $file, Writer $writer) {
        $cdn_file_path = "/GIF/{$writer->result_id}/";        
        $cdn_request = \CDN_DRIVER\CDNUploadRequest::F();
        try {
            $cdn_request->run($cdn_file_path, $file->tmp_name, "gif.gif");
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
        $b->push("UPDATE media__content__gif SET cdn_id=:Pi,cdn_url=:Pu WHERE id=:Pr")
                ->push_params([
                    ":Pi" => $cdn_id,
                    ":Pu" => $cdn_url,
                    ":Pr" => $w->result_id
                ])->execute_transact();
        $w->environment->set("skip_cdn_check", true);
    }

}
