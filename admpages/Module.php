<?php

namespace pavlinter\admpages;

use pavlinter\adm\Adm;
use Yii;
use yii\base\BootstrapInterface;
use yii\helpers\ArrayHelper;

/**
 * @property \pavlinter\admpages\ModelManager $manager
 */
class Module extends \yii\base\Module implements BootstrapInterface
{
    public $controllerNamespace = 'pavlinter\admpages\controllers';

    public $pageLayouts = [];

    public $pageTypes = [];

    public $pageLayout = '/main';

    public $files = [];

    public $closeDeletePage = []; //id [2,130]

    /**
     * @inheritdoc
     */
    public function __construct($id, $parent = null, $config = [])
    {
        $config = ArrayHelper::merge([
            'pageTypes' => [
                'page' => Adm::t('admpages/types','Pages'),
                'main' => Adm::t('admpages/types','Main Page'),
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
                'page' => Adm::t('admpages/layouts','Page'),
                'page-image' => Adm::t('admpages/layouts','Page + image'),
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
     */
    public function bootstrap($adm)
    {
        /* @var $adm \pavlinter\adm\Adm */
        if ($adm->user->can('Adm-Pages')) {
            $adm->params['left-menu']['admpages'] = [
                'label' => '<i class="fa fa-file-text"></i><span>' . $adm::t('admpages','Pages') . '</span>',
                'url' => ['/' . $adm->id . '/admpages/page/index', 'id_parent' => 0]
            ];
        }

    }

    /**
     * @inheritdoc
     */
    public function beforeAction($action)
    {
        PageAsset::register(Yii::$app->getView());
        return parent::beforeAction($action);
    }
}
