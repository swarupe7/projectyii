<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "courses".
 *
 * @property int $id
 * @property string $course
 * @property int|null $fee
 *
 * @property Linking[] $linkings
 */
class Courses extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'courses';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['course'], 'required'],
            [['fee'], 'integer'],
            [['course'], 'string', 'max' => 255],
            [['course'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'course' => 'Course',
            'fee' => 'Fee',
        ];
    }

    /**
     * Gets query for [[Linkings]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getLinkings()
    {
        return $this->hasMany(Linking::class, ['course_id' => 'id']);
    }
}
