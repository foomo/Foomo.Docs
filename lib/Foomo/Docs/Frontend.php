<?php

namespace Foomo\Docs;

use Foomo\MVC\AbstractApp;
use Foomo\Modules\ModuleAppInterface;

/**
 * module app
 */
class Frontend extends AbstractApp implements ModuleAppInterface {
	public function __construct()
	{
		parent::__construct();
		\Foomo\HTMLDocument::getInstance()->addStylesheets(array(
			\Foomo\ROOT_HTTP . '/modules/' . Module::NAME . '/css/module.css'
		));
	}
}