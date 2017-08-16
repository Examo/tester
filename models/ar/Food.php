<?php

namespace app\models\ar;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "food".
 *
 * @property integer $id
 * @property string $food_name
 *
 * @property ChallengeFood[] $challengeFoods
 */
class Food extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'food';
    }

    public static function getList($exclude = [], $field = 'food_name')
    {
        $ids = [];
        foreach ($exclude as $item) {
            $ids[] = is_object($item) ? $item->id : $item;
        }

        $condition = count($ids) ? ['not in', 'id', $ids] : '';

        return ArrayHelper::map(static::find()->where($condition)->all(), 'id', $field);
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['food_name'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'food_name' => Yii::t('app', 'Food Name'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getChallengeFoods()
    {
        return $this->hasMany(ChallengeFood::className(), ['food_id' => 'id']);
    }
}
