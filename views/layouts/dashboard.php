<?php

/** @var yii\web\View $this */
/** @var string $content */

use app\assets\DashboardAsset;
use app\widgets\Alert;
use yii\bootstrap5\Breadcrumbs;
use yii\bootstrap5\Html;
use yii\bootstrap5\Nav;
use yii\bootstrap5\NavBar;

DashboardAsset::register($this);

$this->registerCsrfMetaTags();
$this->registerMetaTag(['charset' => Yii::$app->charset], 'charset');
$homeUrl = Yii::$app->homeUrl.'back/';
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body data-sidebar="dark" data-layout-mode="light">
<?php $this->beginBody() ?>
    <div id="layout-wrapper">
        <?php require_once('components/header.php') ?>
        <?php require_once('components/sidebar.php') ?>
        <div class="main-content">
            <div class="page-content">
                <div class="container-fluid">
                    <?php require_once('components/breadcrumb.php') ?>
                    <?=$content;?>
                </div> 
            </div> 
            <?php require_once('components/footer.php') ?>
        </div>
    </div>
    <?php 
    /* $this->registerJsFile(Yii::$app->request->baseUrl.'/backassets/libs/bootstrap/js/bootstrap.bundle.min.js',['depends' => [\yii\web\JqueryAsset::className()]]);  */
    ?>
    <?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
