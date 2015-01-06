<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "training_class_student".
 *
 * @property integer $id
 * @property integer $training_id
 * @property integer $training_class_id
 * @property integer $training_student_id
 * @property string $number
 * @property integer $head_class
 * @property string $activity
 * @property string $presence
 * @property string $pre_test
 * @property string $post_test
 * @property string $test
 * @property integer $status
 * @property string $created
 * @property integer $created_by
 * @property string $modified
 * @property integer $modified_by
 *
 * @property TrainingStudent $trainingStudent
 * @property Training $training
 * @property TrainingClass $trainingClass
 * @property TrainingClassStudentAttendance[] $trainingClassStudentAttendances
 * @property TrainingExecutionEvaluation[] $trainingExecutionEvaluations
 */
class TrainingClassStudent extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'training_class_student';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['training_id', 'training_class_id', 'training_student_id'], 'required'],
            [['training_id', 'training_class_id', 'training_student_id', 'head_class', 'status', 'created_by', 'modified_by'], 'integer'],
            [['activity', 'presence', 'pre_test', 'post_test', 'test'], 'number'],
			[['number'], 'string', 'max' => 5],
            [['created', 'modified'], 'safe'],
            [['training_id', 'training_student_id'], 'unique', 'targetAttribute' => ['training_id', 'training_student_id'], 'message' => 'The combination of Training ID and Training Student ID has already been taken.']
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
            'training_class_id' => Yii::t('app', 'BPPK_TEXT_TRAINING_CLASS_ID'),
            'training_student_id' => Yii::t('app', 'BPPK_TEXT_TRAINING_STUDENT_ID'),
            'number' => Yii::t('app', 'BPPK_TEXT_NUMBER'),
            'head_class' => Yii::t('app', 'BPPK_TEXT_HEAD_CLASS'),
            'activity' => Yii::t('app', 'BPPK_TEXT_ACTIVITY'),
            'presence' => Yii::t('app', 'BPPK_TEXT_PRESENCE'),
            'pre_test' => Yii::t('app', 'BPPK_TEXT_PRE_TEST'),
            'post_test' => Yii::t('app', 'BPPK_TEXT_POST_TEST'),
            'test' => Yii::t('app', 'BPPK_TEXT_TEST'),
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
    public function getTrainingStudent()
    {
        return $this->hasOne(TrainingStudent::className(), ['id' => 'training_student_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTraining()
    {
        return $this->hasOne(Training::className(), ['activity_id' => 'training_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTrainingClass()
    {
        return $this->hasOne(TrainingClass::className(), ['id' => 'training_class_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTrainingClassStudentAttendances()
    {
        return $this->hasMany(TrainingClassStudentAttendance::className(), ['training_class_student_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTrainingExecutionEvaluations()
    {
        return $this->hasMany(TrainingExecutionEvaluation::className(), ['training_class_student_id' => 'id']);
    }
	
	 /**
     * @return \yii\db\ActiveQuery
     */
    public function getTrainingClassStudentCertificate()
    {
        return $this->hasOne(TrainingClassStudentCertificate::className(), ['training_class_student_id' => 'id']);
    }
}
