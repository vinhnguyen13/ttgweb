<?php
use yii\web\View;
use yii\widgets\ActiveForm;
use yii\helpers\Url;

$this->registerCssFile(Yii::$app->view->theme->baseUrl."/resources/css/bootstrap.min.css", ['depends' => [\yii\bootstrap\BootstrapAsset::className()],], 'bootstrap');
$this->registerCssFile("https://fonts.googleapis.com/css?family=Roboto:400,300,700", ['depends' => [\yii\bootstrap\BootstrapAsset::className()],], 'font-roboto');
$this->registerCssFile(Yii::$app->view->theme->baseUrl."/resources/css/font-awesome.min.css", ['depends' => [\yii\bootstrap\BootstrapAsset::className()],], 'font-awesome');
$this->registerCssFile(Yii::$app->view->theme->baseUrl."/resources/css/simple-line-icons.css", ['depends' => [\yii\bootstrap\BootstrapAsset::className()],], 'simple-line-icons');
$this->registerCssFile(Yii::$app->view->theme->baseUrl."/resources/css/style-custom.css", ['depends' => [\yii\bootstrap\BootstrapAsset::className()],], 'style-custom');

Yii::$app->getView()->registerJsFile(Yii::$app->view->theme->baseUrl.'/resources/js/jquery.min.js', ['position'=>View::POS_HEAD]);
Yii::$app->getView()->registerJsFile(Yii::$app->view->theme->baseUrl.'/resources/js/bootstrap.min.js', ['position'=>View::POS_HEAD]);
Yii::$app->getView()->registerJsFile(Yii::$app->view->theme->baseUrl.'/resources/js/menu.min.js', ['position'=>View::POS_HEAD]);
$script = <<< JS
var url_tt = "_url_tt",
            url_loaibds = "_url_loaibds",
            url_ttuc = "_url_ttuc";
JS;
Yii::$app->getView()->registerJs(strtr($script, ['_url_tt'=>Yii::$app->view->theme->baseUrl.'/resources/data/tinh-thanh.json',
                                                '_url_loaibds'=>Yii::$app->view->theme->baseUrl.'/resources/data/loai-bds.json',
                                                '_url_ttuc'=>Yii::$app->view->theme->baseUrl.'/resources/data/loai-tintuc.json'
                                        ]), View::POS_HEAD);
Yii::$app->getView()->registerJsFile(Yii::$app->view->theme->baseUrl.'/resources/js/common.js', ['position'=>View::POS_END]);
Yii::$app->getView()->registerJsFile(Yii::$app->view->theme->baseUrl.'/resources/js/search.js', ['position'=>View::POS_END]);
/* @var $this yii\web\View */

$this->title = Yii::t('express','We offer exeptional amenities and renowned white - glove services');
?>
<script>
    $(document).ready(function(){
        $(document).on('click', '#btn-search', function(){
            setTimeout(function() {
                $('.wrap-search-home .logo-home').addClass('ani-logo').css({
                    'transform': 'translate3d( 0, 0, 0)',
                    '-webkit-transform': 'translate3d( 0, 0, 0)',
                    '-moz-transform': 'translate3d( 0, 0, 0)',
                    '-ms-transform': 'translate3d( 0, 0, 0)'
                });
                $('.box-search-header').addClass('ani-search').css({
                    'transform': 'translate3d( 0, 0, 0)',
                    '-webkit-transform': 'translate3d( 0, 0, 0)',
                    '-moz-transform': 'translate3d( 0, 0, 0)',
                    '-ms-transform': 'translate3d( 0, 0, 0)'
                });
                setTimeout(function() {
                    $('header').addClass('border-shadow');
                    setTimeout(function() {$('#search-kind').submit();},500);
                },500);
            },500);
            return false;
        });

        $(document).bind( 'real-estate/news', function(event, json, string){
            setTimeout(function() {
                $('.wrap-search-home .logo-home').addClass('ani-logo').css({
                    'transform': 'translate3d( 0, 8px, 0)',
                    '-webkit-transform': 'translate3d( 0, 8px, 0)',
                    '-moz-transform': 'translate3d( 0, 8px, 0)',
                    '-ms-transform': 'translate3d( 0, 8px, 0)'
                });
                $('.box-search-header').addClass('ani-search').css({
                    'transform': 'translate3d(180px, 8px, 0px)',
                    '-webkit-transform': 'translate3d(180px, 8px, 0px)',
                    '-moz-transform': 'translate3d(180px, 8px, 0px)',
                    '-ms-transform': 'translate3d(180px, 8px, 0px)'
                });
                setTimeout(function() {
                    $('header').addClass('border-shadow');
                    setTimeout(function() {$('#search-kind').submit();},100);
                },500);
            },500);
        });
        
        $(document).bind( 'real-estate/post', function(event, json, string){
            setTimeout(function() {
                $('.wrap-search-home .logo-home').addClass('ani-logo');
                $('.box-search-header').addClass('ani-search');
                setTimeout(function() {
                    $('header').addClass('border-shadow');
                    location.href = '<?=Url::to(['/ads/post'])?>';
                },500);
            },500);
        });


    });
</script>
<div class="o-wrapper clearfix wrap-page-home">
    <header class="home-page cd-secondary-nav">
        <div class="container clearfix">
            <?php $this->beginContent('@app/views/layouts/_partials/menuMain.php'); ?><?php $this->endContent();?>
            <div class="wrap-search-home">
                <div class="bgcover logo-home" style="background-image:url(<?=Yii::$app->view->theme->baseUrl?>/resources/images/logo.png);"><a href="<?=Url::home()?>"></a></div>
                <div class="box-search-header clearfix">
                        <div class="pull-left">
                            <?php $form = ActiveForm::begin([
                                'options'=>['class' => 'form-inline pull-left', 'method'=>'POST'],
                                'id'=>'search-kind',
                                'action'=>Url::to(['/ads/search']),
                                'fieldConfig' => [],
                            ]); ?>
                                <div class="form-group">
                                    <div class="type-search">
                                        <ul class="outsideevent"></ul>
                                        <input id="searchInput" name="search" type="text" class="form-control outsideevent" placeholder="" readonly="readonly">
                                    </div>
                                    <div id="step-1" class="outsideevent search-wrap hidden-effect" data-txt-step="Bạn ở Tỉnh/Thành nào ?">
                                        <div class="wrap-effect">
                                            <div class="search-item">
                                                <a href="#" class="btn-close-search"><em class="icon-close"></em></a>
                                                <h3>Bạn ở Thành phố nào ?</h3>
                                                <ul class="clearfix list-tinh-thanh"></ul>
                                            </div>
                                        </div>
                                    </div>
                                    <div id="step-2" class="outsideevent search-wrap hidden-effect" data-txt-step="Bạn ở Quận/Huyện nào ?">
                                        <div class="wrap-effect">
                                            <div class="search-item clearfix">
                                                <a href="#" class="btn-close-search"><em class="icon-close"></em></a>
                                                <h3>Bạn ở Quận nào ?</h3>
                                                <ul class="list-quan-huyen"></ul>
                                            </div>
                                        </div>
                                    </div>
                                    <div id="step-3" class="outsideevent search-wrap hidden-effect" data-txt-step="Loại BDS bạn quan tâm ?">
                                        <div class="wrap-effect">
                                            <div class="search-item clearfix">
                                                <a href="#" class="btn-close-search"><em class="icon-close"></em></a>
                                                <h3>Loại BDS bạn quan tâm ?</h3>
                                                <ul class="list-loai-bds"></ul>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="outsideevent search-wrap hidden-effect" data-txt-step="" data-template="suggest-list" data-end="true">
                                        <div class="wrap-effect">
                                            <div class="search-item clearfix">
                                                <a href="#" class="btn-close-search"><em class="icon-close"></em></a>
                                                <h3>Chọn dự án</h3>
                                                <ul class="list-duan-suggest">
                                                    <li><a href="#">RICHSTAR</a></li>
                                                    <li><a href="#">SUNRISE RIVERSIDE</a></li>
                                                    <li><a href="#">ORCHARD PARKVIEW</a></li>
                                                    <li><a href="#">GOLDEN MANSION</a></li>
                                                    <li><a href="#">KINGSTON RESIDENCE</a></li>
                                                    <li><a href="#">THE BOTANICA</a></li>
                                                    <li><a href="#">THE SUN AVENUE</a></li>
                                                    <li><a href="#">ORCHARD GARDEN</a></li>
                                                    <li><a href="#">SUNRISE CITYVIEW</a></li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="outsideevent search-wrap hidden-effect" data-txt-step="" data-template="cost-min-max" data-end="true">
                                        <div class="wrap-effect">
                                            <div class="search-item clearfix">
                                                <a href="#" class="btn-close-search"><em class="icon-close"></em></a>
                                                <h3>Nhập khoảng giá ?</h3>
                                                <div class="frm-cost-min-max clearfix">
                                                    <div class="form-group inline-group box-cost col-xs-5" data-tab="min">
                                                        <input id="minCost" type="text" class="form-control cost-value" placeholder="min" readonly="readonly">
                                                        <div class="outsideevent wrap-cost-bds hidden-cost">
                                                            <div class="wrap-effect-cost">
                                                                <ul>
                                                                    <li data-cost="0" data-unit=""><span>0</span></li>
                                                                    <li data-cost="1" data-unit="triệu"><span>1 triệu</span></li>
                                                                    <li data-cost="2" data-unit="triệu"><span>2 triệu</span></li>
                                                                    <li data-cost="3" data-unit="triệu"><span>3 triệu</span></li>
                                                                    <li data-cost="4" data-unit="triệu"><span>4 triệu</span></li>
                                                                    <li data-cost="5" data-unit="triệu"><span>5 triệu</span></li>
                                                                    <li data-cost="6" data-unit="tỷ"><span>6 triệu</span></li>
                                                                </ul>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="line-center form-group inline-group box-cost col-xs-2"><span></span></div>
                                                    <div class="form-group inline-group box-cost col-xs-5" data-tab="max">
                                                        <input id="maxCost" type="text" class="form-control cost-value" placeholder="max" readonly="readonly">
                                                        <div class="outsideevent wrap-cost-bds hidden-cost">
                                                            <div class="wrap-effect-cost">
                                                                <ul>
                                                                    <li data-cost="0" data-unit=""><span>0</span></li>
                                                                    <li data-cost="1" data-unit="triệu"><span>1 triệu</span></li>
                                                                    <li data-cost="2" data-unit="triệu"><span>2 triệu</span></li>
                                                                    <li data-cost="3" data-unit="triệu"><span>3 triệu</span></li>
                                                                    <li data-cost="4" data-unit="triệu"><span>4 triệu</span></li>
                                                                    <li data-cost="5" data-unit="triệu"><span>5 triệu</span></li>
                                                                    <li data-cost="6" data-unit="tỷ"><span>6 triệu</span></li>
                                                                </ul>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <input id="valCosMin" type="hidden" value="" data-val-cost="min" class="val-cost">
                                                <input id="valCosMax" type="hidden" value="" data-val-cost="max" class="val-cost">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="outsideevent search-wrap hidden-effect" data-txt-step="Bạn nên chọn Loại Tin Tức ?" data-template="news" data-end="true">
                                        <div class="wrap-effect">
                                            <div class="search-item clearfix">
                                                <a href="#" class="btn-close-search"><em class="icon-close"></em></a>
                                                <h3>Bạn nên chọn Loại Tin Tức ?</h3>
                                                <ul class="list-loai-tt"></ul>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                                <button id="btn-search" type="submit" class="btn btn-default"><span><em class="fa fa-search"></em></span></button>
                                <input class="getValSuggest" type="hidden" id="valTT" name="city" value="">
                                <input class="getValSuggest" type="hidden" id="valQh" name="district" value="">
                                <input class="getValSuggest" type="hidden" id="valLoai" name="category" value="">
                                <input class="getValSuggest" type="hidden" id="valTTuc" name="news" value="">
                            <?php ActiveForm::end(); ?>
                            <div class="pull-left text-right mgT-10 mgL-15">
                                <div class="search-select active">
                                    <a href="#" data-placeholder="Bạn ở Tỉnh/Thành nào ?" rel="#dd-search">
                                        <span>
                                            <em class="fa fa-home"></em>
                                            <em class="fa fa-search"></em>
                                        </span>
                                        <i>Muốn Mua/Thuê</i>
                                    </a>
                                </div>
                                <div class="search-select">
                                    <a href="#" data-placeholder="Bạn ở Tỉnh/Thành nào ?" rel="#dd-dky">
                                        <span>
                                            <em class="fa fa-home"></em>
                                            <em class="fa fa-pencil-square-o"></em>
                                        </span>
                                        <i>Đăng ký Bán/Thuê</i>
                                    </a>
                                </div>
                                <div class="search-select">
                                    <a href="#" class="" data-step-fix="step-5" data-placeholder="Bạn nên chọn Loại Tin Tức ?" rel="#dd-news">
                                        <span>
                                            <em class="fa fa-home"></em>
                                            <em class="fa fa-file-text"></em>
                                        </span>
                                        <i>Tin Tức</i>
                                    </a>
                                </div>
                            </div>
                        </div>
                </div>
                
            </div>
        </div>
    </header>
    <div class="container">

    </div>
    <footer class="clearfix">
        <div class="pull-left copyright">
            <p><span>&copy;</span>2015. Bản quyền thuộc về Công ty Metvuong</p>
        </div>
        <div class="pull-right polli">
            <ul>
                <li><a href="#">Giới thiệu</a></li>
                <li><a href="#">Điều khoản</a></li>
                <li>
                    <span>Kết nối:</span>
                    <a title="facebook metvuong.com" class="logo-social fb-icon" href="#"></a>
                    <a title="twitter metvuong.com" class="logo-social twe-icon" href="#"></a>
                    <a title="google plus metvuong.com" class="logo-social g-icon" href="#"></a>
                    <a title="youtube metvuong.com" class="logo-social ytu-icon" href="#"></a>
                </li>
            </ul>
        </div>
    </footer>
    <div id="iePopup">
        <div id="jr_overlay"></div>
        <div id="jr_wrap">
            <div id="jr_inner">
                <h1 id="jr_header">Bạn có biết rằng trình duyệt của bạn đã lỗi thời?</h1>
                <p>Trình duyệt của bạn đã lỗi thời, và có thể không tương thích tốt với website, chắc chắn rằng trải nghiệm của bạn trên website sẽ bị hạn chế. Bên dưới là danh sách những trình duyệt phổ biến hiện nay.</p>
                <p>Click vào biểu tượng để tải trình duyệt bạn muốn.</p>
                <ul>
                    <li id="jr_chrome"><a href="http://www.google.com/chrome/" target="_blank">Chrome 34</a></li>
                    <li id="jr_firefox"><a href="http://www.mozilla.com/firefox/" target="_blank">Firefox 29</a></li>
                    <li id="jr_msie"><a href="http://www.microsoft.com/windows/Internet-explorer/" target="_blank">Internet Explorer 10</a></li>
                    <li id="jr_opera"><a href="http://www.opera.com/download/" target="_blank">Opera 20</a></li>
                    <li id="jr_safari"><a href="http://www.apple.com/safari/download/" target="_blank">Safari</a></li>
                </ul>
            </div>
        </div>
    </div>
</div>
<?php $this->beginContent('@app/views/layouts/_partials/popup.php'); ?><?php $this->endContent();?>