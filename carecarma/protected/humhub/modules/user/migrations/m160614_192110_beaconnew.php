<?php

use yii\db\Migration;

class m160614_192110_beaconnew extends Migration
{
    public function up()
    {
        $this->createTable('beacon', array(
            'id' => 'int(20) NOT NULL AUTO_INCREMENT PRIMARY KEY',
            'user_id' => 'int(11) NOT NULL',
            'beacon_id' => 'varchar(100) DEFAULT NULL',
            'distance' => 'varchar(100) DEFAULT NULL',
            'datetime' => 'datetime DEFAULT NULL',
        ), '');
    }

    public function down()
    {
        echo "m160614_192110_beaconnew cannot be reverted.\n";

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
