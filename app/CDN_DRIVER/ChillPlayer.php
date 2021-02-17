<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace CDN_DRIVER;

/**
 * Description of ChillPlayer
 *
 * @author eve
 * @property-read string[] $video_id_list
 * @property-read string $reader_hash 
 */
class ChillPlayer {

    //put your code here

    protected function __construct(array $ids) {
        $this->video_id_list = [];
        foreach ($ids as $id) {
            $cid = \Filters\FilterManager::F()->apply_chain($id, ['Strip', 'Trim', 'NEString', 'DefaultNull']);
            $cid ? $this->video_id_list[] = $cid : 0;
        }
        sort($this->video_id_list);
        $this->reader_hash = sprintf("%s:%s", count($this->video_id_list), md5(implode(";", $this->video_id_list)));
    }

    /**
     * 
     * @param array $ids
     * @return \static
     */
    public static function F(array $ids) {
        return new static($ids);
    }

    public function get_player_id() {
        $id = $this->get_player_id_from_db();
        if (!$id) {
            $id = $this->get_player_id_from_cdn();
        }
        return $id;
    }

    protected function get_player_id_from_db() {
        $query = "SELECT player_id FROM chill__players WHERE id_hash=:P";
        $player_id = \Filters\FilterManager::F()->apply_chain(\DB\DB::F()->queryScalar($query, [":P" => $this->reader_hash]), ['Strip', 'Trim', 'NEString', 'DefaultNull']);
        return $player_id;
    }

    protected function get_player_id_from_cdn() {
        $request = CDNPlayerRequest::F();
        $request->run($this->video_id_list);
        if ($request->success) {
            \DB\SQLTools\SQLBuilder::F()->push("INSERT INTO chill__players (id_hash,player_id) VALUES(:P,:PP) ON DUPLICATE KEY UPDATE player_id=VALUES(player_id);")
                    ->push_params([":P" => $this->reader_hash, ":PP" => $request->player_id])->execute();
            return $request->player_id;
        }
        \Errors\common_error::RF("player request fails: %s",$request->response);
    }

}
