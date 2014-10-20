<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "training_execution_evaluation".
 *
 * @property integer $id
 * @property integer $training_class_student_id
 * @property string $value
 * @property string $text1
 * @property string $text2
 * @property string $text3
 * @property string $text4
 * @property string $text5
 * @property integer $overall
 * @property string $comment
 * @property integer $status
 * @property string $created
 * @property integer $created_by
 * @property string $modified
 * @property integer $modified_by
 *
 * @property TrainingClassStudent $trainingClassStudent
 */
class TrainingExecutionEvaluation extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'training_execution_evaluation';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['training_class_student_id'], 'required'],
            [['training_class_student_id', 'overall', 'status', 'created_by', 'modified_by'], 'integer'],
            [['created', 'modified'], 'safe'],
            [['value'], 'string', 'max' => 255],
            [['text1', 'text2', 'text3', 'text4', 'text5'], 'string', 'max' => 500],
            [['comment'], 'string', 'max' => 3000]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'training_class_student_id' => 'Training Class Student ID',
            'value' => 'Value',
            'text1' => 'Text1',
            'text2' => 'Text2',
            'text3' => 'Text3',
            'text4' => 'Text4',
            'text5' => 'Text5',
            'overall' => 'Overall',
            'comment' => 'Comment',
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
    public function getTrainingClassStudent()
    {
        return $this->hasOne(TrainingClassStudent::className(), ['id' => 'training_class_student_id']);
    }
}
