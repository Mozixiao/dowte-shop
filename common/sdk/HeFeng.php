<?php

namespace common\sdk;

Class HeFeng
{
    /**
     * @var string 和风天气接口调用凭据key
     */
    public $key;

    public function __construct($key)
    {
        $this->key = $key;
    }

    /**
     * @param string $location 城市名称。除拼音外，还可以使用 v3 id、汉语等形式
     * @return string
     */
    public function getNowWeather($location)
    {
        $api = \Yii::$app->params['HeFeng']['api']['weather']['now']; // 接口地址
        // 生成签名。文档：https://www.seniverse.com/doc#sign
        $url = $api . '?key=' . $this->key . '&location=' . urlencode($location);

        return \common\util\Http::curlPost($url, [], ["Accept: application/json", "Content-Type: application/json;charset=utf-8"]);
    }

    public function getThreeWeather($location)
    {
        $api = \Yii::$app->params['HeFeng']['api']['weather']['three']; // 接口地址
        $url = $api . '?key=' . $this->key . '&location=' . urlencode($location);
        return \common\util\Http::curlPost($url, [], ["Accept: application/json", "Content-Type: application/json;charset=utf-8"]);
    }
//    function requestByKey(){
//        //准备请求参数
//        $key ="f775cfee861445c1bd0373cea7b7e300";
//        $location = "诸暨";
//        $curlPost = "key=".$key."&location=".urlencode($location);
//        //初始化请求链接
//        $req=curl_init();
//        //设置请求链接
//        curl_setopt($req, CURLOPT_URL,'https://free-api.heweather.com/s6/weather/now?'.$curlPost);
//        //设置超时时长(秒)
//        curl_setopt($req, CURLOPT_TIMEOUT,3);
//        //设置链接时长
//        curl_setopt($req, CURLOPT_CONNECTTIMEOUT,10);
//        //设置头信息
//        $headers=array( "Accept: application/json", "Content-Type: application/json;charset=utf-8" );
//        curl_setopt($req, CURLOPT_HTTPHEADER, $headers);
//
//        curl_setopt($req, CURLOPT_SSL_VERIFYPEER, false);
//        curl_setopt($req, CURLOPT_SSL_VERIFYHOST, false);
//        $data = curl_exec($req);
//        curl_close($req);
//        return $data;
//    }
}