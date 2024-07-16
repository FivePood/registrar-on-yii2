<?php

use frontend\models\UpdateDnsForm;
use yii\bootstrap4\Html;
use yii\bootstrap4\ActiveForm;
use yii\widgets\MaskedInput;

/** @var yii\web\View $this */
/** @var yii\bootstrap4\ActiveForm $form */
/** @var UpdateDnsForm $model */
?>
<div class="site-update-dns">
    <h1><?= Html::encode($this->title) ?></h1>

    <?php $form = ActiveForm::begin(['id' => 'update-dns-form']); ?>

    <div class="row" style='margin-bottom: 10px'>
        <?= $form->field($model, 'domainId', ['options' => ['class' => 'col-lg-3']])->widget(MaskedInput::className(), [
            'mask' => '[[9]{1,10}]',
            'clientOptions' => ['greedy' => false]
        ]) ?>

        <?= $form->field($model, 'dns', ['options' => ['class' => 'col-lg-9']])->textInput(['maxlength' => 255]) ?>
    </div>


    <div class="form-group">
        <?= Html::submitButton('Обновить DNS', [
            'class' => 'btn btn-outline-dark btn-block',
            'style' => 'margin-top: 20px',
            'name' => 'update-dns-button'
        ]) ?>
    </div>
    <?php ActiveForm::end(); ?>

</div>
