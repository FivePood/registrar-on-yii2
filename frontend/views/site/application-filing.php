<?php

/** @var yii\web\View $this */
/** @var yii\bootstrap4\ActiveForm $form */
/** @var \frontend\models\ApplicationFilingForm $model */

use yii\bootstrap4\Html;
use yii\bootstrap4\ActiveForm;

?>
<div class="site-application-filing">
    <h1><?= Html::encode($this->title) ?></h1>

    <div class="row">
        <div class="col-lg-5">
            <?php $form = ActiveForm::begin(['id' => 'application-filing-form']); ?>

            <?= $form->field($model, 'name')->textInput(['autofocus' => true]) ?>

            <div class="form-group">
                <?= Html::submitButton('Подать заявку', ['class' => 'btn btn-primary', 'name' => 'application-filing-button']) ?>
            </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>

</div>
