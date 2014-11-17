<?php

namespace pavlinter\admpages\models;

use Yii;

/**
 * This is the model class for table "{{%page_lang}}".
 *
 * @property string $id
 * @property string $page_id
 * @property integer $language_id
 * @property string $name
 * @property string $title
 * @property string $description
 * @property string $keywords
 * @property string $image
 * @property string $alias
 * @property string $text
 *
 * @property Language $language
 * @property Page $page
 */
class PageLang extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%page_lang}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'url'], 'required'],
            [['page_id', 'language_id'], 'integer'],
            [['text'], 'string'],
            [['name'], 'string', 'max' => 100],
            [['title'], 'string', 'max' => 80],
            [['description', 'image', 'url'], 'string', 'max' => 200],
            [['keywords'], 'string', 'max' => 250],
            [['alias'], 'match', 'pattern' => '/^([A-Za-z0-9_-])+$/'],
            [['alias'], 'unique'],
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
            'page_id' => Yii::t('adm/admpages', 'Page ID'),
            'language_id' => Yii::t('adm/admpages', 'Language ID'),
            'name' => Yii::t('adm/admpages', 'Name'),
            'title' => Yii::t('adm/admpages', 'Title'),
            'description' => Yii::t('adm/admpages', 'Description'),
            'keywords' => Yii::t('adm/admpages', 'Keywords'),
            'image' => Yii::t('adm/admpages', 'Image'),
            'alias' => Yii::t('adm/admpages', 'Alias'),
            'text' => Yii::t('adm/admpages', 'Text'),
        ];
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPage()
    {
        return $this->hasOne(Page::className(), ['id' => 'page_id']);
    }
}
