<?php

/** @var yii\web\View $this */

use app\views\assets\TendersBundle;
use yii\grid\GridView;
use yii\helpers\Html;

TendersBundle::register($this);

$this->title = 'Tenders';

?>
<div class="site-tenders">
    <h1><?= Html::encode($this->title) ?></h1>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'layout' => "{pager}{summary}\n{items}\n{pager}",
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'tenderId',
             [
                'attribute' => 'description',
                'format' => function ($value) {
                    return strlen($value) > 255 ? substr($value, 0, 255) . '...' : $value;
                }
            ],
            [
                'attribute' => 'amount',
                'format' => ['decimal', 2],
                'contentOptions' => ['class' => 'tender-amount'],
                'headerOptions' => ['class' => 'tender-amount']
            ],
            'dateModified'
        ]
    ]);
    ?>

</div>
