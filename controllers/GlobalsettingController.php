<?php

namespace app\controllers;

use app\models\Globalsetting;
use app\models\GlobalsettingSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * GlobalsettingController implements the CRUD actions for Globalsetting model.
 */
class GlobalsettingController extends Controller
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
     * Lists all Globalsetting models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new GlobalsettingSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Globalsetting model.
     * @param string $global_settings_id Global Settings ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($global_settings_id)
    {
        return $this->render('view', [
            'model' => $this->findModel($global_settings_id),
        ]);
    }

    /**
     * Creates a new Globalsetting model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new Globalsetting();

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                return $this->redirect(['view', 'global_settings_id' => $model->global_settings_id]);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Globalsetting model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $global_settings_id Global Settings ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($global_settings_id)
    {
        $model = $this->findModel($global_settings_id);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['view', 'global_settings_id' => $model->global_settings_id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Globalsetting model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $global_settings_id Global Settings ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($global_settings_id)
    {
        $this->findModel($global_settings_id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Globalsetting model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $global_settings_id Global Settings ID
     * @return Globalsetting the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($global_settings_id)
    {
        if (($model = Globalsetting::findOne(['global_settings_id' => $global_settings_id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
