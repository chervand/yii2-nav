<?php
namespace chervand\nav\models;

use yii\db\ActiveRecord;

/**
 * Class Item
 * @package chervand\nav
 * @property string $name
 * @property string $label
 * @property string $url
 * @property string $description
 * @property Item[] $parentItems
 * @property Item[] $childItems
 * @property ItemChild[] $parentItemsJunctions
 * @property ItemChild[] $childItemsJunctions
 */
class Item extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%nav_item}}';
    }

    public function getParentItemsJunctions()
    {
        return $this->hasMany(ItemChild::className(), ['child_name' => 'name']);
    }

    public function getChildItemsJunctions()
    {
        return $this->hasMany(ItemChild::className(), ['parent_name' => 'name']);
    }

    public function getParentItems()
    {
        return $this->hasMany(static::className(), ['name' => 'parent_name'])
            ->via('parentItemsJunctions');
    }

    public function getChildItems()
    {
        return $this->hasMany(static::className(), ['name' => 'child_name'])
            ->via('childItemsJunctions');
    }
}
