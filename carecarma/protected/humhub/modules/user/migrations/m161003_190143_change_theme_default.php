<?php

use yii\db\Migration;

class m161003_190143_change_theme_default extends Migration
{
    public function up()
    {
        $this->alterColumn('user', 'theme', 'varchar(255) DEFAULT "theme-1.css"');
    }

    public function down()
    {
        echo "m161003_190143_change_theme_default cannot be reverted.\n";

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
