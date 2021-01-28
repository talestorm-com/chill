<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Content\MediaContent\Preplay;

/**
 * Description of Remover
 *
 * @author eve
 */
class Remover {

    //put your code here
    private $id;

    protected function __construct(int $id) {
        $this->id = $id;
    }

    /**
     * 
     * @param int $id
     * @return \static
     */
    public static function F(int $id) {
        return new static($id);
    }

    public function run() {
        try {
            $content = Preplay::F($this->id);
            \DB\DB::F()->exec("DELETE FROM media__preplay__video WHERE id=:P", [":P" => $content->id]);
            if ($content->cdn_id) {
                \Content\MediaContent\Removers\CDNRemoveTask::mk_params()->add("files", [$content->cdn_id])->run();
            }
        } catch (\Throwable $e) {
            throw $e;
        }
    }

}
