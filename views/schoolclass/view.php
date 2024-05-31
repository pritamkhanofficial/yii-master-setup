<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\SchoolClass $model */

$this->title = $model->school_class_id;
$this->params['breadcrumbs'][] = ['label' => 'School Classes', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="school-class-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'school_class_id' => $model->school_class_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'school_class_id' => $model->school_class_id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'school_class_id',
            'class',
            'class_type',
            'created_at',
            'updated_at',
            'created_by',
            'updated_by',
            'is_active',
            'is_deleted',
        ],
    ]) ?>

</div>
