# Yii2 Tag/Category module

[Yii2](http://www.yiiframework.com) tag/category module

## Installation

### Composer

The preferred way to install this extension is through [Composer](http://getcomposer.org/).

Either run

```
php composer.phar require zelenin/yii2-tag-module "dev-master"
```

or add

```
"zelenin/yii2-tag-module": "dev-master"
```

to the require section of your ```composer.json```

## Usage

Run:

```
php yii migrate --migrationPath=@Zelenin/yii/modules/Tag/migrations
```

Add behavior to model:

```php
public function behaviors()
{
    return [
        'tag' => [
            'class' => 'Zelenin\yii\modules\Tag\behaviors\TagBehavior',
            'attributes' => [
                'tag' => [
                    'multiple' => true
                ],
                'category' => [
                    'multiple' => false
                ]
            ]
        ]
    ];
}
```

Create controllers for each attributes:

```php
<?php

namespace backend\controllers;

use common\models\Post;
use Yii;
use Zelenin\yii\modules\Tag\controllers\DefaultController;

class PostTagController extends DefaultController
{
    public function init()
    {
        $this->modelClass = Post::className();
        $this->modelAttribute = 'tag';
        $this->entityName = 'Tags';
        parent::init();
    }
}
```

Now you can enter to tag/category CRUD - ```http://backend.yourdomain.com/post-tag/index```.

Add widgets to view:

```php
<div class="row">
    <?= $form->field($model, 'tag', ['options' => ['class' => 'form-group col-sm-6']])->widget(Tag::className(), []) ?>
    <?= $form->field($model, 'category', ['options' => ['class' => 'form-group col-sm-6']])->widget(Tag::className(), []) ?>
</div>
```

You can get tags via $model->tag.

### Work in progress

Write your feature request to [issues](https://github.com/zelenin/zelenin/yii2-tag-module/issues)

## Author

[Aleksandr Zelenin](https://github.com/zelenin/), e-mail: [aleksandr@zelenin.me](mailto:aleksandr@zelenin.me)
