<?php

use yii\db\Migration;

class m160525_170407_combine_contact_user extends Migration
{
    public function up()
    {
        $this->addColumn('contact', 'contact_user_id', 'int(11) DEFAULT NULL');
        $this->addColumn('contact', 'relation', 'varchar(255) DEFAULT NULL');
    }

    public function down()
    {
        echo "m160525_170407_combine_contact_user cannot be reverted.\n";

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
