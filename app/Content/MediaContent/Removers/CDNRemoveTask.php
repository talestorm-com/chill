<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Content\MediaContent\Removers;

/**
 * Description of CDNRemoveTask
 *
 * @author eve
 */
class CDNRemoveTask extends \AsyncTask\AsyncTaskAbstract {

    protected function get_log_file_name(): string {
        return "async_cdn_delete_trailers";
    }

    protected function exec() {
        $files = $this->params->get_filtered("files", ["NEArray", "ArrayOfNEString", "NEArray", "DefaultNull"]);
        if ($files) {
            $files = array_unique($files);
            foreach ($files as $file) {
                try {
                    \CDN_DRIVER\CDNRemoveRequest::F()->run($file);
                } catch (\Throwable $x) {
                    $this->log(sprintf("%s at %s in %s", $x->getMessage(), $x->getLine(), $x->getFile()), "NONCRITICAL_ERROR");
                }
            }
        }
    }

}
