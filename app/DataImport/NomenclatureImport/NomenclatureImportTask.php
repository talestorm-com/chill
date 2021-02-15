<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace DataImport\NomenclatureImport;

class NomenclatureImportTask extends \AsyncTask\AsyncTaskAbstract {
    
    
    protected function get_log_file_name() {
        return "nomenclature_import";
    }

    protected function exec() {
        $file_name = $this->params->get_filtered("file", ["Trim", "NEString", "DefaultNull"]);
        if ($file_name && file_exists($file_name) && is_file($file_name)) {
            $input = \DataMap\CommonDataMap::F();
            $input->set("local_file", $file_name);
            $input->set("disable_new_categories", 1);
            $input->set("disable_new_products", 1);
            $input->set("default_parent", "3296"); ///fixed category?
            $step_count_wd = 0;
            $mutex = \Mutex\SimpleNamedMutex::F("import_module");
            $mutex->get();
            //if (!$mutex->get_if()) {
            //    $this->log("mutex is buisy", "error");
            //    die();
            //}
            $this->log("import started");
            while (true && $step_count_wd < 1000) {
                $step_count_wd++;
                try {
                    $import = NomenclatureImport::F(TRUE);
                    $import->set_local_mode();
                    $step = (string) $input->get_filtered("step", ['Strip', 'Trim', 'NEString', 'Default0']);
                    $this->log("import: executing step {$step}");
                    $import->run($input, $step);
                    $import->release();
                    unset($import);
                    \DataImport\Common\DataImportLog::F()->clear();
                    \DataImport\Common\DataImportLog::destroy_instance();
                } catch (\DataImport\Common\ImportRedirectException $ee) {
                    $na = $ee->get_url_params();
                    $input->rebind($na);
                    continue;
                } catch (\DataImport\Common\ImportFinishedException $ee) {
                    $this->log("import done", "success");
                    $mutex->release();
                    die();
                } catch (\Exception $e) {
                    $this->log(sprintf("%s at %s in %s", $e->getMessage(), $e->getLine(), $e->getFile()), "error");
                    $mutex->release();
                    die();
                }
            }
            $this->log("too many steps", "error");
        } else {
            $this->log("no file available", "error");
        }
        $mutex->release();
    }

}
