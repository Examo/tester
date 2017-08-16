<?php

namespace app\models\ar;

use Yii;

/**
 * This is the model class for table "challenge_food".
 *
 * @property integer $id
 * @property integer $challenge_id
 * @property integer $food_id
 *
 * @property Challenge $challenge
 * @property Food $food
 */
class ChallengeFood extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'challenge_food';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['challenge_id', 'food_id'], 'required'],
            [['challenge_id', 'food_id'], 'integer'],
            [['challenge_id'], 'exist', 'skipOnError' => true, 'targetClass' => Challenge::className(), 'targetAttribute' => ['challenge_id' => 'id']],
            [['food_id'], 'exist', 'skipOnError' => true, 'targetClass' => Food::className(), 'targetAttribute' => ['food_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'challenge_id' => Yii::t('app', 'Challenge ID'),
            'food_id' => Yii::t('app', 'Food ID'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getChallenge()
    {
        return $this->hasOne(Challenge::className(), ['id' => 'challenge_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFood()
    {
        return $this->hasOne(Food::className(), ['id' => 'food_id']);
    }
}
