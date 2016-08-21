<?php

namespace backend\controllers;

use backend\controllers\MainController;

use Yii;

use yii\db\Expression;

use common\models\refCommon;
use common\models\refReason;
use common\models\Requests;
use common\models\reqComment;
use common\models\refCompany;
use common\models\refUser;
use yii\data\ActiveDataProvider;

use backend\models\requestSearch;

class RequestController extends MainController {

    // Список обращений
    public function actionList() {

        $reqSearchModel = new requestSearch();
        // pass get arr for filtering
        $provider = $reqSearchModel->search( Yii::$app->request->get() );

        return $this->render(
                    'list_view_Reqs',
                    ['provider' => $provider,
                     'searchModel' => $reqSearchModel,
                     'modelCompany' => refCompany::find()->all(),
                     'modelStatus' => refCommon::getRefByName('Статус обращения'),
                     'modelForm' => refCommon::getRefByName('Форма обращения'),
                     'modelWay' => refCommon::getRefByName('Путь поступления'),
                     'modelKind' => refCommon::getRefByName('Вид обращения'),
                     'modelUser' => refUser::getAll(),
                     'modelReason' => refReason::findAll(['kind_ref_id' => $reqSearchModel->kind_ref_id]),
                    ]
                );
    }

    // Форма для обращений
    public function actionForm($id = null) {

        if (!is_null($id)) {
            $model = Requests::findOne($id);
        } else {
            $model = new Requests();
            $model->created_on = Yii::$app->db->createCommand('select NOW() as sdate from dual')->queryOne()['sdate'];
            $model->company_id = Yii::$app->user->identity->company_id;

            // Get default status for new request
            $defaultStatus = refCommon::findOne(
                                ['type' => 'Статус обращения',
                                 'text' => 'в работе']
                             );
            if ($defaultStatus) {
                $model->status_ref_id = $defaultStatus->ref_id;
            }
        }

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

    // Комментарии
    public function actionComments($id = null) {

        $commentModels = reqComment::findAll(['request_id' => $id]);

        return $this->render('form_Comments',
                            ['req_id' => $id,
                             'commentModels' => $commentModels ]);

    }

    // Записи разговоров
    public function actionRecords($id = null) {

        return $this->render('form_Records',
                            ['req_id' => $id ]);

    }

    public function actionCreate() {

        $model = new Requests();

        $model->created_by = Yii::$app->user->getId();
        $model->created_on = new Expression('NOW()');

        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->save() ) {
            parent::flash(true);
        } else {
            parent::flash(false);
        }

        return $this->redirect(['/request/list']);

    }

    public function actionDelete($id) {

        parent::flash( Requests::findOne($id)->delete() );

        return $this->redirect(['/request/list']);
    }

    public function actionUpdate($id) {

        $model = Requests::findOne($id);

        if ( $model->load(Yii::$app->request->post()) ) {

        if ( $model->validate() && $model->save() ) {
                parent::flash(true);
            } else {
                parent::flash(false);
            }
        }

        return $this->redirect(['/request/list']);

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
