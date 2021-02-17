<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace controllers\admin;

/**
 * Description of PaymentReportController
 *
 * @author eve
 */
class PaymentReportController extends AbstractAdminController {

    public function get_desktop_component_id() {
        return "desktop.payment_report";
    }

    public function actionIndex() {
        $this->render_view('admin', '../common_index');
    }

    protected function API_pending_report() {
        $data_raw = \Filters\FilterManager::F()->apply_filter_datamap(\DataMap\InputDataMap::F(), [
            'date_start' => ['DateMatch'],
            'date_end' => ['DateMatch'],
            'grouping' => ['Strip', 'Trim', 'NEString',],
            'mailto' => ['Strip', 'Trim', 'NEString', 'EmailMatch',],
        ]);
        \Filters\FilterManager::F()->raise_array_error($data_raw);
        \Stat\Payments\ReportTask::mk_params()->add_array($data_raw)->run();
    }

    public function actionDownload() {
        $start = \DataMap\InputDataMap::F()->get_filtered('date_start', ['DateMatch', 'DefaultNull']);
        $end = \DataMap\InputDataMap::F()->get_filtered('date_end', ['DateMatch', 'DefaultNull']);
        $mode = \DataMap\InputDataMap::F()->get_filtered('grouping', ['Strip', 'Trim', 'NEString', 'DefaultNull']);
        $start && $end && $mode ? 0 : die('invalid request');
        $gen = \Stat\Payments\StatGenerator::F($start, $end, $mode)->run();
        $gen->run();

        if (!headers_sent()) {
            header("Content-Type: application/octet-stream", true);
            header("Content-Disposition: attachment;filename=\"report.xlsx\"");
        }
        readfile($gen->temp_name);
        unlink($gen->temp_name);
    }

}
