<?php

use yii\db\Migration;

/**
 * Handles the creation of table `challenges_weeks`.
 */
class m180309_204116_create_challenges_weeks_table_new extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('challenges_weeks', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'course_id' => $this->integer()->notNull(),
            'element_id' => $this->integer()->notNull(),
            'week_id' => $this->integer()->notNull(),
            'challenges' => $this->text()->notNull(),
        ]);

        $this->createIndex('idx_challenges_weeks_user', 'challenges_weeks', 'user_id');
        $this->addForeignKey('fk_challenges_weeks_user', 'challenges_weeks', 'user_id', 'user', 'id', 'CASCADE');

        $this->createIndex('idx_challenges_weeks_course', 'challenges_weeks', 'course_id');
        $this->addForeignKey('fk_challenges_weeks_course', 'challenges_weeks', 'course_id', 'course', 'id', 'CASCADE');

        $this->createIndex('idx_challenges_element_id', 'challenges_weeks', 'element_id');
        $this->addForeignKey('fk_challenges_element_id', 'challenges_weeks', 'element_id', 'element', 'id', 'CASCADE');
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropIndex('idx_challenges_weeks_user', 'challenges_weeks');
        $this->dropForeignKey('fk_challenges_weeks_user', 'user');

        $this->dropIndex('idx_challenges_weeks_course', 'challenges_weeks');
        $this->dropForeignKey('fk_challenges_weeks_course', 'course');

        $this->dropIndex('idx_challenges_element_id', 'challenges_weeks');
        $this->dropForeignKey('fk_challenges_element_id', 'element');

        $this->dropTable('challenges_weeks');
    }
}
