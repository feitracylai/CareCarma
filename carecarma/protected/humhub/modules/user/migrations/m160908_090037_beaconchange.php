<?php

use yii\db\Migration;

class m160908_090037_beaconchange extends Migration
{
    public function up()
    {
        $this->addColumn('beacon', 'time', 'varchar(255) DEFAULT NULL');
    }

    public function down()
    {
        echo "m160908_090037_beaconchange cannot be reverted.\n";

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
