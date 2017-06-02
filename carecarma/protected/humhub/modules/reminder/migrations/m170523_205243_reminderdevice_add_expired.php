<?php

use yii\db\Migration;

class m170523_205243_reminderdevice_add_expired extends Migration
{
    public function up()
    {
        $this->addColumn('reminder_device_time', 'deadline', 'text NOT NULL');
        $this->addColumn('reminder_device_time', 'remove_sent', 'int(11) DEFAULT NULL');
    }

    public function down()
    {
        echo "m170523_205243_reminderdevice_add_expired cannot be reverted.\n";

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
