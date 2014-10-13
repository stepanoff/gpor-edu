<?php
class SiteController extends Controller
{
    public $layout='base';

    public function actionIndex()
    {
        $this->setPageTitle(''.Yii::app()->params['siteName']);
        $this->render('main', array(
        ));
    }

    public function actionLogin () {
        $ajax = Yii::app()->request->isAjaxRequest;

		$service = Yii::app()->request->getQuery('service');
		if (isset($service)) {
            try {
                $authIdentity = Yii::app()->vauth->getIdentity($service);
                if ($authIdentity->authenticate()) {

                    if (isset($_GET['nopopup']))
                    {
                        $rUrl = isset($_GET['returnUrl']) ? $_GET['returnUrl'] : Yii::app()->user->returnUrl;
                        $rUrl .= (strstr('?', $rUrl) ? '&' : '?').'error='.urlencode($service);
                        $authIdentity->cancelUrl = $rUrl;
                    }
                    else
                        $authIdentity->cancelUrl = '#error:'.$service;

                    $identity = new VAuthUserIdentity($authIdentity);

                    // успешная авторизация
                    $rememberMe = true;
                    if ($identity->authenticate()) {
                        Yii::app()->user->login($identity, $rememberMe);
                        if (isset($_GET['nopopup']))
                        {
                            $returnUrl = isset($_GET['returnUrl']) ? $_GET['returnUrl'] : Yii::app()->user->returnUrl;
                            $this->redirect($returnUrl);
                            Yii::app()->end();
                            /*
                            $rUrl = isset($_GET['redirectUrl']) ? $_GET['redirectUrl'] : Yii::app()->user->returnUrl;
                            $rUrl .= (strstr('?', $rUrl) ? '&' : '?').'auth_token='.urlencode(Yii::app()->user->generateTemporaryToken());
                            $rUrl .= '&returnUrl='.urlencode($returnUrl);
                            $authIdentity->redirectUrl = $rUrl;
                            */
                        }
                        else
                            $authIdentity->redirectUrl = '#reload:1';

                        if ($ajax)
                        {
                            $data = array ('success' => true, 'redirect_url' => $authIdentity->getRedirectUrl());
                            echo CJSON::encode($data);
                            die();
                            Yii::app()->end();
                        }
                        else
                        {
                            // специальное перенаправления для корректного закрытия всплывающего окна
                            $authIdentity->redirect();
                        }
                    }
                    else {
                        // закрытие всплывающего окна и перенаправление на cancelUrl
                        $authIdentity->cancel();
                    }
                }
                die();
                // авторизация не удалась, перенаправляем на страницу входа
                if (!$ajax)
                    $this->redirect(array('site/login'));
                else
                    Yii::app()->vauth->cancel(false);
            }
            catch (EAuthException $e)
            {
                $error = $e->getMessage();
                $code = $e->getCode();
                if ($ajax)
                {
                    $data = array(
                        'error' => array (
                            'code' => $code,
                            'message' => $error,
                        )
                    );
                    echo CJSON::encode($data);
                    die();
                    Yii::app()->end();
                }
                else
                {
                    $data = array(
                        'error' => array (
                            'code' => $code,
                            'message' => $error,
                        )
                    );
                    if (isset($_GET['returnUrl']))
                    {
                        $url = $_GET['returnUrl'];
                        $url .= (strstr('?', $url) ? '&' : '?').'error='.urlencode($data['error']['message']);
                        $this->redirect($url);
                        die();
                        Yii::app()->end();
                    }
                }
            }
		}

        $data = array(
            'error' => array (
                'code' => 500,
                'message' => 'Ошибка аутентификации. Сервис не найден',
            )
        );
        echo CJSON::encode($data);
        die();
        Yii::app()->end();
    }


    public function actionRegister () {

    }

    public function actionLogout () {
    }

    public function actionForgetPass () {
    }

    public function actionRegisterShop () {
    }

}