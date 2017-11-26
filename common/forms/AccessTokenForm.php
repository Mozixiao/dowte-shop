<?php

namespace common\forms;

use common\base\BaseException;
use common\base\BaseForm;
use common\enums\StatusEnum;
use common\models\AccessToken;

class AccessTokenForm extends BaseForm
{
    const WECHAT_TYPE = 1;
    const BAIDU_PLATE1_TOKEN_TYPE = 2;
    const BAIDU_BASIC_TOKEN_TYPE = 3;

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

    public function getAccessToken($tokenType, $columns = ['access_token', 'created_at'])
    {
        $accessToken = AccessToken::find()
            ->select($columns)
            ->where(['token_type' => $tokenType, 'status' => StatusEnum::STATUS_ACTIVE])
            ->orderBy('id DESC')
            ->asArray()
            ->one();

        return $accessToken;
    }
}