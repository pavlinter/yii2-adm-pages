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
        <?= Html::a(Adm::t('admpage', 'Create Page'), ['create', 'id_parent' => $id_parent], ['class' => 'btn btn-success']) ?>

        <?= Html::a(Adm::t('admpage', 'All pages'), [''], ['class' => 'btn btn-info']) ?>

        <?= Html::a(Adm::t('admpage', 'Front pages'), ['','id_parent' => 0,], ['class' => 'btn btn-info']) ?>
    </p>

    <?= Adm::widget('GridView',[
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            [
                'attribute' => 'id_parent',
                'format' => 'html',
                'visible' => $id_parent === false,
                'value' => function ($model) {
                    if ($model->parent) {
                        return Html::a($model->parent->name,['','id_parent' => $model->id_parent]);
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
            'alias',
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
                'template' => '{view} {show} {update} {subpages} {copy} {delete}',
                'buttons' => [
                    'delete' => function ($url, $model) {
                        if (in_array($model->id, Module::getInstance()->closeDeletePage)) {
                            return null;
                        }
                        return Html::a('<span class="glyphicon glyphicon-trash"></span>', $url, [
                            'title' => Yii::t('yii', 'Delete'),
                            'data-confirm' => Yii::t('yii', 'Are you sure you want to delete this item?'),
                            'data-method' => 'post',
                            'data-pjax' => '0',
                        ]);
                    },
                    'show' => function ($url, $model, $key) {
                        if ($model->alias) {
                            return Html::a(null, ['default/index', 'alias' => $model->alias],['class' => 'fa fa-laptop', 'title' => Adm::t('admpage', 'Example', ['dot' => false]),'target' => '_blank']);
                        }
                    },
                    'copy' => function ($url, $model, $key) {
                        return Html::a(null, ['create', 'id' => $model->id],['class' => 'fa fa-copy', 'title' => Adm::t('admpage', 'Copy', ['dot' => false]),]);
                    },
                    'subpages' => function ($url, $model, $key) {
                        return Html::a(null, ['', 'id_parent' => $model->id],['class' => 'fa fa-plus-circle', 'title' => Adm::t('admpage', 'Sub pages', ['dot' => false]),]);
                    }

                ],
            ],
        ],
    ]); ?>

</div>
