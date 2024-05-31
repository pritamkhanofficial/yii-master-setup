<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Sections $model */

$this->title = 'Add Section';
$this->params['breadcrumbs'][] = ['label' => 'Sections', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="sections-create">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>
