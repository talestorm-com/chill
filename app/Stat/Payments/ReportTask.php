<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Stat\Payments;

/**
 * Description of ReportTask
 *
 * @author eve
 * @property string $grouping
 * @property string $mailto
 * @property \DateTime $start
 * @property \DateTime $end
 */
class ReportTask extends \AsyncTask\AsyncTaskAbstract {

    /** @var string */
    protected $grouping;

    /** @var string */
    protected $mailto;

    /** @var \DateTime */
    protected $start;

    /** @var \DateTime */
    protected $end;

    /** @return string */
    protected function __get__grouping() {
        return $this->grouping;
    }

    /** @return string */
    protected function __get__mailto() {
        return $this->mailto;
    }

    /** @return \DateTime */
    protected function __get__start() {
        return $this->start;
    }

    /** @return \DateTime */
    protected function __get__end() {
        return $this->end;
    }

    /**
     * date_start' => ['DateMatch'],
      'date_end' => ['DateMatch'],
      'grouping' => ['Strip', 'Trim', 'NEString', ],
      'mailto' => ['Strip', 'Trim', 'NEString', 'EmailMatch', ],
     */
    protected function exec() {
        $this->start = $this->params->get_filtered('date_start', ['DateMatch', 'DefaultNull']);
        $this->end = $this->params->get_filtered('date_end', ['DateMatch', 'DefaultNull']);
        $this->grouping = $this->params->get_filtered('grouping', ['Strip', 'Trim', 'NEString', 'DefaultNull']);
        $this->mailto = $this->params->get_filtered('mailto', ['Strip', 'Trim', 'NEString', 'EmailMatch', 'DefaultNull']);
        if ($this->start && $this->end && $this->grouping && $this->mailto) {
            $this->exec_int();
        }
    }

    protected function get_log_file_name(): string {
        return 'report_mailer';
    }

    protected function exec_int() {
        
        $report = StatGenerator::F($this->start, $this->end, $this->grouping)->run();
        $report->run();
        $data = ['task' => $this, 'subject' => "Отчет {$this->start->format('d.m.Y')}-{$this->end->format('d.m.Y')}"];
        $mailer = \SWIFT\SWIFTMAILER::F();
        $report_file_attachment = \Swift_Attachment::newInstance(file_get_contents($report->temp_name), "{$data['subject']}.xlsx");
        $report_file_attachment->setDisposition('attachment'); 
        $report_file_attachment->generateId();
        $mailer->send_email_with_template('report/payment', $this->mailto, $data['subject'], $data, [$report_file_attachment], $this);
        unlink($report->temp_name);
        
    }

}
