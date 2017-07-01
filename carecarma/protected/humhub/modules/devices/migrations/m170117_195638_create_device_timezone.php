<?php

use yii\db\Migration;

class m170117_195638_create_device_timezone extends Migration
{
    public function up()
    {
        $this->createTable('device_timezone', array(
            'id' => 'pk',
            'user_id' => 'int(11) DEFAULT NULL',
            'hardware_id' => 'varchar(15) DEFAULT NULL',
            'timezone' => 'varchar(255) DEFAULT NULL',
            'updated_time' => 'BIGINT(13) DEFAULT NULL',
        ), '');
    }

    public function down()
    {
        echo "m170117_195638_create_device_timezone cannot be reverted.\n";

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
