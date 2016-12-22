<?php

use yii\db\Migration;

class m161210_033518_heartrate_time extends Migration
{
    public function up()
    {
        $this->alterColumn('heartrate', 'datetime', 'datetime(6)');
        $this->addColumn('heartrate', 'time', 'varchar(255) DEFAULT NULL');
    }

    public function down()
    {
        echo "m161210_033518_heartrate_time cannot be reverted.\n";

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
