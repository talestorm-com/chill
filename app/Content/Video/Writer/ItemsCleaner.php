<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Content\Video\Writer;

/**
 * Description of ItemsCleaner
 *
 * @author eve
 */
class ItemsCleaner {

    public function run(VideoGroupWriter $w) {
        $ref = $w->runtime->get_filtered("item_writer_writed", ['ArrayOfNEString', 'NEArray', 'DefaultEmptyArray']);

        //выбираем существующие,но не переданные и поочередно их удаляем
        $b = \DB\SQLTools\SQLBuilder::F();
        $t = "a" . md5(__METHOD__);
        $tt = "";
        $tt = " TEMPORARY ";
        $b->push("DROP {$tt} TABLE IF EXISTS `{$t}`;
                CREATE {$tt} TABLE `{$t}`(uid VARCHAR(100) NOT NULL,PRIMARY KEY(uid))ENGINE=MyISAM;                    
            ");
        $qp = "INSERT INTO `{$t}` (uid) VALUES %s ON DUPLICATE KEY UPDATE uid=VALUES(uid);";
        $inserts = [];
        $params = [];
        $ic = 0;
        foreach ($ref as $row) {
            $inserts[] = "(:P{$b->c}_{$ic}uid)";
            $params[":P{$b->c}_{$ic}uid"] = $row;
            $ic++;
        }
        if (count($inserts)) {
            $b->push(sprintf($qp, implode(",", $inserts)));
            $b->push_params($params);
        }
        $b->execute();
        $rows = \DB\DB::F()->queryAll("SELECT A.uid,A.video FROM video__group__item A LEFT JOIN `{$t}` B ON(A.uid=B.uid) WHERE A.id=:P AND B.uid IS NULL;", [":P" => $w->result_id]);
        foreach ($rows as $row) {
            $x = \Helpers\Helpers::NEString($row["uid"], null);
            $video = \Helpers\Helpers::NEString($row['video'], null);
            if ($x) {
                \ImageFly\ImageFly::F()->remove_images(\Content\Video\VideoItem::MEDIA_CONTEXT, "{$w->result_id}_{$x}");
                if ($video) {
                    $video_path = \Config\Config::F()->PROTECTED_VIDEOTUTORIALS_BASE . $w->result_id . DIRECTORY_SEPARATOR . $video;
                    if (file_exists($video_path) && is_file($video_path) && is_writable($video_path)) {
                        @unlink($video_path);
                    }
                }
            }
        }
        //\Out\Out::F()->add("to_delete", \DB\DB::F()->queryAll("select A.* FROM filterpreset__item A LEFT JOIN `{$t}` B ON(A.uid=B.uid) WHERE A.id=:P AND B.uid IS NULL", [":P"=>$w->result_id]));
        \DB\SQLTools\SQLBuilder::F()->push("DELETE A.* FROM video__group__item A LEFT JOIN `{$t}` B ON(A.uid=B.uid) WHERE A.id=:P AND B.uid IS NULL;")
                ->push_param(":P", $w->result_id)->execute();
    }

    public function __construct() {
        ;
    }

    /**
     * 
     * @return \Content\Video\Writer\ItemsCleaner
     */
    public static function F(): ItemsCleaner {
        return new static();
    }

}
