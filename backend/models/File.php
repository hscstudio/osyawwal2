<?php

namespace backend\models;

use Yii;
use yii\db\ActiveRecord;								
use yii\behaviors\TimestampBehavior;
use yii\behaviors\AttributeBehavior;
use yii\db\Expression;
use yii\behaviors\BlameableBehavior;
/**
 * This is the model class for table "file".
 *
 * @property integer $id
 * @property string $name
 * @property string $file_name
 * @property string $file_type
 * @property integer $file_size
 * @property string $description
 * @property integer $status
 * @property string $created
 * @property integer $created_by
 * @property string $modified
 * @property integer $modified_by
 *
 * @property ObjectFile[] $objectFiles
 */
class File extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'file';
    }
	
	/**
     * @inheritdoc
     */	
    public function behaviors()
    {
        return [
            'timestamp' => [
                'class' => TimestampBehavior::className(),
                'attributes' => [
                        ActiveRecord::EVENT_BEFORE_INSERT => ['created','modified'],
                        ActiveRecord::EVENT_BEFORE_UPDATE => 'modified',
                ],
                'value' => new Expression('NOW()'),
            ],
            'blameable' => [
                'class' => BlameableBehavior::className(),
                'attributes' => [
                        ActiveRecord::EVENT_BEFORE_INSERT => ['created_by','modified_by'],
                        ActiveRecord::EVENT_BEFORE_UPDATE => 'modified_by',
                ],
            ],
        ];
    }
	
	
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['file_size', 'status', 'created_by', 'modified_by'], 'integer'],
            [['created', 'modified'], 'safe'],
            [['name', 'file_name', 'file_type', 'description'], 'string', 'max' => 255],
			
			// DEFAULT
			[['file_name'], 'file', 'extensions' => 'jpg, png, gif', 
				'mimeTypes' => 'image/jpeg, image/png, image/gif','on' => 'default'],
			// IMAGE
			[['file_name'], 'file', 'extensions' => 'jpg, png, gif', 
				'mimeTypes' => 'image/jpeg, image/png, image/gif','on' => 'filetype-image'],
			
			// DOCUMENT
			[['file_name'], 'file', 
				'extensions' => 'pdf, xls, xlsx, doc, docx, ppt, pptx, txt, rtf,
								odt, ods, odp', 
				'mimeTypes' => '				 
					application/vnd.ms-excel, application/vnd.ms-powerpoint, text/plain, text/rtf,
					application/vnd.openxmlformats-officedocument.wordprocessingml.document, 
					application/vnd.openxmlformats-officedocument.presentationml.presentation, 
					application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,
					application/vnd.oasis.opendocument.presentation, 
					application/vnd.oasis.opendocument.spreadsheet, 
					application/vnd.oasis.opendocument.text,
					application/pdf
				','on' => 'filetype-document'],
				
			[['file_name'], 'file', 
				'extensions' => 'zip, rar, 7z', 
				'mimeTypes' => '
					application/x-7z-compressed,
					application/x-rar-compressed,
					application/zip
				','on' => 'filetype-compressed'],
				
			[['file_name'], 'file', 
				'extensions' => 'pdf, xls, xlsx, doc, docx, ppt, pptx, txt, rtf,
								odt, ods, odp, zip, rar, 7z', 
				'mimeTypes' => '
					application/vnd.ms-excel, application/vnd.ms-powerpoint, text/plain, text/rtf,
					application/vnd.openxmlformats-officedocument.wordprocessingml.document, 
					application/vnd.openxmlformats-officedocument.presentationml.presentation, 
					application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,
					application/vnd.oasis.opendocument.presentation, 
					application/vnd.oasis.opendocument.spreadsheet, 
					application/vnd.oasis.opendocument.text,
					application/x-7z-compressed,
					application/x-rar-compressed,
					application/zip
				','on' => 'filetype-document-compressed'],	
        ];
    }

	public function scenarios()
    {
       /*  $scenarios = parent::scenarios(); */
        $scenarios['filetype-document-compressed'] = ['file_name'];
        $scenarios['filetype-document'] = ['file_name'];
        $scenarios['filetype-compressed'] = ['file_name'];
        $scenarios['filetype-image'] = ['file_name'];
        $scenarios['default'] = ['file_name'];
        return $scenarios;
    }
	
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'name' => Yii::t('app', 'BPPK_TEXT_NAME'),
            'file_name' => Yii::t('app', 'SYSTEM_TEXT_FILENAME'),
            'file_type' => Yii::t('app', 'SYSTEM_TEXT_FILETYPE'),
            'file_size' => Yii::t('app', 'SYSTEM_TEXT_FILESIZE'),
            'description' => Yii::t('app', 'BPPK_TEXT_DESCRIPTION'),
            'status' => Yii::t('app', 'Status'),
            'created' => Yii::t('app', 'SYSTEM_TEXT_CREATED'),
            'created_by' => Yii::t('app', 'SYSTEM_TEXT_CREATED_BY'),
            'modified' => Yii::t('app', 'SYSTEM_TEXT_MODIFIED'),
            'modified_by' => Yii::t('app', 'SYSTEM_TEXT_MODIFIED_BY'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getObjectFiles()
    {
        return $this->hasMany(ObjectFile::className(), ['object_id' => 'id']);
    }
}
