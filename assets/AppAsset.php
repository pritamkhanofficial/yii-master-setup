<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\assets;

use yii\web\AssetBundle;

/**
 * Main application asset bundle.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        
        [
            'assets/images/favicon.ico', 
            'rel'=>"icon",
            'type'=>"image/x-icon"
        ],
        'web/back/css/toastr.min.css',
        'web/back/css/bootstrap.min.css',
        'web/back/css/icons.min.css',
        'web/back/css/select2.min.css',
        'web/back/css/app.min.css',
        'web/back/css/custom.css',

    ];
    public $js = [
        //'web/backpanelassets/libs/jquery/jquery.min.js',
        'web/back/js/bootstrap.bundle.min.js',
        'web/back/js/toastr.min.js',
        'web/back/js/simplebar.min.js',
        'web/back/js/waves.min.js',
        'web/back/js/metisMenu.min.js',
        'web/back/js/select2.min.js',
        'web/back/js/app.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap4\BootstrapAsset',
        //'sdelfi\datatables\DataTablesAsset',
        // 'reine\datatables\DataTablesAsset',
        //'fedemotta\datatables\DataTablesAsset',
    ];
}
