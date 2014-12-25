<?php

namespace pavlinter\admpages\actions;

use pavlinter\admpages\Module;
use Yii;
use yii\base\Action;
use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;

/**
 * Class PageAction
 */
class PageAction extends Action
{
    /**
     * @var boolean
     */
    public $isMainPage = false;
    /**
     *
     */
    public function init()
    {
        Yii::$app->getModule('adm');
    }

    /**
     * Runs the action.
     * @param $alias
     */
    public function run($alias = '')
    {
        /* @var $module \pavlinter\admpages\Module */
        $module = Module::getInstance();

        /* @var $model \pavlinter\admpages\models\Page */
        if ($this->isMainPage) {
            $model = $module->manager->createPageQuery('get', null, [
                'where' => ['type' => 'main'],
                'orderBy' => ['weight' => SORT_ASC],
            ]);
        } else {
            if ($alias === '') {
                throw new NotFoundHttpException('The requested page does not exist.');
            }
            $model = $module->manager->createPageQuery('get', null, [
                'where' => ['alias' => $alias],
                'url' => function ($model, $id_language, $language) {
                    if ($model->hasTranslation($id_language)) {
                        $url = $model->url();
                    } else {
                        $url = [''];
                    }
                    $url['lang'] = $language[Yii::$app->getI18n()->langColCode];
                    return Yii::$app->getUrlManager()->createUrl($url);
                },
            ]);
        }

        if ($model === false) {
            throw new NotFoundHttpException('The requested page does not exist.');
        }

        if (isset($module->pageRedirect[$model->layout])) {
            $params = $module->pageRedirect[$model->layout];
            $route  = ArrayHelper::remove($params, 0);
            $params['page'] = $model;
            $app = Yii::$app;
            $result = $app->runAction($route, $params);
            if ($result instanceof \yii\web\Response) {
                return $result;
            } else {
                $response = $app->getResponse();
                if ($result !== null) {
                    $response->data = $result;
                }
                return $response;
            }
        }

        return $this->controller->render('@vendor/pavlinter/yii2-adm-pages/admpages/views/default/' . $model->layout, [
            'model' => $model,
            'module' => Module::getInstance(),
        ]);
    }
}
