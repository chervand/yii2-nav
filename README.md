```
use \chervand\nav\traits\IdentityTrait;
```

```
<?= chervand\nav\widgets\Nav::widget([
                    'encodeLabels' => true,
                    'identity' => Yii::$app->user->identity,
                    'options' => ['class' => 'nav nav-pills nav-stacked'],
                ]); ?>
                ```
                
                // todo: optimize queries
                // todo: cache