<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "object_reference".
 *
 * @property string $object
 * @property integer $object_id
 * @property string $type
 * @property integer $reference_id
 *
 * @property Reference $reference
 */
class ObjectReference extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'object_reference';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['object', 'object_id', 'type'], 'required'],
            [['object_id', 'reference_id'], 'integer'],
            [['object'], 'string', 'max' => 100],
            [['type'], 'string', 'max' => 50]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'object' => Yii::t('app', 'SYSTEM_TEXT_OBJECT'),
            'object_id' => Yii::t('app', 'SYSTEM_TEXT_OBJECT_ID'),
            'type' => Yii::t('app', 'BPPK_TEXT_TYPE'),
            'reference_id' => Yii::t('app', 'SYSTEM_TEXT_REFERENCE_ID'),
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
