<?php

use yii\db\Migration;

/**
 * Class m180111_201702_difficult_subjects
 */
class m180111_201702_difficult_subjects extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->createTable('difficult_subjects', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'subject_id' => $this->integer()->notNull(),
            'points' => $this->integer()->notNull()
        ]);

        $this->createIndex('idx_difficult_subjects_user', 'difficult_subjects', 'user_id');
        $this->addForeignKey('fk_difficult_subjects_user', 'difficult_subjects', 'user_id', 'user', 'id', 'CASCADE');

        $this->createIndex('idx_question_has_subject', 'difficult_subjects', 'subject_id');
        $this->addForeignKey('fk_question_has_subject', 'difficult_subjects', 'subject_id', 'question_has_subject', 'subject_id', 'CASCADE');
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropIndex('idx_difficult_subjects_user', 'difficult_subjects');
        $this->dropForeignKey('fk_difficult_subjects_user', 'user');

        $this->dropIndex('idx_question_has_subject', 'difficult_subjects');
        $this->dropForeignKey('fk_question_has_subject', 'question_has_subject');

        $this->dropTable('difficult_subjects');

        //echo "m180111_201702_difficult_subjects cannot be reverted.\n";
        // return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180111_201702_difficult_subjects cannot be reverted.\n";

        return false;
    }
    */
}
