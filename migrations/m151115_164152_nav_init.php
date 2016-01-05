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

        $this->createTable('{{%nav__item}}', [
            'name' => $this->string(),
            'label' => $this->string()->notNull(),
            'url' => $this->string(),
            'description' => $this->text(),
            'PRIMARY KEY (`name`)',
        ], $tableOptions);

        $this->createTable('{{%nav}}', [
            'id' => $this->primaryKey(),
            'title' => $this->string()->notNull(),
            'description' => $this->text(),
            'root_item' => $this->string()->notNull()->defaultValue('root'),
            'FOREIGN KEY (`root_item`) REFERENCES {{%nav__item}} (`name`) ON DELETE RESTRICT ON UPDATE CASCADE',
        ], $tableOptions);

        $this->createTable('{{%nav__assignment}}', [
            'id' => $this->primaryKey(),
            'nav_id' => $this->integer()->notNull(),
            'user_id' => $this->integer()->notNull(),
            'widget' => $this->string()->notNull(),
            'route' => $this->string(),
            'FOREIGN KEY (`nav_id`) REFERENCES {{%nav}} (`id`) ON DELETE CASCADE ON UPDATE CASCADE',
            'KEY (`user_id`)',
        ], $tableOptions);

        $this->createTable('{{%nav__item_child}}', [
            'nav_id' => $this->integer()->notNull(),
            'parent_name' => $this->string()->notNull(),
            'child_name' => $this->string()->notNull(),
            'weight' => $this->smallInteger()->notNull()->defaultValue(0),
            'PRIMARY KEY (`nav_id`, `parent_name`, `child_name`)',
            'FOREIGN KEY (`nav_id`) REFERENCES {{%nav}} (`id`) ON DELETE CASCADE ON UPDATE CASCADE',
            'FOREIGN KEY (`parent_name`) REFERENCES {{%nav__item}} (`name`) ON DELETE CASCADE ON UPDATE CASCADE',
            'FOREIGN KEY (`child_name`) REFERENCES {{%nav__item}} (`name`) ON DELETE CASCADE ON UPDATE CASCADE',
        ], $tableOptions);

        $this->insert('{{%nav__item}}', ['name' => 'root', 'label' => 'root']);
    }

    public function safeDown()
    {
        $this->dropTable('{{%nav__item_child}}');
        $this->dropTable('{{%nav__assignment}}');
        $this->dropTable('{{%nav}}');
        $this->dropTable('{{%nav__item}}');
    }
}
