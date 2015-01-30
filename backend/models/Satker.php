<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "satker".
 *
 * @property integer $reference_id
 * @property string $letter_number
 * @property integer $eselon
 * @property string $address
 * @property string $city
 * @property string $phone
 * @property string $fax
 * @property string $email
 * @property string $website
 *
 * @property Reference $reference
 */
class Satker extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'satker';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['reference_id', 'letter_number', 'eselon', 'address', 'city', 'phone', 'fax', 'email', 'website'], 'required'],
            [['reference_id', 'eselon'], 'integer'],
            [['address'], 'string'],
            [['letter_number'], 'string', 'max' => 10],
            [['city', 'phone', 'fax'], 'string', 'max' => 50],
            [['email'], 'string', 'max' => 100],
            [['website'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'reference_id' => Yii::t('app', 'SYSTEM_TEXT_REFERENCE_ID'),
            'letter_number' => Yii::t('app', 'BPPK_TEXT_LETTER_NUMBER'),
            'eselon' => Yii::t('app', 'BPPK_TEXT_ESELON'),
            'address' => Yii::t('app', 'BPPK_TEXT_ADDRESS'),
            'city' => Yii::t('app', 'BPPK_TEXT_CITY'),
            'phone' => Yii::t('app', 'BPPK_TEXT_PHONE'),
            'fax' => Yii::t('app', 'BPPK_TEXT_FAX'),
            'email' => Yii::t('app', 'BPPK_TEXT_EMAIL'),
            'website' => Yii::t('app', 'BPPK_TEXT_HOMEPAGE'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getReference()
    {
        return $this->hasOne(Reference::className(), ['id' => 'reference_id']);
    }
}
