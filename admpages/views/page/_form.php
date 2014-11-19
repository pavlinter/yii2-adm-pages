<?php

use kartik\checkbox\CheckboxX;
use pavlinter\admpages\Module;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use pavlinter\adm\Adm;

/* @var $this yii\web\View */
/* @var $model app\models\Page */
/* @var $form yii\widgets\ActiveForm */

$parents = Module::getInstance()->manager->createPageQuery('find')->with(['translations']);
if (!$model->isNewRecord) {
    $parents->where(['!=', 'id' , $model->id]);
}


$parentsData = ArrayHelper::map($parents->all(), 'id', 'name');
?>

<div class="admpage-form">


    <?php $form = Adm::begin('ActiveForm'); ?>

    <?= $form->errorSummary([$model] + $model->getLangModels(), ['class' => 'alert alert-danger']); ?>

    <div class="row">
        <div class="col-xs-12 col-sm-6 col-md-3">
            <?= $form->field($model, 'id_parent')->widget(\kartik\widgets\Select2::classname(), [
                'data' => $parentsData,
                'options' => ['placeholder' => Adm::t('','Select ...', ['dot' => false])],
                'pluginOptions' => [
                    'allowClear' => true,
                ]
            ]); ?>
        </div>
        <div class="col-xs-12 col-sm-6 col-md-3">
        <?= $form->field($model, 'layout')->widget(\kartik\widgets\Select2::classname(), [
            'data' => Module::getInstance()->pageLayouts,
        ]); ?>
        </div>
        <div class="col-xs-12 col-sm-6 col-md-3">
            <?= $form->field($model, 'type')->widget(\kartik\widgets\Select2::classname(), [
                'data' => Module::getInstance()->pageTypes,
            ]); ?>
        </div>
        <div class="col-xs-12 col-sm-6 col-md-3">
            <?= $form->field($model, 'weight')->textInput(['maxlength' => 10]) ?>
        </div>
    </div>

    <section class="panel adm-langs-panel">
        <header class="panel-heading bg-light">
            <ul class="nav nav-tabs nav-justified text-uc">
                <?php  foreach (Yii::$app->getI18n()->getLanguages() as $id_language => $language) { ?>
                    <li><a href="#lang-<?= $id_language ?>" data-toggle="tab"><?= $language['name'] ?></a></li>
                <?php  }?>
            </ul>
        </header>
        <div class="panel-body">
            <div class="tab-content">
                <?php  foreach (Yii::$app->getI18n()->getLanguages() as $id_language => $language) { ?>
                    <div class="tab-pane" id="lang-<?= $id_language ?>">

                        <div class="row">
                            <div class="col-xs-12 col-sm-6 col-md-6">
                                <?= $form->field($model->getTranslation($id_language), '['.$id_language.']name')->textInput(['maxlength' => 100]) ?>
                            </div>
                            <div class="col-xs-12 col-sm-6 col-md-6">
                                <?= $form->field($model->getTranslation($id_language), '['.$id_language.']title')->textInput(['maxlength' => 80]) ?>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-12 col-sm-6 col-md-6">
                                <?= $form->field($model->getTranslation($id_language), '['.$id_language.']description')->textarea(['maxlength' => 200]) ?>
                            </div>
                            <div class="col-xs-12 col-sm-6 col-md-6">
                                <?= $form->field($model->getTranslation($id_language), '['.$id_language.']keywords')->textarea(['maxlength' => 250]) ?>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-xs-12 col-sm-6 col-md-6">
                                <?= $form->field($model->getTranslation($id_language), '['.$id_language.']alias')->textInput(['maxlength' => 200]) ?>
                            </div>
                            <div class="col-xs-12 col-sm-6 col-md-6">
                                <?= Adm::widget('FileInput',[
                                    'form' => $form,
                                    'model'      => $model->getTranslation($id_language),
                                    'attribute'  => '['.$id_language.']image',
                                ]);?>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-12">
                            <?= \pavlinter\adm\Adm::widget('Redactor',[
                                'form' => $form,
                                'model'      => $model->getTranslation($id_language),
                                'attribute'  => '['.$id_language.']text'
                            ]) ?>
                            </div>
                        </div>
                    </div>
                <?php  }?>
            </div>
        </div>
    </section>

        <div class="row">
            <div class="col-xs-6 col-sm-4 col-md-4">
                <?= $form->field($model, 'active', ["template" => "{input}\n{label}\n{hint}\n{error}"])->widget(CheckboxX::classname(), ['pluginOptions'=>['threeState' => false]]); ?>
            </div>
            <div class="col-xs-6 col-sm-4 col-md-4">
                <?= $form->field($model, 'visible', ["template" => "{input}\n{label}\n{hint}\n{error}"])->widget(CheckboxX::classname(), ['pluginOptions'=>['threeState' => false]]); ?>
            </div>
        </div>

        <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Adm::t('admpage', 'Create') : Adm::t('admpage', 'Update'), ['class' => 'btn btn-primary']) ?>
    </div>

    <?php Adm::end('ActiveForm'); ?>

</div>
