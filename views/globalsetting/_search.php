<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\GlobalsettingSearch $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="globalsetting-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'global_settings_id') ?>

    <?= $form->field($model, 'organization_name') ?>

    <?= $form->field($model, 'organization_code') ?>

    <?= $form->field($model, 'organization_email') ?>

    <?= $form->field($model, 'address') ?>

    <?php // echo $form->field($model, 'phone') ?>

    <?php // echo $form->field($model, 'currency') ?>

    <?php // echo $form->field($model, 'currency_symbol') ?>

    <?php // echo $form->field($model, 'footer_text') ?>

    <?php // echo $form->field($model, 'timezone') ?>

    <?php // echo $form->field($model, 'date_format') ?>

    <?php // echo $form->field($model, 'facebook_url') ?>

    <?php // echo $form->field($model, 'twitter_url') ?>

    <?php // echo $form->field($model, 'linkedin_url') ?>

    <?php // echo $form->field($model, 'youtube_url') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <?php // echo $form->field($model, 'updated_at') ?>

    <?php // echo $form->field($model, 'created_by') ?>

    <?php // echo $form->field($model, 'updated_by') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
