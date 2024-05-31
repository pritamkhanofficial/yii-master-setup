<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use app\models\ClassType;
use app\models\Sections;
/** @var yii\web\View $this */
/** @var app\models\SchoolClass $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="school-class-form">
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
                        <?= $form->field($model, 'class',['options' => ['class' => 'form-group row'],'labelOptions'=>['class'=>'col-form-label col-sm-2']])->textInput(['maxlength' => true,'class'=>'form-control','required'=>true]) ?>
                    </div>

                    <div class="mb-3 row">
                        <?= $form->field($model, 'class_type',['options' => ['class' => 'form-group row'],'labelOptions'=>['class'=>'col-form-label col-sm-2']])->dropDownList(ArrayHelper::map(ClassType::find()->where(['is_active'=>1,'is_deleted'=>0])->all(), 'class_type_id', 'class_type'), ['prompt' => ' -- Select --','class' => 'form-select']) ?>

                    </div>

                    <div class="mb-3 row">
                        <?= $form->field($model, 'section_id',['options' => ['class' => 'form-group row'],'labelOptions'=>['class'=>'col-form-label col-sm-2']])->dropDownList(ArrayHelper::map(Sections::find()->where(['is_active'=>1,'is_deleted'=>0])->all(), 'section_id', 'section_name'), ['class' => 'form-select select2','data-placeholder' => '-- Select --','multiple'=>"multiple"]) ?>

                    </div>
                    <div class="mb-3 row">
                        <?php
                        if ($model->isNewRecord) {
                            echo $form->field($model, 'is_active',['options' => ['class' => 'form-group row form-check-success'],'labelOptions'=>['class'=>'col-form-label col-sm-2 form-check-success']])->checkBox(['maxlength' => true, 'uncheck' => 0, 'checked' => true,'class'=>'form-check-input'])->label('');
                        }else{
                            echo $form->field($model, 'is_active',['options' => ['class' => 'form-group row form-check-success'],'labelOptions'=>['class'=>'col-form-label col-sm-2 form-check-success']])->checkBox(['maxlength' => true, 'uncheck' => 0, 'class'=>'form-check-input'])->label('');
                        }
                        ?>
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
