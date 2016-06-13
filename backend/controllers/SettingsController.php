<?php
namespace backend\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use backend\models\refUser;
use yii\data\ActiveDataProvider;
use yii\filters\VerbFilter;

use common\models\refReason;
use common\models\refCommon;
use common\models\refCompany;
use backend\models\refCommonSearch;
use backend\models\refReasonSearch;

/**
 * Site controller
 */

class SettingsController extends Controller
{
    
    public $flashText = 'Действие выполнено';
    public $flahErr = 'Действие не выполнено';
    public $defaultPageSize = 50;
    
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
                        'actions' => ['logout', 
                                      'index','reasons','commons','company',
                                      'user-form','user-create','user-update','user-delete',
                                      'reason-form','reason-create','reason-update','reason-delete',
                                      'common-form','common-create','common-update','common-delete',
                                      'company-form','company-create','company-update','company-delete',],
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

    public function actionIndex()
    {
        $userModel = refUser::find();
        
        $provider = new ActiveDataProvider([
                        'query' => $userModel,
                        'pagination' => [
                            'pageSize' => $this->defaultPageSize,
                        ],
                    ]);

        
        return $this->render('list_RefUsers',[
            'provider' => $provider
        ]);

    }
    
    public function actionUserForm( $id = null ) {
        
        if (!is_null($id)) {
            $model = refUser::findOne($id);
        } else {
            $model = new refUser();
        }
        
        return $this->render('form_RefUser',
                             ['model' => $model,
                              'action' => is_null($id) ? 'create' : 'edit']
                            );
    }
    
    public function actionUserCreate() {
            
        $model = new refUser();
        
        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->save() ) {
            Yii::$app->session->setFlash('success',$this->flashText);
        } else {
            Yii::$app->session->setFlash('error', $this->flashErr);
        }
        
        return $this->redirect(['/settings/index']);
        
    }
    
    public function actionUserUpdate($id) {
        
        $model = refUser::findOne($id);
        
        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->save() ) {
            Yii::$app->session->setFlash('success', $this->flashText);
        } else {
            Yii::$app->session->setFlash('error', $this->flashErr);
        }
        
        return $this->redirect(['/settings/index']);
        
    }
    
    public function actionUserDelete($id) {
        
        $model = refUser::findOne($id);
        
        if (!$model->delete()) {
            Yii::$app->session->setFlash('error', $this->flashErr);
        } else {
            Yii::$app->session->setFlash('success', $this->flashText);
        }
        
        return $this->redirect(['/settings/index']);
    }
    
    public function actionReasons() {
        
        $searchModel = new refReasonSearch();
        $provider = $searchModel->search(Yii::$app->request->get());
        
        return $this->render('list_RefReasons',[
            'provider' => $provider,
            'filterModel' => $searchModel,
            'kindArrLov' => refCommon::getRefByName('Вид обращения'),
        ]);
        
    }
    
    public function actionReasonForm( $id = null ) {
        
        if (!is_null($id)) {
            $model = refReason::findOne($id);
        } else {
            $model = new refReason();
        }
        
        
        
        return $this->render('form_RefReason',
                             ['model' => $model,
                              'modelKind' => refCommon::getRefByName('Вид обращения'),
                              'action' => is_null($id) ? 'create' : 'edit']
                            );
    }
    
    public function actionReasonCreate() {
            
        $model = new refReason();
        
        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->save() ) {
            Yii::$app->session->setFlash('success',$this->flashText);
        } else {
            Yii::$app->session->setFlash('error', $this->flashErr);
        }
        
        return $this->redirect(['/settings/reasons']);
        
    }
    
    public function actionReasonUpdate($id) {
        
        $model = refReason::findOne($id);
        
        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->save() ) {
            Yii::$app->session->setFlash('success', $this->flashText);
        } else {
            Yii::$app->session->setFlash('error', $this->flashErr);
        }
        
        return $this->redirect(['/settings/reasons']);
        
    }
    
    public function actionReasonDelete($id) {
        
        $model = refReason::findOne($id);
        
        if (!$model->delete()) {
            Yii::$app->session->setFlash('error', $this->flashErr);
        } else {
            Yii::$app->session->setFlash('success', $this->flashText);
        }
        
        return $this->redirect(['/settings/reasons']);
    }
    
    public function actionCommons() {
        
        $searchModel = new refCommonSearch();
        
        $provider = $searchModel->search(Yii::$app->request->get());
        
        return $this->render('list_RefCommon',[
            'provider' => $provider,
            'searchModel' => $searchModel,
            'typeArr' => refCommon::getCommonTypesArr(),
        ]);
    }
    
    public function actionCommonForm( $id = null ) {
        
        if (!is_null($id)) {
            $model = refCommon::findOne($id);
        } else {
            $model = new refCommon();
        }
        
        return $this->render('form_RefCommon',
                             ['model' => $model,
                              'typeArr' => refCommon::getCommonTypesArr(),
                              'action' => is_null($id) ? 'create' : 'edit']
                            );
    }
    
    public function actionCommonCreate() {
            
        $model = new refCommon();
        
        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->save() ) {
            Yii::$app->session->setFlash('success',$this->flashText);
        } else {
            Yii::$app->session->setFlash('error', $this->flashErr);
        }
        
        return $this->redirect(['/settings/commons']);
        
    }
    
    public function actionCommonUpdate($id) {
        
        $model = refCommon::findOne($id);
        
        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->save() ) {
            Yii::$app->session->setFlash('success', $this->flashText);
        } else {
            Yii::$app->session->setFlash('error', $this->flashErr);
        }
        
        return $this->redirect(['/settings/commons']);
        
    }
    
    public function actionCommonDelete($id) {
        
        $model = refCommon::findOne($id);
        
        if (!$model->delete()) {
            Yii::$app->session->setFlash('error', $this->flashErr);
        } else {
            Yii::$app->session->setFlash('success', $this->flashText);
        }
        
        return $this->redirect(['/settings/commons']);
    }

    public function actionCompany() {
        
        $model = refCompany::find()->with('ref_common');
        
        $provider = new ActiveDataProvider([
                        'query' => $model,
                        'pagination' => [
                            'pageSize' => $this->defaultPageSize,
                        ],
                    ]);
        
        return $this->render('list_RefCompany',[
            'provider' => $provider
        ]);
    }
    
    public function actionCompanyForm( $id = null ) {
        
        if (!is_null($id)) {
            $model = refCompany::findOne($id);
        } else {
            $model = new refCompany();
        }
        
        $modelType = refCommon::getRefByName('Тип организации');
                
        return $this->render('form_RefCompany',
                             ['model' => $model,
                              'modelType' => $modelType,
                              'action' => is_null($id) ? 'create' : 'edit']
                            );
    }
    
    public function actionCompanyCreate() {
            
        $model = new refCompany();
        
        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->save() ) {
            Yii::$app->session->setFlash('success',$this->flashText);
        } else {
            Yii::$app->session->setFlash('error', $this->flashErr);
        }
        
        return $this->redirect(['/settings/company']);
        
    }
    
    public function actionCompanyUpdate($id) {
        
        $model = refCompany::findOne($id);
        
        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->save() ) {
            Yii::$app->session->setFlash('success', $this->flashText);
        } else {
            Yii::$app->session->setFlash('error', $this->flashErr);
        }
        
        return $this->redirect(['/settings/company']);
        
    }
    
    public function actionCompanyDelete($id) {
        
        $model = refCompany::findOne($id);
        
        if (!$model->delete()) {
            Yii::$app->session->setFlash('error', $this->flashErr);
        } else {
            Yii::$app->session->setFlash('success', $this->flashText);
        }
        
        return $this->redirect(['/settings/company']);
    }    
    
}
