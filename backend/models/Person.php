<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "person".
 *
 * @property integer $id
 * @property string $nip
 * @property string $name
 * @property string $nickname
 * @property string $front_title
 * @property string $back_title
 * @property string $nid
 * @property string $npwp
 * @property string $born
 * @property string $birthday
 * @property integer $gender
 * @property string $phone
 * @property string $email
 * @property string $homepage
 * @property string $address
 * @property string $office_phone
 * @property string $office_fax
 * @property string $office_email
 * @property string $office_address
 * @property string $bank_account
 * @property integer $married
 * @property string $blood
 * @property string $graduate_desc
 * @property integer $position
 * @property string $position_desc
 * @property string $organisation
 * @property integer $status
 * @property string $created
 * @property integer $created_by
 * @property string $modified
 * @property integer $modified_by
 *
 * @property Employee $employee
 * @property Student $student
 * @property Trainer $trainer
 */
class Person extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'person';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'nid'], 'required'],
			[['nid'], 'unique'],
            [['birthday', 'created', 'modified'], 'safe'],
            [['gender', 'married', 'position', 'status', 'created_by', 'modified_by'], 'integer'],
            [['nip', 'nickname', 'front_title', 'back_title', 'blood'], 'string', 'max' => 25],
			[['nip'], 'string', 'max' => 18, 'min'=>9],
            [['name', 'nid', 'npwp', 'email', 'office_email'], 'string', 'max' => 100],
            [['born', 'phone', 'office_phone', 'office_fax'], 'string', 'max' => 50],			
            [['homepage', 'address', 'office_address', 'bank_account', 'graduate_desc', 'position_desc', 'organisation'], 'string', 'max' => 255],
			
			['nid', 'filter', 'filter' => 'trim'],
            ['nid', 'unique', 'targetClass' => '\backend\models\Person', 'message' => 'This NID has already been taken.'],
			['nip', 'filter', 'filter' => 'trim'],
            ['nip', 'unique', 'targetClass' => '\backend\models\Person', 'message' => 'This NIP has already been taken.'],
            
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'BPPK_TEXT_ID'),
            'nip' => Yii::t('app', 'BPPK_TEXT_NIP'),
            'name' => Yii::t('app', 'BPPK_TEXT_NAME'),
            'nickname' => Yii::t('app', 'BPPK_TEXT_NICKNAME'),
            'front_title' => Yii::t('app', 'BPPK_TEXT_FRONT_TITLE'),
            'back_title' => Yii::t('app', 'BPPK_TEXT_BACK_TITLE'),
            'nid' => Yii::t('app', 'BPPK_TEXT_NID'),
            'npwp' => Yii::t('app', 'BPPK_TEXT_NPWP'),
            'born' => Yii::t('app', 'BPPK_TEXT_BORN'),
            'birthday' => Yii::t('app', 'BPPK_TEXT_BIRTHDAY'),
            'gender' => Yii::t('app', 'BPPK_TEXT_GENDER'),
            'phone' => Yii::t('app', 'BPPK_TEXT_PHONE'),
            'email' => Yii::t('app', 'BPPK_TEXT_EMAIL'),
            'homepage' => Yii::t('app', 'BPPK_TEXT_HOMEPAGE'),
            'address' => Yii::t('app', 'BPPK_TEXT_ADDRESS'),
            'office_phone' => Yii::t('app', 'BPPK_TEXT_OFFICE_PHONE'),
            'office_fax' => Yii::t('app', 'BPPK_TEXT_OFFICE_FAX'),
            'office_email' => Yii::t('app', 'BPPK_TEXT_OFFICE_EMAIL'),
            'office_address' => Yii::t('app', 'BPPK_TEXT_OFFICE_ADDRESS'),
            'bank_account' => Yii::t('app', 'BPPK_TEXT_BANK_ACCOUNT'),
            'married' => Yii::t('app', 'BPPK_TEXT_MARRIED'),
            'blood' => Yii::t('app', 'BPPK_TEXT_BLOOD'),
            'graduate_desc' => Yii::t('app', 'BPPK_TEXT_GRADUATE_DESC'),
            'position' => Yii::t('app', 'BPPK_TEXT_POSITION'),
            'position_desc' => Yii::t('app', 'BPPK_TEXT_POSITION_DESC'),
            'organisation' => Yii::t('app', 'BPPK_TEXT_ORGANISATION'),
            'status' => Yii::t('app', 'BPPK_TEXT_STATUS'),
            'created' => Yii::t('app', 'SYSTEM_TEXT_CREATED'),
            'created_by' => Yii::t('app', 'SYSTEM_TEXT_CREATED_BY'),
            'modified' => Yii::t('app', 'SYSTEM_TEXT_MODIFIED'),
            'modified_by' => Yii::t('app', 'SYSTEM_TEXT_MODIFIED_BY'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEmployee()
    {
        return $this->hasOne(Employee::className(), ['person_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStudent()
    {
        return $this->hasOne(Student::className(), ['person_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTrainer()
    {
        return $this->hasOne(Trainer::className(), ['person_id' => 'id']);
    }
	
	/**
     * @return \yii\db\ActiveQuery
     */
    public function getUnit()
    {
        return $this->hasOne(ObjectReference::className(), [
				'object_id' => 'id',
			])
			->onCondition([
				'object' => 'person',
				'type' => 'unit',
			]);
    }
	
	/**
     * @return \yii\db\ActiveQuery
     */
    public function getRankClass()
    {
        return $this->hasOne(ObjectReference::className(), [
				'object_id' => 'id',
			])
			->onCondition([
				'object' => 'person',
				'type' => 'rank_class',
			]);
    }
	/**
     * @inheritdoc
     * @return ProgramQuery
     */
    public static function find()
    {
        return new PersonQuery(get_called_class());
    }
}

class PersonQuery extends \yii\db\ActiveQuery
{
	
	public function active($status=1)
    {
        $this->andWhere(['status'=>$status]);
        return $this;
    }
}
