<?php

use yii\db\Migration;

class m161202_204036_heartrate extends Migration
{
    public function up()
    {
        $this->createTable('heartrate', array(
            'id' => 'pk',
            'user_id' => 'int(11) NOT NULL',
            'device_id' => 'int(11) NOT NULL',
            'heartrate' => 'varchar(255) DEFAULT NULL',
            'datetime' => 'datetime DEFAULT NULL',
        ), '');
    }

    public function down()
    {
        echo "m161202_204036_heartrate cannot be reverted.\n";

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
