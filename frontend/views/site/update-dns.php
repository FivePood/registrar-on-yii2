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

    <div class="row" style='margin-bottom: 10px'>
        <?= $form->field($model, 'clientId', ['options' => ['class' => 'col-lg-6']])->widget(MaskedInput::className(), [
            'mask' => '[[9]{1,10}]',
            'clientOptions' => ['greedy' => false]
        ]) ?>
        <?= $form->field($model, 'domainId', ['options' => ['class' => 'col-lg-6']])->widget(MaskedInput::className(), [
            'mask' => '[[9]{1,10}]',
            'clientOptions' => ['greedy' => false]
        ]) ?>
    </div>

    <?= $form->field($model, 'dns')->textInput(['maxlength' => 255]) ?>

    <div class="form-group">
        <?= Html::submitButton('Обновить DNS', [
            'class' => 'btn btn-outline-dark btn-block',
            'style' => 'margin-top: 20px',
            'name' => 'update-dns-button'
        ]) ?>
    </div>
    <?php ActiveForm::end(); ?>

</div>
