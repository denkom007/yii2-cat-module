<?php

namespace Zelenin\yii\modules\Tag\models;

use Yii;
use yii\db\ActiveQuery;
use yii\db\ActiveQueryInterface;
use yii\db\ActiveRecord;
use Zelenin\yii\modules\Tag\models\query\ItemQuery;
use Zelenin\yii\modules\Tag\Module;

class Item extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%tag_item}}';
    }

    /**
     * @return ActiveQuery|ActiveQueryInterface|ItemQuery
     */
    public static function find()
    {
        return new ItemQuery(get_called_class());
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['name', 'required'],
            ['name', 'string', 'max' => 255],
            ['slug', 'required'],
            ['slug', 'string', 'max' => 255],
            ['category_id', 'required'],
            ['category_id', 'integer'],
            [['slug', 'category_id'], 'unique', 'targetAttribute' => ['slug', 'category_id'], 'message' => Module::t('The combination of {0} and {1} has already been taken.', ['Slug', 'Category'])]
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
            'slug' => Module::t('Slug'),
            'category_id' => Module::t('Category ID')
        ];
    }

    /**
     * @return ActiveQuery
     */
    public function getCategory()
    {
        return $this->hasOne(Category::className(), ['id' => 'category_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getModels()
    {
        return $this->hasMany(ItemModel::className(), ['tag_id' => 'id']);
    }
}
