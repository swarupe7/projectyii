<?php

namespace frontend\controllers;

use app\models\Courses;
use app\models\Linking;
use frontend\models\ResendVerificationEmailForm;
use frontend\models\VerifyEmailForm;
use Yii;
use yii\base\InvalidArgumentException;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\LoginForm;
use Error;
use frontend\models\PasswordResetRequestForm;
use frontend\models\ResetPasswordForm;
use frontend\models\SignupForm;
use frontend\models\ContactForm;
use frontend\models\FormUsers;
use yii\data\Pagination;
use yii\web\Response;
use yii\web\NotFoundHttpException;
use yii\db\Query;
use yii\helpers\ArrayHelper;

/**
 * Site controller
 */
class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['logout', 'signup'],
                'rules' => [
                    [
                        'actions' => ['signup'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => \yii\web\ErrorAction::class,
            ],
            'captcha' => [
                'class' => \yii\captcha\CaptchaAction::class,
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return mixed
     */
    public function actionIndex()
    {
        return $this->render('index');
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

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }

        $model->password = '';

        return $this->render('login', [
            'model' => $model,
        ]);
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
     * Displays contact page.
     *
     * @return mixed
     */
    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail(Yii::$app->params['adminEmail'])) {
                Yii::$app->session->setFlash('success', 'Thank you for contacting us. We will respond to you as soon as possible.');
            } else {
                Yii::$app->session->setFlash('error', 'There was an error sending your message.');
            }

            return $this->refresh();
        }

        return $this->render('contact', [
            'model' => $model,
        ]);
    }

    /**
     * Displays about page.
     *
     * @return mixed
     */
    public function actionAbout()
    {
        return $this->render('about');
    }

    /**
     * Signs user up.
     *
     * @return mixed
     */
    public function actionSignup()
    {
        $model = new SignupForm();
        if ($model->load(Yii::$app->request->post()) && $model->signup()) {
            Yii::$app->session->setFlash('success', 'Thank you for registration. Please check your inbox for verification email.');
            return $this->goHome();
        }

        return $this->render('signup', [
            'model' => $model,
        ]);
    }

    /**
     * Requests password reset.
     *
     * @return mixed
     */
    public function actionRequestPasswordReset()
    {
        $model = new PasswordResetRequestForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail()) {
                Yii::$app->session->setFlash('success', 'Check your email for further instructions.');

                return $this->goHome();
            }

            Yii::$app->session->setFlash('error', 'Sorry, we are unable to reset password for the provided email address.');
        }

        return $this->render('requestPasswordResetToken', [
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
    public function actionResetPassword($token)
    {
        try {
            $model = new ResetPasswordForm($token);
        } catch (InvalidArgumentException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->resetPassword()) {
            Yii::$app->session->setFlash('success', 'New password saved.');

            return $this->goHome();
        }

        return $this->render('resetPassword', [
            'model' => $model,
        ]);
    }

    /**
     * Verify email address
     *
     * @param string $token
     * @throws BadRequestHttpException
     * @return yii\web\Response
     */
    public function actionVerifyEmail($token)
    {
        try {
            $model = new VerifyEmailForm($token);
        } catch (InvalidArgumentException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }
        if (($user = $model->verifyEmail()) && Yii::$app->user->login($user)) {
            Yii::$app->session->setFlash('success', 'Your email has been confirmed!');
            return $this->goHome();
        }

        Yii::$app->session->setFlash('error', 'Sorry, we are unable to verify your account with provided token.');
        return $this->goHome();
    }

    /**
     * Resend verification email
     *
     * @return mixed
     */
    public function actionResendVerificationEmail()
    {
        $model = new ResendVerificationEmailForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail()) {
                Yii::$app->session->setFlash('success', 'Check your email for further instructions.');
                return $this->goHome();
            }
            Yii::$app->session->setFlash('error', 'Sorry, we are unable to resend verification email for the provided email address.');
        }

        return $this->render('resendVerificationEmail', [
            'model' => $model
        ]);
    }

    public function actionTrial(){
        return $this->render('trial');
    }

   


    // public function actionForm(){
    //     $model=new FormUsers();
    //     if(Yii::$app->request->isAjax){
    //         $postData=Yii::$app->request->post();
    //         print_r($postData);
    //         if (isset($postData['FormUsers']['hobbies']) && is_array($postData['FormUsers']['hobbies'])) {
    //             $postData['FormUsers']['hobbies'] = implode(',', $postData['FormUsers']['hobbies']);
    //         }
    //         $model->attributes =  $postData['FormUsers'];
    //         if ($model->validate()) {
    //             if($model->save()){
    //                 return json_encode([
    //                     "success"=>true,
    //                     "message" => "User successfully added",
    //                 ]);
    //             }else{
    //                 return json_encode([
    //                     "error"=>true,
    //                     "message"=> 'not saved'//$model->errors,
    //                 ]);
    //             }
    //         }else{
    //             return json_encode([
    //                      "error"=>true,
    //                     "message"=> 'not valid'//$model->errors, 
    //                 ]);
    //         }
            
    //     }
            
    //     return $this->render('form',['model'=>$model]);
    // }

    public function actionTable(){

        if($postData = Yii::$app->request->post()){

            if(isset($postData['type'])){
                $models = FormUsers::find()->filterWhere(['=','firstname',$postData['name']]);
            }else{
                $models = FormUsers::find();
            }

            $pagination=new Pagination([
                'defaultPageSize'=>12,
                'totalCount'=>$models->count(),
            ]);
    
            $models = $models->offset($pagination->offset)->limit($pagination->limit)->all();

            return $this->render('table', ['pagination' => $pagination,'models' => $models]);

        }else{
            $models = FormUsers::find();
        }
     
        $pagination=new Pagination([
            'defaultPageSize'=>12,
            'totalCount'=>$models->count(),
        ]);

        $models = $models->offset($pagination->offset)->limit($pagination->limit)->all();

        return $this->render('table', ['pagination' => $pagination,'models' => $models]);
    }


    public  function actionDelete(){
        Yii::$app->response->format=Response::FORMAT_JSON;
        $id=Yii::$app->request->post('id');
        if(FormUsers::findOne($id)!==null){
            if(FormUsers::findOne($id)->delete()){
                return ['success'=>true, 'message'=>'Delete successfully'];
            }
        }
        return ['error'=>true, 'message'=>'Failed to delete'];
    }


    public function actionUpdate($id)
{        
    $model = FormUsers::findOne($id);
    if (!$model) {
        throw new NotFoundHttpException("User not found");
    }

    if (Yii::$app->request->isAjax) {
        $postData = Yii::$app->request->post();
        if (isset($postData['FormUsers']['hobbies']) && is_array($postData['FormUsers']['hobbies'])) {
            $postData['FormUsers']['hobbies'] = implode(',', $postData['FormUsers']['hobbies']);
        }
        $model->attributes = $postData['FormUsers'];
        if ($model->validate() && $model->save()) {
            return json_encode([
                "success" => true,
                "message" => "User successfully updated",
            ]);
        } else {
            return json_encode([
                "error" => true,
                "message" => 'Update failed', // $model->errors
            ]);
        }
    }

    return $this->renderAjax('update', ['model' => $model]);
}


public function actionFetch($id){  
    $model = FormUsers::findOne($id);

    if (!$model) {
        return $this->asJson([
            "success" => false,
            "message" => 'No Data found',
        ]);
       
    }
     return $this->asJson([
        "success" => true,
        "message" => [
            "firstname" => $model->firstname,
            "lastname" => $model->lastname,
            "email" => $model->email,
            "number" => $model->number,
            "study" => $model->study,
            "hobbies" => $model->hobbies,
            "gender" => $model->gender,
        ],
    ]);

}

// public function actionSearch($name){
//   if(isset($name)){
//     // $data= FormUsers::find()->where(['firstname'=>$name])();

//     $data=(new Query())->from('students')->filterWhere(['=','firstname',$name])->all();

    

//     if(!$data){
//         return $this->asJson([
//             "success"=>false,
//             "message"=>"Not found ",
//         ]);


//     }

//     return $this->asJson([
//         "success"=>true,
//         "data"=> $data
//         ]);

//    // return $this->renderAjax('search',['data'=>$data]);
//   }

// }


public function actionForm($id = null)
{
    $model = $id === null ? new FormUsers() : FormUsers::findOne($id);

    if (Yii::$app->request->isAjax) {
        $postData = Yii::$app->request->post();
        if (isset($postData['FormUsers']['hobbies']) && is_array($postData['FormUsers']['hobbies'])) {
            $postData['FormUsers']['hobbies'] = implode(',', $postData['FormUsers']['hobbies']);
        }
        $model->attributes = $postData['FormUsers'];
        if ($model->validate()) {
            if ($model->save()) {
                return json_encode([
                    "success" => true,
                    "message" => $id === null ? "User successfully added" : "User successfully updated",
                ]);
            } else {
                return json_encode([
                    "error" => true,
                    "message" => 'not saved', // $model->errors
                ]);
            }
        } else {
            return json_encode([
                "error" => true,
                "message" => 'not valid', // $model->errors
            ]);
        }
    }

    return $this->render('form', ['model' => $model]);
}


public function actionDataTable(){

   // $result= Courses::find()->asArray()->all();
   
    // $result=$model::find()->where(['firstname'=>'swarup'])->all();

    //$result=$model::find()->where(['number'=>'1234567890'])->andWhere(['id'=>'31'])->all();
    
    //  $result = Courses::find()->all();

    // $students = FormUsers::find();

    //$result = Linking::find()->where(['student_id'=>2, 'course_id'=>1])->all(); 

    //$result = Linking::find()->where([ 'course_id'=>1])->all();
    
    //  $result = (new Query())
    //  ->from('students')
    //  ->innerJoin('linking','linking.student_id = students.id')
    //  ->innerJoin('courses' ,'courses.id = linking.course_id')
    //  ->where(['course'=>'MECH'])
    //  ->all();

    // $query2=(new Query())->select(['firstname'])->from('students')->where(['gender'=>'female'])->limit(2);

    // $query3 = $query1->union($query2)->all();

    // $array = [
    //     'foo' =>'lakshman', [
    //         'bar' => "Arjun",
    //     ]
    // ];

    // ArrayHelper::getValue($array,'foo.bar');

    // ArrayHelper::setValue($array,'foo.bar','karnaa');

    // $result=ArrayHelper::getValue($array,'foo.bar');

    // ArrayHelper::setValue($array,'foo.bar','karnaa');

    // $value=ArrayHelper::getValue($array,'foo.bar');
    // ArrayHelper::setValue($array,'foo.lakshman','Parusurama');
    // ArrayHelper::setValue($array,'fool',"foller");

    // $resulr=ArrayHelper::getColumn($array,'foo');


    // $array = [
    //     ['id' => '123', 'data' => 'abc', 'device' => 'laptop'],
    //     ['id' => '345', 'data' => 'abc', 'device' => 'tablet'],
    //     ['id' => '345', 'data' => 'hgi', 'device' => 'smartphone'],
    // ];

    // // $resulr =ArrayHelper::index($array,'data');

    // $resulr=ArrayHelper::map($array,'id','data',"device");

    $db=Yii::$app->db;

    $command=$db->createCommand('select * from courses')->queryAll();

    $data = [
        ['age' => 30, 'name' => 'Alexander'],
        ['age' => 30, 'name' => 'Brian'],
        ['age' => 19, 'name' => 'Barney'],
    ];

    $resulr=ArrayHelper::multisort($data,['name','age'],[SORT_ASC,SORT_DESC]);


    return $this->render('datatable', ['mode'=>$command]);
}
    

}
