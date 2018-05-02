<?php

namespace app\models\ar;

use Yii;

/**
 * This is the model class for table "learn_object".
 *
 * @property integer $id
 * @property string $object
 */
class LearnObject extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'learn_object';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['object'], 'required'],
            [['object'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'object' => 'Object',
        ];
    }
}
