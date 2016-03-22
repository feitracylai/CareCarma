<?php

use yii\db\Migration;

class m160314_165413_contact extends Migration
{
    public function up()
    {
        $this->createTable('contact', array(
            'contact_id' => 'pk',
            'contact_first' => 'varchar(255) DEFAULT NULL',
            'contact_last' => 'varchar(255) DEFAULT NULL',
            'contact_mobile' => 'varchar(255) DEFAULT NULL',
            'contact_email' => 'varchar(100) NOT NULL',
            'nickname' => 'varchar(255) NOT NULL',
            'user_id' => 'varchar(255) DEFAULT NULL',
        ), '');



    }

    public function down()
    {
        echo "m160314_165413_contact cannot be reverted.\n";

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
