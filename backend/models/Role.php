<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "role".
 *
 * @property integer $id
 * @property integer $parent_id
 * @property string $name
 * @property integer $eselon
 * @property integer $organisation_id
 *
 * @property Organisation $organisation
 */
class Role extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'role';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['parent_id', 'name', 'eselon', 'organisation_id'], 'required'],
            [['parent_id', 'eselon', 'organisation_id'], 'integer'],
            [['name'], 'string', 'max' => 255],
            [['name'], 'unique']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'parent_id' => 'Parent ID',
            'name' => 'Name',
            'eselon' => 'Eselon',
            'organisation_id' => 'Organisation ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrganisation()
    {
        return $this->hasOne(Organisation::className(), ['ID' => 'organisation_id']);
    }
}
