<?php

use pavlinter\admpages\Module;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Page */

$this->title = Module::t('', 'Update Page: ') . ' ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => Module::t('', 'Pages'), 'url' => ['index']];
$this->params['breadcrumbs'][] = Module::t('', 'Update');
?>
<div class="page-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
