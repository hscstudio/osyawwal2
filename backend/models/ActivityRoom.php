<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "activity_room".
 *
 * @property integer $activity_id
 * @property integer $room_id
 * @property string $start
 * @property string $end
 * @property string $note
 * @property integer $status
 * @property string $created
 * @property integer $created_by
 * @property string $modified
 * @property integer $modified_by
 *
 * @property Activity $activity
 * @property Room $room
 */
class ActivityRoom extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'activity_room';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['activity_id', 'room_id', 'status'], 'required'],
            [['activity_id', 'room_id', 'status', 'created_by', 'modified_by'], 'integer'],
            [['start', 'end', 'created', 'modified'], 'safe'],
            [['note'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'activity_id' => Yii::t('app', 'BPPK_TEXT_ACTIVITY_ID'),
            'room_id' => Yii::t('app', 'BPPK_TEXT_ROOM_ID'),
            'start' => Yii::t('app', 'BPPK_TEXT_START'),
            'end' => Yii::t('app', 'BPPK_TEXT_END'),
            'note' => Yii::t('app', 'BPPK_TEXT_NOTE'),
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
    public function getActivity()
    {
        return $this->hasOne(Activity::className(), ['id' => 'activity_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRoom()
    {
        return $this->hasOne(Room::className(), ['id' => 'room_id']);
    }
}
