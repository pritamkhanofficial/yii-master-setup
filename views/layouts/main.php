<?php

/** @var yii\web\View $this */
/** @var string $content */

use app\assets\AppAsset;
use app\widgets\Alert;
use yii\bootstrap5\Breadcrumbs;
use yii\bootstrap5\Html;
use yii\bootstrap5\Nav;
use yii\bootstrap5\NavBar;


AppAsset::register($this);

$this->registerCsrfMetaTags();
$this->registerMetaTag(['charset' => Yii::$app->charset], 'charset');

$this->registerCssFile(Yii::$app->request->baseUrl.'/web/back/css/dataTables.bootstrap5.min.css');
$homeUrl = Yii::$app->homeUrl.'web/back/';
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

    /* Required datatable js */ 
    $this->registerJsFile(Yii::$app->request->baseUrl.'/web/back/js/jquery.dataTables.min.js',['depends' => [\yii\web\JqueryAsset::className()]]); 
    $this->registerJsFile(Yii::$app->request->baseUrl.'/web/back/js/dataTables.bootstrap5.min.js',['depends' => [\yii\web\JqueryAsset::className()]]); 
    $this->registerJsFile(Yii::$app->request->baseUrl.'/web/back/js/dataTables.responsive.min.js',['depends' => [\yii\web\JqueryAsset::className()]]); 
    $this->registerJsFile(Yii::$app->request->baseUrl.'/web/back/js/responsive.bootstrap4.min.js',['depends' => [\yii\web\JqueryAsset::className()]]);
    ?>
    <?php $this->endBody() ?>

    <script type="text/javascript">
        $(document).ready(function(){
            $(".select2").select2()

            <?php if(Yii::$app->session->getFlash('type')){ ?>
            toastr["<?=Yii::$app->session->getFlash('type')?>"]("<?=Yii::$app->session->getFlash('message')?>", "<?=Yii::$app->session->getFlash('title')?>")

            toastr.options = {
            "closeButton": true,
            "debug": false,
            "newestOnTop": false,
            "progressBar": true,
            "positionClass": "toast-top-right",
            "preventDuplicates": false,
            "onclick": null,
            "showDuration": 300,
            "hideDuration": 1000,
            "timeOut": 5000,
            "extendedTimeOut": 1000,
            "showEasing": "swing",
            "hideEasing": "linear",
            "showMethod": "fadeIn",
            "hideMethod": "fadeOut"
            }
            <?php } ?>
        });
</script>
</body>
</html>
<?php $this->endPage() ?>
