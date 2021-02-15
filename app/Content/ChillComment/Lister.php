<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
namespace Content\ChillComment;
/**
 * Description of Lister
 *
 * @author eve
 */
class Lister extends \Content\Lister\Lister {

    protected function get_filters(): array {
        return [
            'id' => 'Int:A.id',
            'author' => 'String:A.author',
            'datum' => 'Date:A.datum',
            'rating' => 'Int:B.rating',
            'enabled' => 'Int:A.enabled',
        ];
    }

    protected function get_sorts(): array {
        return [
            'id' => 'A.id',
            'author' => 'A.author|A.id',
            'datum' => 'A.datum|A.id',
            'enabled' => 'A.enabled|A.id',
            'rating' => 'B.rating|A.id',
        ];
    }

    protected function get_sort_separator(): string {
        return '|';
    }

    protected function build_query() {
        $qt = "SELECT SQL_CALC_FOUND_ROWS 
            A.id,A.author,DATE_FORMAT(A.datum,'%%d.%%m.%%Y') datum,B.rating,C.cdn_url,SUBSTRING(A.content,1,50) content,
            A.enabled,SUBSTRING(A.r,1,50) r
            FROM chill__review A LEFT JOIN chill__review__rating B ON(A.id=B.id)
            LEFT JOIN chill__review__sticker C ON(C.id=A.sticker)
            %s %s %s %s            
            ";

        return sprintf($qt, $this->filter->whereWord, $this->where, $this->sort->SQL, $this->limit->MySqlLimit);
    }

}
