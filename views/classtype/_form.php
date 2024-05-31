<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\ClassType $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="class-type-form">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <?php $form = ActiveForm::begin([
                                            'fieldConfig' => [
                                                'template' => "{label}\n<div class=\"col-sm-10\">\n{input}{error}</div>",
                                                    //'labelOptions' => ['class' => 'col-lg-1 col-form-label'],
                                                    ],
                                        ]); ?>
                    <div class="mb-3 row">
                        <!-- <label for="example-text-input" class="col-md-2 col-form-label">Text</label>
                                            <div class="col-md-10">
                                                <input class="form-control" type="text" value="Artisanal kale"
                                                    id="example-text-input">
                                            </div> -->
                        <?= $form->field($model, 'class_type',['options' => ['class' => 'form-group row'],'labelOptions'=>['class'=>'col-form-label col-sm-2']])->textInput(['maxlength' => true,'class'=>'form-control','required'=>true]) ?>
                    </div>
                    <div class="mb-3 row">
                        <div class="col-md-2">

                        </div>
                        <div class="col-md-10">
                            <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
                            <?=Html::a('Back',['index'],['class'=>'btn btn-danger']); ?>
                        </div>
                    </div>
                    <?php ActiveForm::end(); ?>
                </div>
            </div>
        </div> <!-- end col -->
    </div>
</div>