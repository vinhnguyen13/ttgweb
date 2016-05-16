<?php 
	use frontend\models\Tracking;
	use vsoft\ad\models\AdImages;
	use vsoft\express\components\StringHelper;
	use vsoft\ad\models\AdCategory;
	use yii\helpers\Url;
	use vsoft\ad\models\AdProduct;
	use yii\web\View;
	use yii\helpers\Html;
	use vsoft\ad\models\AdWard;
	use yii\helpers\ArrayHelper;
	use vsoft\ad\models\AdStreet;
	use yii\widgets\LinkPager;
	use common\models\AdCity;
	use vsoft\ad\models\AdDistrict;
	use vsoft\ad\models\AdBuildingProject;

$categoriesDb = \vsoft\ad\models\AdCategory::getDb();
$categories = $categoriesDb->cache(function($categoriesDb){
    return \vsoft\ad\models\AdCategory::find()->indexBy('id')->asArray(true)->all();
});
$types = AdProduct::getAdTypes();
?>
<?php foreach ($products as $product): ?>
<li class="col-xs-12 col-sm-6 col-lg-4">
	<div class="item">
		<a data-id="<?= $product->id ?>" class="clearfix" href="<?= $product->urlDetail(); ?>" title="<?= $product->getAddress($product->show_home_no) ?>">
			<div class="pic-intro">
				<img src="<?= $product->file_name ? AdImages::getImageUrl($product->folder, $product->file_name, AdImages::SIZE_THUMB) : AdImages::defaultImage() ?>" />
			</div>
			<div class="info-item clearfix">
				<p class="date-post"><?= Yii::t('statistic', 'Date of posting') ?>:
					<strong><?= date("d/m/Y H:i", $product->created_at) ?></strong></p>
				<div class="address-listing">
					<?= $product->getAddress($product->show_home_no) ?>
				</div>
				<p class="infor-by-up">
					<strong><?= ucfirst(Yii::t('ad', $categories[$product->category_id]['name'])) ?> <?= strtolower($types[$product->type]) ?></strong>
				</p>
				<p class="id-duan"><?= Yii::t('ad', 'ID') ?>:<span><?= Yii::$app->params['listing_prefix_id'] . $product->id;?></span></p>
				<ul class="clearfix list-attr-td">
                    <?php if(empty($product->area) && empty($product->adProductAdditionInfo->room_no) && empty($product->adProductAdditionInfo->toilet_no)){ ?>
                        <li><?=Yii::t('listing','updating')?></li>
                    <?php } else {
                        echo $product->area ? '<li> <span class="icon-mv"><span class="icon-page-1-copy"></span></span>' . $product->area . 'm2 </li>' : '';
                        echo $product->adProductAdditionInfo->room_no ? '<li><span class="icon-mv"><span class="icon-bed-search"></span></span>' . $product->adProductAdditionInfo->room_no . ' </li>' : '';
                        echo $product->adProductAdditionInfo->toilet_no ? '<li> <span class="icon-mv"><span class="icon-bathroom-search-copy-2"></span></span>' . $product->adProductAdditionInfo->toilet_no . ' </li>' : '';
                    } ?>
				</ul>
		        <p class="price-item"><?= Yii::t('listing', 'Price') ?><strong><?= StringHelper::formatCurrency($product->price) ?></strong></p>   
		    </div>
		</a>
        <?php
        // tracking finder
        if($product->user_id != Yii::$app->user->id && isset(Yii::$app->params['tracking']['all']) && Yii::$app->params['tracking']['all'] == true) {
            Tracking::find()->productFinder(Yii::$app->user->id, (int)$product->id, time());
        }
        ?>
	</div>
</li>
<?php endforeach; ?>