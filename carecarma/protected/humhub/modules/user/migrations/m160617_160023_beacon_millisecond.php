<?php

use yii\db\Migration;

class m160617_160023_beacon_millisecond extends Migration
{
    public function up()
    {
        $this->alterColumn('beacon', 'datetime', 'datetime(6)');
    }

    public function down()
    {
        echo "m160617_160023_beacon_millisecond cannot be reverted.\n";

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
