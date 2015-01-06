<?php

namespace backend\models;

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
 * @property TrainingClassSubject $trainingClassSubject
 * @property TrainingClass $trainingClass
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
            'id' => Yii::t('app', 'ID'),
            'training_class_id' => Yii::t('app', 'BPPK_TEXT_TRAINING_CLASS_ID'),
            'training_class_subject_id' => Yii::t('app', 'BPPK_TEXT_TRAINING_CLASS_SUBJECT_ID'),
            'activity_room_id' => Yii::t('app', 'BPPK_TEXT_ACTIVITY_ROOM_ID'),
            'activity' => Yii::t('app', 'BPPK_TEXT_ACTIVITY'),
            'pic' => Yii::t('app', 'BPPK_TEXT_PIC'),
            'hours' => Yii::t('app', 'BPPK_TEXT_HOURS'),
            'start' => Yii::t('app', 'BPPK_TEXT_START'),
            'end' => Yii::t('app', 'BPPK_TEXT_END'),
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
    public function getTrainingClassSubject()
    {
        return $this->hasOne(TrainingClassSubject::className(), ['id' => 'training_class_subject_id']);
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
    public function getTrainingScheduleTrainers()
    {
        return $this->hasMany(TrainingScheduleTrainer::className(), ['training_schedule_id' => 'id']);
    }
}
