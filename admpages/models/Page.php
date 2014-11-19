<?php

namespace pavlinter\admpages\models;

use pavlinter\admpages\Module;
use Yii;
use pavlinter\translation\TranslationBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;

/**
 * This is the model class for table "{{%page}}".
 *
 * @method \pavlinter\translation\TranslationBehavior getLangModels
 * @method \pavlinter\translation\TranslationBehavior setLanguage
 * @method \pavlinter\translation\TranslationBehavior getLanguage
 * @method \pavlinter\translation\TranslationBehavior saveTranslation
 * @method \pavlinter\translation\TranslationBehavior saveAllTranslation
 * @method \pavlinter\translation\TranslationBehavior saveAll
 * @method \pavlinter\translation\TranslationBehavior validateAll
 * @method \pavlinter\translation\TranslationBehavior validateLangs
 * @method \pavlinter\translation\TranslationBehavior loadAll
 * @method \pavlinter\translation\TranslationBehavior loadLang
 * @method \pavlinter\translation\TranslationBehavior loadLangs
 * @method \pavlinter\translation\TranslationBehavior getTranslation
 *
 * @property string $id
 * @property string $id_parent
 * @property string $layout
 * @property string $type
 * @property string $weight
 * @property integer $visible
 * @property integer $active
 *
 * Translation
 * @property string $name
 * @property string $title
 * @property string $description
 * @property string $keywords
 * @property string $image
 * @property string $alias
 * @property string $text
 *
 * @property PageLang[] $translations
 * @property Page $parent
 */
class Page extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'value' => new Expression('NOW()'),
            ],
            'trans' => [
                'class' => TranslationBehavior::className(),
                'translationAttributes' => [
                    'name',
                    'title',
                    'description',
                    'keywords',
                    'image',
                    'alias',
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
            [['weight', 'id_parent'], 'default', 'value' => null],
            [['id_parent', 'weight', 'visible', 'active'], 'integer'],
            [['layout', 'type'], 'required'],
            [['layout', 'type'], 'string', 'max' => 50],
            [['layout'], 'in', 'range' => array_keys(Module::getInstance()->pageLayouts)],
            [['type'], 'in', 'range' => array_keys(Module::getInstance()->pageTypes)],
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
            'id_parent' => Yii::t('adm/admpages', 'Parent'),
            'layout' => Yii::t('adm/admpages', 'Layout'),
            'weight' => Yii::t('adm/admpages', 'Weight'),
            'visible' => Yii::t('adm/admpages', 'Visible'),
            'active' => Yii::t('adm/admpages', 'Active'),
        ];
    }
    public function beforeSave($insert)
    {
        $query = self::find()->select(['MAX(weight)']);
        if (!$insert) {
            $query->where(['!=', 'id', $this->id]);
        }
        $this->weight = $query->scalar() + 50;
        return parent::beforeSave($insert);
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTranslations()
    {
        return $this->hasMany(Module::getInstance()->manager->pageLangClass, ['page_id' => 'id'])->indexBy('language_id');
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getParent()
    {
        return $this->hasOne(Module::getInstance()->manager->pageClass, ['id' => 'id_parent']);
    }
}
