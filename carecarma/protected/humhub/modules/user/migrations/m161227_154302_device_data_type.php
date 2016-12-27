<?php

use yii\db\Migration;

class m161227_154302_device_data_type extends Migration
{
    public function up()
    {
        $this->alterColumn('sensor', 'accelX', 'float(100) DEFAULT NULL');
        $this->alterColumn('sensor', 'accelY', 'float(100) DEFAULT NULL');
        $this->alterColumn('sensor', 'accelZ', 'float(100) DEFAULT NULL');
        $this->alterColumn('sensor', 'GyroX', 'float(100) DEFAULT NULL');
        $this->alterColumn('sensor', 'GyroY', 'float(100) DEFAULT NULL');
        $this->alterColumn('sensor', 'GyroZ', 'float(100) DEFAULT NULL');
        $this->alterColumn('sensor', 'CompX', 'float(100) DEFAULT NULL');
        $this->alterColumn('sensor', 'CompY', 'float(100) DEFAULT NULL');
        $this->alterColumn('sensor', 'CompZ', 'float(100) DEFAULT NULL');
        $this->alterColumn('sensor', 'time', 'int(100) DEFAULT NULL');

        $this->alterColumn('heartrate', 'heartrate', 'float(100) DEFAULT NULL');
        $this->alterColumn('heartrate', 'time', 'int(100) DEFAULT NULL');
    }

    public function down()
    {
        $this->alterColumn('sensor', 'accelX', 'varchar(100) DEFAULT NULL');
        $this->alterColumn('sensor', 'accelY', 'varchar(100) DEFAULT NULL');
        $this->alterColumn('sensor', 'accelZ', 'varchar(100) DEFAULT NULL');
        $this->alterColumn('sensor', 'GyroX', 'varchar(100) DEFAULT NULL');
        $this->alterColumn('sensor', 'GyroY', 'varchar(100) DEFAULT NULL');
        $this->alterColumn('sensor', 'GyroZ', 'varchar(100) DEFAULT NULL');
        $this->alterColumn('sensor', 'CompX', 'varchar(100) DEFAULT NULL');
        $this->alterColumn('sensor', 'CompY', 'varchar(100) DEFAULT NULL');
        $this->alterColumn('sensor', 'CompZ', 'varchar(100) DEFAULT NULL');
        $this->alterColumn('sensor', 'time', 'varchar(255) DEFAULT NULL');

        $this->alterColumn('heartrate', 'heartrate', 'varchar(255) DEFAULT NULL');
        $this->alterColumn('heartrate', 'time', 'varchar(255) DEFAULT NULL');
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
