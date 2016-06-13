<?php
namespace backend\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use common\models\Requests;
use backend\models\LoginForm;
use yii\filters\VerbFilter;
use yii\data\ActiveDataProvider;

use common\models\refCommon;
use common\models\refReason;

use yii\db\Expression;
use yii\db\Query;
/**
 * Site controller
 */

class SiteController extends Controller
{
    
    const flashText = 'Действие выполнено';
    const flahErr = 'Действие не выполнено';
    
    /**
     * @inheritdoc
     */
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
                        'actions' => ['logout', 'index',
                                      'form','create','update','delete',
                                      'main-form',
                                      'subreason','subresult'],
                        'allow' => true,
                        'roles' => ['@'],
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

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    public function actionIndex() {
        
        $searchModel = new \backend\models\RequestsSearch;
        $provider = $searchModel->search(Yii::$app->request->get());
        
        return $this->render(
                    'listRequest',
                    ['provider' => $provider,
                     'searchModel' => $searchModel ]
                );
        
    }
    
    public function actionForm($id = null) {
        
        if (!is_null($id)) {
            $model = Requests::findOne($id);
        } else {
            $model = new Requests();
            $model->created_on = Yii::$app->db->createCommand('select NOW() as sdate from dual')->queryOne()['sdate'];
            $model->status_ref_id = refCommon::findOne(['type' => 'Статус обращения', 'text' => 'в работе'])->ref_id;
        }

        //return $this->render('formReqContainer');
        
        return $this->render('form_Req',
                             ['model' => $model,
                              'modelForm' => refCommon::getRefByName('Форма обращения'),
                              'modelWay' => refCommon::getRefByName('Путь поступления'),
                              'modelKind' => refCommon::getRefByName('Вид обращения'),
                              'modelStatus' => refCommon::getRefByName('Статус обращения'),
                              'modelResult' => refCommon::getRefResult($model->kind_ref_id)->all(),
                              //'modelReason' => refReason::getAll(),
                              'modelReason' => refReason::findAll(['kind_ref_id' => $model->kind_ref_id]),
                              'action' => is_null($id) ? 'create' : 'edit']);
        
    }

    // TODO: Dynamic Tab-X for form Request
    public function actionMainForm() {

       return \yii\helpers\Json::encode('$html');
        
    }
    
    public function actionCreate() {
        
        $model = new Requests();
        
        $model->created_by = Yii::$app->user->getId();
        $model->created_on = new Expression('NOW()');
        
        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->save() ) {
            Yii::$app->session->setFlash('success',self::flashText);
        } else {
            Yii::$app->session->setFlash('error', self::flahErr);
        }
        
        return $this->redirect(['/site/index']);
        
    }
    
    public function actionDelete($id) {
        
        $model = Requests::findOne($id);
        
        if (!$model->delete()) {
            Yii::$app->session->setFlash('error', self::flashErr);
        } else {
            Yii::$app->session->setFlash('success', self::flashText);
        }
        
        return $this->redirect(['/site/index']);
    }
    
    public function actionUpdate($id) {
        
        $model = Requests::findOne($id);
                
        if ( $model->load(Yii::$app->request->post()) ) {
            
        if ( $model->validate() && $model->save() ) {
                Yii::$app->session->setFlash('success', self::flashText);
            } else {
                Yii::$app->session->setFlash('error', self::flahErr);
            }
        }
        
        return $this->redirect(['/site/index']);

    }
    
    public function actionSubreason() {
        
        $kind_ref_id = Yii::$app->request->post()['depdrop_all_params']['kind_ref_id'];
        
        $reasonModel = refReason::find()->where(['kind_ref_id' => $kind_ref_id])->asArray()->all();
        
        $hmap = \yii\helpers\ArrayHelper::map($reasonModel,'reason_id','reason_text');
        $out = [];
        foreach ($hmap as $key => $value ) {
            $out[] = [ 'id' => $key, 'name' => $value ];
        }
        
        //var_dump($out);
        echo \yii\helpers\Json::encode(
            [
             'output' =>  $out,
             'selected' => ''
            ]);
        
    }
    
    
    public function actionSubresult() {
        
        $kind_ref_id = Yii::$app->request->post()['depdrop_all_params']['kind_ref_id'];

        $resModel = refCommon::getRefResult($kind_ref_id)->asArray()->all();
        
        $hmap = \yii\helpers\ArrayHelper::map($resModel,'ref_id','text');
        $out = [];
        foreach ($hmap as $key => $value ) {
            $out[] = [ 'id' => $key, 'name' => $value ];
        }
        
        echo \yii\helpers\Json::encode(
            [
             'output' =>  $out,
             'selected' => ''
            ]);
        
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
