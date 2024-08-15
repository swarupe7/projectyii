<?php

use yii\db\Migration;

/**
 * Class m240803_111053_courses_table
 */
class m240803_111053_courses_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {

        $this->createTable('courses',[
            "id"=>$this->primaryKey(),
            "course"=>$this->string()->notNull()->unique(),
            "fee"=>$this->integer(),
        ]);

        $this->insert('courses',["course"=>"CSE","fee"=>100000]);

        $this->insert('courses',["course"=>"ECE","fee"=>80000]);

        $this->insert('courses',["course"=>"EEE","fee"=>85000]);

        $this->insert('courses',["course"=>"MECH","fee"=>70000]);

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {

        $this->delete('courses', ['id' => 4]);

        $this->delete('courses', ['id' => 3]);

        $this->delete('courses', ['id' => 2]);

        $this->delete('courses', ['id' => 1]);


       
       $this->dropTable('courses');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m240803_111053_courses_table cannot be reverted.\n";

        return false;
    }
    */
}
