<?php

use app\models\Apple;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var app\models\AppleSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */
/** @var app\models\AppleForm $form */

$this->title = 'Яблоки';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="apple-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_inline_form', [
        'model' => $form,
    ]) ?>
    <p>
        <?= Html::a('Добавить', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

//            'id',
            [
                'attribute' => 'color',
                'content' => function (Apple $data) {
                    return $data->getColorName();
                }
            ],
            [
                'attribute' => 'created',
                'format' => ['date', 'php:d.m.Y H:i:s']
            ],
            [
                'attribute' => 'updated',
                'format' => ['date', 'php:d.m.Y H:i:s']
            ],
            [
                'attribute' => 'state',
                'content' => function (Apple $data) {
                    return $data->getStateLabel();
                },
                'filter' => (new Apple())->getStateList()
            ],
            'prs',
            [
                'class' => ActionColumn::className(),
                'urlCreator' => function ($action, Apple $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id' => $model->id]);
                },
                'template' => '{eat} {fall} {view} {update} {delete}',
                'buttons' => [
                    'fall' => function ($url, $model, $key) {
                        return Html::a(' &dArr; ', ['apple/fall', 'id' => $model->id], ['title' => 'Упасть', 'class' => 'icon-link']);
                    },
                    'eat' => function ($url, $model, $key) {
                        return Html::beginForm(['/apple/eat', 'id' => $model->id], 'post', ['class' => 'd-flex'])
                            . Html::input('number', 'prs', '', ['step' => 0.01, 'min' => 0, 'max' => 1])
                            . Html::submitButton(
                                'Откусить',
                                ['class' => 'btn btn-link text-decoration-none']
                            )
                            . Html::endForm();
                    },
                ]
            ],
        ],
    ]); ?>


</div>
