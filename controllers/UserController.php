<?phpnamespace app\controllers;use Yii;use app\models\UserRegisterForm;use app\models\UserSigninForm;use app\models\UserEditpermissionsForm;use app\models\UserUpdateBasicinfoForm;//use app\models\UserbasicinfoSearch;use yii\web\Controller;use yii\web\NotFoundHttpException;use yii\filters\VerbFilter;use app\helpers\Tool;class UserController extends Controller{	//发送验证码	public function actionCheckphone()	{		$phone = Yii::$app->request->get('phone');				if ( strlen( $phone ) == 11 ){			$model = new UserRegisterForm;			return $model->makeicode( $phone );		}				return Tool::return_json( false, 'phone', '手机号码不正确' );	}		/*	 * 注册	 */	public function actionRegister()	{				$model = new UserRegisterForm;				if ($model->load(Yii::$app->request->post()) && $model->validate()) {			return $model->regitser();		} else {			//回馈到ajax的错误数组			return Tool::return_json( false, $model->getFirstErrors() );        }	}		/*	 * 登录	 */	public function actionSignin(){		//为了节省查询数据库登录的次数，如果用户本地过期，只要服务器没过期，直接根据session登录。		$session = Yii::$app->session;				$post = Yii::$app->request->post();				// 由于session过期时间bug，暂时使用注释session快捷登录		// if( !empty($session['user']) 		// && $session['user']['phone']==$post['UserSigninForm']['phone'] 		// && password_verify($post['UserSigninForm']['password'], $session['user']['password']) ){			// return Tool::return_json( true, 'user', array_diff_assoc($session['user'], ['password' => $session['user']['password']]) );		// }				$model = new UserSigninForm;				if( $model->load($post) && $model->validate() ) {			return $model->signin();		} else {			//回馈到ajax的错误数组			return Tool::return_json( false, $model->getFirstErrors() );        }	}		/*	 * 成为魅客	 */	public function actionAsmaker(){				$phone = Yii::$app->request->get('phone');				$model = new UserEditpermissionsForm;				$model->phone = $phone;				if ( $model->validate() ) {			return $model->permissionsToMaker();		}				return Tool::return_json( false, $model->getFirstErrors() );			}		/*	 * 修改基本信息	 */	public function actionUpdatebasicinfo(){				$session = Yii::$app->session;				if( !isset($session['user']) || empty($session['user']) ){			return Tool::return_json( false, 'user', '请登录后操作' );		}				$model = new UserUpdateBasicinfoForm;				if ($model->load(Yii::$app->request->post()) && $model->validate()) {			$model->mask = '';			if( !empty($_FILES['mask']) ){				$model->mask = $_FILES['mask'];			}			return $model->updatebasicinfo();		}				//回馈到ajax的错误数组		return Tool::return_json( false, $model->getFirstErrors() );			}		}