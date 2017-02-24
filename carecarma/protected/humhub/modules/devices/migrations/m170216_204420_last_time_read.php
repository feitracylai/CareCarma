<?php

use yii\db\Migration;

class m170216_204420_last_time_read extends Migration
{
    public function up()
    {
        $this->createTable('LastTimeReadHeart', array(
            'id' => 'pk',
            'user_id' => 'int(11) DEFAULT NULL',
            'device_id' => 'varchar(11) DEFAULT NULL',
            'datetime' => 'DATETIME DEFAULT NULL',
            'time' => 'varchar(255) DEFAULT NULL',
            'hardware_id' => 'varchar(15) DEFAULT NULL',
        ), '');

        $this->createTable('LastTimeReadSteps', array(
            'id' => 'pk',
            'user_id' => 'int(11) DEFAULT NULL',
            'device_id' => 'varchar(11) DEFAULT NULL',
            'datetime' => 'DATETIME DEFAULT NULL',
            'time' => 'varchar(255) DEFAULT NULL',
            'hardware_id' => 'varchar(15) DEFAULT NULL',
        ), '');
    }

    public function down()
    {
        $this->dropTable('LastTimeReadHeart');
        $this->dropTable('LastTimeReadSteps');
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
