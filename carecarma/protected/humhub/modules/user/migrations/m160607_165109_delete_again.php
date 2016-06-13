<?php

use yii\db\Migration;

class m160607_165109_delete_again extends Migration
{
    public function up()
    {
        $this->dropTable('sensor');
        $this->dropTable('beacon');
    }

    public function down()
    {
        echo "m160607_165109_delete_again cannot be reverted.\n";

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