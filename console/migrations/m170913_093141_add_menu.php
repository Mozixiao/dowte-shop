<?php

use yii\db\Migration;
use common\models\Menu;

class m170913_093141_add_menu extends Migration
{
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB COMMENT="菜单表"';
        }
        $table = Yii::$app->db->schema->getTableSchema('menu');
        if ($table === null) {
            $this->createTable(Menu::tableName(), [
                'id' => $this->primaryKey(11),
                'name' => $this->string(128)->notNull(),
                'parent' => $this->integer(11)->null(),
                'route' => $this->string(256)->null(),
                'order' => $this->integer(11)->null(),
                'data' => $this->text(),
            ], $tableOptions);

            $this->addForeignKey('menu_ibfk_1', 'menu', 'parent', 'menu', 'id', 'SET NULL', 'CASCADE');
        }
    }

    public function safeDown()
    {
        echo "m170913_093141_add_menu cannot be reverted.\n";
        $this->dropTable('menu');
        return false;
    }
}
