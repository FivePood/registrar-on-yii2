<?php

/** @var yii\web\View $this */
/** @var yii\bootstrap4\ActiveForm $form */

/** @var \frontend\models\ApplicationFilingForm $model */

use frontend\models\ApplicationFilingForm;
use yii\bootstrap4\Html;
use yii\bootstrap4\ActiveForm;
use yii\widgets\MaskedInput;
use kartik\select2\Select2;
use kartik\date\DatePicker;

?>
<div class="site-application-filing">
    <h1><?= Html::encode($this->title) ?></h1>

    <?php $form = ActiveForm::begin(['id' => 'application-filing-form']); ?>

    <?= $form->field($model, 'userName')->textInput(['maxlength' => 255]) ?>

    <div class="row justify-content-center">

        <?= $form->field($model, 'legal', ['options' => ['class' => 'col-lg-6']])->widget(Select2::classname(), [
            'data' => ApplicationFilingForm::legalLabels(),
            'hideSearch' => true,
            'options' => [
                'id' => 'legal',
                'placeholder' => ''
            ]
        ]); ?>

        <?= $form->field($model, 'type', ['options' => ['class' => 'col-lg-6']])->widget(Select2::classname(), [
            'data' => ApplicationFilingForm::typeLabels(),
            'hideSearch' => true,
            'options' => [
                'id' => 'type',
                'placeholder' => ''
            ]
        ]); ?>

    </div>

    <div class="row justify-content-center" style='margin-top: 10px'>

        <?= $form->field($model, 'toBirthString', ['options' => ['class' => 'col-lg-3']])->widget(DatePicker::classname(), [
            'data' => $model->toBirthString,
            'pluginOptions' => [
                'autoclose' => true,
                'startDate' => \Yii::$app->formatter->asDate('1900-01-01'),
                'endDate' => \Yii::$app->formatter->asDate(time()),
                'initialize' => true,
                'allowClear' => false,
            ],
        ])->label('Дата рождения') ?>

        <?= $form->field($model, 'series', ['options' => ['class' => 'col-lg-3']])->textInput(['maxlength' => 255]) ?>

        <?= $form->field($model, 'number', ['options' => ['class' => 'col-lg-3']])->textInput(['maxlength' => 255]) ?>

        <?= $form->field($model, 'issued', ['options' => ['class' => 'col-lg-3']])->textInput(['maxlength' => 255]) ?>
    </div>

    <?= $form->field($model, 'issuer', ['options' => ['style' => 'margin-top: 10px']])->textInput(['maxlength' => 255]) ?>

    <div class="row justify-content-center" style='margin-top: 10px'>

        <?= $form->field($model, 'index', ['options' => ['class' => 'col-lg-4']])->textInput(['maxlength' => 255]) ?>

        <?= $form->field($model, 'city', ['options' => ['class' => 'col-lg-4']])->textInput(['maxlength' => 255]) ?>

        <?= $form->field($model, 'street', ['options' => ['class' => 'col-lg-4']])->textInput(['maxlength' => 255]) ?>

    </div>

    <div class="row justify-content-center" style='margin-top: 10px'>

        <?= $form->field($model, 'email', ['options' => ['class' => 'col-lg-6']])->textInput(['maxlength' => 255]) ?>

        <?= $form->field($model, 'phone', ['options' => ['class' => 'col-lg-6']])->textInput(['maxlength' => 255]) ?>

    </div>

    <div class="row justify-content-center" style='margin-top: 10px'>

        <?= $form->field($model, 'domainName', ['options' => ['class' => 'col-lg-8']])->textInput(['maxlength' => 63]) ?>

        <?= $form->field($model, 'period', ['options' => ['class' => 'col-lg-4']])->widget(MaskedInput::className(), [
            'mask' => '[[9]{1,10}]',
            'clientOptions' => ['greedy' => false],
            'options' => ['placeholder' => ''],
        ]) ?>

    </div>

    <div class="row justify-content-center" style='margin-top: 10px'>

        <?= $form->field($model, 'vendorId', ['options' => ['class' => 'col-lg-6']])->textInput(['maxlength' => 255]) ?>

        <?= $form->field($model, 'authCode', ['options' => ['class' => 'col-lg-6']])->textInput(['maxlength' => 255]) ?>

    </div>

    <?= $form->field($model, 'noCheck', ['options' => ['style' => 'margin-top: 10px']])->checkbox() ?>

    <div class="form-group">
        <?= Html::submitButton('Подать заявку', [
            'class' => 'btn btn-outline-dark btn-block',
            'style' => 'margin-top: 10px',
            'name' => 'application-filing-button'
        ]) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
