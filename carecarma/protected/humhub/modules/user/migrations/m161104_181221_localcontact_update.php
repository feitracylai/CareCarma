<?php

use yii\db\Migration;

class m161104_181221_localcontact_update extends Migration
{
    public function up()
    {
        $this->addColumn('localcontact', 'token', 'varchar(255) DEFAULT NULL');
    }

    public function down()
    {
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
