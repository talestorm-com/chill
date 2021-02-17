<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace DataImport\Common;   

class SliceFilter implements \PhpOffice\PhpSpreadsheet\Reader\IReadFilter {

    protected $min_row;
    protected $max_row;

    public function readCell($column, $row, $worksheetName = ''): bool {
        return (($row >= $this->min_row) && ($row < $this->max_row));
    }

    protected function __construct(int $offset, int $len) {
        $this->min_row = $offset;
        $this->max_row = $this->min_row + $len;
    }

    /**
     * 
     * @param int $offset
     * @param int $count
     * @return \PhpOffice\PhpSpreadsheet\Reader\IReadFilter
     */
    public static function F(int $offset, int $count): \PhpOffice\PhpSpreadsheet\Reader\IReadFilter {
        return new static($offset, $count);
    }

}
