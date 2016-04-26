<?php

use yii\db\Migration;

class m160426_142030_create_trigger_device extends Migration
{
    public function up()
    {
        $this->addColumn('user','device_id', 'varchar(45) DEFAULT NULL');

        $this->execute('
            CREATE TRIGGER after_device_update
            AFTER UPDATE ON device
            FOR EACH ROW UPDATE user
            SET gcmId=new.gcmId
            WHERE device_id=new.device_id
        ');
    }

    public function down()
    {
        $this->dropTable('trigger_device');
    }
}
