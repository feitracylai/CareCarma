<?php

use yii\db\Migration;

class m160317_214624_contact extends Migration
{
    /**
     *
     */
    public function up()
    {
        $this->addColumn('contact', 'AndroidId', 'varchar(255) DEFAULT NULL');


    }

    public function down()
    {
        echo "m160317_214624_contact cannot be reverted.\n";

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
