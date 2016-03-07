<?php

namespace vsoft\ad\models;

use Yii;
use common\models\AdDistrict as AD;

/**
 * This is the model class for table "ad_district".
 *
 * @property integer $id
 * @property integer $city_id
 * @property string $name
 * @property string $pre
 * @property integer $order
 * @property integer $status
 *
 * @property AdCity $city
 * @property AdProduct[] $adProducts
 * @property AdStreet[] $adStreets
 * @property AdWard[] $adWards
 */
class AdDistrict extends AD
{
	public function getStreets()
	{
		return $this->hasMany(AdStreet::className(), ['district_id' => 'id']);
	}
	

	public function getWards()
	{
		return $this->hasMany(AdWard::className(), ['district_id' => 'id']);
	}
	
	public static function getListByCity($cityId) {
		$items = [];
		
		if($cityId) {
			$districts = self::find()->where('`city_id` = :city_id', [':city_id' => $cityId])->all();
			
			foreach($districts as $district) {
				$items[$district['id']] = $district['pre'] ? "{$district['pre']} {$district['name']}" : $district['name'];
			}
		}
		
		return $items;
	}
}
