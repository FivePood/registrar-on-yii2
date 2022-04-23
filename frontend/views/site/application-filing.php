<?php

/** @var yii\web\View $this */
/** @var yii\bootstrap4\ActiveForm $form */

/** @var \frontend\models\ApplicationFilingForm $model */

use yii\bootstrap4\Html;
use yii\bootstrap4\ActiveForm;
use yii\widgets\MaskedInput;

?>
<div class="site-application-filing">
    <h1><?= Html::encode($this->title) ?></h1>

    <?php $form = ActiveForm::begin(['id' => 'application-filing-form']); ?>

    <?= $form->field($model, 'clientId')->widget(MaskedInput::className(), [
        'mask' => '[[9]{1,10}]',
        'clientOptions' => ['greedy' => false],
        'options' => ['placeholder' => ''],
    ]) ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => 63]) ?>

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
