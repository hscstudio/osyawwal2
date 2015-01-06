<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "training_subject_trainer_recommendation".
 *
 * @property integer $id
 * @property integer $training_id
 * @property integer $program_subject_id
 * @property integer $type
 * @property integer $trainer_id
 * @property string $note
 * @property integer $sort
 * @property integer $status
 * @property string $created
 * @property integer $created_by
 * @property string $modified
 * @property integer $modified_by
 *
 * @property Training $training
 * @property Trainer $trainer
 */
class TrainingSubjectTrainerRecommendation extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'training_subject_trainer_recommendation';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['training_id', 'program_subject_id', 'type', 'trainer_id'], 'required'],
            [['training_id', 'program_subject_id', 'type', 'trainer_id', 'sort', 'status', 'created_by', 'modified_by'], 'integer'],
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
            'program_subject_id' => Yii::t('app', 'BPPK_TEXT_PROGRAM_SUBJECT_ID'),
            'type' => Yii::t('app', 'BPPK_TEXT_TYPE'),
            'trainer_id' => Yii::t('app', 'BPPK_TEXT_TRAINER_ID'),
            'note' => Yii::t('app', 'BPPK_TEXT_NOTE'),
            'sort' => Yii::t('app', 'BPPK_TEXT_SORT'),
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
    public function getTraining()
    {
        return $this->hasOne(Training::className(), ['activity_id' => 'training_id']);
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
	public function getReference()
	{
	   return $this->hasOne(Reference::className(), ['id' => 'type']);
	}
}
