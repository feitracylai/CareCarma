<?php

use yii\db\Migration;

class m160906_170631_add_background extends Migration
{
    public function up()
    {
        $this->addColumn('user', 'background', 'varchar(255) DEFAULT NULL');
    }

    public function down()
    {
        echo "m160906_170631_add_background cannot be reverted.\n";

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
