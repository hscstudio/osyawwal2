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
 * This is the model class for table "program".
 *
 * @property integer $id
 * @property integer $satker_id
 * @property string $number
 * @property string $name
 * @property string $hours
 * @property integer $days
 * @property integer $test
 * @property string $note
 * @property string $stage
 * @property string $category
 * @property integer $validation_status
 * @property string $validation_note
 * @property integer $status
 * @property string $created
 * @property integer $created_by
 * @property string $modified
 * @property integer $modified_by
 *
 * @property ProgramSubject[] $programSubjects
 * @property Training[] $trainings
 */
class Program extends ActiveRecord
{
    public $create_revision = false;
	/**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'program';
    }

	/**
     * @inheritdoc
     */	
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
					'id',
					'name','hours',
					'days', 'test', 'validation_status', 'status', 'created_by', 'modified_by', 'satker_id',
					'stage','created', 'modified',
					'number',
					'note', 'category', 'validation_note'
				],
				'historyClass'=>ProgramHistory::className(),
            ]
        ];
    }
	
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['hours'], 'number'],
            [['days', 'test', 'validation_status', 'status', 'created_by', 'modified_by', 'satker_id'], 'integer'],
            [['stage','created', 'modified'], 'safe'],
            [['number'], 'string', 'max' => 15],
            [['name', 'note', 'category', 'validation_note'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'BPPK_TEXT_ID'),
			'satker_id' => Yii::t('app', 'BPPK_TEXT_SATKER_ID'),
            'number' => Yii::t('app', 'BPPK_TEXT_NUMBER'),
            'name' => Yii::t('app', 'BPPK_TEXT_NAME'),
            'hours' => Yii::t('app', 'BPPK_TEXT_HOURS'),
            'days' => Yii::t('app', 'BPPK_TEXT_DAYS'),
            'test' => Yii::t('app', 'BPPK_TEXT_TEST'),
            'note' => Yii::t('app', 'BPPK_TEXT_NOTE'),
            'stage' => Yii::t('app', 'BPPK_TEXT_STAGE'),
            'category' => Yii::t('app', 'BPPK_TEXT_CATEGORY'),
            'validation_status' => Yii::t('app', 'BPPK_TEXT_VALIDATION_STATUS'),
            'validation_note' => Yii::t('app', 'BPPK_TEXT_VALIDATION_NOTE'),
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
    public function getProgramSubjects()
    {
        return $this->hasMany(ProgramSubject::className(), ['program_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTrainings()
    {
        return $this->hasMany(Training::className(), ['program_id' => 'id']);
    }
	
	public function getSatker()
    {
        return $this->hasOne(Reference::className(), ['id' => 'satker_id']);
    }
	 /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     */
    public function setSatker($satker='current')
    {
        if($satker=='current'){
			$this->satker_id = Yii::$app->user->identity->employee->satker_id;
		}
    }
	
	/**
     * @inheritdoc
     * @return ProgramQuery
     */
    public static function find()
    {
        return new ProgramQuery(get_called_class());
    }
}

class ProgramQuery extends \yii\db\ActiveQuery
{
    public function currentSatker()
    {
        $this->andWhere(['satker_id'=>(int)Yii::$app->user->identity->employee->satker_id]);
        return $this;
    }
	
	public function active($status=1)
    {
        $this->andWhere(['status'=>$status]);
        return $this;
    }
}

