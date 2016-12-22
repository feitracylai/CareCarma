<?php

use yii\db\Migration;

class m161221_212628_ClassLabels_LastTimeRead extends Migration
{
    public function up()
    {
        $this->createTable('ClassLabels', array(
            'id' => 'pk',
            'user_id' => 'int(11) DEFAULT NULL',
            'device_id' => 'varchar(11) DEFAULT 0',
            'activityLabel' => 'int(11) DEFAULT NULL',
            'turnLabel' => 'int(11) DEFAULT NULL',
            'beaconLabel' => 'varchar(255) DEFAULT NULL',
            'datetime' => 'datetime DEFAULT NULL',
            'time' => 'varchar(255) DEFAULT NULL',
            'stepsLabel' => 'int(11) DEFAULT NULL',
            'heartrateLabel' => 'int(11) DEFAULT NULL',
        ), '');

        $this->createTable('LastTimeRead', array(
            'id' => 'pk',
            'user_id' => 'int(11) DEFAULT NULL',
            'device_id' => 'varchar(11) DEFAULT 0',
            'datetime' => 'datetime DEFAULT NULL',
            'time' => 'varchar(255) DEFAULT NULL',
        ), '');
    }

    public function down()
    {
        echo "m161221_212628_ClassLabels_LastTimeRead cannot be reverted.\n";

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
