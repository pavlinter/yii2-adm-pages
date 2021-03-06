<?php

/**
 * @package yii2-adm-pages
 * @author Pavels Radajevs <pavlinter@gmail.com>
 * @copyright Copyright &copy; Pavels Radajevs <pavlinter@gmail.com>, 2015
 * @version 1.0.4
 */

namespace pavlinter\admpages;

use Closure;
use pavlinter\adm\Adm;
use pavlinter\adm\AdmBootstrapInterface;
use Yii;
use yii\helpers\ArrayHelper;

/**
 * @property \pavlinter\admpages\ModelManager $manager
 */
class Module extends \yii\base\Module implements AdmBootstrapInterface
{
    static $layoutAliases;

    static $idAliases;

    public $controllerNamespace = 'pavlinter\admpages\controllers';
    /**
     * @var Closure|array
     */
    public $pageLayouts = [];
    /**
     *  'pageLayouts' => [
     *   'contact' => 'Contact',
     *  ],
     *  'pageRedirect' => [
     *   'contact' => ['site/contact'], or function($modelPage){ return ['site/contact', 'ownParamName' => $modelPage]}
     *  ],
     *
     *  @param \pavlinter\admpages\models\Page $modelPage
     *  public function actionContact($modelPage)
     *  {
     *
     *  }
     */
    public $pageRedirect = [];
    /**
     * @var Closure|array
     */
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
            'files' => [
                'page' => [
                    'dirs' => [
                        '@webroot/files/pages/{id}/gallery'// {id} - id page
                    ],
                    'startPath' => 'pages::{id}', // where :: replace to /
                    'maxWidth' => 1500,
                    'maxHeight' => 1500,
                    'watermark' => 0, //see https://github.com/pavlinter/yii2-app-core/blob/master/core/elfinder/views/connect.php#L40
                ],
                'main' => [
                    'dirs' => [
                        '@webroot/files/pages/{id}/gallery'
                    ],
                    'startPath' => 'pages::{id}',
                    'maxWidth' => 1500,
                    'maxHeight' => 1500,
                    'watermark' => 0,
                ],
            ],
            'components' => [
                'manager' => [
                    'class' => 'pavlinter\admpages\ModelManager'
                ],
            ],
        ], $config);

        parent::__construct($id, $parent, $config);
    }

    public function init()
    {
        parent::init();
        if ($this->pageLayouts instanceof Closure) {
            $this->pageLayouts = call_user_func($this->pageLayouts, $this);
        }
        if ($this->pageTypes instanceof Closure) {
            $this->pageTypes = call_user_func($this->pageTypes, $this);
        }

        if (!isset($this->pageTypes['main'])) {
            $this->pageTypes = ['main' => self::t('types', 'Main Page', ['dot' => false])] + $this->pageTypes;
        } else if($this->pageTypes['main'] === false) {
            unset($this->pageTypes['main']);
        }

        if (!isset($this->pageTypes['page'])) {
            $this->pageTypes = ['page' => self::t('types', 'Page', ['dot' => false])] + $this->pageTypes;
        } else if($this->pageTypes['page'] === false) {
            unset($this->pageTypes['page']);
        }

        if (!isset($this->pageLayouts['page-image'])) {
            $this->pageLayouts = ['page-image' => self::t('layouts', 'Page + image', ['dot' => false])] + $this->pageLayouts;
        } else if($this->pageLayouts['page-image'] === false) {
            unset($this->pageLayouts['page-image']);
        }

        if (!isset($this->pageLayouts['page'])) {
            $this->pageLayouts = ['page' => self::t('layouts', 'Page', ['dot' => false])] + $this->pageLayouts;
        } else if($this->pageLayouts['page'] === false) {
            unset($this->pageLayouts['page']);
        }

        if ($this->files['page'] === false) {
            unset($this->files['page']);
        }

        if ($this->files['main'] === false) {
            unset($this->files['main']);
        }
    }

    /**
     * @param \pavlinter\adm\Adm $adm
     */
    public function loading($adm)
    {
        if ($adm->user->can('Adm-Pages')) {
            $adm->params['left-menu']['admpages'] = [
                'label' => '<i class="fa fa-file-text"></i><span>' . $adm::t('menu', 'Pages') . '</span>',
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
            $adm = Adm::register(); //required load adm,if use adm layout
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
