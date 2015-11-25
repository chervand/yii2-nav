<?php

namespace chervand\nav\traits;

use chervand\nav\models\Assignment;
use chervand\nav\models\Item;
use chervand\nav\models\ItemChild;
use chervand\nav\models\Nav;
use Yii;
use yii\base\ErrorException;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * Class IdentityTrait
 * @package chervand\nav\traits
 * @property Assignment $navAssignment
 * @property Nav $nav
 * @property Item $navItems
 * @property array $navItemsAsArray
 * @property ItemChild $navItemsChild
 */
trait IdentityTrait
{
    /**
     * @return ActiveQuery
     * @throws ErrorException
     */
    public function getNavAssignment()
    {
        if (!$this instanceof ActiveRecord) {
            throw new ErrorException(__CLASS__ . ' is not instance of yii\db\ActiveRecord.');
        }

        return $this->hasOne(Assignment::className(), ['assignment' => 'id'])
            ->orderBy('type ASC');
    }

    /**
     * @return ActiveQuery
     * @throws ErrorException
     */
    public function getNav()
    {
        if (!$this instanceof ActiveRecord) {
            throw new ErrorException(__CLASS__ . ' is not instance of yii\db\ActiveRecord.');
        }

        return $this->hasOne(Nav::className(), ['id' => 'nav_id'])
            ->via('navAssignment');

    }

    /**
     * @return ActiveQuery
     * @throws ErrorException
     */
    public function getNavItemsChild()
    {
        if (!$this instanceof ActiveRecord) {
            throw new ErrorException(__CLASS__ . ' is not instance of yii\db\ActiveRecord.');
        }

        /** @var ActiveQuery $query */
        $query = $this->hasMany(ItemChild::className(), ['nav_id' => 'id'])
            ->via('nav');

        return $query->where(['parent_name' => 'root']);
    }

    /**
     * @return array
     * @throws ErrorException
     */
    public function getNavItemsAsArray()
    {
        return $this->getNavItems()->asArray();
    }

    /**
     * @return ActiveQuery
     * @throws ErrorException
     */
    public function getNavItems()
    {
        if (!$this instanceof ActiveRecord) {
            throw new ErrorException(__CLASS__ . ' is not instance of yii\db\ActiveRecord.');
        }

        /** @var ActiveQuery $query */
        $query = $this->hasMany(Item::className(), ['name' => 'child_name'])
            ->via('navItemsChild');

        return $query->joinWith([
            'childItems' => function (ActiveQuery $query) {
                $query->from(['childItem' => Item::tableName()]);
            }
        ]);
    }
}
