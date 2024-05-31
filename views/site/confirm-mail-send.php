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
                
                <!-- end row -->
                <div class="row justify-content-center">
                    <div class="col-md-8 col-lg-6 col-xl-5">
                        <div class="card">
                            
                            <div class="card-body"> 
                                
                                <div class="p-2">
                                    <div class="text-center">

                                        <div class="avatar-md mx-auto">
                                            <div class="avatar-title rounded-circle bg-light">
                                                <i class="bx bx-mail-send h1 mb-0 text-primary"></i>
                                            </div>
                                        </div>
                                        <div class="p-2 mt-4">
                                            <h4>Success !</h4>
                                            <p class="text-muted"> Check your email for a link to reset your password. If it doesn’t appear within a few minutes, check your spam folder. </p>
                                            <div class="mt-4">
                                                <a href="<?=Url::to(['site/index'])?>" class="btn btn-success">Return to login</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
            
                            </div>
                        </div>
                        <div class="mt-5 text-center">
                            
                            <p>© <script>document.write(new Date().getFullYear())</script> Skote. Crafted with <i class="mdi mdi-heart text-danger"></i> by Themesbrand</p>
                        </div>

                    </div>
                </div>
            </div>