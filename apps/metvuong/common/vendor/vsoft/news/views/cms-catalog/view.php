<?php

use vsoft\news\Module;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\CmsCatalog */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => Module::t('cms', 'Cms Catalogs'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="cms-catalog-view">

    <p>
        <?= Html::a(Module::t('cms', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Module::t('cms', 'Delete'), ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Module::t('cms', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ]) ?>
        <?= Html::a(Yii::t('app', 'Back'), 'index', ['class' => 'btn btn-success pull-right']) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            [
                'attribute' => 'parent_id',
                'value' => $model->parent_id ? $model->parent->title : Module::t('cms', 'News'),
            ],
            'title',
//            'surname',
//            'brief',
            'content:ntext',
            'seo_title',
            'seo_keywords',
            'seo_description',
//            'banner',
            [
                'attribute' => 'banner',
                'format' => 'html',
                'value' => Html::img( Yii::$app->request->getHostInfo() . '/store/news/catalog/' . $model->banner,['width' => 200, 'alt' => $model->banner]),
            ],
            [
                'attribute' => 'is_nav',
                'value' => \vsoft\news\models\YesNo::labels($model->is_nav),
            ],
            'sort_order',
            [
                'attribute' => 'page_type',
                'value' => \vsoft\news\models\CmsCatalog::getCatalogPageTypeLabels($model->page_type),
            ],
//            'page_type',
            'page_size',
//            'template_list',
//            'template_show',
//            'template_page',
//            'redirect_url:url',
//            [
//                'attribute' => 'status',
//                'value' => \vsoft\news\models\Status::labels($model->status),
//            ],
//            'created_at:datetime',
            'updated_at:datetime',
        ],
    ]) ?>

</div>
