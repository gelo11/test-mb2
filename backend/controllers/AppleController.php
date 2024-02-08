<?php

namespace backend\controllers;

use app\models\Apple;
use app\models\AppleForm;
use app\models\AppleSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use Yii;

/**
 * AppleController implements the CRUD actions for Apple model.
 */
class AppleController extends Controller
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
     * Lists all Apple models.
     *
     * @return string
     */
    public function actionIndex()
    {
        Apple::refreshState(); // TODO: Move to cron

        $searchModel = new AppleSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);
        $form = new AppleForm();

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'form' => $form
        ]);
    }

    /**
     * Displays a single Apple model.
     * @param int $id ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Apple model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new Apple();

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    public function actionCreateMulti()
    {
        $model = new Apple();

        $form = new AppleForm();

        if ($this->request->isPost) {
            if ($form->load($this->request->post()) && $form->validate()) {
                $rows = [];
                for ($i = 0; $i < $form->qty; $i++) {
                    $rows[] = [
                        'color' => $model->getRandomColor(),
                    ];
                }
                $affected_rows = Yii::$app->db->createCommand()->batchInsert(Apple::tableName(), ['color'], $rows)->execute();
                Yii::$app->session->setFlash('success', 'Яблок добавлено: ' . $affected_rows);
                return $this->redirect(['index']);
            }
        }

        return $this->redirect(['index']);
    }

    /**
     * Updates an existing Apple model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    public function actionFall($id)
    {
        $model = $this->findModel($id);

        $model->state = Apple::FALL_STATE;
        $model->save();
        Yii::$app->session->setFlash('success', 'Яблок упало');
        return $this->redirect(['index']);
    }

    public function actionEat($id)
    {
        $model = $this->findModel($id);

        if ($model->state != Apple::FALL_STATE) {
            $word = ($model->state == Apple::HANG_STATE ? 'еще' : 'больше');
            Yii::$app->session->setFlash('success', 'Яблоко ' . $word . ' нельзя откусить');
            return $this->redirect(['index']);
        }

        if ($this->request->isPost && $prs = $this->request->post('prs')) {
            $prs = floatval($prs);
            if (($model->prs - $prs) >= 0) {
                $model->prs = $model->prs - $prs;
                $model->save();
                Yii::$app->session->setFlash('success', 'Яблок откусили');
            } else {
                Yii::$app->session->setFlash('success', 'Яблок больше нельзя откусить');
            }
        }

        return $this->redirect(['index']);
    }

    /**
     * Deletes an existing Apple model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Apple model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Apple the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Apple::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
