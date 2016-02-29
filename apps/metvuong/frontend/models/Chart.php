<?php
/**
 * Created by PhpStorm.
 * User: vinhnguyen
 * Date: 12/8/2015
 * Time: 10:14 AM
 */

namespace frontend\models;
use common\components\Util;
use vsoft\ad\models\AdProduct;
use vsoft\ad\models\AdProductSaved;
use vsoft\ad\models\AdProductSavedSearch;
use vsoft\tracking\models\base\AdProductFinder;
use vsoft\tracking\models\base\AdProductVisitor;
use vsoft\tracking\models\AdProductVisitorSearch;
use Yii;
use yii\base\Component;
use yii\helpers\Url;
use yii\web\HttpException;
use yii\web\NotFoundHttpException;

class Chart extends Component
{
    const DATE_FORMAT = 'Y-m-d';
    const TYPE_VISITOR = 1;
    const TYPE_FINDER = 2;
    const TYPE_SAVED = 3;

    public static function find()
    {
        return Yii::createObject(Chart::className());
    }

    public function getDataFinder($pid, $from, $to){
        // finder
        $query = AdProductFinder::find();
        $query->andFilterWhere(['between', 'time', $from, $to]);
        if(!empty($pid)){
            $query->andWhere(['product_id' => (int)$pid]);
        }
        $adProductFinders = $query->orderBy('time DESC')->limit(7)->all();
        $infoFinder = array();
        foreach($adProductFinders as $k => $q){
            $username = User::findIdentity($q->user_id)->username;
            if(array_key_exists($username, $infoFinder)){
                $infoFinder[$username]++;
            } else
                $infoFinder[$username] = 1;
        }
        // visitor
        $query = AdProductVisitor::find();
        $query->andFilterWhere(['between', 'time', $from, $to]);
        if(!empty($pid)){
            $query->andWhere(['product_id' => (int)$pid]);
        }
        $adProductVisitor = $query->orderBy('time DESC')->limit(7)->all();
        $infoVisitors = array();
        foreach($adProductVisitor as $k => $q){
            $username = User::findIdentity($q->user_id)->username;
            if(array_key_exists($username, $infoVisitors)){
                $infoVisitors[$username]++;
            } else
                $infoVisitors[$username] = 1;
        }
        // saved
        $query = AdProductSaved::find();
        $query->andFilterWhere(['between', 'saved_at', $from, $to]);
        if(!empty($pids)){
            $query->andWhere(['product_id' => $pid]);
        }
        $adProductSaved = $query->all();
        $infoSaved = array();
        foreach($adProductSaved as $k => $q){
            $username = User::findIdentity($q->user_id)->username;
            if(array_key_exists($username, $infoSaved)){
                $infoSaved[$username]++;
            } else
                $infoSaved[$username] = 1;
        }

        $infoData["finders"] = $infoFinder;
        $infoData["visitors"] = $infoVisitors;
        $infoData["saved"] = $infoSaved;

        $dateRange = Util::me()->dateRange($from, $to, '+1 day', self::DATE_FORMAT);
        $defaultData = array_map(function ($key, $date) {
            return ['y' => 0,'url' => Url::to(['/dashboard/chart', 'view'=>'_partials/listContact', 'date'=>$date])];
        }, array_keys($dateRange), $dateRange);
        if(!empty($adProductFinders)){
            return $this->pushDataToChart($adProductFinders, $defaultData, $dateRange, $infoData);
        }
        return false;
    }

    public function getDataVisitor($pid, $from, $to){
        // visitor
        $query = AdProductVisitor::find();
        $query->andFilterWhere(['between', 'time', $from, $to]);
        if(!empty($pid)){
            $query->andWhere(['product_id' => (int)$pid]);
        }
        $adProductVisitors = $query->orderBy('time DESC')->limit(7)->all();
        $infoVisitors = array();
        foreach($adProductVisitors as $k => $q){
            $username = User::findIdentity($q->user_id)->username;
            if(array_key_exists($username, $infoVisitors)){
                $infoVisitors[$username]++;
            } else
                $infoVisitors[$username] = 1;
        }

        $infoData["visitors"] = $infoVisitors;

        $dateRange = Util::me()->dateRange($from, $to, '+1 day', self::DATE_FORMAT);
        $defaultData = array_map(function ($key, $date) {
            return ['y' => 0,'url' => Url::to(['/dashboard/chart', 'view'=>'_partials/listContact', 'date'=>$date])];
        }, array_keys($dateRange), $dateRange);
        if(!empty($adProductVisitors)){
            return $this->pushDataToChart($adProductVisitors, $defaultData, $dateRange, $infoData);
        }
        return false;
    }

    public function getDataSaved($pid, $from, $to){
        // saved
        $query = AdProductSaved::find();
        $query->andFilterWhere(['between', 'saved_at', $from, $to]);
        if(!empty($pids)){
            $query->andWhere(['product_id' => $pid]);
        }
        $adProductSaveds = $query->all();
        $infoSaved = array();
        foreach($adProductSaveds as $k => $q){
            $username = User::findIdentity($q->user_id)->username;
            if(array_key_exists($username, $infoSaved)){
                $infoSaved[$username]++;
            } else
                $infoSaved[$username] = 1;
        }
        $infoData["saved"] = $infoSaved;
        $dateRange = Util::me()->dateRange($from, $to, '+1 day', self::DATE_FORMAT);
        $defaultData = array_map(function ($key, $date) {
            return ['y' => 0,'url' => Url::to(['/dashboard/chart', 'view'=>'_partials/listContact', 'date'=>$date])];
        }, array_keys($dateRange), $dateRange);
        if(!empty($adProductSaveds)){
            return $this->pushDataToChart($adProductSaveds, $defaultData, $dateRange, $infoData);
        }
        return false;
    }

    public function getContacts(){
        $date = Yii::$app->request->get('date');
        $type = Yii::$app->request->get('type');
        $pid = Yii::$app->request->get('pid');
        $from = strtotime($date);
        $to = strtotime('+1 days', strtotime($date));
        if(empty($type)){
            throw new NotFoundHttpException('Not found');
        }
        $provider = [
            1 => [
                'title' => 'Nguyễn Trung Ngạn',
                'phone' => '090903xxxx',
                'time' => date('H:i:s d-m-Y', strtotime('-2days')),
            ],
            2 => [
                'title' => 'Quách Tuấn Lệnh',
                'phone' => '090903xxxx',
                'time' => date('H:i:s d-m-Y', strtotime('-3days')),
            ],
            3 => [
                'title' => 'Quách Tuấn Du',
                'phone' => '090903xxxx',
                'time' => date('H:i:s d-m-Y', strtotime('-5days')),
            ],

        ];
        switch($type){
            case self::TYPE_VISITOR:
                $searchModel = new AdProductVisitorSearch();
                $searchModel->product_id = $pid;
                $provider = $searchModel->search2(Yii::$app->request->queryParams, $from, $to);
                break;
            case self::TYPE_FINDER:
                break;
            case self::TYPE_SAVED:
                $searchModel = new AdProductSavedSearch();
                $searchModel->product_id = $pid;
                $provider = $searchModel->search2(Yii::$app->request->queryParams, $pid, $from, $to);
                break;
        }
        return $provider;

    }

    private function pushDataToChart($adProductTypes, $defaultData, $dateRange, $infoData){
        if(!empty($adProductTypes)){
            $tmpDataByPid = [];
            foreach($adProductTypes as $k => $item){
                $day = date(self::DATE_FORMAT, $item->time);
                $product = AdProduct::getDb()->cache(function ($db) use ($item) {
                    return AdProduct::find()->where(['id' => $item->product_id])->one();
                });
                $key = $item->product_id;
                if(empty($tmpDataByPid[$key]['data'])){
                    $tmpDataByPid[$key]['data'] = $defaultData;
                }
                $kDate = array_search($day, $dateRange);
                $tmpDataByPid[$key]['data'][$kDate]['y']++;
//                $tmpDataByPid[$key]['data'][$kDate]['url'] = Url::to(['/user-management/chart', 'view'=>'_partials/listContact', 'date'=>$day, 'pid'=>$key, 'type'=>$type]);
                $tmpDataByPid[$key]['name'] = $product->getAddress();
                $tmpDataByPid[$key]['data'][$kDate]['color'] = '#00a769';

            }
            return ['dataChart'=>$tmpDataByPid, 'categories'=>$dateRange, 'infoData' => $infoData];
        }
    }
}