<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use kartik\grid\GridView;
use richardfan\widget\JSRegister;

/* @var $this yii\web\View */
/* @var $searchModel app\models\UsersSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Users';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="users-index">

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

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
                                <th>Username</th>
                                <th>Name</th>
                                <th>Role</th>
                                <th>Email</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
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

    processing: true,
    serverSide: true,
    ajax: {
        url: "<?=URL::to(['datatable'])?>",
        type: "POST"
    },
    language: {
        search: "_INPUT_",
        searchPlaceholder: "Search ..."
    },
    columns: [
            { "data": "username"},
            { "data": "full_name"},
            { "data": "role"},
            { "data": "email"},
            { "data": "action", "orderable" : false, "searchable": false }
        ]
});
<?php JSRegister::end(); ?>
</script>