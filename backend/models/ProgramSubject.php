<?php

namespace backend\models;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\AttributeBehavior;
use yii\db\Expression;
use yii\behaviors\BlameableBehavior;
use hscstudio\heart\components\HistoryBehavior;
/**
 * This is the model class for table "program_subject".
 *
 * @property integer $id
 * @property integer $program_id
 * @property integer $program_revision
 * @property integer $type
 * @property string $name
 * @property string $hours
 * @property integer $sort
 * @property integer $test
 * @property string $stage
 * @property integer $status
 * @property string $created
 * @property integer $created_by
 * @property string $modified
 * @property integer $modified_by
 *
 * @property Program $program
 */
class ProgramSubject extends \yii\db\ActiveRecord
{
	public $create_revision = false;
   /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'program_subject';
    }

	public function behaviors()
    {
        return [
			'timestamp' => [
                'class' => TimestampBehavior::className(),
                'attributes' => [
                        ActiveRecord::EVENT_BEFORE_INSERT => ['created','modified'],
                        ActiveRecord::EVENT_BEFORE_UPDATE => 'modified',
                ],
                'value' => new Expression('NOW()'),
            ],
            'blameable' => [
                'class' => BlameableBehavior::className(),
                'attributes' => [
                        ActiveRecord::EVENT_BEFORE_INSERT => ['created_by','modified_by'],
                        ActiveRecord::EVENT_BEFORE_UPDATE => 'modified_by',
                ],
            ],
			'history' => [
                'class'=>HistoryBehavior::className(),
                'attributes'=>[
					'id', 'program_id', 'program_revision', 'type', 'sort', 'test', 'status', 'created_by', 'modified_by',
					'name', 'hours', 'stage', 'created', 'modified',				
				],
				'historyClass'=>ProgramSubjectHistory::className(),
            ]
        ];
    }
	
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['program_id', 'type', 'name', 'hours'], 'required'],
            [['program_id', 'program_revision', 'type', 'sort', 'test', 'status', 'created_by', 'modified_by'], 'integer'],
            [['hours'], 'number'],
            [['stage', 'created', 'modified'], 'safe'],
            [['name'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'BPPK_TEXT_ID'),
            'revision' => Yii::t('app', 'BPPK_TEXT_REVISION'),
            'program_id' => Yii::t('app', 'BPPK_TEXT_PROGRAM_ID'),
            'program_revision' => Yii::t('app', 'BPPK_TEXT_PROGRAM_REVISION'),
            'type' => Yii::t('app', 'BPPK_TEXT_TYPE'),
            'name' => Yii::t('app', 'BPPK_TEXT_NAME'),
            'hours' => Yii::t('app', 'BPPK_TEXT_HOURS'),
            'sort' => Yii::t('app', 'BPPK_TEXT_SORT'),
            'test' => Yii::t('app', 'BPPK_TEXT_TEST'),
            'stage' => Yii::t('app', 'BPPK_TEXT_STAGE'),
            'status' => Yii::t('app', 'BPPK_TEXT_STATUS'),
            'created' => Yii::t('app', 'SYSTEM_TEXT_CREATED'),
            'created_by' => Yii::t('app', 'SYSTEM_TEXT_CREATED_ID'),
            'modified' => Yii::t('app', 'SYSTEM_TEXT_MODIFIED'),
            'modified_by' => Yii::t('app', 'SYSTEM_TEXT_MODIFIED_BY'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProgram()
    {
        return $this->hasOne(Program::className(), ['id' => 'program_id']);
    }
	
	/** 
    * @return \yii\db\ActiveQuery 
    */ 
   public function getReference() 
   { 
       return $this->hasOne(Reference::className(), ['id' => 'type']); 
   } 
}
