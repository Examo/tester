<?php

use yii\db\Migration;

/**
 * Handles the creation of table `user_points`.
 */
class m180619_121506_create_user_points_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('user_points', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'course_id' => $this->integer()->notNull(),
            'element_id' => $this->integer()->notNull(),
            'points' => $this->integer()->notNull()
        ]);
        $this->createIndex('idx_user_points_user', 'user_points', 'user_id');
        $this->addForeignKey('fk_user_points_user', 'user_points', 'user_id', 'user', 'id', 'CASCADE');

        $this->createIndex('idx_user_points_course', 'user_points', 'course_id');
        $this->addForeignKey('fk_user_points_course', 'user_points', 'course_id', 'course', 'id', 'CASCADE');

    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropIndex('idx_user_points_user', 'user_points');
        $this->dropForeignKey('fk_user_points_user', 'user');

        $this->dropIndex('idx_user_points_course', 'user_points');
        $this->dropForeignKey('fk_user_points_course', 'course');

        $this->dropTable('user_points');
    }
}
