<?php
/**
 */

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

    public function getParentItems()
    {
        return $this->hasMany(static::className(), ['name' => 'parent_name'])
            ->viaTable('{{%nav_item_child}}', ['child_name' => 'name']);
    }

    public function getChildItems()
    {
        return $this->hasMany(static::className(), ['name' => 'child_name'])
            ->viaTable('{{%nav_item_child}}', ['parent_name' => 'name']);
    }
}
