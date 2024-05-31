<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\SchoolClass $model */

$this->title = 'Add Class';
$this->params['breadcrumbs'][] = ['label' => 'School Classes', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="school-class-create">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
