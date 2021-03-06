<?php

/**
 * @package yii2-adm-pages
 * @author Pavels Radajevs <pavlinter@gmail.com>
 * @copyright Copyright &copy; Pavels Radajevs <pavlinter@gmail.com>, 2015
 * @version 1.0.4
 */

namespace pavlinter\admpages\models;

use Yii;
use pavlinter\translation\TranslationBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;
use yii\helpers\ArrayHelper;

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
 * @method \pavlinter\translation\TranslationBehavior hasTranslation
 *
 * @property string $id
 * @property string $id_parent
 * @property string $layout
 * @property string $type
 * @property string $weight
 * @property integer $visible
 * @property integer $active
 * @property string $date
 * @property string $created_at
 * @property string $updated_at
 *
 * Translation
 * @property string $name
 * @property string $title
 * @property string $description
 * @property string $keywords
 * @property string $image
 * @property string $alias
 * @property string $url
 * @property string $text
 *
 * @property PageLang[] $translations
 * @property Page $parent
 * @property Page[] $childs
 */
class Page extends \yii\db\ActiveRecord
{
    static $textBreak = '<div style="page-break-after: always"><span style="display:none">&nbsp;</span></div>';
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
        /* @var \pavlinter\admpages\Module $module */
        $module = Yii::$app->getModule('admpages');
        return [
            [['weight', 'id_parent'], 'default', 'value' => null],
            [['id_parent', 'weight', 'visible', 'active'], 'integer'],
            [['layout', 'type'], 'required'],
            [['layout', 'type'], 'string', 'max' => 50],
            [['date'], 'date', 'format' => 'yyyy-MM-dd HH:mm:ss'],
            [['layout'], 'in', 'range' => array_keys($module->pageLayouts)],
            [['type'], 'in', 'range' => array_keys($module->pageTypes)],
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
            'id' => Yii::t('modelAdm/admpages', 'ID'),
            'id_parent' => Yii::t('modelAdm/admpages', 'Parent'),
            'layout' => Yii::t('modelAdm/admpages', 'Layout'),
            'weight' => Yii::t('modelAdm/admpages', 'Weight'),
            'visible' => Yii::t('modelAdm/admpages', 'Visible'),
            'active' => Yii::t('modelAdm/admpages', 'Active'),
            'date' => Yii::t('modelAdm/admpages', 'Date'),
        ];
    }

    /**
     * @param bool $insert
     * @return bool
     */
    public function beforeSave($insert)
    {
        if ($this->weight === null) {
            $query = static::find()->select(['MAX(weight)']);
            if (!$insert) {
                $query->where(['!=', 'id', $this->id]);
            }
            $this->weight = $query->scalar() + 50;
        }
        return parent::beforeSave($insert);
    }

    /**
     * @param bool $onlyshort
     * @return bool|string
     */
    public function shortText($onlyshort = false)
    {
        $pos = strpos($this->text, static::$textBreak);
        if ($pos !== false) {
            return \yii\helpers\StringHelper::truncate($this->text, $pos, null);
        }
        if ($onlyshort) {
            return false;
        }
        return $this->text;
    }

    /**
     * @param null $encoding
     * @return string
     */
    public function text($encoding = null)
    {
        $pos = strpos($this->text, static::$textBreak);
        if ($pos !== false) {
            return mb_substr($this->text, $pos + strlen(static::$textBreak), null, Yii::$app->charset);
        }
        return $this->text;
    }

    /**
     * @param $url
     * @param null $id_language
     * @param string $key
     * @return mixed
     */
    public function url($url = true, $id_language = null, $key = 'alias')
    {
        if ($url === true) {
            $url = ['/admpages/default/index'];
        } else if($url === null) {
            $url = ['/admpages/default/main'];
            $key = false;
        }
        return $this->getTranslation($id_language)->url($url, $key);
    }

    /**
     * @param bool $scheme
     * @param array $options
     * @return string
     */
    public function urlTo($scheme = false, $options = [])
    {
        $options  = ArrayHelper::merge([
            'url' => true,
            'id_language' => null,
            'key' => 'alias',
        ], $options);
        return \yii\helpers\Url::to($this->url($options['url'], $options['id_language'], $options['key']), $scheme);
    }

    /**
     * @param $id
     * @param array $config
     * @return array|bool|null|\yii\db\ActiveRecord
     */
    public static function get($id, $config = [])
    {
        $config = ArrayHelper::merge([
            'setLanguageUrl' => true,
            'registerMetaTag' => true,
            'where' => false,
            'orderBy' => false,
        ], $config);

        $query = static::find()->from(['p' => static::tableName()])->innerJoinWith(['translations']);
        if ($config['where'] === false) {
            $query->where(['p.id' => $id]);
        } else {
            $query->where($config['where']);
        }
        if ($config['orderBy'] !== false) {
            $query->orderBy($config['orderBy']);
        }

        $model = $query->one();

        if ($model === null) {
            return null;
        }

        if (!$model->active || !isset($model->translations[Yii::$app->getI18n()->getId()])) {
            return false;
        }

        if ($config['setLanguageUrl']) {
            if (!isset($config['url'])) {
                $url = [''];
            } else {
                $url = $config['url'];
            }
            
            foreach (Yii::$app->getI18n()->getLanguages() as $id_language => $language) {
                if (is_array($url)) {
                    $language['url'] = ArrayHelper::merge($url, [
                        'lang' => $language[Yii::$app->getI18n()->langColCode],
                    ]);
                    $language['url'] = Yii::$app->getUrlManager()->createUrl($language['url']);
                } elseif (is_callable($url)) {
                    $language['url'] = call_user_func($url, $model, $id_language, $language);
                }

                Yii::$app->getI18n()->setLanguage($id_language, $language);
            }
        }
        if ($config['registerMetaTag']) {
            Yii::$app->getView()->registerMetaTag(['name' => 'description', 'content' => $model->description]);
            Yii::$app->getView()->registerMetaTag(['name' => 'keywords', 'content' => $model->keywords]);
        }
        return $model;
    }

    /**
     * @param $layout
     * @param array $options
     * @return array
     * @throws \Exception
     */
    public static function urlLayout($layout, $options = [])
    {
        /* @var $module \pavlinter\admpages\Module */
        $module = Yii::$app->getModule('admpages');

        $options  = ArrayHelper::merge([
            'url' => true,
            'key' => 'alias',
        ], $options);

        $url = ['/admpages/default/index'];
        if ($options['url'] !== true) {
            $url = $options['url'];
        }
        if ($module::$layoutAliases === null) {
            $layouts = Yii::$app->cache->get('admpagesUrlLayout');
            if ($layouts === false) {
                $layouts =  static::find()->from(['p' => static::tableName()])->select(['l.alias', 'p.layout'])
                    ->innerJoin(['l'=> PageLang::tableName()],'l.page_id=p.id AND l.language_id=:language_id',[':language_id' => Yii::$app->getI18n()->getId()])
                    ->where(['p.active' => 1])->groupBy('p.layout')->all();
                $layouts = ArrayHelper::map($layouts, 'layout', 'alias');
                $dependency = new \yii\caching\DbDependency([
                    'sql' => 'SELECT MAX(updated_at) FROM ' . static::tableName(),
                ]);
                Yii::$app->cache->set('admpagesUrlLayout', $layouts, 86400, $dependency);
            }
            $module::$layoutAliases = $layouts;
        }
        if (isset($module::$layoutAliases[$layout])) {
            $url[$options['key']] = $module::$layoutAliases[$layout];
        } else {
            $url = null;
        }
        return $url;
    }

    /**
     * @param $layout
     * @param bool $scheme
     * @param array $options
     * @return string
     */
    public static function urlToLayout($layout, $scheme = false, $options = [])
    {
        $options  = ArrayHelper::merge([
            'defaultUrl' => '/',
        ], $options);
        $url = static::urlLayout($layout, $options);
        if ($url === null) {
            $url = $options['defaultUrl'];
        }
        return \yii\helpers\Url::to($url, $scheme);
    }

    /**
     * @param $id
     * @param array $options
     * @return array|null
     */
    public static function urlId($id, $options = [])
    {
        /* @var $module \pavlinter\admpages\Module */
        $module = Yii::$app->getModule('admpages');

        $options  = ArrayHelper::merge([
            'url' => true,
            'key' => 'alias',
        ], $options);

        $url = ['/admpages/default/index'];
        if ($options['url'] !== true) {
            $url = $options['url'];
        }

        if ($module::$idAliases === null) {
            $aliases = Yii::$app->cache->get('admpagesUrlId');
            if ($aliases === false) {
                $aliases =  static::find()->from(['p' => static::tableName()])->select(['l.alias', 'p.id'])
                    ->innerJoin(['l'=> PageLang::tableName()],'l.page_id=p.id AND l.language_id=:language_id',[':language_id' => Yii::$app->getI18n()->getId()])
                    ->where(['p.active' => 1])->all();
                $aliases = ArrayHelper::map($aliases, 'id', 'alias');
                $dependency = new \yii\caching\DbDependency([
                    'sql' => 'SELECT MAX(updated_at) FROM ' . static::tableName(),
                ]);
                Yii::$app->cache->set('admpagesUrlId', $aliases, 86400, $dependency);
            }
            $module::$idAliases = $aliases;
        }

        if (isset($module::$idAliases[$id])) {
            $url[$options['key']] = $module::$idAliases[$id];
        } else {
            $url = null;
        }
        return $url;
    }

    /**
     * @param $id
     * @param bool $scheme
     * @param array $options
     * @return string
     */
    public static function urlToId($id, $scheme = false, $options = [])
    {
        $options  = ArrayHelper::merge([
            'defaultUrl' => '/',
        ], $options);
        $url = static::urlId($id, $options);
        if ($url === null) {
            $url = $options['defaultUrl'];
        }
        return \yii\helpers\Url::to($url, $scheme);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTranslations()
    {
        /* @var \pavlinter\admpages\Module $module */
        $module = Yii::$app->getModule('admpages');
        return $this->hasMany($module->manager->pageLangClass, ['page_id' => 'id'])->indexBy('language_id');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getParent()
    {
        return $this->hasOne(static::className(), ['id' => 'id_parent']);
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getChilds()
    {
        return $this->hasMany(static::className(), ['id_parent' => 'id']);
    }
}
