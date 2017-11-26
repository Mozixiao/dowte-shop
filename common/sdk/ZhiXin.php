<?php

namespace common\sdk;

Class ZhiXin
{
    /**
     * @var string 心知天气接口调用凭据key
     */
    public $key;

    /**
     * @var string 心知天气接口调用凭据uid
     */
    public $uid;

    public function __construct($key, $uid)
    {
        $this->key = $key;
        $this->uid = $uid;
    }

    /**
     * @param string $location 城市名称。除拼音外，还可以使用 v3 id、汉语等形式
     * @param int $start 开始日期。0 = 今天天气
     * @param int $days 查询天数，1 = 只查一天
     * @return string
     */
    public function getWeather($location, $start = 0, $days = 1)
    {
        $api = \Yii::$app->params['ZhiXin']['api']['weather']; // 接口地址
        // 生成签名。文档：https://www.seniverse.com/doc#sign
        $param = [
            'ts' => time(),
            'ttl' => 300,
            'uid' => $this->uid,
        ];
        $sig_data = http_build_query($param); // http_build_query 会自动进行 url 编码
        // 使用 HMAC-SHA1 方式，以 API 密钥（key）对上一步生成的参数字符串（raw）进行加密，然后 base64 编码
        $sig = base64_encode(hash_hmac('sha1', $sig_data, $this->key, TRUE));
        // 拼接 url 中的 get 参数。文档：https://www.seniverse.com/doc#daily
        $param['sig'] = $sig; // 签名
        $param['location'] = $location;
        $param['start'] = $start;
        $param['days'] = $days;
        // 构造 url
        $url = $api . '?' . http_build_query($param);

        return \common\util\Http::get($url);
    }
}