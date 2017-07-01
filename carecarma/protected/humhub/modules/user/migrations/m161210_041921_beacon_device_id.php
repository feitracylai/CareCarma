<?php

use yii\db\Migration;

class m161210_041921_beacon_device_id extends Migration
{
    public function up()
    {
        $this->addColumn('beacon', 'device_id', 'int(11) DEFAULT 0');
    }

    public function down()
    {
        echo "m161210_041921_beacon_device_id cannot be reverted.\n";

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
