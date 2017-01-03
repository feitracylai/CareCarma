<?php

use yii\db\Migration;

class m170103_163213_time_datatype extends Migration
{
    public function up()
    {
        $this->alterColumn('sensor', 'time', 'varchar(255) DEFAULT NULL');
        $this->alterColumn('heartrate', 'time', 'varchar(255) DEFAULT NULL');
    }

    public function down()
    {
        $this->alterColumn('sensor', 'time', 'int(100) DEFAULT NULL');
        $this->alterColumn('heartrate', 'time', 'int(100) DEFAULT NULL');
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
