<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "object_file".
 *
 * @property string $object
 * @property integer $object_id
 * @property string $type
 * @property integer $file_id
 *
 * @property File $file
 */
class ObjectFile extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'object_file';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['object', 'object_id', 'type'], 'required'],
            [['object_id','file_id'], 'integer'],
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
            'file_id' => Yii::t('app', 'SYSTEM_TEXT_FILE_ID'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFile()
    {
        return $this->hasOne(File::className(), ['id' => 'file_id']);
    }
}
