<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use pavlinter\adm\Adm;

/* @var $this yii\web\View */
/* @var $model app\models\Page */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => Adm::t('admpage', 'Pages'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="page-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Adm::t('admpage', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Adm::t('admpage', 'Delete'), ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Adm::t('admpage', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'id_parent',
            'layout',
            'weight',
            'visible',
            'active',
            //translations
            'name',
            'title',
            'description',
            'keywords',
            'image',
            'alias',
            'text',
        ],
    ]) ?>

</div>
