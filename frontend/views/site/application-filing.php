<?php

use frontend\models\ApplicationFilingForm;
use yii\bootstrap4\Html;
use yii\bootstrap4\ActiveForm;
use yii\widgets\MaskedInput;
use kartik\select2\Select2;
use kartik\date\DatePicker;

/** @var yii\web\View $this */
/** @var yii\bootstrap4\ActiveForm $form */
/** @var ApplicationFilingForm $model */
?>
<div class="site-application-filing">
    <h1><?= Html::encode($this->title) ?></h1>

    <?php $form = ActiveForm::begin(['id' => 'application-filing-form']); ?>

    <div class="row">
        <?= $form->field($model, 'userName', ['options' => ['class' => 'col-lg-9']])->textInput(['maxlength' => 255]) ?>

        <?= $form->field($model, 'inn', ['options' => ['class' => 'col-lg-3']])->widget(MaskedInput::className(), [
            'mask' => '[[9]{1,15}]',
            'clientOptions' => ['greedy' => false]
        ]) ?>
    </div>

    <div class="row" style='margin-top: 10px'>
        <?= $form->field($model, 'legal', ['options' => ['class' => 'col-lg-6']])->widget(Select2::classname(), [
            'data' => ApplicationFilingForm::legalLabels(),
            'hideSearch' => true,
            'options' => ['id' => 'legal'],
            'pluginOptions' => ['initialize' => true],
            'pluginEvents' => [
                'change' => "function() {
                if (this.value == 'org') {
                    $('#issuer-field').hide();
                    $('#document-field').hide();
                    $('#type-field').hide();
                    $('#org-kpp').show();
                    $('#org-okpo').show();
                    $('#issuer').prop('disabled', true);
                    $('#toBirthString').prop('disabled', true);
                    $('#series').prop('disabled', true);
                    $('#number').prop('disabled', true);
                    $('#toIssuedString').prop('disabled', true);
                    $('#type').prop('disabled', true);
                    $('#kpp').prop('disabled', false);
                    $('#okpo').prop('disabled', false);
                } else {
                    $('#issuer-field').show();
                    $('#document-field').show();
                    $('#type-field').show();
                    $('#org-kpp').hide();
                    $('#org-okpo').hide();
                    $('#issuer').prop('disabled', false);
                    $('#toBirthString').prop('disabled', false);
                    $('#series').prop('disabled', false);
                    $('#number').prop('disabled', false);
                    $('#toIssuedString').prop('disabled', false);
                    $('#type').prop('disabled', false);
                    $('#kpp').prop('disabled', true);
                    $('#okpo').prop('disabled', true);
                }
            }",
            ],
        ]); ?>

        <?= $form->field($model, 'type', [
            'options' => [
                'class' => 'col-lg-6',
                'id' => 'type-field'
            ]
        ])->widget(Select2::classname(), [
            'data' => ApplicationFilingForm::typeLabels(),
            'hideSearch' => true,
            'options' => ['id' => 'type']
        ]); ?>

        <?= $form->field($model, 'kpp', [
            'options' => [
                'class' => 'col-lg-3',
                'style' => 'display: none',
                'id' => 'org-kpp'
            ]
        ])->widget(MaskedInput::className(), [
            'mask' => '[[9]{1,9}]',
            'clientOptions' => ['greedy' => false],
        ]) ?>
        <?= $form->field($model, 'okpo', [
            'options' => [
                'class' => 'col-lg-3',
                'style' => 'display: none',
                'id' => 'org-okpo'
            ]
        ])->widget(MaskedInput::className(), [
            'mask' => '[[9]{1,10}]',
            'clientOptions' => ['greedy' => false],
        ]) ?>
    </div>

    <div class="row" id="document-field" style='margin-top: 10px'>
        <?= $form->field($model, 'toBirthString', ['options' => ['class' => 'col-lg-3']])->widget(DatePicker::classname(), [
            'data' => $model->toBirthString,
            'pluginOptions' => [
                'autoClose' => true,
                'startDate' => \Yii::$app->formatter->asDate('1900-01-01'),
                'initialize' => true,
                'allowClear' => false,
            ],
        ])->label('Дата рождения') ?>
        <?= $form->field($model, 'series', ['options' => ['class' => 'col-lg-3']])->textInput(['maxlength' => 255]) ?>
        <?= $form->field($model, 'number', ['options' => ['class' => 'col-lg-3']])->textInput(['maxlength' => 255]) ?>
        <?= $form->field($model, 'toIssuedString', ['options' => ['class' => 'col-lg-3']])->widget(DatePicker::classname(), [
            'data' => $model->toIssuedString,
            'pluginOptions' => [
                'autoClose' => true,
                'startDate' => \Yii::$app->formatter->asDate('1900-01-01'),
                'initialize' => true,
                'allowClear' => false,
            ],
        ])->label('Дата выдачи') ?>
    </div>

    <?= $form->field($model, 'issuer', ['options' => ['style' => 'margin-top: 10px', 'id' => 'issuer-field']])->textInput(['maxlength' => 255]) ?>

    <div class="row" style='margin-top: 10px'>
        <?= $form->field($model, 'index', ['options' => ['class' => 'col-lg-4']])->widget(MaskedInput::className(), [
            'mask' => '[[9]{1,6}]',
            'clientOptions' => ['greedy' => false],
        ]) ?>
        <?= $form->field($model, 'city', ['options' => ['class' => 'col-lg-4']])->textInput(['maxlength' => 255]) ?>
        <?= $form->field($model, 'street', ['options' => ['class' => 'col-lg-4']])->textInput(['maxlength' => 255]) ?>
    </div>

    <p style='margin: 10px 0'>Список адресов E-mail</p>
    <div class="row">
        <?= $form->field($model, 'email1', ['options' => ['class' => 'col-lg-4']])->textInput(['maxlength' => 255])->label(false) ?>
        <?= $form->field($model, 'email2', ['options' => ['class' => 'col-lg-4']])->textInput(['maxlength' => 255])->label(false) ?>
        <?= $form->field($model, 'email3', ['options' => ['class' => 'col-lg-4']])->textInput(['maxlength' => 255])->label(false) ?>
    </div>

    <div class="row" style='margin-top: 10px'>
        <?= $form->field($model, 'phones', ['options' => ['class' => 'col-lg-6']])->widget(MaskedInput::className(), [
            'mask' => '[+7 999 9999999, +7 999 9999999, +7 999 9999999]',
        ]) ?>
        <?= $form->field($model, 'faxes', ['options' => ['class' => 'col-lg-6']])->widget(MaskedInput::className(), [
            'mask' => '[+7 999 9999999, +7 999 9999999, +7 999 9999999]',
        ]) ?>
    </div>

    <div class="row" style='margin-top: 10px'>
        <?= $form->field($model, 'domainName', ['options' => ['class' => 'col-lg-8']])->textInput(['maxlength' => 63]) ?>
        <?= $form->field($model, 'period', ['options' => ['class' => 'col-lg-4']])->widget(MaskedInput::className(), [
            'mask' => '[[9]{1,10}]',
            'clientOptions' => ['greedy' => false],
        ]) ?>
    </div>

    <div class="row" style='margin-top: 10px'>
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

<?php
$legalOrg = ApplicationFilingForm::LEGAL_ORG;
$js = <<<JS
var legalOrg = $legalOrg;
$(document).ready(function () {
    if ($('#legal').val() == legalOrg) {
        $('#issuer-field').show();
        $('#document-field').show();
        $('#type-field').show();
        $('#org-kpp').hide();
        $('#org-okpo').hide();
        $('#issuer').prop('disabled', true);
        $('#toBirthString').prop('disabled', true);
        $('#series').prop('disabled', true);
        $('#number').prop('disabled', true);
        $('#toIssuedString').prop('disabled', true);
        $('#type').prop('disabled', true);
        $('#kpp').prop('disabled', false);
        $('#okpo').prop('disabled', false);
    } else {
        $('#issuer-field').hide();
        $('#document-field').hide();
        $('#type-field').hide();
        $('#org-kpp').show();
        $('#org-okpo').show();
        $('#issuer').prop('disabled', false);
        $('#toBirthString').prop('disabled', false);
        $('#series').prop('disabled', false);
        $('#number').prop('disabled', false);
        $('#toIssuedString').prop('disabled', false);
        $('#type').prop('disabled', false);
        $('#kpp').prop('disabled', true);
        $('#okpo').prop('disabled', true);
    }
})
JS;
$this->registerJs($js);
?>
