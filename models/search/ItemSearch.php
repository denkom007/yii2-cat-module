<?php

namespace Zelenin\yii\modules\Tag\models\search;

use Yii;
use yii\data\ActiveDataProvider;
use Zelenin\yii\modules\Tag\models\Item;
use Zelenin\yii\modules\Tag\Module;

class ItemSearch extends Item
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        $attributes = $this->attributes();
        $rules = [];
        foreach ($attributes as $attribute) {
            $rules[] = [$attribute, 'safe'];
        }
        return $rules;
    }

    /**
     * @param $params
     * @param $name
     * @param $attribute
     * @return ActiveDataProvider
     */
    public function search($params, $name, $attribute)
    {
        $query = static::find()
            ->fromCategory($name, $attribute);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => ['pageSize' => Module::$pageSize]
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query
            ->andFilterWhere(['like', Item::tableName() . '.name', $this->name])
            ->andFilterWhere(['like', 'slug', $this->slug]);
        return $dataProvider;
    }
}
