<?php

use yii\db\Migration;

class m170216_210634_device_report_notify extends Migration
{
    public function up()
    {
        $this->createTable('device_show', array(
            'id' => 'pk',
            'space_id' => 'int(11) DEFAULT NULL',
            'report_user_id' => 'int(11) DEFAULT NULL',
            'hardware_id' => 'varchar(15) DEFAULT NULL',
            'user_id' => 'int(11) DEFAULT NULL',
            'updated_at' => 'datetime DEFAULT CURRENT_TIMESTAMP',
            'seen' => 'int(11) DEFAULT NULL',
        ), '');

        $this->execute('
        CREATE TRIGGER `device_show_update` AFTER UPDATE ON `LastTimeReadHeart`
        FOR EACH ROW UPDATE device_show SET seen=0, updated_at = Now() 
        WHERE hardware_id=NEW.hardware_id
        ');

        $this->execute('
        CREATE TRIGGER `device_show_update2` AFTER UPDATE ON `LastTimeReadSteps`
        FOR EACH ROW UPDATE device_show SET seen=0, updated_at = Now() 
        WHERE hardware_id=NEW.hardware_id
        ');
    }

    public function down()
    {
        echo "m170216_210634_device_report_notify cannot be reverted.\n";

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
