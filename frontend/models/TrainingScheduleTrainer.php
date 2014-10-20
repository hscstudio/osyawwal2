<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "training_schedule_trainer".
 *
 * @property integer $id
 * @property integer $training_schedule_id
 * @property integer $type
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
            [['training_schedule_id', 'type', 'trainer_id'], 'required'],
            [['training_schedule_id', 'type', 'trainer_id', 'cost', 'status', 'created_by', 'modified_by'], 'integer'],
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
            'id' => 'ID',
            'training_schedule_id' => 'Training Schedule ID',
            'type' => 'Type',
            'trainer_id' => 'Trainer ID',
            'hours' => 'Hours',
            'reason' => 'Reason',
            'cost' => 'Cost',
            'status' => 'Status',
            'created' => 'Created',
            'created_by' => 'Created By',
            'modified' => 'Modified',
            'modified_by' => 'Modified By',
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
}
