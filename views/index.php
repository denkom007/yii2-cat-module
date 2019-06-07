<?php
/**
 * @var View $this
 * @var ItemSearch $searchModel
 * @var ActiveDataProvider $dataProvider
 */

use yii\data\ActiveDataProvider;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\Breadcrumbs;
use yii\widgets\Pjax;
use Zelenin\yii\modules\Tag\models\search\ItemSearch;
use Zelenin\yii\modules\Tag\Module;

$this->title = Module::t($this->context->entityName);
echo Breadcrumbs::widget(['links' => [
    $this->title
]]);
?>
<div class="tag-index">
    <h3><?= Html::encode($this->title) ?></h3>

    <p><?= Html::a(Module::t('Create'), ['create'], ['class' => 'btn btn-success']) ?></p>
    <?php
    Pjax::begin();
    echo GridView::widget([
        'filterModel' => $searchModel,
        'dataProvider' => $dataProvider,
        'id' => ($grid_id = 'tag-id'),
        'columns' => [
            'id',
            [
                'attribute' => 'name',
                'format' => 'raw',
                'value' => function ($model, $index, $dataColumn) {
                        return Html::a($model->name, ['update', 'id' => $model->id], ['data' => ['pjax' => 0]]);
                    }
            ],
            'slug',
            [
                'class' => ActionColumn::className(),
                'template' => '{delete}'
            ]
        ]
    ]);
    Pjax::end();
    ?>
</div>
