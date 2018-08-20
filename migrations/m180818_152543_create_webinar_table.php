<?php

use yii\db\Migration;

/**
 * Handles the creation of table `webinar`.
 */
class m180818_152543_create_webinar_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('webinar', [
            'id' => $this->primaryKey(),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('webinar');
    }
}
