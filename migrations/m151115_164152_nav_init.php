<?php

use yii\db\Migration;

class m151115_164152_nav_init extends Migration
{
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%nav}}', [
            'id' => $this->primaryKey(),
            'title' => $this->string()->notNull(),
            'description' => $this->text(),
        ], $tableOptions);

        $this->createTable('{{%nav_assignment}}', [
            'nav_id' => $this->integer()->notNull(),
            'type' => $this->smallInteger()->notNull(),
            'assignment' => $this->string()->notNull(),
        ], $tableOptions);

        $this->addPrimaryKey('PK_nav_assignment',
            '{{%nav_assignment}}', ['nav_id', 'type', 'assignment']
        );
        $this->addForeignKey('FK_nav_assignment_nav',
            '{{%nav_assignment}}', 'nav_id',
            '{{%nav}}', 'id',
            'CASCADE', 'CASCADE'
        );
        $this->createIndex('IDX_nav_assignment_type',
            '{{%nav_assignment}}', 'type'
        );

        $this->createTable('{{%nav_item}}', [
            'name' => $this->string(),
            'label' => $this->string()->notNull(),
            'url' => $this->string()->notNull(),
            'description' => $this->text(),
        ], $tableOptions);

        $this->addPrimaryKey('PK_nav_item',
            '{{%nav_item}}', 'name'
        );

        $this->createTable('{{%nav_item_child}}', [
            'nav_id' => $this->integer()->notNull(),
            'parent_name' => $this->string()->notNull(),
            'child_name' => $this->string()->notNull(),
            'weight' => $this->smallInteger()->notNull()->defaultValue(0),
        ], $tableOptions);

        $this->addPrimaryKey('PK_nav_item_child',
            '{{%nav_item_child}}', ['nav_id', 'parent_name', 'child_name']
        );
        $this->addForeignKey('FK_nav_item_child_nav',
            '{{%nav_item_child}}', 'nav_id',
            '{{%nav}}', 'id',
            'CASCADE', 'CASCADE'
        );
        $this->addForeignKey('FK_nav_item_child_nav_item_1',
            '{{%nav_item_child}}', 'parent_name',
            '{{%nav_item}}', 'name',
            'CASCADE', 'CASCADE'
        );
        $this->addForeignKey('FK_nav_item_child_nav_item_2',
            '{{%nav_item_child}}', 'child_name',
            '{{%nav_item}}', 'name',
            'CASCADE', 'CASCADE'
        );

    }

    public function safeDown()
    {
        $this->dropTable('{{%nav_item_child}}');
        $this->dropTable('{{%nav_item}}');
        $this->dropTable('{{%nav_assignment}}');
        $this->dropTable('{{%nav}}');
    }
}
