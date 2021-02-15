<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Stat\Payments\reports;

/**
 * Description of payment_report_by_content
 *
 * @author eve
 * @property \PhpOffice\PhpSpreadsheet\Spreadsheet $xlsx
 * @property string $temp_name
 * @property \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet $sheet
 * @property \DateTime $date_start
 * @property \DateTime $date_end
 */
class payment_report_by_content {

    use \common_accessors\TCommonAccess;

    /** @var \PhpOffice\PhpSpreadsheet\Spreadsheet */
    protected $xlsx;

    /** @var string */
    protected $temp_name;

    /** @var \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet */
    protected $sheet;

    /** @var \DateTime */
    protected $date_start;

    /** @var \DateTime */
    protected $date_end;

    /** @return \PhpOffice\PhpSpreadsheet\Spreadsheet */
    protected function __get__xlsx() {
        return $this->xlsx;
    }

    /** @return string */
    protected function __get__temp_name() {
        return $this->temp_name;
    }

    /** @return \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet */
    protected function __get__sheet() {
        return $this->sheet;
    }

    /** @return \DateTime */
    protected function __get__date_start() {
        return $this->date_start;
    }

    /** @return \DateTime */
    protected function __get__date_end() {
        return $this->date_end;
    }

    protected function init_xlsx() {
        \PhpOffice\PhpSpreadsheet\Settings::setLocale('en_US');
        $this->xlsx = \PhpOffice\PhpSpreadsheet\IOFactory::load(__DIR__ . DIRECTORY_SEPARATOR . "payment_report_1.xlsx");
        $this->temp_name = tempnam(sys_get_temp_dir(), md5(get_called_class()));
        $this->sheet = $this->xlsx->getSheet(0);
        $this->sheet->setTitle("Отчет");
        $this->sheet->getCell("B1")->setValue(sprintf("%s - %s", $this->date_start->format('d.m.Y'), $this->date_end->format('d.m.Y')));
    }

    

    protected function save_file() {
        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($this->xlsx, "Xlsx");
        $writer->save($this->temp_name);
    }

    protected function generate_report($data) {
        $rows_to_insert = count($data);

        // выставляем стили
        $current_coord = "A3";
        $pase_n = null;
        $start_row = 3;
        $end_row = 3 + $rows_to_insert;
        $ltrs = ["A", "B", "C", "D", "E", "F", "G", "H", "I"];
        $eri = $end_row - 1;
        foreach ($ltrs as $letter) {
            $style = $this->sheet->getCell("{$letter}{$start_row}")->getStyle();
            $this->sheet->duplicateStyle($style, "{$letter}{$start_row}:{$letter}{$eri}");
        }
        $data_to_insert = [];
        foreach ($data as $row) {
            $data_to_insert[] = [$row['soap_name'], $row['season_name'], $row['serie_name'], $row['date'], $row['amount'], empty($row['copyright_holder'])?'-' : $row['copyright_holder']];
        }
        $this->sheet->fromArray($data_to_insert, '', "A3", true);
        $this->sheet->freezePane('A3');
    }

    protected function get_rows() {
        $query = "
                SELECT A.content_id,DATE_FORMAT(A.d,'%d.%m.%Y') `date`,A.amount,SOAP.common_name soap_name,SEASON.common_name season_name,SERIE.common_name serie_name,SERIE.id serie_id,
                SEASON.id season_id,SOAP.id soap_id,SOAP.copyright_holder
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
        $rows = \DB\DB::F()->queryAll($query, [":Pds" => $this->date_start->format('Y-m-d'), ':Pde' => $this->date_end->format('Y-m-d')]);
        return $this->prepare_rows($rows);
    }

    protected function prepare_rows(array $rows) {
        return $rows;
    }

    public function __construct(\DateTime $start, \DateTime $end) {
        $this->date_start = new \DateTime();
        $this->date_start->setTimestamp($start->getTimestamp());
        $this->date_end = new \DateTime();
        $this->date_end->setTimestamp($end->getTimestamp());
    }

    /**
     * 
     * @param \DateTime $start
     * @param \DateTime $end
     * @return \static
     */
    public static function F(\DateTime $start, \DateTime $end) {
        return new static($start, $end);
    }

    public function run() {
        $this->init_xlsx();
        $this->generate_report($this->get_rows());
        $this->save_file();
    }

}
