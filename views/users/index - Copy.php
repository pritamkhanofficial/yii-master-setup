<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use kartik\grid\GridView;


/* @var $this yii\web\View */
/* @var $searchModel app\models\UsersSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Users';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="users-index">

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'pjax' => true,
                            'pjaxSettings' => [
                                'options' => [
                                    'enablePushState' => false,
                                ],
                            ],

                            'panel' => [
                                'heading' => '<h3 class="panel-title text-white">Users</h3>',
                                'type' => 'primary',
                                'before' => Html::a('<i class="fas fa-plus"></i> Add User', ['create'], ['class' => 'btn btn-sm btn-outline-primary']),
                                'after' => Html::a('<i class="fas fa-redo"></i> Clear filter', ['index'], ['class' => 'btn btn-primary']),

                            ],
                            'toolbar' =>  [

                                '{toggleData}',
                            ],
                            //'bordered' => true,
                            'striped' => false,
                            'condensed' => true,
                            'hover' => true,
                            //'responsive' => true,
                            'toggleDataOptions' => ['minCount' => 10],
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'user_id',
            //'role_id',
            'username',
            'email:email',
            //'password',
            //'full_name',
            //'phone',
            //'profile_pic',
            //'last_ip',
            //'last_login',
            //'last_logout',
            //'is_online',
            //'is_block',
            //'generate_token',
            //'generate_on',
            //'created_at',
            //'updated_at',
            //'created_by',
            //'updated_by',
            //'is_active',
            /* [
                'class' => ActionColumn::className(),
                'urlCreator' => function ($action, $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'user_id' => $model->user_id]);
                 }
            ], */
        ],
    ]); ?>


</div>
