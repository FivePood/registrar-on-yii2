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

    <?= $form->field($model, 'legal')->widget(Select2::classname(), [
        'data' => ApplicationFilingForm::legalLabels(),
        'hideSearch' => true,
        'options' => [
            'id'=>'legal',
            'placeholder' => 'Выберите юридический статус клиента...'
        ]
    ]);?>
    <?= $form->field($model, 'userName')->textInput(['maxlength' => 255]) ?>

    <?= $form->field($model, 'toBirthString')->widget(DatePicker::classname(), [
        'data' => $model->toBirthString,
        'options' => ['placeholder' => 'Выберите дату рождения...'],
        'pluginOptions' => [
            'autoclose' => true,
            'startDate' => \Yii::$app->formatter->asDate('1900-01-01'),
            'endDate' => \Yii::$app->formatter->asDate(time()),
            'initialize' => true,
            'allowClear' => false,
        ],
    ])->label('Дата рождения') ?>

    <?= $form->field($model, 'type')->textInput(['maxlength' => 255]) ?>

    <div class="row justify-content-xl-center">
        <div style='margin-right: 10px'>
            <?= $form->field($model, 'series')->textInput(['maxlength' => 255]) ?>
        </div>
            <?= $form->field($model, 'number')->textInput(['maxlength' => 255]) ?>
        <div style='margin-left: 10px'>
            <?= $form->field($model, 'issued')->textInput(['maxlength' => 255]) ?>
        </div>
    </div>
    <?= $form->field($model, 'issuer')->textInput(['maxlength' => 255]) ?>
    <?= $form->field($model, 'email')->textInput(['maxlength' => 255]) ?>
    <?= $form->field($model, 'phone')->textInput(['maxlength' => 255]) ?>
    <?= $form->field($model, 'index')->textInput(['maxlength' => 255]) ?>
    <?= $form->field($model, 'city')->textInput(['maxlength' => 255]) ?>
    <?= $form->field($model, 'street')->textInput(['maxlength' => 255]) ?>


    <!--    --><? //= $form->field($model, 'clientId')->widget(MaskedInput::className(), [
    //        'mask' => '[[9]{1,10}]',
    //        'clientOptions' => ['greedy' => false],
    //        'options' => ['placeholder' => ''],
    //    ]) ?>

    <?= $form->field($model, 'domainName')->textInput(['maxlength' => 63]) ?>

    <?= $form->field($model, 'vendorId')->textInput(['maxlength' => 255]) ?>

    <?= $form->field($model, 'period')->widget(MaskedInput::className(), [
        'mask' => '[[9]{1,10}]',
        'clientOptions' => ['greedy' => false],
        'options' => ['placeholder' => ''],
    ]) ?>

    <?= $form->field($model, 'authCode')->textInput(['maxlength' => 255]) ?>

    <?= $form->field($model, 'noCheck')->checkbox() ?>

    <div class="form-group">
        <?= Html::submitButton('Подать заявку', ['class' => 'btn btn-primary', 'name' => 'application-filing-button']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
