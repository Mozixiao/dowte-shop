<?php

namespace common\sdk;

use common\base\BaseException;
use common\util\Http;
use yii\base\Object;

class BaiduOcr extends Object
{
    const GET_ACCESS_URL = 'https://openapi.baidu.com/oauth/2.0/token';
    const PLATE_URL = 'https://aip.baidubce.com/rest/2.0/ocr/v1/license_plate';
    const BASIC_URL = 'https://aip.baidubce.com/rest/2.0/ocr/v1/accurate_basic';
    const EXCEL_URL = 'https://aip.baidubce.com/rest/2.0/solution/v1/form_ocr/request';
    const GET_EXCEL_URL = 'https://aip.baidubce.com/rest/2.0/solution/v1/form_ocr/get_request_result';

    /**
     * @var string 应用id
     */
    public $apiKey;

    /**
     * @var string 应用key
     */
    public $secretKey;

    /**
     * @var string 请求token
     */
    public $accessToken;

    public function ocrExcel($fileContent)
    {
        $res = self::run(self::EXCEL_URL . '?access_token=' . $this->accessToken,
            ['image' => $fileContent], ['Content-Type:application/x-www-form-urlencoded']);

        return $res;
    }

    public function getExcel($requestId)
    {
        $res = self::run(self::GET_EXCEL_URL . '?access_token=' . $this->accessToken,
            ['request_id' => $requestId, 'result_type' => 'excel'], ['Content-Type:application/x-www-form-urlencoded']);

        return $res;
    }

    public function getPlate($file_content)
    {
        $res = self::run(self::PLATE_URL . '?access_token=' . $this->accessToken,
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
    public function getWords($file_content)
    {
        $str = '';

        for ($i = 0; $i < 3; $i ++ ){
            //执行请求
            $res = self::run(self::BASIC_URL . '?access_token=' . $this->accessToken,
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
                    foreach ($res['words_result'] as $words) {
                        $str .= "<br>" . $words['words'];
                    }
                }
                break;
            }
        }

        return $str;
    }

    /**
     * @return string
     */
    public function getAccessToken()
    {
        $res = self::run(self::GET_ACCESS_URL, [
            'grant_type' => 'client_credentials',
            'client_id' => $this->apiKey,
            'client_secret' => $this->secretKey
        ]);

        return $res['access_token'];
    }

    /**
     * @param $url
     * @param $params
     * @param array $header
     * @return mixed
     */
    private static function run($url, $params, $header = [])
    {
        $res = Http::curlPost($url, $params, $header);

        return json_decode($res, true);
    }
}