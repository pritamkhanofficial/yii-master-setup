<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\ClassType $model */

$this->title = 'Add Class Type';
$this->params['breadcrumbs'][] = ['label' => 'Class Types', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="class-type-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
