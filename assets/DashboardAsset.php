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
class DashboardAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        [
            'href' => 'web/back/images/favicon.ico',
            'rel' => 'icon'
        ],
        'web/back/css/bootstrap.min.css',
        'web/back/css/icons.min.css',
        'web/back/css/app.min.css',
        'web/back/css/custom.css',
    ];
    public $js = [
        // 'backassets/libs/jquery/jquery.min.js',
        'web/back/js/bootstrap.bundle.min.js',
        'web/back/js/metisMenu.min.js',
        'web/back/js/simplebar.min.js',
        'web/back/js/waves.min.js',
        'web/back/js/apexcharts.min.js',
        'web/back/js/pages/dashboard.init.js',
        'web/back/js/app.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap5\BootstrapAsset'
    ];
}
