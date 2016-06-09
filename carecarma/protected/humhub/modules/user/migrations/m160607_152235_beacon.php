<?php

use yii\db\Migration;

class m160607_152235_beacon extends Migration
{
    public function up()
    {
        $this->createTable('beacon', array(
            'id' => 'pk',
            'user_id' => 'int(11) NOT NULL',
            'beacon_id' => 'varchar(255) NOT NULL',
            'distance' => 'varchar(255) DEFAULT NULL',
            'datetime' => 'varchar(255) DEFAULT NULL',
        ), '');
    }

    public function down()
    {
        echo "m160607_152235_beacon cannot be reverted.\n";

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
