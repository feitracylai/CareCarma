<?php

use yii\db\Migration;

class m160615_203029_device_add_password extends Migration
{
    public function up()
    {
        $this->addColumn('device', 'temp_password', 'varchar(255) DEFAULT NULL');
    }

    public function down()
    {
        echo "m160615_203029_device_add_password cannot be reverted.\n";

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
