<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\SchoolClass $model */

$this->title = 'Edit Class';
$this->params['breadcrumbs'][] = ['label' => 'School Classes', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->school_class_id, 'url' => ['view', 'school_class_id' => $model->school_class_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="school-class-update">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
