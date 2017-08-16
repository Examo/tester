<?php

namespace app\models\ar;

use Yii;

/**
 * This is the model class for table "challenges_feed".
 *
 * @property integer $id
 * @property integer $course_id
 * @property integer $week_id
 * @property string $challenges
 *
 * @property Course $course
 */
class ChallengesFeed extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'challenges_feed';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['course_id', 'week_id'], 'required'],
            [['course_id', 'week_id'], 'integer'],
            [['challenges'], 'string'],
            [['course_id'], 'exist', 'skipOnError' => true, 'targetClass' => Course::className(), 'targetAttribute' => ['course_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'course_id' => Yii::t('app', 'Course ID'),
            'week_id' => Yii::t('app', 'Week ID'),
            'challenges' => Yii::t('app', 'Challenges'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCourse()
    {
        return $this->hasOne(Course::className(), ['id' => 'course_id']);
    }
}
