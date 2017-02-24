<?php

use yii\db\Migration;

class m170224_144355_mc_need_tables extends Migration
{
    public function up()
    {
        $this->createTable('ClassLabelsHour', array(
            'id' => 'pk',
            'user_id' => 'int(11) DEFAULT NULL',
            'device_id' => 'varchar(11) DEFAULT NULL',
            'datetime' => 'DATETIME DEFAULT NULL',
            'time' => 'varchar(255) DEFAULT NULL',
            'heartrateLabel' => 'float DEFAULT NULL',
            'hardware_id' => 'varchar(15) DEFAULT NULL',

        ), '');

        $this->createTable('ClassLabelsSteps', array(
            'id' => 'pk',
            'user_id' => 'int(11) DEFAULT NULL',
            'device_id' => 'varchar(11) DEFAULT NULL',
            'datetime' => 'DATETIME DEFAULT NULL',
            'time' => 'varchar(255) DEFAULT NULL',
            'stepsLabel' => 'int(11) DEFAULT NULL',
            'hardware_id' => 'varchar(15) DEFAULT NULL',

        ), '');

        $this->createTable('LastDateReadHeart', array(
            'id' => 'pk',
            'user_id' => 'int(11) DEFAULT NULL',
            'device_id' => 'varchar(11) DEFAULT NULL',
            'datetime' => 'DATETIME DEFAULT NULL',
            'time' => 'varchar(255) DEFAULT NULL',
            'hardware_id' => 'varchar(15) DEFAULT NULL',

        ), '');

        $this->createTable('LastDateReadSteps', array(
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
        echo "m170224_144355_mc_need_tables cannot be reverted.\n";

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
