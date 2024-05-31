<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
// use yii\grid\GridView;
use kartik\grid\GridView;
use richardfan\widget\JSRegister;

/* @var $this yii\web\View */
/* @var $searchModel app\models\RolesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Roles';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="roles-index">
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
                                <th>Name</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($dataProvider as $key => $value) {?>
                            <tr>
                                <td><?=++$key?></td>
                                <td><?=$value->display_name?></td>
                                <td><?=Yii::$app->helpers->getStatus($value->is_active)?></td>
                                <td>
                                    <!-- <a href="" class="btn btn-primary btn-sm">Edit</a> -->

                                    <?php
                                    if($value->role_id != Yii::$app->user->identity->role_id){
                                        echo Html::a('<i class="bx bx-edit"></i> Edit',['update', 'id'=>$value->role_id],['class'=>'btn btn-primary btn-sm']);
                                        echo "&nbsp;";
                                        echo Html::a('<i class="bx bx-trash"></i> Delete',['delete','id'=>$value->role_id],['class'=>'btn btn-danger btn-sm','data' => [
                                            'confirm' => 'Are you sure you want to delete this item?',
                                            'method'=> 'POST'
                                        ],]);
                                    }
                                    
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