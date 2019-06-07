<?php

namespace Zelenin\yii\modules\Tag\models;

use yii\db\ActiveQuery;
use Yii;
use yii\db\ActiveRecord;
use Zelenin\yii\modules\Tag\Module;

class ItemModel extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%tag_item_model}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['item_id', 'required'],
            ['item_id', 'integer'],
            ['model_id', 'required'],
            ['model_id', 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'item_id' => Module::t('Item ID'),
            'model_id' => Module::t('Model ID')
        ];
    }

    /**
     * @return ActiveQuery
     */
    public function getItem()
    {
        return $this->hasOne(Item::className(), ['id' => 'item_id']);
    }
}
