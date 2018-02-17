<?php

use yii\db\Migration;

/**
 * Class m180210_203641_scale_clean_table
 */
class m180210_203641_scale_clean_table extends Migration
{
    public function safeUp()
    {
        $this->createTable('scale_clean', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'last_time' => $this->dateTime()->notNull(),
            'points' => $this->integer()->notNull(),
            'step' => $this->integer()->notNull()
        ]);

        $this->createIndex('idx_scale_clean_user', 'scale_clean', 'user_id');
        $this->addForeignKey('fk_scale_clean_user', 'scale_clean', 'user_id', 'user', 'id', 'CASCADE');
    }

    public function safeDown()
    {
        $this->dropIndex('idx_scale_clean_user', 'scale_clean');
        $this->dropForeignKey('fk_scale_clean_user', 'user');

        $this->dropTable('scale_clean');
    }
}
