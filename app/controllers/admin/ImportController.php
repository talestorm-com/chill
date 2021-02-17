<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace controllers\admin;

class ImportController extends \controllers\admin\AbstractAdminController {

    protected function actionIndex() {
        $this->render_view('admin', 'index');
    }

    protected function actionLog() {
        $this->render_view('admin', 'log');
    }

    protected function actionAsyncForm() {
        $this->render_view('admin', 'async');
    }
    protected function actionAsyncFormStorage() {
        $this->render_view('admin', 'async_s');
    }

    protected function API_list() {
        $condition = \ADVTable\Filter\FixedTokenFilter::F(null, [
                    'id' => 'Int:id',
                    'updated' => 'Date:updated',
        ]);
        $direction = \ADVTable\Sort\FixedTokenSort::F(null, [
                    'id' => 'id',
                    'updated' => 'updated|id',
        ]);
        $direction->tokens_separator = "|";
        $limit = \ADVTable\Limit\FixedTokenLimit::F();
        $query = "SELECT SQL_CALC_FOUND_ROWS id,updated,state FROM data_import_log %s %s %s %s;";
        $p = [];
        $c = 0;
        $where = $condition->buildSQL($p, $c);
        $q = sprintf($query, $condition->whereWord, $where, $direction->SQL, $limit->MySqlLimit);
        $rows = \DB\DB::F()->queryAll($q, $p);
        $total = \DB\DB::F()->get_found_rows();
        if (!count($rows) && $total && $limit->page) {
            $limit->setPage(0);
            $q = sprintf($query, $condition->whereWord, $where, $direction->SQL, $limit->MySqlLimit);
            $rows = \DB\DB::F()->queryAll($q, $p);
            $total = \DB\DB::F()->get_found_rows();
        }
        $this->out->add("items", $rows)->add("total", $total)->add("page", $limit->page)->add("perpage", $limit->perpage);
    }

    protected function API_remove() {
        $id = $this->GP->get_filtered('id_to_remove', ['IntMore0', 'DefaultNull']);
        if ($id) {
            $builder = \DB\SQLTools\SQLBuilder::F();
            $builder->push("DELETE FROM data_import_log WHERE id=:P;");
            $builder->push_param(":P", $id);
            $builder->execute_transact();
        }
        return $this->API_list();
    }

    protected function API_clear_old() {
        $now = new \DateTime();
        $ago = $now->sub(new \DateInterval("P31D"));
        $builder = \DB\SQLTools\SQLBuilder::F();
        $builder->push("DELETE FROM data_import_log WHERE updated<:P;");
        $builder->push_param(":P", $ago->format('Y-m-d'));
        $builder->execute_transact();
        return $this->API_list();
    }

    protected function API_get_log() {
        $id = $this->GP->get_filtered('id', ['IntMore0', 'DefaultNull']);
        $id ? 0 : \Errors\common_error::R("invalid request");
        $this->out->add("log", \DB\DB::F()->queryScalar("SELECT `log` FROM data_import_log WHERE id=:P", [":P" => $id]));
    }

    protected function actioncategories() {
        try {
            $callback = $this->GP->get_filtered('callback', ['Strip', 'Trim', 'NEString', 'DefaultNull']);
            $callback ? FALSE : \Errors\common_error::R("invalid request");
            $files = \DataMap\FileMap::F();
            $files->count === 1 ? false : \Errors\common_error::R("this method requires exactly 1 file");
            $file = $files->get_by_index();
            /* @var $file \DataMap\UploadedFile */
            echo __FILE__ . __LINE__;
            \DataImport\CategoryImport\CategoryImport::F($file->tmp_name)->run();
        } catch (\Exception $e) {
            var_dump($e);
            die();
        }
    }

    protected function actionNomenclature() {
        try {
            $callback = $this->GP->get_filtered('callback', ['Strip', 'Trim', 'NEString', 'DefaultNull']);
            $callback ? FALSE : \Errors\common_error::R("invalid request");
            $files = \DataMap\FileMap::F();
            $files->count === 1 ? false : \Errors\common_error::R("this method requires exactly 1 file");
            $file = $files->get_by_index();
            /* @var $file \DataMap\UploadedFile */
            echo __FILE__ . __LINE__;
            \DataImport\NomenclatureImport\NomenclatureImport::F($file->tmp_name)->run();
        } catch (\Exception $e) {
            var_dump($e);
            die();
        }
    }

    protected function API_nomenclature() {
        try {
            // разделим импорт на пошаговые элементы
            $this->out->add('data_import_log', \DataImport\Common\DataImportLog::F());
            $step = $this->GP->get_filtered('step', ['Strip', 'Trim', 'NEString', 'DefaultNull']);
            $file = null; /* @var $file \DataMap\UploadedFile */
            $file_name = $this->GP->get_filtered('name', ['Strip', 'Trim', 'NEString', 'DefaultNull']);
            if ($step === null) {
                $files = \DataMap\FileMap::F();
                $files->count === 1 ? false : \Errors\common_error::R("this method requires exactly 1 file");
                $file = $files->get_by_index();
                $step = "0";
            }
            \DataImport\NomenclatureImport\NomenclatureImport::F()->run($this->GP, $step, $file, $file_name);
        } catch (\DataImport\Common\ImportRedirectException $e) {
            $this->out->add('status', 'redirect')
                    ->add('action', 'redirect')
                    ->add('redirect', "/admin/Import/API?action=nomenclature")
                    ->add("redirect_params", $e->get_url_params());
        }
    }

    protected function API_storage() {
        try {
            // разделим импорт на пошаговые элементы
            $this->out->add('data_import_log', \DataImport\Common\DataImportLog::F());
            $step = $this->GP->get_filtered('step', ['Strip', 'Trim', 'NEString', 'DefaultNull']);
            $file = null; /* @var $file \DataMap\UploadedFile */
            $file_name = $this->GP->get_filtered('name', ['Strip', 'Trim', 'NEString', 'DefaultNull']);
            if ($step === null) {
                $files = \DataMap\FileMap::F();
                $files->count === 1 ? false : \Errors\common_error::R("this method requires exactly 1 file");
                $file = $files->get_by_index();
                $step = "0";
            }
            \DataImport\StorageImport\StorageImport::F()->run($this->GP, $step, $file, $file_name);
            //\DataImport\NomenclatureImport\NomenclatureImport::F()->run($this->GP, $step, $file, $file_name);
        } catch (\DataImport\Common\ImportRedirectException $e) {
            $this->out->add('status', 'redirect')
                    ->add('action', 'redirect')
                    ->add('redirect', "/admin/Import/API?action=nomenclature")
                    ->add("redirect_params", $e->get_url_params());
        }
    }

    protected function API_backup_test() {
        die('test');
        \Backup\AbstractBackupTask::mk_params()->add("config", "db")->run();
    }

}
