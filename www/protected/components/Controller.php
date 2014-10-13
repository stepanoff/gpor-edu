<?php
/**
 * Controller is the customized base controller class.
 * All controller classes for this application should extend from this base class.
 */
class Controller extends VController
{
    protected $data = array();
    public $pageDescription = '';
    public $seoText = '';
    public $mainPage = false;
    public $crumbs = array();

    public function getData($key)
    {
        if (isset($this->data[$key]))
            return $this->data[$key];
        return false;
    }

	public function setData($key, $value)
    {
        $this->data[$key] = $value;
    }

    public function beforeRender($view)
    {
        return parent::beforeRender($view);
    }
}