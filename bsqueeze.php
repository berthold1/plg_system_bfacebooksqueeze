<?php

defined('_JEXEC') or die;

jimport('joomla.plugin.plugin');
jimport('joomla.html.parameter');

class plgSystemBsqueeze extends JPlugin
{
	private $render = '';

	function onAfterDispatch()
	{
		$app = JFactory::getApplication();
		$document = $app->getDocument();

		// Only display the modal in the site section (nothing for admin)
		// If a cookie exists, we leave without displaying the modal
		if ($document->getType() !== 'html' || !$app->isSite() || JRequest::getString('tmpl') == 'component' || JRequest::getVar('bsqueeze_state', '', 'cookie', 'string'))
			return;

		// If on Mobile but not active for it
		if (!$this->params->get('dispmobile', 1) && $this->isMobile())
			return;

		$delaytimer = abs(intval($this->params->get('delaytimer', 0))) * 1000;
		$hidetimer = abs(intval($this->params->get('hidetimer', 0))) * 1000;
		$interval = intval($this->params->get('interval', 7)) * 3600 * 24;
		$width = abs(intval($this->params->get('boxwidth', 250)));
		$height = abs(intval($this->params->get('boxheight', 200)));
		$mid = trim($this->params->get('moduleid', 0), ',');

		setcookie('bsqueeze_state', true, time() + $interval, '/');

		if (!empty($mid)) {
			$db = JFactory::getDBO();
			$db->setQuery("SELECT * FROM `#__modules` WHERE id IN ($mid)");
			$modules = $db->loadObjectList();
			if (!empty($modules)) {
				// Load modal script
				JHTML::_('behavior.modal');
				$script = <<<EOD
window.addEvent('domready', function() { 
	setTimeout(function() {
		SqueezeBox.setOptions({size: {x: $width, y: $height}, classWindow: 'bsqueeze'});
		SqueezeBox.assignOptions();
		SqueezeBox.setContent('adopt', jQuery('#bsqueeze').detach().css('display', 'block'));
		if ($hidetimer > 0) {
			setTimeout(function() {
				SqueezeBox.close();
			}, $hidetimer);
		}
    }, $delaytimer);
});
EOD;
				$document->addScriptDeclaration($script);
				$params = array();
				$this->render = "<div id='bsqueeze' style='display: none;'>";
				foreach ($modules as $module) {
					$this->render .= "<div id='bsqueeze-{$module->id}'>".JModuleHelper::renderModule($module, $params)."</div>";
				}
				$this->render .= '</div>';
			}
		}
		return true;

	} // onAfterDispatch()

	function onAfterRender()
	{
		if (!empty($this->render)) {
			JResponse::appendBody($this->render);
		}
		return true;

	} // onAfterRender()

	private function isMobile()
	{
		//Check if it's a mobile devise or not to display another version of the squeeze box
		if (!class_exists('Mobile_Detect', false)) {
			include_once(dirname(__FILE__).DS.'mobile_detect.php');
		}
		$mobileClass = new Mobile_Detect();
		return $mobileClass->isMobile();

	} // isMobile()

} // class plgSystemBsqueeze