<?php

/** @var yii\web\View $this */
/** @var yii\bootstrap4\ActiveForm $form */

/** @var \frontend\models\UpdateDnsForm $model */

use yii\bootstrap4\Html;
use yii\bootstrap4\ActiveForm;

?>
<div class="site-contact">
    <h1><?= Html::encode($this->title) ?></h1>

    <?php $form = ActiveForm::begin(['id' => 'contact-form']); ?>

    <?= $form->field($model, 'id')->textInput(['autofocus' => true]) ?>

    <?= $form->field($model, 'dnskey')->textInput(['autofocus' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton('Обновить DNS', ['class' => 'btn btn-primary', 'name' => 'contact-button']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
