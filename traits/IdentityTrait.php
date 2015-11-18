<?php

namespace chervand\nav\traits;

use chervand\nav\models\Assignment;
use chervand\nav\models\Item;
use chervand\nav\models\ItemChild;
use chervand\nav\models\Nav;
use Yii;
use yii\db\ActiveRecord;

/**
 * Class IdentityTrait
 * @package chervand\nav\traits
 * @property Assignment $navAssignment
 * @property Nav $nav
 * @property Item $navItems
 * @property ItemChild $navItemsChild
 */
trait IdentityTrait
{
    public function getNavAssignment()
    {
        if ($this instanceof ActiveRecord) {
            return $this->hasOne(Assignment::className(), ['assignment' => 'id'])
                ->orderBy('type ASC');
        }
        return null;
    }

    public function getNav()
    {
        if ($this instanceof ActiveRecord) {
            return $this->hasOne(Nav::className(), ['id' => 'nav_id'])
                ->via('navAssignment');
        }
        return null;
    }

    public function getNavItemsChild()
    {
        if ($this instanceof ActiveRecord) {
            return $this->hasMany(ItemChild::className(), ['nav_id' => 'id'])
                ->via('nav')->where(['parent_name' => 'root']);
        }
        return null;
    }

    public function getNavItems()
    {
        if ($this instanceof ActiveRecord) {
            return $this->hasMany(Item::className(), ['name' => 'child_name'])
                ->via('navItemsChild')->with(['childItems']);
        }
        return null;
    }
}
