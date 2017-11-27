<?php

namespace common\sdk;

Class HeFeng
{
    const NOW_WEATHER = 'https://free-api.heweather.com/s6/weather/now';
    const THREE_WEATHER = 'https://free-api.heweather.com/s6/weather/forecast';

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
        $api = self::NOW_WEATHER; // 接口地址
        // 生成签名。文档：https://www.seniverse.com/doc#sign
        $url = $api . '?key=' . $this->key . '&location=' . urlencode($location);

        return \common\util\Http::curlPost($url, [], ["Accept: application/json", "Content-Type: application/json;charset=utf-8"]);
    }

    /**
     * @param string $location
     * @return mixed
     */
    public function getThreeWeather($location)
    {
        $api = self::THREE_WEATHER; // 接口地址
        $url = $api . '?key=' . $this->key . '&location=' . urlencode($location);
        return \common\util\Http::curlPost($url, [], ["Accept: application/json", "Content-Type: application/json;charset=utf-8"]);
    }
}