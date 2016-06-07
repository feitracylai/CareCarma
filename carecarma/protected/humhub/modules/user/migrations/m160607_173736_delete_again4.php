<?php

use yii\db\Migration;

class m160607_173736_delete_again4 extends Migration
{
    public function up()
    {
        $this->dropTable('sensor');
        $this->dropTable('beacon');
    }

    public function down()
    {
        echo "m160607_173736_delete_again4 cannot be reverted.\n";

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
