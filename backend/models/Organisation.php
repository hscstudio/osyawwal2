<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "organisation".
 *
 * @property integer $ID
 * @property string $KD_UNIT_ORG
 * @property string $KD_UNIT_ES1
 * @property string $KD_UNIT_ES2
 * @property string $KD_UNIT_ES3
 * @property string $KD_UNIT_ES4
 * @property string $KD_UNIT_ES5
 * @property integer $JNS_KANTOR
 * @property string $NM_UNIT_ORG
 * @property string $KD_ESELON
 * @property string $KD_SURAT_ORG
 * @property string $TKT_ESELON
 */
class Organisation extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'organisation';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['KD_UNIT_ORG', 'KD_UNIT_ES1', 'KD_UNIT_ES2', 'KD_UNIT_ES3', 'KD_UNIT_ES4', 'KD_UNIT_ES5', 'JNS_KANTOR', 'NM_UNIT_ORG', 'KD_ESELON', 'KD_SURAT_ORG', 'TKT_ESELON'], 'required'],
            [['JNS_KANTOR'], 'integer'],
            [['KD_UNIT_ORG'], 'string', 'max' => 10],
            [['KD_UNIT_ES1', 'KD_UNIT_ES2', 'KD_UNIT_ES3', 'KD_UNIT_ES4', 'KD_UNIT_ES5', 'KD_ESELON', 'TKT_ESELON'], 'string', 'max' => 2],
            [['NM_UNIT_ORG', 'KD_SURAT_ORG'], 'string', 'max' => 100]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'ID' => Yii::t('app', 'BPPK_TEXT_ID'),
            'KD_UNIT_ORG' => Yii::t('app', 'BPPK_TEXT_KD_UNIT_ORG'),
            'KD_UNIT_ES1' => Yii::t('app', 'BPPK_TEXT_KD_UNIT_ES1'),
            'KD_UNIT_ES2' => Yii::t('app', 'BPPK_TEXT_KD_UNIT_ES2'),
            'KD_UNIT_ES3' => Yii::t('app', 'BPPK_TEXT_KD_UNIT_ES3'),
            'KD_UNIT_ES4' => Yii::t('app', 'BPPK_TEXT_KD_UNIT_ES4'),
            'KD_UNIT_ES5' => Yii::t('app', 'BPPK_TEXT_KD_UNIT_ES5'),
            'JNS_KANTOR' => Yii::t('app', 'BPPK_TEXT_JNS_KANTOR'),
            'NM_UNIT_ORG' => Yii::t('app', 'BPPK_TEXT_NM_UNIT_ORG'),
            'KD_ESELON' => Yii::t('app', 'BPPK_TEXT_KD_ESELON'),
            'KD_SURAT_ORG' => Yii::t('app', 'BPPK_TEXT_KD_SURAT_ORG'),
            'TKT_ESELON' => Yii::t('app', 'BPPK_TEXT_TKT_ESELON'),
        ];
    }
}
