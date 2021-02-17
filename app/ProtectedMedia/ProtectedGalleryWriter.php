<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace ProtectedMedia;

/**
 * Description of ProtectedGalleryWriter
 * differential protectedGalleryWriter
 * @author eve
 * 
 */
class ProtectedGalleryWriter implements \common_accessors\IMarshall {

    use \common_accessors\TCommonAccess,
        \common_accessors\TCommonImport,
        \common_accessors\TDefaultMarshaller;

    private $user_id = null;

    public function __construct() {
        ;
    }

    /**
     * 
     * @param int $user_id
     * @return $this
     */
    public function set_user_id(int $user_id = null) {
        $this->user_id = $user_id;
        return $this;
    }

    protected function get_user_id(): int {
        $result = intval($this->user_id) ? intval($this->user_id) : ( \Auth\Auth::F()->is_authentificated() ? \Auth\Auth::F()->get_id() : null );
        $result && $result > 0 ? 0 : \Errors\common_error::RF("invalid user in %s", __METHOD__);
        return $result;
    }

    /**
     * 
     * @return \ProtectedMedia\ProtectedGalleryWriter
     */
    public static function F(): ProtectedGalleryWriter {
        return new static();
    }

    protected function get_writer_filters() {
        
    }

    protected function get_update_filters() {
        return [
            'uid' => ["Trim", "NEString"], //string            
            'title' => ["Trim"], //string
            'sort' => ["Int"], //int
            'info' => ["Trim"], //string            
        ];
    }

    protected function get_insert_filters() {
        return [
            'title' => ["Trim", "NEString", "DefaultEmptyString"], //string
            'sort' => ["Int", "Default0"], //int
            'info' => ["Trim", "NEString", "DefaultEmptyString"], //string     
        ];
    }

    public function run(\DataMap\IDataMap $data = null, \DB\SQLTools\SQLBuilder $b, string $return_uid_var) {
        $map = $data ? $data : \DataMap\InputDataMap::F(); /* @var $map \DataMap\IDataMap */
        $uid = $map->get_filtered('uid', ["Trim", "NEString", "DefaultNull"]);
        $uid ? $this->mk_update($map, $b, $return_uid_var) : $this->mk_insert($map, $b, $return_uid_var);
    }

    protected function mk_update(\DataMap\IDataMap $map, \DB\SQLTools\SQLBuilder $b, string $retvar) {
        $data = \Filters\FilterManager::F()->apply_filter_datamap($map, $this->get_update_filters());
        $cdata = [];
        foreach ($data as $key => $value) {
            if ($value instanceof \Filters\EmptyValue) {
                continue;
            }
            $cdata[$key] = $value;
        }
        \Filters\FilterManager::F()->raise_array_error($cdata);
        if (count($cdata) > 1) {
            $query = "
                SET {$retvar} = :P{$b->c}uid;
                UPDATE protected__gallery SET %s WHERE uid={$retvar} AND owner_id=:P{$b->c}owner_id;";
            $updates = [];
            $c = 0;
            foreach ($cdata as $key => $value) {
                if ($key !== 'uid') {
                    $param_name = sprintf(":P%d_%d_%s", $b->c, $c, $key);
                    $updates[] = sprintf("`%s`=%s", $key, $param_name);
                    $b->push_param($param_name, $value);
                    $c++;
                }
            }
            if (count($updates)) {
                $b->push(sprintf($query, implode(",", $updates)));
                $b->push_params([
                    ":P{$b->c}uid" => $cdata["uid"],
                    ":P{$b->c}owner_id" => \Auth\Auth::F()->get_id(),
                ]);
            }
        }
        $b->inc_counter();
    }

    protected function mk_insert(\DataMap\IDataMap $map, \DB\SQLTools\SQLBuilder $b, string $retvar) {
        $data = \Filters\FilterManager::F()->apply_filter_datamap($map, $this->get_insert_filters());
        \Filters\FilterManager::F()->raise_array_error($data);
        $tn = "@a" . md5(__METHOD__);
        $b->push("
            SET {$tn}=UUID();
            INSERT INTO protected__gallery (uid,owner_id,title,sort,info)    
            VALUES({$tn},:P{$b->c}owner_id,:P{$b->c}title,:P{$b->c}sort,:P{$b->c}info);");
        $b->push_params([
            ":P{$b->c}owner_id" => \Auth\Auth::F()->get_id(),
            ":P{$b->c}title" => $data["title"],
            ":P{$b->c}sort" => $data["sort"],
            ":P{$b->c}info" => $data["info"],
        ]);
        $b->push("SET {$retvar} = {$tn};");
        $b->inc_counter();
    }

}
