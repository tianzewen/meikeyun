<?phpnamespace app\controllers;use Yii;use app\models\UserRegisterForm;use app\models\UserLoginForm;use yii\web\Controller;use yii\web\NotFoundHttpException;use yii\filters\VerbFilter;use app\helpers\Tool;class UserController extends Controller{	//发送验证码	public function actionCheckphone()	{		$phone = Yii::$app->request->get('phone');				if ( strlen( $phone ) == 11 ){			$model = new UserRegisterForm;			return $model->makeicode( $phone );		}				return Tool::return_json( false, 'phone', '手机号码不正确' );	}		/**	 * 注册	 */	public function actionRegister()	{				// $model = new Userbasicinfo;		// $model->scenario = 'register';				// if ($model->load(Yii::$app->request->post()) && $model->validate()) {			// return $model->register();		// } else {			// 回馈到ajax的错误数组			// return Tool::return_json( false, $model->getFirstErrors() );        // }	}		/**	 * 登录	 */	public function actionLogin()	{		$model = new UserLoginForm;				if( $model->load(Yii::$app->request->post()) && $model->validate() ) {			return $model->login();		} else {			//回馈到ajax的错误数组			return Tool::return_json( false, $model->getFirstErrors() );        }	}		/**	 * 注销	 */	public function actionLogout()	{		Yii::$app->user->logout();	}		/**	 * 成为魅客	 */	public function actionAsmaker(){				$phone = Yii::$app->request->get('phone');				$model = new UserEditpermissionsForm;				$model->phone = $phone;				if ( $model->validate() ) {			return $model->permissionsToMaker();		}				return Tool::return_json( false, $model->getFirstErrors() );			}		/*	 * 修改基本信息	 */	public function actionUpdatebasicinfo(){				$session = Yii::$app->session;				if( !isset($session['user']) || empty($session['user']) ){			return Tool::return_json( false, 'user', '请登录后操作' );		}				$model = new UserUpdateBasicinfoForm;				if ($model->load(Yii::$app->request->post()) && $model->validate()) {			$model->mask = '';			if( !empty($_FILES['mask']) ){				$model->mask = $_FILES['mask'];			}			return $model->updatebasicinfo();		}				//回馈到ajax的错误数组		return Tool::return_json( false, $model->getFirstErrors() );			}		}