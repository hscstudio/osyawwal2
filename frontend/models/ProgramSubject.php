<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "program_subject".
 *
 * @property integer $id
 * @property integer $program_id
 * @property integer $program_revision
 * @property integer $type
 * @property string $name
 * @property string $hours
 * @property integer $sort
 * @property integer $test
 * @property string $stage
 * @property integer $status
 * @property string $created
 * @property integer $created_by
 * @property string $modified
 * @property integer $modified_by
 *
 * @property Program $program
 */
class ProgramSubject extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'program_subject';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['program_id', 'type', 'name', 'hours'], 'required'],
            [['program_id', 'program_revision', 'type', 'sort', 'test', 'status', 'created_by', 'modified_by'], 'integer'],
            [['hours'], 'number'],
            [['stage'], 'string'],
            [['created', 'modified'], 'safe'],
            [['name'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'program_id' => 'Program ID',
            'program_revision' => 'Program Revision',
            'type' => 'Type',
            'name' => 'Name',
            'hours' => 'Hours',
            'sort' => 'Sort',
            'test' => 'Test',
            'stage' => 'Stage',
            'status' => 'Status',
            'created' => 'Created',
            'created_by' => 'Created By',
            'modified' => 'Modified',
            'modified_by' => 'Modified By',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProgram()
    {
        return $this->hasOne(Program::className(), ['id' => 'program_id']);
    }
}
