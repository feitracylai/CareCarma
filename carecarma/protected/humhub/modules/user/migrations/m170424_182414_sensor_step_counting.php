<?php

use yii\db\Migration;

class m170424_182414_sensor_step_counting extends Migration
{
    public function up()
    {
        $this->createTable('sensor_step_counter', array(
            'id' => 'pk',
            'user_id' => 'int(11) NOT NULL',
            'hardware_id' => 'varchar(15) DEFAULT NULL',
            'steps' => 'int(11) DEFAULT NULL',
            'datetime' => 'datetime DEFAULT NULL',
            'time' => 'BIGINT(13) DEFAULT NULL',
        ), '');
    }

    public function down()
    {
        echo "m170424_182414_sensor_step_counting cannot be reverted.\n";

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
