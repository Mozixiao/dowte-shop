<?php

namespace common\forms;

use common\base\BaseException;
use common\base\BaseForm;
use common\enums\StatusEnum;
use common\models\AccessToken;

class AccessTokenForm extends BaseForm
{
    const WECHAT_TYPE = 1;

    public static function createAccessToken($accessToken = null, $tokenType = null)
    {
        parent::checkNull(['accessToken' => $accessToken, 'tokenType' => $tokenType], __CLASS__);

        $model = new AccessToken();
        $model->access_token = $accessToken;
        $model->token_type = $tokenType;
        $model->created_at = time();
        $model->updated_at = time();

        if ( ! $model->save()) {
            throw new BaseException(BaseException::MODEL_SAVE_ERROR, '');
        }

        return $model->id;
    }

    public static function getAccessToken($tokenType = null)
    {
        parent::checkNull(['tokenType' => $tokenType], __CLASS__);

        $accessToken = AccessToken::find()
            ->where(['token_type' => $tokenType, 'status' => StatusEnum::STATUS_ACTIVE])
            ->orderBy('id DESC')
            ->one();

        if ( ! isset($accessToken->access_token)) {
            throw new BaseException(BaseException::ACCESS_TOKEN_NOT_EXISTS, '');
        }

        return $accessToken->access_token;
    }
}