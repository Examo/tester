<?php

use yii\db\Migration;

/**
 * Handles the creation of table `question_settings`.
 */
class m190724_182518_create_question_settings_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('question_settings', [
            'id' => $this->primaryKey(),
            'type_id' => $this->integer()->notNull(),
            'settings' => $this->text(),
            'name' => $this->string(255)
        ]);

        $this->createIndex('question_settings_type_idx', 'question_settings', 'type_id');
        $this->addForeignKey(
            'fk_question_settings_type_idx',
            'question_settings',
            'type_id',
            'question_type',
            'id',
            'CASCADE'
        );

        $this->addColumn('question', 'question_settings_id', $this->integer());
        $this->createIndex('idx_question_question_settings', 'question', 'question_settings_id');
        $this->addForeignKey(
            'fk_question_question_settings',
            'question',
            'question_settings_id',
            'question_settings',
            'id',
            'CASCADE'
        );
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropForeignKey(
            'fk_question_question_settings',
            'question'
        );
        $this->dropIndex(
            'idx_question_question_settings',
            'question'
        );
        $this->dropColumn('question', 'question_settings_id');


        $this->dropForeignKey(
            'fk_question_settings_type_idx',
            'question_settings'
        );
        $this->dropIndex(
            'question_settings_type_idx',
            'question_settings'
        );
        $this->dropTable('question_settings');
    }
}
