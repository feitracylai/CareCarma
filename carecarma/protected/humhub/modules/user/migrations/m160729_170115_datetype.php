<?php

use yii\db\Migration;

class m160729_170115_datetype extends Migration
{
    public function up()
    {
        $this->addColumn('sensor', 'time', 'varchar(255) DEFAULT NULL');
    }

    public function down()
    {
        echo "m160729_170115_datetype cannot be reverted.\n";

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
