<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "training_class_student_certificate".
 *
 * @property integer $training_class_student_id
 * @property string $number
 * @property string $seri
 * @property string $date
 * @property integer $position
 * @property string $position_desc
 * @property integer $graduate
 * @property string $graduate_desc
 * @property string $eselon2
 * @property string $eselon3
 * @property string $eselon4
 * @property string $satker
 * @property integer $status
 * @property string $created
 * @property integer $created_by
 * @property string $modified
 * @property integer $modified_by
 */
class TrainingClassStudentCertificate extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'training_class_student_certificate';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['training_class_student_id'], 'required'],
            [['training_class_student_id', 'position', 'graduate', 'status', 'created_by', 'modified_by'], 'integer'],
            [['date', 'created', 'modified'], 'safe'],
            [['satker'], 'string'],
            [['number', 'seri'], 'string', 'max' => 50],
            [['position_desc', 'graduate_desc', 'eselon2', 'eselon3', 'eselon4'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'training_class_student_id' => Yii::t('app', 'BPPK_TEXT_TRAINING_CLASS_STUDENT_ID'),
            'number' => Yii::t('app', 'BPPK_TEXT_NUMBER'),
            'seri' => Yii::t('app', 'BPPK_TEXT_SERI'),
            'date' => Yii::t('app', 'BPPK_TEXT_DATE'),
            'position' => Yii::t('app', 'BPPK_TEXT_POSITION'),
            'position_desc' => Yii::t('app', 'BPPK_TEXT_POSITION_DESC'),
            'graduate' => Yii::t('app', 'BPPK_TEXT_GRADUATE'),
            'graduate_desc' => Yii::t('app', 'BPPK_TEXT_GRADUATE_DESC'),
            'eselon2' => Yii::t('app', 'BPPK_TEXT_ESELON2'),
            'eselon3' => Yii::t('app', 'BPPK_TEXT_ESELON3'),
            'eselon4' => Yii::t('app', 'BPPK_TEXT_ESELON4'),
            'satker' => Yii::t('app', 'BPPK_TEXT_SATKER'),
            'status' => Yii::t('app', 'BPPK_TEXT_STATUS'),
            'created' => Yii::t('app', 'SYSTEM_TEXT_CREATED'),
            'created_by' => Yii::t('app', 'SYSTEM_TEXT_CREATED_BY'),
            'modified' => Yii::t('app', 'SYSTEM_TEXT_MODIFIED'),
            'modified_by' => Yii::t('app', 'SYSTEM_TEXT_MODIFIED_BY'),
        ];
    }
	
	public function getTrainingClassStudent()
    {
        return $this->hasOne(TrainingClassStudent::className(), ['id' => 'training_class_student_id']);
    }
}
