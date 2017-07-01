<?php

use yii\db\Migration;

class m160610_195256_modify_contact_phones extends Migration
{
    public function up()
    {
        $this->addColumn('device', 'phone', 'varchar(255) DEFAULT NULL');
        $this->addColumn('contact', 'device_phone', 'varchar(255) DEFAULT NULL');
        $this->addColumn('contact', 'home_phone', 'varchar(255) DEFAULT NULL');
        $this->addColumn('contact', 'work_phone', 'varchar(255) DEFAULT NULL');
    }

    public function down()
    {
        echo "m160610_195256_modify_contact_phones cannot be reverted.\n";

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
