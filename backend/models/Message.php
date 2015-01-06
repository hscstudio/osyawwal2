<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "message".
 *
 * @property integer $id
 * @property integer $type
 * @property integer $category
 * @property integer $author
 * @property integer $recipient
 * @property string $subject
 * @property string $content
 * @property integer $status
 * @property string $created
 * @property integer $created_by
 */
class Message extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'message';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['type', 'category', 'author', 'recipient', 'content'], 'required'],
            [['type', 'category', 'author', 'recipient', 'status', 'created_by'], 'integer'],
            [['content'], 'string'],
            [['created'], 'safe'],
            [['subject'], 'string', 'max' => 255],
            [['type'], 'unique']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'type' => Yii::t('app', 'BPPK_TEXT_TYPE'),
            'category' => Yii::t('app', 'BPPK_TEXT_CATEGORY'),
            'author' => Yii::t('app', 'BPPK_TEXT_AUTHOR'),
            'recipient' => Yii::t('app', 'BPPK_TEXT_RECIPIENT'),
            'subject' => Yii::t('app', 'SYSTEM_TEXT_SUBJECT'),
            'content' => Yii::t('app', 'SYSTEM_TEXT_CONTENT'),
            'status' => Yii::t('app', 'SYSTEM_TEXT_STATUS'),
            'created' => Yii::t('app', 'SYSTEM_TEXT_CREATED'),
            'created_by' => Yii::t('app', 'SYSTEM_TEXT_CREATED_BY'),
        ];
    }
}
