<?php

use yii\db\Migration;

class m170110_230236_device_add_type extends Migration
{
    public function up()
    {
        $this->addColumn('device', 'hardware_id', 'varchar(15) DEFAULT NULL');
        $this->addColumn('device', 'type', 'varchar(255) DEFAULT NULL');
        $this->addColumn('device', 'model', 'varchar(255) DEFAULT NULL');
        $this->addColumn('device', 'activate', 'int(1) DEFAULT 0');

    }

    public function down()
    {
        echo "m170110_230236_device_add_type cannot be reverted.\n";

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
