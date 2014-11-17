<?php

namespace pavlinter\admpages\models;

use Yii;
use pavlinter\translation\TranslationBehavior;

/**
 * This is the model class for table "{{%page}}".
 *
 * @property string $id
 * @property string $id_parent
 * @property string $layout
 * @property string $weight
 * @property integer $visible
 * @property integer $active
 *
 * @property PageLang[] $translations
 */
class Page extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'trans' => [
                'class' => TranslationBehavior::className(),
                'translationAttributes' => [
                    'name',
                    'title',
                    'description',
                    'keywords',
                    'image',
                    'url',
                    'text',
                ]
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%page}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_parent', 'weight', 'visible', 'active'], 'integer'],
            [['layout'], 'required'],
            [['layout'], 'string', 'max' => 50]
        ];
    }

    /**
    * @inheritdoc
    */
    public function scenarios()
    {
        $scenarios = parent::scenarios();
        return $scenarios;
    }
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('adm/admpages', 'ID'),
            'id_parent' => Yii::t('adm/admpages', 'Id Parent'),
            'layout' => Yii::t('adm/admpages', 'Layout'),
            'weight' => Yii::t('adm/admpages', 'Weight'),
            'visible' => Yii::t('adm/admpages', 'Visible'),
            'active' => Yii::t('adm/admpages', 'Active'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTranslations()
    {
        return $this->hasMany(PageLang::className(), ['page_id' => 'id']);
    }
}
