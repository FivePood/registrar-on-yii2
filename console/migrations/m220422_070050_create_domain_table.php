<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%domain}}`.
 */
class m220422_070050_create_domain_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%domain}}', [
            'domainId' => $this->primaryKey(),
            'name' => $this->string(),
            'registeredId' => $this->integer(),
            'handle' => $this->string(),
            'comment' => $this->string(),
            'createdAt' => $this->bigInteger(),
            'updatedAt' => $this->bigInteger(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%domain}}');
    }
}
