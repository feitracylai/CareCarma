<?php

use yii\db\Migration;

class m160427_143912_activate_device_trigger extends Migration
{
    public function up()
    {
        $this->addColumn('user', 'activated', 'tinyint(4) DEFAULT NULL');
        $this->addColumn('device', 'user_id', 'int(11) NOT NULL');
        $this->execute('
            DROP TRIGGER IF EXISTS after_device_update;
            CREATE TRIGGER after_device_update
            AFTER UPDATE ON device
            FOR EACH ROW UPDATE user
            SET gcmId=new.gcmId,activated=0
            WHERE device_id=new.device_id
        ');
    }

    public function down()
    {
        echo "m160427_143912_activate_device_trigger cannot be reverted.\n";

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
