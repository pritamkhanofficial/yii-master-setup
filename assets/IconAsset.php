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
class IconAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';

    public $css = [
        
        'https://fonts.googleapis.com/css?family=Roboto:100,300,300i,400,500,600,700,900%7CRaleway:500%7CAbril+Fatface',
        'frontassets/css/bootstrap.css',
        'frontassets/css/fonts.css',
        ['frontassets/css/style.css', 'id'=>'main-styles-link'],
        'web/back/css/custom.css',
       

    ];
    public $js = [
        'frontassets/js/core.min.js',
        'frontassets/js/script.js',
     

    ];
   /* public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap4\BootstrapAsset',
    ];*/
}
