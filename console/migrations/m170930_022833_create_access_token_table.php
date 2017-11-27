<?php

use yii\db\Migration;

/**
 * Handles the creation of table `access_token`.
 */
class m170930_022833_create_access_token_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('access_token', [
            'id' => $this->primaryKey(11)->unsigned()->comment('流水号'),
            'access_token' => $this->string(512)->notNull()->comment('临时授权码'),
            'token_type' => $this->integer(4)->notNull()->comment('授权码类型'),
            'status' => $this->integer(4)->defaultValue(1)->notNull()->comment('状态位'),
            'created_at' => $this->integer(11)->notNull()->comment('创建时间'),
            'updated_at' => $this->integer(11)->notNull()->comment('更新时间'),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('access_token');
    }
}
