<?php

use yii\db\Migration;

class m160627_204011_profile_privacyfield extends Migration
{
    public function up()
    {
        if (\humhub\models\Setting::isInstalled()) {

            $this->insert('profile_field_category', [
                'title' => 'Privacy',
                'sort_order' => '900',
                'visibility' => '1',
                'translation_category' => 'UserModule.models_ProfileFieldCategory',
                'is_system' => '1',
            ]);

            $row = (new \yii\db\Query())
                ->select("*")
                ->from('profile_field_category')
                ->where(['title' => 'Privacy'])
                ->one();

            $categoryId = $row['id'];
            if ($categoryId == "") {
                throw new yii\base\Exception("Could not find 'General' profile field category!");
            }

            $this->insert('profile_field', [
                'profile_field_category_id' => $categoryId,
                'field_type_class' => 'humhub\modules\user\models\fieldtype\Select',
                'field_type_config' => '{"options":"Only Me\r\nCircle Members\r\nPublic"}',
                'internal_name' => 'privacy',
                'title' => 'Profile Privacy',
                'sort_order' => '100',
                'editable' => '1',
                'visible' => '1',
                'show_at_registration' => '0',
                'required' => '0',
                'is_system' => '1',
                'care_edit' => '1',
            ]);

            // Create column for profile field
            $this->addColumn('profile', 'privacy', 'varchar(255) DEFAULT 1');
        }
    }

    public function down()
    {
        echo "m160627_204011_profile_privacyfield cannot be reverted.\n";

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
