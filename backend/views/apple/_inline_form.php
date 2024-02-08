<?php

use yii\helpers\Html;
use yii\bootstrap5\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\AppleForm $model */
/** @var yii\bootstrap5\ActiveForm $form */
?>

<div class="apple-form">

    <?php $form = ActiveForm::begin([
        'action' => ['apple/create-multi'],
        'layout' => 'inline'
    ]); ?>
    <div class="row g-3">
        <div class="col">
            <?= $form->field($model, 'qty')->textInput(['type' => 'number']) ?>
        </div>
        <div class="col">
            <?= Html::submitButton('Сгенерировать', ['class' => 'btn btn-success']) ?>
        </div>
    </div>
    <?php ActiveForm::end(); ?>

</div>
