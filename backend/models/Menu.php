<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "menu".
 *
 * @property integer $id
 * @property string $name
 * @property integer $parent
 * @property string $route
 * @property integer $order
 * @property string $data
 *
 * @property Menu $parent0
 * @property Menu[] $menus
 */
class Menu extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'menu';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['parent', 'order'], 'integer'],
            [['data'], 'string'],
            [['name'], 'string', 'max' => 128],
            [['route'], 'string', 'max' => 256]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'name' => Yii::t('app', 'BPPK_TEXT_NAME'),
            'parent' => Yii::t('app', 'SYSTEM_TEXT_PARENT'),
            'route' => Yii::t('app', 'SYSTEM_TEXT_ROUTE'),
            'order' => Yii::t('app', 'SYSTEM_TEXT_ORDER'),
            'data' => Yii::t('app', 'BPPK_TEXT_DATA'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getParent0()
    {
        return $this->hasOne(Menu::className(), ['id' => 'parent']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMenus()
    {
        return $this->hasMany(Menu::className(), ['parent' => 'id']);
    }
}
