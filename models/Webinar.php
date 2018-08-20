<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "webinar".
 *
 * @property integer $id
 */
class Webinar extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'webinar';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            //,
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
        ];
    }
}
