<?php

namespace vsoft\ad\models;

use dektrium\user\helpers\Password;
use frontend\models\User;
use Yii;
use vsoft\ad\models\base\AdContactInfoBase;

/**
 * This is the model class for table "ad_contact_info".
 *
 * @property integer $product_id
 * @property string $name
 * @property string $address
 * @property string $phone
 * @property string $mobile
 * @property string $email
 *
 * @property AdProduct $product
 */
class AdContactInfo extends AdContactInfoBase
{

	public function rules()
	{
		return [
			[['mobile'], 'required'],
			[['product_id'], 'integer'],
			[['name', 'phone', 'mobile'], 'string', 'max' => 32],
			[['address', 'email'], 'string', 'max' => 255],
			[['mobile', 'phone'], 'string', 'length' => [7, 11]],
			['email', 'email'],
			[['product_id'], 'unique']
		];
	}
	
	public function loadDefaultValues($skipIfSet = true) {
		if(!Yii::$app->user->isGuest) {
			$this->name = Yii::$app->user->identity->profile->name;
			$this->email = Yii::$app->user->identity->profile->public_email;
		}
		
		return parent::loadDefaultValues($skipIfSet);
	}

	/**
	 * get Url
	 */
	public function getUrl() {

	}
	/**
	 * Auto register user for agent
	 */
	public function getUserInfo() {
		if(($user = User::findOne(['email'=>$this->email])) != null) {
			return $user;
		}else{
			$user = Yii::createObject(User::className());
			$user->setScenario('register');
			$user->email = $this->email;
			$user->password = Password::generate(8);
			if(empty($user->username)){
				$user->username = $user->generateUsername();
			}
			$user->setAttributes($this->attributes);
			$user->validate();
			if (!$user->hasErrors()) {
				$user->register();
				return $user;
			}else{
				return $user->errors;
			}
		}
	}
}
