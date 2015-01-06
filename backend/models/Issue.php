<?php

namespace backend\models;

use Yii;
use yii\db\ActiveRecord;								
use yii\behaviors\TimestampBehavior;
use yii\behaviors\AttributeBehavior;
use yii\db\Expression;
use yii\behaviors\BlameableBehavior;

/**
 * This is the model class for table "issue".
 *
 * @property integer $id
 * @property integer $parent_id
 * @property string $subject
 * @property string $content
 * @property string $label
 * @property integer $status
 * @property string $created
 * @property integer $created_by
 * @property string $modified
 * @property integer $modified_by
 */
class Issue extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'issue';
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
        ];
    }
	
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['parent_id', 'status', 'created_by', 'modified_by'], 'integer'],
            [['subject', 'content'], 'required'],
            [['content'], 'string'],
            [['created', 'modified'], 'safe'],
            [['subject', 'label', 'attachment'], 'string', 'max' => 255],			
			[['attachment'], 'file', 'extensions' => 'jpg, png, gif, zip', 
				'mimeTypes' => 'image/jpeg, image/png, image/gif, application/zip','skipOnEmpty' => true],
        ];
    }

	
	
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'parent_id' => Yii::t('app', 'SYSTEM_TEXT_PARENT_ID'),
            'subject' => Yii::t('app', 'SYSTEM_TEXT_SUBJECT'),
            'content' => Yii::t('app', 'SYSTEM_TEXT_CONTENT'),
            'label' => Yii::t('app', 'SYSTEM_TEXT_LABEL'),
            'status' => Yii::t('app', 'SYSTEM_TEXT_STATUS'),
            'created' => Yii::t('app', 'SYSTEM_TEXT_CREATED'),
            'created_by' => Yii::t('app', 'SYSTEM_TEXT_CREATED_BY'),
            'modified' => Yii::t('app', 'SYSTEM_TEXT_MODIFIED'),
            'modified_by' => Yii::t('app', 'SYSTEM_TEXT_MODIFIED_BY'),
        ];
    }
	
	public function getLastLabel($parent_id){
		$obj = self::find()
			->where([
				'parent_id'=>$parent_id,
				'subject'=>'label',
			])
			->orderBy('id DESC')
			->one();
		if(!empty($obj)){
			return $obj->label;
		}		
		else{
			return '';
		}
			
	}
	
	public function getLastStatus($parent_id){
		$obj = self::find()
			->where([
				'parent_id'=>$parent_id,
				'subject'=>'status',
			])
			->orderBy('id DESC')
			->one();
		if(!empty($obj)){
			return $obj->status;
		}		
		else{
			return '';
		}
			
	}
}
