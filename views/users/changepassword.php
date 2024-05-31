<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\Roles;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model app\models\Users */
/* @var $form yii\widgets\ActiveForm */

$this->title = 'Change Password';
$this->params['breadcrumbs'][] = ['label' => 'Users', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="users-form">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <?php 
                    $form = ActiveForm::begin([
                                            'fieldConfig' => [
                                                'template' => "{label}\n<div class=\"col-sm-10\">\n{input}{error}</div>",
                                                    //'labelOptions' => ['class' => 'col-lg-1 col-form-label'],
                                                    ],
                                        ]); ?>
                    <div class="mb-3 row">
                        <?= $form->field($model, 'newPassword',['options' => ['class' => 'form-group row'],'labelOptions'=>['class'=>'col-form-label col-sm-2']])->passwordInput(['maxlength' => true,'class'=>'form-control','required'=>true]) ?>
                    </div>
                    <div class="mb-3 row">
                        <?= $form->field($model, 'confirmPassword',['options' => ['class' => 'form-group row'],'labelOptions'=>['class'=>'col-form-label col-sm-2']])->passwordInput(['maxlength' => true,'class'=>'form-control','required'=>true]) ?>
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