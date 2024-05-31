<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\Users;
use app\models\ContactForm;
use yii\helpers\Url;

class SiteController extends Controller
{
    public $layout = 'login';

    public function beforeAction( $action ) {
        if ( parent::beforeAction ( $action ) ) {
            if ( $action->id == 'error' ) {
                Yii::$app->helpers->logData();
            }
            return true;
        } 
    }
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'logout' => ['get'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        //echo Yii::$app->helpers->dbDateFormat(); die();
        return $this->actionLogin();
    }

    /**
     * Login action.
     * @author Pritam <pritamkhanofficial@gmail.com>
     * 
     * @return Response|string
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            $this->redirect(Url::to(['dashboard/index']));
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post())) {
             //die('login success');
            if($model->login()){
                if(Yii::$app->helpers->usersActivity($model->rememberMe)){
                    $this->redirect(Url::to(['dashboard/index']));
                }else{
                    die('login failure');
                }
            }
           
        }

        $model->password = '';
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout()
    {
        if(Yii::$app->helpers->usersActivity()){
            Yii::$app->user->logout();
            return $this->goBack();
            //$this->redirect(Url::to(['site/index']));
        }
    }

    /**
     * Displays contact page.
     *
     * @return Response|string
     */
    /* public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        }
        return $this->render('contact', [
            'model' => $model,
        ]);
    } */

    /**
     * Displays about page.
     *
     * @return string
     */
    public function actionAbout()
    {
        return $this->render('about');
    }

    public function actionForgotpassword()
    {
        if (!Yii::$app->user->isGuest) {
            $this->redirect(Url::to(['dashboard/index']));
        }

        $model = new Users();
        if (Yii::$app->request->post() && $model->load($this->request->post())) {
            $count = Users::find()
                ->where(['email' => $model->email])
                ->count();
                if ($count) {
                    $userData = Users::findOne(['email' => $model->email]);
                    $generate_token = Yii::$app->helpers->generateToken(false);
                    $generate_on = Yii::$app->helpers->dbDateFormat();

                    $userData->generate_token = $generate_token;
                    $userData->generate_on = Yii::$app->helpers->dbDateFormat();
                    if ($userData->save()) {
                        $userData = Users::findOne(['email' => $model->email]);
                        if (Yii::$app->helpers->sendMail($userData)){
                            $mailSend = true;
                            $this->redirect(Url::to(['site/mailsentsuccess']));
                        }else{
                            echo "Mail Not Send"; die;
                            $model->addError('email', '!Oops somthing went wrong. Mail not send.');
                        }
                    }
                }else{
                    $model->addError('email', 'User not found. Please try again anather email address.');
                }           
        }

        return $this->render('forgot-password', [
            'model' => $model
        ]);
    }

    public function actionMailsentsuccess()
    {
        return $this->render('confirm-mail-send',[]);
    }

    public function actionChangepassword($generate_token)
    {
        // echo $generate_token; die;
        if (!Yii::$app->user->isGuest) {
            $this->redirect(Url::to(['dashboard/index']));
        }
        $model = Users::findOne(['generate_token' => $generate_token]);
        if($model === null){
            $this->redirect(Url::to(['site/forgotpassword']));
        }

        $timeDeffrence = Yii::$app->helpers->timeDeffrenceCalculator($model->generate_on);
        if($timeDeffrence->h >= 1){
            $this->redirect(Url::to(['site/forgotpassword']));
        }

        if (Yii::$app->request->post() && $model->load($this->request->post())) {
            $model->password = Yii::$app->helpers->generateHash($model->confirmPassword);
            $model->updated_at = Yii::$app->helpers->dbDateFormat();
            if ($model->save()){
                $this->redirect(Url::to(['site/index']));
            }
        
        }

        return $this->render('change-password', [
            'model' => $model,
        ]);
    }

    /* public function actionError()
    {
        $exception = Yii::$app->errorHandler->exception;
        if ($exception !== null) {
            die($exception);
            return $this->render('error', ['exception' => $exception]);
        }
    } */
}
