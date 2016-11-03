<?php

use yii\db\Migration;

class m160725_213103_mobile_token extends Migration
{
    public function up()
    {

        $this->createTable('mobile_token', array(
			'id' => 'pk',
            'device_token' => 'varchar(255) NOT NULL',
            'user_id' => 'int(11) NOT NULL',
			'created_at' => 'datetime NOT NULL DEFAULT CURRENT_TIMESTAMP',
                ), '');
    }

    public function down()
    {
        echo "m160725_213103_mobile_token cannot be reverted.\n";

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
