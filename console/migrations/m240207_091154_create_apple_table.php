<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%apple}}`.
 */
class m240207_091154_create_apple_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%apple}}', [
            'id' => $this->primaryKey(),
            'color' => $this->integer()->unsigned()->notNull(),
            'created' => $this->timestamp()->notNull()->defaultExpression('NOW()'),
            'updated' => $this->timestamp()->notNull()->defaultExpression('CURRENT_TIMESTAMP')->append('ON UPDATE NOW()'),
            'state' => $this->tinyInteger()->unsigned()->notNull()->defaultValue(0),
            'prs' => $this->decimal(3,2)->notNull()->defaultValue(1),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%apple}}');
    }
}
