<?php

namespace app\models\ar;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * @inheritdoc
 */
class User extends \dektrium\user\models\User
{
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAttempts()
    {
        return $this->hasMany(Attempt::className(), ['user_id' => 'id'])->inverseOf('user');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCourseSubscriptions()
    {
        return $this->hasMany(CourseSubscription::className(), ['user_id' => 'id'])->inverseOf('user');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCourses()
    {
        return $this->hasMany(Course::className(), ['id' => 'course_id'])->viaTable('course_subscription', ['user_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProfile()
    {
        return $this->hasOne(Profile::className(), ['user_id' => 'id'])->inverseOf('user');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSocialAccounts()
    {
        return $this->hasMany(SocialAccount::className(), ['user_id' => 'id'])->inverseOf('user');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTokens()
    {
        return $this->hasMany(Token::className(), ['user_id' => 'id'])->inverseOf('user');
    }

    public static function getList($exclude = [], $field = 'username')
    {
        $ids = [];
        foreach ($exclude as $item) {
            $ids[] = is_object($item) ? $item->id : $item;
        }

        $condition = count($ids) ? ['not in', 'id', $ids] : '';

        return ArrayHelper::map(static::find()->where($condition)->all(), 'id', $field);
    }
}
