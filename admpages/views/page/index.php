<?php

use pavlinter\admpages\Module;
use yii\helpers\Html;
use pavlinter\adm\Adm;

/* @var $this yii\web\View */
/* @var $searchModel app\models\PageSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Adm::t('admpage', 'Pages');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="page-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a(Adm::t('admpage', 'Create {modelClass}', [
    'modelClass' => 'Page',
]), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= Adm::widget('GridView',[
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            [
                'attribute' => 'id_parent',
                'value' => function ($model) {
                    if ($model->parent) {
                        return $model->parent->name;
                    }
                },
            ],
            [
              'attribute' => 'name',
              'value' => function ($model) {
                  return $model->name;
              },
            ],
            [
                'attribute' => 'title',
                'value' => function ($model) {
                    return $model->title;
                },
            ],
            [
                'attribute' => 'layout',
                'filter' => Module::getInstance()->pageLayouts
            ],
            [
                'class' => '\kartik\grid\BooleanColumn',
                'attribute' => 'visible',
            ],
            [
                'class' => '\kartik\grid\BooleanColumn',
                'attribute' => 'active',
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{view} {update} {copy} {delete}',
                'buttons' => [
                    'copy' => function ($url, $model, $key) {
                        return Html::a(null, ['create', 'id' => $model->id],['class' => 'fa fa-copy', 'title' => Adm::t('admpage', 'Copy', ['dot' => false]),]);
                    }
                ],
            ],
        ],
    ]); ?>

</div>
