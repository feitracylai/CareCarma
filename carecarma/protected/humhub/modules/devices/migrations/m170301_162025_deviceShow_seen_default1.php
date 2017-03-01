<?php

use yii\db\Migration;

class m170301_162025_deviceShow_seen_default1 extends Migration
{
    public function up()
    {
        $this->alterColumn('device_show', 'seen', 'int(11) DEFAULT 1');
    }

    public function down()
    {
        $this->alterColumn('device_show', 'seen', 'int(11) DEFAULT NULL');
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
