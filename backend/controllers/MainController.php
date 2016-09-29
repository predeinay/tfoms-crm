<?php
namespace backend\controllers;

use Yii;

use yii\web\Controller;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;

use backend\models\LoginForm;

class MainController extends Controller
{
    
    const FLASH_OK = 'Действие выполнено';
    const FLASH_ERROR = 'Действие не выполнено';
    const PAGINATION_SIZE = 50;
    
    public function behaviors()
    { 
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['login', 'error'],
                        'allow' => true,
                    ],
                    [
                        'controllers' => ['main'],
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                    [
                        'controllers' => ['request'],
                        'actions' => ['form','list',
                                      'create','update','delete',
                                      // ajax actions
                                      'subreason','subresult','is-custom-reason',
                                      // add relation actions
                                      'comments','records',
                                      // report
                                       'print-journal' ],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                    
                    [
                        'controllers' => ['site'],
                        'actions' => ['logout', 'index',
                                      'form','create','update','delete',
                                      'main-form',
                                      'subreason','subresult',
                                      'is-custom-reason',],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                    [   
                        'controllers' => ['settings'],
                        'actions' => ['index','reasons','commons','company',
                                      'user-form','user-create','user-update','user-delete',
                                      'reason-form','reason-create','reason-update','reason-delete',
                                      'common-form','common-create','common-update','common-delete',
                                      'company-form','company-create','company-update','company-delete',],
                        'allow' => true,
                        'roles' => ['@'],
                        'matchCallback' => function() {
                            return Yii::$app->user->identity->isTfomsRole( Yii::$app->user->identity->id );
                        },
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }
    
    public function flash($boolType) {
        if ($boolType) {
            Yii::$app->session->setFlash('success', self::FLASH_OK);
        } else {
            Yii::$app->session->setFlash('error', self::FLASH_ERROR);
        }
    
    }
    
    public function actionLogin()
    {
        
        if (!\Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        } else {
            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }
    
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }
}
