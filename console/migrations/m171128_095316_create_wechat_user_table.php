<?php

use yii\db\Migration;

/**
 * Handles the creation of table `wechat_user`.
 */
class m171128_095316_create_wechat_user_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        /**
        CREATE TABLE `wechat_user` (
        `id` int(10) NOT NULL AUTO_INCREMENT COMMENT '自增ID',
        `mpid` int(10) NOT NULL DEFAULT '0' COMMENT '公众号标识',
        `openid` varchar(255) NOT NULL COMMENT '粉丝标识',
        `unionid` varchar(255) NOT NULL DEFAULT '0',
        `subscribe` tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否关注',
        `subscribe_time` int(10) NOT NULL DEFAULT '0' COMMENT '关注时间',
        `unsubscribe_time` int(10) NOT NULL DEFAULT '0' COMMENT '取消关注时间',
        `nickname` varchar(50) NOT NULL DEFAULT '0' COMMENT '粉丝昵称',
        `sex` tinyint(1) NOT NULL DEFAULT '1' COMMENT '粉丝性别',
        `headimgurl` varchar(255) NOT NULL DEFAULT '0' COMMENT '粉丝头像',
        `relname` varchar(50) NOT NULL DEFAULT '0' COMMENT '真实姓名',
        `signature` text COMMENT '个性签名',
        `mobile` varchar(15) NOT NULL DEFAULT '0' COMMENT '手机号',
        `is_bind` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否绑定',
        `language` varchar(50) NOT NULL DEFAULT 'zh_CN' COMMENT '使用语言',
        `country` varchar(50) NOT NULL DEFAULT '中国' COMMENT '国家',
        `province` varchar(50) NOT NULL DEFAULT '0' COMMENT '省份',
        `city` varchar(50) NOT NULL DEFAULT '0' COMMENT '城市',
        `remark` varchar(50) NOT NULL DEFAULT '0' COMMENT '备注',
        `groupid` int(10) NOT NULL DEFAULT '0' COMMENT '分组ID',
        `tagid_list` varchar(255) NOT NULL DEFAULT '0' COMMENT '标签',
        `latitude` varchar(50) NOT NULL DEFAULT '0' COMMENT '纬度',
        `longitude` varchar(50) NOT NULL DEFAULT '0' COMMENT '经度',
        `location_precision` varchar(50) NOT NULL DEFAULT '0' COMMENT '精度',
        PRIMARY KEY (`id`)
        ) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='公众号粉丝表';
         */
        $this->createTable('wechat_user', [

            'id' => $this->primaryKey()->unsigned()->comment('流水号'),
            'mpid' => $this->integer(11)->notNull()->defaultValue(0)->comment('公众号标识'),
            'openid' => $this->string('255')->notNull()->comment('openid'),
            'unionid' => $this->string('255')->notNull()->defaultValue(0)->comment('unionid'),
            'subscribe' => 'tinyint(1) NOT NULL DEFAULT \'1\' COMMENT \'是否关注\'',
            'subscribe_time' => 'int(10) DEFAULT 0 NOT NULL COMMENT \'关注时间\'',
            'unsubscribe_time' => 'int(10) DEFAULT 0 NOT NULL COMMENT \'取消关注时间\'',
            'nickname' => 'varchar(50) DEFAULT 0 NOT NULL COMMENT \'粉丝昵称\'',
            'sex' => 'tinyint(1) DEFAULT 1 NOT NULL COMMENT \'粉丝性别\'',
            'headimgurl' => 'varchar(255) DEFAULT 0 NOT NULL COMMENT \'粉丝头像\'',
            'relname' => 'varchar(50) DEFAULT 0 NOT NULL COMMENT \'真实姓名\'',
            'signature' => 'text COMMENT \'个性签名\'',
            'mobile' => 'varchar(15) DEFAULT 0 NOT NULL COMMENT \'手机号\'',
            'is_bind' => 'tinyint(1) NOT NULL DEFAULT \'0\' COMMENT \'是否绑定\'',
            'language' => 'varchar(50) DEFAULT \'zh_CN\' NOT NULL COMMENT \'使用语言\'',
            'country' => 'varchar(50) NOT NULL DEFAULT \'中国\' COMMENT \'国家\'',
            'province' => 'varchar(50) NOT NULL DEFAULT \'0\' COMMENT \'省份\'',
            'city' => 'varchar(50) NOT NULL DEFAULT \'0\' COMMENT \'城市\'',
            'remark' => 'varchar(50) NOT NULL DEFAULT \'0\' COMMENT \'备注\'',
            'groupid' => 'int(10) NOT NULL DEFAULT \'0\' COMMENT \'分组ID\'',
            'tagid_list' => 'varchar(255) NOT NULL DEFAULT \'0\' COMMENT \'标签\'',
            'latitude' => 'varchar(50) NOT NULL DEFAULT \'0\' COMMENT \'纬度\'',
            'longitude' => 'varchar(50) NOT NULL DEFAULT \'0\' COMMENT \'经度\'',
            'location_precision' => 'varchar(50) NOT NULL DEFAULT \'0\' COMMENT \'精度\'',
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('wechat_user');
    }
}
