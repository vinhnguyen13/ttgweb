<?php
use yii\helpers\StringHelper;
use yii\helpers\Url;
use frontend\models\Ad;
?>
<div class="page-home">

    <section class="search-box hFullScreen">
        <!-- <div class="text-center sologan">
            Đưa bạn đến ngôi nhà yêu thích<br>
            Tìm hiểu &amp; giao dịch bất động sản dễ dàng
        </div> -->
        <div id="hScreen"></div>
        <div class="search-wrap-home">
            <div class="text-center logo-search-box wrap-img">
                <img src="<?= Yii::$app->view->theme->baseUrl . '/resources/images/logo.png' ?>" alt="">
            </div>
            <div class="search-homepage">
                <ul class="clearfix">
                    <li><a href="<?= Url::to(['/ad/index']) ?>">Mua</a></li>
                    <li><a href="#">Thuê</a></li>
                    <li><a href="<?= Url::to(['/ad/post']) ?>">Bán / Cho Thuê</a></li>
                </ul>
                <form id="" action="">
                    <input autocomplete="off" data-url="<?= Url::to(['site/search']) ?>" id="search" type="text" placeholder="Tìm kiếm nhanh..." />
                    <button type="submit" id="btn-search"><span class="icon"></span></button>
                    <div class="suggest-search hide">
                        <div class="content-suggest">
                            <ul></ul>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <span class="arrow-down"></span>
    </section>
    <?php
    $categories = \vsoft\ad\models\AdCategory::find ()->indexBy ( 'id' )->asArray ( true )->all ();
    $types = \vsoft\ad\models\AdProduct::getAdTypes ();
    $products = Ad::find()->homePageRandom();
    ?>
    <section class="box-item box-feature-item">
        <div class="title-sub">Featured properties</div>
        <div class="wrap-item">
            <?php foreach ($products as $product): ?>
            <?php
            if ($image = \vsoft\ad\models\AdImages::find ()->where ( [
                'order' => 0,
                'product_id' => $product->id
            ] )->one ()) {
                $imgUrl = $image->imageMedium;
            } else {
                $imgUrl = '/themes/metvuong2/resources/images/default-ads.jpg';
            }
            ?>
            <div class="item">
                <a href="<?= Url::to(['/ad/detail', 'id' => $product->id, 'slug' => \yii\helpers\Inflector::slug($product->getAddress())]) ?>" class="pic-intro">
                    <div class="wrap-img"><img src="<?= $imgUrl ?>" alt=""></div>
                    <div class="title-item"><?= ucfirst($categories[$product->category_id]['name']) ?> <?= strtolower($types[$product->type]) ?></div>
                </a>
                <div class="info-item">
                    <div class="address-feat">
<!--                        <strong>Lancaster x</strong>-->
                        <?= $product->getAddress(true) ?>
                        <p class="id-duan">ID:<span><?=$product->id;?></span></p>
                        <ul class="clearfix list-attr-td">
                            <li>
                                <span class="icon icon-dt icon-dt-small"></span>80m2
                            </li>
                            <li>
                                <span class="icon icon-bed icon-bed-small"></span> 02
                            </li>
                            <li>
                                <span class="icon icon-pt icon-pt-small"></span> 02
                            </li>
                        </ul>
                    </div>
                    <div class="bottom-feat-box clearfix">
                        <a href="<?= Url::to(['/ad/detail', 'id' => $product->id, 'slug' => \yii\helpers\Inflector::slug($product->getAddress())]) ?>" class="pull-right">Chi tiết</a>
                        <p>Giá <strong>4 tỷ đồng</strong></p>
                    </div>
                </div>
            </div>
            <?php
            endforeach;
            ?>
        </div>
    </section>

    <?php if(count($news) > 0) {?>
    <section class="box-item news-item">
        <div class="title-sub">news</div>
        <div class="wrap-item">
            <?php
                foreach($news as $n){
            ?>
            <div class="item clearfix">
                <a href="<?= \yii\helpers\Url::to(['news/view', 'id' => $n['id'], 'slug' => $n['slug'], 'cat_id' => $n['catalog_id'], 'cat_slug' => $n["cat_slug"]]) ?>">
                    <div class="wrap-img bgcover" style="background-image:url(<?=Url::to('/store/news/show/' . $n['banner']) ?>);"></div>
                    <span class="txt-short-news">
                        <span class="title-news color-30a868"><?=$n['title']?></span>
                        <span class="date-news"><?=date('d/m/Y, H:i', $n['updated_at'])?></span>
                        <?=StringHelper::truncate($n['brief'], 120)?>
                    </span>
                </a>
            </div>
            <?php } ?>
        </div>
    </section>
    <?php } ?>

    <section class="search-home">
        <div class="txt-intro">
            <h2>Chúng tôi sẽ giúp bạn tìm được ngôi nhà mà mình mong muốn trong chốc lát</h2>
            <p>Mét Vuông cung cấp lên đến hàng chục ngàn các căn hộ cao cấp, nhà riêng và biệt thự cho thuê hoặc đang rao bán tại hầu hết các tỉnh thành trên cả nước. Thông qua những mô tả chi tiết và hình ảnh chân thật, chúng tôi cam kết giúp khách hàng tìm được sản phẩm phù hợp nhất, một cách nhanh chóng và hiệu quả.</p>
            <div class="text-center pdT-25"><a href="#" class="btn-common">Tìm hiểu thêm</a></div>
        </div>
    </section>
</div>