<?php

namespace console\controllers;

use common\sdk\WeChatSdk;
use yii\console\Controller;
use common\forms\AccessTokenForm;
use common\util\Http;

class CrontabController extends Controller
{
    public function actionWechatAccessToken()
    {
        $appid = \Yii::$app->params['wechat']['appId'];
        $secret = \Yii::$app->params['wechat']['secret'];
        $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid={$appid}&secret={$secret}";
        $accessToken = Http::get($url);
        $accessToken = json_decode($accessToken, true);
        if (isset($accessToken['access_token'])) {
            (new AccessTokenForm())->createAccessToken($accessToken['access_token'], AccessTokenForm::WECHAT_TYPE);
        }

        return true;
    }

    public function actionCreateMenu()
    {
        $accessToken = (new AccessTokenForm())->getAccessToken(AccessTokenForm::WECHAT_TYPE)->access_token;
        $options = [
            'appid' => \Yii::$app->params['wechat']['appId'],
            'appsecret' => \Yii::$app->params['wechat']['secret'],
            'token'  => $accessToken,
        ];
        $app  = new WeChatSdk($options);
        $data = [
            'button' => [
                0 => [
                    'name'       => '扫码',
                    'sub_button' => [
                        0 => [
                            'type' => 'scancode_waitmsg',
                            'name' => '扫码带提示',
                            'key'  => 'rselfmenu_0_0',
                        ],
                        1 => [
                            'type' => 'scancode_push',
                            'name' => '扫码推事件',
                            'key'  => 'rselfmenu_0_1',
                        ],
                    ],
                ],
                1 => [
                    'name'       => '发图',
                    'sub_button' => [
                        0 => [
                            'type' => 'pic_sysphoto',
                            'name' => '系统拍照发图',
                            'key'  => 'rselfmenu_1_0',
                        ],
                        1 => [
                            'type' => 'pic_photo_or_album',
                            'name' => '拍照或者相册发图',
                            'key'  => 'rselfmenu_1_1',
                        ],
                    ],
                ],
                2 => [
                    'name'       => '工具',
                    'sub_button' => [
                        0 => [
                            'type' => 'click',
                            'name' => '今日天气',
                            'key'  => 'K1000_TODAY_WEATHER',
                        ],
//                        1 => [
//                            'type' => 'pic_photo_or_album',
//                            'name' => '拍照或者相册发图',
//                            'key'  => 'rselfmenu_1_1',
//                        ],
//                        2 => [
//                            'type' => 'location_select',
//                            'name' => '发送位置',
//                            'key'  => 'rselfmenu_2_0',
//                        ]
                    ],
                ],
            ],
        ];
        $app->createMenu($data);
    }
}