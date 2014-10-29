<?php
class VAdminMenuWidget extends CWidget {

    public $items = false;
    public $uri = false;
    public $baseLink = false;

    public function run() {
		parent::run();

        $items = $this->items;

		$this->render('menu', array(
            'baseLink' => $this->baseLink,
            'items' => $items,
		));
    }

}
