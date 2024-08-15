<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%linking}}`.
 */
class m240803_114522_create_linking_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%linking}}', [
            'id' => $this->primaryKey(),
            'student_id'=>$this->integer()->notNull()->unique(),
            'course_id'=>$this->integer()->notNull(),
        ]);
        
        $this->createIndex('idx_linking_student_id','linking','student_id');

        $this->addForeignKey('fk_linking_student_id', 'linking','student_id','students','id','CASCADE');

        $this->createIndex('idx_linking_course_id','linking','course_id');

        $this->addForeignKey('fk_linking_course_id', 'linking','course_id','courses','id','CASCADE');

        $this->insert('{{%linking}}',['student_id'=>2, 'course_id'=>1]);

        $this->insert('{{%linking}}',['student_id'=>3, 'course_id'=>1]);

        $this->insert('{{%linking}}',['student_id'=>4, 'course_id'=>2]);

        $this->insert('{{%linking}}',['student_id'=>5, 'course_id'=>3]);

        $this->insert('{{%linking}}',['student_id'=>6, 'course_id'=>3]);

        $this->insert('{{%linking}}',['student_id'=>7, 'course_id'=>4]);

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {

        $this->delete('{{%linking}}', ['id' => 1]);

        $this->delete('{{%linking}}', ['id' => 2]);

        $this->delete('{{%linking}}', ['id' => 3]);

        $this->delete('{{%linking}}', ['id' => 4]);

        $this->delete('{{%linking}}', ['id' => 5]);

        $this->delete('{{%linking}}', ['id' => 6]);

        $this->dropForeignKey('fk_linking_course_id', 'linking');

        $this->dropIndex('idx_linking_course_id','linking');

        $this->dropForeignKey('fk_linking_student_id', 'linking');

        $this->dropIndex('idx_linking_student_id','linking');

        $this->dropTable('{{%linking}}');
    }
}
