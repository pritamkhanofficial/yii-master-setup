<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Roles */

$this->title = 'Update Roles';
$this->params['breadcrumbs'][] = ['label' => 'Roles', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'role_id' => $model->role_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="roles-update">

    <!-- <h1>< ?= Html::encode($this->title) ?></h1> -->

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
