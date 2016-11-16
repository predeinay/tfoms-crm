<?php
namespace backend\controllers;

use Yii;
use yii\data\ActiveDataProvider;

use backend\controllers\MainController;
use backend\models\refUser;
use backend\models\refCommonSearch;
use backend\models\refReasonSearch;

use common\models\refReason;
use common\models\refCommon;
use common\models\refCompany;

use backend\models\UploadForm;
use yii\web\UploadedFile;

class SettingsController extends MainController
{

    public function actionIndex()
    {
        
        $userModel = refUser::find()
                       ->select('user_id,login,user_name,company_name')
                       ->leftJoin('ref_company', 'ref_company.company_id = ref_users.company_id');

        $provider = new ActiveDataProvider([
                        'query' => $userModel,
                        'pagination' => [
                                'pageSize' => parent::PAGINATION_SIZE,
                        ],
                    ]);

        
        $provider->sort->attributes['company_name'] = [
                'asc' => ['company_name' => SORT_ASC],
                'desc' => ['company_name' => SORT_DESC],
            ];
        
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
        
        $companyModel = refCompany::find();
        
        return $this->render('form_RefUser',
                             ['model' => $model,
                              'companyModel' => $companyModel->all(),
                              'action' => is_null($id) ? 'create' : 'edit']
                            );
    }
    
    public function actionUserCreate() {
            
        $model = new refUser();
        
        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->save() ) {
            parent::flash(true);
        } else {
            parent::flash(false);
        }
        
        return $this->redirect(['/settings/index']);
        
    }
    
    public function actionUserUpdate($id) {
        
        $model = refUser::findOne($id);
        
        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->save() ) {
            parent::flash(true);
        } else {
            parent::flash(false);
        }
        
        return $this->redirect(['/settings/index']);
        
    }
    
    public function actionUserDelete($id) {
        
        $model = refUser::findOne($id);
        
        if (!$model->delete()) {
            parent::flash(true);
        } else {
            parent::flash(false);
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
            parent::flash(true);
        } else {
            parent::flash(false);
        }
        
        return $this->redirect(['/settings/reasons']);
        
    }
    
    public function actionReasonUpdate($id) {
        
        $model = refReason::findOne($id);
        
        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->save() ) {
            parent::flash(true);
        } else {
            parent::flash(false);
        }
        
        return $this->redirect(['/settings/reasons']);
        
    }
    
    public function actionReasonDelete($id) {
        
        $model = refReason::findOne($id);
        
        if (!$model->delete()) {
            parent::flash(true);
        } else {
            parent::flash(false);
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
            parent::flash(true);
        } else {
            parent::flash(false);
        }
        
        return $this->redirect(['/settings/commons']);
        
    }
    
    public function actionCommonUpdate($id) {
        
        $model = refCommon::findOne($id);
        
        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->save() ) {
            parent::flash(true);
        } else {
            parent::flash(false);
        }
        
        return $this->redirect(['/settings/commons']);
        
    }
    
    public function actionCommonDelete($id) {
        
        $model = refCommon::findOne($id);
        
        if (!$model->delete()) {
            parent::flash(true);
        } else {
            parent::flash(false);
        }
        
        return $this->redirect(['/settings/commons']);
    }

    public function actionCompany() {
        
        $model = refCompany::find()->with('ref_common');
        
        $uploadForm = new UploadForm();
        
        if (Yii::$app->request->isPost) {
            $uploadForm->file = UploadedFile::getInstance($uploadForm, 'file');
            if ($uploadForm->upload() && $uploadForm->parseMedOrgXml()) {
                parent::flash(true);
            } else {
                parent::flash(false);
            }
        }
        
        $provider = new ActiveDataProvider([
                        'query' => $model,
                        'pagination' => [
                            'pageSize' => parent::PAGINATION_SIZE,
                        ],
                    ]);
        
        return $this->render('list_RefCompany',[
            'provider' => $provider,
            'uploadModel' => $uploadForm
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
            parent::flash(true);
        } else {
            parent::flash(false);
        }
        
        return $this->redirect(['/settings/company']);
        
    }
    
    public function actionCompanyUpdate($id) {
        
        $model = refCompany::findOne($id);
        
        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->save() ) {
            parent::flash(true);
        } else {
            parent::flash(false);
        }
        
        return $this->redirect(['/settings/company']);
        
    }
    
    public function actionCompanyDelete($id) {
        
        $model = refCompany::findOne($id);
        
        if (!$model->delete()) {
            parent::flash(true);
        } else {
            parent::flash(false);
        }
        
        return $this->redirect(['/settings/company']);
    }    
    
}
