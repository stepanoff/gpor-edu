<?php
/**
 * GporAuth class file.
 *
 * @author stepanoff <stenlex@gmail.com>
 */

/**
 * The GporAuth class provides simple authentication via OpenID and OAuth providers.
 * @package application.extensions.eauth
 */
class GporAuth extends EAuth {

    protected $_fullServices = null;

	
	public function getServices() {
		//if (Yii::app()->hasComponent('cache'))
		//	$services = Yii::app()->cache->get('EAuth.services');
		if (!isset($services) || !is_array($services)) {
			$services = array();
			foreach ($this->services as $service => $options) {
				$class = $this->getIdentity($service);
				$services[$service] = (object) array(
					'id' => $class->getServiceName(),
					'title' => $class->getServiceTitle(),
					'type' => $class->getServiceType(),
					'jsArguments' => $class->getJsArguments(),
				);
			}
			//if (Yii::app()->hasComponent('cache'))
			//	Yii::app()->cache->set('EAuth.services', $services);
		}
		return $services;
	}

    /**
     * Returns services classes declared in the authorization classes.
     * @return array services settings.
     */
    public function getFullServices() {
        if ($this->_fullServices === null) {
            $this->_fullServices = array();
            foreach ($this->services as $service => $options) {
                $this->_fullServices[$service] = $this->getIdentity($service);
            }
        }
        return $this->_fullServices;
    }

    public function getServiceClassName($service) {
        $services = $this->services;
        if (isset($services[$service]))
        {
            return $services[$service]['class'];
        }
        return false;
    }


	public function redirect($url, $jsRedirect = true) {
		require_once dirname(__FILE__).DIRECTORY_SEPARATOR.'GporAuthRedirectWidget.php';
		$widget = Yii::app()->getWidgetFactory()->createWidget($this, 'GporAuthRedirectWidget', array(
			'url' => $url,
			'redirect' => $jsRedirect,
		));
		$widget->init();
		$widget->run();
	}

    public function cancel($url, $jsRedirect = true) {
        $error = array (
                    'code' => 500,
                    'message' => 'Ошибка авторизации. Попробуйте еще раз позже',
        );
        throw new EAuthException($error['message'], $error['code']);
    }

//	public function renderWidget($properties = array()) {
//		require_once dirname(__FILE__).DIRECTORY_SEPARATOR.'GporAuthWidget.php';
//		$widget = Yii::app()->getWidgetFactory()->createWidget($this, 'GporAuthWidget', $properties);
//		$widget->init();
//		$widget->run();
//	}

    public function getServiceTemplate ($service)
    {
        $services = $this->getFullServices();
        if (!isset($services[$service]))
            throw new EAuthException(Yii::t('eauth', 'Undefined service name: {service}.', array('{service}' => $service), 'en'), 500);

        if (method_exists($services[$service], 'getCustomTemplate'))
               return $services[$service]->getCustomTemplate();
        return $this->getBaseTemplatePath ().'service_item';
    }

    protected function getBaseTemplatePath ()
    {
        return 'ext.gporauth.views.services.';
    }

}