<?php

namespace common\sdk;

use common\base\BaseException;
use common\forms\AccessTokenForm;
use common\util\Http;

class BaiduOcr
{
    const GET_ACCESS_URL = 'https://openapi.baidu.com/oauth/2.0/token';
    const PLATE_URL = 'https://aip.baidubce.com/rest/2.0/ocr/v1/license_plate';
    const BASIC_URL = 'https://aip.baidubce.com/rest/2.0/ocr/v1/accurate_basic';

    const PLATE_TYPE = 1;
    const BASIC_TYPE = 2;

    //数组key对应ding_access_token库存储的type
    private static $keys = [
        '2' => [//车牌
            'ApiKey' => 'eGEcGfeLQpG8EyRcSodaklvX',
            'SecretKey' => 'PC9SF2QxXBDqBgppzEWKrFoBUz1Zydfw',
        ],
        '3' => [//通用图片
            'ApiKey' => 'eGEcGfeLQpG8EyRcSodaklvX',
            'SecretKey' => 'PC9SF2QxXBDqBgppzEWKrFoBUz1Zydfw',
        ],
    ];

    public function ocr($fileContent, $type)
    {
        switch ($type) {
            case self::PLATE_TYPE :
                return $this->getPlate($fileContent);
            case self::BASIC_TYPE :
                return $this->getWords($fileContent);
            default :
                return $this->getWords($fileContent);
        }
    }

    private function getPlate($file_content)
    {
        $access_token = self::getAccessToken(AccessTokenForm::BAIDU_PLATE1_TOKEN_TYPE);
        $res = self::run(self::PLATE_URL . '?access_token=' . $access_token,
            ['image' => $file_content], ['Content-Type:application/x-www-form-urlencoded']);

        if (isset($res['error_code'])){
            throw new BaseException(BaseException::RECOGNIZE_FALSE, '');

        } else {
            $res = [
                'PlateNum' => 1,
                'ErrorCode' => 0,
                'Plate' => [
                    ['车牌颜色' => $res['words_result']['color'], '车牌号' => $res['words_result']['number']]
                ]
            ];
        }
        return $res;
    }

    /**
     * @param $file_content
     * @return array|mixed
     * @throws BaseException
     */
    private function getWords($file_content)
    {
        $str = '';
        //验证access_token
        $access_token = self::getAccessToken(AccessTokenForm::BAIDU_BASIC_TOKEN_TYPE);

        for ($i = 0; $i < 3; $i ++ ){
            //执行请求
            $res = self::run(self::BASIC_URL . '?access_token=' . $access_token,
                ['image' => $file_content, 'detect_direction' => 'true'], ['Content-Type:application/x-www-form-urlencoded']);

            if (isset($res['error_code'])){
                if ($i == 2) {
                    throw new BaseException(BaseException::RECOGNIZE_FALSE, '');

                } else {
                    continue;
                }

            } else {
                if (isset($res['words_result_num']) && empty($res['words_result_num'])) {
                    return false;

                } else {
                    foreach ($res['words_result_num'] as $words) {
                        $str .= $words['words'];
                    }
                }
            }
        }

        return $str;
    }

    private static function getAccessToken($type)
    {
        $accessToken = new AccessTokenForm();
        $res = $accessToken->getAccessToken($type);
        if (empty($res) || (time() - $res['created_at'])/86400 >= 30) {
            $res = self::run(self::GET_ACCESS_URL, [
                'grant_type' => 'client_credentials',
                'client_id' => self::$keys[$type]['ApiKey'],
                'client_secret' => self::$keys[$type]['SecretKey']
            ]);
            $accessToken->createAccessToken($res['access_token'], $type);
            $accessToken = $res['access_token'];

        } else {
            $accessToken = $res['access_token'];
        }

        return $accessToken;
    }

    private static function run($url, $params, $header = [])
    {
        $res = Http::curlPost($url, $params, $header);

        return json_decode($res, true);
    }
}