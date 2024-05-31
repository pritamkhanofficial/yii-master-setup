<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\Globalsetting $model */

$this->title = $model->global_settings_id;
$this->params['breadcrumbs'][] = ['label' => 'Globalsettings', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="globalsetting-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'global_settings_id' => $model->global_settings_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'global_settings_id' => $model->global_settings_id], [
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
            'global_settings_id',
            'organization_name',
            'organization_code',
            'organization_email:email',
            'address:ntext',
            'phone',
            'currency',
            'currency_symbol',
            'footer_text',
            'timezone',
            'date_format',
            'facebook_url:url',
            'twitter_url:url',
            'linkedin_url:url',
            'youtube_url:url',
            'created_at',
            'updated_at',
            'created_by',
            'updated_by',
        ],
    ]) ?>

</div>
