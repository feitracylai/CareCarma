<?php

use yii\db\Migration;

class m170227_160655_add_glass_primary extends Migration
{
    public function up()
    {
        $this->addColumn('contact', 'glass_primary_number', 'INT(1) DEFAULT 0');
    }

    public function down()
    {
        echo "m170227_160655_add_glass_primary cannot be reverted.\n";

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
