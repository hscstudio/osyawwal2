<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "student".
 *
 * @property integer $person_id
 * @property string $username
 * @property string $password_hash
 * @property string $auth_key
 * @property string $eselon2
 * @property string $eselon3
 * @property string $eselon4
 * @property integer $satker
 * @property integer $no_sk
 * @property integer $tmt_sk
 *
 * @property Person $person
 * @property TrainingClassSubjectTrainerEvaluation[] $trainingClassSubjectTrainerEvaluations
 * @property TrainingStudent[] $trainingStudents
 */
class Student extends \yii\db\ActiveRecord
{
    public $new_password;
	/**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'student';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['person_id'], 'required'],
            [['person_id', 'satker'], 'integer'],

            [['password_hash'], 'string', 'max' => 60],
            [['auth_key'], 'string', 'max' => 32],
            [['eselon2', 'eselon3', 'eselon4','no_sk',], 'string', 'max' => 255],
			[['tmt_sk'], 'safe'],
			['username', 'filter', 'filter' => 'trim'],
            ['username', 'required'],
            ['username', 'unique', 'targetClass' => '\backend\models\Student', 'message' => 'This username has already been taken.'],
            ['username', 'string', 'min' => 6, 'max' => 25],		
                       
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'person_id' => Yii::t('app', 'SYSTEM_TEXT_PERSON_ID'),
            'username' => Yii::t('app', 'SYSTEM_TEXT_USERNAME'),
            'password_hash' => Yii::t('app', 'SYSTEM_TEXT_PASSWORD_HASH'),
            'auth_key' => Yii::t('app', 'SYSTEM_TEXT_AUTH_KEY'),
            'eselon2' => Yii::t('app', 'BPPK_TEXT_ESELON2'),
            'eselon3' => Yii::t('app', 'BPPK_TEXT_ESELON3'),
            'eselon4' => Yii::t('app', 'BPPK_TEXT_ESELON4'),
            'satker' => Yii::t('app', 'BPPK_TEXT_SATKER'),
            'no_sk' => Yii::t('app', 'BPPK_TEXT_NO_SK'),
            'tmt_sk' => Yii::t('app', 'BPPK_TEXT_TMT_SK'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPerson()
    {
        return $this->hasOne(Person::className(), ['id' => 'person_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTrainingClassSubjectTrainerEvaluations()
    {
        return $this->hasMany(TrainingClassSubjectTrainerEvaluation::className(), ['student_id' => 'person_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTrainingStudents()
    {
        return $this->hasMany(TrainingStudent::className(), ['student_id' => 'person_id']);
    }
	
	/**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }
}
