<?php

namespace pavlinter\admpages\controllers;

use pavlinter\admpages\Module;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

class DefaultController extends Controller
{
    public function actionIndex($alias)
    {
        if ($alias === '') {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
        /* @var \pavlinter\admpages\models\Page $model*/
        $model = Module::getInstance()->manager->createPageQuery('find')->innerJoinWith(['translations'])->where(['alias' => $alias])->one();

        if ($model === null || !$model->active || !isset($model->translations[Yii::$app->getI18n()->getId()])) {
            throw new NotFoundHttpException('The requested page does not exist.');
        }

        return $this->render('layouts/'.$model->layout,[
            'model' => $model,
        ]);
    }
}
