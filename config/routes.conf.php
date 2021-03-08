<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
return [
    //regexp=>[ns,controller,action,arguments(take arguments as part of match?)]
    'routes' => [
        "/^\/$/" => ['FrontEnd', 'Page', 'Index', ["alias" => 'home']],
        "/^\/{0,1}page\/(?P<alias>.*)$/i" => ['FrontEnd', 'Page', 'Index', ['alias' => '$$alias']],
       // "/^\/{0,1}login\/{0,1}$/i" => ['FrontEnd', 'Page', 'Index', ['alias' => 'login']],
      //  "/^\/{0,1}signup\/{0,1}$/i" => ['FrontEnd', 'Page', 'Index', ['alias' => 'signup']],
        "/^\/{0,1}catalog\/(?P<alias>.{1,})\/(?P<page>\d{1,})$/i" => ["FrontEnd", "Page", "Index", ['alias' => 'catalog_page', "catalog_alias" => '$$alias', 'catalog_page' => '$$page']],
        "/^\/{0,1}catalog\/(?P<alias>.{1,})$/i" => ["FrontEnd", "Page", "Index", ['alias' => 'catalog_page', "catalog_alias" => '$$alias']],
        "/^\/{0,1}product\/(?P<alias>.{1,})$/i" => ["FrontEnd", "Page", "Index", ['alias' => 'product_page', "product_alias" => '$$alias']],
        "/^\/{0,1}search$/i" => ["FrontEnd", "Search", "Index", []],
        "/^\/{0,1}soap\/(?P<alias>\d{1,})(?P<translit_name>.*)$/i" => ["FrontEnd", "Page", "Index", ['alias' => 'soap_page', "soap_id" => '$$alias',"translit_name" => '$$translit_name']],
        "/^\/{0,1}profile$/i" => ["FrontEnd", "Cabinet", "Profile", ['r'=>0]],
        "/^\/{0,1}News\/(?P<alias>\d{1,})$/i" => ["FrontEnd", "Page", "Index", ['alias' => 'news_page', "content_id" => '$$alias']],
        "/^\/{0,1}NewsList\/(?P<page>\d{1,})$/i" => ["FrontEnd", "NewsList", "Index", ["page" => '$$page']],
        "/^\/{0,1}NewsList$/i" => ["FrontEnd", "NewsList", "Index", ["page" => '0']],
        "/^\/{0,1}search\/by_tag\/(?P<tag_id>\d{1,})$/i" => ["FrontEnd", "Page", "Index", ["alias"=>"search_by_tag", "tag_id" => '$$tag_id']],
        "/^\/{0,1}search\/by_track_language\/(?P<language_id>\d{1,})$/i" => ["FrontEnd", "Page", "Index", ["alias"=>"search_by_track", "language_id" => '$$language_id']],
        "/^\/{0,1}search\/by_genre\/(?P<genre_id>\d{1,})$/i" => ["FrontEnd", "Page", "Index", ["alias"=>"search_by_genre", "genre_id" => '$$genre_id']],
        "/^\/{0,1}search\/by_emoji\/(?P<emoji_id>\d{1,})$/i" => ["FrontEnd", "Page", "Index", ["alias"=>"search_by_emoji", "emoji_id" => '$$emoji_id']],
        "/^\/{0,1}search\/by_origin\/(?P<origin_id>\d{1,})$/i" => ["FrontEnd", "Page", "Index", ["alias"=>"search_by_origin", "origin_id" => '$$origin_id']],
        "/^\/{0,1}catalog$/i" => ["FrontEnd", "ChillCatalog", "Index", []],
        "/^\/{0,1}collection\/(?P<collection_id>\d{1,})(?P<translit_name>.*)$/i" => ["FrontEnd", "ChillCatalog", "Collection", [ "collection_id" => '$$collection_id',"translit_name" => '$$translit_name']],
        "/^\/{0,1}lent_v_2\/more\/(?P<page>\d{1,})$/i" => ["FrontEnd", "Page", "Index", ['alias'=>'lent_v_2', 'page'=>'$$page' ]],
        "/^\/{0,1}lent_v_2$/i" => ["FrontEnd", "Page", "Index", ['alias'=>'lent_v_2']],
        "/^\/{0,1}comments$/i" => ["FrontEnd", "ComChill", "Index", []],
        //https://chill.ironstar.pw/search/by_tag/7
    ],
    //handler=>[ns,controller,action,params]
    'static' => [
        '404' => ["FrontEnd", "Page", '404'],
        '500' => ["FrontEnd", "Page", '500'],
        '403000' => ["FrontEnd", "Page", '403000'],
        'index' => ['FrontEnd', 'Page', "Index", ["alias" => "home"]],
    ]
];
