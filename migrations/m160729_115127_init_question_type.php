<?php

use yii\db\Migration;

class m160729_115127_init_question_type extends Migration
{
    public function up()
    {
        $this->insert( 'question_type', [
            'name' => 'Выбор единственного варианта',
            'sysname' => 'select_one',
        ] );

        $this->insert( 'question_type', [
            'name' => 'Выбор нескольких вариантов',
            'sysname' => 'select_multiple',
        ] );

        $this->insert( 'question_type', [
            'name' => 'Ручной ввод ответа',
            'sysname' => 'text_short',
        ] );

        $this->insert( 'question_type', [
            'name' => 'Письменная работа',
            'sysname' => 'text_long',
        ] );

        $this->insert( 'question_type', [
            'name' => 'Диктант',
            'sysname' => 'dictation',
        ] );
    }

    public function down()
    {
        echo "m160729_115127_init_question_type cannot be reverted.\n";

        return false;
    }
}
