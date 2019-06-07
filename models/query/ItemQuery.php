<?php

namespace Zelenin\yii\modules\Tag\models\query;

use yii\db\ActiveQuery;
use Zelenin\yii\modules\Tag\models\Category;

class ItemQuery extends ActiveQuery
{
    /**
     * @param $name
     * @param $attribute
     * @return $this
     */
    public function fromCategory($name, $attribute)
    {
        $this->joinWith([
            'category' => function ($query) use ($name, $attribute) {
                    $categoryTableName = Category::tableName();
                    /** @var ActiveQuery $query */
                    $query->andWhere($categoryTableName . '.name = :name and ' . $categoryTableName . '.attribute = :attribute', [
                        ':name' => $name,
                        ':attribute' => $attribute
                    ]);
                }]);
        return $this;
    }
}
