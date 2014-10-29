<?phpclass VAdminController extends VController{	public $onPageCount = 20;		public $model;		public $title; 		public $_sessionKey = null;		public $enableAjaxValidation = false;		public $_session = null;		public $_layoutFilters;	    public $route = ''; // default controller route	private $__session = null;    private $_adminModule = null;		public function filters()	{		return array( 			'accessControl'		);	}    public function getAdminModule () {        if ($this->_adminModule === null) {            $this->_adminModule = Yii::app()->getModule('VAdmin');        }        return $this->_adminModule;    }	public function accessRules()	{        $roles = $this->getAdminModule()->getAdminRoles();        if ($roles) {            return array(                array('allow',                    'roles'=> $roles,                ),                array('deny',                    'users' => array('*'),                ),            );        }        return parent::accessRules();	}		public function init()	{        $this->layout = $this->getAdminModule()->getViewsAlias('layouts.base');		// Инициируем сессию		$this->_sessionKey = $this->getAdminModule()->getId().'_'.$this->id;        return parent::init();	}		public function templates()	{		$res = array(            'table' => $this->getAdminModule()->getViewsAlias('admin.table'),            'list' => $this->getAdminModule()->getViewsAlias('admin.list'),            'edit' => $this->getAdminModule()->getViewsAlias('admin.edit'),        );        return $res;	}		public function appendLayoutFilters($model, $form)	{		return $model;	}			public function layoutsFilters()	{		return array();	}		protected function editFormElements ($model)	{		return array();    }    public function getRoute () {        return $this->route;    }    public function getListRoute () {        return $this->route.'/index';    }	public function actionList()	{        $filterForm = new VAdminFilterForm;        $filterForm->setElements($this->layoutsFilters());        $cFilterForm = new VFormRender(array());        $cFilterForm->model = $filterForm;        $cFilterForm->method = 'get';        $cFilterForm->action = CHtml::normalizeUrl(array($this->getListRoute()));        if ($cFilterForm->submitted('submit')) {}        $model = $this->appendLayoutFilters(CActiveRecord::model($this->model), $cFilterForm);        $dataProvider = new CActiveDataProvider($this->model, array(            'criteria' => $model->getDbCriteria()->toArray(),            'pagination'=>array(                'pageSize' => $this->onPageCount,            ),        ));        $columns = $this->getListColumns();        $widget = $this->createWidget('VAdminGridWidget', array(            'dataProvider' => $dataProvider,            'columns' => $columns,            'filters' => array('form' => $cFilterForm),            'route' => $this->getListRoute(),            'beforeListContent' => $this->beforeListContent($dataProvider),            'afterListContent' => $this->afterListContent($dataProvider),        ));		if (Yii::app()->request->isAjaxRequest)		{            $renderedParts = $widget->renderParts();            foreach ($renderedParts as $k => $content) {                $this->setAjaxData($k, $content);            }		}		else {            $templates = $this->templates();            $tplData = array('widget' => $widget);            $tablePart = $this->renderPartial($templates['table'], $tplData, true);            $pageData = array('table' => $tablePart);            $this->render($templates['list'], $pageData);        }	}		public function actionIndex ()	{		return $this->actionList();	}		public function beforeListContent ($list = array())	{        return CHtml::link('Добавить', array($this->getRoute().'/edit'), array('class' => 'btn btn-primary'));	}	    public function afterListContent ($list = array())    {        return '';    }	protected function buildModel ($id = false)	{		if ($id)			return CActiveRecord::model($this->model)->findByPk($_GET['id']);		else			return new $this->model;	}		public function actionEdit($render = true)	{		if (isset($_GET['id']))		{			if (!$model = $this->buildModel($_GET['id']))				$this->redirect(array('list'));			$isNewRecord = false;		}		else		{			$model = $this->buildModel();			$isNewRecord = true;		}		$model->setScenario('admin');				$model = $this->beforeEdit ($model, $isNewRecord);        $form = $this->createForm($model);		if ($form->submitted('submit'))		{            $model = $form->model;			if ($model->validate())			{				if ($this->enableAjaxValidation && Yii::app()->request->isAjaxRequest)				{					$this->setAjaxData('success', true);				}				else 				{					if ($model = $this->save($model))					{						if ($isNewRecord)							$this->afterAdd($model);						else							$this->afterEdit($model);					}											if ($render)						$this->redirect(array('list'));					else						return $model;				}			}			elseif ($this->enableAjaxValidation && Yii::app()->request->isAjaxRequest)			{				$result = array();				foreach($model->getErrors() as $attribute=>$errors)		            $result[CHtml::activeId($model,$attribute)]=$errors[0];				$this->setAjaxData('errors', $result);			}			elseif (!$render)				return $model;		}					if ($render && !($this->enableAjaxValidation && Yii::app()->request->isAjaxRequest) )		{			$additionalEditData = $this->getAdditionalEditData($model, $isNewRecord);            $templates = $this->templates();            $this->render($templates['edit'], array_merge(array('form' => $form), $additionalEditData));		}		else 			return $model;	}    protected function createForm ($model) {        $elements = array(            'elements' => array(),            'enctype' => 'multipart/form-data',            'elements' => $this->getFormElements($model),            'buttons' => $this->getButtons($model),        );        $form = new VFormRender($elements);        $form->model = $model;        return $form;    }	public function getAdditionalEditData($model, $isNewRecord)	{		return array();	}		public function actionDelete()	{		if (isset($_GET['id']) && $model = CActiveRecord::model($this->model)->findByPk($_GET['id']))		{			if ($this->beforeDelete($model))			{				if (isset($_REQUEST['confirm']))				{					if ($model = $this->delete($model))					{						$this->afterDelete($model);					}											$this->actionList();				}				else				{					Yii::app()->informer->confirm(						array('title' => 'Удалить объект?', 'text' => 'Вы действительно хотите удлаить этот объект?'),						CHtml::normalizeUrl(array('delete', 'id' => $_GET['id']))					);				}			}		}		else			$this->actionList();	}    public function actionShow () {        if (isset($_GET['id']) && $model = CActiveRecord::model($this->model)->findByPk($_GET['id']))        {            $model->setScenario('admin');            $model->visibleOn();            $this->actionList();        }        else            $this->actionList();    }	    public function actionHide () {        if (isset($_GET['id']) && $model = CActiveRecord::model($this->model)->findByPk($_GET['id']))        {            $model->setScenario('admin');            $model->visibleOff();            $this->actionList();        }        else            $this->actionList();    }    public function actionSetStatus () {        $status = isset($_GET['status']) ? $_GET['status'] : null;        if ($status !== null && isset($_GET['id']) && ($model = CActiveRecord::model($this->model)->findByPk($_GET['id'])) )        {            $model->setScenario('admin');            $model->changeStatus($status);            $this->actionList();        }        else            $this->actionList();    }	protected function save ($model)	{		$model->save();		return $model;	}		protected function delete ($model)	{		$model->delete();		return $model;	}		public function afterAdd($model) { }			public function afterEdit($model) { }		public function afterDelete($model) { }		public function beforeDelete($model) { return true; }		public function beforeEdit ($model, $isNewRecord)	{		return $model;	}    protected function registerAssets () {    }    public function getListColumns() {        return array(            'name',            array(                'class'=>'VAdminButtonWidget',            ),        );    }    public function getFormElements($model) {        return array();    }    public function getButtons($model) {        return array(            'submit' => array(                'type' => 'submit',                'label'=> 'Сохранить и выйти',                'class' => 'btn btn-large btn-primary'            ),            'submit_exit' => array(                'type' => 'submit',                'label'=> 'Сохранить',                'class' => 'btn btn-large'            ),        );    }}