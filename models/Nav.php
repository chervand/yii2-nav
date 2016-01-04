<?php

namespace chervand\nav\models;

use yii\db\ActiveRecord;
use yii\db\ActiveQuery;

/**
 * Class Nav
 * @package chervand\nav\models
 *
 * @property Assignment[] $assignments
 * @property ItemChild[] $itemsJunctions
 * @property Item[] $items
 * @property array $itemsAsArray
 */
class Nav extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%nav}}';
    }

    public static function findByUserId($userId)
    {
        return Nav::find()->one();
    }

    public function getItemsJunctions()
    {
        return $this->hasMany(ItemChild::className(), ['nav_id' => 'id']);
    }

    public function getItems()
    {
        return $this->hasMany(Item::className(), ['name' => 'child_name'])
            ->with('childItems')
            ->via('itemsJunctions', function (ActiveQuery $query) {
                $query->andWhere(['parent_name' => 'root']);
            });
    }

    public function getItemsAsArray()
    {
        return $this->getItems()->asArray();
    }
}
