<?php
class SiteController extends Controller
{
    const ITEMS_BLOCK_1 = 6;
    const ITEMS_BLOCK_2 = 6;

    public $layout='base';

    public static function getTypes()
    {
        return array(
            Institution::TYPE_VUZ => array (
                'title' => 'Лучшие ВУЗы Екатеринбурга',
                'listTitle' => 'Все ВУЗы Екатеринбурга',
                'limit' => 6,
                'listLimit' => 8,
                'alias' => 'vuz'),
            Institution::TYPE_COLLEGE => array (
                'title' => 'Лучшие колледжи и техникумы Екатеринбурга',
                'listTitle' => 'Все колледжи Екатеринбурга',
                'limit' => 6,
                'listLimit' => 8,
                'alias' => 'college'),
            Institution::TYPE_LINGVO => array (
                'title' => 'Лучшие языковые центры Екатеринбурга',
                'listTitle' => 'Все языковые центры Екатеринбурга',
                'limit' => 3,
                'listLimit' => 8,
                'alias' => 'linvo'),
            Institution::TYPE_BUSINESS => array (
                'title' => 'Лучшее бизнес-образование Екатеринбурга',
                'listTitle' => 'Бизнес образование в Екатеринбурге',
                'listLimit' => 8,
                'limit' => 3, 'alias' => 'bo'),
            Institution::TYPE_ADDITIONAL => array (
                'title' => 'Лучшее дополнительное образование Екатеринбурга',
                'listTitle' => 'Дополнительное образование в Екатеринбурге',
                'limit' => 3,
                'listLimit' => 8,
                'alias' => 'do'),
        );
    }

    public function actionIndex()
    {
        $types = self::getTypes();

        // весь список
        $itemsArray = array();        

        foreach ($types as $key => $value) {
            $limit = 100;
            $offset = 0;
            $criteria = new CDbCriteria(array(
                'limit' => $value['listLimit'],
                'offset' => $offset*$limit,
            ));
            $itemsTotal[$key] = Institution::model()->onSite()->byType($key)->count();
            $items = Institution::model()->onSite()->byType($key)->orderDefault()->findAll($criteria);
            foreach ($items as $item) {
                $itemsArray[$key][] = array(
                    'id' => $item->id,
                    'title' => $item->title,
                    'fullTitle' => $item->fullTitle,
                );
            }
        }

        $imgBlocks = array();

        foreach ($types as $key => $value) {
            $model = new Institution;            
            $criteria = new CDbCriteria(array(
                'limit' => $value['limit'],
            ));
            $imgBlocks[$key] = Institution::model()->onSite()->byType($key)->orderPriority()->findAll($criteria);
        }

        // новости
        $news = Yii::app()->cache->get('mainNews');

        $this->setPageTitle('Университеты, колледжи и институты Екатеринбурга &mdash; '.Yii::app()->params['siteName']);
        $this->render('main', array(
            'itemsTotal' => $itemsTotal,
            'list' => $itemsArray,
            'imgBlocks' => $imgBlocks,
            'types' => $types,
            'news' => $news,
        ));
    }

    public function actionType()
    {
        $typeParam = isset($_GET['type']) ? (string)$_GET['type'] : false;
        if (!$typeParam) {
            throw new CHttpException(404, 'Страница не найдена');
        }
        $types = self::getTypes();
        $type = false;
        foreach ($types as $key => $value) {
            if ($types[$key]['alias'] == $typeParam) {
                $type = $types[$key];
                break;
            }
        }

        if (!$type) {
            throw new CHttpException(404, 'Страница не найдена');
        }

        $itemsArray = array();        
        $limit = 100;
        $offset = 0;
        $criteria = new CDbCriteria(array(
            'limit' => $limit,
            'offset' => $offset*$limit,
        ));
        $items = Institution::model()->onSite()->byType($key)->orderDefault()->findAll($criteria);
        while ($items) {
            foreach ($items as $item) {
                $itemsArray[] = array(
                    'id' => $item->id,
                    'title' => $item->title,
                    'fullTitle' => $item->fullTitle,
                );
            }
            $offset++;
            $criteria = new CDbCriteria(array(
                'limit' => $limit,
                'offset' => $offset*$limit,
            ));
            $items = Institution::model()->onSite()->byType($key)->orderDefault()->findAll($criteria);
        }

        foreach ($itemsArray as $item) {
            $keys[mb_strtoupper(mb_substr($item['title'], 0, 1, 'UTF8'), 'UTF8')]['rows'][] = $item;
        }
        $col_num = 3;
        $rows_in_col = ceil(sizeof($itemsArray)/$col_num);
        $col = 0;
        $rows = array();
        $cur_key = false;
        $cur_count = 0;
        foreach ($keys as $k=>$row) {
            $rows[$col][$k] = $row;
            $cur_count += sizeof($row['rows']);
            if ($cur_count>=$rows_in_col) {
                $col++;
                $cur_count = 0;
            }
        }


        $this->setPageTitle($type['title'] . '. Полный список учебных заведений &mdash; '.Yii::app()->params['siteName']);
        $this->render('typeList', array(
            'rows' => $rows,
            'title' => $type['title'],
        ));
    }

    public function actionshowCard()
    {
        $id = isset($_GET['id']) ? (int)$_GET['id'] : false;
        if (!$id) {
            throw new CHttpException(404, 'Страница не найдена');
        }

        $item = Institution::model()->findByPk($id);
        if (!$item) {
            throw new CHttpException(404, 'Страница не найдена');
        }

        $this->setPageTitle($item->getFullTitle().'. Список документов для поступления, расписание, новости высшего учебного заведения в Екатеринбурге &mdash; '.Yii::app()->params['siteName']);
        $this->render('card', array(
            'item' => $item,
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