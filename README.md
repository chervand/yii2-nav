# Yii2 Role Based Navigation Module

## Data

TODO: TBA

```php
use \chervand\nav\traits\IdentityTrait;
```

## Widget

```php
<?= chervand\bootstrap\Nav::widget([
    'toggle' => 'collapse',
    'items' => Yii::$app->user->identity->navItemsAsArray,
    'options' => ['class' => 'nav nav-pills nav-stacked'],
]) ?>
```
