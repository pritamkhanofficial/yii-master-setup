<?php

namespace app\controllers;
use Yii;
use app\models\Users;
use app\models\UsersSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\Html;
use yii\web\UploadedFile;
use yii\helpers\Url;
/**
 * UsersController implements the CRUD actions for Users model.
 */
class UsersController extends Controller
{
    /**
     * @inheritDoc
     */
    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            [
                'verbs' => [
                    'class' => VerbFilter::className(),
                    'actions' => [
                        'delete' => ['POST'],
                    ],
                ],
            ]
        );
    }

    /**
     * Show The index Page
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new UsersSearch();

        return $this->render('index', [
            'searchModel' => $searchModel,
        ]);
    }


    /**
     * List All Users From Ajax Datatable
     * 
     */
    public function actionDatatable()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $model = new Users();
        $limit = $this->request->post('length');
        $offset = $this->request->post('start');
        $search_string = $_POST['search']['value'];
        // $order_by = $_POST['columns'][$_POST['order'][0]['column']]['data'];


        $users = $model->datatable($limit, $offset, $search_string);
        $data = [];
        $data['data'] = [];
        $data['draw'] = (int)$_POST['draw'];
        $data['recordsTotal'] = $users['recordsTotal'];
        $data['recordsFiltered'] = $users['recordsFiltered'];
        // print_r($users['data']); die;
        if (!empty($users['data'])) {
            foreach ($users['data'] as $user) {
                $action = '';
                if($user['id'] != Yii::$app->user->id){
                    $action .=  Html::a('<i class="bx bx-edit"></i> Edit',['update', 'id'=>$user['id']],['class'=>'btn btn-primary btn-sm']);
                    $action .= "&nbsp;";
                    $action .= Html::a('<i class="bx bx-trash"></i> Delete',['delete','id'=>$user['id']],['class'=>'btn btn-danger btn-sm','data' => [
                        'confirm' => 'Are you sure you want to delete this item?',
                        'method'=> 'POST'
                    ],]);
                }
                $user['action'] = $action;
                $data['data'][] = $user;
            }
        }
        return $data;
        /* return $this->render('index', [
            'searchModel' => $searchModel,
        ]); */
    }
     

    /**
     * Displays a single Users model.
     * @param string $id User ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Users model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new Users();

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Users model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id User ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Users model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id User ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Users model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id User ID
     * @return Users the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Users::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    /**
     * Update Profile.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionUpdateprofile()
    {
        $model = new Users();
        $id = Yii::$app->user->id;
        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                return $this->redirect(['index']);
            }
        }

        return $this->render('update', [
            'model' => $this->findModel($id),
        ]);
    }

    public function actionProfile()
    {
        $id = Yii::$app->user->id;
        //die();
        $model = $this->findModel($id);
        $oldImage = $model->profile_pic;
        if ($this->request->isPost) {
            if ($model->load($this->request->post())) {
                $image = UploadedFile::getInstance($model, 'profile_pic');
                if (isset($image)) {
                    $model->profile_pic = Yii::$app->helpers->generateToken(false) .'.'. $image->extension;
                } else {
                    $model->profile_pic = $oldImage;
                }
                if ($model->save()) {
                    if (isset($image)) {
                        $filePath = Url::to('@app/web/uploads/').$oldImage;
                        @unlink($filePath);
                        $image->saveAs('web/uploads/'.$model->profile_pic);
                    }
                    Yii::$app->helpers->generateFlash([
                        'type'=>'success',
                        'title'=>'Success',
                        'message'=>'Profile changed successfully.',
                    ]);
                    return $this->redirect(['profile']);
                }
            }
        }
        return $this->render('profile', [
            'model' => $model,
        ]);
    }

    public function actionChangepassword()
    {
        $id = Yii::$app->user->id;
        //die();
        $model = $this->findModel($id);
        $model->scenario = 'changepassword';
        if ($this->request->isPost) {
            $oldPassword = $model->password;
            if($model->load($this->request->post())){
                $model->password = Yii::$app->security->generatePasswordHash($model->confirmPassword);
                if($model->save()){
                    Yii::$app->helpers->generateFlash([
                        'type'=>'success',
                        'title'=>'Success',
                        'message'=>'Password changed successfully.',
                    ]);
                    return $this->redirect(['changepassword']);
                }
            }
        }
        return $this->render('changepassword', [
            'model' => $model,
        ]); 
    }
}
