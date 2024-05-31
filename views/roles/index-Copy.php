<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
// use yii\grid\GridView;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\RolesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Roles';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="roles-index">
        <!-- start page title -->
        <!-- end page title -->
        <div class="row">
            <div class="col-xl-12">
                <div class="card">
                    <div class="card-body">
                        <!-- <p>
                            < ?= Html::a('Create Roles', ['create'], ['class' => 'btn btn-success']) ?>
                        </p> -->
                        <?php // echo $this->render('_search', ['model' => $searchModel]); 
                        ?>
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
                                'heading' => '<h3 class="panel-title text-white">Roles</h3>',
                                'type' => 'primary',
                                'before' => Html::a('<i class="fas fa-plus"></i> Add Role', ['create'], ['class' => 'btn btn-sm btn-outline-primary']),
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

                                'display_name',
                                [
                                    'attribute' => 'is_active',
                                    'filterInputOptions' => ['class' => 'form-select'],
                                    'value' => function($model) {
                                        return $model->is_active==1?'Active':'Inactive';
                                    },
                                    'filter' => ['1'=>'Active','0'=>'Inactive'],
                                    'filterWidgetOptions' => [
                                        'options' => ['prompt' => 'Select'],
                                        'pluginOptions' => ['allowClear' => true],
                                    ],
                                ],
                                ['class' => 'yii\grid\ActionColumn', 'header'=>'Option','template'=>'{update}'],
                            ],
                        ]); ?>
                    </div>
                </div>
            </div>
        </div>
        <!-- end row -->
</div>