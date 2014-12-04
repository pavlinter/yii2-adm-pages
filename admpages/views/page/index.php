<?php

use kartik\grid\GridView;
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
        <?= Html::a(Adm::t('admpage', 'Create Page'), ['create', 'id_parent' => $id_parent], ['class' => 'btn btn-primary']) ?>

        <?= Html::a(Adm::t('admpage', 'All pages'), [''], ['class' => 'btn btn-info']) ?>

        <?= Html::a(Adm::t('admpage', 'Front pages'), ['','id_parent' => 0,], ['class' => 'btn btn-info']) ?>
    </p>

    <?= Adm::widget('GridView',[
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'nestable' => [],
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            [
                'attribute' => 'id_parent',
                'vAlign' => 'middle',
                'hAlign' => 'center',
                'format' => 'html',
                'enableSorting' => false,
                'visible' => $id_parent === false,
                'value' => function ($model) {
                    if ($model->parent) {
                        return Html::a($model->parent->name,['','id_parent' => $model->id_parent]);
                    }
                },
            ],
            [
              'attribute' => 'name',
              'vAlign' => 'middle',
              'hAlign' => 'center',
              'value' => function ($model) {
                  return $model->name;
              },
            ],
            [
                'attribute' => 'title',
                'vAlign' => 'middle',
                'hAlign' => 'center',
                'value' => function ($model) {
                    return $model->title;
                },
            ],
            [
                'attribute' => 'alias',
                'vAlign' => 'middle',
                'hAlign' => 'center',
                'value' => function ($model) {
                    if ($model->url) {
                        return $model->url;
                    }
                    return $model->alias;
                },
            ],
            [
                'attribute' => 'layout',
                'vAlign' => 'middle',
                'hAlign' => 'center',
                'vAlign' => 'middle',
                'filterType' => GridView::FILTER_SELECT2,
                'filter'=> Module::getInstance()->pageLayouts,
                'value' => function ($model) {
                    if (isset(Module::getInstance()->pageLayouts[$model->layout])) {
                        return Module::getInstance()->pageLayouts[$model->layout];
                    }
                },
                'filterWidgetOptions' => [
                    'pluginOptions' => ['allowClear' =>true ],
                ],
                'filterInputOptions' => ['placeholder' => Adm::t('','Select ...', ['dot' => false])],
                'format' => 'raw'
            ],
            [
                'attribute' => 'type',
                'vAlign' => 'middle',
                'hAlign' => 'center',
                'vAlign' => 'middle',
                'hAlign' => 'center',
                'filterType' => GridView::FILTER_SELECT2,
                'filter'=> Module::getInstance()->pageTypes,
                'value' => function ($model) {
                    if (isset(Module::getInstance()->pageTypes[$model->type])) {
                        return Module::getInstance()->pageTypes[$model->type];
                    }
                },
                'filterWidgetOptions' => [
                    'pluginOptions' => ['allowClear' =>true ],
                ],
                'filterInputOptions' => ['placeholder' => Adm::t('','Select ...', ['dot' => false])],
                'format' => 'raw'
            ],
            [
                'attribute' => 'weight',
                'vAlign' => 'middle',
                'hAlign' => 'center',
            ],
            [
                'class' => '\kartik\grid\BooleanColumn',
                'attribute' => 'active',
                'vAlign' => 'middle',
                'hAlign' => 'center',
                'filterType' => GridView::FILTER_SELECT2,
                'filterWidgetOptions' => [
                    'options' => ['placeholder' => Adm::t('','Select ...', ['dot' => false])],
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
                ],
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{view} {update} {subpages} {copy} {delete}',
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
                    'view' => function ($url, $model) {
                        if ($model->alias) {
                            return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', ['default/index', 'alias' => $model->alias], [
                                'title' => Yii::t('yii', 'View'),
                                'data-pjax' => '0',
                                'target' => '_blank'
                            ]);
                        }
                    },
                    'copy' => function ($url, $model) {
                        return Html::a('<span class="fa fa-copy"></span>', ['create', 'id' => $model->id], [
                            'title' => Adm::t('admpage', 'Copy', ['dot' => false]),
                            'data-pjax' => '0',
                        ]);
                    },
                    'subpages' => function ($url, $model) {
                        return Html::a('<span class="fa fa-plus-circle"></span>', ['', 'id_parent' => $model->id], [
                            'title' => Adm::t('admpage', 'Sub pages', ['dot' => false]),
                            'data-pjax' => '0',
                        ]);
                    },
                ],
            ],
        ],
    ]); ?>

</div>
