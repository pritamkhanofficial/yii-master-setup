<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\ClassType $model */

$this->title = 'Edit Class Type';
$this->params['breadcrumbs'][] = ['label' => 'Class Types', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->class_type_id, 'url' => ['view', 'class_type_id' => $model->class_type_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="class-type-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
