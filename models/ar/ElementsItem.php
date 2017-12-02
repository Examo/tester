<?php

namespace app\models\ar;

/**
 *  * This is the model class for table "elements_item".
 *
 * Class ElementsItem
 * @package app\models\ar
 */

class ElementsItem extends \app\components\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'elements_item';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'element_id'], 'required'],
            [['name'], 'string'],
            [['element_id'], 'integer'],
          
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
            'element_id' => 'Element\'s item',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getElement()
    {
        return $this->hasOne(Element::className(), ['id' => 'element_id'])->inverseOf('elements_item');
    }

        /**
     * @return \yii\db\ActiveQuery
     */
    public function getChallenges()
    {
        return $this->hasMany(Challenge::className(), ['elements_item_id' => 'id'])->inverseOf('elements_item');
    }

}