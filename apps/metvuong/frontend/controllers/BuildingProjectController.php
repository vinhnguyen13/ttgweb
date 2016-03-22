<?php
namespace frontend\controllers;

use frontend\components\Controller;
use vsoft\buildingProject\models\BuildingProject;
use Yii;
use yii\data\Pagination;
use vsoft\ad\models\AdBuildingProject;
use yii\web\NotFoundHttpException;
use yii\helpers\Url;
use yii\web\Response;

class BuildingProjectController extends Controller
{
	public $layout = '@app/views/layouts/layout';

    /**
     * @return string
     */
    public function beforeAction($action)
    {
        $this->view->params['menuProject'] = true;
        return parent::beforeAction($action);
    }

	function actionIndex() {
        $models = AdBuildingProject::find()->where('`status` = ' . AdBuildingProject::STATUS_ENABLED)->orderBy('id DESC');
        $count = $models->count();
        $pagination = new Pagination(['totalCount' => $count]);
        $pagination->defaultPageSize = 12;
        $models = $models->offset($pagination->offset)
            ->limit($pagination->limit)
            ->all();
        return $this->render('index', ['models' => $models, 'pagination' => $pagination]);

//		$model = AdBuildingProject::find()->where('`status` = ' . AdBuildingProject::STATUS_ENABLED)->one();
//		if($model) {
//			return $this->render('index', ['model' => $model]);
//		} else {
//			throw new NotFoundHttpException('The requested page does not exist.');
//		}
//		$countQuery = clone $query;
//		$query = $query->orderBy('created_at DESC');
//
//		$pages = new Pagination(['totalCount' => $countQuery->count()]);
//
//		$models = $query->offset($pages->offset)->limit($pages->limit)->all();
//
//		return $this->render('index', [
//			'models' => $models,
//			'pages' => $pages,
//		]);
	}
	
	function actionView($slug) {
		$model = AdBuildingProject::find()->where('`slug` = :slug', [':slug' => $slug])->one();
		if($model) {
			return $this->render('view', ['model' => $model]);
		} else {
			throw new NotFoundHttpException('The requested page does not exist.');
		}
	}
	
	function actionDetail($id) {
		\Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
		
		$model = AdBuildingProject::find()->where('`id` = :id', [':id' => $id])->asArray(true)->one();
	
		$model['url'] = Url::to(['building-project/view', 'slug' => $model['slug']]);
		
		return $model;
	}

    function actionFind() {
        $name = \Yii::$app->request->get("project_name");
        $models = AdBuildingProject::find()->where('`status` = ' . AdBuildingProject::STATUS_ENABLED)->orderBy('id DESC');
        if(!empty($name)) {
            $models = $models->where('name LIKE :query')->addParams([':query' => '%' . $name . '%']);
        }
        $count = $models->count();
        $pagination = new Pagination(['totalCount' => $count]);
        $pagination->defaultPageSize = 12;
        $models = $models->offset($pagination->offset)
            ->limit($pagination->limit)
            ->all();
        return $this->render('index', ['models' => $models, 'project_name' => $name, 'pagination' => $pagination]);
    }
}