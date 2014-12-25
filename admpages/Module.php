<?php

namespace pavlinter\admpages;

use pavlinter\adm\AdmBootstrapInterface;
use Yii;
use yii\base\BootstrapInterface;
use yii\helpers\ArrayHelper;

/**
 * @property \pavlinter\admpages\ModelManager $manager
 */
class Module extends \yii\base\Module implements BootstrapInterface, AdmBootstrapInterface
{
    public $controllerNamespace = 'pavlinter\admpages\controllers';

    public $pageLayouts = [];

    public $pageRedirect = [];

    public $pageTypes = [];

    public $pageLayout = '/main';

    public $files = [];

    public $closeDeletePage = []; //id [2,130]

    public $pageUrl;

    public $mainPageUrl;

    public $layout = '@vendor/pavlinter/yii2-adm/adm/views/layouts/main';

    /**
     * @inheritdoc
     */
    public function __construct($id, $parent = null, $config = [])
    {
        $this->registerTranslations();

        $config = ArrayHelper::merge([
            'pageTypes' => [
                'page' => self::t('types','Pages'),
                'main' => self::t('types','Main Page'),
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
                'page' => self::t('layouts','Page'),
                'page-image' => self::t('layouts','Page + image'),
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
     * @inheritdoc
     * @param \yii\base\Application $app
     */
    public function bootstrap($app)
    {

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
            Yii::$app->getModule('adm');
            PageAsset::register(Yii::$app->getView());
        }
        return parent::beforeAction($action);
    }

    /**
     *
     */
    public function registerTranslations()
    {
        if (!isset(Yii::$app->i18n->translations['admpages/*'])) {
            Yii::$app->i18n->translations['admpages/*'] = [
                'class' => 'pavlinter\translation\DbMessageSource',
                'forceTranslation' => true,
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
