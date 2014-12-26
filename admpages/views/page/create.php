<?php

use pavlinter\admpages\Module;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Page */

Yii::$app->i18n->disableDot();
$this->title = Module::t('', 'Create Page');
$this->params['breadcrumbs'][] = ['label' => Module::t('', 'Pages'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
Yii::$app->i18n->enableDot();
?>
<div class="page-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
