<?php

use yii\db\Migration;
use yii\db\Schema;

class m160908_131026_discipline extends Migration
{
    public function up()
    {
        $this->createTable('discipline', [
            'id' => Schema::TYPE_PK,
            'name' => Schema::TYPE_STRING . ' NOT NULL',
            'position' => Schema::TYPE_INTEGER
        ]);

        $this->addColumn(
            'course',
            'discipline_id',
            Schema::TYPE_INTEGER . ' NOT NULL'
        );
        $this->execute('SET FOREIGN_KEY_CHECKS=0');
        $this->addForeignKey(
            'fk_course_discipline',
            'course', 'discipline_id',
            'discipline', 'id'
        );
        $this->execute('SET FOREIGN_KEY_CHECKS=1');
    }

    public function down()
    {
        $this->dropForeignKey('fk_course_discipline', 'course');
        $this->dropColumn('course', 'discipline_id');
        $this->dropTable('discipline');
    }
}
