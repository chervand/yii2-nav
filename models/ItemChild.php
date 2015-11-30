<?php
/**
 */

namespace chervand\nav\models;

use yii\db\ActiveRecord;

class ItemChild extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%nav__item_child}}';
    }
}
