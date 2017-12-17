<?php

namespace common\sdk;

use common\forms\AccessTokenForm;

class WeChat
{
    public $options;
    public $app;

    public function __construct()
    {
        $accessToken = (new AccessTokenForm())->getAccessToken(AccessTokenForm::WECHAT_TYPE)->access_token;
        $this->options = [
            'appid' => \Yii::$app->params['wechat']['appId'],
            'appsecret' => \Yii::$app->params['wechat']['secret'],
            'token'  => $accessToken,
        ];
        $this->app = new WeChatSdk($this->options);
    }

    /**
     * @param $userId
     * @param $data
     * @param string $url
     * @return array
     */
    public function sendWeatherTemplateMsg($userId, $data, $url = '')
    {
        $templateId = 'fodArAQKIblsorud9exjOroJQJN9wV1jbQgcCoOm6KM';
        $dataArr = [];
        $dataArr['touser'] = $userId;
        $dataArr['template_id'] = $templateId;
        $dataArr['url'] = $url;
        $dataArr['topcolor'] = '#FF0000';
        $dataArr['data'] = $this->buildTemplateData($data);

        return $this->app->sendTemplateMessage($dataArr);
    }

    public function getUserInfo($openId)
    {
        $userInfo = $this->app->getUserInfo($openId);
        return $userInfo;
    }

    private function buildTemplateData($data)
    {
        $result = [];
        foreach ($data as $k => $v) {
            $result[$k] = is_array($v) ? ['value' => $v[0], 'color' => $v[1]] : ['value' => $v, 'color' => '#173177'];
        }
        return $result;
    }
}