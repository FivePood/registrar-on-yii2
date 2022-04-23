<?php

/** @var yii\web\View $this */
/** @var yii\bootstrap4\ActiveForm $form */

/** @var \frontend\models\UpdateDnsForm $model */

use yii\bootstrap4\Html;
use yii\bootstrap4\ActiveForm;
use yii\widgets\MaskedInput;

?>
<div class="site-update-dns">
    <h1><?= Html::encode($this->title) ?></h1>

    <?php $form = ActiveForm::begin(['id' => 'update-dns-form']); ?>

    <?= $form->field($model, 'clientId')->widget(MaskedInput::className(), [
        'mask' => '[[9]{1,10}]',
        'clientOptions' => ['greedy' => false],
        'options' => ['placeholder' => ''],
    ]) ?>

    <?= $form->field($model, 'domainId')->widget(MaskedInput::className(), [
        'mask' => '[[9]{1,10}]',
        'clientOptions' => ['greedy' => false],
        'options' => ['placeholder' => ''],
    ]) ?>

    <?= $form->field($model, 'dnskey')->textInput(['maxlength' => 255]) ?>

    <div class="form-group">
        <?= Html::submitButton('Обновить DNS', ['class' => 'btn btn-primary', 'name' => 'update-dns-button']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
