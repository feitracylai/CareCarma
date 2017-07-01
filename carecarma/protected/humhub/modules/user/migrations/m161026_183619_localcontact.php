<?php

use yii\db\Migration;

class m161026_183619_localcontact extends Migration
{
    public function up()
    {
        $this->createTable('localcontact', array(
            'contact_id' => 'pk',
            'user_id' => 'varchar(255) DEFAULT NULL',
            'name' => 'varchar(255) DEFAULT NULL',
            'email' => 'varchar(255) DEFAULT NULL',
            'phone_number1' => 'varchar(100) NOT NULL',
            'phone_number2' => 'varchar(100) NOT NULL',
            'phone_number3' => 'varchar(100) NOT NULL',
        ), '');
    }

    public function down()
    {
        echo "m161026_183619_localcontact cannot be reverted.\n";

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
