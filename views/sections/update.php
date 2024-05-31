<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Sections $model */

$this->title = 'Edit Sections';
$this->params['breadcrumbs'][] = ['label' => 'Sections', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->section_id, 'url' => ['view', 'section_id' => $model->section_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="sections-update">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
