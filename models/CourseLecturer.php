<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "course_lecturer".
 *
 * @property integer $id
 * @property integer $user_id
 * @property integer $course_id
 *
 * @property Course $course
 * @property User $user
 */
class CourseLecturer extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'course_lecturer';
    }

    public static function getList()
    {
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'course_id'], 'required'],
            [['user_id', 'course_id'], 'integer'],
            [['course_id'], 'exist', 'skipOnError' => true, 'targetClass' => Course::className(), 'targetAttribute' => ['course_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'course_id' => 'Course ID',
        ];
    }

    /**
     * @param $course_id
     * @return \yii\db\ActiveQuery
     */
    public function getCourse($course_id)
    {
        return $this->hasOne(Course::className(), ['id' => $course_id]);
    }

    /**
     * @param $user_id
     * @return \yii\db\ActiveQuery
     */
    public function getUser($user_id)
    {
        return $this->hasOne(User::className(), ['id' => $user_id]);
    }
}
