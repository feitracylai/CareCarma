<?php

use yii\db\Migration;

class m160927_040513_contact_update extends Migration
{
    public function up()
    {
        $this->addColumn('contact', 'watch_primary_number', 'int(1) DEFAULT 0');
        $this->addColumn('contact', 'phone_primary_number', 'int(1) DEFAULT 1');
    }

    public function down()
    {
        echo "m160927_040513_contact_update cannot be reverted.\n";

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
