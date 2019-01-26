<?php

use yii\db\Migration;

/**
 * Class m190111_190401_create_table_webinar_answers
 */
class m190111_190401_create_webinar_answers_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->createTable('webinar_answers', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'webinar_exercise_id' => $this->integer()->notNull(),
            'challenge_id' => $this->integer()->notNull(),
            'answers' => $this->text()->notNull(),
            'hints' => $this->text()->notNull(),
            'result' => $this->text()->notNull(),
            'points' => $this->text()->notNull(),
            'all_user_points' => $this->integer()->notNull(),
            'all_points' => $this->integer()->notNull(),
            'mark' => $this->integer()->notNull(),
            'time' => $this->integer()->notNull()
        ]);

        $this->createIndex('idx_webinar_answers_user', 'webinar_answers', 'user_id');
        $this->addForeignKey('fk_webinar_answers_user', 'webinar_answers', 'user_id', 'user', 'id', 'CASCADE');

    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropIndex('idx_webinar_answers_user', 'webinar_answers');
        $this->dropForeignKey('fk_webinar_answers_user', 'user');

        $this->dropTable('webinar_answers');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190111_190401_create_table_webinar_answers cannot be reverted.\n";

        return false;
    }
    */
}
