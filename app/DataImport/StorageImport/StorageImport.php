<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace DataImport\StorageImport;

class StorageImport {

    /** @var bool */
    protected $local_mode = false;

    /** @var \DataMap\IDataMap */
    protected $local_input;

    /** @var \Mutex\IMutex */
    protected $mutex = null;

    //<editor-fold defaultstate="collapsed" desc="local mode">
    public function set_local_mode() {
        $this->local_mode = true;
    }

    public function reset_local_mode() {
        $this->local_mode = false;
    }

    //</editor-fold>
    //<editor-fold defaultstate="collapsed" desc="constructors,factories and destructors">
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

    public static function F(bool $skip_mutex = false): StorageImport {
        return new static($skip_mutex);
    }

    //</editor-fold>
    //<editor-fold defaultstate="collapsed" desc="logger">
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
    //<editor-fold defaultstate="collapsed" desc="utilitary">
    //<editor-fold defaultstate="collapsed" desc="filename">
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

    //</editor-fold>
    //<editor-fold defaultstate="collapsed" desc="segment processor">
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
        //0  Код склада
        //1  название склада
        //2  код товара
        //3   наименование
        //4  остаток       
        $max = $ws->getHighestDataRow();
        $max_letter = $ws->getHighestDataColumn();
        $data_raw = $ws->rangeToArray("A{$offset}:{$max_letter}{$max}", NULL, TRUE, FALSE);
        $cd = \DataMap\CommonDataMapIndex::F();  /* @var $cd \DataMap\CommonDataMapIndex  */
        $map = \DataMap\CommonDataMap::F();
        $cd->set_keys(['storage_uid', 'storage_name', 'product_uid', 'product_name', 'qty',]);
        $lines = 0;
        $ic = 0;
        $b->inc_counter();
        $params = [];
        $inserts = [];
        $inserts2 = [];
        $params2 = [];
        foreach ($data_raw as $raw_row) {
            $lines++;
            $raw_row_filt = \Filters\FilterManager::F()->apply_filter_datamap($cd->rebind($raw_row), [
                'storage_uid' => ['Strip', 'Trim', 'NEString', 'DefaultNull'],
                'storage_name' => ['Strip', 'Trim', 'NEString', 'DefaultNull'],
                'product_uid' => ['Strip', 'Trim', 'NEString', 'DefaultNull'],
                'product_name' => ['Strip', 'Trim', 'NEString', 'DefaultNull'],
                'qty' => ['IntMore0', 'Default0'],
            ]);

            $row = $map->rebind($raw_row_filt);
            if ($row->get('storage_uid') && $row->get('product_uid') && $row->get("qty")) {
                $inserts[] = "(:P{$b->c}I{$ic}storage_uid,:P{$b->c}I{$ic}product_uid,:P{$b->c}I{$ic}qty)";
                $params[":P{$b->c}I{$ic}storage_uid"] = $row->get("storage_uid");
                $params[":P{$b->c}I{$ic}product_uid"] = $row->get("product_uid");
                $params[":P{$b->c}I{$ic}qty"] = $row->get("qty");
                $ic++;
            }
            if ($row->get('storage_uid') && $row->get("storage_name")) {
                $inserts2[] = "(:P{$b->c}I{$ic}storage_uid,:P{$b->c}I{$ic}storage_name)";
                $params2[":P{$b->c}I{$ic}storage_uid"] = $row->get("storage_uid");
                $params2[":P{$b->c}I{$ic}storage_name"] = $row->get("storage_name");
                $ic++;
            }
        }
        if (count($inserts)) {
            $b->push_params($params);
            $b->push(sprintf("INSERT INTO `{$var}` (
                storage_guid,product_guid,qty
                ) VALUES %s ON DUPLICATE KEY UPDATE qty=qty+VALUES(qty);", implode(",", $inserts)));
        }
        if (count($inserts2)) {
            $b->push_params($params2);
            $b->push(sprintf("INSERT INTO `{$var}storage` (guid,name) VALUES %s ON DUPLICATE KEY UPDATE name=VALUES(name); ", implode(",", $inserts2)));
        }

        $book->disconnectWorksheets();
        unset($sheets);
        unset($ws);
        unset($book);
        return $lines;
    }

    //</editor-fold>
    //<editor-fold defaultstate="collapsed" desc="work">
    //<editor-fold defaultstate="collapsed" desc="run">    
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
    }

    //</editor-fold>
    //<editor-fold defaultstate="collapsed" desc="steps">
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
            }else{
                \Backup\BackupError::R("input file not exists or not available");
            }
        }
        \DataImport\Common\DataImportLog::F()->log("Импорт остатков.Загрузка файла завершена");
        $b = \DB\SQLTools\SQLBuilder::F();
        $TT = "@var" . md5(__METHOD__);
        $b->push("INSERT INTO data_import_log (updated,state,`log`) VALUES(:PN,0,:PT); SET {$TT}=LAST_INSERT_ID();");
        $b->push_params([":PN" => (new \DateTime())->format("Y-m-d"),
            ":PT" => "Импорт остатков. Загрузка файла импорта завершена",
        ]);
        $log_id = $b->execute_transact($TT);
        \DataImport\Common\ImportRedirectException::F($this->import_permanent_params([
                    'step' => 1,
                    'file_name' => base64_encode($file_name),
                    'log_id' => $log_id,
        ]));
    }

    //</editor-fold>
    //<editor-fold defaultstate="collapsed" desc="step1">
    protected function run_step_1(\DataMap\IDataMap $input, \DataMap\UploadedFile $file = null) {
        $path = $this->get_filename_from_input($input);
        \DataImport\Common\DataImportLog::F()->log("создание временных таблиц");
        $b = \DB\SQLTools\SQLBuilder::F();
        //uid склада, склад, код номенклатуры, название, остаок        
        $tn = "data_import_" . md5(__METHOD__);
        $b->push("DROP  TABLE IF EXISTS `{$tn}`;
            DROP  TABLE IF EXISTS `{$tn}storage`;
            CREATE TABLE `{$tn}storage` (guid VARCHAR(100) NOT NULL,name VARCHAR(100) NOT NULL,PRIMARY KEY(guid))ENGINE=MyISAM;                
            CREATE  table `{$tn}` (
                storage_guid VARCHAR(100) NOT NULL,
                product_guid VARCHAR(100) NOT NULL,
                qty INT(11) UNSIGNED DEFAULT 0,
                PRIMARY KEY(storage_guid,product_guid))ENGINE=MyISAM;");
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
    //<editor-fold defaultstate="collapsed" desc="step2">
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
    //<editor-fold defaultstate="collapsed" desc="step3">
    public function run_step_3(\DataMap\IDataMap $input, \DataMap\UploadedFile $file = null) {
        $table = $input->get_filtered('table', ['Strip', 'Trim', 'NEString', 'DefaultNull']);
        $table ? 0 : \DataImport\Common\DataImportError::R("no temporary table name provided");
        $tn = $table;
        \DataImport\Common\DataImportLog::F()->log("Создание и обновление складов");
        $logtext = \DataImport\Common\DataImportLog::F()->get_text("\n");
        $this->write_log($input, $logtext);
        $query = "INSERT INTO `storage` (guid,name,display_name,visible)
            SELECT guid,name,name,0 FROM `{$tn}storage`
                ON DUPLICATe kEY UPDATE guid=VALUES(guid);            
            ";
        \DB\SQLTools\SQLBuilder::F()->push($query)->execute_transact();
        \DataImport\Common\ImportRedirectException::F($this->import_permanent_params([
                    'step' => 4, 'table' => $table,
        ]));
    }

    //</editor-fold>
    //<editor-fold defaultstate="collapsed" desc="step4">
    public function run_step_4(\DataMap\IDataMap $input, \DataMap\UploadedFile $file = null) {
        $table = $input->get_filtered('table', ['Strip', 'Trim', 'NEString', 'DefaultNull']);
        $table ? 0 : \DataImport\Common\DataImportError::R("no temporary table name provided");
        $tn = $table;
        \DataImport\Common\DataImportLog::F()->log("Импорт остатков в промежуточную таблицу");
        $logtext = \DataImport\Common\DataImportLog::F()->get_text("\n");
        $this->write_log($input, $logtext);
        $query = "DROP TABLE IF EXISTS `{$tn}v`;
            CREATE TABLE `{$tn}v` (
                product_id INT(11) UNSIGNED NOT NULL,
                hash VARCHAR(255) NOT NULL,                
                storage_id INT(11) UNSIGNED NOT NULL,
                qty INT(11) UNSIGNED NOT NULL,
                color_guid VARCHAR(100) NULL,
                size_id INT(11) UNSIGNED NULL,
                PRIMARY KEY (product_id,hash,storage_id))ENGINE=MyISAM;
            INSERT INTO `{$tn}v` (product_id,hash,storage_id,qty,color_guid,size_id)
                SELECT A.product_id, 
                CONCAT ('P',A.product_id,'C',COALESCE(CV.guid,'N'),'S',COALESCE(SV.id,'N')),
                ST.id,
                S.qty,
                COALESCE(CV.guid,NULL),
                COALESCE(SV.id,NULL)
                FROM catalog__product_guid_link A JOIN `{$tn}` S ON(S.product_guid=A.guid_1c)
                    LEFT JOIN catalog__size__def SV ON(SV.guid=A.size_guid)
                    LEFT JOIN catalog__product__color CV ON(CV.exchange_uid=A.color_guid AND CV.product_id=A.product_id)
                    JOIN storage ST ON(ST.guid=S.storage_guid)
            ON DUPLICATE KEY UPDATE `{$tn}v`.qty=`{$tn}v`.qty+VALUES(qty)    ;
            
        ";
        \DB\SQLTools\SQLBuilder::F()->push($query)->execute();
        \DataImport\Common\ImportRedirectException::F($this->import_permanent_params([
                    'step' => 5, 'table' => $table,
        ]));
    }

    //</editor-fold>
    //<editor-fold defaultstate="collapsed" desc="step5">
    //storage__contents
    public function run_step_5(\DataMap\IDataMap $input, \DataMap\UploadedFile $file = null) {
        $table = $input->get_filtered('table', ['Strip', 'Trim', 'NEString', 'DefaultNull']);
        $table ? 0 : \DataImport\Common\DataImportError::R("no temporary table name provided");
        $tn = $table;
        \DataImport\Common\DataImportLog::F()->log("Импорт остатков в основную таблицу");
        $logtext = \DataImport\Common\DataImportLog::F()->get_text("\n");
        $this->write_log($input, $logtext);
        $query = "DELETE FROM storage__contents;
            INSERT INTO storage__contents (hash,storage_id,product_id,color,size,qty)
            SELECT hash,storage_id,product_id,color_guid,size_id,qty
            FROM`{$tn}v` WHERE qty>0;                        
        ";
        \DB\SQLTools\SQLBuilder::F()->push($query)->execute_transact();
        \DataImport\Common\DataImportLog::F()->log("Импорт завершен");
        $this->write_log($input, $logtext, 2);
        if ($this->local_mode) {
            \DataImport\Common\ImportFinishedException::R("done");
        }
    }

    //</editor-fold>
    //</editor-fold>
    //</editor-fold>
}
