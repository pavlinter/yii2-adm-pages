<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use pavlinter\adm\Adm;

/* @var $this yii\web\View */
/* @var $model app\models\Page */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="admpage-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->errorSummary([$model] + $model->getLangModels()); ?>

    <?= $form->field($model, 'id_parent')->widget(\kartik\widgets\Select2::classname(), [
		'data' => [],
		'options' => ['placeholder' => Adm::t('','Select ...', ['dot' => false])],
		'pluginOptions' => [
			'allowClear' => true,
		]
	]); ?>

    <?= $form->field($model, 'layout')->widget(\kartik\widgets\Select2::classname(), [
		'data' => [],
		'options' => ['placeholder' => Adm::t('','Select ...', ['dot' => false])],
		'pluginOptions' => [
			'allowClear' => true,
		]
	]); ?>

    <?= $form->field($model, 'weight')->textInput(['maxlength' => 10]) ?>

    <?= $form->field($model, 'visible')->checkbox() ?>

    <?= $form->field($model, 'active')->checkbox() ?>

    <?php  foreach (Yii::$app->getI18n()->getLanguages() as $id_language => $language) { ?>
        <section class="panel pos-rlt clearfix">
            <header class="panel-heading">
                <ul class="nav nav-pills pull-right">
                    <li>
                        <a href="#" class="panel-toggle text-muted"><i class="fa fa-caret-down text-active"></i><i class="fa fa-caret-up text"></i></a>
                    </li>
                </ul>
                <h3 class="panel-title"><?= $language['name'] ?></h3>
            </header>
            <div class="panel-body clearfix">

                <?= $form->field($model->getTranslation($id_language), '['.$id_language.']name')->textInput(['maxlength' => 100]) ?>
                <?= $form->field($model->getTranslation($id_language), '['.$id_language.']title')->textInput(['maxlength' => 80]) ?>
                <?= $form->field($model->getTranslation($id_language), '['.$id_language.']description')->textInput(['maxlength' => 200]) ?>
                <?= $form->field($model->getTranslation($id_language), '['.$id_language.']keywords')->textInput(['maxlength' => 250]) ?>
                <?= Adm::widget('FileInput',[
                    'form' => $form,
                    'model'      => $model->getTranslation($id_language),
                    'attribute'  => '['.$id_language.']image',
                ]);?>
                <?= $form->field($model->getTranslation($id_language), '['.$id_language.']alias')->textInput(['maxlength' => 200]) ?>
                <?= \pavlinter\adm\Adm::widget('Redactor',[
					'form' => $form,
					'model'      => $model->getTranslation($id_language),
					'attribute'  => '['.$id_language.']text'
				]) ?>

            </div>
        </section>

    <?php  }?>
        <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Adm::t('admpage', 'Create') : Adm::t('admpage', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
