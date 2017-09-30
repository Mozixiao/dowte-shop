<?php

namespace console\controllers;

use yii\console\Controller;
use common\forms\AccessTokenForm;
use common\util\Http;

class CrontabController extends Controller
{
    public function actionWechatAccessToken()
    {
        $appid = \Yii::$app->params['wechat']['appid'];
        $secret = \Yii::$app->params['wechat']['secret'];
        $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid={$appid}&secret={$secret}";
        $accessToken = Http::get($url);
        $accessToken = json_decode($accessToken);
        if (isset($accessToken['access_token'])) {
            AccessTokenForm::createAccessToken($accessToken, AccessTokenForm::WECHAT_TYPE);
        }
        return true;
    }

}