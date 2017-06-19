<?php

namespace app\models\ar;

use Yii;

/**
 * This is the model class for table "discipline".
 *
 * @property integer $id
 * @property string $name
 * @property integer $position
 *
 * @property Course[] $courses
 */
class Discipline extends \app\components\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'discipline';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['position'], 'integer'],
            [['name'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'position' => 'Position',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCourses()
    {
        return $this->hasMany(Course::className(), ['discipline_id' => 'id'])->inverseOf('discipline');
    }
}
