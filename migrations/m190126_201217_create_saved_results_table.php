<?php

use yii\db\Migration;

/**
 * Handles the creation of table `saved_results`.
 */
class m190126_201217_create_saved_results_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('saved_results', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'course_id' => $this->integer()->notNull(),
            'exercise_id' => $this->integer()->notNull(),
            'challenge_id' => $this->integer()->notNull(),
            'answers' => $this->text()->notNull(),
            'hints' => $this->text()->notNull(),
            'result' => $this->text()->notNull(),
            'points' => $this->text()->notNull(),
            'all_user_points' => $this->integer()->notNull(),
            'all_points' => $this->integer()->notNull(),
            'mark' => $this->integer()->notNull(),
            'time' => $this->integer()->notNull(),
            'link' => $this->text()->notNull()
        ]);

        $this->createIndex('idx_saved_results_user', 'saved_results', 'user_id');
        $this->addForeignKey('fk_saved_results_user', 'saved_results', 'user_id', 'user', 'id', 'CASCADE');

    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropIndex('idx_saved_results_user', 'saved_results');
        $this->dropForeignKey('fk_saved_results_user', 'user');

        $this->dropTable('saved_results');
    }
}
