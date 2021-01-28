<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace DataImport\NomenclatureImport;

class NomenclatureImport {

    protected $var_counter = 0;
    protected $default_category_parent = 0;
    protected $default_visible_state = null;
    protected $default_visible_state_product = null;
    protected $temp = ""; //TEMPORARY
    protected $local_mode = false;

    /** @var \DataMap\IDataMap */
    protected $local_input;

    /** @var \Mutex\IMutex */
    protected $mutex = null;

    //<editor-fold defaultstate="collapsed" desc="misc">
    public function set_local_mode() {
        $this->local_mode = true;
    }

    public function reset_local_mode() {
        $this->local_mode = false;
    }

    protected function generate_variable_name() {
        $this->var_counter++;
        return implode("", ["@a", md5(__METHOD__), "b{$this->var_counter}"]);
    }

    protected function get_default_category_parent() {

        if ($this->default_category_parent === 0) {
            if ($this->local_mode) {
                $this->default_category_parent = $this->local_input->get_filtered("default_parent", ['IntMore0', 'DefaultNull']);
            } else {
                $this->default_category_parent = \DataMap\GPDataMap::F()->get_filtered("default_parent", ['IntMore0', 'DefaultNull']);
            }
        }
        return $this->default_category_parent;
    }

    protected function get_default_visible_state() {
        if ($this->default_visible_state === null) {
            if ($this->local_mode) {
                $this->default_visible_state = (!$this->local_input->get_filtered('disable_new_categories', ['Boolean', 'DefaultFalse'])) ? 1 : 0;
            } else {
                $this->default_visible_state = (!\DataMap\GPDataMap::F()->get_filtered('disable_new_categories', ['Boolean', 'DefaultFalse'])) ? 1 : 0;
            }
        }
        return $this->default_visible_state;
    }

    protected function get_default_product_visible_state() {
        if ($this->default_visible_state_product === null) {
            if ($this->local_mode) {
                $this->default_visible_state_product = (!$this->local_input->get_filtered('disable_new_products', ['Boolean', 'DefaultFalse'])) ? 1 : 0;
            } else {
                $this->default_visible_state_product = (!\DataMap\GPDataMap::F()->get_filtered('disable_new_products', ['Boolean', 'DefaultFalse'])) ? 1 : 0;
            }
        }
        return $this->default_visible_state_product;
    }

    protected function createCategoryAlias(ImportedCategory $c): string {
        return \Helpers\Helpers::translit($c->name) . uniqid($c->uid);
    }

    protected function __construct(bool $skip_mutex = false) {
        if (!$skip_mutex) {
            $this->mutex = \Mutex\SimpleNamedMutex::F("import_module");
            if (!$this->mutex->get_if()) {
                \DataImport\Common\DataImportError::R("mutex is buisy");
            }
        }
    }

    public function release() {
        $this->mutex ? $this->mutex->release() : 0;
    }

    public function __destruct() {
        $this->mutex ? $this->mutex->release() : 0;
    }

    public static function F(bool $skip_mutex = false): NomenclatureImport {
        return new static($skip_mutex);
    }

    //</editor-fold>

    /**
     * процессор сегмента
     * @param string $file_name
     * @param type $offset
     * @param type $count
     * @param type $max_letter
     * @param \DB\SQLTools\SQLBuilder $b
     * @param string $var
     * @return int
     */
    //<editor-fold defaultstate="collapsed" desc="process_slice">
    protected function process_slice(string $file_name, $offset, $count, $max_letter, \DB\SQLTools\SQLBuilder $b, string $var) {

        $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
        $reader->setReadFilter(\DataImport\Common\SliceFilter::F($offset, $count));
        $book = $reader->load($file_name);
        unset($reader);
        $sheets = array_values($book->getAllSheets());
        count($sheets) ? false : \Errors\common_error::R("no sheets in file");
        $ws = $sheets[0];
        /* @var $ws \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet */
        //cellmap
        //0  Код
        //1  Артикул
        //2  Наименование
        //3  Полное наименование
        //4  Ед. изм.
        //5  Код родителя
        //6  Это группа
        //7  Цвет
        //8  Размер
        //9  Цена оптовая
        //10 Цена розничная
        //11 Состав
        //12 Описание	
        //13 Цена оптовая (старая)	
        //14 Цена розничная (старая)	
        //15 Скидка оптовая
        //16 Скидка розничная
        //17 Код аналога        
        $max = $ws->getHighestDataRow();
        $max_letter = $ws->getHighestDataColumn();
        $data_raw = $ws->rangeToArray("A{$offset}:{$max_letter}{$max}", NULL, TRUE, FALSE);
        $cd = \DataMap\CommonDataMapIndex::F();  /* @var $cd \DataMap\CommonDataMapIndex  */
        $map = \DataMap\CommonDataMap::F();
        $cd->set_keys(['uid', 'article', 'name', 'full_name', 'ediz', 'parent',
            'group', 'color', 'size', 'price_opt', 'price_ret', 'consists', 'info',
            'price_opt_old', 'price_ret_old', 'discount_opt', 'discount_ret', 'analog']);
        $lines = 0;
        $ic = 0;
        $b->inc_counter();
        $params = [];
        $inserts = [];

        foreach ($data_raw as $raw_row) {

            $lines++;
            $raw_row_filt = \Filters\FilterManager::F()->apply_filter_datamap($cd->rebind($raw_row), [
                'uid' => ['Strip', 'Trim', 'NEString', 'DefaultNull'],
                'article' => ['Strip', 'Trim', 'NEString', 'DefaultNull'],
                'name' => ['Strip', 'Trim', 'NEString', 'DefaultNull'],
                'full_name' => ['Strip', 'Trim', 'NEString', 'DefaultNull'],
                'ediz' => ['Strip', 'Trim', 'NEString', 'DefaultNull'],
                'parent' => ['Strip', 'Trim', 'NEString', 'DefaultNull'],
                'group' => ['Int', 'Boolean', 'DefaultFalse', 'SQLBool'],
                'color' => ['Strip', 'Trim', 'NEString', 'DefaultNull'],
                'size' => ['Strip', 'Trim', 'NEString', 'DefaultNull'],
                'price_opt' => ['Float', 'DefaultNull'],
                'price_ret' => ['Float', 'DefaultNull'],
                'consists' => ['Trim', 'NEString', 'DefaultEmptyString'],
                'info' => ['Trim', 'NEString', 'DefaultEmptyString'],
                'price_opt_old' => ['Float', 'DefaultNull'],
                'price_ret_old' => ['Float', 'DefaultNull'],
                'discount_opt' => ['Float', 'DefaultNull'],
                'discount_ret' => ['Float', 'DefaultNull'],
                'analog' => ['Strip', 'Trim', 'NEString', 'DefaultNull'],
            ]);

            $row = $map->rebind($raw_row_filt);
            if ($row->get('uid') && $row->get('name')) {
                $inserts[] = "(
                    :P{$b->c}I{$ic}uid,:P{$b->c}I{$ic}article,:P{$b->c}I{$ic}name,
                    :P{$b->c}I{$ic}full_name,:P{$b->c}I{$ic}parent,:P{$b->c}I{$ic}group,
                    :P{$b->c}I{$ic}color,:P{$b->c}I{$ic}size,
                    :P{$b->c}I{$ic}price_opt,
                    :P{$b->c}I{$ic}price_retail,
                    :P{$b->c}I{$ic}consists,
                    :P{$b->c}I{$ic}description,
                    :P{$b->c}I{$ic}price_opt_old,
                    :P{$b->c}I{$ic}price_retail_old,
                    :P{$b->c}I{$ic}discount_opt,
                    :P{$b->c}I{$ic}discount_retail,
                    :P{$b->c}I{$ic}analog,
                    :P{$b->c}I{$ic}article
                   )";
                $params[":P{$b->c}I{$ic}uid"] = $row->get("uid");
                $params[":P{$b->c}I{$ic}article"] = $row->get("article");
                $params[":P{$b->c}I{$ic}name"] = $row->get("name");
                $params[":P{$b->c}I{$ic}full_name"] = $row->get("full_name");
                $params[":P{$b->c}I{$ic}parent"] = $row->get("parent");
                $params[":P{$b->c}I{$ic}group"] = $row->get("group");
                $params[":P{$b->c}I{$ic}color"] = $row->get("color");
                $params[":P{$b->c}I{$ic}size"] = $row->get("size");
                $params[":P{$b->c}I{$ic}price_opt"] = $row->get("price_opt");
                $params[":P{$b->c}I{$ic}price_retail"] = $row->get("price_ret");
                $params[":P{$b->c}I{$ic}consists"] = $row->get("consists");
                $params[":P{$b->c}I{$ic}description"] = $row->get("info");
                $params[":P{$b->c}I{$ic}price_opt_old"] = $row->get("price_opt_old");
                $params[":P{$b->c}I{$ic}price_retail_old"] = $row->get("price_ret_old");
                $params[":P{$b->c}I{$ic}discount_opt"] = $row->get("discount_opt");
                $params[":P{$b->c}I{$ic}discount_retail"] = $row->get("discount_ret");
                $params[":P{$b->c}I{$ic}analog"] = $row->get("analog");
                $ic++;
            }
        }
        if (count($inserts)) {
            $b->push_params($params);
            $b->push(sprintf("INSERT INTO `{$var}` (
                guid ,article ,name ,full_name,parent_guid ,is_group ,color ,size 
                ,price_opt ,price_retail ,consits ,description ,price_opt_old 
                ,price_retail_old ,discount_opt ,discount_retail ,analog,source_article 
                ) VALUES %s ON DUPLICATE KEY UPDATE guid=VALUES(guid);", implode(",", $inserts)));
        }
        $book->disconnectWorksheets();
        unset($sheets);
        unset($ws);
        unset($book);
        return $lines;
    }

    //</editor-fold>

    protected function import_permanent_params(array $required_params) {
        if (!$this->local_mode) {
            foreach ($_GET as $key => $value) {
                if (!array_key_exists($key, $required_params)) {
                    $required_params[$key] = $value;
                }
            }
            foreach ($_POST as $key => $value) {
                if (!array_key_exists($key, $required_params)) {
                    $required_params[$key] = $value;
                }
            }
        } else {
            $required_params = array_merge($this->local_input->get_all_cloned(), $required_params);
        }
        return $required_params;
    }

    public function get_temp_dir_name() {
        $tmp_dir = \Config\Config::F()->LOCAL_TMP_PATH;
        $import_tmp_dir = "{$tmp_dir}data_import" . DIRECTORY_SEPARATOR;
        if (!(file_exists($import_tmp_dir) && is_dir($import_tmp_dir) )) {
            @mkdir($import_tmp_dir, 0777, true);
        }
        (file_exists($import_tmp_dir) && is_dir($import_tmp_dir) && is_readable($import_tmp_dir) && is_writable($import_tmp_dir) ) ? 0 : \DataImport\Common\DataImportError::R("no temp dir access");
        $list = scandir($import_tmp_dir);
        foreach ($list as $file_name) {
            if (mb_substr($import_tmp_dir, 0, 1, 'UTF-8') !== "." && is_file($import_tmp_dir . $file_name) && is_writable($import_tmp_dir . $file_name)) {
                if (filemtime($import_tmp_dir . $file_name) < (time() - 86400)) {
                    @unlink($import_tmp_dir . $file_name);
                }
            }
        }
        return $import_tmp_dir;
    }

    /**
     * аплоад
     * @param \DataMap\IDataMap $input
     * @param \DataMap\UploadedFile $file
     */
    //<editor-fold defaultstate="collapsed" desc="step 0">
    protected function run_step_0(\DataMap\IDataMap $input, \DataMap\UploadedFile $file = null) {
        $import_tmp_dir = $this->get_temp_dir_name();
        $file_name = md5(__METHOD__) . ".xlsx";
        if (file_exists("{$import_tmp_dir}{$file_name}")) {
            @unlink("{$import_tmp_dir}{$file_name}");
        }
        if (!$this->local_mode) {
            $file ? false : \DataImport\Common\DataImportError::R("no file uploaded");
            $file->move("{$import_tmp_dir}{$file_name}");
        } else {
            $this->local_input = $input;
            $local_path = $input->get("local_file");
            if (file_exists($local_path) && is_file($local_path) && is_readable($local_path)) {
                @rename($local_path, "{$import_tmp_dir}{$file_name}");
            }
        }
        \DataImport\Common\DataImportLog::F()->log("Загрузка файла завершена");
        $b = \DB\SQLTools\SQLBuilder::F();
        $TT = "@var" . md5(__METHOD__);
        $b->push("INSERT INTO data_import_log (updated,state,`log`) VALUES(:PN,0,:PT); SET {$TT}=LAST_INSERT_ID();");
        $b->push_params([":PN" => (new \DateTime())->format("Y-m-d"),
            ":PT" => "Импорт каталога. Загрузка файла импорта завершена",
        ]);
        $log_id = $b->execute_transact($TT);
        \DataImport\Common\ImportRedirectException::F($this->import_permanent_params([
                    'step' => 1,
                    'file_name' => base64_encode($file_name),
                    'log_id' => $log_id,
        ]));
    }

    protected function write_log(\DataMap\IDataMap $input, $log_text, $log_state = 1) {
        $pl = $input->get_filtered("log_id", ['IntMore0', 'DefaultNull']);
        $pl ? 0 : \DataImport\Common\DataImportError::R("no log identifier");
        $b = \DB\SQLTools\SQLBuilder::F();
        $b->push("UPDATE data_import_log SET updated=:PN,state=:PS,`log`=TRIM(CONCAT(COALESCE(`log`,''),'\n',:PT)) WHERE id=:PL;");
        $b->push_params([
            ":PL" => $pl,
            ":PN" => (new \DateTime())->format('Y-m-d'),
            ":PS" => $log_state,
            ":PT" => $log_text
        ]);
        $b->execute();
        \DataImport\notificator\ImportLogNotificator::RESET_CACHE();
    }

    //</editor-fold>
    //<editor-fold defaultstate="collapsed" desc="filename utils">
    protected function get_filename_from_input(\DataMap\IDataMap $input) {
        $file_name_b64 = $this->get_encoded_filename_from_input($input);
        $file_name = base64_decode($file_name_b64);
        $file_name ? 0 : \DataImport\Common\DataImportError::R("invalid tmp name");
        $tmp_dir = $this->get_temp_dir_name();
        $path = "{$tmp_dir}{$file_name}";
        file_exists($path) && is_file($path) && is_readable($path) ? false : \DataImport\Common\DataImportError::R("no tmp file found or access forbidden (`{$path}`)");
        return $path;
    }

    protected function get_encoded_filename_from_input(\DataMap\IDataMap $input) {
        $file_name_b64 = $input->get("file_name", ['Strip', 'Trim', 'NEString', 'DefaultNull']);
        $file_name_b64 ? 0 : \DataImport\Common\DataImportError::R("tmp name is required");
        return $file_name_b64;
    }

    //</editor-fold>

    /**
     * создание таблиц и подсчет строк
     * @param \DataMap\IDataMap $input
     * @param \DataMap\UploadedFile $file
     */
    //<editor-fold defaultstate="collapsed" desc="step 1">
    protected function run_step_1(\DataMap\IDataMap $input, \DataMap\UploadedFile $file = null) {
        $path = $this->get_filename_from_input($input);
        \DataImport\Common\DataImportLog::F()->log("создание временных таблиц");
        $b = \DB\SQLTools\SQLBuilder::F();
        //Код|Артикул|Наименование|Полное наименование|Ед. изм.|Код родителя|Это группа|Цвет|Размер|Цена оптовая|Цена розничная|Состав|Описание
        //|Цена оптовая(старая)|Цена розничная (старая)|Скидка оптовая|Скидка розничная|Код аналога
        $temp = $this->temp;
        $tn = "data_import_" . md5(__METHOD__);
        $b->push("DROP {$temp} TABLE IF EXISTS `{$tn}`;
            CREATE {$temp} table `{$tn}` (guid VARCHAR(100) NOT NULL,
                article VARCHAR(100) NULL DEFAULT NULL,
                name VARCHAR(255) NOT NULL,full_name VARCHAR(255) NULL DEFAULT NULL,parent_guid VARCHAR(100) NULL DEFAULT NULL,
                is_group INT(11) UNSIGNED NOT NULL DEFAULT 0,color VARCHAR(255) NULL DEFAULT NULL,
                size VARCHAR(255) NULL DEFAULT NULL,price_opt DOUBLE NULL DEFAULT NULL,
                price_retail DOUBLE NULL DEFAULT NULL,consits MEDIUMTEXT,description MEDIUMTEXT,
                price_opt_old DOUBLE NULL DEFAULT NULL,price_retail_old DOUBLE NULL DEFAULT NULL,
                discount_opt DOUBLE NULL DEFAULT NULL,discount_retail DOUBLE NULL DEFAULT NULL,
                analog VARCHAR(100) NULL DEFAULT NULL,
                source_article VARCHAR(100) NULL DEFAULT NULL,
                PRIMARY KEY(guid))ENGINE=MyISAM;");
        $b->execute();
        \DataImport\Common\DataImportLog::F()->log("Подсчет импротируемых строк");
        $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
        $wksheet_info = $reader->listWorksheetInfo($path);
        $max_letter = $wksheet_info[0]['lastColumnLetter'];
        $max_row_number = intval($wksheet_info[0]['totalRows']);
        $offset = 2;
        \DataImport\Common\DataImportLog::F()->log("Размер таблицы:{$max_row_number} строк. Максимальный индекс столбца:{$max_letter}. стартовый оффсет:{$offset}");
        $logtext = \DataImport\Common\DataImportLog::F()->get_text("\n");
        $this->write_log($input, $logtext);
        \DataImport\Common\ImportRedirectException::F($this->import_permanent_params([
                    'step' => 2, 'file_name' => $this->get_encoded_filename_from_input($input),
                    'table' => $tn, 'total_readed' => 0, 'index' => 0, 'offset' => $offset,
                    'row_count' => $max_row_number, 'column_count' => $max_letter
        ]));
    }

    //</editor-fold>

    /**
     * цикличный импорт
     * @param \DataMap\IDataMap $input
     * @param \DataMap\UploadedFile $file
     */
    //<editor-fold defaultstate="collapsed" desc="step 2">
    protected function run_step_2(\DataMap\IDataMap $input, \DataMap\UploadedFile $file = null) {
        $path = $this->get_filename_from_input($input);
        $table = $input->get_filtered('table', ['Strip', 'Trim', 'NEString', 'DefaultNull']);
        $table ? 0 : \DataImport\Common\DataImportError::R("no temporary table name provided");
        $max_row_number = $input->get_filtered('row_count', ['IntMore0', 'DefaultNull']);
        $max_row_number ? 0 : \DataImport\Common\DataImportError::R("row_count has invalid value");
        $max_letter = $input->get_filtered('column_count', ['Strip', 'Trim', 'NEString', 'DefaultNull']);
        $max_letter ? 0 : \DataImport\Common\DataImportError::R("colin_count has invalid value");
        $total = $input->get_filtered('total_readed', ['IntMore0', 'Default0']);
        $index = $input->get_filtered('index', ['IntMore0', 'Default0']);
        $offset = $input->get_filtered('offset', ['IntMore0', 'Default0']);
        $builder = \DB\SQLTools\SQLBuilder::F();
        \DataImport\Common\DataImportLog::F()->log("Импорт строк с " . ($index + $offset) . " по " . ($index + $offset + 5000));
        $readed = $this->process_slice($path, $index + $offset, 5000, $max_letter, $builder, $table);
        \DataImport\Common\DataImportLog::F()->log("Прочитано {$readed} строк");
        if (!$builder->empty) {
            $builder->execute();
            \DataImport\Common\DataImportLog::F()->log("Импортировано {$readed} строк");
        }
        $index += $readed;
        $total += $readed;
        if ($readed === 0 || $total > $max_row_number) {
            \DataImport\Common\DataImportLog::F()->log("Удаление временного файла");
            @unlink($path);
            $logtext = \DataImport\Common\DataImportLog::F()->get_text("\n");
            $this->write_log($input, $logtext);
            \DataImport\Common\ImportRedirectException::F($this->import_permanent_params([
                        'step' => 3, 'table' => $table,
            ]));
        }
        $logtext = \DataImport\Common\DataImportLog::F()->get_text("\n");
        $this->write_log($input, $logtext);
        \DataImport\Common\ImportRedirectException::F($this->import_permanent_params([
                    'step' => 2, 'file_name' => $this->get_encoded_filename_from_input($input),
                    'table' => $table, 'total_readed' => $total, 'index' => $index, 'offset' => $offset,
                    'row_count' => $max_row_number, 'column_count' => $max_letter
        ]));
    }

    //</editor-fold>

    /**
     * пустой шаг
     * @param \DataMap\IDataMap $input
     * @param \DataMap\UploadedFile $file
     */
    //<editor-fold defaultstate="collapsed" desc="step 3">
    public function run_step_3(\DataMap\IDataMap $input, \DataMap\UploadedFile $file = null) {
        $table = $input->get_filtered('table', ['Strip', 'Trim', 'NEString', 'DefaultNull']);
        $table ? 0 : \DataImport\Common\DataImportError::R("no temporary table name provided");
        $tn = $table;
        \DataImport\Common\DataImportLog::F()->log("Очистка некорректных родительских ссылок");
        $logtext = \DataImport\Common\DataImportLog::F()->get_text("\n");
        $this->write_log($input, $logtext);
        \DataImport\Common\ImportRedirectException::F($this->import_permanent_params([
                    'step' => 4, 'table' => $table,
        ]));
    }

    //</editor-fold>

    /**
     * фикс родительских ссылок
     * @param \DataMap\IDataMap $input
     * @param \DataMap\UploadedFile $file
     */
    //<editor-fold defaultstate="collapsed" desc="step 4">
    public function run_step_4(\DataMap\IDataMap $input, \DataMap\UploadedFile $file = null) {
        $table = $input->get_filtered('table', ['Strip', 'Trim', 'NEString', 'DefaultNull']);
        $table ? 0 : \DataImport\Common\DataImportError::R("no temporary table name provided");
        $tn = $table;
        \DB\SQLTools\SQLBuilder::F()->push("UPDATE `{$table}` SET parent_guid=NULL WHERE parent_guid=0;")->execute();
        \DataImport\Common\DataImportLog::F()->log("Очистка некорректных ссылок аналогов");
        $logtext = \DataImport\Common\DataImportLog::F()->get_text("\n");
        $this->write_log($input, $logtext);
        \DataImport\Common\ImportRedirectException::F($this->import_permanent_params([
                    'step' => 5, 'table' => $table,
        ]));
    }

    //</editor-fold>

    /**
     * фикс аналогов и индексы
     * @param \DataMap\IDataMap $input
     * @param \DataMap\UploadedFile $file
     */
    //<editor-fold defaultstate="collapsed" desc="step 5">
    public function run_step_5(\DataMap\IDataMap $input, \DataMap\UploadedFile $file = null) {
        $table = $input->get_filtered('table', ['Strip', 'Trim', 'NEString', 'DefaultNull']);
        $table ? 0 : \DataImport\Common\DataImportError::R("no temporary table name provided");
        $tn = $table;
        \DB\SQLTools\SQLBuilder::F()->push("UPDATE `{$tn}` SET analog=NULL WHERE analog=0;")->execute();
        \DataImport\Common\DataImportLog::F()->log("Индексация по analog");
        \DB\SQLTools\SQLBuilder::F()->push("ALTER TABLE `{$tn}` ADD INDEX (`analog`);")->execute();
        \DataImport\Common\DataImportLog::F()->log("Индексация по article");
        \DB\SQLTools\SQLBuilder::F()->push("ALTER TABLE `{$tn}` ADD INDEX (`article`);")->execute();
        \DB\SQLTools\SQLBuilder::F()->push("ALTER TABLE `{$tn}` ADD INDEX (`source_article`);")->execute();
        \DataImport\Common\DataImportLog::F()->log("Удаление analog=null && is_group=0 && нет дочерних ссылок");
        $logtext = \DataImport\Common\DataImportLog::F()->get_text("\n");
        $this->write_log($input, $logtext);
        \DataImport\Common\ImportRedirectException::F($this->import_permanent_params([
                    'step' => "6", 'table' => $table,
        ]));
    }

    //</editor-fold>

    /**
     * Удаление пустых аналогов
     * @param \DataMap\IDataMap $input
     * @param \DataMap\UploadedFile $file
     */
    //<editor-fold defaultstate="collapsed" desc="step 6">
    public function run_step_6(\DataMap\IDataMap $input, \DataMap\UploadedFile $file = null) {
        $table = $input->get_filtered('table', ['Strip', 'Trim', 'NEString', 'DefaultNull']);
        $table ? 0 : \DataImport\Common\DataImportError::R("no temporary table name provided");
        $tn = $table;
        \DB\SQLTools\SQLBuilder::F()->push("DELETE A.* FROM `{$tn}` A LEFT JOIN `{$tn}` B ON(B.analog=A.guid)
                WHERE A.analog IS NULL AND A.is_group=0 AND B.guid IS NULL;")->execute();
        \DataImport\Common\DataImportLog::F()->log("Восстанавливаем цепочку аналогов до корневых элементов");
        $logtext = \DataImport\Common\DataImportLog::F()->get_text("\n");
        $this->write_log($input, $logtext);
        \DataImport\Common\ImportRedirectException::F($this->import_permanent_params([
                    'step' => "6_5", 'table' => $table,
        ]));
    }

    //</editor-fold>

    /**
     * фикс аналогов
     * @param \DataMap\IDataMap $input
     * @param \DataMap\UploadedFile $file
     */
    //<editor-fold defaultstate="collapsed" desc="step 6-5">
    public function run_step_6_5(\DataMap\IDataMap $input, \DataMap\UploadedFile $file = null) {
        $table = $input->get_filtered('table', ['Strip', 'Trim', 'NEString', 'DefaultNull']);
        $table ? 0 : \DataImport\Common\DataImportError::R("no temporary table name provided");
        $tn = $table;

        $query = " SELECT CASE WHEN EXISTS(
            SELECT 1 FROM `{$tn}` A JOIN `{$tn}` B ON(B.analog=A.guid)
                WHERE A.analog IS NOT NULL AND A.analog!=A.guid 
            ) THEN 1 ELSE 0 END";
        $icont = 0;
        while (\DB\DB::F()->queryScalari($query)) {
            \DB\SQLTools\SQLBuilder::F()->push("
                UPDATE `{$tn}` A JOIN `{$tn}` B ON(B.analog=A.guid)
                    SET B.analog=A.analog
                    WHERE A.analog IS NOT NULL AND A.analog!=A.guid;
                ")->execute();
            $icont++;
        }
        \DataImport\Common\DataImportLog::F()->log("Восстановлено за {$icont} итераций");
        \DataImport\Common\DataImportLog::F()->log("Выделение товаров в отдельную таблицу");
        $logtext = \DataImport\Common\DataImportLog::F()->get_text("\n");
        $this->write_log($input, $logtext);
        \DataImport\Common\ImportRedirectException::F($this->import_permanent_params([
                    'step' => 7, 'table' => $table,
        ]));
    }

    //</editor-fold>

    /**
     * выделение товаров
     * @param \DataMap\IDataMap $input
     * @param \DataMap\UploadedFile $file
     */
    //<editor-fold defaultstate="collapsed" desc="step 7">
    public function run_step_7(\DataMap\IDataMap $input, \DataMap\UploadedFile $file = null) {
        $table = $input->get_filtered('table', ['Strip', 'Trim', 'NEString', 'DefaultNull']);
        $table ? 0 : \DataImport\Common\DataImportError::R("no temporary table name provided");
        $tn = $table;

        \DB\SQLTools\SQLBuilder::F()->push("DROP  TABLE IF EXISTS `{$tn}products`; 
            CREATE  table `{$tn}products` (guid VARCHAR(100) NOT NULL,article VARCHAR(100) NULL DEFAULT NULL,
                name VARCHAR(255) NOT NULL,full_name VARCHAR(255) NULL DEFAULT NULL,parent_guid VARCHAR(100) NULL DEFAULT NULL,
                is_group INT(11) UNSIGNED NOT NULL DEFAULT 0,color VARCHAR(255) NULL DEFAULT NULL,
                size VARCHAR(255) NULL DEFAULT NULL,price_opt DOUBLE NULL DEFAULT NULL,
                price_retail DOUBLE NULL DEFAULT NULL,consits MEDIUMTEXT,description MEDIUMTEXT,
                price_opt_old DOUBLE NULL DEFAULT NULL,price_retail_old DOUBLE NULL DEFAULT NULL,
                discount_opt DOUBLE NULL DEFAULT NULL,discount_retail DOUBLE NULL DEFAULT NULL,
                analog VARCHAR(100) NULL DEFAULT NULL,source_article VARCHAR(100) NULL DEFAULT NULL,
                PRIMARY KEY(guid))ENGINE=MyISAM;")->execute();

        \DB\SQLTools\SQLBuilder::F()->push("INSERT INTO `{$tn}products` (guid,article,
                name,full_name,parent_guid,is_group,color,size,price_opt,price_retail,consits,description,
                price_opt_old,price_retail_old,discount_opt,discount_retail,analog,source_article)
                SELECT * FROM `{$tn}` WHERE guid=analog AND is_group=0;")->execute();
        \DataImport\Common\DataImportLog::F()->log("Индексация по article");
        \DB\SQLTools\SQLBuilder::F()->push("ALTER TABLE `{$tn}products` ADD INDEX (`article`);")->execute();
        \DB\SQLTools\SQLBuilder::F()->push("ALTER TABLE `{$tn}products` ADD INDEX (`source_article`);")->execute();
        \DataImport\Common\DataImportLog::F()->log("Забиваем пропущенные артикулы фиктивными");
        $logtext = \DataImport\Common\DataImportLog::F()->get_text("\n");
        $this->write_log($input, $logtext);
        \DataImport\Common\ImportRedirectException::F($this->import_permanent_params([
                    'step' => "7_5", 'table' => $table,
        ]));
    }

    //</editor-fold>


    protected function run_step_7_5(\DataMap\IDataMap $input, \DataMap\UploadedFile $file = null) {
        $table = $input->get_filtered('table', ['Strip', 'Trim', 'NEString', 'DefaultNull']);
        $table ? 0 : \DataImport\Common\DataImportError::R("no temporary table name provided");
        $tn = $table;
        \DataImport\Common\DataImportLog::F()->log("Сохраняем связи товарных предложений.....");
        $logtext = \DataImport\Common\DataImportLog::F()->get_text("\n");
        /*  нужно разнести гуиды товаров - fixed_guid,цвет,размер-->guid во входном списке */
        // аналог - цвет-размер = гуид
        // и нужен забойный ключ
        //empty_color = a7a966c8699d42168e534af0b0cc7689
        //empty_size =  cdd11a1370a74849a93a430ef4a61cc9
        // все таки guid(P)-color-size-analog
        $b = \DB\SQLTools\SQLBuilder::F();
        $b->push("DROP TABLE IF EXISTS `{$tn}product_guid`;
            CREATE TABLE `{$tn}product_guid` (
                guid VARCHAR(100) NOT NULL,
                color VARCHAR(100)  NULL DEFAULT NULL,
                size VARCHAR(100)  NULL DEFAULT NULL,
                analog VARCHAR(100) NOT NULL,PRIMARY KEY(guid))ENGINE=MyISAM;
            INSERT INTO `{$tn}product_guid` (guid,color,size,analog)    
                SELECT guid,CASE WHEN CHAR_LENGTH(TRIM(color))>0 THEN CONCAT(analog,'-',TRIM(color)) ELSE NULL END color,
                CASE WHEN CHAR_LENGTH(TRIM(size))>0 AND size!=0 THEN TRIM(size) ELSE NULL END size,
                analog
                FROM `{$tn}` WHERE ANALOG IS NOT NULL AND GUID IS NOT NULL;
        ");
        $b->execute();
        \DataImport\Common\ImportRedirectException::F($this->import_permanent_params([
                    'step' => "8", 'table' => $table,
        ]));
    }

    /**
     * фикс артикулов
     * @param \DataMap\IDataMap $input
     * @param \DataMap\UploadedFile $file
     */
    //<editor-fold defaultstate="collapsed" desc="step 8">
    protected function run_step_8(\DataMap\IDataMap $input, \DataMap\UploadedFile $file = null) {
        $table = $input->get_filtered('table', ['Strip', 'Trim', 'NEString', 'DefaultNull']);
        $table ? 0 : \DataImport\Common\DataImportError::R("no temporary table name provided");
        $tn = $table;
        \DB\SQLTools\SQLBuilder::F()->push("UPDATE `{$tn}products` SET article= UUID() WHERE article IS NULL;")->execute();
        \DataImport\Common\DataImportLog::F()->log("Забиваем дублирующиеся артикулы");
        \DB\SQLTools\SQLBuilder::F()->push("UPDATE `{$tn}products` A JOIN `{$tn}products` B ON(A.guid!=B.guid AND A.article=B.article)
                SET A.article=CONCAT(A.article,'-',A.guid);")->execute();

        \DataImport\Common\DataImportLog::F()->log("Добавление колонки алиаса");
        $logtext = \DataImport\Common\DataImportLog::F()->get_text("\n");
        $this->write_log($input, $logtext);
        \DataImport\Common\ImportRedirectException::F($this->import_permanent_params([
                    'step' => "8_4", 'table' => $table,
        ]));
    }

    //</editor-fold>
    protected function run_step_8_4(\DataMap\IDataMap $input, \DataMap\UploadedFile $file = null) {
        $table = $input->get_filtered('table', ['Strip', 'Trim', 'NEString', 'DefaultNull']);
        $table ? 0 : \DataImport\Common\DataImportError::R("no temporary table name provided");
        $tn = $table;

        \DB\SQLTools\SQLBuilder::F()->push("ALTER TABLE `{$tn}products` ADD `alias` VARCHAR(255)  NULL DEFAULT NULL AFTER `analog`;")->execute();
        \DataImport\Common\DataImportLog::F()->log("Транслитерация алиасов");
        $logtext = \DataImport\Common\DataImportLog::F()->get_text("\n");
        $this->write_log($input, $logtext);
        \DataImport\Common\ImportRedirectException::F($this->import_permanent_params([
                    'step' => "8_5", 'table' => $table,
        ]));
    }

    protected function run_step_8_5(\DataMap\IDataMap $input, \DataMap\UploadedFile $file = null) {
        $table = $input->get_filtered('table', ['Strip', 'Trim', 'NEString', 'DefaultNull']);
        $table ? 0 : \DataImport\Common\DataImportError::R("no temporary table name provided");
        $tn = $table;
        $translit_artcile_offset = $input->get_filtered("translit_article_offset", ['IntMore0', 'Default0']);
        $step = 500;
        $rows = \DB\DB::F()->queryAll("SELECT guid,name FROM `{$tn}products` ORDER BY guid LIMIT {$step} OFFSET {$translit_artcile_offset};");
        if (count($rows)) {
            \DataImport\Common\DataImportLog::F()->log("Транслитерация алиасов c {$translit_artcile_offset} по " . ($translit_artcile_offset + count($rows)));
            $builder = \DB\SQLTools\SQLBuilder::F();
            $b = $builder;
            foreach ($rows as $row) {
                $alias = implode("_", [\Helpers\Helpers::translit($row['name']), $row['guid']]);
                $builder->push("UPDATE `{$tn}products` SET alias=:P{$b->c}alias WHERE guid=:P{$b->c}guid;");
                $builder->push_params([
                    ":P{$b->c}alias" => $alias,
                    ":P{$b->c}guid" => $row['guid']
                ]);
                $builder->inc_counter();
            }
            $builder->execute();
            $translit_artcile_offset += count($rows);
            $logtext = \DataImport\Common\DataImportLog::F()->get_text("\n");
            $this->write_log($input, $logtext);
            \DataImport\Common\ImportRedirectException::F($this->import_permanent_params([
                        'step' => "8_5", 'table' => $table, 'translit_article_offset' => $translit_artcile_offset
            ]));
        }

        \DataImport\Common\DataImportLog::F()->log("Импорт основных записей товаров");
        $logtext = \DataImport\Common\DataImportLog::F()->get_text("\n");
        $this->write_log($input, $logtext);
        \DataImport\Common\ImportRedirectException::F($this->import_permanent_params([
                    'step' => "9", 'table' => $table,
        ]));
    }

    /**
     * импорт товаров
     * @param \DataMap\IDataMap $input
     * @param \DataMap\UploadedFile $file
     */
    //<editor-fold defaultstate="collapsed" desc="step 9">
    protected function run_step_9(\DataMap\IDataMap $input, \DataMap\UploadedFile $file = null) {
        $table = $input->get_filtered('table', ['Strip', 'Trim', 'NEString', 'DefaultNull']);
        $table ? 0 : \DataImport\Common\DataImportError::R("no temporary table name provided");
        $tn = $table;
        \DB\SQLTools\SQLBuilder::F()->push("INSERT INTO catalog__product (guid,alias,enabled,article,source_article)
            SELECT guid,alias,{$this->get_default_product_visible_state()},article,source_article
                FROM `{$tn}products` 
            ON DUPLICATE KEY UPDATE article=VALUES(article),source_article=VALUES(source_article);")->execute();
        \DataImport\Common\DataImportLog::F()->log("Импорт наименований и описаний товаров");
        $logtext = \DataImport\Common\DataImportLog::F()->get_text("\n");
        $this->write_log($input, $logtext);
        \DataImport\Common\ImportRedirectException::F($this->import_permanent_params([
                    'step' => 10, 'table' => $table,
        ]));
    }

    //</editor-fold>

    /**
     * импорт строк
     * @param \DataMap\IDataMap $input
     * @param \DataMap\UploadedFile $file
     */
    //<editor-fold defaultstate="collapsed" desc="step 10">
    protected function run_step_10(\DataMap\IDataMap $input, \DataMap\UploadedFile $file = null) {
        $table = $input->get_filtered('table', ['Strip', 'Trim', 'NEString', 'DefaultNull']);
        $table ? 0 : \DataImport\Common\DataImportError::R("no temporary table name provided");
        $tn = $table;
        \DB\SQLTools\SQLBuilder::F()->push("INSERT INTO catalog__product__strings(id,name,description,consists)
            SELECT B.id, CASE WHEN CHAR_LENGTH(TRIM(A.full_name))>0 THEN A.full_name ElSE A.name END,A.description,A.consits  FROM `{$tn}products` A JOIN catalog__product B ON(A.guid=B.guid)
                ON DUPLICATE KEY UPDATE name=VALUES(name),description=VALUES(description),consists=VALUES(consists);                    
            ")->execute(); //основные товары вставлены. теперь надо создать идентификаторы размеров
        $logtext = \DataImport\Common\DataImportLog::F()->get_text("\n");
        $this->write_log($input, $logtext);
        \DataImport\Common\ImportRedirectException::F($this->import_permanent_params([
                    'step' => 11, 'table' => $table,
        ]));
    }

    //</editor-fold>

    /**
     * выделение и импорт размеров
     * @param \DataMap\IDataMap $input
     * @param \DataMap\UploadedFile $file
     */
    //<editor-fold defaultstate="collapsed" desc="step 11">
    protected function run_step_11(\DataMap\IDataMap $input, \DataMap\UploadedFile $file = null) {
        $table = $input->get_filtered('table', ['Strip', 'Trim', 'NEString', 'DefaultNull']);
        $table ? 0 : \DataImport\Common\DataImportError::R("no temporary table name provided");
        $tn = $table;
        \DataImport\Common\DataImportLog::F()->log("Выделение таблицы размеров");
        \DB\SQLTools\SQLBuilder::F()->push("DROP  TABLE IF EXISTS `{$tn}size`;
            CREATE TABLE `{$tn}size` (guid VARCHAR(100), value VARCHAR(100),PRIMARY KEY(guid));
            INSERT INTO `{$tn}size` (guid,value)
                SELECT TRIM(size),TRIM(size) FROM `{$tn}`
                    WHERE CHAR_LENGTH(TRIM(size))>0 AND size IS NOT NULL AND size!=0
            ON DUPLICATE KEY UPDATE guid=VALUES(guid),value=VALUES(value);")->execute();
        \DataImport\Common\DataImportLog::F()->log("Обновление существующих размеров");
        \DB\SQLTools\SQLBuilder::F()->push("UPDATE catalog__size__def A JOIN `{$tn}size` B ON(A.guid=B.guid) SET A.size=B.value;")->execute();
        \DataImport\Common\DataImportLog::F()->log("Импорт новых размеров");
        \DB\SQLTools\SQLBuilder::F()->push("            
            DELETE A.* FROM `{$tn}size` A JOIN catalog__size__def B ON(A.guid=B.guid);            
            INSERT INTO catalog__size__def (guid,size) SELECT guid,value FROM `{$tn}size`;                                
        ")->execute();
        $logtext = \DataImport\Common\DataImportLog::F()->get_text("\n");
        $this->write_log($input, $logtext);
        \DataImport\Common\ImportRedirectException::F($this->import_permanent_params([
                    'step' => "11_5", 'table' => $table,
        ]));
    }

    //</editor-fold>

    /**
     * импорт размеров
     * @param \DataMap\IDataMap $input
     * @param \DataMap\UploadedFile $file
     */
    //<editor-fold defaultstate="collapsed" desc="step 11-5">
    protected function run_step_11_5(\DataMap\IDataMap $input, \DataMap\UploadedFile $file = null) {
        $table = $input->get_filtered('table', ['Strip', 'Trim', 'NEString', 'DefaultNull']);
        $table ? 0 : \DataImport\Common\DataImportError::R("no temporary table name provided");
        $tn = $table;
        \DataImport\Common\DataImportLog::F()->log("Обновление записей размеров в товарах");
        \DB\SQLTools\SQLBuilder::F()->push("ALTER TABLE `{$tn}` ADD INDEX (`size`);")->execute();
        \DB\SQLTools\SQLBuilder::F()->push("DROP TABLE IF EXISTS `{$tn}size_import`;
            CREATE TABLE `{$tn}size_import` (product_guid VARCHAR(100),size_guid VARCHAR(100),PRIMARY KEY(product_guid,size_guid));
            INSERT INTO `{$tn}size_import` SELECT analog,TRIM(size)
            FROM `{$tn}` WHERE size  iS NOT NULL AND analog IS NOT NULL AND CHAR_LENGTH(TRIM(size))>0 AND size!=0
            ON DUPLICATE KEY UPDATE size_guid=VALUES(size_guid)    ;
            ")->execute();
        \DB\SQLTools\SQLBuilder::F()->push("
            INSERT INTO catalog__product__size (product_id,size_id)
            SELECT P.id,S.id
            FROM  `{$tn}size_import` A 
            JOIN catalog__product P ON(P.guid=A.product_guid)    
            JOIN catalog__size__def S ON(S.guid=A.size_guid)
            ON DUPLICATE KEY UPDATE enabled=1;
            ")->execute();
        $logtext = \DataImport\Common\DataImportLog::F()->get_text("\n");
        $this->write_log($input, $logtext);
        \DataImport\Common\ImportRedirectException::F($this->import_permanent_params([
                    'step' => "12", 'table' => $table,
        ]));
    }

    //</editor-fold>

    /**
     * выделение цветов
     * @param \DataMap\IDataMap $input
     * @param \DataMap\UploadedFile $file
     */
    //<editor-fold defaultstate="collapsed" desc="step 12">
    protected function run_step_12(\DataMap\IDataMap $input, \DataMap\UploadedFile $file = null) {
        $table = $input->get_filtered('table', ['Strip', 'Trim', 'NEString', 'DefaultNull']);
        $table ? 0 : \DataImport\Common\DataImportError::R("no temporary table name provided");
        $tn = $table;
        \DataImport\Common\DataImportLog::F()->log("Выделение таблицы цветов");
        \DB\SQLTools\SQLBuilder::F()->push("DROP  TABLE IF EXISTS `{$tn}color`;
            CREATE TABLE `{$tn}color` (product_guid VARCHAR(150) NOT NULL,
                color_guid VARCHAR(150) NOT NULL, name VARCHAR(100),
                PRIMARY KEY(product_guid,color_guid));")->execute();
        \DB\SQLTools\SQLBuilder::F()->push("ALTER TABLE `{$tn}` ADD INDEX (`color`);")->execute();
        \DB\SQLTools\SQLBuilder::F()->push("INSERT INTO `{$tn}color` (product_guid,color_guid,name)
             SELECT analog,CONCAT(analog,'-',color),color
             FROM `{$tn}` 
             WHERE color IS NOT NULL AND analog IS NOT NULL AND CHAR_LENGTH(TRIM(color))>0                 
             ON DUPLICATE KEY UPDATE name=VALUES(name);")->execute();
        \DataImport\Common\DataImportLog::F()->log("Импорт цветов в основную схему");
        $logtext = \DataImport\Common\DataImportLog::F()->get_text("\n");
        $this->write_log($input, $logtext);
        \DataImport\Common\ImportRedirectException::F($this->import_permanent_params([
                    'step' => 13, 'table' => $table,
        ]));
    }

    //</editor-fold>

    /**
     * импорт цветов
     * @param \DataMap\IDataMap $input
     * @param \DataMap\UploadedFile $file
     */
    //<editor-fold defaultstate="collapsed" desc="step 13">
    protected function run_step_13(\DataMap\IDataMap $input, \DataMap\UploadedFile $file = null) {
        $table = $input->get_filtered('table', ['Strip', 'Trim', 'NEString', 'DefaultNull']);
        $table ? 0 : \DataImport\Common\DataImportError::R("no temporary table name provided");
        $tn = $table;
        \DB\SQLTools\SQLBuilder::F()->push("INSERT INTO catalog__product__color (guid,product_id,exchange_uid,sort,html_color)
            SELECT UUID(), P.id,C.color_guid,0,'#ececec'
            FROM `{$tn}color` C JOIN catalog__product P ON(P.guid=C.product_guid)
            ON DUPLICATE KEY UPDATE  exchange_uid=VALUES(exchange_uid);
            ")->execute();
        \DataImport\Common\DataImportLog::F()->log("Импорт наименований цветов в основную схему");
        \DB\SQLTools\SQLBuilder::F()->push("INSERT INTO catalog__product__color__strings (guid,name)
            SELECT A.guid,B.name
            FROM catalog__product__color A 
            JOIN catalog__product P ON(P.id=A.product_id)
            JOIN `{$tn}color` B 
              ON(A.exchange_uid=B.color_guid AND B.product_guid=P.guid)  
            ON DUPLICATE KEY UPDATE  name=VALUES(name);
            ")->execute();
        \DataImport\Common\DataImportLog::F()->log("Выделение категорий");
        $logtext = \DataImport\Common\DataImportLog::F()->get_text("\n");
        $this->write_log($input, $logtext);
        \DataImport\Common\ImportRedirectException::F($this->import_permanent_params([
                    'step' => "13_5", 'table' => $table,
        ]));
    }

    protected function run_step_13_5(\DataMap\IDataMap $input, \DataMap\UploadedFile $file = null) {
        $table = $input->get_filtered('table', ['Strip', 'Trim', 'NEString', 'DefaultNull']);
        $table ? 0 : \DataImport\Common\DataImportError::R("no temporary table name provided");
        $tn = $table;
        \DB\SQLTools\SQLBuilder::F()->push("
            DROP TABLE IF EXISTS `{$tn}categories`;
            CREATE TABLE `{$tn}categories` (
                guid VARCHAR(80) NOT NULL ,
                parent_guid VARCHAR(80) NULL DEFAULT NULL,
                name VARCHAR(1024) NOT NULL,
                info MEDIUMTEXT,
                PRIMARY KEY (guid));
            INSERT INTO `{$tn}categories` (guid,parent_guid,name,info)
                SELECT guid,parent_guid,COALESCE(name,full_name),description
                FROM `{$tn}` WHERE is_group=1 AND COALESCE(name,full_name) IS NOT NULL
            ON DUPLICATE KEY UPDATE parent_guid=VALUES(parent_guid),name=VALUES(name),info=VALUES(info);
            ")->execute();
        \DataImport\Common\DataImportLog::F()->log("Восстановление дерева категорий");
        $logtext = \DataImport\Common\DataImportLog::F()->get_text("\n");
        $this->write_log($input, $logtext);
        \DataImport\Common\ImportRedirectException::F($this->import_permanent_params([
                    'step' => 14, 'table' => $table,
        ]));
    }

    //</editor-fold>

    /**
     * импорт категорий
     * @param \DataMap\IDataMap $input
     * @param \DataMap\UploadedFile $file
     */
    //<editor-fold defaultstate="collapsed" desc="step 14">
    protected function run_step_14(\DataMap\IDataMap $input, \DataMap\UploadedFile $file = null) {
        $table = $input->get_filtered('table', ['Strip', 'Trim', 'NEString', 'DefaultNull']);
        $table ? 0 : \DataImport\Common\DataImportError::R("no temporary table name provided");
        $tn = $table;
        $categories = \DB\DB::F()->queryAll("SELECT guid uid,name,parent_guid parent, info
            FROM `{$tn}categories`;
            ");
        $imported_catgories = []; /* @var $imported_catgories ImportedCategory */
        $root_catgories = []; /* @var $root_catgories ImportedCategory */
        $category_index = []; /* @var $category_index ImportedCategory */
        $cvoc = \CatalogTree\CatalogTree::F(); /* @var $cvoc \CatalogTree\CatalogTree */
        foreach ($categories as $category_raw) {
            $category = ImportedCategory::FA($category_raw);
            if ($category && $category->valid) {
                if (array_key_exists($category->category_key, $category_index)) {
                    die("reimort" . __LINE__ . __FILE__);
                }
                $imported_catgories[] = $category;
                $category_index[$category->category_key] = $category;
                $catalog_category = $cvoc->get_item_by_guid($category->uid);
                if ($catalog_category) {
                    $category->set_exists_id($catalog_category->id);
                }
                if ($category->parent) {
                    $parent_category = $cvoc->get_item_by_guid($category->parent);
                    if ($parent_category) {
                        $category->set_exists_parent_id($parent_category->id);
                    }
                }
            }
        }
        foreach ($imported_catgories as $category) {
            if ($category->parent) {
                if (array_key_exists($category->parent_key, $category_index)) {
                    $category_index[$category->parent_key]->add_child($category);
                }
            } else {
                $root_catgories[] = $category;
            }
        }
        \DataImport\Common\DataImportLog::F()->log(sprintf("Прочитано %s категорий, %s корневых", count($category_index), count($root_catgories)));
        unset($category_index);
        unset($imported_catgories);
        unset($categories);
        $tmp_dir = $this->get_temp_dir_name();
        $tmp_name = md5(__CLASS__ . time()) . ".bin";
        $tmp_file = "{$tmp_dir}{$tmp_name}";
        if (file_put_contents($tmp_file, serialize($root_catgories)) === false) {
            \Errors\common_error::R("cant store catalog tree into temp file");
        }
        \DataImport\Common\ImportRedirectException::F($this->import_permanent_params([
                    'step' => "14_5", 'table' => $table,
                    'filename' => $tmp_name
        ]));
        if (false) {
            $guid_to_var = [];
            $b = \DB\SQLTools\SQLBuilder::F();
            $this->create_categories_sql($root_catgories, $b, $guid_to_var);
            if (!$b->empty) {
                //  die($b->sql);
                $b->execute();
                die('aaaaaaa');
            }
            //\CatalogTree\CatalogTree::clear_dependency_beacon();
            \DataImport\Common\DataImportLog::F()->log("Разнос товаров по импортированным категориям");
            \DataImport\Common\ImportRedirectException::F($this->import_permanent_params([
                        'step' => 15, 'table' => $table,
            ]));
        }
    }

    protected function create_cat_insert_sql(array $categories, array &$inserts, array &$params, int &$c) {
        foreach ($categories as $category) {/* @var $category ImportedCategory */
            $c++;
            $inserts[] = "(:P{$c}guid,:P{$c}id,:P{$c}parent_guid,:P{$c}parent_id,:P{$c}visible,:P{$c}name,:P{$c}alias,:P{$c}info)";
            $params = array_merge($params, [
                ":P{$c}guid" => $category->uid,
                ":P{$c}id" => $category->id ? $category->id : null,
                ":P{$c}parent_guid" => $category->parent,
                ":P{$c}parent_id" => $category->parent_id ? $category->parent_id : null,
                ":P{$c}visible" => $this->get_default_visible_state(),
                ":P{$c}name" => $category->name,
                ":P{$c}alias" => $this->createCategoryAlias($category),
                ":P{$c}info" => $category->info,
            ]);
            if ($category->childs && count($category->childs)) {
                $this->create_cat_insert_sql($category->childs, $inserts, $params, $c);
            }
        }
    }

    protected function run_step_14_5(\DataMap\IDataMap $input, \DataMap\UploadedFile $file = null) {
        $table = $input->get_filtered('table', ['Strip', 'Trim', 'NEString', 'DefaultNull']);
        $table ? 0 : \DataImport\Common\DataImportError::R("no temporary table name provided");
        $tn = $table;
        $filename = $input->get_filtered('filename', ['Strip', 'Trim', 'NEString', 'DefaultNull']);
        $filename ? 0 : \Errors\common_error::R("no tep bin name provided");
        $file_path = $this->get_temp_dir_name() . $filename;
        file_exists($file_path) && is_file($file_path) && is_readable($file_path) ? 0 : \Errors\common_error::R('tmp bin is unaccessible');
        $root = unserialize(file_get_contents($file_path));
        $query = "DROP TABLE IF EXISTS `{$tn}c3`; 
            CREATE TABLE `{$tn}c3` (guid VARCHAR(80),id INT(11) UNSIGNED NULL DEFAULT NULL,
                parent_guid VARCHAR(80) NULL DEFAULT NULL,
                parent_id INT(11) UNSIGNED NULL DEFAULT NULL,
                visible INT(1) UNSIGNED NOT NULL DEFAULT 0,
                name VARCHAR(1024) NOT NULL,
                alias VARCHAR(1024) NOT NULL,
                info MEDIUMTEXT,
                PRIMARY KEY(guid)                
                );                            
        ";
        $inserts = [];
        $params = [];
        $counter = 0;
        $this->create_cat_insert_sql($root, $inserts, $params, $counter);
        if (count($inserts)) {
            $query .= "INSERT INTO `{$tn}c3` (guid,id,parent_guid,parent_id,visible,name,alias,info)
                VALUES " . implode(",", $inserts) . ";
            ";
        }
        $builder = \DB\SQLTools\SQLBuilder::F()->push($query)->push_params($params);
        $builder->execute();
        \DataImport\Common\DataImportLog::F()->log("Индексация");
        \DB\SQLTools\SQLBuilder::F()->push("ALTER TABLE `{$tn}c3` ADD INDEX (`id`);")->execute();
        \DB\SQLTools\SQLBuilder::F()->push("ALTER TABLE `{$tn}c3` ADD INDEX (`parent_guid`);")->execute();
        \DB\SQLTools\SQLBuilder::F()->push("ALTER TABLE `{$tn}c3` ADD INDEX (`parent_id`);")->execute();
        \DataImport\Common\DataImportLog::F()->log("Восстановление связей дерева категорий");
        \DataImport\Common\ImportRedirectException::F($this->import_permanent_params([
                    'step' => "14_6", 'table' => $table,
        ]));
    }

    //</editor-fold>
    protected function run_step_14_6(\DataMap\IDataMap $input, \DataMap\UploadedFile $file = null) {
        $table = $input->get_filtered('table', ['Strip', 'Trim', 'NEString', 'DefaultNull']);
        $table ? 0 : \DataImport\Common\DataImportError::R("no temporary table name provided");
        $tn = $table;
        \DB\SQLTools\SQLBuilder::F()->push("
            UPDATE `{$tn}c3` A LEFT JOIN catalog__group B ON(A.guid = B.guid)
                SET A.id=B.id;
            ")->execute();
        \DB\SQLTools\SQLBuilder::F()->push("
            UPDATE `{$tn}c3` A LEFT JOIN catalog__group B ON(A.parent_guid = B.guid)
                SET A.parent_id=B.id;
            ")->execute();
        //нужно как-то удалить ненужные категории - те в которых нет ни одного товара
        //// и как-нибудь при этом не зацепить дочерние (финальные категории - нет дочерних и нет товаров?)
        \DataImport\Common\DataImportLog::F()->log("Поиск и удаление пустых категорий");
        \DB\SQLTools\SQLBuilder::F()->push("ALTER TABLE `{$tn}products` ADD INDEX (`parent_guid`);")->execute();
        if (true) {
            for ($i = 0; $i < 10; $i++) {
                \DB\SQLTools\SQLBuilder::F()->push("DROP TEMPORARY TABLE IF EXISTS `cdel{$tn}`;
            CREATE TEMPORARY TABLE `cdel{$tn}`( guid VARCHAR(80) NOT NULL,PRIMARY KEY(guid));
            INSERT INTO `cdel{$tn}`(guid)    
            SELECT A.guid
            FROM `{$tn}c3` A 
            LEFT JOIN `{$tn}c3` B ON(A.guid=B.parent_guid)
            LEFT JOIN `{$tn}products` P ON(P.parent_guid = A.guid)    
            WHERE B.guid IS NULL AND P.guid IS NULL ;
            DELETE A.* FROM `{$tn}c3` A JOIN `cdel{$tn}` B ON(B.guid=A.guid);
            ")->execute();
            }
        }
        // вставляем нулловые - те, которых пока не существовало
        \DB\SQLTools\SQLBuilder::F()->push("
            INSERT INTO catalog__group (parent_id,sort_order,name,visible,alias,guid,info,meta_keywords,meta_description,og_description)
            SELECT NULL,0,name,visible,alias,guid,info,'','',''
            FROM `{$tn}c3` WHERE id IS NULL;
            ")->execute();
        // и снова пересканируем id
        \DB\SQLTools\SQLBuilder::F()->push("
            UPDATE `{$tn}c3` A LEFT JOIN catalog__group B ON(A.guid = B.guid)
                SET A.id=B.id;
            ")->execute();
        \DB\SQLTools\SQLBuilder::F()->push("
            UPDATE `{$tn}c3` A LEFT JOIN catalog__group B ON(A.parent_guid = B.guid)
                SET A.parent_id=B.id;
            ")->execute();
        if ($this->get_default_category_parent()) {
            \DB\SQLTools\SQLBuilder::F()->push("
            UPDATE `{$tn}c3` SET parent_id=:P WHERE parent_id IS NULL;
            ")->push_param(":P", $this->get_default_category_parent())->execute();
        }

        // а вот теперь - обновляем по уже существующим связям
        \DB\SQLTools\SQLBuilder::F()->push("
             UPDATE catalog__group A JOIN `{$tn}c3` B ON(A.id=B.id)
                 SET A.parent_id=B.parent_id,A.name=B.name,A.info=B.info;
             ")->execute();
        \CatalogTree\CatalogTree::clear_dependency_beacon();
        \DataImport\Common\DataImportLog::F()->log("бинд товаров к категориям");
        $logtext = \DataImport\Common\DataImportLog::F()->get_text("\n");
        $this->write_log($input, $logtext);
        \DataImport\Common\ImportRedirectException::F($this->import_permanent_params([
                    'step' => "15", 'table' => $table,
        ]));
    }

    /**
     * бинд товаров к категориям
     * @param \DataMap\IDataMap $input
     * @param \DataMap\UploadedFile $file
     */
    //<editor-fold defaultstate="collapsed" desc="step 15">
    protected function run_step_15(\DataMap\IDataMap $input, \DataMap\UploadedFile $file = null) {
        $table = $input->get_filtered('table', ['Strip', 'Trim', 'NEString', 'DefaultNull']);
        $table ? 0 : \DataImport\Common\DataImportError::R("no temporary table name provided");
        $tn = $table;
        \DB\SQLTools\SQLBuilder::F()->push("
            INSERT INTO catalog__product__group (product_id,group_id)
            SELECT  B.id,C.id
            FROM `{$tn}products` A JOIN catalog__product B ON(B.guid=A.guid)
                JOIN catalog__group C ON(C.guid=A.parent_guid)
                
            ON DUPLICATE KEY UPDATE product_id=VALUES(product_id);
            ")->execute();
        \DataImport\Common\DataImportLog::F()->log("Индексация цеников");
        $logtext = \DataImport\Common\DataImportLog::F()->get_text("\n");
        $this->write_log($input, $logtext);
        \DataImport\Common\ImportRedirectException::F($this->import_permanent_params([
                    'step' => 16, 'table' => $table,
        ]));
    }

    //</editor-fold>

    /**
     * Импорт ценников
     * @param \DataMap\IDataMap $input
     * @param \DataMap\UploadedFile $file
     */
    //<editor-fold defaultstate="collapsed" desc="step 16">
    protected function run_step_16(\DataMap\IDataMap $input, \DataMap\UploadedFile $file = null) {
        $table = $input->get_filtered('table', ['Strip', 'Trim', 'NEString', 'DefaultNull']);
        $table ? 0 : \DataImport\Common\DataImportError::R("no temporary table name provided");
        $tn = $table;
        \DB\SQLTools\SQLBuilder::F()->push("ALTER TABLE `{$tn}` ADD INDEX (`price_retail`);")->execute();
        \DB\SQLTools\SQLBuilder::F()->push("ALTER TABLE `{$tn}` ADD INDEX (`price_opt`);")->execute();
        \DB\SQLTools\SQLBuilder::F()->push("ALTER TABLE `{$tn}` ADD INDEX (`price_retail_old`);")->execute();
        \DB\SQLTools\SQLBuilder::F()->push("ALTER TABLE `{$tn}` ADD INDEX (`price_opt_old`);")->execute();
        \DB\SQLTools\SQLBuilder::F()->push("ALTER TABLE `{$tn}` ADD INDEX (`discount_opt`);")->execute();
        \DB\SQLTools\SQLBuilder::F()->push("ALTER TABLE `{$tn}` ADD INDEX (`discount_retail`);")->execute();
        \DataImport\Common\DataImportLog::F()->log("Выделение ценников");
        $logtext = \DataImport\Common\DataImportLog::F()->get_text("\n");
        $this->write_log($input, $logtext);
        \DataImport\Common\ImportRedirectException::F($this->import_permanent_params([
                    'step' => 17, 'table' => $table,
        ]));
    }

    protected function run_step_17(\DataMap\IDataMap $input, \DataMap\UploadedFile $file = null) {
        $table = $input->get_filtered('table', ['Strip', 'Trim', 'NEString', 'DefaultNull']);
        $table ? 0 : \DataImport\Common\DataImportError::R("no temporary table name provided");
        $tn = $table;
        \DB\SQLTools\SQLBuilder::F()->push("DROP TABLE IF EXISTS `{$tn}price`;
            CREATE TABLE `{$tn}price`(guid VARCHAR(80) NOT NULL,
                price_retail DOUBLE NULL DEFAULT NULL,
                price_opt DOUBLE NULL DEFAULT NULL,
                price_retail_old DOUBLE NULL DEFAULT NULL,
                price_opt_old DOUBLE NULL DEFAULT NULL,
                discount_retail DOUBLE NULL DEFAULT NULL,
                discount_opt DOUBLE NULL DEFAULT NULL,
                PRIMARY KEY (guid)
                );
            INSERT INTO `{$tn}price` (guid,price_retail,price_opt,price_retail_old,price_opt_old,discount_retail,discount_opt)    
                SELECT B.guid,A.price_retail,A.price_opt,A.price_retail_old,A.price_opt_old,A.discount_retail,A.discount_opt
                FROM `catalog__product` B JOIN `{$tn}products` A ON(A.guid=B.guid) 
                ON DUPLICATE KEY UPDATE
                  `{$tn}price`.price_retail = VALUES(price_retail),
                  `{$tn}price`.price_opt = VALUES(price_opt),
                  `{$tn}price`.price_retail_old = VALUES(price_retail_old),
                  `{$tn}price`.price_opt_old = VALUES(price_opt_old),
                  `{$tn}price`.discount_retail = VALUES(discount_retail),
                  `{$tn}price`.discount_opt = VALUES(discount_opt);                                    
            ")->execute();
        \DB\errors\MySQLWarn::F(\DB\DB::F());
        \DataImport\Common\DataImportLog::F()->log("Импорт ценников");
        $logtext = \DataImport\Common\DataImportLog::F()->get_text("\n");
        $this->write_log($input, $logtext);
        \DataImport\Common\ImportRedirectException::F($this->import_permanent_params([
                    'step' => 18, 'table' => $table,
        ]));
    }

    protected function run_step_18(\DataMap\IDataMap $input, \DataMap\UploadedFile $file = null) {
        $table = $input->get_filtered('table', ['Strip', 'Trim', 'NEString', 'DefaultNull']);
        $table ? 0 : \DataImport\Common\DataImportError::R("no temporary table name provided");
        $tn = $table;
        \DB\SQLTools\SQLBuilder::F()->push("
            INSERT INTO catalog__product__price(id,retail,gross,retail_old,gross_old,discount_retail,discount_gross)
                SELECT B.id,A.price_retail,A.price_opt,A.price_retail_old,A.price_opt_old,A.discount_retail,A.discount_opt
                FROM `{$tn}price` A JOIN catalog__product B ON(A.guid=B.guid)
            ON DUPLICATE KEY UPDATE
                retail = VALUES(retail),
                gross = VALUES(gross),
                retail_old = VALUES(retail_old),
                gross_old=VALUES(gross_old),
                discount_retail = VALUES(discount_retail),
               discount_gross=VALUES(discount_gross);
            ")->execute();
        \DataImport\Common\DataImportLog::F()->log("Подготовка постпроцессоров каталога");
        $logtext = \DataImport\Common\DataImportLog::F()->get_text("\n");
        $this->write_log($input, $logtext);
        \DataImport\Common\ImportRedirectException::F($this->import_permanent_params([
                    'step' => "18_5", 'table' => $table,
        ]));
    }

    protected function run_step_18_5(\DataMap\IDataMap $input, \DataMap\UploadedFile $file = null) {
        $table = $input->get_filtered('table', ['Strip', 'Trim', 'NEString', 'DefaultNull']);
        $table ? 0 : \DataImport\Common\DataImportError::R("no temporary table name provided");
        $tn = $table;
        $p = new \stdClass();
        $p->processors = [];
        $tree = \CatalogTree\CatalogTree::F();
        $tree->map(function(\CatalogTree\CatalogTreeItem $node, \CatalogTree\CatalogTree $tree, $o) {
            /* @var $node \CatalogTree\CatalogTreeItem */
            if (is_array($node->import_processor) && count($node->import_processor)) {
                foreach ($node->import_processor as $one_processor) {
                    $o->processors[] = [$node->id, $one_processor];
                }
            }
        }, $p);
        if (count($p->processors)) {
            $t = time();
            $fn = "postprocessor_{$t}";
            $tmp_name = $this->get_temp_dir_name() . $fn . ".map";
            file_put_contents($tmp_name, serialize($p->processors));
            $postprocessor_counter = 0;
            \DataImport\Common\DataImportLog::F()->log("запуск постпроцессоров");
            $logtext = \DataImport\Common\DataImportLog::F()->get_text("\n");
            $this->write_log($input, $logtext);
            \DataImport\Common\ImportRedirectException::F($this->import_permanent_params([
                        'step' => "18_6", 'table' => $table,
                        'postprocessor_c' => $postprocessor_counter, 'postprocessor_file' => $fn
            ]));
        } else {
            \DataImport\Common\DataImportLog::F()->log("Постпроцессоры не определены");
            \DataImport\Common\DataImportLog::F()->log("Очистка кеша");
            $logtext = \DataImport\Common\DataImportLog::F()->get_text("\n");
            $this->write_log($input, $logtext);
            \DataImport\Common\ImportRedirectException::F($this->import_permanent_params([
                        'step' => 19, 'table' => $table,
            ]));
        }
    }

    protected function run_step_18_6(\DataMap\IDataMap $input, \DataMap\UploadedFile $file = null) {
        $table = $input->get_filtered('table', ['Strip', 'Trim', 'NEString', 'DefaultNull']);
        $table ? 0 : \DataImport\Common\DataImportError::R("no temporary table name provided");
        $tn = $table;
        $fn = $input->get_filtered("postprocessor_file", ['Strip', 'Trim', 'NEString']);
        $fn ? 0 : \DataImport\Common\DataImportError::R("no temporary postproc file name provided");
        $fn = str_ireplace(".", "", $fn);
        $path = $this->get_temp_dir_name() . $fn . ".map";
        file_exists($path) && is_readable($path) && is_file($path) ? 0 : \DataImport\Common\DataImportError::R("temporary postprc file not found");
        $counter = $input->get_filtered("postprocessor_c", ['IntMore0', "Default0"]);
        $processors = unserialize(file_get_contents($path));
        if (array_key_exists($counter, $processors)) {
            $node = \CatalogTree\CatalogTree::F()->get_item_by_id($processors[$counter][0]);
            if ($node) {
                $processor = $processors[$counter][1];
                $this->run_post_processor($node, $processor);
            }
            $counter++;
            $logtext = \DataImport\Common\DataImportLog::F()->get_text("\n");
            $this->write_log($input, $logtext);
            \DataImport\Common\ImportRedirectException::F($this->import_permanent_params([
                        'step' => "18_6", 'table' => $table,
                        'postprocessor_c' => $counter, 'postprocessor_file' => $fn
            ]));
        } else {
            \DataImport\Common\DataImportLog::F()->log("Все постпроцессоры завершены");
            \DataImport\Common\DataImportLog::F()->log("Индексация товарных предложений");
            $logtext = \DataImport\Common\DataImportLog::F()->get_text("\n");
            $this->write_log($input, $logtext);
            \DataImport\Common\ImportRedirectException::F($this->import_permanent_params([
                        'step' => "18_7", 'table' => $table,
            ]));
        }
    }

    protected function run_step_18_7(\DataMap\IDataMap $input, \DataMap\UploadedFile $file = null) {
        $table = $input->get_filtered('table', ['Strip', 'Trim', 'NEString', 'DefaultNull']);
        $table ? 0 : \DataImport\Common\DataImportError::R("no temporary table name provided");
        $tn = $table;
        // нужна связка гуид-guidцвета-id размера=аналог/0 или оставить гуидами.
        $Q = "DROP TABLE IF EXISTS `catalog__product_guid_link`;            
            CREATE TABLE `catalog__product_guid_link` (guid_1c VARCHAR(100) NOT NULL,
            color_guid VARCHAR(100) NULL DEFAULT NULL,
            size_guid VARCHAR(100) NULL DEFAULT NULL,
            product_id INT(11) UNSIGNED NOT NULL,
            PRIMARY KEY (guid_1c),
            INDEX(product_id)
            )ENGINE=InnoDB;
            ALTER TABLE catalog__product_guid_link ADD CONSTRAINT `catalog__product__guid__link_2_caalog_product` 
            FOREIGN KEY (`product_id`) REFERENCES `catalog__product`(`id`) ON DELETE CASCADE ON UPDATE CASCADE;            
            ";
        $q2 = "INSERT INTO `catalog__product_guid_link` (guid_1c,color_guid,size_guid,product_id)
               SELECT PG.guid,PG.color,PG.size,P.id 
               FROM catalog__product P JOIN `{$tn}product_guid` PG ON(PG.analog=P.guid)
               LEFT JOIN catalog__size__def CS ON(CS.guid=PG.size)    
               LEFT JOIN catalog__product__color CC ON(CC.exchange_uid=PG.color AND CC.product_id=P.id)
               WHERE (PG.color IS NULL OR PG.color=CC.exchange_uid) AND(PG.size IS NULL OR PG.size=CS.guid);";
               
        \DB\SQLTools\SQLBuilder::F()->push($Q)->execute();    
        \DB\SQLTools\SQLBuilder::F()->push($q2)->execute_transact();                  
        \DataImport\Common\DataImportLog::F()->log("Очистка кеша");
        $logtext = \DataImport\Common\DataImportLog::F()->get_text("\n");
        $this->write_log($input, $logtext);
        \DataImport\Common\ImportRedirectException::F($this->import_permanent_params([
                    'step' => "19", 'table' => $table,
        ]));
    }

    protected function run_post_processor(\CatalogTree\CatalogTreeItem $node, string $processor_name) {
        $processor_info = \DataImport\ImportProcessor\ImportProcessorManager::F()->get_by_name($processor_name);
        if ($processor_info && $node) {/* @var $processor_info \DataImport\ImportProcessor\ImportProcessorInfo */
            $proc = $processor_info->instance(); /* @var $proc \DataImport\ImportProcessor\IImportProcessor */
            try {
                \DataImport\Common\DataImportLog::F()->log($proc->before_run($node));
                \DataImport\Common\DataImportLog::F()->log($proc->run($node));
                \DataImport\Common\DataImportLog::F()->log($proc->after_run($node));
            } catch (\Exception $e) {
                \DataImport\Common\DataImportLog::F()->log("error in postprocessor:{$e->getMessage()}");
            }
        } else if ($node) {
            \DataImport\Common\DataImportLog::F()->log("processor \"{$processor_name}\" skipped - processor not exists");
        } else {
            \DataImport\Common\DataImportLog::F()->log("processor \"{$processor_name}\" skipped - node not exists");
        }
    }

    protected function run_step_19(\DataMap\IDataMap $input, \DataMap\UploadedFile $file = null) {
        $table = $input->get_filtered('table', ['Strip', 'Trim', 'NEString', 'DefaultNull']);
        $table ? 0 : \DataImport\Common\DataImportError::R("no temporary table name provided");
        $tn = $table;
        \CatalogTree\CatalogTree::clear_dependency_beacon();
        \DataModel\Product\Model\ProductModel::RESET_CACHE();
        \DataImport\Common\DataImportLog::F()->log("Импорт завершен");
        $logtext = \DataImport\Common\DataImportLog::F()->get_text("\n");
        $this->write_log($input, $logtext, 2);

        if ($this->local_mode) {
            \DataImport\Common\ImportFinishedException::R("done");
        }
    }

    //</editor-fold>
    //<editor-fold defaultstate="collapsed" desc="categories sql recursive">
    protected function create_categories_sql(array $categories, \DB\SQLTools\SQLBuilder $b, array &$guid_to_var_name) {
        foreach ($categories as $category) {/* @var $category ImportedCategory */
            $b->inc_counter();
            $parent_id = 'NULL';
            if ($category->parent) {
                $parent_id = $guid_to_var_name[$category->parent_key];
            } else if ($this->get_default_category_parent()) {
                $parent_id = $this->get_default_category_parent();
            }
            $var_name = "@a{$b->c}" . md5($category->uid);
            $guid_to_var_name[$category->category_key] = $var_name;
            if ($category->id) {
                $b->push("SET {$var_name} = :P{$b->c}id;");
                $b->push("UPDATE catalog__group SET parent_id={$parent_id},name=:P{$b->c}name,info=:P{$b->c}info
                    WHERE id={$var_name};
                ");
                $b->push_params([
                    ":P{$b->c}id" => $category->id,
                    ":P{$b->c}name" => $category->name,
                    ":P{$b->c}info" => $category->info
                ]);
            } else {
                $b->push("INSERT INTO catalog__group(parent_id,sort_order,name,visible,alias,guid,info)
                       VALUES({$parent_id},0,:P{$b->c}name,{$this->get_default_visible_state()},:P{$b->c}alias,:P{$b->c}guid,:P{$b->c}info);");
                $b->push("SET {$var_name} = LAST_INSERT_ID();");
                $b->push_params([
                    ":P{$b->c}name" => $category->name,
                    ":P{$b->c}guid" => $category->uid,
                    ":P{$b->c}info" => $category->info,
                    ":P{$b->c}alias" => $this->createCategoryAlias($category),
                ]);
            }
            $b->inc_counter();
            if ($category->childs && count($category->childs)) {
                $this->create_categories_sql($category->childs, $b, $guid_to_var_name);
            }
        }
    }

    //</editor-fold>

    public function run(\DataMap\IDataMap $input, string $step, \DataMap\UploadedFile $file = null) {
        if ($this->local_mode) {
            $this->local_input = $input;
        }
        try {
            $fn = "run_step_{$step}";
            if (method_exists($this, $fn)) {
                $this->$fn($input, $file);
            } else {
                \DataImport\Common\DataImportError::RF("no processor defined for step %s", $step);
            }
        } catch (\DataImport\Common\ImportRedirectException $eee) {
            throw $eee;
        } catch (\DataImport\Common\ImportFinishedException $eee) {
            throw $eee;
        } catch (\Exception $eee) {
            try {
                $this->write_log($input, "error:{$eee->getMessage()}", 3);
            } catch (\Exception $xee) {
                
            }
            throw $eee;
        }
        return;
        //<editor-fold defaultstate="collapsed" desc="deprecated part">
        $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
        $wksheet_info = $reader->listWorksheetInfo($this->xls_name);
        $max_letter = $wksheet_info[0]['lastColumnLetter'];
        $max_row_number = intval($wksheet_info[0]['totalRows']);
        $offset = 2;
        $index = 0;
        $total = 0;
        $tn = "a" . md5(__METHOD__);
        $b = \DB\SQLTools\SQLBuilder::F();
        //Код|Артикул|Наименование|Полное наименование|Ед. изм.|Код родителя|Это группа|Цвет|Размер|Цена оптовая|Цена розничная|Состав|Описание
        //|Цена оптовая(старая)|Цена розничная (старая)|Скидка оптовая|Скидка розничная|Код аналога
        $temp = ""; //"TEMPORARY"
        $b->push("DROP {$temp} TABLE IF EXISTS `{$tn}`;
            CREATE {$temp} table `{$tn}` (
                guid VARCHAR(100) NOT NULL,
                article VARCHAR(100) NULL DEFAULT NULL,
                name VARCHAR(255) NOT NULL,
                full_name VARCHAR(255) NULL DEFAULT NULL,
                parent_guid VARCHAR(100) NULL DEFAULT NULL,
                is_group INT(11) UNSIGNED NOT NULL DEFAULT 0,
                color VARCHAR(255) NULL DEFAULT NULL,
                size VARCHAR(255) NULL DEFAULT NULL,
                price_opt DOUBLE NULL DEFAULT NULL,
                price_retail DOUBLE NULL DEFAULT NULL,
                consits MEDIUMTEXT,
                description MEDIUMTEXT,
                price_opt_old DOUBLE NULL DEFAULT NULL,
                price_retail_old DOUBLE NULL DEFAULT NULL,
                discount_opt DOUBLE NULL DEFAULT NULL,
                discount_retail DOUBLE NULL DEFAULT NULL,
                analog VARCHAR(100) NULL DEFAULT NULL,
                PRIMARY KEY(guid)                
                )ENGINE=MyISAM;");
        while (($readed = $this->process_slice($index + $offset, 5000, $max_letter, $b, $tn)) > 0) {
            set_time_limit(40);
            if (!$b->empty) {
                $b->execute();
            }
            $b = \DB\SQLTools\SQLBuilder::F();
            $index += $readed;
            $total += $readed;
            if ($total > $max_row_number) {
                break;
            }
        }
        if (!$b->empty) {
            $b->execute();
        }
        /// теперь делаем выборку по товарам
        // сразу удалим строки в которых - analog=null и группа - 0 и нет элементов ссылающихся на эти строки
        $b = \DB\SQLTools\SQLBuilder::F();
        $b->push("UPDATE `{$tn}` SET parent_guid=NULL WHERE parent_guid=0;
            UPDATE `{$tn}` SET analog=NULL WHERE analog=0;
            -- сразу удалим строки в которых - analog=null и группа - 0 и нет элементов ссылающихся на эти строки
            DELETE A.* FROM `{$tn}` A LEFT JOIN `{$tn}` B ON(B.analog=A.guid)
                WHERE A.analog IS NULL AND A.is_group=0 AND B.guid IS NULL;
            DROP {$temp} TABLE IF EXISTS `{$tn}products`; 
            CREATE TABLE `{$tn}products` AS SELECT * FROM `{$tn}` WHERE guid=analog AND is_group=0; 
            UPDATE `{$tn}products` SET article= UUID() WHERE article IS NULL;            
            UPDATE `{$tn}products` A JOIN `{$tn}products` B ON(A.guid!=B.guid AND A.article=B.article)
                SET A.article=CONCAT(A.article,'-',A.guid);                
                ");
        $b->push("INSERT INTO catalog__product (guid,alias,enabled,article)
            SELECT guid,CONCAT('temp_',guid),{$this->get_default_product_visible_state()},article
                FROM `{$tn}products` 
            ON DUPLICATE KEY UPDATE article=VALUES(article);
            INSERT INTO catalog__product__strings(id,name,description,consists)
            SELECT B.id, A.name,A.description,A.consits  FROM `{$tn}products` A JOIN catalog__product B ON(A.guid=B.guid)
                ON DUPLICATE KEY UPDATE name=VALUES(name),description=VALUES(description),consists=VALUES(consists);                    
            "); //основные товары вставлены. теперь надо создать идентификаторы размеров
        $b->push("DROP {$temp} TABLE IF EXISTS `{$tn}size`;
            CREATE {$temp} TABLE `{$tn}size` (guid VARCHAR(100), value VARCHAR(100),PRIMARY KEY(guid));
            INSERT INTO `{$tn}size` (guid,value)
                SELECT TRIM(size),TRIM(size) FROM `{$tn}`
                    WHERE CHAR_LENGTH(TRIM(size))>0 AND size IS NOT NULL AND size!=0
            ON DUPLICATE KEY UPDATE guid=VALUES(guid),value=VALUES(value);                    
            UPDATE catalog__size__def A JOIN `{$tn}size` B ON(A.guid=B.guid) SET A.size=B.value;
            -- удаляем существующие из времянки    
            DELETE A.* FROM `{$tn}size` A JOIN catalog__size__def B ON(A.guid=B.guid);
            -- вставляем несуществующие
            INSERT INTO catalog__size__def (guid,size) SELECT guid,value FROM `{$tn}size`;                                
        ");
        //теперь надо раскрыть цепочку аналогов - есть изрядные сомнения что она однорядная
        // раскрывать надо выше - в основной таблице. проще говоря надо вывести аналог до товара у которого guid===analog



        var_dump($b->sql);
        $b->execute();
        //    
        die(__FILE__ . __LINE__);


        \CatalogTree\CatalogTree::clear_dependency_beacon();
        //</editor-fold>
    }

    //<editor-fold defaultstate="collapsed" desc="deprecated">
    /**
     * 
     * @param \DataImport\NomenclatureImport\ImportedCategory $categories
     * @param \DB\SQLTools\SQLBuilder $b
     * @param \CatalogTree\CatalogTree $tree
     * @param array $inserts_vars
     * @deprecated since version 0
     */
    protected function createCategoriesQuery(array $categories, \DB\SQLTools\SQLBuilder $b, \CatalogTree\CatalogTree $tree, array &$inserts_vars) {
        /* @var $categories ImportedCategory[] */
        foreach ($categories as $category) {
            $existed = $tree->get_item_by_guid($category->uid);
            if ($existed) {
                $parent_block = "";
                //установить новую родительскую категорию                
                $existed_parent = $category->parent ? $tree->get_item_by_guid($category->parent) : null; /* @var $existed_parent \CatalogTree\CatalogTreeItem */
                if ($existed_parent) {
                    $parent_block = "parent_id=:P{$b->c}parent_id, ";
                    $b->push_param(":P{$b->c}parent_id", $existed_parent->id);
                } else if ($category->parent && array_key_exists($category->parent, $inserts_vars)) {
                    $parent_block = "parent_id={$inserts_vars[$category->parent]}, ";
                } else {
                    $parent_block = "parent_id=:P{$b->c}parent_id, ";
                    $b->push_param(":P{$b->c}parent_id", $this->get_default_category_parent());
                }
                $b->push("UPDATE catalog__group SET {$parent_block} name=:P{$b->c}name WHERE id=:P{$b->c}id;");
                $b->push_params([
                    ":P{$b->c}name" => $category->name,
                    ":P{$b->c}id" => $existed->id,
                ]);
            } else {
                $parent_block = "NULL";
                $existed_parent = $category->parent ? $tree->get_item_by_guid($category->parent) : null; /* @var $existed_parent \CatalogTree\CatalogTreeItem */
                if ($existed_parent) {
                    $parent_block = ":P{$b->c}Aparent_id";
                    $b->push_param(":P{$b->c}Aparent_id", $existed_parent->id);
                } else if ($category->parent && array_key_exists($category->parent, $inserts_vars)) {
                    $parent_block = "{$inserts_vars[$category->parent]}";
                } else {
                    $parent_block = ":P{$b->c}Bparent_id";
                    $b->push_param(":P{$b->c}Bparent_id", $this->get_default_category_parent());
                }
                $b->push("INSERT INTO catalog__group(parent_id,name,visible,alias,guid,info)
                    VALUES({$parent_block},:P{$b->c}name,{$this->get_default_visible_state()},:P{$b->c}alias,:P{$b->c}guid,:P{$b->c}info);
                    ");
                $var_name = $this->generate_variable_name();
                $inserts_vars[$category->uid] = $var_name;
                $b->push("SET {$var_name} = LAST_INSERT_ID();");
                $b->push_params([
                    ":P{$b->c}name" => $category->name,
                    ":P{$b->c}alias" => $this->createCategoryAlias($category),
                    ":P{$b->c}guid" => $category->uid,
                    ":P{$b->c}info" => $category->info,
                ]);
            }
            $b->inc_counter();
            $this->createCategoriesQuery($category->childs, $b, $tree, $inserts_vars);
        }
    }

    protected function enflate_categories(array $categories, array &$out) {
        foreach ($categories as $category) {/* @var $category ImportedCategory */
            $out[] = $category;
            if ($category->length) {
                $this->enflate_categories($category->childs, $out);
            }
        }
    }

    protected function normalize_categories(array $categories, array $products) {
        $flat_categories = $this->filter_product_categories($categories, $products);
        unset($products);
        unset($categories);
        /* @var $flat_categories ImportedCategory[] */
        /* @var $index ImportedCategory[] */
        /* @var $root ImportedCategory[] */
        $index = [];
        foreach ($flat_categories as $category) {
            $index[$category->category_key] = $category;
        }
        unset($flat_categories);
        $root = [];
        foreach ($index as $key => $category) {
            if ($category->parent) {
                if (array_key_exists($category->parent_key, $index)) {
                    $index[$category->parent_key]->add_child($category);
                }
            } else {
                $root[] = $category;
            }
        }
        return $root;
    }

    protected function filter_product_categories(array $cat, array $prod): Array {
        $result = [];
        foreach ($cat as $category) {/* @var $category ImportedCategory */
            if (array_key_exists($category->category_key, $prod) && is_array($prod[$category->category_key]) && count($prod[$category->category_key])) {// если в категории есть товары
                $skip = false;
                foreach ($prod[$category->category_key] as $article) {
                    if (preg_match("/(:?^|\s)" . preg_quote($article, "/") . "(?:$|\D)/i", $category->name)) {
                        $skip = true;
                        break;
                    }
                }
                if ($skip) {
                    continue;
                }
            }
            $result[] = $category;
        }
        return $result;
    }

    //</editor-fold>
}
