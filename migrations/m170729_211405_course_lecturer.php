<?php

use yii\db\Migration;
use yii\db\Schema;

class m170729_211405_course_lecturer extends Migration
{
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {
        $this->createTable('course_lecturer', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'course_id' => $this->integer()->notNull(),
        ]);

        $this->createIndex('idx_course_lecturer_course', 'course_lecturer', 'course_id');
        $this->addForeignKey('fk_course_lecturer_course', 'course_lecturer', 'course_id', 'course', 'id', 'CASCADE');

        $this->createIndex('idx_course_lecturer_user', 'course_lecturer', 'user_id');
        $this->addForeignKey('fk_course_lecturer_user', 'course_lecturer', 'user_id', 'user', 'id','CASCADE');
    }

    public function down()
    {
        $this->dropIndex('idx_course_lecturer_course','course_lecturer');
        $this->dropForeignKey('fk_course_lecturer_course', 'course');

        $this->dropIndex('idx_course_lecturer_user','course_lecturer');
        $this->dropForeignKey('fk_course_lecturer_user', 'user');

        $this->dropTable('course_lecturer');
    }

    /*public function safeUp()
    {

    }

    public function safeDown()
    {
        echo "m170729_211405_course_lecturer cannot be reverted.\n";

        return false;
    }
*/



}
