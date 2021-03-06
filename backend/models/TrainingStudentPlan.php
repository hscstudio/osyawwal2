<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "training_student_plan".
 *
 * @property integer $training_id
 * @property string $spread
 * @property integer $status
 * @property string $created
 * @property integer $created_by
 * @property string $modified
 * @property integer $modified_by
 *
 * @property Training $training
 */
class TrainingStudentPlan extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'training_student_plan';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['training_id'], 'required'],
            [['training_id', 'status', 'created_by', 'modified_by'], 'integer'],
            [['created', 'modified'], 'safe'],
            [['spread'], 'string', 'max' => 500]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'training_id' => Yii::t('app', 'BPPK_TEXT_TRAINING_ID'),
            'spread' => Yii::t('app', 'BPPK_TEXT_SPREAD'),
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
	
	public function setJsonSpread($spread)
    {
		if(is_array($spread)){
			$this->spread = \yii\helpers\Json::encode($spread);
		}
		else{
			$this->spread = NULL;
		}
    }
	
	public function getSpread()
    {
        return \yii\helpers\Json::decode($this->spread);		
    }
	
	public function getStudentCountByUnit($unit)
    {
		$student_spreads = $this->getSpread();
		if(isset($student_spreads[$unit])){
			return (int)$student_spreads[$unit];
		}
		return 0;
    }
}
