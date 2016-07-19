<?php

namespace app\controllers;

use Yii;

use yii\web\Controller;

class HomeController extends Controller
{
	public $layout = false;
	
	public function actionCsrf()
	{
		return Yii::$app->request->csrfToken;
	}
}