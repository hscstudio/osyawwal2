<?php

namespace backend\models;

use Yii;
use yii\db\ActiveRecord;								
use yii\behaviors\TimestampBehavior;
use yii\behaviors\AttributeBehavior;
use yii\db\Expression;
use yii\behaviors\BlameableBehavior;
/**
 * This is the model class for table "room".
 *
 * @property integer $id
 * @property integer $satker_id
 * @property string $code
 * @property string $name
 * @property integer $capacity
 * @property integer $owner
 * @property integer $computer
 * @property integer $hostel
 * @property string $address
 * @property integer $status
 * @property string $created
 * @property integer $created_by
 * @property string $modified
 * @property integer $modified_by
 *
 * @property ActivityRoom[] $activityRooms
 * @property Activity[] $activities
 */
class Room extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'room';
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
            [['satker_id', 'code', 'name'], 'required'],
            [['satker_id', 'capacity', 'owner', 'computer', 'hostel', 'status', 'created_by', 'modified_by'], 'integer'],
            [['created', 'modified'], 'safe'],
            [['code'], 'string', 'max' => 25],
            [['name', 'address'], 'string', 'max' => 255],
            [['code'], 'unique']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'satker_id' => Yii::t('app', 'BPPK_TEXT_SATKER_ID'),
            'code' => Yii::t('app', 'BPPK_TEXT_CODE'),
            'name' => Yii::t('app', 'BPPK_TEXT_NAME'),
            'capacity' => Yii::t('app', 'BPPK_TEXT_CAPACITY'),
            'owner' => Yii::t('app', 'BPPK_TEXT_OWNER'),
            'computer' => Yii::t('app', 'BPPK_TEXT_COMPUTER'),
            'hostel' => Yii::t('app', 'BPPK_TEXT_HOSTEL'),
            'address' => Yii::t('app', 'BPPK_TEXT_ADDRESS'),
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
    public function getActivityRooms()
    {
        return $this->hasMany(ActivityRoom::className(), ['room_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getActivities()
    {
        return $this->hasMany(Activity::className(), ['id' => 'activity_id'])->viaTable('{activity_room}', ['room_id' => 'id']);
    }
	
	public function getSatker()
    {
        return $this->hasOne(Reference::className(), [
			'id' => 'satker_id',			
		])
		->andWhere([
			'type' => 'satker'
		]);
    }
}
