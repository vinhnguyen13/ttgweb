<?php

namespace common\vendor\vsoft\ad\models;

use Yii;
use common\vendor\vsoft\ad\models\base\AdInvestorBase;
use yii\data\ActiveDataProvider;

/**
 * This is the model class for table "ad_investor".
 *
 * @property integer $id
 * @property string $name
 * @property string $address
 * @property string $phone
 * @property string $fax
 * @property string $website
 * @property string $email
 *
 * @property AdInvestorBuildingProject[] $adInvestorBuildingProjects
 */
class AdInvestor extends AdInvestorBase
{
	const STATUS_ENABLED = 1;
	const STATUS_DISABLED = 0;
	
	public static function labels($id = null)
	{
		$data = [
			self::STATUS_ENABLED => Yii::t('cms', 'Enable'),
			self::STATUS_DISABLED => Yii::t('cms', 'Disable'),
		];
	
		if ($id !== null && isset($data[$id])) {
			return $data[$id];
		} else {
			return $data;
		}
	}
	
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'ad_investor';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['created_at', 'updated_at', 'status'], 'integer'],
            [['name', 'logo', 'phone', 'fax'], 'string', 'max' => 32],
            [['address', 'website', 'email'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'address' => 'Address',
            'phone' => 'Phone',
            'fax' => 'Fax',
            'website' => 'Website',
            'email' => 'Email',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAdInvestorBuildingProjects()
    {
        return $this->hasMany(AdInvestorBuildingProject::className(), ['investor_id' => 'id']);
    }
    
    public function search($params)
    {
    	$query = self::find();
    
    	$query->orderBy(['created_at' => SORT_DESC]);
    
    	$dataProvider = new ActiveDataProvider([
    			'query' => $query,
    			]);
    
    	if ($this->load($params) && !$this->validate()) {
    		return $dataProvider;
    	}
    
    	$query->andFilterWhere([
    			'id' => $this->id
    			]);
    
    	$query->andFilterWhere(['like', 'name', $this->name]);
    
    	return $dataProvider;
    }
}
