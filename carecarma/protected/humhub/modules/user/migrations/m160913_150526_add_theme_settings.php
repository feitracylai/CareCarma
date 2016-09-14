<?php

use yii\db\Migration;

class m160913_150526_add_theme_settings extends Migration
{
    public function up()
    {
        $this->addColumn('user', 'theme', 'varchar(255) DEFAULT NULL');
    }

    public function down()
    {
        echo "m160913_150526_add_theme_settings cannot be reverted.\n";

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
