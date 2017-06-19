<?php

use yii\db\Migration;

class m160921_123736_remember_hint_usage extends Migration
{
    public function up()
    {
        $this->addColumn('answer', 'hint', \yii\db\Schema::TYPE_BOOLEAN);
    }

    public function down()
    {
        $this->dropColumn('answer', 'hint');
    }
}
