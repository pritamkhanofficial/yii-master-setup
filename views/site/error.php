<?php

/** @var yii\web\View $this */
/** @var string $name */
/** @var string $message */
/** @var Exception$exception */
use yii\helpers\Url;
use yii\helpers\Html;

$this->title = $name;
$homeUrl = Url::base(true).'/web/back/';
?>
<?php if($exception !== NULL && $exception->statusCode == 500){ ?>
<div class="account-pages my-5 pt-5">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="text-center mb-5">
                    <h1 class="display-2 fw-medium">5<i class="bx bx-buoy bx-spin text-primary display-3"></i>0</h1>
                    <h1 class="text-uppercase"><?= Html::encode($this->title) ?></h1>
                    <h4 class="text-uppercase"><?= nl2br(Html::encode($message)) ?></h4>
                    <div class="mt-5 text-center">
                        <?php if(Yii::$app->user->isGuest){?>
                        <a class="btn btn-primary waves-effect waves-light" href="<?=Url::to(['site/index'])?>">Back to
                            Login</a>
                        <?php }else{ ?>
                        <a class="btn btn-primary waves-effect waves-light"
                            href="<?=Url::to(['dashboard/index'])?>">Back to Dashboard</a>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-md-8 col-xl-6">
                <div>
                    <img src="<?=$homeUrl?>images/error-img.png" alt="" class="img-fluid">
                </div>
            </div>
        </div>
    </div>
</div>
<?php  }else{ ?>
<div class="account-pages my-5 pt-5">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="text-center mb-5">
                    <h1 class="display-2 fw-medium">4<i class="bx bx-buoy bx-spin text-primary display-3"></i>4</h1>
                    <h1 class="text-uppercase"><?= Html::encode($this->title) ?></h1>
                    <h4 class="text-uppercase"><?= nl2br(Html::encode($message)) ?></h4>
                    <div class="mt-5 text-center">
                        <?php if(Yii::$app->user->isGuest){?>
                        <a class="btn btn-primary waves-effect waves-light" href="<?=Url::to(['site/index'])?>">Back to
                            Login</a>
                        <?php }else{ ?>
                        <a class="btn btn-primary waves-effect waves-light"
                            href="<?=Url::to(['dashboard/index'])?>">Back to Dashboard</a>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-md-8 col-xl-6">
                <div>
                    <img src="<?=$homeUrl?>images/error-img.png" alt="" class="img-fluid">
                </div>
            </div>
        </div>
    </div>
</div>
<?php } ?>