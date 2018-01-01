<?php

namespace common\forms;

use common\base\BaseException;
use common\base\BaseForm;
use common\enums\StatusEnum;
use common\models\AccessToken;
use common\sdk\BaiduOcr;

class AccessTokenForm extends BaseForm
{
    const WECHAT_TYPE = 1;
    const BAIDU_BASIC_TOKEN_TYPE = 2;

    public function createAccessToken($accessToken, $tokenType)
    {
        $model = new AccessToken();
        $model->access_token = $accessToken;
        $model->token_type = $tokenType;
        $model->created_at = time();
        $model->updated_at = time();

        if (! $model->validate() || ! $model->save()) {
            throw new BaseException(BaseException::MODEL_SAVE_ERROR, '');
        }

        return $model->id;
    }

    /**
     * @param $tokenType
     * @param array $columns
     * @return array|null|\yii\db\ActiveRecord|object
     */
    public function getAccessToken($tokenType, $columns = ['access_token', 'created_at'])
    {
        $accessToken = AccessToken::find()
            ->select($columns)
            ->where(['token_type' => $tokenType, 'status' => StatusEnum::STATUS_ACTIVE])
            ->orderBy('id DESC')
            ->one();

        return $accessToken;
    }

    public function getBaiduToken($type, $apiKey, $secretKey)
    {
        $accessToken = $this->getAccessToken($type);
        if (empty($accessToken) || (time() - $accessToken->created_at)/86400 >= 30) {
            $baidu = new BaiduOcr(['apiKey' => $apiKey, 'secretKey' => $secretKey]);
            $res = $baidu->getAccessToken();
            $this->createAccessToken($res, $type);
            $accessToken = $res;

        } else {
            $accessToken = $accessToken['access_token'];
        }

        return $accessToken;
    }
}