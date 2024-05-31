<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Globalsetting $model */

$this->title = 'Create Globalsetting';
$this->params['breadcrumbs'][] = ['label' => 'Globalsettings', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="globalsetting-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
