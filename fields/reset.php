<?php

defined('_JEXEC') or die();

JHTML::_('behavior.modal','a.modal');

class JFormFieldReset extends JFormField
{
	var $type = 'reset';

	function getInput()
	{
		return '<a class="modal" title="Click here"  href="../plugins/system/bfacebooksqueeze/reset.php" rel="{handler: \'iframe\', size: {x: 650, y: 375}}"><button class="btn" onclick="return false">Click here</button></a>';

	} // getInput()

} // JFormFieldReset
