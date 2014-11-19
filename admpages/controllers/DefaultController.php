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
        $model = Module::getInstance()->manager->createPageQuery('find')->innerJoinWith(['translations'])->where(['alias' => $alias])->one();

        if ($model === null || !$model->active || !isset($model->translations[Yii::$app->getI18n()->getId()])) {
            throw new NotFoundHttpException('The requested page does not exist.');
        }

        foreach (Yii::$app->getI18n()->getLanguages() as $id_language => $language) {
            if (isset($model->translations[$id_language])) {
                $pageLang = $model->translations[$id_language];
                $url = ['/adm/admpages/default/index', 'alias' => $pageLang->alias,'lang' => $language[Yii::$app->getI18n()->langColCode]];
            } else {
                $url = ['','lang' => $language[Yii::$app->getI18n()->langColCode]];
            }
            $language['url'] = Yii::$app->getUrlManager()->createUrl($url);
            Yii::$app->getI18n()->setLanguage($id_language, $language);
        }

        Yii::$app->getView()->registerMetaTag(['name' => 'description', 'content' => $model->description]);
        Yii::$app->getView()->registerMetaTag(['name' => 'keywords', 'content' => $model->keywords]);

        return $this->render('layouts/'.$model->layout,[
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
        $model = Module::getInstance()->manager->createPageQuery('mainPage');
        if ($model === false) {
            throw new NotFoundHttpException('The requested page does not exist.');
        }

        return $this->render('layouts/'.$model->layout,[
            'model' => $model,
        ]);
    }

}
