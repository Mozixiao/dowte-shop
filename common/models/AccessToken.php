<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "access_token".
 *
 * @property integer $id
 * @property string $access_token
 * @property integer $token_type
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 */
class AccessToken extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'access_token';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['access_token', 'token_type'], 'required'],
            [['token_type', 'status', 'created_at', 'updated_at'], 'integer'],
            [['access_token'], 'string', 'max' => 512],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'access_token' => '临时授权码',
            'token_type' => '授权码类型',
            'status' => '状态位',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
}
