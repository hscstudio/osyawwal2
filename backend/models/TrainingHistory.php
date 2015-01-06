<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "training_history".
 *
 * @property integer $activity_id
 * @property integer $program_id
 * @property integer $program_revision
 * @property string $number
 * @property string $note
 * @property integer $regular
 * @property string $stakeholder
 * @property integer $student_count_plan
 * @property integer $class_count_plan
 * @property string $execution_sk
 * @property string $result_sk
 * @property string $cost_source
 * @property string $cost_plan
 * @property string $cost_real
 * @property integer $approved_status
 * @property string $approved_note
 * @property string $approved_date
 * @property integer $approved_by
 */
class TrainingHistory extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'training_history';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['activity_id', 'program_id','revision'], 'required'],
            [['revision','activity_id', 'program_id', 'program_revision', 'regular', 'student_count_plan', 'class_count_plan', 'approved_status', 'approved_by'], 'integer'],
            [['cost_plan', 'cost_real'], 'number'],
            [['approved_date'], 'safe'],
			[['number_forma','number_formb'], 'string', 'max' => 5],
            [['number'], 'string', 'max' => 100],
            [['note','note_formb', 'stakeholder', 'execution_sk', 'result_sk', 'cost_source', 'approved_note'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'activity_id' => Yii::t('app', 'BPPK_TEXT_ACTIVITY_ID'),
            'program_id' => Yii::t('app', 'BPPK_TEXT_PROGRAM_ID'),
            'program_revision' => Yii::t('app', 'BPPK_TEXT_PROGRAM_REVISION'),
            'number_forma' => Yii::t('app', 'BPPK_TEXT_NUMBER_FORM_A'),
            'number_formb' => Yii::t('app', 'BPPK_TEXT_NUMBER_FORM_B'),
            'number' => Yii::t('app', 'BPPK_TEXT_NUMBER'),
            'note' => Yii::t('app', 'BPPK_TEXT_NOTE'),
            'note_formb' => Yii::t('app', 'BPPK_TEXT_NOTE_FORM_B'),
            'regular' => Yii::t('app', 'BPPK_TEXT_REGULAR'),
            'stakeholder' => Yii::t('app', 'BPPK_TEXT_STAKEHOLDER'),
            'student_count_plan' => Yii::t('app', 'BPPK_TEXT_STUDENT_COUNT_PLAN'),
            'class_count_plan' => Yii::t('app', 'BPPK_TEXT_CLASS_COUNT_PLAN'),
            'execution_sk' => Yii::t('app', 'BPPK_TEXT_EXECUTION_SK'),
            'result_sk' => Yii::t('app', 'BPPK_TEXT_RESULT_SK'),
            'cost_source' => Yii::t('app', 'BPPK_TEXT_COST_SOURCE'),
            'cost_plan' => Yii::t('app', 'BPPK_TEXT_COST_PLAN'),
            'cost_real' => Yii::t('app', 'BPPK_TEXT_COST_REAL'),
            'approved_status' => Yii::t('app', 'BPPK_TEXT_APPROVED_STATUS'),
            'approved_note' => Yii::t('app', 'BPPK_TEXT_APPROVED_NOTE'),
            'approved_date' => Yii::t('app', 'BPPK_TEXT_APPROVED_DATE'),
            'approved_by' => Yii::t('app', 'BPPK_TEXT_APPROVED_BY'),
        ];
    }
	
	public static function getRevision($id){  
      return self::find()->where(['activity_id' => $id,])->max('revision');  
    } 
}
