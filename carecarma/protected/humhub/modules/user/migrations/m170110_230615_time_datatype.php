<?php

use yii\db\Migration;

class m170110_230615_time_datatype extends Migration
{
    public function up()
    {
        $this->alterColumn('sensor', 'time', 'BIGINT(13) DEFAULT NULL');
        $this->alterColumn('heartrate', 'time', 'BIGINT(13) DEFAULT NULL');
        $this->alterColumn('beacon', 'time', 'BIGINT(13) DEFAULT NULL');

        $this->renameColumn('sensor', 'device_id', 'hardware_id');
        $this->alterColumn('sensor', 'hardware_id', 'varchar(15) DEFAULT NULL');
        $this->renameColumn('heartrate', 'device_id', 'hardware_id');
        $this->alterColumn('heartrate', 'hardware_id', 'varchar(15) DEFAULT NULL');
        $this->renameColumn('beacon', 'device_id', 'hardware_id');
        $this->alterColumn('beacon', 'hardware_id', 'varchar(15) DEFAULT NULL');
    }

    public function down()
    {
        $this->alterColumn('sensor', 'time', 'varchar(255) DEFAULT NULL');
        $this->alterColumn('heartrate', 'time', 'varchar(255) DEFAULT NULL');
        $this->alterColumn('beacon', 'time', 'varchar(255) DEFAULT NULL');
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
