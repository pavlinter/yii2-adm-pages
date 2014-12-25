<?php

use pavlinter\admpages\Module;
use yii\helpers\Html;
use pavlinter\adm\Adm;
use mihaildev\elfinder\Assets;

/* @var $this yii\web\View */
/* @var $model app\models\Page */
/* @var $startPath string */

$this->title = $model->name;

$this->params['breadcrumbs'][] = ['label' => Module::t('', 'Pages'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['update', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Module::t('', 'Files');


Assets::register($this);
Assets::addLangFile(Yii::$app->language, $this);

$this->registerJs('
    $("#elfinder").elfinder({
        url  : "'.\yii\helpers\Url::to(['/adm/elfinder/connect', 'startPath' => $startPath]).'",
        lang : "'.Yii::$app->language.'",
        customData: {"'.Yii::$app->request->csrfParam.'":"'.Yii::$app->request->csrfToken.'"},
        rememberLastDir : false,
    });
');


?>
<div class="product-files">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Module::t('', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Module::t('', 'Delete'), ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Module::t('', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <div id="elfinder"></div>
</div>
