<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "issue".
 *
 * @property integer $id
 * @property integer $parent_id
 * @property string $subject
 * @property string $content
 * @property string $label
 * @property integer $status
 * @property string $created
 * @property integer $created_by
 * @property string $modified
 * @property integer $modified_by
 */
class Issue extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'issue';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['parent_id', 'status', 'created_by', 'modified_by'], 'integer'],
            [['subject', 'content'], 'required'],
            [['content'], 'string'],
            [['created', 'modified'], 'safe'],
            [['subject', 'label', 'attachment'], 'string', 'max' => 255],			
            [['subject'], 'unique'],
			[['attachment'], 'file', 'extensions' => 'jpg, png, gif, zip', 
				'mimeTypes' => 'image/jpeg, image/png, image/gif, application/zip','on' => 'default'],
        ];
    }

	public function scenarios()
    {
       /*  $scenarios = parent::scenarios(); */
        $scenarios['default'] = ['attachment'];
        return $scenarios;
    }
	
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'parent_id' => 'Parent ID',
            'subject' => 'Subject',
            'content' => 'Content',
            'label' => 'Label',
            'status' => 'Status',
            'created' => 'Created',
            'created_by' => 'Created By',
            'modified' => 'Modified',
            'modified_by' => 'Modified By',
        ];
    }
}
