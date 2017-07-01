<?php

use yii\db\Migration;

class m161115_201724_contact_update extends Migration
{
    public function up()
    {
        $this->alterColumn('contact', 'phone_primary_number', 'int(1) DEFAULT 0');
    }

    public function down()
    {
        echo "m161115_201724_contact_update cannot be reverted.\n";

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
