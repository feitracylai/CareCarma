<?php

use yii\db\Migration;

class m160607_173810_beaconsensor4 extends Migration
{
    public function up()
    {
        $this->createTable('beacon', array(
            'id' => 'int(20) NOT NULL AUTO_INCREMENT PRIMARY KEY',
            'user_id' => 'int(11) NOT NULL',
            'beacon_id' => 'int(11) NOT NULL',
            'distance' => 'varchar(100) DEFAULT NULL',
            'datetime' => 'datetime DEFAULT NULL',
        ), '');

        $this->createTable('sensor', array(
            'id' => 'int(20) NOT NULL AUTO_INCREMENT PRIMARY KEY',
            'user_id' => 'int(11) NOT NULL',
            'datetime' => 'datetime DEFAULT NULL',
            'accelX' => 'varchar(100) DEFAULT NULL',
            'accelY' => 'varchar(100) DEFAULT NULL',
            'accelZ' => 'varchar(100) DEFAULT NULL',
            'GyroX' => 'varchar(100) DEFAULT NULL',
            'GyroY' => 'varchar(100) DEFAULT NULL',
            'GyroZ' => 'varchar(100) DEFAULT NULL',
            'CompX' => 'varchar(100) DEFAULT NULL',
            'CompY' => 'varchar(100) DEFAULT NULL',
            'CompZ' => 'varchar(100) DEFAULT NULL',
        ), '');
    }

    public function down()
    {
        echo "m160607_173810_beaconsensor4 cannot be reverted.\n";

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
