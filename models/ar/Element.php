<?php

namespace app\models\ar;

use Yii;

/**
 * This is the model class for table "element".
 *
 * @property integer $id
 * @property string $name
 * @property string $description

 *
 * @property Challenge[] $challenges
 */
class Element extends \app\components\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'element';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'description'], 'string'],
            [['position'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'description' => 'Description',
            'position' => 'Position',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getChallenges()
    {
        return $this->hasMany(Challenge::className(), ['element_id' => 'id'])->inverseOf('element');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getElements_item()
    {
        return $this->hasMany(ElementsItem::className(), ['element_id' => 'id'])->inverseOf('element');
    }
}
