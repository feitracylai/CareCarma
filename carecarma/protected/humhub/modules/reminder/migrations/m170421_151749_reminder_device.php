<?php

use yii\db\Migration;

class m170421_151749_reminder_device extends Migration
{
    public function up()
    {
        $this->createTable('reminder_device', array(
            'id' => 'pk',
            'title' => 'text NOT NULL',
            'description' => 'text DEFAULT NULL',
            'user_id' => 'int(11) NOT NULL',
            'sent' => 'int(1) DEFAULT 0',
            'update_user_id' => 'int(11) NOT NULL',
        ), '');

        $this->createTable('reminder_device_time' , array(
            'id' => 'pk',
            'reminder_id' => 'int(11) NOT NULL',
            'time' => 'text NOT NULL',
            'repeat' => 'int(1) NOT NULL',
            'date' => 'text NOT NULL',
            'day' => 'text NOT NULL',
        ), '');
    }

    public function down()
    {
        echo "m170421_151749_reminder_device cannot be reverted.\n";

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
