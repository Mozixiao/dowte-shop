<?php

namespace console\controllers;

use yii\console\Controller;
use common\forms\AccessTokenForm;
use common\util\Http;

class CrontabController extends Controller
{
    public function actionWechatAccessToken()
    {
        $appid = \Yii::$app->params['wechattest']['appid'];
        $secret = \Yii::$app->params['wechattest']['secret'];
        $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid={$appid}&secret={$secret}";
        $accessToken = Http::get($url);
        $accessToken = json_decode($accessToken, true);
        if (isset($accessToken['access_token'])) {
            AccessTokenForm::createAccessToken($accessToken['access_token'], AccessTokenForm::WECHAT_TYPE);
        }
        return true;
    }

    public function actionCreateMenu()
    {
        $url = 'https://api.weixin.qq.com/cgi-bin/menu/addconditional?access_token=i_itpgmoUgw9vqLifqzc5eZAmHrBn5gU5XH6hVveD7Q_Hd2db2U94JUY7cWarkhRukwxtMqIB6w5apvX1XsvkQ3EkfecomGRO752BR_241L9tmlpDtTxNaxRO_Cqj_d5GCFeAGALHK';
        $post = <<<EOF
{
     "button":[
     {    
          "type":"click",
          "name":"今日歌曲",
          "key":"V1001_TODAY_MUSIC"
      },
      {
           "type":"click",
           "name":"歌手简介",
           "key":"V1001_TODAY_SINGER"
      },
      {
           "name":"菜单",
           "sub_button":[
           {    
               "type":"view",
               "name":"搜索",
               "url":"http://www.soso.com/"
            },
            {
               "type":"view",
               "name":"视频",
               "url":"http://v.qq.com/"
            },
            {
               "type":"click",
               "name":"赞一下我们",
               "key":"V1001_GOOD"
            }]
       }]
}
EOF;
        echo Http::curlPost($url, $post);
    }
}