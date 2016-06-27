<?php
namespace frontend\controllers;

use Yii;

use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\refCompany;
use frontend\models\Call;
use yii\db\Expression;
use common\models\Requests;
use yii\data\ActiveDataProvider;
use common\models\refCommon;
use common\models\refReason;
use frontend\models\CallBind;
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
                'only' => ['logout', 'signup'],
                'rules' => [
                    [
                        'actions' => ['signup'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        'actions' => ['logout'],
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
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return mixed
     */
    public function actionIndex()
    {
        $s = new \yii\web\Session();
        
        $searchModel = new \frontend\models\RequestsSearch;
        $provider = $searchModel->search(Yii::$app->request->get());
        
        //       ->where(['phone_aoh' => $s['phone_aoh'] ]);
        
        return $this->render(
                            'index',
                            [ 'provider' => $provider,
                             'searchModel' => $searchModel ]
                );
        
    }

    public function actionRegisterCall( $uid , $aoh ,$oid) {
        
        // here register call
        
        $call = new Call();
        
        $call->call_uid = $uid;
        $call->phone_aoh = $aoh;
        $call->created_on = new Expression('NOW()');
        
        if ( $call->validate() ) {

            $call->save();
            
            // here set session var
            $s = new \yii\web\Session;
            
            $s['user_id'] = $oid;
            
            $call->setInSession();
            
        }
                
        return $this->goHome();
        //return $this->render('index');
    }
    
    public function actionForm($id = null) {
        
        if (!is_null($id)) {
            $model = Requests::findOne($id);
        } else {
            $model = new Requests();
            
            // default data for model
            $model->created_on = Yii::$app->db->createCommand('select NOW() as sdate from dual')->queryOne()['sdate'];
            
            $s =new \yii\web\Session;
            $model->phone_aoh = $s['phone_aoh'];
            $defaultStatus = refCommon::findOne( ['type' => 'Статус обращения', 
                                                  'text' => 'в работе'] );
            if ($defaultStatus) { $model->status_ref_id = $defaultStatus->ref_id; }
            
            $defaultForm = refCommon::findOne( ['type' => 'Форма обращения',
                                                'text' => 'устно'] );
            if ($defaultForm) { $model->form_ref_id = $defaultForm->ref_id; }
            
            $defaultWay = refCommon::findOne( ['type' => 'Путь поступления',
                                                'text' => 'По телефону горячей линии'] );
            if ($defaultWay) { $model->way_ref_id = $defaultWay->ref_id; }

        }

        //return $this->render('formReqContainer');
        
        return $this->render('form_Req',
                             ['model' => $model,
                              'modelForm' => refCommon::getRefByName('Форма обращения'),
                              'modelWay' => refCommon::getRefByName('Путь поступления'),
                              'modelKind' => refCommon::getRefByName('Вид обращения'),
                              'modelStatus' => refCommon::getRefByName('Статус обращения'),
                              'modelResult' => refCommon::getRefResult($model->kind_ref_id)->all(),
                              'modelReason' => refReason::findAll(['kind_ref_id' => $model->kind_ref_id]),
                              'modelCompany' => refCompany::find()->all(),
                              'action' => is_null($id) ? 'create' : 'edit']);
        
    }

    public function actionCreate() {
        
        $model = new Requests();
        
        $s =new \yii\web\Session;
        
        $model->created_by = $s['user_id'];
        $model->created_on = new Expression('NOW()');
        $model->phone_aoh = $s['phone_aoh'];
        
        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->save() ) {
            
            $cb = new CallBind();
            $cb->call_id = $s['call_id'];
            $cb->req_id = $model->req_id;
            $cb->save();
            
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
 
    public function actionIsCustomReason() {
        
        if (Yii::$app->request->post()['reason_id']) {
            
            $reason = refReason::findOne(Yii::$app->request->post());
            return \yii\helpers\Json::encode(
                    ['custom_reason_flag' => $reason->custom_text_flag]
                );
        }
        
        return \yii\helpers\Json::encode(
                    ['custom_reason_flag' => 0]
                );
        
    }
    
}
