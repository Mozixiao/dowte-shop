<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "wechat_user".
 *
 * @property integer $id
 * @property integer $mpid
 * @property string $openid
 * @property string $unionid
 * @property integer $subscribe
 * @property integer $subscribe_time
 * @property integer $unsubscribe_time
 * @property string $nickname
 * @property integer $sex
 * @property string $headimgurl
 * @property string $relname
 * @property string $signature
 * @property string $mobile
 * @property integer $is_bind
 * @property string $language
 * @property string $country
 * @property string $province
 * @property string $city
 * @property string $remark
 * @property integer $groupid
 * @property string $tagid_list
 * @property string $latitude
 * @property string $longitude
 * @property string $location_precision
 */
class WechatUser extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'wechat_user';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['mpid', 'subscribe', 'subscribe_time', 'unsubscribe_time', 'sex', 'is_bind', 'groupid'], 'integer'],
            [['openid'], 'required'],
            [['signature'], 'string'],
            [['openid', 'unionid', 'headimgurl', 'tagid_list'], 'string', 'max' => 255],
            [['nickname', 'relname', 'language', 'country', 'province', 'city', 'remark', 'latitude', 'longitude', 'location_precision'], 'string', 'max' => 50],
            [['mobile'], 'string', 'max' => 15],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '自增ID',
            'mpid' => '公众号标识',
            'openid' => '粉丝标识',
            'unionid' => 'Unionid',
            'subscribe' => '是否关注',
            'subscribe_time' => '关注时间',
            'unsubscribe_time' => '取消关注时间',
            'nickname' => '粉丝昵称',
            'sex' => '粉丝性别',
            'headimgurl' => '粉丝头像',
            'relname' => '真实姓名',
            'signature' => '个性签名',
            'mobile' => '手机号',
            'is_bind' => '是否绑定',
            'language' => '使用语言',
            'country' => '国家',
            'province' => '省份',
            'city' => '城市',
            'remark' => '备注',
            'groupid' => '分组ID',
            'tagid_list' => '标签',
            'latitude' => '纬度',
            'longitude' => '经度',
            'location_precision' => '精度',
        ];
    }
}
