<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "reference".
 *
 * @property integer $id
 * @property integer $parent_id
 * @property string $type
 * @property string $name
 * @property string $value
 * @property string $sort
 * @property integer $status 
 * @property string $created
 * @property integer $created_by
 * @property string $modified
 * @property integer $modified_by
 *
 * @property ObjectReference[] $objectReferences
 */
class Reference extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'reference';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['parent_id', 'status', 'created_by', 'modified_by', 'sort'], 'integer'],
            [['name'], 'required'],
            [['created', 'modified'], 'safe'],
            [['name', 'value','type'], 'string', 'max' => 255],
            [['name'], 'unique']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'parent_id' => Yii::t('app', 'SYSTEM_TEXT_PARENT_ID'),
			'type' => Yii::t('app', 'BPPK_TEXT_TYPE'),
            'name' => Yii::t('app', 'BPPK_TEXT_NAME'),
            'value' => Yii::t('app', 'BPPK_TEXT_VALUE'),
			'sort' => Yii::t('app', 'BPPK_TEXT_SORT'),
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
    public function getObjectReferences()
    {
        return $this->hasMany(ObjectReference::className(), ['object_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSatker()
    {
        return $this->hasOne(Satker::className(), ['reference_id' => 'id']);
    }
}
