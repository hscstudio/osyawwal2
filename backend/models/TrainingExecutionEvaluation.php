<?php

namespace backend\models;

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
            'id' => Yii::t('app', 'ID'),
            'training_class_student_id' => Yii::t('app', 'BPPK_TEXT_TRAINING_CLASS_STUDENT_ID'),
            'value' => Yii::t('app', 'BPPK_TEXT_VALUE'),
            'text1' => Yii::t('app', 'BPPK_TEXT_TEXT1'),
            'text2' => Yii::t('app', 'BPPK_TEXT_TEXT2'),
            'text3' => Yii::t('app', 'BPPK_TEXT_TEXT3'),
            'text4' => Yii::t('app', 'BPPK_TEXT_TEXT4'),
            'text5' => Yii::t('app', 'BPPK_TEXT_TEXT5'),
            'overall' => Yii::t('app', 'BPPK_TEXT_OVERALL'),
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
    public function getTrainingClassStudent()
    {
        return $this->hasOne(TrainingClassStudent::className(), ['id' => 'training_class_student_id']);
    }
}
