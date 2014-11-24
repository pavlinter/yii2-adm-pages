<?php

namespace pavlinter\admpages\controllers;

use pavlinter\admpages\Module;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

class DefaultController extends Controller
{
    /**
     * @param $alias
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionIndex($alias)
    {
        if ($alias === '') {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
        /* @var \pavlinter\admpages\models\Page $model*/
        $model = Module::getInstance()->manager->createPageQuery('get', null, [
            'where' => ['alias' => $alias],
            'url' => function ($model, $id_language, $language) {
                if (isset($model->translations[$id_language])) {
                    $pageLang = $model->translations[$id_language];
                    $url = ['/adm/admpages/default/index', 'alias' => $pageLang->alias];
                } else {
                    $url = [''];
                }
                $url['lang'] = $language[Yii::$app->getI18n()->langColCode];
                return Yii::$app->getUrlManager()->createUrl($url);
            },
        ]);

        if ($model === false) {
            throw new NotFoundHttpException('The requested page does not exist.');
        }

        return $this->render($model->layout,[
            'model' => $model,
        ]);
    }

    /**
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionMain()
    {
        /* @var \pavlinter\admpages\models\Page $model*/
        $model = Module::getInstance()->manager->createPageQuery('get', null, [
            'where' => ['type' => 'main'],
            'orderBy' => ['weight' => SORT_ASC],
            'url' => [''],
        ]);

        if ($model === false) {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
        
        return $this->render($model->layout,[
            'model' => $model,
        ]);
    }

}
