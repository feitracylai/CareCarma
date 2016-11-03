<?php

use yii\db\Migration;

class m160421_154001_create_device_table extends Migration
{
    public function up()
    {
        $this->createTable('device', array(
            'id' => 'pk',
            'device_id' => 'varchar(45) DEFAULT NULL',
            'gcmId' => 'varchar(255) DEFAULT NULL',
        ), '');
		
		$this->addColumn('user','device_id', 'varchar(45) DEFAULT NULL');
    }

    public function down()
    {
        echo "m160421_154001_create_device_table does not support migration down.\n";
        return false;
    }
}
