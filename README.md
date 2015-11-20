# Yii2 Role Based Navigation Module

## Data

TBA

```
use \chervand\nav\traits\IdentityTrait;
```

## Widget

`\chervand\nav\widgets\Nav` is an extension of `\yii\bootstrap\Nav` widget
which additionally implements collapsible sub navs (alongside with original
widget's dropdowns).

### Additional widget attributes
- `toggle` 'collapse' or 'dropdown', defaults to 'dropdown'
- `collapseIdPrefix` sub nav's 'id' prefix, defaults to 'sub-'

### Additional items attributes
- `name` unique item name used for toggling collapses, **required**
- `description` link 'title'


### Usage example

```
<?= chervand\nav\widgets\Nav::widget([
    'toggle' => 'collapse',
    'items' => [
        [
            'label' => 'Item 1',
            'url' => '#',
            'name' => 'item1',
            'items' => [
                [
                    'label' => 'Item 1-1',
                    'url' => '#',
                    'name' => 'item1-1'
                ],
                [
                    'label' => 'Item 1-2',
                    'url' => '#',
                    'name' => 'item1-2'
                ]
            ]
        ],
        [
            'label' => 'Item 2',
            'url' => '#',
            'name' => 'item2'
        ],
    ],
    'options' => ['class' => 'nav nav-pills nav-stacked'],
]) ?>
```

or

```
<?= chervand\nav\widgets\Nav::widget([
    'toggle' => 'collapse',
    'items' => Yii::$app->user->identity->navItemsAsArray,
    'options' => ['class' => 'nav nav-pills nav-stacked'],
]) ?>
```