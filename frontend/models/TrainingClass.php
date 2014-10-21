<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "training_class".
 *
 * @property integer $id
 * @property integer $training_id
 * @property string $class
 * @property integer $status
 * @property string $created
 * @property integer $created_by
 * @property string $modified
 * @property integer $modified_by
 *
 * @property Training $training
 * @property TrainingClassStudent[] $trainingClassStudents
 * @property TrainingClassSubject[] $trainingClassSubjects
 * @property TrainingSchedule[] $trainingSchedules
 */
class TrainingClass extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'training_class';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['training_id', 'class'], 'required'],
            [['training_id', 'status', 'created_by', 'modified_by'], 'integer'],
            [['created', 'modified'], 'safe'],
            [['class'], 'string', 'max' => 5]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'training_id' => 'Training ID',
            'class' => 'Class',
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
    public function getTraining()
    {
        return $this->hasOne(Training::className(), ['activity_id' => 'training_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTrainingClassStudents()
    {
        return $this->hasMany(TrainingClassStudent::className(), ['training_class_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTrainingClassSubjects()
    {
        return $this->hasMany(TrainingClassSubject::className(), ['training_class_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTrainingSchedules()
    {
        return $this->hasMany(TrainingSchedule::className(), ['training_class_id' => 'id']);
    }
}
