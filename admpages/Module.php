<?php

namespace pavlinter\admpages;

use Yii;
use yii\base\BootstrapInterface;
use yii\helpers\ArrayHelper;

/**
 * @property \pavlinter\admpages\ModelManager $manager
 */
class Module extends \yii\base\Module implements BootstrapInterface
{
    public $controllerNamespace = 'pavlinter\admpages\controllers';

    public $pageLayouts = [
        'index' => 'Page',
    ];
    /**
     * @inheritdoc
     */
    public function __construct($id, $parent = null, $config = [])
    {
        $config = ArrayHelper::merge([
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

        // custom initialization code goes here
    }
    /**
     * @inheritdoc
     */
    public function bootstrap($adm)
    {
        /* @var $adm \pavlinter\adm\Adm */
        $adm->params['left-menu']['admpages'] = [
            'label' => '<i class="fa fa-hdd-o"></i><span>' . $adm::t('admpages','Pages') . '</span>',
            'url' => ['/' . $adm->id . '/admpages/page/index']
        ];
    }
}
