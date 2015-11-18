<?php
/**
 *
 */

namespace chervand\nav\widgets;

use chervand\nav\models\Item;
use chervand\nav\traits\IdentityTrait;
use yii\base\InvalidConfigException;

/**
 * Class Nav
 * @package chervand\nav\widgets
 */
class Nav extends \yii\bootstrap\Nav
{
    public $identity;

    public function init()
    {
        parent::init();
        if (!isset($this->identity)) {
            throw new InvalidConfigException("The 'identity' option is required.");
        }
        $this->items();
    }

    protected function items()
    {
        /** @var IdentityTrait $identity */
        if (in_array('chervand\nav\traits\IdentityTrait', class_uses($this->identity))) {
            foreach ($this->identity->navItems as $navItem) {
                if ($navItem instanceof Item) {
                    $this->items[] = $navItem->toArray(
                        ['label', 'url'], ['items']
                    );
                }
            }
        }
    }
}
