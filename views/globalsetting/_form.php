<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\Globalsetting $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="globalsetting-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'organization_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'organization_code')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'organization_email')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'address')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'phone')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'currency')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'currency_symbol')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'footer_text')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'timezone')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'date_format')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'facebook_url')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'twitter_url')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'linkedin_url')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'youtube_url')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'created_at')->textInput() ?>

    <?= $form->field($model, 'updated_at')->textInput() ?>

    <?= $form->field($model, 'created_by')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'updated_by')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
