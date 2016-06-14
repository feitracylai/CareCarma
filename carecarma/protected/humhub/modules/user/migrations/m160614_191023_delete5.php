<?php

use yii\db\Migration;

class m160614_191023_delete5 extends Migration
{
    public function up()
    {
        $this->dropTable('beacon');
    }

    public function down()
    {
        echo "m160614_191023_delete5 cannot be reverted.\n";

        return false;
    }

    /*
    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
    }

    public function safeDown()
    {
    }
    */
}
