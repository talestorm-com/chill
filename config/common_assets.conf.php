<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

return [
    'admin' => [
        'styles' => [
            '/assets/css/layout.css',
            '/assets/css/efo.css',
        ],
        'scripts' => [
            //url,async        
            ['/assets/js/efo.js', true],
        ],
        'inline_styles' => [
        ],
        'inline_scripts' => [
        ]
    ],
    'FrontEnd' => [
        'styles' => [
          //  '/assets/css/front/layout.css',
          //  '/assets/css/front/font.css',
          //  '/assets/css/efo.css',
          //  '/assets/css/front/m/m.css',
        ],
        'scripts' => [
          //  ['/assets/js/efo.js', true],
          //  ['/assets/js/basket/basket.min.js', true],
          //  ['/assets/js/ProductManager/src/ProductManager.js', true],
            ['https://www.google.com/recaptcha/api.js?render=<!PARAM RECAPTCHA_SITE_KEY!>',false],
            ['/assets/js/hls.js',false],
        ],
        'inline_styles' => [
        ],
        'inline_scripts' => [
        ]
    ],
    'styles' => [
    //'/assets/css/layout.css',
    // '/assets/css/efo.css',
    ],
    'scripts' => [
    //['/assets/js/jquery.js', true], //url,async        
    //  ['/assets/js/efo.js', true],
    ],
    'inline_styles' => [
    ],
    'inline_scripts' => [
    ]
];
