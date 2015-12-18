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

        /** @var ActiveRecord $this */
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

        /** @var ActiveRecord $this */
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

        /** @var ActiveRecord $this */
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
     * @param bool $populateJunctions
     * @return ActiveQuery
     * @throws ErrorException
     */
    public function getNavItems($populateJunctions = false)
    {
        if (!$this instanceof ActiveRecord) {
            throw new ErrorException(__CLASS__ . ' is not instance of yii\db\ActiveRecord.');
        }

        /** @var ActiveRecord $this */
        /** @var ActiveQuery $query */
        $query = $this->hasMany(Item::className(), ['name' => 'child_name'])
            ->via('navItemsChild');

        if ($populateJunctions === true) {
            $query->innerJoinWith([
                'parentItemsJunctions' => function (ActiveQuery $query) {
                    $query->from(['parentItemJunction' => ItemChild::tableName()]);
                }
            ]);
        } else {
            $query->innerJoin(
                ItemChild::tableName() . ' `parentItemJunction`',
                '`parentItemJunction`.`child_name` = ' . Item::tableName() . '.`name`'
            );
        }

        $query->joinWith([
            'childItems' => function (ActiveQuery $query) {
                $query->from(['childItem' => Item::tableName()]);
            },
        ]);

        return $query->orderBy('parentItemJunction.weight');
    }
}
