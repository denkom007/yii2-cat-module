<?php

namespace Zelenin\yii\modules\Tag\helpers;

class TagHelper
{
    /**
     * @param $className
     * @return string
     */
    public static function getCategoryFromClass($className)
    {
        return str_replace('\\', '.', $className);
    }

    /**
     * @param $category
     * @return string
     */
    public static function getClassFromCategory($category)
    {
        return str_replace('.', '\\', $category);
    }
}
