<?php

use yii\db\Migration;

class m160907_115407_question_type_assoc extends Migration
{
    public function up()
    {
        $this->insert('question_type', [
            'name' => 'Ассоциации',
            'sysname' => 'assoc'
        ]);
    }

    public function down()
    {
        $this->delete('question_type', [
            'sysname' => 'assoc'
        ]);
    }
}
