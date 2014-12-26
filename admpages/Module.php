<?php

namespace pavlinter\admpages;

use pavlinter\adm\AdmBootstrapInterface;
use Yii;
use yii\helpers\ArrayHelper;

/**
 * @property \pavlinter\admpages\ModelManager $manager
 */
class Module extends \yii\base\Module implements AdmBootstrapInterface
{
    public $controllerNamespace = 'pavlinter\admpages\controllers';

    public $pageLayouts = [];
    /**
     *  'pageLayouts' => [
     *   'contact' => 'Contact',
     *  ],
     *  'pageRedirect' => [
     *   'contact' => ['site/contact'],
     *  ],
     *
     *  @param \pavlinter\admpages\models\Page $page
     *  public function actionContact($page)
     *  {
     *
     *  }
     */
    public $pageRedirect = [];

    public $pageTypes = [];

    public $pageLayout = '/main';

    public $files = [];

    public $closeDeletePage = []; //id [2,130]

    public $layout = '@vendor/pavlinter/yii2-adm/adm/views/layouts/main';

    /**
     * @inheritdoc
     */
    public function __construct($id, $parent = null, $config = [])
    {
        $this->registerTranslations();

        $config = ArrayHelper::merge([
            'pageTypes' => [
                'page' => self::t('types','Pages', ['dot' => false]),
                'main' => self::t('types','Main Page', ['dot' => false]),
            ],
            'files' => [
                'page' => [
                    'dirs' => [
                        '@webroot/files/pages/{id}/gallery'// {id} - id page
                    ],
                    'startPath' => 'pages::{id}', // where :: replace to /
                ],
                'main' => [
                    'dirs' => [
                        '@webroot/files/pages/{id}/gallery'
                    ],
                    'startPath' => 'pages::{id}',
                ],
            ],
            'pageLayouts' => [
                'page' => self::t('layouts','Page', ['dot' => false]),
                'page-image' => self::t('layouts','Page + image', ['dot' => false]),
            ],
            'components' => [
                'manager' => [
                    'class' => 'pavlinter\admpages\ModelManager'
                ],
            ],
        ], $config);

        if ($config['pageLayouts']['page'] == false) {
            unset($config['pageLayouts']['page']);
        }
        if ($config['pageLayouts']['page-image'] == false) {
            unset($config['pageLayouts']['page-image']);
        }
        if ($config['files']['page'] == false) {
            unset($config['files']['page']);
        }
        if ($config['files']['main'] == false) {
            unset($config['files']['main']);
        }

        parent::__construct($id, $parent, $config);
    }

    public function init()
    {
        parent::init();
        // custom initialization code goes here
    }

    /**
     * @param \pavlinter\adm\Adm $adm
     */
    public function loading($adm)
    {
        if ($adm->user->can('Adm-Pages')) {
            $adm->params['left-menu']['admpages'] = [
                'label' => '<i class="fa fa-file-text"></i><span>' . self::t('', 'Pages') . '</span>',
                'url' => ['/admpages/page/index', 'id_parent' => 0]
            ];
        }
    }

    /**
     * @inheritdoc
     */
    public function beforeAction($action)
    {
        if ($action->controller->id !== 'default') {
            Yii::$app->getModule('adm'); //required load adm,if use adm layout
            PageAsset::register(Yii::$app->getView());
        }
        return parent::beforeAction($action);
    }

    /**
     *
     */
    public function registerTranslations()
    {
        if (!isset(Yii::$app->i18n->translations['admpages*'])) {
            Yii::$app->i18n->translations['admpages*'] = [
                'class' => 'pavlinter\translation\DbMessageSource',
                'forceTranslation' => true,
                'autoInsert' => true,
                'dotMode' => true,
            ];
        }
    }
    /**
     * @param $category
     * @param $message
     * @param array $params
     * @param null $language
     * @return string
     */
    public static function t($category, $message, $params = [], $language = null)
    {
        if ($category) {
            $category = 'admpages/' . $category;
        } else {
            $category = 'admpages';
        }
        return Yii::t($category, $message, $params, $language);
    }
}
