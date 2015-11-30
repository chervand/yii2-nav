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

        $this->createTable('{{%nav__assignment}}', [
            'nav_id' => $this->integer()->notNull(),
            'type' => $this->smallInteger()->notNull(),
            'assignment' => $this->string()->notNull(),
        ], $tableOptions);

        $this->addPrimaryKey('PK_nav__assignment',
            '{{%nav__assignment}}', ['nav_id', 'type', 'assignment']
        );
        $this->addForeignKey('FK_nav__assignment_nav',
            '{{%nav__assignment}}', 'nav_id',
            '{{%nav}}', 'id',
            'CASCADE', 'CASCADE'
        );
        $this->createIndex('IDX_nav__assignment_type',
            '{{%nav__assignment}}', 'type'
        );

        $this->createTable('{{%nav__item}}', [
            'name' => $this->string(),
            'label' => $this->string()->notNull(),
            'url' => $this->string(),
            'description' => $this->text(),
        ], $tableOptions);

        $this->addPrimaryKey('PK_nav__item',
            '{{%nav__item}}', 'name'
        );

        $this->createTable('{{%nav__item_child}}', [
            'nav_id' => $this->integer()->notNull(),
            'parent_name' => $this->string()->notNull(),
            'child_name' => $this->string()->notNull(),
            'weight' => $this->smallInteger()->notNull()->defaultValue(0),
        ], $tableOptions);

        $this->addPrimaryKey('PK_nav__item_child',
            '{{%nav__item_child}}', ['nav_id', 'parent_name', 'child_name']
        );
        $this->addForeignKey('FK_nav__item_child_nav',
            '{{%nav__item_child}}', 'nav_id',
            '{{%nav}}', 'id',
            'CASCADE', 'CASCADE'
        );
        $this->addForeignKey('FK_nav__item_child_nav__item_1',
            '{{%nav__item_child}}', 'parent_name',
            '{{%nav__item}}', 'name',
            'CASCADE', 'CASCADE'
        );
        $this->addForeignKey('FK_nav__item_child_nav__item_2',
            '{{%nav__item_child}}', 'child_name',
            '{{%nav__item}}', 'name',
            'CASCADE', 'CASCADE'
        );

        $this->insert('{{%nav__item}}', [
            'name' => 'root',
            'label' => 'root',
            'url' => '#',
        ]);

    }

    public function safeDown()
    {
        $this->dropTable('{{%nav__item_child}}');
        $this->dropTable('{{%nav__item}}');
        $this->dropTable('{{%nav__assignment}}');
        $this->dropTable('{{%nav}}');
    }
}
