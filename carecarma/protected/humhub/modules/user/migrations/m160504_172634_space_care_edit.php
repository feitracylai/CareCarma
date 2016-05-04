<?php

use yii\db\Migration;

class m160504_172634_space_care_edit extends Migration
{
    public function up()
    {
        $this->addColumn('profile_field', 'care_edit', 'tinyint(4) NOT NULL DEFAULT \'1\'');
    }

    public function down()
    {
        echo "m160504_172634_space_care_edit cannot be reverted.\n";

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
