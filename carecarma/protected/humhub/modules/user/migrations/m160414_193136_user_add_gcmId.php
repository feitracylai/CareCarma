<?php

use yii\db\Migration;

class m160414_193136_user_add_gcmId extends Migration
{
    public function up()
    {
        $this->addColumn('user', 'gcmId', 'varchar(255) DEFAULT NULL');
    }

    public function down()
    {
        echo "m160414_193136_user_add_gcmId cannot be reverted.\n";

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
