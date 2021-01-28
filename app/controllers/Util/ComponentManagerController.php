<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace controllers\Util;

class ComponentManagerController extends \controllers\abstract_controller {

    protected function actionGetComponent() {
        $fqcn = $this->GP->get_filtered('fqcn', ['Strip', 'Trim', 'NEString', 'DefaultNull']);
        if (!$fqcn) {
            $this->e404('no fqcn provided');
        }
        $m = [];        
        if (preg_match("/^(?P<cf>.*)\.js$/i", $fqcn, $m)) {
            $fqcn = $m['cf'];
        }
        try {            
            \LoadableComponents\ComponentBuilder::F($fqcn)->build_component($fqcn);
        } catch (\LoadableComponents\ComponentNotFound $e) {
            $this->e404($e->getMessage());
        } catch (\Exception $e) {
            $this->e404($e->getMessage());
        }
        die();
    }

    protected function e404(string $message = '') {
        if (!headers_sent()) {  
            header("HTTP/1.0 404 Not Found", true, 404);
        }
        die($message); 
    }

}
