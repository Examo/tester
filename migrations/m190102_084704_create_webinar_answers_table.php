<?php

use yii\db\Migration;

/**
 * Handles the creation of table `webinar_answers`.
 */
class m190102_084704_create_webinar_answers_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('webinar_answers', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'webinar_exercise_id' => $this->integer()->notNull(),
            'challenge_id' => $this->integer()->notNull(),
            'question_id' => $this->integer()->notNull(),
            'answer_correctness' => $this->text()->notNull(),
            'answer_indicator' => $this->text()->notNull()
        ]);

        $this->createIndex('idx_webinar_answers_user', 'webinar_answers', 'user_id');
        $this->addForeignKey('fk_webinar_answers_user', 'webinar_answers', 'user_id', 'user', 'id', 'CASCADE');
    }

    //user_id, webinar_exercise_id, challenge_id, question_id, answer_correctness (true/false), answer_indicator

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropIndex('idx_webinar_answers_user', 'webinar_answers');
        $this->dropForeignKey('fk_webinar_answers_user', 'user');

        $this->dropTable('webinar_answers');
    }
}
