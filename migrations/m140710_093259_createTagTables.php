<?php

use yii\db\Migration;
use yii\db\Schema;
use Zelenin\yii\modules\Tag\models\Category;
use Zelenin\yii\modules\Tag\models\Item;
use Zelenin\yii\modules\Tag\models\ItemModel;

class m140710_093259_createTagTables extends Migration
{
    /** @var array $tables */
    private $tables = [];

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        $this->tables = [
            'tag_item_model' => ItemModel::tableName(),
            'tag_item' => Item::tableName(),
            'tag_category' => Category::tableName()
        ];
    }

    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTagItemTable();
        $this->createTagCategoryTable();
        $this->createTagModelTable();
    }

    public function createTagItemTable()
    {
        $this->createTable($this->tables['tag_item'], [
            'id' => Schema::TYPE_PK,
            'name' => Schema::TYPE_STRING . ' not null',
            'slug' => Schema::TYPE_STRING . ' not null',
            'category_id' => Schema::TYPE_INTEGER . ' not null'
        ]);
    }

    public function createTagCategoryTable()
    {
        $this->createTable($this->tables['tag_category'], [
            'id' => Schema::TYPE_PK,
            'name' => Schema::TYPE_STRING . ' not null',
            'attribute' => Schema::TYPE_STRING . ' not null'
        ]);
        $this->createIndex('name', $this->tables['tag_category'], ['name', 'attribute'], true);
    }

    public function createTagModelTable()
    {
        $this->createTable($this->tables['tag_item_model'], [
            'item_id' => Schema::TYPE_INTEGER . ' not null',
            'model_id' => Schema::TYPE_INTEGER . ' not null',

        ]);
        $this->addPrimaryKey('pk', $this->tables['tag_item_model'], ['item_id', 'model_id']);
        $this->addForeignKey('tag_item_model_ibfk_1', $this->tables['tag_item_model'], 'item_id', $this->tables['tag_item'], 'id');
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        foreach ($this->tables as $table) {
            $this->dropTable($table);
        }
        return true;
    }
}
