<?php
return [
    'adminEmail' => 'admin@example.com',
    'supportEmail' => 'support@example.com',
    'user.passwordResetTokenExpire' => 3600,
    'wechat' => [
        'appid' => 'wx3fa5a8376afca81c',
        'secret' => '175e62b5c16a48154d5b33346e8be6c9',
    ],
    'wechattest' => [
        'appid' => 'wx831217340e7a4f65',
        'secret' => 'd3f3ef52802b97024536d5d98b3e8342',
    ],
    'weChatUrl' => [
        'menu' => [
            'list' => 'https://api.weixin.qq.com/cgi-bin/menu/get',
        ],
    ],
    'HeFeng' => [
        'api' => [
            'weather' =>
                [
                    'now' =>'https://free-api.heweather.com/s6/weather/now',
                    'three' => 'https://free-api.heweather.com/s6/weather/forecast',
                ],
        ],
        'key' => 'f775cfee861445c1bd0373cea7b7e300',
    ]
];
