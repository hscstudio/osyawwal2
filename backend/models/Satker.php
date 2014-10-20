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
            'reference_id' => 'Reference ID',
            'letter_number' => 'Letter Number',
            'eselon' => 'Eselon',
            'address' => 'Address',
            'city' => 'City',
            'phone' => 'Phone',
            'fax' => 'Fax',
            'email' => 'Email',
            'website' => 'Website',
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
