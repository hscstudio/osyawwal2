<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "training_class_student_attendance".
 *
 * @property integer $id
 * @property integer $training_schedule_id
 * @property integer $training_class_student_id
 * @property string $hours
 * @property string $reason
 * @property integer $status
 * @property string $created
 * @property integer $created_by
 * @property string $modified
 * @property integer $modified_by
 *
 * @property TrainingClassStudent $trainingClassStudent
 */
class TrainingClassStudentAttendance extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'training_class_student_attendance';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['training_schedule_id', 'training_class_student_id'], 'required'],
            [['training_schedule_id', 'training_class_student_id', 'status', 'created_by', 'modified_by'], 'integer'],
            [['hours'], 'number'],
            [['created', 'modified'], 'safe'],
            [['reason'], 'string', 'max' => 255]
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
            'training_class_student_id' => Yii::t('app', 'BPPK_TEXT_TRAINING_CLASS_STUDENT_ID'),
            'hours' => Yii::t('app', 'BPPK_TEXT_HOURS'),
            'reason' => Yii::t('app', 'BPPK_TEXT_REASON'),
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
    public function getTrainingClassStudent()
    {
        return $this->hasOne(TrainingClassStudent::className(), ['id' => 'training_class_student_id']);
    }
}
