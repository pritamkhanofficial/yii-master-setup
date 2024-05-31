<?php

use app\models\Sections;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use richardfan\widget\JSRegister;

/** @var yii\web\View $this */
/** @var app\models\SectionsSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Sections';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="sections-index">
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-body">
                    <p class="text-end">
                        <?=Html::a('<i class="bx bx-plus-medical"></i> Add', ['create'], ['class'=> 'btn btn-success btn-sm']) ?>
                    </p>
                    <table id="datatable" class="table table-bordered dt-responsive  nowrap w-100">
                        <thead>
                            <tr>
                                <th>SL#</th>
                                <th>Section Name</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($dataProvider as $key => $value) {?>
                            <tr>
                                <td><?=++$key?></td>
                                <td><?=$value->section_name?></td>
                                <td><?=Yii::$app->helpers->getStatus($value->is_active)?></td>
                                <td>
                                    <?php
                                        echo Html::a('<i class="bx bx-edit"></i> Edit',['update', 'id'=>$value->section_id],['class'=>'btn btn-primary btn-sm']);
                                        echo "&nbsp;";
                                        echo Html::a('<i class="bx bx-trash"></i> Delete',['delete','id'=>$value->section_id],['class'=>'btn btn-danger btn-sm','data' => [
                                            'confirm' => 'Are you sure you want to delete this item?',
                                            'method'=> 'POST'
                                        ],]);                                    
                                    ?>

                                </td>
                            </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <!-- end row -->
</div>

<script type="text/javascript">
<?php JSRegister::begin(); ?>
$("#datatable").DataTable({
    language: {
        search: "_INPUT_",
        searchPlaceholder: "Search ..."
    },
    'columnDefs': [{
        'targets': [3],
        /* column index */
        'orderable': false,
        /* true or false */
    }]
});
<?php JSRegister::end(); ?>
</script>