<?php

namespace app\controllers;
use Yii;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
class DashboardController extends \yii\web\Controller
{
    public $layout = 'dashboard';

    /**
     * @inheritDoc
     */
    /* public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    } */
    public function actionIndex()
    {
        //echo "<pre>"; print_r(Yii::$app->user->id);
        return $this->render('index');
    }

    public function actionView()
    {
        //echo "<pre>"; print_r(Yii::$app->user->id);
        return $this->render('index');
    }

}
