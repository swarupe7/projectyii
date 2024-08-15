<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "students".
 *
 * @property int $id
 * @property string $firstname
 * @property string $lastname
 * @property string $number
 * @property string $gender
 * @property string $email
 * @property string $study
 * @property string $hobbies
 */
class FormUsers extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'students';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['firstname', 'lastname', 'number', 'gender', 'email', 'study', 'hobbies'], 'required'],
            ['email','email'], 
            ['study', 'required', 'message' => 'Please select your highest study.'],
            ['number','string','max'=>11],
            ['number', 'match', 'pattern' => '/^\d{10,11}$/','message' => 'Please enter number made of digits'],
            [['firstname', 'lastname',  'hobbies'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'firstname' => 'Firstname',
            'lastname' => 'Lastname',
            'number' => 'Number',
            'gender' => 'Gender',
            'email' => 'Email',
            'study' => 'Study',
            'hobbies' => 'Hobbies',
        ];
    }
}
