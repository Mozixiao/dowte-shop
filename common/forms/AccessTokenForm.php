<?php

namespace common\forms;

use common\base\BaseException;
use common\base\BaseForm;
use common\models\AccessToken;

class AccessTokenForm extends BaseForm
{
    const WECHAT_TYPE = 1;

    public static function createAccessToken($accessToken = null, $tokenType = null)
    {
        parent::checkNull([$accessToken, $tokenType], __CLASS__);

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
}