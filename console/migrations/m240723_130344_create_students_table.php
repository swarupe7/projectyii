<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%students}}`.
 */
class m240723_130344_create_students_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%students}}', [
            'id' => $this->primaryKey(),
            'firstname'=>$this->string()->notNull(),
            'lastname'=>$this->string()->notNull(),
            'number'=>$this->string()->notNull(),
            'gender'=>$this->string()->notNull(),
            'email'=>$this->string()->notNull(),
            'study'=>$this->string()->notNull(),
            'hobbies'=>$this->string()->notNull(), 
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%students}}');
    }
}
