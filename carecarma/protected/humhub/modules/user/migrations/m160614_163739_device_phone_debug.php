<?php

use yii\db\Migration;

class m160614_163739_device_phone_debug extends Migration
{
    public function up()
    {
        $this->alterColumn('device', 'phone', 'varchar(255) NOT NULL');
    }

    public function down()
    {
        echo "m160614_163739_device_phone_debug cannot be reverted.\n";

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
