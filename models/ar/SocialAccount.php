<?php

namespace app\models\ar;

use Yii;

/**
 * @inheritdoc
 */
class SocialAccount extends \dektrium\user\models\Account
{
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id'])->inverseOf('socialAccounts');
    }
}
