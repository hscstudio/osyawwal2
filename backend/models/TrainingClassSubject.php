<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "training_class_subject".
 *
 * @property integer $id
 * @property integer $training_class_id
 * @property integer $program_subject_id
 * @property integer $status
 * @property string $created
 * @property integer $created_by
 * @property string $modified
 * @property integer $modified_by
 *
 * @property TrainingClass $trainingClass
 * @property TrainingClassSubjectTrainerEvaluation[] $trainingClassSubjectTrainerEvaluations
 * @property TrainingSchedule[] $trainingSchedules
 */
class TrainingClassSubject extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'training_class_subject';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['training_class_id', 'program_subject_id'], 'required'],
            [['training_class_id', 'program_subject_id', 'status', 'created_by', 'modified_by'], 'integer'],
            [['created', 'modified'], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'training_class_id' => Yii::t('app', 'BPPK_TEXT_TRAINING_CLASS_ID'),
            'program_subject_id' => Yii::t('app', 'BPPK_TEXT_PROGRAM_SUBJECT_ID'),
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
    public function getTrainingClass()
    {
        return $this->hasOne(TrainingClass::className(), ['id' => 'training_class_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTrainingClassSubjectTrainerEvaluations()
    {
        return $this->hasMany(TrainingClassSubjectTrainerEvaluation::className(), ['training_class_subject_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTrainingSchedules()
    {
        return $this->hasMany(TrainingSchedule::className(), ['training_class_subject_id' => 'id']);
    }
	
	public function getProgramSubject()
    {
        return $this->hasOne(ProgramSubject::className(), ['id' => 'program_subject_id']);
    }
	
}
