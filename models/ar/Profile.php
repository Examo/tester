<?php

namespace app\models\ar;

use Yii;

/**
 * @inheritdoc
 */
class Profile extends \dektrium\user\models\Profile
{
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id'])->inverseOf('profile');
    }
}
