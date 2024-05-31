<?php

use app\models\Globalsetting;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var app\models\GlobalsettingSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Globalsettings';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="globalsetting-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Globalsetting', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'global_settings_id',
            'organization_name',
            'organization_code',
            'organization_email:email',
            'address:ntext',
            //'phone',
            //'currency',
            //'currency_symbol',
            //'footer_text',
            //'timezone',
            //'date_format',
            //'facebook_url:url',
            //'twitter_url:url',
            //'linkedin_url:url',
            //'youtube_url:url',
            //'created_at',
            //'updated_at',
            //'created_by',
            //'updated_by',
            [
                'class' => ActionColumn::className(),
                'urlCreator' => function ($action, Globalsetting $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'global_settings_id' => $model->global_settings_id]);
                 }
            ],
        ],
    ]); ?>


</div>
