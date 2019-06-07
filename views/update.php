<?php
/**
 * @var View $this
 * @var Item $model
 */

use yii\helpers\Html;
use yii\web\View;
use yii\widgets\Breadcrumbs;
use Zelenin\yii\modules\Tag\models\Item;
use Zelenin\yii\modules\Tag\Module;

$this->title = Module::t('Update') . ': ' . $model->name;
echo Breadcrumbs::widget(['links' => [
    ['label' => Module::t($this->context->entityName), 'url' => ['index']],
    ['label' => $this->title]
]]);
?>
<div class="tag-update">
    <h3><?= Html::encode($this->title) ?></h3>
    <?= $this->render('_form', ['model' => $model]) ?>
</div>
