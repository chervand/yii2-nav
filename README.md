# Yii2 Role Based Navigation Module

## Data

TODO: TBA

```php
use \chervand\nav\traits\IdentityTrait;
```

### Assignments

By default Nav assigns to identity's id, but you can implement your own assignment
logic. You can do this by overriding [[IdentityTrait::getNavAssignment()]] relation.

```php
class Identity extends ActiveRecord implements IdentityInterface
{
    use \chervand\nav\traits\IdentityTrait;
    ...
    public function getNavAssignment()
    {
        return $this->hasOne(Assignment::className(), ['assignment' => 'role_id'])
            ->orderBy('type ASC');
    }
    ...
}
```

## Widget

```php
<?= chervand\bootstrap\Nav::widget([
    'toggle' => 'collapse',
    'items' => Yii::$app->user->identity->navItemsAsArray,
    'options' => ['class' => 'nav nav-pills nav-stacked'],
]) ?>
```
