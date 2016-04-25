<?php

use yii\db\Migration;

class m160422_201214_device_create_trigger extends Migration
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
        $this->execute('
                        DROP TRIGGER IF EXISTS after_device_update
                ');
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
