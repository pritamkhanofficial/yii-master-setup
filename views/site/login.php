<?php

/** @var yii\web\View $this */
/** @var yii\bootstrap5\ActiveForm $form */
/** @var app\models\LoginForm $model */

use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;
use  yii\helpers\Url;

$this->title = 'Login Page';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6 col-xl-5">
            <div class="card overflow-hidden">
                <div class="bg-primary bg-soft">
                    <div class="row">
                        <div class="col-7">
                            <div class="text-primary p-4">
                                <h5 class="text-primary">Welcome Back !</h5>
                                <p>Login to continue.</p>
                            </div>
                        </div>
                        <div class="col-5 align-self-end">
                            <img src="<?=Yii::$app->request->baseUrl?>/web/back//profile-img.png" alt=""
                                class="img-fluid">
                        </div>
                    </div>
                </div>
                <div class="card-body pt-0">
                    <div class="auth-logo">
                        <a href="index.html" class="auth-logo-light">
                            <div class="avatar-md profile-user-wid mb-4">
                                <span class="avatar-title rounded-circle bg-light">
                                    <img src="<?=Yii::$app->request->baseUrl?>/web/back//images/logo-light.svg" alt=""
                                        class="rounded-circle" height="34">
                                </span>
                            </div>
                        </a>

                        <a href="index.html" class="auth-logo-dark">
                            <div class="avatar-md profile-user-wid mb-4">
                                <span class="avatar-title rounded-circle bg-light">
                                    <img src="<?=Yii::$app->request->baseUrl?>/web/back//images/logo.svg" alt=""
                                        class="rounded-circle" height="34">
                                </span>
                            </div>
                        </a>
                    </div>
                    <div class="p-2">
                        <?php $form = ActiveForm::begin([
                                    'id' => 'login-form',
                                    'layout' => 'horizontal',
                                    'fieldConfig' => [
                                        'template' => "<div class=\"ee\">{label}\n{input}{error}</div>",
                                        'labelOptions' => ['class' => 'col-form-label fw-semibold'],
                                        'inputOptions' => ['class' => 'form-control'],
                                        'errorOptions' => ['class' => 'invalid-feedback'],
                                    ],
                                ]); ?>

                        <?= $form->field($model, 'username')->textInput(['autofocus' => true, 'placeholder' => "Enter Username/Email"]) ?>

                        <?= $form->field($model, 'password')->passwordInput(['placeholder' => "Enter Password"]) ?>
                        <div class="row">
                            <div class="col-6">
                                <?php echo $form->field($model, 'rememberMe',['options' => ['class' => ''],'labelOptions'=>['class'=>'form-check-label']])->checkBox(['template' => '<div class="form-check">{input}<label class="form-check-label fw-semibold text-dark" for="loginform-rememberme">{label}</label></div>','maxlength' => true, 'class'=>'form-check-input font-size-16']);?>
                            </div>
                        </div>
                        <div class="mt-3 d-grid">
                            <button class="btn btn-primary waves-effect waves-light" type="submit">Log In</button>
                        </div>

                        <div class="mt-4 text-center">
                            <a href="<?=Url::to(['site/forgotpassword'])?>" class="text-muted text-decoration-none"><i
                                    class="mdi mdi-lock me-1"></i> Forgot your password?</a>
                        </div>
                        <?php ActiveForm::end(); ?>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>