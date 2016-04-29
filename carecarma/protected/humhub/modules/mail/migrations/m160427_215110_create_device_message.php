<?php

use yii\db\Migration;

class m160427_215110_create_device_message extends Migration
{
    public function up()
    {
        $this->createTable('device_message', array(
            'id' => 'pk',
            'message_id' => 'int(11) NOT NULL',
            'user_id' => 'int(11) NOT NULL',
            'from_id' => 'int(11) NOT NULL',
            'content' => 'text NOT NULL',
            'updated_at' => 'datetime DEFAULT NULL',
            'isRead' => 'varchar(255) NOT NULL DEFAULT "false"',
        ), '');
    }

    public function down()
    {
        echo "m160427_215110_create_device_message does not support migration down.\n";
        return false;
    }
}
