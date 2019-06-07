<?php

namespace Zelenin\yii\modules\Tag;

use Yii;

class Module extends \yii\base\Module
{
    /** @var int $pageSize */
    public static $pageSize = 50;

    public function init()
    {
        parent::init();
    }

    /**
     * @return null|static
     */
    public static function module()
    {
        return static::getInstance();
    }

    /**
     * @param $message
     * @param array $params
     * @param null $language
     * @return string
     */
    public static function t($message, $params = [], $language = null)
    {
        return Yii::t('zelenin/modules/tag', $message, $params, $language);
    }
}
