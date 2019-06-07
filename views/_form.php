<?php
/**
 * @var View $this
 * @var Item $model
 * @var ActiveForm $form
 */

use yii\helpers\Html;
use yii\web\View;
use yii\widgets\ActiveForm;
use Zelenin\yii\modules\Tag\models\Item;
use Zelenin\yii\modules\Tag\Module;

?>
<div class="tag-form">
    <?php $form = ActiveForm::begin(); ?>
    <div class="row">
        <?= $form->field($model, 'name', ['options' => ['class' => 'form-group col-sm-6']])->textInput(['maxlength' => 255]) ?>
        <?= $form->field($model, 'slug', ['options' => ['class' => 'form-group col-sm-6']])->textInput(['maxlength' => 255]) ?>
    </div>
    <div class="form-group">
        <?=
        Html::submitButton(
            $model->getIsNewRecord() ? Module::t('Create') : Module::t('Update'),
            [
                'class' => $model->getIsNewRecord() ? 'btn btn-success' : 'btn btn-primary'
            ]
        ) ?>
    </div>
    <?php $form::end(); ?>
</div>
