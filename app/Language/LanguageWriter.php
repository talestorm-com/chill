<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Language;

/**
 * Description of LanguageWriter
 *
 * @author eve
 * @property \DataMap\IDataMap $input
 * @property string $operation_id
 * @property bool $created
 */
class LanguageWriter {

    use \common_accessors\TCommonAccess;

    /** @var \DataMap\IDataMap */
    protected $input;
    protected $operation_id;
    protected $created = false;

    //<editor-fold defaultstate="collapsed" desc="getters">

    /** @return \DataMap\IDataMap */
    protected function __get__input() {
        return $this->input;
    }

    /** @return string */
    protected function __get__operation_id() {
        return $this->operation_id;
    }

    /** @return bool */
    protected function __get__created() {
        return $this->created;
    }

    //</editor-fold>

    /**
     * 
     * @return $this
     */
    public function run() {
        $data = \Filters\FilterManager::F()->apply_filter_datamap($this->input, $this->get_filters());
        \Filters\FilterManager::F()->raise_array_error($data);
        $existed_row = \DB\DB::F()->queryRow("SELECT * FROM language__language WHERE id=:P", [":P" => $data["id"]]);
        $b = \DB\SQLTools\SQLBuilder::F();
        if ($existed_row) {
            $b->push("UPDATE language__language SET name=:Pname,name_en=:Pname_en,enabled=:Penabled,sort=:Psort WHERE id=:Pid;");
            $this->created = false;
        } else {
            $this->created = true;
            $b->push("INSERT INTO language__language (id,name,name_en,enabled,sort) VALUES(:Pid,:Pname,:Pname_en,:Penabled,:Psort);");
        }
        $b->push_params([
            ":Pid" => $data["id"],
            ":Pname" => $data["name"],
            ":Pname_en" => $data["name_en"],
            ":Penabled" => $data["enabled"] ? 1 : 0,
            ":Psort" => $data["sort"]
        ]);
        $b->execute_transact();
        $this->operation_id = $data["id"];
        if ($this->created) {
            LanguageTablesManager::F()->mk_language_tables($data["id"], false);
        }
        return $this;
    }

    private function __construct(\DataMap\IDataMap $input) {
        $this->input = $input;
    }

    /**
     * 
     * @param \DataMap\IDataMap $input
     * @return \static
     */
    public static function F(\DataMap\IDataMap $input) {
        return new static($input);
    }

    protected function get_filters() {
        return[
            'name' => ['Strip', 'Trim', 'NEString'],
            'name_en' => ['Strip', 'Trim', 'NEString'],
            'id' => ['Strip', 'Trim', 'NEString', 'Lowercase'],
            'enabled' => 'Boolean', 'DefaultTrue',
            'sort' => ['Int', 'Default0'],
        ];
    }

}
