<?php

namespace app\models\ar;

use Yii;

/**
 * This is the model class for table "challenge_mark".
 *
 * @property integer $id
 * @property integer $challenge_id
 * @property integer $value_from
 * @property integer $value_to
 * @property string $mark
 *
 * @property Challenge $challenge
 */
class ChallengeMark extends \app\components\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'challenge_mark';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['challenge_id'], 'required'],
            [['challenge_id', 'value_from', 'value_to'], 'integer'],
            [['mark'], 'string', 'max' => 64],
            [['challenge_id'], 'exist', 'skipOnError' => true, 'targetClass' => Challenge::className(), 'targetAttribute' => ['challenge_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'challenge_id' => 'Challenge ID',
            'value_from' => 'Value From',
            'value_to' => 'Value To',
            'mark' => 'Mark',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getChallenge()
    {
        return $this->hasOne(Challenge::className(), ['id' => 'challenge_id'])->inverseOf('challengeMarks');
    }
}
