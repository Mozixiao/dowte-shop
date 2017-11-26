<?php

use yii\db\Migration;

class m171014_055217_add_tool_mean extends Migration
{
    public function safeUp()
    {
        $route = new \mdm\admin\models\AuthItem();
        $route->name = '/tool/ocr';
        $route->type = 2;
        $route->save();

        $firstMenu = new \common\models\Menu();
        $firstMenu->name = '工具';
        $firstMenu->order = 3;
        $firstMenu->data = '{"icon": "align-justify"}';
        $parentId = $firstMenu->save();

        $secondMenu = new \common\models\Menu();
        $secondMenu->name = '图片转文字';
        $secondMenu->order = 1;
        $secondMenu->parent = $parentId;
        $secondMenu->save();
    }

    public function safeDown()
    {
        echo "m171014_055217_add_tool_mean cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m171014_055217_add_tool_mean cannot be reverted.\n";

        return false;
    }
    */
}
