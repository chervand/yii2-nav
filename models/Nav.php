<?php

namespace chervand\nav\models;

use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

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

    /**
     * @param integer $userId
     * @param string $widget
     * @param null|string $route
     * @return null|ActiveRecord
     */
    public static function findByAssignment($userId, $widget, $route = null)
    {
        return static::find()
            ->innerJoinWith([
                'assignments' => function (ActiveQuery $query) {
                    $query->from(['assignment' => Assignment::tableName()]);
                }
            ])
            ->andWhere('`assignment`.`user_id` = :user_id')
            ->andWhere('`assignment`.`widget` = :widget')
            ->andWhere('`assignment`.`route` = :route OR `assignment`.`route` IS NULL')
            ->params([
                ':user_id' => $userId,
                ':widget' => $widget,
                ':route' => $route,
            ])
            ->orderBy([
                'assignment.route' => SORT_DESC,
                'assignment.id' => SORT_DESC,
            ])
            ->one();
    }

    public function getItemsJunctions()
    {
        return $this->hasMany(ItemChild::className(), ['nav_id' => 'id']);
    }

    public function getItemsAsArray()
    {
        return $this->getItems()->asArray();
    }

    /**
     * @return ActiveQuery
     */
    public function getItems()
    {
        return $this->hasMany(Item::className(), ['name' => 'child_name'])
            ->with('childItems')
            ->via('itemsJunctions', function (ActiveQuery $query) {
                $query->andWhere(['parent_name' => 'root']);
            });
    }
}
