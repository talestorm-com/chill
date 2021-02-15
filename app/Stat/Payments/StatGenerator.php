<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Stat\Payments;

/**
 * Description of StatGenerator
 *
 * @author eve
 * @property \DateTime $date_start
 * @property \DateTime $date_end
 * @property string $group_mode
 * 
 */
class StatGenerator {

    use \common_accessors\TCommonAccess;

    const GROUP_MODE_CONTENT = 'content';

    /** @var \DateTime */
    protected $date_start;

    /** @var \DateTime */
    protected $date_end;

    /** @var string */
    protected $group_mode;

    /** @return \DateTime */
    protected function __get__date_start() {
        return $this->date_start;
    }

    /** @return \DateTime */
    protected function __get__date_end() {
        return $this->date_end;
    }

    /** @return string */
    protected function __get__group_mode() {
        return $this->group_mode;
    }

    public function __construct(\DateTime $date_start, \DateTime $date_end, string $group_mode = 'date') {
        $this->date_start = new \DateTime();
        $this->date_start->setTimestamp($date_start->getTimestamp());
        $this->date_start->setTime(0, 0, 0, 0);
        $this->date_end = new \DateTime();
        $this->date_end->setTimestamp($date_end->getTimestamp());
        $this->date_end->setTime(0, 0, 0);
        $this->date_end->add(new \DateInterval('P1D'));
        $this->group_mode = $group_mode;
    }

    /**
     * 
     * @return  reports\payment_report_by_content|reports\payment_report_by_date|null
     */
    public function run() {
        //$data = $this->get_report_data();
        if ($this->group_mode === static::GROUP_MODE_CONTENT) {
            return reports\payment_report_by_content::F($this->date_start, $this->date_end);
        } else {
            return reports\payment_report_by_date::F($this->date_start, $this->date_end);
        }
        return null;
    }

    protected function init_xlsx() {
        \PhpOffice\PhpSpreadsheet\Settings::setLocale('en_US');
        $xlsx = \PhpOffice\PhpSpreadsheet\IOFactory::load(__DIR__ . DIRECTORY_SEPARATOR . "reports" . DIRECTORY_SEPARATOR . "payment_report_1.xlsx");
        $this->temp_name = tempnam(sys_get_temp_dir(), md5(get_called_class()));
        $this->sheet = $this->xlsx->getSheet(0);
        $this->presets_sheet = $this->xlsx->getSheetByName('presets');
        $this->sheet->setTitle("прайс-лист");
        $this->date_format = trim($this->presets_sheet->getCell("preset_date_format")->getValue());
        $this->date_format && mb_strlen($this->date_format, 'UTF-8') ? false : $this->date_format = 'd.m.Y';
        $d = new \DateTime();
        $this->sheet->getCell("current_date")->setValue($d->format($this->date_format));
    }

    protected function get_report_data() {
        if ($this->group_mode === static::GROUP_MODE_CONTENT) {
            $query = "
                SELECT A.content_id,A.d `date`,A.amount,SOAP.common_name soap_name,SEASON.common_name season_name,SERIE.common_name serie_name,SERIE.id serie_id,
                SEASON.id season_id,SOAP.id soap_id
                FROM
            (    
            SELECT DATE(ts) d,CAST(param2 as UNSIGNED) content_id,SUM(amount) amount
            FROM user__history WHERE action='payment_local' AND param1 = 'content'
            AND ts >=:Pds AND ts<:Pde
            GROUP BY CAST(param2 as UNSIGNED),DATE(ts)
            ) A LEFT JOIN media__content__season__series SERIE ON(SERIE.id=A.content_id)
            LEFT JOIN media__content__season__season SEASON ON(SEASON.id=SERIE.seasonseason_id)
            LEFT JOIN media__content__season SOAP ON(SOAP.id=SEASON.season_id)
            ORDER BY SOAP.id,SEASON.id,SERIE.id,A.d;
            ";
        } else {
            $query = "
                SELECT A.content_id,A.d `date`,A.amount,SOAP.common_name soap_name,SEASON.common_name season_name,SERIE.common_name serie_name,SERIE.id serie_id,
                SEASON.id season_id,SOAP.id soap_id
                FROM (
            SELECT DATE(ts) d,CAST(param2 as UNSIGNED) content_id,SUM(amount) amount
            FROM user__history WHERE action='payment_local' AND param1 = 'content'
            AND ts >=:Pds AND ts<:Pde
            GROUP BY DATE(ts),CAST(param2 as UNSIGNED)  
            ) A
            LEFT JOIN media__content__season__series SERIE ON(SERIE.id=A.content_id)
            LEFT JOIN media__content__season__season SEASON ON(SEASON.id=SERIE.seasonseason_id)
            LEFT JOIN media__content__season SOAP ON(SOAP.id=SEASON.season_id)
            ORDER BY A.d,SOAP.id,SEASON.id,SERIE.id;
            ";
        }
        $rows = \DB\DB::F()->queryAll($query, [":Pds" => $this->date_start->format('Y-m-d'), ':Pde' => $this->date_end->format('Y-m-d')]);
        return $rows;
    }

    /**
     * 
     * @param \DateTime $start
     * @param \DateTime $end
     * @param string $group_mode 
     * @return \static
     */
    public static function F(\DateTime $start, \DateTime $end, string $group_mode = 'date') {
        return new static($start, $end, $group_mode);
    }

}
