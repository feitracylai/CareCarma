<?php

use yii\db\Migration;

class m170519_133651_status_change_sent extends Migration
{
    public function up()
    {
        $this->renameColumn('reminder_device', 'sent', 'status');
    }

    public function down()
    {
        echo "m170519_133651_status_change_sent cannot be reverted.\n";

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
