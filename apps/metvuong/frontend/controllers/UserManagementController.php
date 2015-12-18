<?php

namespace frontend\controllers;
use dektrium\user\Mailer;
use frontend\models\User;
use frontend\models\userManagement\ProfileForm;
use vsoft\express\components\ImageHelper;
use Yii;
use yii\db\mssql\PDO;
use yii\helpers\Url;
use vsoft\news\models\CmsShow;
use yii\web\Response;
use yii\web\UploadedFile;
use yii\web\View;
use frontend\components\Controller;

class UserManagementController extends Controller
{
    public $layout = '@app/views/user-management/layouts/main';

    public function beforeAction($action)
    {
        if(Yii::$app->user->isGuest){
            $this->redirect(['/member/login']);
        }
        return parent::beforeAction($action);
    }

    public function actionIndex()
    {
        $this->redirect('/user-management/chart');
    }

    public function actionAds()
    {
        if(Yii::$app->request->isAjax){
            return $this->renderPartial('ads/index', [
            ]);
        }
        return $this->render('ads/index', [
        ]);
    }

    public function actionAdsMostSearch()
    {
        if(Yii::$app->request->isAjax){
            return $this->renderPartial('ads/most-search', [
            ]);
        }
        return $this->render('ads/most-search', [
        ]);
    }

    public function actionAdsSuggest()
    {
        if(Yii::$app->request->isAjax){
            return $this->renderPartial('ads/suggest', [
            ]);
        }
        return $this->render('ads/suggest', [
        ]);
    }

    public function actionChart()
    {
        if(Yii::$app->request->isAjax) {
            return $this->renderAjax('chart/ads', [
            ]);
        }
        return $this->render('chart/ads', [
        ]);
    }

    public function actionProfile()
    {
        if (Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = Yii::createObject([
            'class'    => ProfileForm::className(),
            'scenario' => 'updateprofile',
        ]);

        $model = $model->loadProfile();
        if($model->avatar) {
            $model->avatar  = Url::to('/store/avatar/' . $model->avatar);
        } else {
            $model->avatar  = Url::to('/store/avatar/default-avatar.jpg');
        }

        if(Yii::$app->request->isAjax) {
            if(Yii::$app->request->isPost){
                Yii::$app->response->format = Response::FORMAT_JSON;
                $post = Yii::$app->request->post();
                $model->load($post);
                $model->validate();
                if (!$model->hasErrors()) {
                    $res = $model->updateProfile();
                    return ['statusCode'=>true];
                }else{
                    return ['statusCode'=> false, 'parameters' => $model->errors];
                }
            }
            return $this->renderAjax('user/profile', [
                'model' => $model
            ]);
        }
        return $this->render('user/profile', [
            'model' => $model
        ]);
    }

    public function actionPassword()
    {
        if (Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = Yii::createObject([
            'class'    => ProfileForm::className(),
            'scenario' => 'password',
        ]);

        if(Yii::$app->request->isAjax) {
            if(Yii::$app->request->isPost){
                Yii::$app->response->format = Response::FORMAT_JSON;
                $post = Yii::$app->request->post();
                $model->load($post);
                $model->validate();
                if (!$model->hasErrors()) {
                    $res = $model->resetPass();
                    return ['statusCode'=>$res];
                }else{
                    return ['statusCode'=> false, 'parameters' => $model->errors];
                }
            }
            return $this->renderAjax('user/password', [
                'model' => $model
            ]);
        }

        return $this->render('user/password', [
            'model'=>$model
        ]);
    }


    public function actionAvatar($folder = 'building-project-images', $resizeForAds = false) {
        $model = Yii::createObject([
            'class' => ProfileForm::className(),
            'scenario' => 'updateavatar',
        ]);

        if($_FILES) {
            Yii::$app->response->format = Response::FORMAT_JSON;

            $image = UploadedFile::getInstanceByName('upload');
            $dir = \Yii::getAlias('@store') . "/$folder";
            $uniqid = uniqid();
            $extension = pathinfo($image->name, PATHINFO_EXTENSION);

            $orginal = 'u_'. Yii::$app->user->id. '_' .$uniqid . '.' . $extension;
            $thumbnail = 'u_'. Yii::$app->user->id. '_' .$uniqid . '.thumb.' . $extension;

            $orginalPath = $dir . '/' . $orginal;
            $thumbnailPath = $dir . '/' . $thumbnail;

            $image->saveAs($orginalPath);

            $options = ($resizeForAds) ? [] : ['thumbWidth' => 120, 'thumbHeight' => 120];
            $imageHelper = new ImageHelper($orginalPath, $options);
            $imageHelper->makeThumb(false, $thumbnailPath);

            $response['files'][] = [
                'url'           => Url::to("/store/$folder/". $orginal),
                'thumbnailUrl'  => Url::to("/store/$folder/" . $thumbnail),
                'name'          => $orginal,
                'type'          => $image->type,
                'size'          => $image->size,
                'deleteUrl'     => Url::to(['user-management/delete-image', 'orginal' => $orginal, 'thumbnail' => $thumbnail, 'folder' => $folder]),
                'deleteType'    => 'DELETE',
                'deleteLater'	=> 0,
            ];
            $model->updateAvatar($orginal);
            return $response;
        }
    }

    public function actionDeleteImage($orginal, $thumbnail, $deleteLater = false, $folder = 'building-project-images', $resizeForAds = false) {
        Yii::$app->response->format = Response::FORMAT_JSON;
        if(! $deleteLater) {
            $dir = \Yii::getAlias('@store') . DIRECTORY_SEPARATOR . $folder;
            unlink($dir . DIRECTORY_SEPARATOR . $orginal);
            if(unlink($dir . DIRECTORY_SEPARATOR . $thumbnail) && $thumbnail != "default-avatar.thumb.jpg")
            {
                $model = Yii::createObject([
                    'class' => ProfileForm::className(),
                    'scenario' => 'updateavatar',
                ]);

                $model->updateAvatar(null);
            }

        }

        return ['files' => []];
    }

}
