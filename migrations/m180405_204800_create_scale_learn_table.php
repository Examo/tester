<?php

use yii\db\Migration;

/**
 * Handles the creation of table `scale_learn`.
 */
class m180405_204800_create_scale_learn_table extends Migration
{
    public function up()
    {
        $this->createTable('scale_learn', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'course_id' => $this->integer()->notNull(),
            'week_id' => $this->integer()->notNull(),
            'monday' => $this->text()->notNull(),
            'tuesday' => $this->text()->notNull(),
            'wednesday' => $this->text()->notNull(),
            'thursday' => $this->text()->notNull(),
            'friday' => $this->text()->notNull(),
            'saturday' => $this->text()->notNull(),
            'sunday' => $this->text()->notNull(),
        ]);

        $this->createIndex('idx_scale_learn_user', 'scale_learn', 'user_id');
        $this->addForeignKey('fk_scale_learn_user', 'scale_learn', 'user_id', 'user', 'id', 'CASCADE');

        $this->createIndex('idx_scale_learn_course', 'scale_learn', 'course_id');
        $this->addForeignKey('fk_scale_learn_course', 'scale_learn', 'course_id', 'course', 'id', 'CASCADE');

    }
    
    public function down()
    {
        $this->dropIndex('idx_scale_learn_user', 'scale_learn');
        $this->dropForeignKey('fk_scale_learn_user', 'user');

        $this->dropIndex('idx_scale_learn_course', 'scale_learn');
        $this->dropForeignKey('fk_scale_learn_course', 'course');

        $this->dropTable('scale_learn');
    }
}
