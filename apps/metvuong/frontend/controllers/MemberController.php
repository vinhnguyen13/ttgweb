<?php
namespace frontend\controllers;

use frontend\components\Finder;
use dektrium\user\helpers\Password;
use frontend\models\LoginForm;
use frontend\models\RegistrationForm;
use frontend\models\ResetPasswordForm;
use frontend\models\Token;
use frontend\models\User;
use frontend\models\UserLocation;
use vsoft\ad\models\AdProduct;
use Yii;
use yii\base\InvalidParamException;
use yii\data\Pagination;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\Url;
use yii\web\BadRequestHttpException;
use frontend\components\Controller;
use yii\web\Cookie;
use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use frontend\models\RecoveryForm;
use frontend\models\ProfileForm;

/**
 * Site controller
 */
class MemberController extends Controller
{
    public $layout = '@app/views/layouts/layout';
    public $_module;
    /**
     * @inheritdoc
     */
    public function beforeAction($action)
    {
        $this->view->params['noFooter'] = true;
        return parent::beforeAction($action);
    }

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    public function init(){
        $this->_module = Yii::$app->getModule('user');
        parent::init();
    }

    /**
     * Logs in a user.
     *
     * @return mixed
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }
        if(Yii::$app->request->isAjax){
            if(Yii::$app->request->isPost) {
                Yii::$app->response->format = Response::FORMAT_JSON;
                $model = Yii::createObject(LoginForm::className());
                if ($model->load(Yii::$app->getRequest()->post()) && $model->login()) {
                    return ['statusCode' => 200, 'parameters' => ['username' => !empty(Yii::$app->user->identity->profile->name) ? Yii::$app->user->identity->profile->name : Yii::$app->user->identity->email]];
                } else {
                    return ['statusCode' => 404, 'parameters' => $model->errors];
                }
            }else{
                return $this->renderAjax('login');
            }
        }
        return $this->render('login');
        throw new NotFoundHttpException('Not Found');
    }

    /**
     * Logs out the current user.
     *
     * @return mixed
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Signs user up.
     *
     * @return mixed
     */
    public function actionSignup()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }
        if(Yii::$app->request->isAjax){
            if(Yii::$app->request->isPost) {
                Yii::$app->response->format = Response::FORMAT_JSON;
                $model = Yii::createObject(RegistrationForm::className());
                $model->load(Yii::$app->request->post());
                $model->validate();
                if (!$model->hasErrors()) {
                    $user = $model->register();
                    if (!empty($user) && Yii::$app->getUser()->login($user, $this->_module->rememberFor)) {
                        return ['statusCode' => 200, 'parameters' => ['username' => !empty(Yii::$app->user->identity->profile->name) ? Yii::$app->user->identity->profile->name : Yii::$app->user->identity->email]];
                    }
                } else {
                    return ['statusCode' => 404, 'parameters' => $model->errors];
                }
            }else{
                return $this->renderAjax('signup');
            }
        }
        return $this->render('signup');
        throw new NotFoundHttpException('Not Found');
    }

    /**
     * Requests password reset.
     *
     * @return mixed
     */
    public function actionForgot()
    {
        if (!$this->_module->enablePasswordRecovery) {
            throw new NotFoundHttpException();
        }

        /** @var RecoveryForm $model */
        $model = Yii::createObject([
            'class'    => RecoveryForm::className(),
            'scenario' => 'request',
        ]);
        if(Yii::$app->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            $model->load(Yii::$app->request->post());
            $model->validate();
            if (!$model->hasErrors()) {
                if(($msg =$model->sendRecoveryMessage()) !== false){
                    return ['statusCode'=>200, 'parameters'=>['msg'=>$msg]];
                }
            } else {
                return ['statusCode'=>404, 'parameters'=>$model->errors];
            }
        }

        return $this->render('forgot', [
            'model' => $model,
        ]);
    }

    /**
     * Resets password.
     *
     * @param string $token
     * @return mixed
     * @throws BadRequestHttpException
     */
    public function actionResetPassword($id, $code)
    {
        if (!$this->_module->enablePasswordRecovery) {
            throw new NotFoundHttpException();
        }

        /** @var Token $token */
        $token = Token::find()->where(['MD5(CONCAT(user_id, code))' => $id, 'code' => $code, 'type' => Token::TYPE_RECOVERY])->one();
        if ($token === null || $token->isExpired || $token->user === null) {
            Yii::$app->session->setFlash('danger', Yii::t('user', 'Recovery link is invalid or expired. Please try requesting a new one.'));
            return $this->render('/_systems/_alert', [
                'title'  => Yii::t('user', 'Invalid or expired link'),
                'module' => $this->module,
            ]);
        }

        /** @var RecoveryForm $model */
        $model = Yii::createObject([
            'class'    => RecoveryForm::className(),
            'scenario' => 'reset',
        ]);

        if(Yii::$app->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            $model->load(Yii::$app->request->post());
            $model->validate();
            if (!$model->hasErrors()) {
                if(($msg = $model->resetPassword($token)) !== false){
                    return ['statusCode'=>200, 'parameters'=>['msg'=>$msg]];
                }
            } else {
                return ['statusCode'=>404, 'parameters'=>$model->errors];
            }
        }
        return $this->render('reset', [
            'model' => $model,
        ]);
    }

    public function actionAvatar($usrn)
    {
        $user = User::findOne(['username'=>$usrn]);
        $profile = $user->profile;
        $avatarPath = Yii::getAlias('@webroot').( '/images/default-avatar.jpg');
        if($profile->avatar) {
            $pathinfo = pathinfo($profile->avatar);
            $filePath = Yii::getAlias('@store').'/avatar/' . $pathinfo['filename'] . '.thumb.' . $pathinfo['extension'];
            if(file_exists($filePath)){
                $avatarPath = $filePath;
            }
        }
        $pathinfo = pathinfo($avatarPath);
        $response = Yii::$app->getResponse();
        $response->headers->set('Content-Type', 'image/'.$pathinfo['extension']);
        $response->format = Response::FORMAT_RAW;
        if ( !is_resource($response->stream = fopen($avatarPath, 'r')) ) {
            throw new \yii\web\ServerErrorHttpException('file access failed: permission deny');
        }
        return $response->send();
    }

    public function actionProfile($username)
    {
//        $this->checkAccess();
        $model = Yii::createObject([
            'class'    => ProfileForm::className(),
            'scenario' => 'updateprofile',
        ]);

        $sale_products = array();
        $rent_products = array();
        $model = $model->loadProfile($username, 'updateprofile');
        if($model) {
            $query = AdProduct::find()->where('user_id = :uid', [':uid' => $model->user_id]);
            $count = $query->count();
            $pagination = new Pagination(['totalCount' => $count]);
            $pagination->defaultPageSize = 6;
            $products = $query->offset($pagination->offset)->limit($pagination->limit)
                ->orderBy(['district_id' => SORT_ASC, 'city_id'=> SORT_ASC, 'id' => SORT_DESC])->all();
            if($count > 0){
                foreach($products as $product){
                    if($product->type == 1)
                        array_push($sale_products, $product);
                    else
                        array_push($rent_products, $product);
                }
            }
        }

        if(Yii::$app->request->isAjax) {
            if(Yii::$app->request->isPost){
                Yii::$app->response->format = Response::FORMAT_JSON;
                $post = Yii::$app->request->post();
                $model->load($post);
                $model->validate();
                if (!$model->hasErrors() && $username == Yii::$app->user->identity->username) {
                    if($post["type"])
                        $model->$post["type"] = strip_tags(html_entity_decode($post["txt"]));
                    $res = $model->updateProfile();
                    return ['statusCode'=>true, 'username'=>$username];
                }else{
                    return ['statusCode'=> false, 'parameters' => $model->errors, 'user'=> 'error'];
                }
            }
            return $this->renderAjax('user/profile', [
                'model' => $model, 'username'=>$username, 'products' => $products
            ]);
        }
        return $this->render('user/profile', [
            'model' => $model, 'username'=>$username,
            'products' => $products, 'pagination' => $pagination,
            'sale_products' => $sale_products, 'rent_products' => $rent_products
        ]);
    }

    public function actionUpdateProfile($username)
    {
        if (Yii::$app->user->isGuest) {
            return $this->redirect(Url::to(['member/login']));
        }

        if($username == Yii::$app->user->identity->username) {

            if(Yii::$app->request->isAjax){
                Yii::$app->response->format = Response::FORMAT_JSON;
                $post = Yii::$app->request->post();
                if($post){
                    $scenario = trim($post["scenario"]);
                    $model = Yii::createObject([
                        'class'    => ProfileForm::className(),
                        'scenario' => $scenario,
                    ]);
                    $model = $model->loadProfile($username, $scenario);
                    $model->load($post);
                    $model->validate();
                    if (!$model->hasErrors() ) {
                        $model->updateProfile();
                        return ['statusCode'=>200, 'modelResult'=>$model];
                    }else{
                        return ['statusCode'=>400, 'parameters' => $model->errors, 'user'=> 'error'];
                    }
                }
            }

            $model = Yii::createObject([
                'class'    => ProfileForm::className(),
                'scenario' => 'updateprofile',
            ]);
            $model = $model->loadProfile($username, 'updateprofile');
            return $this->render('user/updateProfile',['model'=> $model]);
        }

        return $this->redirect(Url::to(['member/login']));
    }

    public function actionUpdateUserLocation()
    {
        if (Yii::$app->user->isGuest) {
            return $this->redirect(Url::to(['member/login']));
        }

        $model = UserLocation::find()->where(['user_id' => Yii::$app->user->id])->one();
        if(empty($model))
            $model = new UserLocation();

        if(Yii::$app->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            $post = Yii::$app->request->post();
            if($post) {
                $model->user_id = Yii::$app->user->id;
                $model->city_id = isset($post["UserLocation"]["city_id"]) ? (int)$post["UserLocation"]["city_id"] : null;
                $model->validate();
                if (!$model->hasErrors() ) {
                    $model->save(false);
                    return ['statusCode' => 200, 'model' => $model];
                } else {
                    return ['statusCode' => 400, 'parameters' => $model->errors];
                }
            }
        }
        return ['statusCode' => 400];
    }

    public function actionPassword()
    {
        $this->checkAccess();
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
                    $model->resetPass();
                    return ['statusCode'=>200];
                }else{
                    return ['statusCode'=> 400, 'parameters' => $model->errors];
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



}
