<?php

use yii\db\Migration;

class m160928_145333_decide_add_care extends Migration
{
    public function up()
    {
        $this->addColumn('space_membership', 'add_care', 'int(1) DEFAULT NULL');
    }

    public function down()
    {
        echo "m160928_145333_decide_add_care cannot be reverted.\n";

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
