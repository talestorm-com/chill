<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

function smarty_function_render_selected_soap($aparams, $smtpl) {
    $aparams = is_array($aparams) ? $aparams : [];
    $params = DataMap\CommonDataMap::F()->rebind($aparams);
    $rid = \Router\Router::F()->route->get_params()->get_filtered("soap_id", ['IntMore0', 'DefaultNull']);    
    if ($rid) {
        $soap = null;
        try {            
            $soap = \Content\MediaContentFront\MediaContentFrontSOAP\MediaContentObject::FACTORY($rid);
        } catch (\Throwable $e) {            
            $soap = null;
        }
        if ($soap) {
            $soap->render();            
            return '';
        }
    }
    \Router\NotFoundError::R("not found");
}
