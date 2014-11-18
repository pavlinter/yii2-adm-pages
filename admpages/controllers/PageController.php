<?php

namespace pavlinter\admpages\controllers;

use pavlinter\admpages\Module;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * PageController implements the CRUD actions for Page model.
 */
class PageController extends Controller
{
    /**
    * @inheritdoc
    */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Lists all Page models.
     * @return mixed
     */
    public function actionIndex($id_parent = false)
    {
        $searchModel  = Module::getInstance()->manager->createPageSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, $id_parent);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'id_parent' => $id_parent,
        ]);
    }

    /**
     * Displays a single Page model.
     * @param string $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Page model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($id = null, $id_parent = null)
    {

        $model = Module::getInstance()->manager->createPage();

        $data = Yii::$app->request->post();
        if ($model->loadAll($data)) {
            if ($model->validateAll()) {
                if ($model->saveAll()) {
                    return $this->redirect(['view', 'id' => $model->id]);
                }
            }
        } else {
            if($id){
                $model = $this->findModel($id);
                $model->setIsNewRecord(true);
            } else if($id_parent){
                $model->id_parent = $id_parent;
            }
        }



        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Page model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        if ($model->loadAll(Yii::$app->request->post()) && $model->validateAll()) {
            if ($model->saveAll(false)) {
                return $this->redirect(['view', 'id' => $model->id]);
            }
        }
        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Page model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        if (!in_array($id, Module::getInstance()->closeDeletePage)) {
            $this->findModel($id)->delete();
        }
        return $this->redirect(['index']);
    }

    /**
     * Finds the Page model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return Page the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {

        $model = Module::getInstance()->manager->createPageQuery('find')->with(['translations'])->where(['id' => $id])->one();
        if ($model !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
