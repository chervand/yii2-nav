<?php
/**
 */

namespace chervand\nav\models;

use yii\db\ActiveRecord;

class Assignment extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%nav_assignment}}';
    }
}
