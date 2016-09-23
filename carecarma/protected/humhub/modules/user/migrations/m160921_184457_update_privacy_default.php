<?php

use yii\db\Migration;

class m160921_184457_update_privacy_default extends Migration
{
    public function up()
    {
        $this->alterColumn('profile', 'privacy', 'INT(4) DEFAULT 0');
    }

    public function down()
    {
        echo "m160921_184457_update_privacy_default cannot be reverted.\n";

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
