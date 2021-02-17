<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace PublicMedia;

/**
 * helper class for public tags
 *
 * @author eve
 * @property int $id
 * @property string $tag
 * @property bool $valid
 */
class PublicTag implements \common_accessors\IMarshall {

    use \common_accessors\TCommonAccess,
        \common_accessors\TDefaultMarshaller,
        \common_accessors\TCommonImport;

    //<editor-fold defaultstate="collapsed" desc="props">

    /** @var int */
    protected $id;

    /** @var string */
    protected $tag;

    //</editor-fold>
    //<editor-fold defaultstate="collapsed" desc="getters">

    /** @return int */
    protected function __get__id() {
        return $this->id;
    }

    /** @return string */
    protected function __get__tag() {
        return $this->tag;
    }

    /** @return bool */
    protected function __get__valid() {
        return $this->is_valid();
    }

    //</editor-fold>

    public function is_valid() {
        return !!($this->id && $this->tag);
    }

    public function __construct(int $id = null) {
        if ($this->id) {
            $this->load_from_db($id);
        }
    }

    /**
     * 
     * @param int $id
     * @return $this
     */
    public function load_from_db(int $id) {
        $q = "SELECT id,tag FROM public__tag WHERE id=:P;";
        $row = \DB\DB::F()->queryRow($q, [":P" => $id]);
        if ($row) {
            $this->load_row($row);
        }
        return $this;
    }

    /**
     * 
     * @param array $data
     * @return $this
     */
    public function load_row(array $data) {
        $this->import_props($data);
        return $this;
    }

    protected function t_common_import_get_filters(): array {
        return [
            'id' => ['IntMore0', 'DefaultNull'],
            'tag' => ['Trim', 'NEString', 'DefaultNull'],
        ];
    }

    /**
     * 
     * @param int $id
     * @return \PublicMedia\PublicTag
     */
    public static function F(int $id = null): PublicTag {
        return new static($id);
    }

    /**
     * makes tags array from tagid array (do not create,ofcourse)
     * @param int[] $id
     * @return PublicTag[]
     */
    public static function decode_tags(array $id): array {
        $ids = \Filters\FilterManager::F()->apply_chain($id, ["ArrayOfInt", "NEArray", "DefaultEmptyArray"], \Filters\params\ArrayParamBuilder::B(["ArrayOfInt" => ['more' => 0]], true)->get_param_set_for_property());
        $result = [];
        if (count($ids)) {
            if (count($ids) === 1) {
                $result[] = new static($ids[0]);
            } else {
                $tn = "a" . md5(__METHOD__);
                $b = \DB\SQLTools\SQLBuilder::F();
                $b->push("DROP TEMPORARY TABLE IF EXISTS `{$tn}`;")
                        ->push("CREATE TEMPORARY TABLE `{$tn}` (id BIGINT(19) UNSIGNED NOT NULL,PRIMARY KEY(id))ENGINE=MEMORY;")
                        ->push("INSERT INTO `{$tn}`(id) VALUES(" . implode("),(", $ids) . ") ON DUPLICATE KEY UPDATE id=VALUES(id)")
                        ->execute();
                $result = static::create_tags_from_links($tn);
            }
        }
        return $result;
    }

    // поисковый идекс отдельно!

    /**
     *
     * 
     * @param string $table_name table to link
     * @param string $link_field field in link table to compare
     * @param string $compare_field  field in tags table to compare
     * @return PublicTag[]
     */
    public static function create_tags_from_links(string $table_name, string $link_field = 'id', string $compare_field = 'id'): Array {
        $query = "SELECT A.id,A.tag FROM public__tag A JOIN `{$table_name}` B  ON (A.`{$compare_field}`=B.`{$link_field}`) ORDER BY A.tag";
        $rows = \DB\DB::F()->queryAll($query);
        return static::create_tags_from_data_array($rows);
    }

    /**
     * 
     * @param array $data
     * @return PublicTag[]
     */
    public static function create_tags_from_data_array(array $data) {
        $result = [];
        foreach ($data as $row) {
            $tag = new static();
            $tag->load_row($row)->is_valid() ? $result[] = $tag : 0;
        }
        return $result;
    }

    /**
     * makes tag array from tags names (creates if need)
     * @param string[] $tags
     * @return PublicTag[]
     */
    public static function decode_tags_string(array $tags): array {
        $result = [];
        $ftags = \Filters\FilterManager::F()->apply_chain($tags, ["ArrayOfNEString", "NEArray", "DefaultEmptyArray"], \Filters\params\ArrayParamBuilder::B(["ArrayOfNEString" => ['more' => 1]], true)->get_param_set_for_property());
        $tn = "a" . md5(__METHOD__);
        if (count($ftags)) {
            $b = \DB\SQLTools\SQLBuilder::F();
            $p = [];
            $i = [];
            foreach ($ftags as $tag) {
                $x = count($i);
                $i[] = "(:P_{$b->c}_s_{$x})";
                $p[":P_{$b->c}_s_{$x}"] = $tag;
            }
            $b->push("DROP TEMPORARY TABLE IF EXISTS `{$tn}`;")
                    ->push("DROP TEMPORARY TABLE IF EXISTS `{$tn}_insert`;")
                    ->push("CREATE TEMPORARY TABLE `{$tn}` (tag VARCHAR(200) NOT NULL,PRIMARY KEY(tag))ENGINE=InnoDB;")
                    ->push("CREATE TEMPORARY TABLE `{$tn}_insert` (tag VARCHAR(200) NOT NULL,PRIMARY KEY(tag))ENGINE=InnoDB;");
            if (count($i)) {
                $b->push(sprintf("INSERT INTO `{$tn}` (tag) VALUES %s ON DUPLICATE KEY UPDATE tag=VALUES(tag);", implode(",", $i)));
                $b->push_params($p);
            }
            $b->execute();
            $bx = \DB\SQLTools\SQLBuilder::F();
            $bx->push("INSERT INTO `{$tn}_insert` (tag) SELECT A.tag FROM `{$tn}` A LEFT JOIN public__tag B ON(A.tag=B.tag) WHERE B.id IS NULL ON DUPLICATE KEY UPDATE `{$tn}_insert`.tag=VALUES(tag); ");
            $bx->push("INSERT INTO public__tag (tag) SELECT tag FROM `{$tn}_insert` ON DUPLICATE KEY UPDATE public__tag.tag=public__tag.tag;");
            $bx->execute_transact();
            $result = static::create_tags_from_links($tn, 'tag', 'tag');
        }
        return $result;
    }

    /**
     * 
     * @param int $gallery_id
     * @return PublicTag[]
     */
    public static function get_tags_of_gallery(int $gallery_id) {
        $query = "SELECT A.id,A.tag FROM public__tag A JOIN public__gallery__tag B ON (A.id=B.tag_id) WHERE B.gallery_id=:P";
        $rows = \DB\DB::F()->queryAll($query, [":P" => $gallery_id]);
        return static::create_tags_from_data_array($rows);
    }

    public static function get_tags_of_gallery_item(int $gallery_id, string $uid): array {
        $query = "SELECT A.id,A.tag FROM public__tag A JOIN public__gallery__item__tag B ON (A.id=B.tag_id) WHERE B.gallery_id=:P AND B.item_uid=:PP";
        $rows = \DB\DB::F()->queryAll($query, [":P" => $gallery_id, ":PP" => $uid]);
        return static::create_tags_from_data_array($rows);
    }
    public static function get_tags_of_gallery_item_v2(int $item_id): array {
        $query = "SELECT A.id,A.tag FROM public__tag A JOIN public__gallery__item__tag B ON (A.id=B.tag_id) WHERE  B.item_id=:PP";
        $rows = \DB\DB::F()->queryAll($query, [ ":PP" => $item_id]);
        return static::create_tags_from_data_array($rows);
    }

    /**
     * 
     * @param int $user_id
     * @return PublicTag[]
     */
    public static function get_user_favorite_tags(int $user_id) {
        $query = "SELECT A.id,A.tag FROM public__tag A JOIN public__user_fav_tags B ON(A.id=B.tag_id) WHERE B.user_id=:P ORDER BY B.weight DESC OFFSET 0 LIMIT 8;";
        $rows = \DB\DB::F()->queryAll($query, [":P" => $user_id]);
        return static::create_tags_from_data_array($rows);
    }

    /**
     * 
     * @param int $user_id
     * @param array $tags
     * @param int $rank  1 - view,5 - publication (if first?)
     */
    public static function update_user_tags_interest(int $user_id, array $tags, int $rank = 1) {
        $tags_objects = static::decode_tags_string($tags);
        if ($tags_objects && count($tags_objects)) {
            $inserts = [];
            $params = [];
            $c = 0;
            foreach ($tags_objects as $tag /* @var $tag PublicTag */) {
                $inserts[] = "(:Puser,:P{$c}tag,:Pweigth)";
                $params[":P{$c}tag"] = $tag->id;
            }
            if (count($inserts)) {
                $params[":Puser"] = $user_id;
                $params[":Pweight"] = $rank;
                $b = new \DB\SQLTools\SQLBuilder();
                $b->push(sprintf("INSERT INTO public__user_fav_tags (user_id,tag_id,weight) VALUES %s ON DUPLICATE KEY UPDATE weight=weight+VALUES(weight);", implode(",", $inserts)))
                        ->push_params($params)->execute();
            }
        }
    }

}
