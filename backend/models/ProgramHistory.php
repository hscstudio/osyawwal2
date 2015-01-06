<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "program_history".
 *
 * @property integer $id
 * @property integer $revision
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
 */
class ProgramHistory extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'program_history';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'revision', 'satker_id', 'name'], 'required'],
            [['id', 'revision', 'satker_id', 'days', 'test', 'validation_status', 'status', 'created_by', 'modified_by'], 'integer'],
            [['hours'], 'number'],
            [['created', 'modified'], 'safe'],
            [['number'], 'string', 'max' => 15],
            [['name', 'note', 'stage', 'category', 'validation_note'], 'string', 'max' => 255]
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
	
	public static function getRevision($id){ 
       return self::find()->where(['id' => $id,])->max('revision'); 
	} 
}
