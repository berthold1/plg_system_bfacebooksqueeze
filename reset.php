<?php

define('_JEXEC', 1);

chdir('../../..');
define('JPATH_BASE', getcwd());
define('DS', DIRECTORY_SEPARATOR);
define('BFACEBOOKSQUEEZE_MEDIA', JPATH_BASE . DS . 'media' . DS . 'plg_bfacebooksqueeze');
require_once (JPATH_BASE . DS . 'includes' . DS . 'defines.php');
require_once (JPATH_BASE . DS . 'includes' . DS . 'framework.php');

$app = &JFactory::getApplication('site');
setcookie('bfacebooksqueeze_state', '0', 0, '/');
echo '<div><p style="color:green">Cookie successfully removed</p></div>';
$app->close();