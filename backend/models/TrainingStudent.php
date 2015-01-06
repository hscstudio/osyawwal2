<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "training_student".
 *
 * @property integer $id
 * @property integer $training_id
 * @property integer $student_id
 * @property integer $status
 * @property string $created
 * @property integer $created_by
 * @property string $modified
 * @property integer $modified_by
 *
 * @property TrainingClassStudent[] $trainingClassStudents
 * @property Student $student
 * @property Training $training
 */
class TrainingStudent extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'training_student';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['training_id', 'student_id'], 'required'],
            [['training_id', 'student_id', 'status', 'created_by', 'modified_by'], 'integer'],
            [['created', 'modified'], 'safe'],
			[['note'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'training_id' => Yii::t('app', 'BPPK_TEXT_TRAINING_ID'),
            'student_id' => Yii::t('app', 'BPPK_TEXT_STUDENT_ID'),
			'note' => Yii::t('app', 'BPPK_TEXT_NOTE'),
            'status' => Yii::t('app', 'BPPK_TEXT_STATUS'),
            'created' => Yii::t('app', 'SYSTEM_TEXT_CREATED'),
            'created_by' => Yii::t('app', 'SYSTEM_TEXT_CREATED_BY'),
            'modified' => Yii::t('app', 'SYSTEM_TEXT_MODIFIED'),
            'modified_by' => Yii::t('app', 'SYSTEM_TEXT_MODIFIED_BY'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTrainingClassStudents()
    {
        return $this->hasMany(TrainingClassStudent::className(), ['training_student_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStudent()
    {
        return $this->hasOne(Student::className(), ['person_id' => 'student_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTraining()
    {
        return $this->hasOne(Training::className(), ['activity_id' => 'training_id']);
    }
}
