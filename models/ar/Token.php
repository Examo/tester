<?php

namespace app\models\ar;

use Yii;

/**
 * @inheritdoc
 */
class Token extends \dektrium\user\models\Token
{
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id'])->inverseOf('tokens');
    }
}
