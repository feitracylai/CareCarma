<?php

use yii\db\Migration;

class m170106_155649_carecarma_watch extends Migration
{
    public function up()
    {
        $this->addColumn('contact', 'carecarma_watch_number', 'int(1) DEFAULT 0');
    }

    public function down()
    {
        echo "m170106_155649_carecarma_watch cannot be reverted.\n";

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
