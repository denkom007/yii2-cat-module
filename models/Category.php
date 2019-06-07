<?php

namespace Zelenin\yii\modules\Tag\models;

use Yii;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use Zelenin\yii\modules\Tag\Module;

class Category extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%tag_category}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['name', 'required'],
            ['name', 'string', 'max' => 255],
            ['attribute', 'required'],
            ['attribute', 'string', 'max' => 255],
            [['name', 'attribute'], 'unique', 'targetAttribute' => ['name', 'attribute'], 'message' => Module::t('The combination of {0} and {1} has already been taken.', ['Name', 'Attribute'])]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Module::t('ID'),
            'name' => Module::t('Name'),
            'attribute' => Module::t('Attribute')
        ];
    }

    /**
     * @return ActiveQuery
     */
    public function getItems()
    {
        return $this->hasMany(Item::className(), ['category_id' => 'id']);
    }
}
