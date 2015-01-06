<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "training_class_subject_trainer_evaluation".
 *
 * @property integer $id
 * @property integer $training_class_subject_id
 * @property integer $trainer_id
 * @property integer $student_id
 * @property string $value
 * @property string $comment
 * @property integer $status
 * @property string $created
 * @property integer $created_by
 * @property string $modified
 * @property integer $modified_by
 *
 * @property Trainer $trainer
 * @property TrainingClassSubject $trainingClassSubject
 * @property Student $student
 */
class TrainingClassSubjectTrainerEvaluation extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'training_class_subject_trainer_evaluation';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['training_class_subject_id', 'trainer_id', 'student_id'], 'required'],
            [['training_class_subject_id', 'trainer_id', 'student_id', 'status', 'created_by', 'modified_by'], 'integer'],
            [['created', 'modified'], 'safe'],
            [['value'], 'string', 'max' => 255],
            [['comment'], 'string', 'max' => 3000]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'training_class_subject_id' => Yii::t('app', 'BPPK_TEXT_TRAINING_CLASS_SUBJECT_ID'),
            'trainer_id' => Yii::t('app', 'BPPK_TEXT_TRAINER_ID'),
            'student_id' => Yii::t('app', 'BPPK_TEXT_STUDENT_ID'),
            'value' => Yii::t('app', 'BPPK_TEXT_VALUE'),
            'comment' => Yii::t('app', 'BPPK_TEXT_COMMENT'),
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
    public function getTrainer()
    {
        return $this->hasOne(Trainer::className(), ['person_id' => 'trainer_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTrainingClassSubject()
    {
        return $this->hasOne(TrainingClassSubject::className(), ['id' => 'training_class_subject_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStudent()
    {
        return $this->hasOne(Student::className(), ['person_id' => 'student_id']);
    }
}
