<?php

namespace Zelenin\yii\modules\Tag\controllers;

use Yii;
use yii\base\InvalidConfigException;
use yii\db\ActiveRecord;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use Zelenin\yii\modules\Tag\helpers\TagHelper;
use Zelenin\yii\modules\Tag\models\Category;
use Zelenin\yii\modules\Tag\models\Item;
use Zelenin\yii\modules\Tag\models\search\ItemSearch;
use Zelenin\yii\modules\Tag\Module;

class DefaultController extends Controller
{
    /** @var ActiveRecord $modelClass */
    public $modelClass;
    /** @var string $modelAttribute */
    public $modelAttribute;
    /** @var string $entityName */
    public $entityName;
    /** @var string $viewsPath */
    public $viewsPath = '@Zelenin/yii/modules/Tag/views';

    public function init()
    {
        parent::init();
        if (!$this->modelClass) {
            throw new InvalidConfigException('"modelClass" is not set');
        }
        if (!$this->modelAttribute) {
            throw new InvalidConfigException('"modelAttribute" is not set');
        }
    }

    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ]
            ]
        ];
    }

    public function actionIndex()
    {
        $searchModel = new ItemSearch();
        $dataProvider = $searchModel->search(Yii::$app->getRequest()->get(), TagHelper::getCategoryFromClass($this->modelClass), $this->modelAttribute);
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider
        ]);
    }

    public function actionCreate()
    {
        $category = Category::find()->where('name = :name and attribute = :attribute', [
            ':name' => TagHelper::getCategoryFromClass($this->modelClass),
            ':attribute' => $this->modelAttribute
        ])->one();

        if (!$category) {
            $category = new Category();
            $category->setAttributes([
                'name' => TagHelper::getCategoryFromClass($this->modelClass),
                'attribute' => $this->modelAttribute
            ]);
            $category->save();
        }
        $model = new Item();
        $model->load(Yii::$app->getRequest()->post());
        $model->category_id = $category->id;

        if ($model->save()) {
            return $this->redirect(['update', 'id' => $model->id]);
        } else {
            return $this->render('create', ['model' => $model]);
        }
    }

    public function actionUpdate($id)
    {
        $model = Item::find()
            ->fromCategory(TagHelper::getCategoryFromClass($this->modelClass), $this->modelAttribute)
            ->where(Item::tableName() . '.id = :id', [':id' => $id])
            ->one();
        if (!$model) {
            throw new NotFoundHttpException(Module::t('The requested page does not exist'));
        }

        if ($model->load(Yii::$app->getRequest()->post()) && $model->save()) {
            return $this->redirect(['update', 'id' => $model->id]);
        } else {
            return $this->render('update', ['model' => $model]);
        }
    }

    public function actionDelete($id)
    {
        $model = Item::find()
            ->fromCategory(TagHelper::getCategoryFromClass($this->modelClass), $this->modelAttribute)
            ->where(Item::tableName() . '.id = :id', [':id' => $id])
            ->one();
        if (!$model) {
            throw new NotFoundHttpException(Module::t('The requested page does not exist'));
        }
        $model->delete();
        return $this->redirect(['index']);
    }

    public function render($view, $params = [])
    {
        return parent::render($this->viewsPath . '/' . $view, $params);
    }
}
