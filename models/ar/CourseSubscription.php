<?php

namespace app\models\ar;

use Yii;

/**
 * This is the model class for table "course_subscription".
 *
 * @property integer $user_id
 * @property integer $course_id
 * @property string $course_subscriptioncol
 *
 * @property Course $course
 * @property User $user
 */
class CourseSubscription extends \app\components\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'course_subscription';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'course_id'], 'required'],
            [['user_id', 'course_id'], 'integer'],
            [['course_subscriptioncol'], 'string', 'max' => 45],
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
            'user_id' => 'User ID',
            'course_id' => 'Course ID',
            'course_subscriptioncol' => 'Course Subscriptioncol',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCourse()
    {
        return $this->hasOne(Course::className(), ['id' => 'course_id'])->inverseOf('courseSubscriptions');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id'])->inverseOf('courseSubscriptions');
    }
}
