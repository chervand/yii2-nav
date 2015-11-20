<?php
/**
 *
 */

namespace chervand\nav\widgets;

use chervand\nav\models\Item;
use yii\base\InvalidConfigException;
use yii\bootstrap\BootstrapPluginAsset;
use yii\bootstrap\Html;
use yii\helpers\ArrayHelper;

/**
 * Class Nav
 * @package chervand\nav\widgets
 */
class Nav extends \yii\bootstrap\Nav
{
    const TOGGLE_DROPDOWN = 'dropdown';
    const TOGGLE_COLLAPSE = 'collapse';
    public $toggle;
    public $collapseIdPrefix = 'sub-';

    public function init()
    {
        parent::init();

        if (!in_array($this->toggle, [static::TOGGLE_DROPDOWN, static::TOGGLE_COLLAPSE])) {
            $this->toggle = static::TOGGLE_DROPDOWN;
        }

        foreach ($this->items as $key => $item) {
            $item = static::prepareItem($item);
            if ($item !== null) {
                $this->items[$key] = $item;
            } else {
                unset($this->items[$key]);
            }
        }

        BootstrapPluginAsset::register($this->getView());
    }

    /**
     * object performance
     * @param $item
     * @return array|null
     */
    protected static function prepareItem($item)
    {
        if ($item instanceof Item) {
            return ArrayHelper::toArray($item, [
                'chervand\nav\models\Item' => [
                    'label',
                    'name',
                    'description',
                    'url' => function ($item) {
                        return static::prepareUrl($item->url);
                    },
                    'items' => function ($item) {
                        return !empty($item->childItems) ? $item->childItems : null;
                    },
                ]
            ]);
        } elseif (is_array($item)) {
            if (!isset($item['label'])) {
                $item['label'] = '';
            }
            if (isset($item['url'])) {
                $item['url'] = static::prepareUrl($item['url']);
            }
            if (isset($item['childItems']) && !empty($item['childItems'])) {
                foreach ($item['childItems'] as $childItem) {
                    $item['items'][] = static::prepareItem($childItem);
                }
                unset($item['childItems']);
            }
            return $item;
        }
        return null;
    }

    protected static function prepareUrl($url)
    {
        if (!is_string($url)) {
            return null;
        }
        if (substr($url, 0, 1) === '#') {
            return $url;
        }
        return [$url];
    }

    /**
     * @inheritdoc
     */
    public function renderItem($item)
    {
        if (is_string($item)) {
            return $item;
        }
        if (!isset($item['label'])) {
            throw new InvalidConfigException("The 'label' option is required.");
        }
        $encodeLabel = isset($item['encode']) ? $item['encode'] : $this->encodeLabels;
        $label = $encodeLabel ? Html::encode($item['label']) : $item['label'];
        $options = ArrayHelper::getValue($item, 'options', []);
        $items = ArrayHelper::getValue($item, 'items');
        $url = ArrayHelper::getValue($item, 'url', '#');
        $name = ArrayHelper::getValue($item, 'name');
        $description = ArrayHelper::getValue($item, 'description');
        $linkOptions = ArrayHelper::getValue($item, 'linkOptions', []);
        $options['id'] = $name;
        $options['title'] = $description;

        if (isset($item['active'])) {
            $active = ArrayHelper::remove($item, 'active', false);
        } else {
            $active = $this->isItemActive($item);
        }

        if ($items !== null) {
            $linkOptions['data-toggle'] = $this->toggle;
            Html::addCssClass($options, ['widget' => $this->toggle]);
            Html::addCssClass($linkOptions, ['widget' => $this->toggle . '-toggle']);
            if ($this->dropDownCaret !== '') {
                $label .= ' ' . $this->dropDownCaret;
            }
            if (is_array($items)) {
                if ($this->activateItems) {
                    $items = $this->isChildActive($items, $active);
                }
                if ($this->toggle == static::TOGGLE_COLLAPSE) {
                    $linkOptions['data-target'] = '#' . $this->collapseIdPrefix . $name;
                    $items = $this->renderCollapse($items, $item);
                } else {
                    $items = $this->renderDropdown($items, $item);
                }
            }
        }

        if ($this->activateItems && $active) {
            Html::addCssClass($options, 'active');
        }

        return Html::tag('li', Html::a($label, $url, $linkOptions) . $items, $options);
    }


    protected function renderCollapse($items, $item)
    {
        $id = $this->collapseIdPrefix . ArrayHelper::getValue($item, 'name');
        $class = static::TOGGLE_COLLAPSE;
        if (isset($this->options['class'])) {
            $class .= ' ' . $this->options['class'];
        }
        return \yii\bootstrap\Nav::widget([
            'options' => ['id' => $id, 'class' => $class],
            'items' => $items
        ]);
    }
}
