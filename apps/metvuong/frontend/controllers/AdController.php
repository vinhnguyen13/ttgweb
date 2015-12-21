<?php

namespace frontend\controllers;
use frontend\components\Controller;
use frontend\models\Ad;
use Yii;
use yii\helpers\Url;
use vsoft\news\models\CmsShow;
use vsoft\ad\models\AdBuildingProject;
use vsoft\ad\models\AdProduct;
use vsoft\ad\models\AdImages;
use vsoft\ad\models\AdProductAdditionInfo;
use vsoft\ad\models\AdContactInfo;
use yii\web\Cookie;
use vsoft\express\components\ImageHelper;
use vsoft\express\components\StringHelper;
use vsoft\ad\models\base\ActiveRecord;
use yii\data\Pagination;
use yii\widgets\LinkPager;
use vsoft\ad\models\AdCity;
use yii\helpers\ArrayHelper;
use vsoft\ad\models\AdDistrict;

class AdController extends Controller
{
    public $layout = '@app/views/layouts/layout';

    /**
     * @return string
     */
    public function actionIndex($result = false)
    {
        $this->layout = '@app/views/layouts/search';
        
        if($result) {
        	Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        	
        	$cityId = Yii::$app->request->get('cityId');
        	$districtId = Yii::$app->request->get('districtId');
        	$categoryId = Yii::$app->request->get('categoryId');
        	$orderBy = Yii::$app->request->get('orderBy', 'created_at');
        	
        	$query = (new \yii\db\Query())->groupBy('ad_product.id')
        				->leftJoin('ad_product_addition_info', 'ad_product.id = ad_product_addition_info.product_id')
        				->leftJoin('ad_images', 'ad_product.id = ad_images.product_id')
        				->groupBy('ad_product.id')
        				->select('ad_product.*, ad_images.file_name');
        	
        	if($cityId) {
        		$query->where('city_id = :city_id', [':city_id' => $cityId]);
        	}
        	
        	if($districtId) {
        		$query->andWhere('district_id = :district_id', [':district_id' => $districtId]);
        	}
        	
        	if($categoryId) {
        		$query->andWhere('category_id = :category_id', [':category_id' => $categoryId]);
        	}

        	$queryCraw = clone $query;
        	
        	$query = $query->from('ad_product')->addSelect(["(0) AS is_craw"]);
        	$queryCraw = $queryCraw->from(\Yii::$app->params['schemaPrefix'] . 'ad_product')->addSelect(["(1) AS is_craw"]);
        	
        	$fullQuery = (new yii\db\Query())->from([$query->union($queryCraw)]);
        	
        	if($orderBy == 'created_at') {
        		$fullQuery->orderBy("$orderBy DESC");
        	} else {
        		$fullQuery->orderBy("$orderBy ASC");
        	}
        	
        	$pages = new Pagination(['totalCount' => $fullQuery->count()]);
        	$pages->pageSize = \Yii::$app->params['listingLimit'];
        	
        	$products = $fullQuery->limit($pages->limit)->offset($pages->offset)->all();
        	
        	$productResponse = [];
        	
        	foreach ($products as $k => $product) {
        		$productResponse[$k] = $product;
        		$productResponse[$k]['previous_time'] = StringHelper::previousTime($product['created_at']);
        		$productResponse[$k]['price'] = StringHelper::formatCurrency($product['price']);
        		$productResponse[$k]['area'] = StringHelper::formatCurrency($product['area']);
        		
        		if($product['file_name']) {
        			$productResponse[$k]['image_url'] = AdImages::getImageUrl($product['file_name']);
        		} else {
        			$productResponse[$k]['image_url'] = Yii::$app->view->theme->baseUrl . '/resources/images/default-ads.jpg';;
        		}
        	}
        	
        	return ['productResponse' => $productResponse, 'pages' => LinkPager::widget(['pagination' => $pages,])];
        }
        
        return $this->render('index');
    }
    
    public function actionDetail($id, $isCraw) {
    	if($isCraw) {
    		ActiveRecord::$schemaPrefix = \Yii::$app->params['schemaPrefix'];
    	}
    	
    	$product = AdProduct::findOne($id);
    	return $this->renderPartial('detail', ['product' => $product]); 
    }

    /**
     * @return \yii\web\Response
     */
    public function actionRedirect()
    {
		$url = Ad::find()->redirect();
        $this->redirect($url);
    }

    /**
     * @return string
     */
    public function actionPost()
    {
    	$cityId = Yii::$app->request->get('city');
    	$districtId = Yii::$app->request->get('district');
		$categoryId = Yii::$app->request->get('category');
    	
		$district = AdDistrict::find()->indexBy('id')->where('city_id = :city_id', [':city_id' => $cityId])->all();

		if($district && isset($district[$districtId]) && $categoryId) {
			$model = new AdProduct();
			$model->loadDefaultValues();
	    	$model->city_id = \Yii::$app->request->get('city');
	    	$model->district_id = \Yii::$app->request->get('district');
	    	$model->category_id = \Yii::$app->request->get('category');
			
	    	$adProductAdditionInfo = $model->adProductAdditionInfo ? $model->adProductAdditionInfo : (new AdProductAdditionInfo())->loadDefaultValues();
	    	$adContactInfo = $model->adContactInfo ? $model->adContactInfo : (new AdContactInfo())->loadDefaultValues();
			    	
	    	if(Yii::$app->request->isPost) {
	    		Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
	    		
	    		$post = Yii::$app->request->post();
	    		
	    		$model->load($post);
	    		$model->start_date = time();
	    		$model->end_date = $model->start_date + (24 * 60 * 60);
	    		$model->created_at = $model->start_date;
	    		
	    		$adProductAdditionInfo->load($post);
	    		$adContactInfo->load($post);
	    		
	    		if($model->validate() && $adProductAdditionInfo->validate() && $adContactInfo->validate()) {
	    			$model->user_id = Yii::$app->user->id;
	    			$model->save(false);
	    			var_dump($model);
	    			$adProductAdditionInfo->product_id = $model->id;
	    			$adProductAdditionInfo->save(false);
	    			
	    			$adContactInfo->product_id = $model->id;
	    			$adContactInfo->save();
	    			
	    			if(isset($post['images']) && $post['images']) {
	    				$images = explode(',', $post['images']);
	    				foreach($images as $k => $image) {
	    					if(!ctype_digit($image)) {
	    						unset($images[$k]);
	    					}
	    				}
	    				
	    				Yii::$app->db->createCommand()->update('ad_images', ["product_id" => $model->id], "`id` IN (" . implode(',', $images) . ") AND user_id = " . Yii::$app->user->id)->execute();
	    			}
	    			
	    			$result = ['success' => true];
	    		} else {
	    			$result = ['success' => false, 'errors' => ['adproduct' => $model->getErrors(), 'adproductadditioninfo' => $adProductAdditionInfo->getErrors(), 'adcontactinfo' => $adContactInfo->getErrors()]];
	    		}
	    		return $result;
	    	}
			    	
			return $this->render('post', ['model' => $model, 'adProductAdditionInfo' => $adProductAdditionInfo, 'adContactInfo' => $adContactInfo]);
		}
		
		return $this->render('requireParam');
    }

    public function actionUpload() {
    	$folder = 'ad';
    	$response = Yii::$app->runAction('express/upload/image', ['folder' => $folder, 'resizeForAds' => true]);
    	
    	if($response) {
    		$path = \Yii::getAlias('@store') . DIRECTORY_SEPARATOR . $folder . DIRECTORY_SEPARATOR . $response['files'][0]['name'];
    		$imageHelper = new ImageHelper($path);
    		$imageHelper->makeMedium();
    		$imageHelper->makeLarge(true);
    		
    		$image = new AdImages();
    		$image->file_name = $response['files'][0]['name'];
    		$image->uploaded_at = time();
    		$image->user_id = Yii::$app->user->id;
    		$image->save(false);
    		
    		$response['files'][0]['deleteUrl'] = Url::to(['delete-image', 'id' => $image->id]);
    		$response['files'][0]['name'] = $image->id;
    		
    		return $response;
    	}
    }
    
    public function actionDeleteImage($id) {
    	$image = AdImages::findOne($id);
    	if($image) {
    		$pathInfo = pathinfo($image->file_name);
    		$thumb = $pathInfo['filename'] .  '.thumb.' . $pathInfo['extension'];
    		
    		$response = Yii::$app->runAction('express/upload/delete-image', ['orginal' => $image->file_name, 'thumbnail' => $thumb, 'folder' => 'ad', 'resizeForAds' => true]);
    		
    		$image->delete();
    		
    		return $response;
    	}
    }
}
