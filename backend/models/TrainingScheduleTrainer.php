<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "training_schedule_trainer".
 *
 * @property integer $id
 * @property integer $training_schedule_id
 * @property integer $trainer_id
 * @property string $hours
 * @property string $reason
 * @property integer $cost
 * @property integer $status
 * @property string $created
 * @property integer $created_by
 * @property string $modified
 * @property integer $modified_by
 *
 * @property TrainingSchedule $trainingSchedule
 * @property Trainer $trainer
 */
class TrainingScheduleTrainer extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'training_schedule_trainer';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['training_schedule_id', 'trainer_id','type'], 'required'],
            [['training_schedule_id', 'trainer_id', 'cost', 'status', 'created_by', 'modified_by','type'], 'integer'],
            [['hours'], 'number'],
            [['created', 'modified'], 'safe'],
            [['reason'], 'string', 'max' => 255],
            [['training_schedule_id', 'trainer_id'], 'unique', 'targetAttribute' => ['training_schedule_id', 'trainer_id'], 'message' => 'The combination of Training Schedule ID and Trainer ID has already been taken.']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'training_schedule_id' => Yii::t('app', 'BPPK_TEXT_TRAINING_SCHEDULE_ID'),
			'type' => Yii::t('app', 'BPPK_TEXT_TYPE'),
            'trainer_id' => Yii::t('app', 'BPPK_TEXT_TRAINER_ID'),
            'hours' => Yii::t('app', 'BPPK_TEXT_HOURS'),
            'reason' => Yii::t('app', 'BPPK_TEXT_REASON'),
            'cost' => Yii::t('app', 'BPPK_TEXT_COST'),
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
    public function getTrainingSchedule()
    {
        return $this->hasOne(TrainingSchedule::className(), ['id' => 'training_schedule_id']);
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
	
	public function getTrainerType() 
   { 
       return $this->hasOne(Reference::className(), ['id' => 'type']); 
   }
}
