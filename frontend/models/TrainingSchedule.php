<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "training_schedule".
 *
 * @property integer $id
 * @property integer $training_class_id
 * @property integer $training_class_subject_id
 * @property integer $activity_room_id
 * @property string $activity
 * @property string $pic
 * @property string $hours
 * @property string $start
 * @property string $end
 * @property integer $status
 * @property string $created
 * @property integer $created_by
 * @property string $modified
 * @property integer $modified_by
 *
 * @property TrainingClass $trainingClass
 * @property TrainingClassSubject $trainingClassSubject
 * @property TrainingScheduleTrainer[] $trainingScheduleTrainers
 */
class TrainingSchedule extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'training_schedule';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['training_class_id', 'training_class_subject_id', 'activity_room_id'], 'required'],
            [['training_class_id', 'training_class_subject_id', 'activity_room_id', 'status', 'created_by', 'modified_by'], 'integer'],
            [['hours'], 'number'],
            [['start', 'end', 'created', 'modified'], 'safe'],
            [['activity'], 'string', 'max' => 255],
            [['pic'], 'string', 'max' => 100]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'training_class_id' => 'Training Class ID',
            'training_class_subject_id' => 'Training Class Subject ID',
            'activity_room_id' => 'Activity Room ID',
            'activity' => 'Activity',
            'pic' => 'Pic',
            'hours' => 'Hours',
            'start' => 'Start',
            'end' => 'End',
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
    public function getTrainingClass()
    {
        return $this->hasOne(TrainingClass::className(), ['id' => 'training_class_id']);
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
    public function getTrainingScheduleTrainers()
    {
        return $this->hasMany(TrainingScheduleTrainer::className(), ['training_schedule_id' => 'id']);
    }
}
