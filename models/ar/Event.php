<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 17.02.2018
 * Time: 21:07
 */

namespace app\models\ar;


class Event extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'event';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['course_id', 'title', 'start', 'end'], 'required'],
            [['title'], 'string', 'max' => 255],
            [['color'], 'string', 'max' => 7],
            [['start', 'end'], 'date', 'format' => 'yyyy-M-d H:m:s'],
            [['course_id'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Title',
            'color' => 'Color',
            'start' => 'Start',
            'end' => 'End',
            'course_id' => 'Course ID'
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCourse()
    {
        return $this->hasOne(Course::className(), ['id' => 'course_id'])->inverseOf('events');
    }

}