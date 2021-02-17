<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

return[
    [
        'type' => "ctVIDEO",
        'name' => 'Видео',
        'table' => 'media__content__video',
        'table_alias' => 'MCTV',
        'editor' => 'data_editor.mediacontent.type_editor.video_editor',
        'columns' => [],
        'visible' => false,
    ],
    [
        'type' => "ctCOLLECTION",
        'name' => 'Подборка',
        'table' => 'media__content__collection',
        'table_alias' => 'MCTC',
        'editor' => 'data_editor.mediacontent.type_editor.collection_editor',
        'columns' => [],
        'visible' => true,
    ],
    [
        'type' => "ctSEASON",
        'name' => 'Сериал',
        'table' => 'media__content__season',
        'table_alias' => 'MCTS',
        'editor' => 'data_editor.mediacontent.type_editor.season_editor',
        'columns' => []
    ],
    [
        'type' => "ctGIF",
        'name' => 'GIF',
        'table' => 'media__content__gif',
        'table_alias' => 'MCTG',
        'editor' => 'data_editor.mediacontent.type_editor.gif_editor',
        'columns' => [],
        'visible' => false,
    ],
    [
        'type' => "ctTEXT",
        'name' => 'Новость',
        'table' => 'media__content__text',
        'editor' => 'data_editor.mediacontent.type_editor.text_editor',
        'table_alias' => 'MCTT',
        'columns' => [],
        'visible' => false,
    ]
];
