<?php

use yii\db\Migration;

class m160415_193734_contact_add_isRead extends Migration
{
    public function up()
    {
        $this->addColumn('contact', 'isRead', 'varchar(255) NOT NULL DEFAULT "false"');
    }

    public function down()
    {
        echo "m160415_193734_contact_add_isRead cannot be reverted.\n";

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
