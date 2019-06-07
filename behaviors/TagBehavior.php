<?php

namespace Zelenin\yii\modules\Tag\behaviors;

use Yii;
use yii\base\Behavior;
use yii\base\InvalidParamException;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;
use yii\validators\Validator;
use Zelenin\yii\modules\Tag\helpers\TagHelper;
use Zelenin\yii\modules\Tag\models\Category;
use Zelenin\yii\modules\Tag\models\Item;
use Zelenin\yii\modules\Tag\models\ItemModel;

class TagBehavior extends Behavior
{
    /** @var array $attributes */
    public $attributes;

    /** @var array $old */
    private $old = [];
    /** @var array $new */
    private $new = [];

    /**
     * @inheritdoc
     */
    public function init()
    {
        if (empty($this->attributes) || !is_array($this->attributes)) {
            throw new InvalidParamException('Invalid or empty $attributes array');
        }
    }

    /**
     * @inheritdoc
     */
    public function events()
    {
        return [
            ActiveRecord::EVENT_INIT => 'initEvent',
            ActiveRecord::EVENT_AFTER_FIND => 'afterFind',
            ActiveRecord::EVENT_AFTER_INSERT => 'afterInsert',
            ActiveRecord::EVENT_AFTER_UPDATE => 'afterUpdate',
        ];
    }

    public function initEvent()
    {
        foreach ($this->attributes as $key => $value) {
            $rule = [$key, 'safe'];
            $validator = Validator::createValidator($rule[1], $this->owner, (array)$rule[0], array_slice($rule, 2));
            $this->owner->validators[] = $validator;
        }
    }

    public function afterFind()
    {
        foreach ($this->attributes as $key => $value) {
            $this->old[$key] = array_keys($this->findItems($key)->indexBy('id')->asArray()->all());
        }
    }

    public function afterInsert()
    {
        $this->saveTags();
    }

    public function afterUpdate()
    {
        $this->saveTags();
    }

    public function saveTags()
    {
        foreach ($this->attributes as $attribute => $value) {

            $old = ArrayHelper::getValue($this->old, $attribute, []);
            $new = ArrayHelper::getValue($this->new, $attribute, []);

            if ($old !== $new) {
                $intersect = array_intersect($old, $new);
                $removeIds = array_diff($old, $intersect);
                $addIds = array_diff($new, $intersect);

                foreach ($removeIds as $id) {
                    $model = ItemModel::find()->where('item_id = :item_id and model_id = :model_id', [
                        ':item_id' => $id,
                        ':model_id' => $this->owner->id
                    ])->one();
                    $model->delete();
                }

                if ($addIds) {
                    $categoryName = TagHelper::getCategoryFromClass($this->owner->className());
                    $category = Category::find()->where('name = :name and attribute = :attribute', [
                        ':name' => $categoryName,
                        ':attribute' => $attribute
                    ])->one();
                    if (!$category) {
                        $category = new Category;
                        $category->setAttributes([
                            'name' => $categoryName,
                            'attribute' => $attribute
                        ]);
                        $category->save();
                    }
                    foreach ($addIds as $id) {
                        $model = new ItemModel();
                        $model->setAttributes([
                            'item_id' => $id,
                            'model_id' => $this->owner->id
                        ]);
                        $model->save();
                    }
                }
            }
        }
    }

    /**
     * @param $attribute
     * @return ActiveQuery
     */
    public function findItems($attribute)
    {
        /** @var ActiveRecord $owner */
        $owner = $this->owner;
        $className = TagHelper::getCategoryFromClass($owner->className());

        $query = $this->attributes[$attribute]['multiple']
            ? $owner->hasMany(Item::className(), ['id' => 'item_id'])
            : $owner->hasOne(Item::className(), ['id' => 'item_id']);

        return $query
            ->viaTable(ItemModel::tableName(), ['model_id' => 'id'])
            ->joinWith(['category' => function ($query) use ($className, $attribute) {
                    $categoryTableName = Category::tableName();
                    /** @var ActiveQuery $query */
                    $query->where($categoryTableName . '.name = :name and ' . $categoryTableName . '.attribute = :attribute', [
                        ':name' => $className,
                        ':attribute' => $attribute
                    ]);
                }]);
    }

    /**
     * @inheritdoc
     */
    public function hasMethod($name)
    {
        $attribute = strtolower(preg_replace('/^get(.*)/isU', '', $name));
        if (isset($this->attributes[$attribute])) {
            return true;
        }
        return parent::hasMethod($name);
    }

    /**
     * @inheritdoc
     */
    public function __call($name, $params)
    {
        $attribute = strtolower(preg_replace('/^get(.*)/isU', '', $name));
        if (isset($this->attributes[$attribute])) {
            return $this->findItems($attribute);
        }
    }

    /**
     * @inheritdoc
     */
    public function canGetProperty($name, $checkVars = true)
    {
        if (isset($this->attributes[$name])) {
            return true;
        }
        return parent::canGetProperty($name, $checkVars);
    }

    /**
     * @inheritdoc
     */
    public function __get($name)
    {
        if (isset($this->attributes[$name])) {
            return $this->findItems($name);
        }
        return parent::__get($name);
    }

    /**
     * @inheritdoc
     */
    public function canSetProperty($name, $checkVars = true)
    {
        if (isset($this->attributes[$name])) {
            return true;
        }
        return parent::canSetProperty($name, $checkVars);
    }

    /**
     * @inheritdoc
     */
    public function __set($name, $value)
    {
        if (isset($this->attributes[$name])) {
            if (!is_array($value)) {
                $this->new[$name][] = $value;
            } else {
                $this->new[$name] = $value;
            }
        } else {
            parent::__set($name, $value);
        }
    }
}
