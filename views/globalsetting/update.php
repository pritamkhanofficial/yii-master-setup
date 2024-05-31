<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Globalsetting $model */

$this->title = 'Update Globalsetting: ' . $model->global_settings_id;
$this->params['breadcrumbs'][] = ['label' => 'Globalsettings', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->global_settings_id, 'url' => ['view', 'global_settings_id' => $model->global_settings_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="globalsetting-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
