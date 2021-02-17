<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace controllers\Run;

class ImportController extends \controllers\abstract_controller {

    //4331beac3e0a17f5e7f0e467978cc13d33dc973d32f6d176bbb2502c50c705c60f3f586e8ec79b8149fb3aabd14a8bf16d56148f24f871a8da19ff7d2fea67c60251e7956d5de33d82b7b964efa7f4e122afaaf075f162d1e6b4e8c840903b555bb21c403fc7b8d5618253882cb7dbd52cba61d9bf047a4841f1a3ec685ecaa5
    //1c_syncronization
    protected function check_access() {
        $r = false;
        $user = $this->GP->get_filtered("user", ['Strip', 'Trim', 'NEString', 'DefaultNull']);
        $password = $this->GP->get_filtered("password", ["Strip", "Trim", "NEString", "DefaultNull"]);
        if ($user && $password) {
            $cs = __METHOD__;
            $pass_to_encrypt = "{$user}{$user}{$user}{$cs}{$user}{$cs}";
            $rpass = $sign = \OpenSSL\OpenSSL::F('passwords_1c', 1024)->sign($pass_to_encrypt);
            if ($rpass === $password) {
                $r = true;
            } else {
                //echo "invalid password:{$rpass}";
            }
        }
        $result = $r && parent::check_access();
        if (!$result) {
            die("access denied");
        }
        return $result;
    }

    public function actionImportNomenclature() {
        try {
            $files = \DataMap\FileMap::F();
            count($files) === 1 ? 0 : \Errors\common_error::R("method requires one uploaded file");
            $file = $files->get_by_index();
            if ($file && $file->valid) {
                $import = \DataImport\NomenclatureImport\NomenclatureImport::F(true);
                $temp_path = $import->get_temp_dir_name() . md5(__METHOD__) . ".xlsx";
                $file->move($temp_path);
                \DataImport\NomenclatureImport\NomenclatureImportTask::mk_params()->add('file', $temp_path)->run();
                die("OK. process started.");
            }
        } catch (\Exception $e) {
            if ($this->GP->get_filtered("debug", ['Boolean', 'DefaultFalse'])) {
                echo $e->getTraceAsString();
            }
            die("ERROR. " . $e->getMessage());
        }
    }

    public function actionImportStorage() {
        try {
            $files = \DataMap\FileMap::F();
            count($files) === 1 ? 0 : \Errors\common_error::R("method requires one uploaded file");
            $file = $files->get_by_index();
            if ($file && $file->valid) {
                $import = \DataImport\StorageImport\StorageImport::F(true);
                $temp_path = $import->get_temp_dir_name() . md5(__METHOD__) . ".xlsx";
                $file->move($temp_path);
                \DataImport\StorageImport\StorageImportTask::mk_params()->add('file', $temp_path)->run();
                die("OK. process started.");
            }
        } catch (\Exception $e) {
            if ($this->GP->get_filtered("debug", ['Boolean', 'DefaultFalse'])) {
                echo $e->getTraceAsString();
            }
            die("ERROR. " . $e->getMessage());
        }
    }

}
