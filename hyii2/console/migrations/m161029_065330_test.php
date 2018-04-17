<?php

use yii\db\Migration;

class m161029_065330_test extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }
        $this->createTable('{{%test}}', [
            'id' => $this->primaryKey(),
            'price' => $this->string(32)->notNull(),
            'is_active' => $this->string()->notNull(),
        ], $tableOptions);
    }

    public function down()
    {
        echo "m161029_065330_test cannot be reverted.\n";

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
