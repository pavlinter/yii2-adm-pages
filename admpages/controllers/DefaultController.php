<?php

namespace pavlinter\admpages\controllers;

use pavlinter\admpages\Module;
use Yii;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

/**
 * Class DefaultController
 */
class DefaultController extends Controller
{
    /**
     * @param $alias
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionIndex($alias)
    {

        /* @var $module \pavlinter\admpages\Module */
        $module = Module::getInstance();

        /* @var $model \pavlinter\admpages\models\Page */
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


        if (!$model) {
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

        return $this->render('@vendor/pavlinter/yii2-adm-pages/admpages/views/default/' . $model->layout, [
            'model' => $model,
        ]);
    }

    /**
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionMain()
    {
        /* @var $module \pavlinter\admpages\Module */
        $module = Module::getInstance();

        /* @var $model \pavlinter\admpages\models\Page */
        $model = $module->manager->createPageQuery('get', null, [
            'where' => ['type' => 'main'],
            'orderBy' => ['weight' => SORT_ASC],
        ]);


        if (!$model) {
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

        return $this->render('@vendor/pavlinter/yii2-adm-pages/admpages/views/default/' . $model->layout, [
            'model' => $model,
        ]);
    }

}
