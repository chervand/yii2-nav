<?php
/**
 */

namespace chervand\nav\models;

use yii\db\ActiveRecord;

class Nav extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%nav}}';
    }

    public static function findByUserId($userId)
    {
        return Nav::find()->one();
    }

    public function getItems()
    {
        return $this->hasMany(ItemChild::className(), ['nav_id' => 'id']);
    }
}
