<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
return[
    'enabled' => true, // ip фильтр включен
    'blacklist' => false, //whitelist  режим блэк или вайтлиста. true - страны из списка под запретом, false - страны из списка разрешены
    'codes' => [//коды стран по ISO 3166-1  (https://ru.wikipedia.org/wiki/ISO_3166-1)
        'RUS' => 1, //  значение не имеет значения - только присутствие в списке ключа
        'UKR' => 1,
        'AZE' => 1,
        'ARM' => 1,
        'BLR' => 1,
        'KAZ' => 1,
        'KGZ' => 1, 
        'MDA' => 1, 
        'TJK' => 1,
        'UZB' => 1,
    ],
    'no_check_agents' => [// user-agent совпадающие с регулярками из списка разрешены всегда
        "/YandexWebmaster/i",
        "/facebookexternalhit/i",
        "/Googlebot/i",
        "/APIs-Google/i",
        "/YandexBot/i",
    ]
];
