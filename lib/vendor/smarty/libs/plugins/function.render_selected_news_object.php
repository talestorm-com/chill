<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

function smarty_function_render_selected_news_object($aparams, $smtpl) {
    $aparams = is_array($aparams) ? $aparams : [];
    $params = DataMap\CommonDataMap::F()->rebind($aparams);
    $rid = \Router\Router::F()->route->get_params()->get_filtered("content_id", ['IntMore0', 'DefaultNull']);
    if ($rid) {
        $neue = null;
        try {
            // не тот тип
            $neue = \Content\MediaContentFront\MediaContentFrontTEXT\MediaContentObject::FACTORY($rid);
        } catch (\Throwable $e) {
            $neue = null;
        }
        if ($neue) {

            $neue->render();
            return '';
        }
    }
    \Router\NotFoundError::R("not found");
}
