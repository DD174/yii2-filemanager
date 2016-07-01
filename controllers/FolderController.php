<?php

namespace pendalf89\filemanager\controllers;

use pendalf89\filemanager\models\Folder;
use Yii;
use yii\helpers\Html;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\filters\VerbFilter;
use pendalf89\filemanager\Module;

class FolderController extends Controller
{
    public $enableCsrfValidation = false;

    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    public function beforeAction($action)
    {
        if (defined('YII_DEBUG') && YII_DEBUG) {
            Yii::$app->assetManager->forceCopy = true;
        }

        return parent::beforeAction($action);
    }

    public function actionIndex($selected_id = null)
    {

        return $this->renderPartial('index', ['items' => Folder::getMenu($selected_id)]);
    }

    /**
     * @return array
     */
    public function actionCreate()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $model = new Folder();
        if ($model->load(Yii::$app->request->post())) {
            if ($model->save()) {
                $this->redirect(['index', 'selected_id' => $model->id]);
            } else {
                return ['errors' => $model->getErrors()];
            }
        }
    }

    /**
     * Updated folder by id
     * @param $id
     * @return array
     */
    public function actionUpdate($id)
    {
        /** @var Folder $model */
        $model = Folder::findOne($id);
        $message = Module::t('main', 'Changes not saved.');

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $message = Module::t('main', 'Changes saved!');
        }

        Yii::$app->session->setFlash('mediafileUpdateResult', $message);

        Yii::$app->assetManager->bundles = false;

        return $this->renderAjax('update', [
            'model' => $model,
        ]);
    }

    /**
     * Delete model with files
     * @return array
     * @throws NotFoundHttpException
     * @throws \Exception
     */
    public function actionDelete()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        /** @var Folder $model */
        if (!$model = Folder::findOne(['id' => Yii::$app->request->post('id')])) {
            throw new NotFoundHttpException('Folder not exist.');
        }

        if ($model->delete()) {
            return ['success' => 'true'];
        } else {
            return ['success' => false, 'message' => Html::errorSummary($model)];
        }
    }
}
