<?php

use yii\db\Migration;

class m160502_153612_activate_device extends Migration
{
    public function up()
    {
        $this->addColumn('device', 'user_id', 'int(11) DEFAULT NULL');
        $this->execute('
            DROP TRIGGER IF EXISTS after_device_update;
            CREATE TRIGGER after_device_update
            AFTER UPDATE ON device
            FOR EACH ROW UPDATE user
            SET gcmId=new.gcmId
            WHERE device_id=new.device_id
        ');
    }

    public function down()
    {
        echo "m160502_153612_activate_device cannot be reverted.\n";

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
