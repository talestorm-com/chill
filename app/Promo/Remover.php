<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Promo;

/**
 * Description of Remover
 *
 * @author eve
 */
class Remover {

    private $id;

    protected function __construct(int $id) {
        $this->id = $id;
    }

    /**
     * 
     * @param int $id
     * @return \static
     */
    public static function F(int $id) {
        return new static($id);
    }

    public function run() {
        \DB\SQLTools\SQLBuilder::F()->push("DELETE FROM chill__promo WHERE id=:P;")
                ->push_param(":P", $this->id)->execute_transact();
    }

}
