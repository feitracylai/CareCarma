<?php

use yii\db\Migration;

class m161220_210521_device_id_debug extends Migration
{
    public function up()
    {
        $this->alterColumn('sensor', 'device_id', 'varchar(11) DEFAULT 0');
        $this->alterColumn('beacon', 'device_id', 'varchar(11) DEFAULT 0');
        $this->alterColumn('heartrate', 'device_id', 'varchar(11) DEFAULT 0');
    }

    public function down()
    {
        $this->alterColumn('sensor', 'device_id', 'int(11) DEFAULT 0');
        $this->alterColumn('beacon', 'device_id', 'int(11) DEFAULT 0');
        $this->alterColumn('heartrate', 'device_id', 'int(11) NOT NULL');
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
