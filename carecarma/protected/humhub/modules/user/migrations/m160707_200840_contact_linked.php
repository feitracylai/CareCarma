<?php

use yii\db\Migration;

class m160707_200840_contact_linked extends Migration
{
    public function up()
    {
        $this->addColumn('contact', 'linked', 'tinyint(4) DEFAULT 1 AFTER contact_user_id');
    }

    public function down()
    {
        echo "m160707_200840_contact_linked cannot be reverted.\n";

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
